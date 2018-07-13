<?php
use DomOperationQueue\DomOperationQueue;
use DomOperationQueue\Tests\TestDomOperationWithPriority;
use DomOperationQueue\Tests\TestDomOperation;

require_once '../vendor/autoload.php';

header('Content-Type: text/plain');

if (!class_exists(TestDomOperation::class)) {
    exit('composer dev packages not installed!');
}


// =============================================================================



$list = new DomOperationQueue();

$removable_operation = new TestDomOperationWithPriority('test_c', 'test C', 15);

//
// - operation with highest priority will be executed earlier
// - operation without priority will be added with automatic priority
//
$list->addDomOperation(new TestDomOperationWithPriority('test_a', 'test A', 10));
$list->addDomOperation(new TestDomOperationWithPriority('test_b', 'test B', 5));
$list->addDomOperation(new TestDomOperation('test', 'test content'));
$list->addDomOperation($removable_operation);
$list->addDomOperation(new TestDomOperationWithPriority('test_d', 'test D', 20));




// =============================================================================
echo str_repeat(PHP_EOL, 3) . str_repeat('=', 80) . str_repeat(PHP_EOL, 3);
// =============================================================================




$dom_document = new DOMDocument();
$dom_document->loadXML('<root example="1"></root>');

$list->execute($dom_document);

$dom_document->formatOutput = true;
echo $dom_document->saveXML($dom_document->documentElement);




// =============================================================================
echo str_repeat(PHP_EOL, 3) . str_repeat('=', 80) . str_repeat(PHP_EOL, 3);
// =============================================================================




$list->removeDomOperation($removable_operation);


$dom_document = new DOMDocument();
$dom_document->loadXML('<root example="2"></root>');

$list->execute($dom_document);

$dom_document->formatOutput = true;
echo $dom_document->saveXML($dom_document->documentElement);



// =============================================================================
echo str_repeat(PHP_EOL, 3) . str_repeat('=', 80) . str_repeat(PHP_EOL, 3);
// =============================================================================



$list->addDomOperation(new TestDomOperationWithPriority('test_c2', 'test C2', 15));
$list->addDomOperation($removable_operation);

$dom_document = new DOMDocument();
$dom_document->loadXML('<root example="3"></root>');

$list->execute($dom_document);

$dom_document->formatOutput = true;
echo $dom_document->saveXML($dom_document->documentElement);



// =============================================================================
echo str_repeat(PHP_EOL, 3) . str_repeat('=', 80) . str_repeat(PHP_EOL, 3);
// =============================================================================



