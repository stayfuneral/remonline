# Remonline PHP SDK

Небольшой PHP-SDK для работы с API сервиса [remonline.ru](remonline.ru).

[Документация](https://remonline.ru/docs/api)

### Инициализация объекта

Для создания объекта класса ремонлайн используется стандартная инициализация с передачей API-ключа:

```php
$Remonline = new Remonline('your_api_key');
```

### Методы для работы с API

Для работы с API используется метод `call()`, куда передаются параметры вызова.

Рассмотрим на примере создания заказа:

```php
$method = 'order/'; // метод вызова
$orderParams = [
    'type' => 'post', // тип запроса. Если не указан, по умолчанию ставится get
    'query' => false, // включение функции http_build_query(). Если не указан, по умолчанию ставится true
    'params' => [ // параметры запроса
        'branch_id' => $branchId,
        'order_type' => $orderTypeId,
        'brand' => 'No name',
        'model' => 'untitled model',
        'assigned_at' => strtotime('25.03.2020 15:00:00'),
        'client_id' => $clientId
    ]
];

$createOrder = $Remonline->call($method, $orderParams);
```

### Ограничения

Для всех запросов действует ограничение - не более 8 запросов в секунду.