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