<?php
namespace DomOperationQueue;

/**
 * Interface class for prioritized DOM Operation objects
 *
 * @author Rácz Tibor Zoltán <racztiborzoltan@gmail.com>
 *
 */
interface DomOperationPriorityAwareInterface
{

    /**
     * Returns priority of DOM operation object
     *
     * @return int
     */
    public function getDomOperationPriority(): int;
}