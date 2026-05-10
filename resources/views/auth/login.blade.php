<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

        /* overlay làm mờ nền */
        body::before{
            content:'';
            position:absolute;
            width:100%;
            height:100%;
            background: rgba(0,0,0,0.5);
        }

        .login-box{
            position:relative;
            width:380px;
            padding:40px;
            border-radius:20px;
            backdrop-filter: blur(15px);
            background: rgba(255,255,255,0.1);
            border:1px solid rgba(255,255,255,0.2);
            box-shadow:0 8px 32px rgba(0,0,0,0.3);
            color:white;
        }

        .login-box h2{
            text-align:center;
            margin-bottom:30px;
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

        .btn-login{
            width:100%;
            border-radius:30px;
            padding:10px;
            background:white;
            color:#333;
            font-weight:600;
            transition:0.3s;
        }

        .btn-login:hover{
            background:#ddd;
        }

        .extra{
            display:flex;
            justify-content:space-between;
            font-size:14px;
            margin-top:10px;
        }

        .extra a{
            color:#fff;
            text-decoration:none;
        }

        .register{
            text-align:center;
            margin-top:20px;
        }

        .register a{
            color:#fff;
            font-weight:600;
        }
        .alert {
            border-radius: 12px;
            animation: fadeDown .3s ease;
        }
        .alert-danger{
            background: rgba(255, 0, 0, 0.15);
            color: white;
            backdrop-filter: blur(10px);
        }
        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

<div class="login-box">
    <h2>Login</h2>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert">

        <i class="fa fa-lock me-2 fs-5"></i>

        <div>
            <strong>Đăng nhập thất bại!</strong><br>
            {{ session('error') }}
        </div>

        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert">
        </button>

    </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <input name="email" class="form-control mb-3" placeholder="Email">
        <input name="password" type="password" class="form-control mb-3" placeholder="Password">

        <div class="extra">
            <label><input type="checkbox"> Remember</label>
            <a href="#">Forgot?</a>
        </div>

        <button class="btn btn-login mt-3">Login</button>

        <div class="register">
            Chưa có tài khoản? <a href="/register">Register</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
setTimeout(() => {
    let alertBox = document.querySelector('.alert');

    if(alertBox){
        let bsAlert = bootstrap.Alert.getOrCreateInstance(alertBox);
        bsAlert.close();
    }
}, 2500);
</script>

</body>
</html>