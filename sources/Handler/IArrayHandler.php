<?php

namespace Arris\Entity\Handler;

use Generator;
use Iterator;
use JsonSerializable;

/**
 * Interface IArrayHandler
 */
interface IArrayHandler extends Iterator, JsonSerializable
{
    /**
     * Получить элемент массива
     *
     * @param $key int|bool|string|null индекс элемента
     *
     * @return IValueHandler
     */
    public function get($key = null): IValueHandler;

    /**
     * Извлекает следующее значение, кроме массивов
     *
     * @return Generator
     */
    public function getting();

    /**
     * Проверяет имеет ли массив заданный индекс
     *
     * @param $key int|bool|string|null индекс искомого элемента
     *
     * @return bool
     */
    public function has($key = null): bool;

    /**
     * Возвращает IArrayHandler для вложенного массива
     *
     * @param $key int|bool|string|null индекс элемента с массивом
     *
     * @return IArrayHandler
     */
    public function pull($key = null): IArrayHandler;

    /**
     * Извлекает следующий массив
     * Значение будет экземпляром интерфейса IArrayHandler
     *
     * @return Generator
     */
    public function pulling();

    /**
     * возвращает флаг "Массив не задан"
     *
     * @return bool
     */
    public function isUndefined(): bool;

    /**
     * Возвращает исходный массив
     *
     * @return array
     */
    public function raw(): array;
}
