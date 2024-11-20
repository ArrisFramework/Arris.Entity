<?php

namespace Arris\Entity;

// use Arris\Entity\Helper\Dot;

#[\AllowDynamicProperties]
class Result implements \ArrayAccess, \Serializable
{
    /**
     * @var bool
     */
    public bool $is_success = true;

    /**
     * @var bool
     */
    public bool $is_error = false;

    /**
     * @var string
     */
    public string $message = '';

    /**
     * @var string
     */
    public string $code = '';

    /**
     * @var array
     */
    public array $messages = [];

    /**
     * @var array
     */
    public array $data = [];

    public function __construct(bool $is_success = true, $message = null)
    {
        $this->is_success = $is_success;
        $this->is_error = !$is_success;

        if (!is_null($message)) {
            $this->setMessage($message);
        }
    }

    /**
     * Устанавливаем состояние (аналогично конструктору), но у экземпляра.
     *
     * @param bool $is_success
     * @return $this
     */
    public function setState(bool $is_success = true):Result
    {
        $this->is_success = $is_success;
        $this->is_error = !$is_success;

        return $this;
    }

    /**
     * Устанавливаем произвольный property
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function set($key, $value): Result
    {
        $this->__set($key, $value);

        return $this;
    }

    /**
     * Устанавливаем данные
     *
     * @param array $data
     * @return $this
     */
    public function setData(array $data = []): Result
    {
        foreach ($data as $k => $v) {
            $this->__setData($k, $v);
        }

        return $this;
    }

    /**
     * Устанавливает цифровой код (может использоваться как код ошибки)
     *
     * @param $code string|int
     * @return $this
     */
    public function setCode($code):Result
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Устанавливаем единичное сообщение
     * Если сообщение пустое - не устанавливаем!
     *
     * @param string $message
     * @param ...$args
     * @return $this
     */
    public function setMessage(string $message = '', ...$args): Result
    {
        if (!empty($message)) {
            if (!empty($args[0])) {
                $this->message = \vsprintf($message, $args[0]);
            } else {
                $this->message = $message;
            }
        }

        /*if (func_num_args() > 1) {
            $this->message = vsprintf($message, $args);
        } else {
            $this->message = $message;
        }*/

        return $this;
    }

    /**
     * Добавляем сообщение к массиву сообщений
     *
     * @param string $message
     * @param ...$args
     * @return $this
     */
    public function addMessage(string $message, ...$args): Result
    {
        if (func_num_args() > 1) {
            $this->messages[] = \vsprintf($message, $args);
        } else {
            $this->messages[] = $message;
        }

        return $this;
    }

    /**
     * Устанавливает признак: результат УСПЕШЕН
     *
     * @return $this
     */
    public function success(string $message = '', ...$args): Result
    {
        $this->is_success = true;
        $this->is_error = false;

        $this->setMessage($message, $args);

        return $this;
    }

    /**
     * Устанавливает признак: результат ОШИБОЧЕН.
     * Если message пустой - он не должен быть установлен!
     *
     * @return $this
     */
    public function error(string $message = '', ...$args): Result
    {
        $this->is_success = false;
        $this->is_error = true;

        $this->setMessage($message, $args);

        return $this;
    }

    /* === Getters === */

    /**
     * @return string
     */
    public function getMessage():string
    {
        return $this->message;
    }

    /**
     * @param bool $implode
     * @param string $glue
     * @param array $brackets
     * @return array|string
     */
    public function getMessages(bool $implode = false, string $glue = ',', array $brackets = ['[', ']'])
    {
        if ($implode === false) {
            return $this->messages;
        }

        $imploded = \implode($glue, $this->messages);

        if (!empty($brackets)) {
            switch (\count($brackets)) {
                case 0: {
                    $brackets[1] = $brackets[0] = '';
                    break;
                }
                case 1: {
                    $brackets[1] = $brackets[0];
                    break;
                }
            }

            $imploded = $brackets[0] . $imploded . $brackets[1];
        }

        return $imploded;
    }

    /**
     * Возвращает код
     *
     * @return string|int
     */
    public function getCode()
    {
        return $this->code;
    }

    public function getData($key = null, $default = null)
    {
        // return (new Dot($this->data))->get($key, $default);

        return
            is_null($key)
            ? $this->data
            : (array_key_exists($key, $this->data) ? $this->data[$key] : $default);
    }

    public function getAll():array
    {
        return (array)$this;
    }

    public function get($key = null, $default = null)
    {
        if (\property_exists($this, $key)) {
            return $this->{$key};
        } elseif (\array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } else {
            return $default;
        }
    }

    public function has($key):bool
    {
        return
            \property_exists($this, $key)
            ||
            \array_key_exists($key, $this->data);

        /*if (\property_exists($this, $key)) {
            return true;
        } elseif (\array_key_exists($key, $this->data)) {
            return true;
        } else {
            return false;
        }*/
    }

    /**
     * Getter.
     * Handles access to non-existing property
     *
     * ? если ключ не найден в списке property, то нужно проверить его в массиве $data и только потом вернуть null
     *
     * @param string $key
     * @return null
     */
    public function __get(string $key)
    {
        if (\property_exists($this, $key)) {
            return $this->{$key};
        } elseif (\array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } else {
            return null;
        }
    }

    /**
     * Setter
     * Handles access to non-existing property
     *
     * ? если ключ не найден в списке property, то нужно добавить его в массив $data
     *
     * @param string $key
     * @param $value
     * @return void
     */
    public function __set(string $key, $value = null): void
    {
        $this->{$key} = $value;
    }

    public function __setData(string $key, $value = null):void
    {
        $this->data[$key] = $value;
    }

    /**
     * @param $offset
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset): bool
    {
        return \property_exists($this, $offset) || \array_key_exists($offset, $this->data);
    }

    /**
     * @param $offset
     * @return mixed|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * @param $offset
     * @param $value
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    /**
     * @param $offset
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }

    /**
     * Сериализатор
     *
     * @return false|string|null
     */
    public function serialize()
    {
        return \json_encode([
            'is_success'    =>  $this->is_success,
            'is_error'      =>  $this->is_error,
            'message'       =>  $this->message,
            'messages'      =>  $this->messages,
            'code'          =>  $this->code,
            'data'          =>  $this->data
        ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE /*| JSON_THROW_ON_ERROR*/);
    }

    /**
     * Десериализатор
     *
     * @param $data
     * @return $this
     */
    public function unserialize($data): Result
    {
        $json = \json_decode($data, true);
        $this->__unserialize($json);
        unset($json);

        return $this;
    }

    public function __serialize(): array
    {
        return (array)$this;
    }

    public function __unserialize(array $data): void
    {
        $this->is_success   = array_key_exists('is_success', $data) ? $data['is_success'] : true;
        $this->is_error     = array_key_exists('is_error', $data) ? $data['is_error'] : false;
        $this->message      = array_key_exists('message', $data) ? $data['message'] : '';
        $this->code         = array_key_exists('code', $data) ? $data['code'] : '';
        $this->data         = array_key_exists('data', $data) ? $data['data'] : '';
        $this->messages     = array_key_exists('messages', $data) ? $data['messages'] : '';
    }
}
