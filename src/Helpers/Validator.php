<?php

namespace Parser\Helpers;

class Validator
{
    const VALIDATOR_URL_RULE = '/(?:https?:\/\/)?(?:[a-zA-Z0-9.-]+?\.(?:[a-zA-Z])|\d+\.\d+\.\d+\.\d+)/';
    const VALIDATOR_NAME_RULE = '/(.){3,}/';

    private $messageBag = [];

    /**
     * @param array $values
     * @return bool
     */
    public function validate(array $values)
    {
        $this->messageBag = [];
        foreach ($values as $pattern => $value) {
            if ( ! preg_match($pattern, $value)) {
                $this->messageBag[] = "'$value' didn't match the pattern ($pattern) and its not correct";
            }
        }

        return empty($this->messageBag);
    }

    /**
     * @return array
     */
    public function getLastErrors()
    {
        return $this->messageBag;
    }
}
