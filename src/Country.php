<?php

namespace EveryPolitician\EveryPolitician;

// A class that represents a country from the countries.json file
class Country
{
    public $name;
    public $code;
    public $slug;
    protected $countryData;

    public function __construct($countryData)
    {
        $properties = ['name', 'code', 'slug'];
        foreach ($properties as $k) {
            $this->$k = $countryData[$k];
        }
        $this->countryData = $countryData;
    }

    public function __toString()
    {
        return '<Country: '.$this->name.'>';
    }

    /**
     * Return all the legislatures known for this country
     *
     * A legislature is a chamber of a parliament, e.g. the House of
     * Commons in the UK.
     */
    public function legislatures()
    {
        $legislatures = [];
        $legislaturesData = $this->countryData['legislatures'];
        foreach ($legislaturesData as $legislatureData) {
            $legislatures[] = new Legislature($legislatureData, $this);
        }
        return $legislatures;
    }

    public function legislature($legislatureSlug)
    {
        foreach ($this->legislatures() as $l) {
            if ($l->slug == $legislatureSlug) {
                return $l;
            }
        }
        throw new Exceptions\NotFoundException("Couldn't find the legislature with slug '$legislatureSlug'");
    }
}
