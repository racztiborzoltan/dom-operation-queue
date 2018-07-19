# DOM Operation Queue

List of DOM Operations with optional priority.


# Examples

### Create your DOM Operation class:

```php
class TestDomOperation implements \DomOperationQueue\DomOperationInterface
{
    public function executeDomOperation(\DOMDocument $dom_document): \DOMDocument
    {
        //
        // define your dom manipulation
        //
        return $dom_document;
    }
    
    //
    // ... This is your code area! :)
    //
}
```


### You will need an \DOMDocument object

```php
$dom_document = new DOMDocument();
// load content into dom. For example:
$dom_document->loadXML('<root></root>');
```


### Using the list

```php

$list = new DomOperationQueue();

$operation_1 = new TestDomOperation();

// add operation to list with priority or without priority:
$list->add(new TestDomOperation());
$list->add(new TestDomOperation(), 10);

// remove operation:
// $list->remove($removable_operation_object);

// remove operations by priority:
$list->removeByPriority(15);

// execute operations on an \DOMDocument object:
$list->execute($dom_document);
// or: 
$dom_document = $list->execute($dom_document);
```

### Using the modified \DOMDocument

```php
// for example: 
$dom_document->formatOutput = true;
echo $dom_document->saveXML($dom_document->documentElement);
```


#### Other examples in `examples` directory!


### License


[MIT](LICENSE)
