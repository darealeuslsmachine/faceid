<?php 

$domain = 'http://92.39.75.26:61180/';
    
//получение массива с данными о пользователе
$params = array(
    "pass" => "12345678",
    "id" => "-1"
);

$json_params = json_encode($params, JSON_UNESCAPED_UNICODE);

$apiMethod = 'person/find';
$url = $domain.$apiMethod;

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); //кастомный запрос
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_params); // это post данные
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //Отключаем проверку сертификата https
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //Отключаем проверку хоста
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // вернуть ответ в переменную

$result = curl_exec($ch);

curl_close($ch);
$assocresult_personsinfo = json_decode($result, true);



$apiMethod = 'face/find';
$url = $domain.$apiMethod;

$person_fullinfo = array();

foreach ($assocresult_personsinfo['data'] as $key){
    
    $params = array(
        "personId" => $key['id'],
        "pass" => "12345678"
        
    );
    
    $json_params = json_encode($params, JSON_UNESCAPED_UNICODE);
    
    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_POST, true); //переключаем запрос в POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_params); // это post данные
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //Отключаем проверку сертификата https
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //Отключаем проверку хоста
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // вернуть ответ в переменную
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    $assocresult_base64photo = json_decode($result, true);
    
    array_push($person_fullinfo, array(
        "id" => $key['id'], 
        "name" => $key['name'],
        "path" => $assocresult_base64photo['data']['0']['path']
        )
    );
}
print_r($person_fullinfo);