<?php

namespace EveryPolitician\EveryPolitician;

class Legislature
{
    public $name;
    public $slug;
    public $personCount;
    public $sha;
    public $statementCount;
    public $popoloUrl;
    protected $legislatureData;
    protected $country;

    public function __construct($legislatureData, $country)
    {
        $properties = ['name', 'slug', 'personCount', 'sha', 'statementCount', 'popoloUrl'];
        foreach ($properties as $k) {
            $this->$k = array_key_exists($k, $legislatureData) ? $legislatureData[$k] : null;
        }
        $timestamp = $legislatureData['lastmod'];
        $this->lastmod = \DateTime::createFromFormat('U', $timestamp);
        $this->legislatureData = $legislatureData;
        $this->country = $country;
    }

}
