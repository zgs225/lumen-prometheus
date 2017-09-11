<?php

use PHPUnit\Framework\TestCase;
use Prometheus\Exceptions\DuplicatedMetricException;
use Prometheus\Metrics\Counter;
use Prometheus\Registry\BaseRegistry;

class RegistryTest extends TestCase
{
    public function testRegister()
    {
        $counter  = new Counter("lord_v3", "task", "total", "统计生成任务总数", ['complex' => 4, 'admin_user' => 10069]);
        $registry = BaseRegistry::defaultRegistry();

        $registry->register($counter);
        $this->expectException(DuplicatedMetricException::class);
        $registry->register($counter);
    }

    public function testAll()
    {
        $counter  = new Counter("lord_v3", "task", "total", "统计生成任务总数", ['complex' => 4, 'admin_user' => 10069]);
        $registry = BaseRegistry::defaultRegistry();
        $registry->register($counter);
        $count    = count($registry->all());
        $this->assertEquals(1, $count);
    }
}