<?php
declare(strict_types=1);

namespace DomOperationQueue\Tests;

use PHPUnit\Framework\TestCase;
use DomOperationQueue\DomOperationQueue;
use DomOperationQueue\DomOperationInterface;

final class DomOperationQueueTest extends TestCase
{

    private $_raw_xml_string = '<root></root>';

    /**
     * @var TestDomOperation[]
     */
    private $_test_operations = [];

    private $_expected_tag_name_order = [];

    /**
     *
     * @return \DOMDocument
     */
    protected function _factoryDomDocument(): \DOMDocument
    {
        $dom_document = new \DOMDocument();
        $dom_document->loadXML($this->_raw_xml_string);
        return $dom_document;
    }

    /**
     * Returns new DomOperationQueue instance
     *
     * @return DomOperationQueue
     */
    protected function _factoryDomOperationList(): DomOperationQueue
    {
        $list = new DomOperationQueue();

        $this->_test_operations['test_a'] = new TestDomOperationWithPriority('test_a', 'test A', 10);
        $this->_test_operations['test_b'] = new TestDomOperationWithPriority('test_b', 'test B', 5);
        $this->_test_operations['test'] = new TestDomOperation('test', 'test content');
        $this->_test_operations['test_c'] = new TestDomOperationWithPriority('test_c', 'test C', 15);
        $this->_test_operations['test_d'] = new TestDomOperationWithPriority('test_d', 'test D', 20);

        $this->_expected_tag_name_order = [
            $this->_test_operations['test']->getTagName(),
            $this->_test_operations['test_d']->getTagName(),
            $this->_test_operations['test_c']->getTagName(),
            $this->_test_operations['test_a']->getTagName(),
            $this->_test_operations['test_b']->getTagName(),
        ];

        foreach ($this->_test_operations as $operation) {
            $list->addDomOperation($operation);
        }

        return $list;
    }

    protected function _tagNameOrderIsCorrect(\DOMNodeList $nodes, array $tag_name_order): bool
    {
        if (count($tag_name_order) !== $nodes->length) {
            return false;
        }
        $tag_name_order = array_values($tag_name_order);
        foreach ($tag_name_order as $index => $expected_tag_name) {
            if ($index > count($tag_name_order) - 1
                || $nodes->item($index)->tagName !== $expected_tag_name
                ) {
                    return false;
                }
        }
        return true;
    }

    public function testList1()
    {
        $list = $this->_factoryDomOperationList();

        /**
         * @var \DOMDocument $dom_document
         */
        $dom_document = $this->_factoryDomDocument();
        $list->execute($dom_document);

        $this->assertTrue($this->_tagNameOrderIsCorrect($dom_document->firstChild->childNodes, $this->_expected_tag_name_order));
    }

    public function testList2()
    {
        $list = $this->_factoryDomOperationList();

        $list->removeDomOperation($this->_test_operations['test_c']);
        unset($this->_expected_tag_name_order[array_search($this->_test_operations['test_c']->getTagName(), $this->_expected_tag_name_order)]);

        /**
         * @var \DOMDocument $dom_document
         */
        $dom_document = $this->_factoryDomDocument();
        $list->execute($dom_document);

        $this->assertTrue($this->_tagNameOrderIsCorrect($dom_document->firstChild->childNodes, $this->_expected_tag_name_order));
    }

    public function testList3()
    {
        $list = $this->_factoryDomOperationList();

        // remove operation:
        $list->removeDomOperation($this->_test_operations['test_c']);

        // add new operation:
        /**
         * @var \DomOperationQueue\Tests\TestDomOperationWithPriority $test_operation_c2
         */
        $test_operation_c2 = clone $this->_test_operations['test_c'];
        $test_operation_c2->setTagName('test_c2');
        $test_operation_c2->setContent('test C2');
        $test_operation_c2->setPriority($this->_test_operations['test_c']->getPriority());
        $list->addDomOperation($test_operation_c2);
        // add removed operation:
        $list->addDomOperation($this->_test_operations['test_c']);

        array_splice($this->_expected_tag_name_order, array_search($this->_test_operations['test_c']->getTagName(), $this->_expected_tag_name_order), 0, [
            $test_operation_c2->getTagName()
        ]);

        /**
         * @var \DOMDocument $dom_document
         */
        $dom_document = $this->_factoryDomDocument();
        $list->execute($dom_document);

        $this->assertTrue($this->_tagNameOrderIsCorrect($dom_document->firstChild->childNodes, $this->_expected_tag_name_order));
    }
}