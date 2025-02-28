<?php

use PHPUnit\Framework\TestCase;
use Arris\Entity\Result;

class ResultTest extends TestCase
{
    public static function setUpBeforeClass():void
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
    public function testMessage()
    {
        $r = new Result();
        $r->setMessage("any message");

        $this->assertEquals("any message", $r->getMessage());
    }

    /**
     * @return void
     * @testdox test Result with given message with sprintf args
     */
    public function testMessageWithArgs()
    {
        $r = new Result();
        $r->setMessage("Any message with [%s] and [%s]", [ 1, 2 ]);
        $this->assertEquals("Any message with [1] and [2]", $r->getMessage());
    }

    /**
     * @return void
     * @testdox test Result with multiply imploded messages with sprintf args
     */
    public function testMessagesWithArgs()
    {
        $r = new Result();
        $r->addMessage("%s => %s", [ 1, 2 ]);
        $r->addMessage("%s => %s", [ 4, 5 ]);

        $this->assertEquals("1 => 2 , 4 => 5", $r->getMessages(true, ' , ', []));
    }

    /**
     * @return void
     * @testdox Test Result with two args (string, array)
     */
    public function testTwoArgsStringAndArray()
    {
        $r = new Result(true, 'message');
        $r->set('a', 'b');
        $r->setMessage('message');

        $this->assertTrue($r->is_success);
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

    /**
     * @return void
     * @testdoc test raw fields
     */
    public function testRawFields()
    {
        $r = new Result();

        $r->raw_bool = true;
        $this->assertTrue($r->raw_bool);

        $r->raw_bool = false;
        $this->assertFalse($r->raw_bool);

        $r->raw_int = 123;
        $this->assertEquals(123, $r->raw_int);

        $r->raw_string = 'foo';
        $this->assertEquals('foo', $r->raw_string);

        $r->raw_array = ['foo', 'bar'];
        $this->assertIsArray($r->raw_array);
        $this->assertContains('foo', $r->raw_array);
        $this->assertNotContains('baz', $r->raw_array);

        $r->raw_object->key = 133;

        $this->assertIsObject($r->raw_object);
        $this->assertEquals(133, $r->raw_object->key);
    }

}
