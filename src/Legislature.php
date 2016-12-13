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

    public function __toString()
    {
        return '<Legislature: '.$this->name.' in '.$this->country->name.'>';
    }

    /**
     * Return the directory path in the everypolitician-data repository
     */
    public function directory()
    {
        $splitPath = explode('/', $this->legislatureData['sources_directory']);
        $splitPath = array_slice($splitPath, 1, 2);
        return implode('/', $splitPath);
    }

    public function popolo()
    {
        return Popolo::fromUrl($this->popoloUrl);
    }
}
