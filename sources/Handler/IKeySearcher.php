<?php

namespace Arris\Entity\Handler;

/**
 * Interface IKeySearcher
 */
interface IKeySearcher
{
    /**
     * Искать заданный индекс
     *
     * @param null|int|string|bool|float $key искомый индекс, если
     *                                        не задан, то будет
     *                                        использован индекс
     *                                        текущего элемента
     *
     * @return ISearchResult
     */
    public function search($key = null): ISearchResult;
}
