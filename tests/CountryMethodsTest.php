<?php

namespace EveryPolitician\EveryPolitician;

class CountryMethodsTest extends \PHPUnit_Framework_TestCase
{
    use FakeResponseTrait;

    public function setUp()
    {
        $this->mockHttpCall();

        $this->ep = new EveryPolitician();
        $this->country = $this->ep->country('Aland');
    }

    public function testCountryToString()
    {
        $this->assertEquals('<Country: Åland>', (string) $this->country);
    }

    public function testGetLegislatures()
    {
        $ls = $this->country->legislatures();
        $this->assertCount(1, $ls);
    }
}
