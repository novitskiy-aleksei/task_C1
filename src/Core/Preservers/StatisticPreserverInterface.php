<?php

namespace Parser\Core\Preservers;


interface StatisticPreserverInterface
{
    /**
     * Save provided statistic to file or elsewhere
     *
     * @param string $statistic
     * @return string
     */
    public function save($statistic);
}
