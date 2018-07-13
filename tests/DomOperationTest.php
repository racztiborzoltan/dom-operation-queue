<?php
declare(strict_types=1);

namespace DomOperationQueue\Tests;

use PHPUnit\Framework\TestCase;

final class DomOperationTest extends TestCase
{

    private $_raw_xml_string = '<root></root>';

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

    public function testDomOperation()
    {
        $new_tag_name = 'test';
        $new_tag_content = 'test content 1';

        $operation = new TestDomOperation($new_tag_name, $new_tag_content);

        /**
         * @var \DOMDocument $dom_document
         */
        $dom_document = $this->_factoryDomDocument();
        $operation->executeDomOperation($dom_document);

        $nodes = $dom_document->getElementsByTagName($new_tag_name);
        $this->assertTrue($nodes->length > 0);
        if ($nodes->length > 0) {
            $new_node = $nodes->item(0);
            $this->assertEquals($new_tag_content, $new_node->textContent);
        }
    }
}