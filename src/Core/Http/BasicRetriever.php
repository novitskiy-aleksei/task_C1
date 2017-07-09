<?php

namespace Parser\Core\Http;


class BasicRetriever implements ContentRetrieverInterface
{
    /**
     * Return content of provided url
     *
     * @param string $url
     * @return bool|string
     * @throws RetrievingException
     */
    public function fetchByUrl($url)
    {
        $opts = array('http' =>
            array(
                'method'  => 'GET',
                'header'  => "Content-Type: text/html",
                'timeout' => 60
            )
        );

        $context  = stream_context_create($opts);

        try {
            $content = file_get_contents($url, false, $context);
        } catch (\Exception $exception) {
        } finally {
            if (empty($content) || $content === false) {
                throw new RetrievingException("Could not connect to provided resource\r\n");
            }
        }

        return $content;
    }
}
