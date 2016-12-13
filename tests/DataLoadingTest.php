<?php

namespace EveryPolitician\EveryPolitician;

use \PHPUnit_Framework_TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class DataLoadingTest extends PHPUnit_Framework_TestCase
{
    use FakeResponseTrait;

    public function setUp()
    {
        date_default_timezone_set('Europe/London');
    }

    public function testCreateEp()
    {
        $toStr = '<EveryPolitician: https://raw.githubusercontent.com/'
            .'everypolitician/everypolitician-data/master/countries.json>';
        $ep = new EveryPolitician();
        $this->assertEquals($toStr, (string) $ep);
    }

    public function testEpToStringCustomUrl()
    {
        $toStr = '<EveryPolitician: foobar>';
        $ep = EveryPolitician::fromUrl('foobar');
        $this->assertEquals($toStr, (string) $ep);
    }

    public function testEpFromLocalFile()
    {
        $filename = __DIR__.'/data/example-countries.json';
        $ep = EveryPolitician::fromFilename($filename);
        $this->assertEquals('<EveryPolitician: '.$filename.'>', (string) $ep);
    }

    public function testCountriesFromLocalFile()
    {
        $filename = __DIR__.'/data/example-countries.json';
        $ep = EveryPolitician::fromFilename($filename);
        $countries = $ep->countries();
        $this->assertCount(2, $countries);
    }

    public function testCountries()
    {
        $this->mockHttpCall();

        $ep = new EveryPolitician();
        $countries = $ep->countries();
        $this->assertCount(2, $countries);
        $this->assertEquals('<Country: Åland>', (string) $countries[0]);
        $this->assertEquals('<Country: Argentina>', (string) $countries[1]);
    }

    public function testJsonOnlyFetchedOnce()
    {
        $this->mockHttpCall();

        $ep = new EveryPolitician();
        $ep->countries();
        $ep->countries();
    }

    /**
     * @expectedException EveryPolitician\EveryPolitician\Exceptions\NotFoundException
     * @expectedExceptionMessage Couldn't find the country with slug 'argentina'
     */
    public function testGetASingleCountryBadCase()
    {
        $this->mockHttpCall();

        $ep = new EveryPolitician();
        $ep->country('argentina');
    }

    public function testGetASingleCountry()
    {
        $this->mockHttpCall();

        $ep = new EveryPolitician();
        $country = $ep->country('Argentina');
        $this->assertEquals('Argentina', $country->name);
    }

    public function testGetACountryAndLegislature()
    {
        $this->mockHttpCall();

        $ep = new EveryPolitician();
        $o = $ep->countryLegislature('Argentina', 'Diputados');
        $country = $o[0];
        $legislature = $o[1];
        $this->assertEquals('Argentina', $country->name);
        $this->assertEquals('Cámara de Diputados', $legislature->name);
    }

    /**
     * @expectedException EveryPolitician\EveryPolitician\Exceptions\NotFoundException
     * @expectedExceptionMessage Couldn't find the legislature with slug 'FOO'
     */
    public function testGetACountryLegislatureCNotFound()
    {
        $this->mockHttpCall();

        $ep = new EveryPolitician();
        $ep->countryLegislature('Argentina', 'FOO');
    }

    /**
     * @expectedException EveryPolitician\EveryPolitician\Exceptions\NotFoundException
     * @expectedExceptionMessage Couldn't find the country with slug 'FOO'
     */
    public function testGetACountryLegislatureLNotFound()
    {
        $this->mockHttpCall();

        $ep = new EveryPolitician();
        $ep->countryLegislature('FOO', 'Diputados');
    }

    /**
     * @expectedException EveryPolitician\EveryPolitician\Exceptions\NotFoundException
     * @expectedExceptionMessage Couldn't find the country with slug 'FOO'
     */
    public function testGetACountryLegislatureNeitherFound()
    {
        $this->mockHttpCall();

        $ep = new EveryPolitician();
        $ep->countryLegislature('FOO', 'FOO');
    }
}
