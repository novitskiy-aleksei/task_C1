<?php

namespace Parser\Core\Http;


interface ContentRetrieverInterface
{
    public function fetchByUrl($url);
}
