<?php

namespace EveryPolitician\EveryPolitician;

trait FakeResponseTrait
{
    public function tearDown()
    {
        \Mockery::close();
    }

    private function mockHttpCall()
    {
        $body = file_get_contents(__DIR__.'/data/example-countries.json');
        $response = new \GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/json'], $body);
        return \Mockery::mock('overload:\GuzzleHttp\Client')
            ->shouldReceive('get')
            ->once()
            ->andReturn($response)
            ->getMock();
    }
}
