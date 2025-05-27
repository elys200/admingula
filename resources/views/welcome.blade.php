<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>


    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Nunito', sans-serif;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .right {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: linear-gradient(to bottom, #f00, #881d06);
            background-image: url('/images/login.png');
            background-repeat: repeat;
            background-size: contain;
            z-index: 0;
        }

        .left {
            position: relative;
            z-index: 1;
            background: white;
            width: 40%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-left: 100px;
            border-top-right-radius: 45% 50%;
            border-bottom-right-radius: 45% 50%;
        }

        .title {
            font-size: 48px;
            font-weight: 700;
        }

        .input {
            margin-top: 30px;
            position: relative;
            width: 300px;
        }

        .input i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #555;
            font-size: 18px;
        }

        .input input {
            width: 100%;
            padding: 12px 12px 12px 45px;
            border-radius: 15px;
            border: none;
            background: #d9d9d9;
            font-size: 16px;
            outline: none;
        }

        .btn {
            margin-top: 30px;
            background: #e03710;
            color: white;
            padding: 12px 0;
            border: none;
            border-radius: 15px;
            width: 150px;
            font-weight: bold;
            cursor: pointer;
        }

    </style>
</head>

<body>
    <div class="right"></div>
    <div class="left">
        <div class="title">Login</div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="input">
                <i class="bi bi-person-fill"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input">
                <i class="bi bi-lock-fill"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button class="btn" type="submit">Masuk</button>
        </form>

    </div>
</body>

</html>

