<?php
namespace DomOperationQueue;

class DomOperationQueue implements DomOperationInterface
{

    /**
     * List of DOMOperation objects
     * @var \SplPriorityQueue
     */
    private $_operation_list = null;

    /**
     * Last automatic priority number
     *
     * @var integer
     */
    private $_last_auto_priority = PHP_INT_MAX;

    /**
     * Returns operation list object
     *
     * @return \SplPriorityQueue
     */
    protected function _getOperationList(): \SplPriorityQueue
    {
        if (empty($this->_operation_list)) {
            $this->_operation_list = new \SplPriorityQueue();
        }
        return $this->_operation_list;
    }

    /**
     * Returns last automatic priority number
     *
     * @return int
     */
    protected function _getLastAutoPriority(): int
    {
        return $this->_last_auto_priority;
    }

    /**
     * Returns next automatic priority number
     *
     * @return int
     */
    protected function _getNextAutoPriority(): int
    {
        return --$this->_last_auto_priority;
    }

    /**
     * Add DOM Operation object to list
     *
     * @param DomOperationInterface $operation
     * @throws \LogicException
     * @return \DomOperationQueue\DomOperationQueue
     */
    public function addDomOperation(DomOperationInterface $operation)
    {
        if ($operation instanceof DomOperationPriorityAwareInterface) {
            /**
             * @var DomOperationPriorityAwareInterface $operation
             */
            $priority = $operation->getDomOperationPriority();
        } else {
            $priority = $this->_getNextAutoPriority();
        }
        if ($priority > PHP_INT_MAX || $priority < 1) {
            throw new \LogicException('Invalid priority. Valid range: 1 - '.PHP_INT_MAX);
        }
        /**
         * @var DomOperationInterface $operation
         */
        $this->_getOperationList()->insert($operation, $priority);
        return $this;
    }

    /**
     * Remove DOM Operation object to list
     *
     * @param DomOperationInterface $operation
     * @throws \LogicException
     * @return \DomOperationQueue\DomOperationQueue
     */
    public function removeDomOperation(DomOperationInterface $operation)
    {
        //
        // thanks for original source code: https://gist.github.com/denisdeejay/1ee0ce70b3afe76cf31e
        //
        /**
         * @var \SplPriorityQueue $operation_list
         */
        $operation_list = $this->_getOperationList();
        $original_extract_flag = $operation_list->getExtractFlags();
        $this->_getOperationList()->setExtractFlags(\SplPriorityQueue::EXTR_BOTH);

        $new_list = [];
        foreach($operation_list as $item){
            if($item['data'] !== $operation){
                $new_list[] = $item;
            }
        }
        unset($item);

        $this->_getOperationList()->setExtractFlags($original_extract_flag);

        foreach($new_list as $item){
            $operation_list->insert($item['data'], $item['priority']);
        }
        unset($item);

        return $this;
    }

    public function executeDomOperation(\DOMDocument $dom_document): \DOMDocument
    {
        foreach (clone $this->_getOperationList() as $operation) {
            if (!$operation instanceof \DomOperationQueue\DomOperationInterface) {
                continue;
            }
            /**
             * @var DomOperationInterface $operation
             */
            $dom_document = $operation->executeDomOperation($dom_document);
        }

        return $dom_document;
    }

    /**
     * Execute operations on DOMDocument object
     *
     * @param \DOMDocument $dom_document
     * @return \DOMDocument
     */
    public function execute(\DOMDocument $dom_document): \DOMDocument
    {
        return $this->executeDomOperation($dom_document);
    }
}