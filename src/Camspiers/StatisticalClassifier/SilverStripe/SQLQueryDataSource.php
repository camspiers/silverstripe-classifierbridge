<?php

namespace Camspiers\StatisticalClassifier\SilverStripe;

use SQLQuery;
use Camspiers\StatisticalClassifier\DataSource\DataArray;

/**
 * Class DataSource
 * @package Camspiers\StatisticalClassifier\SilverStripe
 */
class SQLQueryDataSource extends DataArray
{
    /**
     * @var \SQLQuery
     */
    private $query;
    /**
     * The category of the query
     * @var string
     */
    private $category;
    
    /**
     * The column to use for the document
     * @var string
     */
    private $documentColumn;

    /**
     * @param string $category
     * @param SQLQuery $query
     * @param string $documentColumn
     */
    public function __construct($category, SQLQuery $query, $documentColumn)
    {
        $this->category = $category;
        $this->query = $query;
        $this->documentColumn = $documentColumn;
    }

    /**
     * @return array
     */
    protected function read()
    {
        $data = array();
        
        foreach ($this->query->execute() as $result) {
            if (isset($result[$this->documentColumn])) {
                $data[] = array(
                    'category' => $this->category,
                    'document' => $result[$this->documentColumn]
                );
            }
        }

        return $data;
    }
}
