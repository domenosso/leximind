<?php
// Разрешаем запросы с вашего сайта (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Получаем данные из запроса сайта
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['prompt'])) {
    echo json_encode(["error" => "No prompt provided"]);
    exit;
}

// Формируем запрос к нейросети
$send = [
    "model" => "gpt-5.2-chat",
    "request" => [
        "messages" => [
            [
                "role" => "user",
                "content" => $data['prompt']
            ]
        ]
    ]
];

$headers = [
    "Authorization: Bearer openai", // Ключ теперь спрятан на сервере
    "Content-Type: application/json"
];

// Отправляем запрос через cURL (он игнорирует ограничения браузера по HTTPS)
$ch = curl_init('http://api.onlysq.ru/ai/v2');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($send));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
curl_close($ch);

// Проксируем ответ обратно на сайт
echo $response;
?>