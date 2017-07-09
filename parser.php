<?php

use Parser\Helpers\HelpMessageContainer;

// Morphy lib has a bunch of deprecated constructors,
// so I have to suppress PHP warnings
error_reporting( E_ALL ^ E_DEPRECATED );

require __DIR__.'/vendor/autoload.php';


// retrieve arguments from command line
$inputParams = getopt('',['url:','keyword:', 'lang::', 'help::']);

if (isset($inputParams['help'])) {
    echo HelpMessageContainer::getMessage();
    return true;
}
if (empty($inputParams['lang'])) {
    $inputParams['lang'] = 'en';
}

// initialize classes for dummy dependency inversion
$validator = new \Parser\Helpers\Validator();
$retriever = new \Parser\Core\Http\BasicRetriever();
$document = new \Parser\Core\Dom\Document();
$saver = new \Parser\Core\Preservers\CsvSaver();
$parser = new \Parser\Core\KeywordParser($document);

$processor = new \Parser\Core\Processor($retriever, $saver, $parser, $validator);

try {
    echo $processor->boot($inputParams);
} catch (Parser\Core\ParserException $exception) {
    echo "There is errors occurred:\r\n";
    echo $exception->getMessage() . "\r\n";
    echo HelpMessageContainer::getMessage();
}
