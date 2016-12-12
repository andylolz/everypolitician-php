<?php

namespace EveryPolitician\EveryPolitician;

class DataLoadingTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateEveryPolitician()
    {
        $toStr = '<EveryPolitician: https://raw.githubusercontent.com/'
            .'everypolitician/everypolitician-data/master/countries.json>';
        $ep = new EveryPolitician();
        $this->assertEquals($toStr, (string) $ep);
    }
}
