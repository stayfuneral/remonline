<?php require __DIR__.'/src/remonline.php';

function Debug($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

$apiKey = 'xxx'; // API-ключ

$Remonline = new Remonline($apiKey);

$assignedId = $Remonline->call('employees/')->data[0]->id; // ID сотрудника
$branchId = $Remonline->call('branches/')->data[0]->id; // ID мастерской
$orderTypeId = $Remonline->call('order/types/')->data[0]->id; // ID типа заказа

$clients = $Remonline->call('clients/')->data; // Список клиентов. Фильтр почему-то не сработал
foreach($clients as $client) {
    if($client->name === 'Adrian Smith') {
        $clientId = $client->id;
    }
}

$orderParams = [
    'type' => 'post',
    'query' => false,
    'params' => [
        'branch_id' => $branchId,
        'order_type' => $orderTypeId,
        'brand' => 'No name',
        'model' => 'untitled model',
        'assigned_at' => strtotime('25.03.2020 15:00:00'),
        'client_id' => $clientId
    ]
];

$createOrder = $Remonline->call('order/', $orderParams); // Создание заказа
Debug($createOrder);