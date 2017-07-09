<?php

namespace Parser\Helpers;

/**
 * Not sure that this is good place for help message text,
 * but here it is :)
 *
 * @package Parser\Helpers
 */
class HelpMessageContainer
{
    public static function getMessage()
    {
        $help = "
usage: php parser.php [--help] [--keyword=hello] [--url=example.com] [--lang=en]

Options:
        --help      Show this message
        --keyword   Word to search
        --url       Page to search on it
        --lang      Language for provided keyword (used for morphological forms retrieving, default: english)
Example:
        php parser.php --keyword=hello --url=example.com
";

        return $help;
    }
}
