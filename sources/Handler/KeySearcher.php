<?php

namespace Arris\Entity\Handler;

/**
 * Class KeySearcher
 */
class KeySearcher implements IKeySearcher
{
    /**
     * Источник данных для поиска
     *
     * @var array
     */
    private $_source = [];

    /**
     * KeySearcher constructor.
     *
     * @param array $data массив для поиска индекса
     *
     * @return void
     */
    public function __construct(array &$data)
    {
        $this->_source = $data;
    }

    /**
     * Искать заданный индекс
     *
     * @param null $key искомый индекс, если не задан,
     *                  то будет использован индекс текущего элемента
     *
     * @return ISearchResult
     */
    public function search($key = null): ISearchResult
    {
        $result = new SearchResult(false, $key);

        $data = $this->_source;
        $keyExists = array_key_exists($key, $data);
        if ($keyExists) {
            $result = new SearchResult(true, $key);
        }
        $isNullKey = false;
        if (!$keyExists) {
            $isNullKey = is_null($key);
        }
        if ($isNullKey) {
            $key = key($data);
        }
        if ($isNullKey && !$keyExists) {
            $keyExists = array_key_exists($key, $data);
        }
        if ($isNullKey && $keyExists) {
            $result = new SearchResult(true, $key);
        }

        return $result;
    }
}
