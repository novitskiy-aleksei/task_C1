<?php

namespace Parser\Core;

use DOMElement;
use Parser\Core\Dom\Document;

class KeywordParser
{
    private $document;

    private $tagsForSearch = ['title', 'description', 'h1', 'p'];
    private $attsForSearch = ['img' => ['title', 'alt']];

    const OCCURRING_TAGS_KEY = 'Occurring provided keyword in tags';
    const OCCURRING_ATTS_KEY = 'Occurring provided keyword in tag attributes';

    /**
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Walk through html elements, fetch statistics
     *
     * @param string $keyword
     * @param string $content
     * @return KeywordStatistics
     */
    public function grabInfo($keyword, $content)
    {
        $this->document->loadHTML($content);
        $tagStat = $attStat = [];

        // fetch statistics from tag elements
        $tagsCollection = $this->document->getElementsByTagName($this->tagsForSearch);

        /** @var DOMElement $tagElement */
        foreach ($tagsCollection as $tagElement) {
            if (mb_stripos($tagElement->nodeValue, $keyword) !== false) {
                if (!isset($tagStat[$tagElement->tagName])) {
                    $tagStat[$tagElement->tagName] = 0;
                }
                $tagStat[$tagElement->tagName] += substr_count(
                    mb_strtolower($tagElement->nodeValue),
                    mb_strtolower($keyword)
                );
            }
        }

        // stats from tag attributes
        $tagsCollection = $this->document->getElementsByTagName(array_keys($this->attsForSearch));

        foreach ($tagsCollection as $tagElement) {
            $attList = $this->attsForSearch[$tagElement->tagName];
            $stat = $this->getStatForAttributes($tagElement, $attList, $keyword);

            if (!isset($attStat[$tagElement->tagName])) {
                $attStat[$tagElement->tagName] = [];
            }

            // merge results between found elements
            foreach ($stat as $key => $count) {
                if (!isset($attStat[$tagElement->tagName][$key])) {
                    $attStat[$tagElement->tagName][$key] = 0;
                }
                $attStat[$tagElement->tagName][$key] += $count;
            }
        }

        return new KeywordStatistics(
            $keyword,
            [
                self::OCCURRING_TAGS_KEY => $tagStat,
                self::OCCURRING_ATTS_KEY => $attStat
            ]
        );
    }

    /**
     * Fetch statistics for list of attributes for provided element
     *
     * @param DOMElement $element
     * @param array $attributeList
     * @param string $keyword
     * @return null
     */
    protected function getStatForAttributes(DOMElement $element, $attributeList, $keyword)
    {
        $stat = [];
        foreach ($attributeList as $attribute) {
            if ($element->hasAttribute($attribute) && mb_stripos($element->getAttribute($attribute), $keyword) !== false) {
                if (!isset($stat[$attribute])) {
                    $stat[$attribute] = 0;
                }

                $stat[$attribute] += substr_count(
                    mb_strtolower($element->getAttribute($attribute)),
                    mb_strtolower($keyword)
                );
            }
        }

        return $stat;
    }
}
