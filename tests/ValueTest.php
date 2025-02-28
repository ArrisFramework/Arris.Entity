<?php

use PHPUnit\Framework\TestCase;
use Arris\Entity\Value;

class ValueTest extends TestCase
{
    public static function setUpBeforeClass():void
    {
        parent::setUpBeforeClass();
    }

    /**
     * @return void
     * @testdox all tests
     */
    public function testAssert()
    {
        $v = new Value(123);

        $this->assertIsInt($v->toInt());
        $this->assertIsString($v->toString());
        $this->assertIsBool($v->toBool());
        $this->assertIsArray($v->toArray());

        $this->assertEquals('123', $v->toString());
        $this->assertEquals(123, $v->toInt());
        $this->assertEquals([123], $v->toArray());
        $this->assertTrue($v->toBool());
    }

}