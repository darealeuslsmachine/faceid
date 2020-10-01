<?php
session_start();

date_default_timezone_set('Etc/GMT-3');

if (isset($_POST['btnLogout'])) {
    session_destroy();
    header('Location: index.php');
}

if ($_SESSION['user'] == null) {
    header('Location: login.php');
}

if (isset($_GET['btnGet'])) {
    switch ($_GET['sortparam1']){
        case "allusers":
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
            if ($_GET['sortparam2'] == 'timedesceding'){
                array_reverse($person_fullinfo);
            }
            
            $count_allusers = '<button type="button" class="btn btn-info col-md-4 mb-4">
                Всего <span class="badge badge-light">'.count($person_fullinfo).'</span>
                <span class="sr-only"></span>
            </button>';
            
            break;
        case "attempts":
            $domain = 'http://92.39.75.26:61180/';
                
            //получение массива с данными о пользователе
            $params = array(
                "pass" => "12345678",
                "personId" => "-1",
                "index" => "0",
                "length" => "-1",
                "startTime" => "0",
                "endTime" => "0"
            );
            
            $json_params = json_encode($params, JSON_UNESCAPED_UNICODE);
            
            $apiMethod = 'findRecords';
            $url = $domain.$apiMethod;
            
            $ch = curl_init($url);
            
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); //кастомный запрос
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_params); // это post данные
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //Отключаем проверку сертификата https
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //Отключаем проверку хоста
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // вернуть ответ в переменную
            
            $result = curl_exec($ch);
            
            curl_close($ch);
            $assocresult_FindRecordsInfo = json_decode($result, true);
            
            $assocresult_FindRecordsInfoSort = array_values($assocresult_FindRecordsInfo['data']['records']);
            
            //print_r($assocresult_FindRecordsInfoSort); die();
            
            if ($_GET['sortparam2'] == 'timedesceding'){
                $assocresult_FindRecordsInfoSort = array_reverse($assocresult_FindRecordsInfoSort);
            }
            
            $count_stranger = 0;
            foreach ($assocresult_FindRecordsInfoSort as $key) {
                if ($key[name] == 'Stranger') {
                    $count_stranger++;
                }
            }
            
            $count_success = '<button type="button" class="btn btn-success col-md-4 mb-4">
                Успешных <span class="badge badge-light">'.(count($assocresult_FindRecordsInfoSort)-($count_stranger)).'</span>
                <span class="sr-only"></span>
            </button>';
            $count_unsuccess = '<button type="button" class="btn btn-danger col-md-4 mb-4">
                Неуспешных <span class="badge badge-light">'.$count_stranger.'</span>
                <span class="sr-only"></span>
            </button>';
            $count_allattempts = '<button type="button" class="btn btn-info col-md-3 mb-4 ml-4">
                Всего <span class="badge badge-light">'.count($assocresult_FindRecordsInfoSort).'</span>
                <span class="sr-only"></span>
            </button>';
            
            break;
        case "successfulAttempts":
            $domain = 'http://92.39.75.26:61180/';
                
            //получение массива с данными о пользователе
            $params = array(
                "pass" => "12345678",
                "personId" => "-1",
                "index" => "0",
                "length" => "-1",
                "startTime" => "0",
                "endTime" => "0"
            );
            
            $json_params = json_encode($params, JSON_UNESCAPED_UNICODE);
            
            $apiMethod = 'findRecords';
            $url = $domain.$apiMethod;
            
            $ch = curl_init($url);
            
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); //кастомный запрос
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_params); // это post данные
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //Отключаем проверку сертификата https
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //Отключаем проверку хоста
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // вернуть ответ в переменную
            
            $result = curl_exec($ch);
            
            curl_close($ch);
            $assocresult_FindRecordsInfo = json_decode($result, true);
            
            $assocresult_FindRecordsInfo = array_values($assocresult_FindRecordsInfo['data']['records']);
            
            $assocresult_FindRecordsInfoSort = array();
            foreach ($assocresult_FindRecordsInfo as $key) {
                if ($key['name'] != 'Stranger') {
                    array_push($assocresult_FindRecordsInfoSort, $key);
                }
            } 
            
            if ($_GET['sortparam2'] == 'timedesceding'){
                $assocresult_FindRecordsInfoSort = array_reverse($assocresult_FindRecordsInfoSort);
            }
            
            $count_allattempts = '<button type="button" class="btn btn-success col-md-4 mb-4">
                Всего <span class="badge badge-light">'.count($assocresult_FindRecordsInfoSort).'</span>
                <span class="sr-only"></span>
            </button>';
            
            break;
        case "ansuccessfulAttempts":
            $domain = 'http://92.39.75.26:61180/';
                
            //получение массива с данными о пользователе
            $params = array(
                "pass" => "12345678",
                "personId" => "-1",
                "index" => "0",
                "length" => "-1",
                "startTime" => "0",
                "endTime" => "0"
            );
            
            $json_params = json_encode($params, JSON_UNESCAPED_UNICODE);
            
            $apiMethod = 'findRecords';
            $url = $domain.$apiMethod;
            
            $ch = curl_init($url);
            
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); //кастомный запрос
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_params); // это post данные
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //Отключаем проверку сертификата https
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //Отключаем проверку хоста
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // вернуть ответ в переменную
            
            $result = curl_exec($ch);
            
            curl_close($ch);
            $assocresult_FindRecordsInfo = json_decode($result, true);
            
            $assocresult_FindRecordsInfo = array_values($assocresult_FindRecordsInfo['data']['records']);
            
            $assocresult_FindRecordsInfoSort = array();
            foreach ($assocresult_FindRecordsInfo as $key) {
                if ($key['name'] == 'Stranger') {
                    array_push($assocresult_FindRecordsInfoSort, $key);
                }
            } 
            
            if ($_GET['sortparam2'] == 'timedesceding'){
                $assocresult_FindRecordsInfoSort = array_reverse($assocresult_FindRecordsInfoSort);
            }
            
            $count_allattempts = '<button type="button" class="btn btn-danger col-md-4 mb-4">
                Всего <span class="badge badge-light">'.count($assocresult_FindRecordsInfoSort).'</span>
                <span class="sr-only"></span>
            </button>';
            
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>
        Администирование
    </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style type="text/css">
        .active-cyan-4 input[type=text]:focus:not([readonly]) {
            border: 1px solid #4dd0e1;
            box-shadow: 0 0 0 1px #4dd0e1;
        }
        .scrollbar-cyan::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: #F5F5F5;
            border-radius: 10px; 
        }
        
        .scrollbar-cyan::-webkit-scrollbar {
            width: 12px;
            background-color: #F5F5F5; 
            
        }
        .scrollbar-cyan::-webkit-scrollbar-thumb {
        border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: #00bcd4; 
            
        }
        
        .scrollbar-cyan {
            scrollbar-color: #00bcd4 #F5F5F5;
        }
        .bordered-cyan::-webkit-scrollbar-track {
            -webkit-box-shadow: none;
            border: 1px solid #00bcd4;
        }
        .bordered-cyan::-webkit-scrollbar-thumb {
            -webkit-box-shadow: none;
        }
        .square::-webkit-scrollbar-track {
            border-radius: 0 !important; 
        }
        .square::-webkit-scrollbar-thumb {
            border-radius: 0 !important; }
        
        .thin::-webkit-scrollbar {
            width: 6px; 
            
        }
        .example-1 {
            position: relative;
            overflow-y: scroll;
            height: 500px; 
        }
        
    </style>
    
</head>
<body>
<header></header>

<!--------------------------Collapsed content---------------------------------->

<div class="pos-f-t">
  <div class="collapse" id="navbarToggleExternalContent">
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-dark bg-dark">
        <a class="navbar-brand" href="admin.php">Домой</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                 <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                         <a class="nav-link" href="index.php">Добавить пользователя <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Disabled</a>
                     </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Disabled
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Action</a>
                         <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">Disabled</a>
                    </li>
                </ul>
            </div>
    </nav>
  </div>
  <nav class="navbar navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="active-cyan-4 mr-9 col-md-10">
        <input class="form-control" type="text" placeholder="Search" aria-label="Search">
    </div>
    <form method="POST">
        <input type="submit" class="btn btn-outline-light" value="Выйти" name="btnLogout">
    </form>
    <!--<form action="" method="POST">
        <div class="btn-group" role="group" aria-label="Basic example">
            <input type="submit" class="btn btn-light" value="Все пользователи" name="get_userInfo">
            <div class="btn-group">
            
        </div>
    </form>-->
  </nav>
</div>

<!--------------------------Admin panel---------------------------------------->

<div class="container">
    <form method="GET">
        <div class="my-4">
            <select name="sortparam1" class="custom-select col-md-3">
                <option name="users_infoSort" value="allusers">Все пользователи</option>
                <option name="users_loginAttemptsSort" value="attempts">Попытки входа</option>
                <option name="users_loginSuccessSort" value="successfulAttempts">Успешные попытки входа</option>
                <option name="users_loginSuccessFailSort" value="ansuccessfulAttempts">Неуспешные попытки входа</option>
            </select>
            <select name="sortparam2" class="custom-select col-md-3">
                <option name="time_ascendingSort" value="timeascending">Время по возрастанию</option>
                <option name="time_descedingSort" value="timedesceding">Время по убыванию</option>
            </select>
                <input type="submit" name="btnGet" value="Отправить" class="btn btn-outline-secondary"/>
        </div>
    </form>
    
    <?php echo $count_success?>
    <?php echo $count_unsuccess?>
    <?php echo $count_allattempts?>
    <?php echo $count_allusers?>
    
    <div class="row">
        <div class="col-sm col-md-12">
            <div class="card example-1 square scrollbar-cyan bordered-cyan">
      <div class="card-body">
            <ul class="list-group list-group-flush scrollbar scrollbar-black bordered-black square">
                <?php foreach ($person_fullinfo as $key){ ?>
                <li class="list-group-item list-group-item-primary rounded"><?php echo $key['name'];?></li>
                <?php }; ?>
            <!--</ul>
            <ul class="list-group list-group-flush">-->
                <?php foreach ($assocresult_FindRecordsInfoSort as $key){ 
                if ($key['name'] == 'Stranger') { 
                ?>
                <li class="list-group-item list-group-item-danger  rounded"><?php echo $key['name'].' '.date("F j, Y, g:i a", substr($key['time'], 0, -3));?></li>
                <?php 
                } else { ?>
                    <li class="list-group-item list-group-item-success  rounded"><?php echo $key['name'].' '.date("F j, Y, g:i a", substr($key['time'], 0, -3));?></li> 
                <?php 
                    }
                }
                ?>
            </ul>
        </div></div></div>
    </div>
</div>


<footer class="page-footer font-small pt-4">
  <div class="footer-copyright text-center py-3">© 2020 Copyright:
    <a href="https://zhcom.ru/" target="_blank"> zhcom.ru</a>
  </div>
</footer>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>