<?php

namespace Arris\Entity\Handler;

/**
 * Interface ISearchResult
 * Data Transfer Object
 */
interface ISearchResult
{
    /**
     * Возвращает найденный ключ
     *
     * @return null|int|string|bool|float
     */
    public function key();

    /**
     * Возвращает флаг успешного результата поиска
     *
     * @return bool
     */
    public function has(): bool;
}
