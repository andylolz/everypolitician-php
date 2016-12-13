<?php

namespace EveryPolitician\EveryPolitician;

class EveryPolitician
{
    private $countriesJsonFilename;
    private $countriesJsonUrl;
    private $countriesJsonData;

    const DEFAULT_COUNTRIES_JSON_URL = 'https://raw.githubusercontent.com/'
        .'everypolitician/everypolitician-data/master/countries.json';

    public function __construct($options = [])
    {
        if (!array_key_exists('filename', $options)) {
            if (array_key_exists('url', $options)) {
                $this->countriesJsonUrl = $options['url'];
            } else {
                $this->countriesJsonUrl = self::DEFAULT_COUNTRIES_JSON_URL;
            }
        } else {
            $this->countriesJsonFilename = $options['filename'];
        }
    }

    public function __toString()
    {
        $body = !is_null($this->countriesJsonUrl) ? $this->countriesJsonUrl : $this->countriesJsonFilename;
        return "<EveryPolitician: $body>";
    }

    /**
     * Construct from filename
     *
     * @param string $filename name of Popolo json file
     *
     * @return $this
     */
    public static function fromFilename($filename)
    {
        return new self(['filename' => $filename]);
    }

    /**
     * Construct from URL
     *
     * @param string $url location of Popolo json file
     *
     * @return $this
     */
    public static function fromUrl($url)
    {
        return new self(['url' => $url]);
    }

    private function getCountriesJsonData()
    {
        if (!is_null($this->countriesJsonData)) {
            return $this->countriesJsonData;
        }
        if (!is_null($this->countriesJsonFilename)) {
            $f = file_get_contents($this->countriesJsonFilename);
            $this->countriesJsonData = json_decode($f, true);
        } else {
            $client = new \GuzzleHttp\Client();
            $response = $client->get($this->countriesJsonUrl);
            $this->countriesJsonData = json_decode($response->getBody(), true);
        }
        return $this->countriesJsonData;
    }

    /**
     * Return a list of all known countries
     */
    public function countries()
    {
        $countries = [];
        $countriesData = $this->getCountriesJsonData();
        foreach ($countriesData as $countryData) {
            $countries[] = new Country($countryData);
        }
        return $countries;
    }

    /**
     * Return a Country object from a country slug
     */
    public function country($countrySlug)
    {
        foreach ($this->countries() as $c) {
            if ($c->slug == $countrySlug) {
                return $c;
            }
        }
        throw new Exceptions\NotFoundException("Couldn't find the country with slug '$countrySlug'");
    }

    /**
     * Return an array of Country and Legislature objects from their slugs
     */
    public function countryLegislature($countrySlug, $legislatureSlug)
    {
        $country = $this->country($countrySlug);
        $legislature = $country->legislature($legislatureSlug);
        return [$country, $legislature];
    }
}
