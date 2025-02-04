<?php

use Arris\Entity\Handler\IValueHandler;
use Arris\Entity\Handler\ValueHandler;
use PHPUnit\Framework\TestCase;

class ValueHandlerTest  extends TestCase
{
    /**
     * @testdox Проверяем метод ValueHandler::asIs()
     *
     * @return void
     */
    public function testAsIs()
    {
        $value = new ValueHandler(null);
        self::assertTrue(
            is_null($value->asIs()),
            'Value of asIs() MUST BE null'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );

        $value = new ValueHandler(1);
        self::assertTrue(
            $value->asIs() === 1,
            'Value of asIs() MUST BE exact 1'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );

        $value = new ValueHandler('1');
        self::assertTrue(
            $value->asIs() === '1',
            'Value of asIs() MUST BE exact `1`'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );

        $value = new ValueHandler(true);
        self::assertTrue(
            $value->asIs() === true,
            'Value of asIs() MUST BE exact TRUE'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );

        $value = new ValueHandler(false);
        self::assertTrue(
            $value->asIs() === false,
            'Value of asIs() MUST BE exact FALSE'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );

        $value = new ValueHandler(1.1);
        self::assertTrue(
            $value->asIs() === 1.1,
            'Value of asIs() MUST BE exact 1.1'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );

        $value = new ValueHandler([]);
        self::assertTrue(
            empty(array_diff($value->asIs(), [])),
            'For for empty array value of asIs() MUST BE []'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );

        $value = new ValueHandler([false, 1, 'a']);
        self::assertTrue(
            empty(array_diff($value->asIs(), [false, 1, 'a'])),
            'MUST BE exact [false,1,`a`]'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );
    }

    /**
     * @testdox Проверяем метод ValueHandler::int()
     *
     * @return void
     */
    public function testInt()
    {
        $value = new ValueHandler(null);
        self::assertTrue(
            $value->int() === 0,
            'int() for NULL value MUST BE zero'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );

        $value = new ValueHandler(1);
        self::assertTrue(
            $value->int() === 1,
            'int() MUST BE exact 1'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );
    }

    /**
     * @testdox Проверяем метод ValueHandler::bool()
     *
     * @return void
     */
    public function testBool()
    {
        $value = new ValueHandler(null);
        self::assertTrue(
            $value->bool() === false,
            ' bool() for NULL value MUST BE false'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );

        $value = new ValueHandler(true);
        self::assertTrue(
            $value->bool() === true,
            'bool() MUST BE exact true'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );

        $value = new ValueHandler(false);
        self::assertTrue(
            $value->bool() === false,
            'bool() MUST BE exact false'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );
    }

    /**
     * @testdox Проверяем метод ValueHandler::str()
     *
     * @return void
     */
    public function testStr()
    {
        $value = new ValueHandler(null);
        self::assertTrue(
            $value->str() === '',
            'str() for NULL value MUST BE `` (empty string)'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );

        $value = new ValueHandler('a');
        self::assertTrue(
            $value->str() === 'a',
            ' str() MUST BE exact `a`'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );
    }

    /**
     * @testdox Проверяем метод ValueHandler::double()
     *
     * @return void
     */
    public function testDouble()
    {
        $value = new ValueHandler(null);
        self::assertTrue(
            $value->double() === 0.0,
            'double() for NULL value MUST BE 0.0'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );

        $value = new ValueHandler(1.1);
        self::assertTrue(
            $value->double() === 1.1,
            'Value of double() MUST BE exact 1.1'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );
    }

    /**
     * @testdox Проверяем метод ValueHandler::array()
     *
     * @return void
     */
    public function testArray()
    {
        $value = new ValueHandler(null);
        self::assertTrue(
            empty(array_diff($value->array(), [])),
            'For NULL value array() MUST BE []'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );

        $value = new ValueHandler(array(false, 1, 'a'));
        self::assertTrue(
            empty(array_diff($value->array(), [false, 1, 'a'])),
            'MUST BE exact [false,1,`a`]'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );
    }

    /**
     * @testdox Проверяем метод ValueHandler::type()
     *
     * @return void
     */
    public function testType()
    {
        $type = (new ValueHandler(null))->type();
        self::assertEquals(
            'NULL',
            $type,
            'For NULL value type() MUST BE `NULL`'
        );

        $type = (new ValueHandler(false))->type();
        self::assertEquals(
            'boolean',
            $type,
            'For false value type() MUST BE `boolean`'
        );

        $type = (new ValueHandler(1))->type();
        self::assertEquals(
            'integer',
            $type,
            'For 0 value type() MUST BE `integer`'
        );

        $type = (new ValueHandler(0.1))->type();
        self::assertEquals(
            'double',
            $type,
            'For 0.0 value type() MUST BE `double`'
        );

        $type = (new ValueHandler('a'))->type();
        self::assertEquals(
            'string',
            $type,
            'For `a` value type() MUST BE `string`'
        );

        $type = (new ValueHandler([]))->type();
        self::assertEquals(
            'array',
            $type,
            'For [] value type() MUST BE `array`'
        );

        $type = (new ValueHandler(new ValueHandler()))->type();
        self::assertEquals(
            'object',
            $type,
            'For (new ValueHandler()) value type() MUST BE `object`'
        );
    }

    /**
     * @testdox Проверяем метод ValueHandler::object()
     *
     * @return void
     */
    public function testObject()
    {
        /* @var $value ValueHandler */
        $value = (new ValueHandler(new ValueHandler(1)))->object();

        self::assertTrue(
            is_object($value),
            'For (new ValueHandler()) value of object() method'
            . ' MUST BE type of object'
        );
        self::assertTrue(
            $value instanceof ValueHandler,
            'For (new ValueHandler()) value of object() method'
            . ' MUST BE instance of ValueHandler'
        );
        self::assertTrue(
            $value->has(),
            'Flag `has` MUST BE true'
        );
        self::assertTrue(
            $value->asIs() === 1,
            'Value of asIs() MUST BE zero'
        );
    }

    /**
     * @testdox Проверяем создание незаданного Значения
     *
     * @return void
     */
    public function testUndefined()
    {
        $value = ValueHandler::asUndefined();

        self::assertFalse(
            $value->has(),
            'Flag `has` for undefined Value MUST BE false'
        );
        self::assertTrue(
            gettype($value->asIs()) === 'NULL',
            'For undefined Value type of returning value of'
            . ' asIs() MUST BE NULL'
        );
        self::assertTrue(
            is_null($value->asIs()),
            'For undefined Value asIs() MUST BE null'
        );
    }

    /**
     * @testdox Проверяем использование значения по умолчанию
     *
     * @return void
     */
    public function testWith()
    {
        $this->checkWithInt();
        $this->checkWithDouble();
        $this->checkWithBool();
        $this->checkWithStr();
        $this->checkWithArray();
        $this->checkWithObject();
    }

    /**
     * @return IValueHandler
     */
    private function checkWithInt()
    {
        $value = ValueHandler::asUndefined()->default(1);
        self::assertTrue(
            $value->int() === 1,
            'For undefined with(1) value of int() MUST BE exact 1'
        );
        self::assertFalse(
            $value->has(),
            'For undefined with(1) has() MUST BE false'
        );
        $value = (new ValueHandler(3))->default(1);
        self::assertTrue(
            $value->int() === 3,
            'For Value(3) with(1) value of int() MUST BE exact 3'
        );
        self::assertTrue(
            $value->has(),
            'For Value(3) with(1) has() MUST BE true'
        );

        return $value;
    }

    /**
     * @return IValueHandler
     */
    private function checkWithDouble()
    {
        $value = ValueHandler::asUndefined()->default(0.9);
        self::assertTrue(
            $value->double() === 0.9,
            'For undefined with(0.9) value of double()'
            . ' MUST BE exact 0.9'
        );
        self::assertFalse(
            $value->has(),
            'For undefined with(0.9) has() MUST BE false'
        );
        $value = (new ValueHandler(1.1))->default(0.9);
        self::assertTrue(
            $value->double() === 1.1,
            'For Value(1.1) with(0.9) value of double()'
            . ' MUST BE exact 1.1'
        );
        self::assertTrue(
            $value->has(),
            'For Value(1.1) with(0.9) has() MUST BE true'
        );

        return $value;
    }

    /**
     * @return IValueHandler
     */
    private function checkWithBool()
    {
        $value = ValueHandler::asUndefined()->default(true);
        self::assertTrue(
            $value->bool() === true,
            'For undefined with(true) value of bool()'
            . ' MUST BE exact true'
        );
        self::assertFalse(
            $value->has(),
            'For undefined with(true) has() MUST BE false'
        );
        $value = (new ValueHandler(false))->default(true);
        self::assertTrue(
            $value->bool() === false,
            'For Value(false) with(true) value of bool()'
            . ' MUST BE exact false'
        );
        self::assertTrue(
            $value->has(),
            'For Value(false) with(true) has() MUST BE true'
        );

        return $value;
    }

    /**
     * @return IValueHandler
     */
    private function checkWithStr()
    {
        $value = ValueHandler::asUndefined()->default('a');
        self::assertTrue(
            $value->str() === 'a',
            'For undefined with(`a`) value of str()'
            . ' MUST BE exact `a`'
        );
        self::assertFalse(
            $value->has(),
            'For undefined with(`a`) has() MUST BE false'
        );
        $value = (new ValueHandler('b'))->default('a');
        self::assertTrue(
            $value->str() === 'b',
            'For Value(`b`) with(`a`) value of str()'
            . ' MUST BE exact `b`'
        );
        self::assertTrue(
            $value->has(),
            'For Value(`b`) with(`a`) has() MUST BE true'
        );

        return $value;
    }

    /**
     * @return IValueHandler
     */
    private function checkWithArray()
    {
        $value = ValueHandler::asUndefined()->default([0 => 1]);
        self::assertTrue(
            $value->array()[0] === 1,
            'For undefined with([0 => 1]) value of asArray()[0]'
            . ' MUST BE exact 1'
        );
        self::assertFalse(
            $value->has(),
            'For undefined with([0 => 1]) has() MUST BE false'
        );
        $value = (new ValueHandler([2 => 3]))->default([0 => 1]);
        self::assertTrue(
            $value->array()[2] === 3,
            'For Value([2 => 3]) with([0 => 1])'
            . 'value of asArray()[2] MUST BE exact 3'
        );
        self::assertTrue(
            $value->has(),
            'For Value([2 => 3]) with([0 => 1]) has() MUST BE true'
        );

        return $value;
    }

    /**
     * @return IValueHandler
     */
    private function checkWithObject()
    {
        $value = ValueHandler::asUndefined()->default(new ValueHandler(1));
        self::assertTrue(
            $value->object()->int() === 1,
            'For undefined with() (new ValueHandler(1))'
            . ' value of object()->int() MUST BE exact 1'
        );
        self::assertFalse(
            $value->has(),
            'For undefined with() (new ValueHandler(1))'
            . ' has() MUST BE false'
        );
        $value = (new ValueHandler(new ValueHandler(2)))
            ->default(new ValueHandler(1));
        self::assertTrue(
            $value->object()->int() === 2,
            'For Value(ValueHandler(2)) with(ValueHandler(1))'
            . 'value of object()->int() MUST BE exact 2'
        );
        self::assertTrue(
            $value->has(),
            'For Value(ValueHandler(2)) with(ValueHandler(1))'
            . ' has() MUST BE true'
        );

        return $value;
    }

}