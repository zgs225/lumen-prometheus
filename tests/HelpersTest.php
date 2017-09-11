<?php

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function testIsValidMetricName()
    {
        $validNames   = ['lordv3_bill_total', '_lord_v3:public:world'];
        $invalidNames = ['1lord:public', 'public_world-hello', ''];

        foreach ($validNames as $name) {
            $this->assertTrue(isValidMetricName($name));
        }

        foreach($invalidNames as $name) {
            $this->assertFalse(isValidMetricName($name));
        }
    }

    public function testIsValidLabelName()
    {
        $validLabels   = ['method', 'request_path', 'lordv3', '_public'];
        $invalidLabels = ['__public', '3public', 'public-static'];

        foreach($validLabels as $label) {
            $this->assertTrue(isValidLabelName($label));
        }

        foreach($invalidLabels as $label) {
            $this->assertFalse(isValidLabelName($label));
        }
    }

    public function testEscapeString()
    {
        $t1 = "hello world\", public\\world\n";
        $t2 = escapeString($t1);
        $this->assertEquals("hello world\\\", public\\\\world\\\n", $t2);
    }
}
