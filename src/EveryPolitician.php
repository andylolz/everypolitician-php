<?php

namespace EveryPolitician\EveryPolitician;

class EveryPolitician
{
    protected $countriesJsonFilename;
    protected $countriesJsonUrl;
    private $countriesJsonData;
    const DEFAULT_COUNTRIES_JSON_URL = 'https://raw.githubusercontent.com/'
        .'everypolitician/everypolitician-data/master/countries.json';

    /**
     * Create a new Skeleton Instance
     */
    public function __construct($countriesJsonUrl = null, $countriesJsonFilename = null)
    {
        if (is_null($this->countriesJsonFilename)) {
            if (is_null($this->countriesJsonUrl)) {
                $countriesJsonUrl = self::DEFAULT_COUNTRIES_JSON_URL;
            }
            $this->countriesJsonUrl = $countriesJsonUrl;
        } else {
            $this->countriesJsonFilename = $countriesJsonFilename;
        }
    }

    public function __toString()
    {
        $body = !is_null($this->countriesJsonUrl) ? $this->countriesJsonUrl : $this->countriesJsonFilename;
        return "<EveryPolitician: $body>";
    }
}
