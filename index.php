<?php
if (!empty($_POST)) {
    $email = trim($_POST['email']);
    // Сообщение о результатах проверки
    $message = [
        'status' => 'error',
        'message' => '',
    ];
    // Проверка содержит ли email @
    if (strpos($email, '@')) {
        // Проверяем совпадают ли пароли
        if ($_POST['password'] === $_POST['password-reset']) {
            // Массив с существующими пользователями
            $users = [
                [
                    'email' => 'egor@mail.com',
                    'id' => 1,
                    'name' => 'Егор',
                ],
                [
                    'email' => 'ivan@mail.com',
                    'id' => 2,
                    'name' => 'Иван',
                ],
                [
                    'email' => 'leha@mail.com',
                    'id' => 3,
                    'name' => 'Алексей',
                ],
            ];
            // Поиск введённого email в массиве $users
            foreach ($users as $user) {
                if ($user['email'] === $email) {
                    $message['message'] =
                        'Пользователь с таким email уже существует';
                    break;
                }
            }
            if (empty($message['message'])) {
                $message = [
                    'status' => 'success',
                    'message' => 'Регистрация прошла успешно',
                ];
            }
        } else {
            $message['message'] = 'Пароли не совпадают';
        }
    } else {
        $message['message'] = 'Некорректный email';
    }

    //Логируем резудьтат в файл log.log
    define("PATH_TO_LOG", "log.log");
    $text = "---NEW LOG---\n".date("Y:m:d h:i:s")."\n".json_encode($message)."\n";
    file_put_contents(PATH_TO_LOG, $text, FILE_APPEND);
    echo json_encode($message);
    exit();
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Document</title>
    <style>
        .form{
            /* border: 1px solid black; */
            margin: 50px auto 0;
            max-width: 500px;
            padding: 20px;
        }
        .form__title{
            text-align: center;
        }
        .form__submit{
            margin: 20px auto 0;
            display: block;
        }
    </style>
</head>

<body>
    <form action="/" class="form">
        <h2 class="form__title">Регистрация</h2>
        <div class="form-group">
            <label for="name">Имя</label>
            <input type="text" class="form-control" id="name"  placeholder="Имя" name="name">
        </div>
        <div class="form-group">
            <label for="surname">Фамилия</label>
            <input type="text" class="form-control" id="surname"  placeholder="Фамилия" name="surname">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email"  placeholder="Email"  name="email">
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" class="form-control" id="password"  placeholder="Пароль" name="password">
        </div>
        <div class="form-group">
            <label for="password-repeat">Повтор пароля</label>
            <input type="password" class="form-control" id="password-repeat"  placeholder="Повтор пароля" name="password-reset">
        </div>
        <button type="submit" class="btn btn-primary form__submit" name="submit">Зарегистрироваться</button>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        $(function(){
            $(".form").on("submit", function(e){
                e.preventDefault();
                let form = $(this);
                let formData = $(this).serialize();
                $.ajax({
	                url: '/index.php',         
	                method: 'POST',             
	                dataType: 'html',      
	                data:  formData,     
	                success: function(data){   
                        console.log(data);
	                	let json = JSON.parse (data);
                        if(json['status'] == 'error'){
                            alert(json['message']);
                        } else{
                            form.hide();
                            $('body').append(`
                            <div class="alert alert-success" role="alert">
                                ` + json['message'] + `
                            </div>`);
                        }
	                }
                });
                console.log($(this).serialize());
            })
        })
    </script>
</body>

</html>
