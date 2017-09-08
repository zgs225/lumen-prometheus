<?php

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function testIsValidMetricName()
    {
        $validNames   = ['lordv3_bill_total', '_lord_v3:public:world'];
        $invalidNames = ['1lord:public', 'public_world-hello'];

        foreach ($validNames as $name) {
            $this->assertTrue(isValidMetricName($name));
        }

        foreach($invalidNames as $name) {
            $this->assertFalse(isValidMetricName($name));
        }
    }
}
