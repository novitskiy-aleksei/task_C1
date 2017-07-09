<?php

namespace Parser\Helpers;


use cijic\phpMorphy\Morphy;

class MorphyWrapper extends Morphy
{
    /**
     * @param mixed $word
     * @param int|mixed $type
     * @return array
     */
    public function getAllForms($word, $type = self::NORMAL)
    {
        $forms = [];

        // for some reason Morphy lib find words only in uppercase
        if (false === ($paradigms = $this->findWord(mb_strtoupper($word)))) {
            return $forms;
        }

        /** phpMorphy_WordDescriptor_Collection $paradigms */
        // full list of forms stored in last element
        $forms = $paradigms->offsetGet($paradigms->count() - 1)->getAllForms();

        return $forms;
    }
}
