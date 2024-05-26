<?php

namespace Arris\Entity\Handler;

interface IFactory
{
    /**
     * Возвращает IValueHandler
     *
     * @param null $value
     * @return IValueHandler
     */
    public static function getValueHandler($value = null): IValueHandler;

    /**
     * Возвращает IValueHandler с незаданным значением
     *
     * @return IValueHandler
     */
    public static function getUndefinedValue(): IValueHandler;
}
