<?php

namespace EveryPolitician\EveryPolitician;

use \GuzzleHttp;

class EveryPolitician
{
    private $url;
    private $filename;
    private $countriesJsonData;

    const TYPE_FILENAME = 0;
    const TYPE_URL      = 1;
    const DEFAULT_COUNTRIES_JSON_URL = 'https://raw.githubusercontent.com/'
        .'everypolitician/everypolitician-data/master/countries.json';

    /**
     * Creates a new instance
     *
     * @param string $urlOrFilename URL or filename of Popolo json
     * @param int $type the delimiters to consider
     */
    public function __construct($urlOrFilename = self::DEFAULT_COUNTRIES_JSON_URL, $type = self::TYPE_URL)
    {
        switch ($type) {
            case static::TYPE_FILENAME:
                $this->filename = $urlOrFilename;
                break;
            case static::TYPE_URL:
                $this->url = $urlOrFilename;
                break;
            default:
                throw new \Exception('Type must be TYPE_URL or TYPE_FILENAME');
                break;
        }
    }

    /**
     * String representation of {@link EveryPolitician}
     *
     * @return string
     */
    public function __toString()
    {
        $str = $this->url ?: $this->filename;
        return '<EveryPolitician: '.$str.'>';
    }

    /**
     * Return a new {@link EveryPolitician} from a filename
     * of a Popolo json file
     *
     * @param string $filename filename of a Popolo json file
     *
     * @return static
     */
    public static function fromFilename($filename)
    {
        return new static($filename, static::TYPE_FILENAME);
    }

    /**
     * Return a new {@link EveryPolitician} from a URL
     * pointing to a Popolo json file
     *
     * @param string $url URL pointing to a Popolo json file
     *
     * @return static
     */
    public static function fromUrl($url)
    {
        return new static($url, static::TYPE_URL);
    }

    private function countriesJsonData()
    {
        if (!is_null($this->countriesJsonData)) {
            return $this->countriesJsonData;
        }
        if (!is_null($this->filename)) {
            $f = file_get_contents($this->filename);
            $this->countriesJsonData = json_decode($f, true);
        } else {
            $client = new GuzzleHttp\Client();
            $response = $client->get($this->url);
            $this->countriesJsonData = json_decode($response->getBody(), true);
        }
        return $this->countriesJsonData;
    }

    /**
     * Return a list of all known {@link Country}s
     *
     * @return Country[]
     */
    public function countries()
    {
        $countries = [];
        $countriesData = $this->countriesJsonData();
        foreach ($countriesData as $countryData) {
            $countries[] = new Country($countryData);
        }
        return $countries;
    }

    /**
     * Return a {@link Country} object from a country slug
     *
     * @param string $countrySlug slug identifying a country
     *
     * @return Country
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
     * Return an array containing a {@link Country} and a
     * {@link Legislature} object from their slugs
     *
     * @param string $countrySlug slug identifying a country
     * @param string $legislatureSlug slug identifying a legislature
     *
     * @return array
     */
    public function countryLegislature($countrySlug, $legislatureSlug)
    {
        $country = $this->country($countrySlug);
        $legislature = $country->legislature($legislatureSlug);
        return [$country, $legislature];
    }
}
