<?php
  session_start();
  header('Content-Type: text/html; charset=utf-8');
  $thongbao="";
  require_once __DIR__."/../models/taikhoanmodel.php";
  if (isset($_POST["username"],$_POST["password"])){
      $u = trim($_POST["username"]);
      $p = trim($_POST["password"]);
      $role = checkTaiKhoan($u,$p);
      if($role !== 0){
        if($role !== 0){
          $_SESSION["username"]=$u;
          $_SESSION["role"]=$role;
          header("Location: trangchu.php");
          exit();
        }
      }
      else {  
        $thongbao="Sai tài khoản mật khẩu";
      }
  }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/php/web_quan_li_sieu_thi/assets/trangchu.css" >
    <title>Đăng nhập - Hệ Thống Quản Lý Siêu Thị</title>
    <style>
    /* Form nằm giữa màn hình */
    .main {
    display: flex;
    justify-content: center;
    align-items: center;
    height: calc(100vh - 80px); /* trừ chiều cao header */
    }

    .login-box {
      background: white;
      width: 350px;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .login-box h2 {
      text-align: center;
      margin-bottom: 25px;
    }

    .input-group {
      margin-bottom: 15px;
    }

    .input-group label {
      font-size: 14px;
      display: block;
      margin-bottom: 6px;
    }

    .input-icon {
      display: flex;
      align-items: center;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 10px;
      background: #fff;
    }


    .input-icon input {
      border: none;
      outline: none;
      width: 100%;
      font-size: 15px;
    }

    .login-btn {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      background: #007bff;
      color: white;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
    }

    .login-btn:hover {
      background: #0056b3;
    }
    </style>
</head>

<body>
  <header class="header">
        <h1 class="title">QUẢN LÍ SIÊU THỊ</h1>
  </header>
    <main class="main">
        <div class="login-box">
            <h2>Đăng nhập</h2>
            <form action="" method="POST">
                <div class="input-group">
                    <label>Tài khoản</label>
                    <div class="input-icon">
                        <input type="text" name="username" placeholder="Nhập tài khoản" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>Mật khẩu</label>
                    <div class="input-icon">
                        <input type="password" name="password" placeholder="Nhập mật khẩu" required>
                    </div>
                </div>
                <button type="submit" class="login-btn">Đăng nhập</button>
                <div>
                    <p style='color:red; text-align:center;'>
                      <?= $thongbao ?>
                    </p>
                </div>
            </form>
        </div>
    </main>
</body> 
</html>