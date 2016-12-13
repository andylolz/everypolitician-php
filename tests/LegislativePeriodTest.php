<?php

namespace EveryPolitician\EveryPolitician;

use \GuzzleHttp;
use \Mockery;
use \PHPUnit_Framework_TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class LeglislativePeriodTest extends PHPUnit_Framework_TestCase
{
    use FakeResponseTrait;

    public function setUp()
    {
        date_default_timezone_set('Europe/London');
        $this->mockHttpCall();

        $this->ep = new EveryPolitician();
        $this->country = $this->ep->country('Argentina');
        $this->legislature = $this->country->legislature('Diputados');
        $this->period = $this->legislature->legislativePeriods()[0];
    }

    public function testStartDate()
    {
        $this->assertEquals('2015', $this->period->startDate);
    }

    public function testEndDate()
    {
        $this->assertEquals(null, $this->period->endDate);
    }

    public function testCsv()
    {
        $expectedCsv = [
            [
                'area' => 'BUENOS AIRES',
                'area_id' => 'area/buenos_aires',
                'chamber' => 'Cámara de Diputados',
                'email' => 'asegarra@diputados.gob.ar',
                'end_date' => '2015-12-09',
                'facebook' => '',
                'gender' => 'female',
                'group' => 'FRENTE PARA LA VICTORIA - PJ',
                'group_id' => 'frente_para_la_victoria_-_pj',
                'id' => 'b882751f-4014-4f6f-b3cf-e0a5d6d3c605',
                'image' => 'http://www4.hcdn.gob.ar/fotos/asegarra.jpg',
                'name' => 'ADELA ROSA SEGARRA',
                'sort_name' => 'SEGARRA, ADELA ROSA',
                'start_date' => '',
                'term' => '133',
                'twitter' => '',
            ],
            [
                'area' => 'BUENOS AIRES',
                'area_id' => 'area/buenos_aires',
                'chamber' => 'Cámara de Diputados',
                'email' => 'aperez@diputados.gob.ar',
                'end_date' => '',
                'facebook' => '',
                'gender' => 'male',
                'group' => 'FRENTE RENOVADOR',
                'group_id' => 'frente_renovador',
                'id' => '8efb1e0e-8454-4c6b-9f87-0d4fef875fd2',
                'image' => 'http://www4.hcdn.gob.ar/fotos/aperez.jpg',
                'name' => 'ADRIAN PEREZ',
                'sort_name' => 'PEREZ, ADRIAN',
                'start_date' => '',
                'term' => '133',
                'twitter' => 'adrianperezARG',
            ]
        ];

        Mockery::close();
        $url = 'https://raw.githubusercontent.com/everypolitician/'
            .'everypolitician-data/ba00071/data/Argentina/Diputados/term-133.csv';
        $csvStr = file_get_contents(__DIR__.'/data/example-period.csv');
        $response = new GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'text/csv'], $csvStr);
        Mockery::mock('overload:GuzzleHttp\Client')
            ->shouldReceive('get')
            ->with($url)
            ->andReturn($response);

        $csv = $this->period->csv();
        $this->assertEquals($expectedCsv, $csv);
    }
}
