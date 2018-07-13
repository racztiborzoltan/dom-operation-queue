<?php
namespace DomOperationQueue;

/**
 * Interface class for DOM Operation objects
 *
 * @author Rácz Tibor Zoltán <racztiborzoltan@gmail.com>
 *
 */
interface DomOperationInterface
{

    /**
     * Execute operation on an DOMDocument object
     *
     * @param \DOMDocument $dom_document
     * @return \DOMDocument
     */
    public function executeDomOperation(\DOMDocument $dom_document): \DOMDocument;
}