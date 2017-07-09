<?php

// Morphy lib has a bunch of deprecated constructors,
// so I have to suppress PHP warnings
error_reporting( E_ALL ^ E_DEPRECATED );

use Parser\Core\KeywordParser;
use Parser\Core\KeywordStatistics;
use Parser\Helpers\MorphyWrapper;
use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    public function testKeywordOccurrences()
    {
        $document = new \Parser\Core\Dom\Document();
        $parser = new \Parser\Core\KeywordParser($document);

        $content = file_get_contents(__DIR__.'/dummy.html');

        $result = $parser->grabInfo('Lorem', $content);

        $this->assertInstanceOf(KeywordStatistics::class, $result);
        $this->assertArraySubset(['title' => 2, 'h1' => 5, 'p' => 3], $result->get(KeywordParser::OCCURRING_TAGS_KEY));
        $this->assertArraySubset(['img' => ['title' => 5, 'alt' => 1]], $result->get(KeywordParser::OCCURRING_ATTS_KEY));
    }

    public function testWordFormsReceiving()
    {
        $morphy = new MorphyWrapper('en');
        $this->assertArraySubset($morphy->getAllForms('work'), ["WORK", "WORKS", "WORKED", "WROUGHT", "WORKING"]);

        $morphy = new MorphyWrapper('ru');
        $this->assertArraySubset($morphy->getAllForms('баклажан'), [
            'БАКЛАЖАН',
            'БАКЛАЖАНА',
            'БАКЛАЖАНУ',
            'БАКЛАЖАНОМ',
            'БАКЛАЖАНЕ',
            'БАКЛАЖАНЫ',
            'БАКЛАЖАНОВ',
            'БАКЛАЖАНАМ',
            'БАКЛАЖАНАМИ',
            'БАКЛАЖАНАХ'
        ]);
    }
}
