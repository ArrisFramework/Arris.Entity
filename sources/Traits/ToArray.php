<?php

namespace Arris\Entity\Traits;

trait ToArray
{
    /**
     * Преобразует объект в массив с возможностью фильтрации свойств
     *
     * @param array $included Включаемые свойства (если пусто - все публичные)
     * @param array $excluded Исключаемые свойства (имеет приоритет над included)
     * @return array
     */
    public function toArray(array $included = [], array $excluded = []): array
    {
        $result = [];
        $properties = get_object_vars($this);

        foreach ($properties as $key => $value) {
            // Проверка на исключение (имеет высший приоритет)
            if (!empty($excluded) && in_array($key, $excluded, true)) {
                continue;
            }

            // Проверка на включение (если $included не пуст)
            if (!empty($included) && !in_array($key, $included, true)) {
                continue;
            }

            // Обработка значения
            $result[$key] = $this->convertValueToArray($value);
        }

        return $result;
    }

    /**
     * Конвертирует значение в массив, если это возможно
     *
     * @param mixed $value
     * @return mixed
     */
    protected function convertValueToArray($value)
    {
        if (is_object($value) && method_exists($value, 'toArray')) {
            return $value->toArray();
        }

        if (is_array($value)) {
            return array_map([$this, 'convertValueToArray'], $value);
        }

        return $value;
    }

    /**
     * Преобразует объект в JSON-строку
     *
     * @param bool $pretty Форматировать вывод с отступами
     * @param array $included Включаемые свойства
     * @param array $excluded Исключаемые свойства
     * @return string
     * @throws \RuntimeException Если преобразование в JSON не удалось
     */
    public function toJSON(
        bool $pretty = false,
        array $included = [],
        array $excluded = []
    ): string {
        $options = $pretty ? JSON_PRETTY_PRINT : 0;
        $array = $this->toArray($included, $excluded);
        $json = json_encode($array, $options);

        if ($json === false) {
            throw new \RuntimeException('JSON encoding failed: ' . json_last_error_msg());
        }

        return $json;
    }
}