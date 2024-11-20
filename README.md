# Arris.Entities 

Entity types for Arris µFramework

## Entity/Result

Класс Result

```php
$t = new \Arris\Entity\Result();

$t->setMessage("it works!");

$t->setData('foo', [
    'a' =>  5,
    'b' =>  7
])
    ->addData('foo', [
    'c' =>  2,
    'd' =>  4
])
    ->addMessage('xxx')
    ->addMessage('yyy')
    ->error('error')
    ->setCode(123);

var_dump(
    $t->serialize()
);
```

Реализует методы:

- Основные:
  - `__construct(bool $is_success = true, $message = null)`
  - `getIt():array`
- Состояние результата:
  - `setState(bool $is_success = true):Result` - Устанавливаем состояние (аналогично конструктору), но у экземпляра.
  - `getState():bool` - Возвращает состояние результата
  - `success(string $message = '', ...$args):Result` - Устанавливает признак: результат УСПЕШЕН
  - `error(string $message = '', ...$args): Result` - Устанавливает признак: результат ОШИБОЧЕН.
- Одиночные ключи-свойства:
  - `set($key, $value): Result` - Устанавливаем произвольный одиночный ключ-свойство
  - `get($key = null, $default = null)` - Возвращает значение одиночного ключа-свойства
  - `has($key):bool` - Проверяет наличие одиночного ключа-свойства, сначала в списке свойств, потом в массиве данных
- Код результата:
  - `setCode($code):Result` - Устанавливает цифровой код (может использоваться как код ошибки)
  - `getCode()` - Возвращает код
- Сообщения (messages):
  - `setMessage(string $message = '', ...$args): Result` - Устанавливаем единичное сообщение
  - `getMessage():string` - Возвращаем единичное сообщение
  - `addMessage(string $message, ...$args): Result` - Добавляем сообщение к массиву сообщений
  - `getMessages(bool $implode = false, string $glue = ',', array $brackets = ['[', ']'])` - возвращает (отформатированный) 
  массив сообщений,
- Данные (data как репозиторий DOT):
  - `setData($keys, $value = null): Result` - Устанавливает данные в датасете, допустим путь через точку
  - `addData($keys, $value = null):Result` - Добавляет данные в датасет, допустим путь через точку
  - `getData($key = null, $default = null)` - Возвращает данные из датасета, допустим путь через точку
- Сериализация в JSON
  - `serialize()` - Превращает результат в JSON-строку
  - `asJSON()` - алиас
- Десериализация
  - TODO (не проверена)

## Entity/Value

Хелпер для преобразования типа значения. Реальный смысл утерян.

```php
$t = new \Arris\Entity\Value('123');
$t->toInt();
```

## Entity/Context

Я попросил нейросеть придумать, зачем может использоваться такой класс:

Класс Context, который вы предоставили, может использоваться в PHP для управления параметрами, которые могут быть использованы, 
например, при подготовке SQL-запросов или при работе с другими контекстами, 
где требуется связывание значений с уникальными именами параметров.

Давайте рассмотрим, как можно использовать этот класс, с примерами:

```php
// Создание экземпляра класса Context
$context = new Context();

// Добавление параметров
$param1 = $context->parameter('value1'); // Возвращает ":p0"
$param2 = $context->parameter('value2'); // Возвращает ":p1"
$param3 = $context->parameter('value3'); // Возвращает ":p2"

// Выводим параметры, которые были привязаны
print_r($context->context());
```

После выполнения кода выше `print_r($context->context())` выведет следующее:
```
Array
(
    [p0] => value1
    [p1] => value2
    [p2] => value3
)
```

Применение в SQL-запросах

Вы можете использовать класс Context для безопасного связывания параметров в SQL-запросах, например, с использованием PDO:

```php
// Вы можете использовать этот класс для подготовки SQL-запроса
$sql = "INSERT INTO users (username, email) VALUES ({$param1}, {$param2})";

// Теперь вам нужно будет подготовить и выполнить запрос
$stmt = $pdo->prepare($sql);

// Связываем значения с параметрами перед выполнением
$stmt->bindParam(':p0', $context->context()['p0']);
$stmt->bindParam(':p1', $context->context()['p1']);

// Выполняем запрос
$stmt->execute();
```

Вполне вероятно, что это можно делать и в цикле. 