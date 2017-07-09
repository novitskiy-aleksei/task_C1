<?php

namespace Parser\Core;

/**
 * Used for organizing and formatting keyword statistics output
 * @package Parser\Core
 */
class KeywordStatistics
{
    private $parametersBag = [];
    private $keyword;

    /**
     * @param string $keyword
     * @param array $parametersBag
     */
    public function __construct($keyword, $parametersBag = [])
    {
        $this->parametersBag = $parametersBag;
        $this->keyword = $keyword;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->formOutput();
    }

    /**
     * Convert stored arrays of statistics into string representation
     *
     * @return string
     */
    public function formOutput()
    {
        $string = '';

        foreach ($this->parametersBag as $statInfo => $item) {
            $string .= "Statistic for $statInfo. Keyword '$this->keyword':\r\n";

            foreach ($item as $tagName => $data) {
                $string .= "<$tagName>:";

                if (empty($data)) {
                    $string .= "(not found on page)";
                    continue;
                }

                // cause tag attributes info stored as array
                if (is_array($data)) {
                    $string .= "\r\n";
                    foreach ($data as $attribute => $count) {
                        $string .= "'$attribute': $count time(s)\r\n";
                    }
                } else {
                    $string .= "$data time(s)\r\n";
                }
            }
        }

        return $string;
    }

    /**
     * @param string $statInfo
     * @param $value
     */
    public function set($statInfo, $value)
    {
        $this->parametersBag[$statInfo] = $value;
    }

    /**
     * @param string $statInfo
     * @return array
     */
    public function get($statInfo)
    {
        if (array_key_exists($statInfo, $this->parametersBag)) {
            return $this->parametersBag[$statInfo];
        }

        return [];
    }
}
