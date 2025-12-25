<?php
  session_start();
  header('Content-Type: text/html; charset=utf-8');
  $thongbao="";
  require_once __DIR__."/../models/taikhoanmodel.php";
  if (isset($_POST["taikhoan"],$_POST["matkhau"])){
      $u = trim($_POST["taikhoan"]);
      $p = trim($_POST["matkhau"]);
      $chucvu = checkTaiKhoan($u,$p);
      if($role !== 0){
        if($role !== 0){
          $_SESSION["taikhoan"]=$u;
          $_SESSION["chucvu"]=$chucvu;
          header("Location: trangchuview.php");
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
    <link rel="stylesheet" href="/php/web_quan_li_sieu_thi/assets/dangnhap.css" >
    <title>Đăng nhập - Hệ Thống Quản Lý Siêu Thị</title>
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
                        <input type="text" name="taikhoan" placeholder="Nhập tài khoản" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>Mật khẩu</label>
                    <div class="input-icon">
                        <input type="password" name="matkhau" placeholder="Nhập mật khẩu" required>
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