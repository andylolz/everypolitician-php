<?php

namespace EveryPolitician\EveryPolitician;

// A class that represents a country from the countries.json file
class Country
{
    public $name;
    public $code;
    public $slug;
    protected $countryData;

    /**
     * Creates a new instance
     *
     * @param array $countryData Popolo country data
     */
    public function __construct($countryData)
    {
        $properties = ['name', 'code', 'slug'];
        foreach ($properties as $k) {
            $this->$k = $countryData[$k];
        }
        $this->countryData = $countryData;
    }

    /**
     * String representation of {@link Country}
     *
     * @return string
     */
    public function __toString()
    {
        return '<Country: '.$this->name.'>';
    }

    /**
     * Return all the {@link Legislature}s known for this {@link Country}
     *
     * A {@link Legislature} is a chamber of a parliament, e.g. the House of
     * Commons in the UK.
     *
     * @return Legislature[]
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
    /**
     * Return a {@link Legislature} in this {@link Country} from its slug
     *
     * @param string $legislatureSlug slug identifying a legislature
     *
     * @return Legislature
     */
    public function legislature($legislatureSlug)
    {
        foreach ($this->legislatures() as $legislature) {
            if ($legislature->slug == $legislatureSlug) {
                return $legislature;
            }
        }
        throw new Exceptions\NotFoundException("Couldn't find the legislature with slug '$legislatureSlug'");
    }
}
