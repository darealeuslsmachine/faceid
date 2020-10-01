<?php

include_once('functions.php');

$firstname = $_POST['firstname'];
$firstname = trim($firstname);
$firstnameTranslit = ruToEngTranslit($firstname);

$lastname = $_POST['lastname'];
$latname = trim($lastname);
$lastnameTranslit = ruToEngTranslit($lastname);

if (isset($_POST['patronymic'])) {
    $patronymic = $_POST['patronymic'];
     
    if (!ctype_space($patronymic)) {
        $patronymic = trim($patronymic);
        $patronymicTranslit = ruToEngTranslit($patronymic);
    }
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $check = can_upload($_FILES['picture'], $firstname, $lastname, $patronymic);

    if ($check == 228) {
        make_upload($_FILES['picture'], $lastnameTranslit, $firstnameTranslit, $patronymicTranslit);


        echo '<div class="container">
            <div class="col-sm-6 col-md-9 col-md-offset-4">
                <img src="img/user-ok.png" class="center-img">
                <div class="alert alert-success" role="alert">Успешно!    <a href="index.php" class="alert-link">Добавьте</a> еще одного сотрудника.</div>
            </div>
          </div>';
    } else
        echo '<div class="container">
            <div class="col-sm-6 col-md-9 col-md-offset-4">
                <img src="img/user-error.png" class="center-img">
                <div class="alert alert-danger" role="alert">Что-то пошло не так! '."$check".'. <a href="index.php" class="alert-link">Попробуйте снова</a>.</div>
            </div>
          </div>';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>
        Добавление сотрудника
    </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <style>
        
        .center-img {
            margin: auto;
            display: block;
            margin-top: 200px;
            width:; 150px;
            height: 150px;
        }
        
        .preloader {
            /*фиксированное позиционирование*/
            position: fixed;
            /* координаты положения */
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            /* фоновый цвет элемента */
            background: #e0e0e0;
            /* размещаем блок над всеми элементами на странице (это значение должно быть больше, чем у любого другого позиционированного элемента на странице) */
            z-index: 1001;
        }

        .preloader__row {
            position: relative;
            top: 50%;
            left: 50%;
            width: 70px;
            height: 70px;
            margin-top: -35px;
            margin-left: -35px;
            text-align: center;
            animation: preloader-rotate 2s infinite linear;
        }

        .preloader__item {
            position: absolute;
            display: inline-block;
            top: 0;
            background-color: #337ab7;
            border-radius: 100%;
            width: 35px;
            height: 35px;
            animation: preloader-bounce 2s infinite ease-in-out;
        }

        .preloader__item:last-child {
            top: auto;
            bottom: 0;
            animation-delay: -1s;
        }

        @keyframes preloader-rotate {
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes preloader-bounce {

            0%,
            100% {
                transform: scale(0);
            }

            50% {
                transform: scale(1);
            }
        }

        .loaded_hiding .preloader {
            transition: 0.3s opacity;
            opacity: 0;
        }

        .loaded .preloader {
            display: none;
        }
    </style>


</head>
<body>
<div class="preloader">
    <div class="preloader__row">
        <div class="preloader__item"></div>
        <div class="preloader__item"></div>
    </div>
</div>
<header></header>

<script>
    window.onload = function () {
        document.body.classList.add('loaded');
    }
</script>
<script>
    window.onload = function () {
        document.body.classList.add('loaded_hiding');
        window.setTimeout(function () {
                document.body.classList.add('loaded');
                document.body.classList.remove('loaded_hiding');
            },
            500);
    }
</script>

</body>
</html>