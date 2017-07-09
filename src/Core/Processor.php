<?php

namespace Parser\Core;

use Parser\Core\Http\ContentRetrieverInterface;
use Parser\Core\Preservers\StatisticPreserverInterface;
use Parser\Helpers\MorphyWrapper;
use Parser\Helpers\ValidationException;
use Parser\Helpers\Validator;

class Processor
{
    private $retriever;

    /** @var Validator */
    private $validator;

    /** @var KeywordParser */
    private $parser;

    private $saver;

    /**
     * @param ContentRetrieverInterface $retriever
     * @param StatisticPreserverInterface $saver
     * @param KeywordParser $parser
     * @param Validator $validator
     */
    public function __construct(ContentRetrieverInterface $retriever, StatisticPreserverInterface $saver, $parser, $validator)
    {
        $this->retriever = $retriever;
        $this->saver = $saver;
        $this->parser = $parser;
        $this->validator = $validator;
    }

    /**
     * Entry point method. Calls grab methods, check input etc
     *
     * @param $inputParams
     * @return mixed
     */
    public function boot($inputParams)
    {
        $this->checkInput($inputParams);

        $content = $this->retriever->fetchByUrl($inputParams['url']);

        // fetch statistics for all word forms
        $morphy = new MorphyWrapper($inputParams['lang']);
        $statistic = [];
        foreach ($morphy->getAllForms($inputParams['keyword']) as $wordForm) {
            $statistic[] = $this->parser->grabInfo($wordForm, $content);
        }

        // save to file and attach link to response
        $output = $this->makeOutput($statistic);

        $output .= $this->saver->save($output);

        return $output;
    }

    /**
     * @param array $inputParams
     * @throws ValidationException
     */
    public function checkInput($inputParams)
    {
        $validator = $this->validator;

        if (empty($inputParams['url']) || empty($inputParams['keyword'])) {
            throw new ValidationException('Required values are empty');
        }

        $validationMap = [
            $validator::VALIDATOR_URL_RULE  => $inputParams['url'],
            $validator::VALIDATOR_NAME_RULE => $inputParams['keyword']
        ];

        if ( ! $validator->validate($validationMap)) {
            throw new ValidationException(
                implode('\r\n', $validator->getLastErrors())
            );
        }
    }

    /**
     * Convert array of KeywordStatistics classes into formatted string
     *
     * @param KeywordStatistics[] $statistics
     * @return string
     */
    protected function makeOutput(array $statistics)
    {
        $output = '';
        foreach ($statistics as $statistic) {
            $output .= $statistic->formOutput() . "\r\n";
            $output .= "\r\n=================================\r\n";
        }

        return $output;
    }
}
