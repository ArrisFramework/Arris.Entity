<?php

use PHPUnit\Framework\TestCase;
use Arris\Entity\Result;

class ResultTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
    }

    /**
     * @return void
     * @testdox Test Result with one arg for data
     */
    public function testOneArgForData()
    {
        $r = new Result();
        $r->set('a', 'b');

        $this->assertEquals('b', $r->a);
    }

    /**
     * @return void
     * @testdox Test Result with one arg for boolean values
     */
    public function testOneArgForBooleans()
    {
        $r = new Result();
        $r->success();

        $this->assertTrue($r->is_success);
        $this->assertFalse($r->is_error);
    }
    /**
     * @return void
     * @testdox Test Result with given message
     */
    public function TestMessage()
    {
        $r = new Result();
        $r->setMessage("any message");

        $this->assertEquals("any message", $r->message);
    }

    /**
     * @return void
     * @testdox Test Result with two args (string, array)
     */
    public function testTwoArgsStringAndArray()
    {
        $r = new Result('message', ['a' => 'b']);
        $r->set('a', 'b');
        $r->setMessage('message');

        $this->assertEquals('b', $r->a);
        $this->assertEquals('message', $r->message);
    }

    /**
     * @return void
     * @testdox Test Result
     */
    public function testTwoArgsBoolAndArray()
    {
        $r = new Result(false);

        $this->assertFalse($r->is_success);
        $this->assertTrue($r->is_error);
    }

    /**
     * @return void
     * @testdox Test Data
     */
    public function testData()
    {
        $r = new Result(true);
        $r->setData([
            'a' =>  'b'
        ])->setMessage('message');


        $this->assertFalse($r->is_error);
        $this->assertEquals('message', $r->message);
        $this->assertEquals('b', $r->getData('a'));
    }

}
