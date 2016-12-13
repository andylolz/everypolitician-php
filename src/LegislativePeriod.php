<?php

namespace EveryPolitician\EveryPolitician;

use \GuzzleHttp;
use \League\Csv;

class LegislativePeriod
{
    public $id;
    public $name;
    public $slug;
    protected $legislature;
    protected $country;
    protected $legislativePeriodData;

    public function __construct($legislativePeriodData, $legislature, $country)
    {
        $properties = ['id', 'name', 'slug'];
        foreach ($properties as $k) {
            $this->$k = $legislativePeriodData[$k];
        }
        $this->legislature = $legislature;
        $this->country = $country;
        $this->legislativePeriodData = $legislativePeriodData;
    }

    public function __get($prop)
    {
        if (in_array($prop, ['startDate', 'endDate', 'csvUrl'])) {
            $getter = 'get' . ucfirst($prop);
            return $this->$getter();
        }
        trigger_error('Undefined property: '.__CLASS__.'::$'.$prop, E_USER_ERROR);
    }

    /**
     * Return the start date of the legislative period
     *
     * If this is unknown, it returns null.
     */
    private function getStartDate()
    {
        if (array_key_exists('start_date', $this->legislativePeriodData)) {
            return $this->legislativePeriodData['start_date'];
        }
        return null;
    }

    /**
     * Return the end date of the legislative period
     *
     * If this is unknown, it returns null.
     */
    private function getEndDate()
    {
        if (array_key_exists('end_date', $this->legislativePeriodData)) {
            return $this->legislativePeriodData['end_date'];
        }
        return null;
    }

    /**
     * Return the URL to CSV of members during this legislative period
     */
    private function getCsvUrl()
    {
        return 'https://raw.githubusercontent.com/everypolitician'
            .'/everypolitician-data/'.$this->legislature->sha
            .'/'.$this->legislativePeriodData['csv'];
    }

    /**
     * Return parsed data from the CSV of members during the period
     *
     * This returns a list of one dict per row of the CSV file, where
     * the keys are the column headers.
     */
    public function csv()
    {
        $client = new GuzzleHttp\Client();
        $response = $client->get($this->csvUrl);
        $reader = Csv\Reader::createFromString($response->getBody());
        return iterator_to_array($reader->fetchAssoc(), false);
    }
}
