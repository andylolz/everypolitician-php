<?php

namespace EveryPolitician\EveryPolitician;

use \GuzzleHttp;
use \Mockery;

class LeglislatureMethodsTest extends \PHPUnit_Framework_TestCase
{
    use FakeResponseTrait;

    public function setUp()
    {
        $this->mockHttpCall();

        $this->ep = new EveryPolitician();
        $this->country = $this->ep->country('Argentina');
        $this->legislatures = $this->country->legislatures();
    }

    public function testLegislatureToString()
    {
        $legislatureStr = '<Legislature: Cámara de Diputados in Argentina>';
        $this->assertEquals($legislatureStr, (string) $this->legislatures[0]);
    }

    public function testLegislaturePopoloUrl()
    {
        $l = $this->legislatures[1];
        $popoloUrl = 'https://cdn.rawgit.com/everypolitician/'
            .'everypolitician-data/25257c4/'
            .'data/Argentina/Senado/ep-popolo-v1.0.json';
        $this->assertEquals($popoloUrl, $l->popoloUrl);
    }

    public function testDirectory()
    {
        $l = $this->legislatures[0];
        $this->assertEquals('Argentina/Diputados', $l->directory());
    }
}
