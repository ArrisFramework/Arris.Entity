<?php

namespace Arris\Entity\Handler;

class Factory implements IFactory
{
    public static function getValueHandler($value = null): IValueHandler
    {
        $result = new ValueHandler($value);

        return $result;
    }

    public static function getUndefinedValue(): IValueHandler
    {
        $result = ValueHandler::asUndefined();

        return $result;
    }
}
