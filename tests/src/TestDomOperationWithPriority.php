<?php
namespace DomOperationQueue\Tests;

use DomOperationQueue\DomOperationInterface;
use DomOperationQueue\DomOperationPriorityAwareInterface;

/**
 * Test DOM Operation class for phpunit tests
 *
 * @author Rácz Tibor Zoltán <racztiborzoltan@gmail.com>
 *
 */
class TestDomOperationWithPriority extends TestDomOperation implements DomOperationInterface, DomOperationPriorityAwareInterface
{

    /**
     * Test priority
     *
     * @var string
     */
    private $_priortiy = null;

    public function __construct(string $tag_name, string $content, int $priority)
    {
        $this->_priortiy = $priority;
        $content = $content . ' (PRIORITY: ' . $priority . ')';
        parent::__construct($tag_name, $content);
    }

    public function getDomOperationPriority(): int
    {
        return $this->_priortiy;
    }

    public function getPriority(): int
    {
        return $this->getDomOperationPriority();
    }

    public function setPriority(int $priority)
    {
        $this->_priortiy = $priority;
    }
}