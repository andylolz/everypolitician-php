<?php

namespace EveryPolitician\EveryPolitician;

use \GuzzleHttp;
use \Mockery;

trait FakeResponseTrait
{
    public function tearDown()
    {
        \Mockery::close();
    }

    private function mockHttpCall()
    {
        $url = 'https://raw.githubusercontent.com/everypolitician/'
            .'everypolitician-data/master/countries.json';
        $body = file_get_contents(__DIR__.'/data/example-countries.json');
        $response = new GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/json'], $body);
        Mockery::mock('overload:GuzzleHttp\Client')
            ->shouldReceive('get')
            ->with($url)
            ->once()
            ->andReturn($response);
    }
}
