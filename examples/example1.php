<?php
use DomOperationQueue\DomOperationQueue;
use DomOperationQueue\DomOperationInterface;
use DomOperationQueue\DomOperationPriorityAwareInterface;

require_once '../vendor/autoload.php';

header('Content-Type: text/plain');


class TestDomOperation implements DomOperationInterface
{

    private $_content = null;

    public function __construct(string $content)
    {
        $this->_content = $content;
    }

    public function executeDomOperation(\DOMDocument $dom_document): \DOMDocument
    {
        $body = $dom_document->getElementsByTagName('body');
        if ($body->length == 0) {
            return $dom_document;
        }
        /**
         * @var \DOMElement $body
         */
        $body = $body->item(0);
        // Add test content into <body>
        $body->appendChild($body->ownerDocument->createElement('div', $this->_content));
        return $dom_document;
    }
}

class TestDomOperationWithPriority extends TestDomOperation implements DomOperationInterface, DomOperationPriorityAwareInterface
{

    private $_priortiy = null;

    public function __construct(string $content, int $priority)
    {
        $this->_priortiy = $priority;
        $content = $content . ' (PRIORITY: ' . $priority . ')';
        parent::__construct($content);
    }

    public function getDomOperationPriority(): int
    {
        return $this->_priortiy;
    }
}



$list = new DomOperationQueue();

$removable_operation = new TestDomOperationWithPriority('test C', 15);

//
// - operation with highest priority will be executed earlier
// - operation without priority will be added with automatic priority
//
$list->addDomOperation(new TestDomOperationWithPriority('test A', 10));
$list->addDomOperation(new TestDomOperationWithPriority('test B', 5));
$list->addDomOperation(new TestDomOperation('test content'));
$list->addDomOperation($removable_operation);
$list->addDomOperation(new TestDomOperationWithPriority('test D', 20));



// =============================================================================



echo PHP_EOL;
echo PHP_EOL;
echo PHP_EOL;
echo str_repeat('=', 80);
echo PHP_EOL;
echo PHP_EOL;
echo PHP_EOL;



// =============================================================================





$dom_document = new DOMDocument();
$dom_document->loadHTML('
<!DOCTYPE html>
<html>
<head>
<title>example html document</title>
</head>
<body>
<h1>hello world!</h1>
<p>sample paragraph</p>
</body>
</html>
');

$list->execute($dom_document);

echo $dom_document->saveHTML($dom_document);




// =============================================================================



echo PHP_EOL;
echo PHP_EOL;
echo PHP_EOL;
echo str_repeat('=', 80);
echo PHP_EOL;
echo PHP_EOL;
echo PHP_EOL;



// =============================================================================










$list->removeDomOperation($removable_operation);


$dom_document = new DOMDocument();
$dom_document->loadHTML('
<!DOCTYPE html>
<html>
<head>
<title>example html document 2</title>
</head>
<body>
<h1>hello world 2!</h1>
<p>sample paragraph</p>
</body>
</html>
');

$list->execute($dom_document);

echo $dom_document->saveHTML($dom_document);



// =============================================================================



echo PHP_EOL;
echo PHP_EOL;
echo PHP_EOL;
echo str_repeat('=', 80);
echo PHP_EOL;
echo PHP_EOL;
echo PHP_EOL;



// =============================================================================



$list->addDomOperation(new TestDomOperationWithPriority('test C2', 15));
$list->addDomOperation($removable_operation);

$dom_document = new DOMDocument();
$dom_document->loadHTML('
<!DOCTYPE html>
<html>
<head>
<title>example html document 3</title>
</head>
<body>
<p>Hello world 3!</p>
</body>
</html>
');

$list->execute($dom_document);

echo $dom_document->saveHTML($dom_document);
