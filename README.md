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


## Entity/Result advanced

```php
$r = new \Arris\Entity\Result();
$r->setData('thumbnails', []);

for($i=1; $i<3; $i++) {
    $r->addData('thumbnails', [[
        'id'    =>  $i,
        'file'  =>  mt_rand(10000, 99999) . '.jpg'
    ]]);
}

$r->setData([
    'fn_origin'     =>  'xxxx',
    'status'        =>  'pending',
]);

$r->setData('xxx', 'yyy');

var_dump($r->getData());
```

Выдает результат:
```
array(5) {
  ["thumbnails"]=>
  array(2) {
    [0]=>
    array(2) {
      ["id"]=>      int(1)
      ["file"]=>    string(9) "46512.jpg"
    }
    [1]=>
    array(2) {
      ["id"]=>      int(2)
      ["file"]=>    string(9) "38129.jpg"
    }
  }
  ["fn_origin"]=>   string(4) "xxxx"
  ["status"]=>      string(7) "pending"
  ["foo"]=>           string(3) "xxx"
  ["bar"]=> array(1) {
    [0]=>
    string(3) "yyy"
  }
}
```

То есть, setData устанавливает значение ключа, а addData - добавляет значение к массиву, имеющему имя ключа


## Entity/Value

Хелпер для преобразования типа значения. Реальный смысл утерян.

```php
$t = new \Arris\Entity\Value('123');
$t->toInt();
```

