<?php

namespace Arris\Entity\Helper;

use Countable;
use ArrayAccess;
use ArrayIterator;
use JsonSerializable;
use IteratorAggregate;

class Dot implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    protected array $items = [];
    protected string $delimiter = ".";

    public function __construct($items = [], $parse = false, $delimiter = ".")
    {
        $items = $this->getArrayItems($items);

        $this->delimiter = $delimiter ?: ".";

        if ($parse === true) {
            $this->set($items);
            return;
        }

        $this->items = $items;
    }

    /**
     * Set a given key / value pair or pairs
     * if the key doesn't exist already
     *
     * @param array|int|string $keys
     * @param mixed $value
     * @return Dot
     */
    public function add($keys, $value = null):self
    {
        if (is_array($keys)) {
            foreach ($keys as $k => $v) {
                $this->add($k, $v);
            }
        } elseif (is_null($this->get($keys))) {
            $this->set($keys, $value);
        }

        return $this;
    }

    /**
     * Return all the stored items
     * @return array
     */
    public function all():array
    {
        return $this->items;
    }

    /**
     * Delete the contents of a given key or keys
     *
     * @param array|int|string|null $keys
     * @return Dot
     */
    public function clear($keys = null):self
    {
        if (is_null($keys)) {
            $this->items = [];
            return $this;
        }

        $keys = (array) $keys;

        foreach ($keys as $key) {
            $this->set($key, []);
        }

        return $this;
    }

    /**
     * Delete the given key or keys
     *
     * @param array|int|string $keys
     * @return Dot
     */
    public function delete($keys):self
    {
        $keys = (array) $keys;

        foreach ($keys as $key) {
            if ($this->exists($this->items, $key)) {
                unset($this->items[$key]);

                continue;
            }

            $items = &$this->items;
            $segments = explode($this->delimiter, $key);
            $lastSegment = array_pop($segments);

            foreach ($segments as $segment) {
                if (!isset($items[$segment]) || !is_array($items[$segment])) {
                    continue 2;
                }

                $items = &$items[$segment];
            }

            unset($items[$lastSegment]);
        }

        return $this;
    }

    /**
     * Checks if the given key exists in the provided array.
     *
     * @param  array      $array Array to validate
     * @param  int|string $key   The key to look for
     *
     * @return bool
     */
    protected function exists($array, $key):bool
    {
        return array_key_exists($key, $array);
    }

    /**
     * Flatten an array with the given character as a key delimiter
     *
     * @param string $delimiter
     * @param  array|null $items
     * @param  string     $prepend
     * @return array
     */
    public function flatten(string $delimiter = '.', $items = null, $prepend = ''): array
    {
        $flatten = [];

        if (is_null($items)) {
            $items = $this->items;
        }

        foreach ($items as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $flatten = array_merge(
                    $flatten,
                    $this->flatten($delimiter, $value, $prepend.$key.$delimiter)
                );
            } else {
                $flatten[$prepend.$key] = $value;
            }
        }

        return $flatten;
    }

    /**
     * Return the value of a given key
     *
     * @param  int|string|null $key
     * @param  mixed           $default
     * @return mixed
     */
    public function get($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->items;
        }

        if ($this->exists($this->items, $key)) {
            return $this->items[$key];
        }

        if (strpos($key, $this->delimiter) === false) {
            return $default;
        }

        $items = $this->items;

        foreach (explode($this->delimiter, $key) as $segment) {
            if (!is_array($items) || !$this->exists($items, $segment)) {
                return $default;
            }

            $items = &$items[$segment];
        }

        return $items;
    }

    /**
     * Return the given items as an array
     *
     * @param  mixed $items
     * @return array
     */
    protected function getArrayItems($items): array
    {
        if (is_array($items)) {
            return $items;
        }

        if ($items instanceof self) {
            return $items->all();
        }

        return (array) $items;
    }

    /**
     * Check if a given key or keys exists
     *
     * @param  array|int|string $keys
     * @return bool
     */
    public function has($keys): bool
    {
        $keys = (array) $keys;

        if (!$this->items || $keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            $items = $this->items;

            if ($this->exists($items, $key)) {
                continue;
            }

            foreach (explode($this->delimiter, $key) as $segment) {
                if (!is_array($items) || !$this->exists($items, $segment)) {
                    return false;
                }

                $items = $items[$segment];
            }
        }

        return true;
    }

    /**
     * Check if a given key or keys are empty
     *
     * @param  array|int|string|null $keys
     * @return bool
     */
    public function isEmpty($keys = null): bool
    {
        if (is_null($keys)) {
            return empty($this->items);
        }

        $keys = (array) $keys;

        foreach ($keys as $key) {
            if (!empty($this->get($key))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Merge a given array or a Dot object with the given key
     * or with the whole Dot object
     *
     * @param array|string|self $key
     * @param array|self $value
     * @return Dot
     */
    public function merge($key, $value = []):self
    {
        if (is_array($key)) {
            $this->items = array_merge($this->items, $key);
        } elseif (is_string($key)) {
            $items = (array) $this->get($key);
            $value = array_merge($items, $this->getArrayItems($value));

            $this->set($key, $value);
        } elseif ($key instanceof self) {
            $this->items = array_merge($this->items, $key->all());
        }
        return $this;
    }

    /**
     * Recursively merge a given array or a Dot object with the given key
     * or with the whole Dot object.
     *
     * Duplicate keys are converted to arrays.
     *
     * @param array|string|self $key
     * @param array|self $value
     * @return Dot
     */
    public function mergeRecursive($key, $value = []):self
    {
        if (is_array($key)) {
            $this->items = array_merge_recursive($this->items, $key);
        } elseif (is_string($key)) {
            $items = (array) $this->get($key);
            $value = array_merge_recursive($items, $this->getArrayItems($value));

            $this->set($key, $value);
        } elseif ($key instanceof self) {
            $this->items = array_merge_recursive($this->items, $key->all());
        }

        return $this;
    }

    /**
     * Recursively merge a given array or a Dot object with the given key
     * or with the whole Dot object.
     *
     * Instead of converting duplicate keys to arrays, the value from
     * given array will replace the value in Dot object.
     *
     * @param array|string|self $key
     * @param array|self $value
     * @return Dot
     */
    public function mergeRecursiveDistinct($key, $value = []):self
    {
        if (is_array($key)) {
            $this->items = $this->arrayMergeRecursiveDistinct($this->items, $key);
        } elseif (is_string($key)) {
            $items = (array) $this->get($key);
            $value = $this->arrayMergeRecursiveDistinct($items, $this->getArrayItems($value));

            $this->set($key, $value);
        } elseif ($key instanceof self) {
            $this->items = $this->arrayMergeRecursiveDistinct($this->items, $key->all());
        }

        return $this;
    }

    /**
     * Merges two arrays recursively. In contrast to array_merge_recursive,
     * duplicate keys are not converted to arrays but rather overwrite the
     * value in the first array with the duplicate value in the second array.
     *
     * @param  array $array1 Initial array to merge
     * @param  array $array2 Array to recursively merge
     * @return array
     */
    protected function arrayMergeRecursiveDistinct(array $array1, array $array2): array
    {
        $merged = &$array1;

        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->arrayMergeRecursiveDistinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * Return the value of a given key and
     * delete the key
     *
     * @param  int|string|null $key
     * @param  mixed           $default
     * @return mixed
     */
    public function pull($key = null, $default = null)
    {
        if (is_null($key)) {
            $value = $this->all();
            $this->clear();

            return $value;
        }

        $value = $this->get($key, $default);
        $this->delete($key);

        return $value;
    }

    /**
     * Push a given value to the end of the array
     * in a given key
     *
     * @param mixed $key
     * @param mixed $value
     * @return Dot
     */
    public function push($key, $value = null):self
    {
        if (is_null($value)) {
            $this->items[] = $key;

            return $this;
        }

        $items = $this->get($key);

        if (is_array($items) || is_null($items)) {
            $items[] = $value;
            $this->set($key, $items);
        }

        return $this;
    }

    /**
     * Replace all values or values within the given key
     * with an array or Dot object
     *
     * @param array|string|self $key
     * @param array|self $value
     * @return Dot
     */
    public function replace($key, $value = [])
    {
        if (is_array($key)) {
            $this->items = array_replace($this->items, $key);
        } elseif (is_string($key)) {
            $items = (array) $this->get($key);
            $value = array_replace($items, $this->getArrayItems($value));

            $this->set($key, $value);
        } elseif ($key instanceof self) {
            $this->items = array_replace($this->items, $key->all());
        }

        return $this;
    }

    /**
     * Set a given key / value pair or pairs
     *
     * @param array|int|string $keys
     * @param mixed $value
     * @return Dot
     */
    public function set($keys, $value = null):self
    {
        if (is_array($keys)) {
            foreach ($keys as $k => $v) {
                $this->set($k, $v);
            }

            return $this;
        }

        $items = &$this->items;

        foreach (explode($this->delimiter, $keys) as $key) {
            if (!isset($items[$key]) || !is_array($items[$key])) {
                $items[$key] = [];
            }

            $items = &$items[$key];
        }

        $items = $value;

        return $this;
    }

    /**
     * Replace all items with a given array
     *
     * @param mixed $items
     * @return Dot
     */
    public function setArray($items):self
    {
        $this->items = $this->getArrayItems($items);

        return $this;
    }

    /**
     * Replace all items with a given array as a reference
     *
     * @param array $items
     * @return Dot
     */
    public function setReference(array &$items):self
    {
        $this->items = &$items;

        return $this;
    }

    /**
     * Return the value of a given key or all the values as JSON
     *
     * @param  mixed  $key
     * @param  int    $options
     * @return string
     */
    public function toJson($key = null, $options = 0): string
    {
        if (is_string($key)) {
            return json_encode($this->get($key), $options);
        }

        $options = $key === null ? 0 : $key;

        return json_encode($this->items, $options);
    }

    /*
     * --------------------------------------------------------------
     * ArrayAccess interface
     * --------------------------------------------------------------
     */

    /**
     * Check if a given key exists
     *
     * @param  int|string $offset
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * Return the value of a given key
     *
     * @param  int|string $offset
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Set a given value to the given key
     *
     * @param int|string|null $offset
     * @param mixed           $value
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;

            return;
        }

        $this->set($offset, $value);
    }

    /**
     * Delete the given key
     *
     * @param int|string $offset
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        $this->delete($offset);
    }

    /*
     * --------------------------------------------------------------
     * Countable interface
     * --------------------------------------------------------------
     */

    /**
     * Return the number of items in a given key
     *
     * @param  int|string|null $key
     * @return int
     */
    public function count($key = null):int
    {
        return count($this->get($key));
    }

    /*
     * --------------------------------------------------------------
     * IteratorAggregate interface
     * --------------------------------------------------------------
     */

    /**
     * Get an iterator for the stored items
     *
     * @return \ArrayIterator
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /*
     * --------------------------------------------------------------
     * JsonSerializable interface
     * --------------------------------------------------------------
     */

    /**
     * Return items for JSON serialization
     *
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->items;
    }
}

# -eof- #
