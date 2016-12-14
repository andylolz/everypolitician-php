<?php

namespace EveryPolitician\EveryPolitician;

use \DateTime;
use \EveryPolitician\EveryPoliticianPopolo\Popolo;

class Legislature
{
    public $name;
    public $slug;
    public $personCount;
    public $sha;
    public $statementCount;
    public $popoloUrl;
    public $lastmod;
    protected $legislatureData;
    protected $country;

    /**
     * Creates a new instance
     *
     * @param array $legislatureData Popolo legislature data
     * @param Country @country country for this legislature
     */
    public function __construct($legislatureData, $country)
    {
        $propertyMappings = [
            'name' => 'name',
            'slug' => 'slug',
            'person_count' => 'personCount',
            'sha' => 'sha',
            'statement_count' => 'statementCount',
            'popolo_url' => 'popoloUrl'
        ];
        foreach ($propertyMappings as $k => $v) {
            $this->$v = array_key_exists($k, $legislatureData) ? $legislatureData[$k] : null;
        }
        $timestamp = $legislatureData['lastmod'];
        $this->lastmod = DateTime::createFromFormat('U', $timestamp);
        $this->legislatureData = $legislatureData;
        $this->country = $country;
    }

    /**
     * String representation of {@link Legislature}
     *
     * @return string
     */
    public function __toString()
    {
        return '<Legislature: '.$this->name.' in '.$this->country->name.'>';
    }

    /**
     * Return the directory path in the everypolitician-data repository
     *
     * @return string
     */
    public function directory()
    {
        $splitPath = explode('/', $this->legislatureData['sources_directory']);
        $splitPath = array_slice($splitPath, 1, 2);
        return implode('/', $splitPath);
    }

    /**
     * Return a Popolo instance for this {@link Legislature}
     *
     * @return Popolo
     */
    public function popolo()
    {
        return Popolo::fromUrl($this->popoloUrl);
    }

    /**
     * Return all the known legislative periods for this legislature
     *
     * @return LegislativePeriod[]
     */
    public function legislativePeriods()
    {
        $legislativePeriods = [];
        foreach ($this->legislatureData['legislative_periods'] as $lpData) {
            $legislativePeriods[] = new LegislativePeriod($lpData, $this, $this->country);
        }
        return $legislativePeriods;
    }
}
