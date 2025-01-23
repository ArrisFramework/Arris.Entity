ВОЗМОЖНО, нужно исправить метод addData так:

```php
public function addData($keys, $value = null):Result
{
    $this->data->merge($keys, [ $value ]);

    return $this;
}
```