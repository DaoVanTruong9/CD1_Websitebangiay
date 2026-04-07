<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Register</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        *{
            font-family: 'Poppins', sans-serif;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background: url('/images/anh_login.png') no-repeat center center/cover;
            position:relative;
        }

        /* overlay */
        body::before{
            content:'';
            position:absolute;
            width:100%;
            height:100%;
            background: rgba(0,0,0,0.5);
        }

        .register-box{
            position:relative;
            width:400px;
            padding:40px;
            border-radius:20px;
            backdrop-filter: blur(15px);
            background: rgba(255,255,255,0.1);
            border:1px solid rgba(255,255,255,0.2);
            box-shadow:0 8px 32px rgba(0,0,0,0.3);
            color:white;
        }

        .register-box h2{
            text-align:center;
            margin-bottom:25px;
            font-weight:600;
        }

        .form-control{
            background: rgba(255,255,255,0.2);
            border:none;
            color:white;
            border-radius:30px;
            padding:12px 20px;
        }

        .form-control::placeholder{
            color:#ddd;
        }

        .form-control:focus{
            background: rgba(255,255,255,0.3);
            box-shadow:none;
            color:white;
        }

        .btn-register{
            width:100%;
            border-radius:30px;
            padding:10px;
            background:#00c851;
            color:white;
            font-weight:600;
            transition:0.3s;
        }

        .btn-register:hover{
            background:#00a844;
        }

        .login-link{
            text-align:center;
            margin-top:20px;
        }

        .login-link a{
            color:#fff;
            font-weight:600;
            text-decoration:none;
        }
    </style>
</head>

<body>

<div class="register-box">
    <h2>Create Account</h2>

    <form method="POST" action="/register">
        @csrf

        <input name="name" class="form-control mb-3" placeholder="Full Name">
        <input name="email" class="form-control mb-3" placeholder="Email">
        <input name="password" type="password" class="form-control mb-3" placeholder="Password">

        <button class="btn btn-register mt-2">Register</button>

        <div class="login-link">
            Đã có tài khoản? <a href="/login">Login</a>
        </div>
    </form>
</div>

</body>
</html>