<?php

namespace Unit;

use App\Stats;
use PHPUnit\Framework\TestCase;

class StatsTest extends TestCase
{
    public function testToJson()
    {
        $stats = new Stats('../../access_log');
        $example = file_get_contents('../../example.json');
        $this->assertJsonStringEqualsJsonString($example, $stats->toJson());
    }
}
