<?php
namespace DomOperationQueue\Tests;

use DomOperationQueue\DomOperationInterface;

/**
 * Test DOM Operation class for phpunit tests
 *
 * @author Rácz Tibor Zoltán <racztiborzoltan@gmail.com>
 *
 */
class TestDomOperation implements DomOperationInterface
{

    /**
     * Test tag name
     *
     * @var string
     */
    private $_tag_name = null;

    /**
     * Test content
     *
     * @var string
     */
    private $_content = null;

    public function __construct(string $tag_name, string $content)
    {
        $this->_tag_name = $tag_name;
        $this->_content = $content;
    }

    public function getTagName(): string
    {
        return $this->_tag_name;
    }

    public function setTagName(string $tag_name)
    {
        $this->_tag_name = $tag_name;
    }

    public function getContent(): string
    {
        return $this->_content;
    }

    public function setContent(string $content)
    {
        $this->_content = $content;
    }

    public function executeDomOperation(\DOMDocument $dom_document): \DOMDocument
    {
        $dom_document->firstChild->appendChild($dom_document->createElement($this->getTagName(), $this->getContent()));

        return $dom_document;
    }
}