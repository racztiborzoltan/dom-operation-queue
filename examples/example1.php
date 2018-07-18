<?php
use DomOperationQueue\DomOperationQueue;
use DomOperationQueue\Tests\TestDomOperation;

require_once '../vendor/autoload.php';

header('Content-Type: text/plain');

if (!class_exists(TestDomOperation::class)) {
    exit('composer dev packages not installed!');
}



// =============================================================================
echo str_repeat(PHP_EOL, 3) . str_repeat('=', 80) . str_repeat(PHP_EOL, 3);
// =============================================================================



call_user_func(function(){

    echo '# add items to list with priority or without priority:' . PHP_EOL . PHP_EOL;

    $list = new DomOperationQueue();

    //
    // - operation with highest priority will be executed earlier
    // - operation without priority will be added with automatic priority
    //
    $list->add(new TestDomOperation('test_a', 'test A'), 10);
    $list->add(new TestDomOperation('test_b', 'test B'), 5);
    $list->add(new TestDomOperation('test', 'test content'));
    $list->add(new TestDomOperation('test_c', 'test C'), 15);
    $list->add(new TestDomOperation('test_d', 'test D'), 20);

    $dom_document = new DOMDocument();
    $dom_document->loadXML('<root></root>');

    $list->execute($dom_document);

    $dom_document->formatOutput = true;
    echo $dom_document->saveXML($dom_document->documentElement);

});



// =============================================================================
echo str_repeat(PHP_EOL, 3) . str_repeat('=', 80) . str_repeat(PHP_EOL, 3);
// =============================================================================



call_user_func(function(){

    echo '# remove item from the list:' . PHP_EOL . PHP_EOL;

    $list = new DomOperationQueue();

    $removable_operation = new TestDomOperation('test_c', 'test C');

    //
    // - operation with highest priority will be executed earlier
    // - operation without priority will be added with automatic priority
    //
    $list->add(new TestDomOperation('test_a', 'test A'), 10);
    $list->add(new TestDomOperation('test_b', 'test B'), 5);
    $list->add(new TestDomOperation('test', 'test content'));
    $list->add($removable_operation, 15);
    $list->add(new TestDomOperation('test_d', 'test D'), 20);

    $list->remove($removable_operation);

    $dom_document = new DOMDocument();
    $dom_document->loadXML('<root></root>');

    $list->execute($dom_document);

    $dom_document->formatOutput = true;
    echo $dom_document->saveXML($dom_document->documentElement);

});



// =============================================================================
echo str_repeat(PHP_EOL, 3) . str_repeat('=', 80) . str_repeat(PHP_EOL, 3);
// =============================================================================



call_user_func(function(){

    echo '# remove item from the list by priority:' . PHP_EOL . PHP_EOL;

    $list = new DomOperationQueue();

    //
    // - operation with highest priority will be executed earlier
    // - operation without priority will be added with automatic priority
    //
    $list->add(new TestDomOperation('test_a', 'test A'), 10);
    $list->add(new TestDomOperation('test_b', 'test B'), 5);
    $list->add(new TestDomOperation('test_c2', 'test C2'), 15);
    $list->add(new TestDomOperation('test', 'test content'));
    $list->add(new TestDomOperation('test_c', 'test C'), 15);
    $list->add(new TestDomOperation('test_d', 'test D'), 20);

    $list->removeByPriority(15);

    $dom_document = new DOMDocument();
    $dom_document->loadXML('<root></root>');

    $list->execute($dom_document);

    $dom_document->formatOutput = true;
    echo $dom_document->saveXML($dom_document->documentElement);

});



// =============================================================================
echo str_repeat(PHP_EOL, 3) . str_repeat('=', 80) . str_repeat(PHP_EOL, 3);
// =============================================================================



call_user_func(function(){

    echo '# remove item, and add item and removed item with same priority:' . PHP_EOL . PHP_EOL;

    $list = new DomOperationQueue();

    $removable_operation = new TestDomOperation('test_c', 'test C');

    //
    // - operation with highest priority will be executed earlier
    // - operation without priority will be added with automatic priority
    //
    $list->add(new TestDomOperation('test_a', 'test A'), 10);
    $list->add(new TestDomOperation('test_b', 'test B'), 5);
    $list->add(new TestDomOperation('test', 'test content'));
    $list->add($removable_operation, 15);
    $list->add(new TestDomOperation('test_d', 'test D'), 20);

    $list->remove($removable_operation);
    $list->add(new TestDomOperation('test_c2', 'test C2'), 15);
    $list->add($removable_operation, 15);

    $dom_document = new DOMDocument();
    $dom_document->loadXML('<root></root>');

    $list->execute($dom_document);

    $dom_document->formatOutput = true;
    echo $dom_document->saveXML($dom_document->documentElement);

});



// =============================================================================
echo str_repeat(PHP_EOL, 3) . str_repeat('=', 80) . str_repeat(PHP_EOL, 3);
// =============================================================================



