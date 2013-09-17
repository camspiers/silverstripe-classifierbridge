<?php

namespace Camspiers\StatisticalClassifier\SilverStripe;

/**
 * Class Document
 * @package Camspiers\StatisticalClassifier\SilverStripe
 */
interface Document
{
    /**
     * @return mixed
     */
    public function getCategories();
    /**
     * @return mixed
     */
    public function getDocument();
}