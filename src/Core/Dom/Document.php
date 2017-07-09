<?php

namespace Parser\Core\Dom;


class Document extends \DOMDocument
{
    private $loadedHtml = null;

    /**
     * @inheritdoc
     */
    public function loadHTML($source, $options = 0)
    {
        // url can not be changed during one request,
        // so no need to load html again
        if (!empty($this->loadedHtml)) {
            return $this->loadedHtml;
        }

        // as I need to use built-in php features, but DOM extension could not work with html5,
        // so I suppress errors caused by html5 tags
        libxml_use_internal_errors(true);
        $html = parent::loadHTML($source, $options);
        libxml_use_internal_errors(false);
        libxml_clear_errors();

        return $html;
    }

    /**
     * Get DOM elements by tag name or array of names
     *
     * @param string|array $tagNames
     * @return array|\DOMNodeList
     */
    public function getElementsByTagName($tagNames)
    {
        $elements = [];

        if (is_array($tagNames)) {
            foreach ($tagNames as $tagName) {
                $elements = array_merge($elements, iterator_to_array(parent::getElementsByTagName($tagName)));
            }
        } else {
            $elements = parent::getElementsByTagName($tagNames);
        }

        return $elements;
    }
}
