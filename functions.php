<?php

include('classSimpleImage.php');

function can_upload($file, $firstname, $lastname, $patronymic = null){

    $strPass = array("pass" => "12345678");
    $arrayForCheckConnectionJson = json_encode($strPass, JSON_UNESCAPED_UNICODE);

    $url = 'http://92.39.75.26:61180/device/information'; //or http://192.168.5.39:8090/ for internal network
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true); //переключаем запрос в POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayForCheckConnectionJson); // это post данные
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //Отключаем проверку сертификата https
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //Отключаем проверку хоста
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // вернуть ответ в переменную

    $result = curl_exec($ch);
    curl_close($ch);

    $assocresult = json_decode($result, true);

    if ($assocresult == null) {
        return 'Нет ответа от сервера!';
    }

    if (ctype_space($firstname) or ($firstname == null)) {
        return 'Имя не заполнено.';
    }

    if (ctype_space($lastname) or ($lastname == null)) {
        return 'Фамилия не заполнена.';
    }

    // если размер файла 0, значит его не пропустили настройки сервера из-за того, что он слишком большой
    if($file['size'] == 0){
        return 'Файл слишком большой.';
    }

    // разбиваем имя файла по точке и получаем массив
    $getMime = explode('.', $file['name']);
    // нас интересует последний элемент массива - расширение
    $mime = strtolower(end($getMime));
    // объявим массив допустимых расширений
    $types = array('jpg', 'png', 'gif', 'bmp', 'jpeg');

    // если расширение не входит в список допустимых - return
    if(!in_array($mime, $types)){
        return 'Недопустимый тип файла.';
    }

    return 228;

}

function make_upload($file, $firstname, $lastname, $patronymic = ''){

    // формируем уникальное имя картинки
    $id = uniqid();
    $name = $id . '_' . $lastname . '_' . $firstname;
    copy($file['tmp_name'], 'temp_img/' . $name);

    $pathimg = 'temp_img/'. $name;

    if ($file['size'] > 1500000) {
        $image = new SimpleImage();
        $image->load($pathimg);
        $image->scale(50);
        $image->save($pathimg);

    }

    
    $img = imagecreatefromjpeg($pathimg);
    
    if (imagesx($img) > imagesy($img)){
        $degrees = 270;                        
        $imgRotated = imagerotate($img, $degrees, 0);
        imagejpeg($imgRotated, $pathimg, 90);
    }

    $dataimg = file_get_contents($pathimg);
    $imgbase64 = base64_encode($dataimg);
    print_r($imgbase64); die();

    //создание персоны
    $arrayForCreatePerson = array(
        "pass" => "12345678",
        "person" => array(
            "id" => "",
            "name" => $firstname.' '.$lastname.' '.$patronymic,
            "idcardNum" => "1234577",
            "iDNumber" => "",
            "facePermission" => "2",
            "faceAndCardPermission" => "2",
            "iDPermission" => "2",
            "tag" => "",
            "phone" => ""
        )
    );

    $arrayForCreatePersonJson = json_encode($arrayForCreatePerson, JSON_UNESCAPED_UNICODE);

    $domain = 'http://92.39.75.26:61180/';
    $apiMethod = 'person/create';
    $url = $domain.$apiMethod;

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true); //переключаем запрос в POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayForCreatePersonJson); // это post данные
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //Отключаем проверку сертификата https
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //Отключаем проверку хоста
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // вернуть ответ в переменную

    $result = curl_exec($ch);

    curl_close($ch);
    $assocresult = json_decode($result, true);

    //добавление фото
    $arrayForAddFace = array(
        "pass" => "12345678",
        "person" => $assocresult['data']['name'],
        "personId" =>  $assocresult['data']['id'],
        "faceId" => uniqid(),
        "imgBase64" => $imgbase64,
        "isEasyWay" => ""
    );

    $arrayForAddFaceJson = json_encode($arrayForAddFace, JSON_UNESCAPED_UNICODE);

    $apiMethod = 'face/create';
    $url = $domain.$apiMethod;

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true); //переключаем запрос в POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayForAddFaceJson); // это post данные
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //Отключаем проверку сертификата https
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //Отключаем проверку хоста
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // вернуть ответ в переменную

    $result = curl_exec($ch);
    curl_close($ch);

    $assocresult = json_decode($result, true);

    if ($assocresult['code'] == 'LAN_EXP-8006') {
        echo 'Лицо не найдено';
        die();
        
    }

    if ($assocresult['code'] == 'LAN_EXP-8007') {
        echo 'На фото обнаружено несколько лиц';
        die();
    }

    if ($assocresult['code'] == 'LAN_EXP-8010') {
        echo 'Лицо слишком далеко от камеры';
        die();
    }

    if ($assocresult['code'] == 'LAN_EXP-8013') {
        echo 'Лицо слишком близко к камере';
        die();
    }

    if ($assocresult['code'] == 'LAN_EXP-8014') {
        echo 'Большой угол наклона лица';
        die();
    }

    if ($assocresult['code'] == 'LAN_EXP-8015') {
        echo 'Добавьте освещение';
        die();
    }

    if ($assocresult['code'] == 'LAN_EXP-8016') {
        echo 'Лицо размыто';
        die();
    }

    if ($assocresult['code'] == 'LAN_EXP-8017') {
        echo 'Лицо размыто';
        die();
    }
}


function ruToEngTranslit($string) {
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    return strtr($string, $converter);
}