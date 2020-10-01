<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>
        Добавление сотрудника
    </title>
    <link rel="stylesheet" href="web/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<header></header>

<!--------------------------Collapsed content---------------------------------->

<div class="pos-f-t">
  <div class="collapse" id="navbarToggleExternalContent">
    <div class="bg-dark p-4">
        <a class="btn btn-secondary" href="login.php" role="button">Администрирование</a>
        <!--<h4 class="text-white">Администрирование</h4>-->
         <!--<span class="text-muted">Выгрузки и корректировки.</span>-->
    </div>
  </div>
  <nav class="navbar navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </nav>
</div>

<!--------------------------Add user form-------------------------------------->

<div class="container">
    <form action="adduser.php" class="col-sm-6 col-md-9 col-md-offset-4" enctype="multipart/form-data" method="post">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Имя:</label>
            <input type="text" class="form-control" name="firstname" required placeholder="Иван" required autofocus>
            <small class="form-text text-muted">С заглавной буквы кириллицой.</small>
        </div>
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Фамилия:</label>
            <input type="text" class="form-control" name="lastname" required placeholder="Иванов">
        </div>
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Отчество:</label>
            <input type="text" class="form-control" name="patronymic" placeholder="Иванович">
            <small class="form-text text-muted">Необязательно.</small>
        </div>
        <label for="recipient-name" class="col-form-label">Фото:</label>
        <div class="custom-file mb-3">
            <input type="file" name="picture" class="custom-file-input" id="customFile" required>
            <label class="custom-file-label" for="customFile">Выберите фото</label>
            <small class="form-text text-muted">Старайтесь сделать фото без размытий, не слишком близко к камере.</small>
        </div>
        </br>
        <button class="btn btn-outline-secondary" type="submit" style="margin-top: 10px; width: 128px">Отправить</button>
    </form>
</div>


<script>
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>