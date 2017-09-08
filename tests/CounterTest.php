<?php

use PHPUnit\Framework\TestCase;
use Prometheus\Metrics\Counter;

class CounterTest extends TestCase
{
    public function testAdd()
    {
        $counter = new Counter("lord_v3", "task", "total", "统计生成任务总数", ['complex' => 4, 'admin_user' => 10069]);
        $this->assertEquals('lord_v3_task_total', $counter->getFQName());
    }
}