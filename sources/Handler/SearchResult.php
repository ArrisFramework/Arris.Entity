<?php

namespace Arris\Entity\Handler;

/**
 * Class SearchResult
 * Data Transfer Object
 */
class SearchResult implements ISearchResult
{

    private $_key = null;
    private $_has = false;

    /**
     * SearchResult constructor.
     *
     * @param $success bool  успех поиска
     * @param $key     mixed при успехе значение найденного ключа
     */
    public function __construct(bool $success, $key)
    {
        $this->_key = $key;
        $this->_has = $success;
    }

    /**
     * Возвращает найденный ключ
     *
     * @return null|int|string|bool|float
     */
    public function key()
    {
        return $this->_key;
    }

    /**
     * Возвращает флаг успешного результата поиска
     *
     * @return bool
     */
    public function has(): bool
    {
        return $this->_has;
    }
}
