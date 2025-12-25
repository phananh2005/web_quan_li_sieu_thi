<?php
    session_start();
    $u = $_SESSION["taikhoan"];
    $role = $_SESSION["chucvu"];
    require_once __DIR__."/../models/taikhoanmodel.php";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/php/web_quan_li_sieu_thi/assets/trangchu.css" >
    <link rel="stylesheet" href="/php/web_quan_li_sieu_thi/assets/taikhoan.css" >
    <title>Trang chủ - Hệ Thống Quản Lý Siêu Thị</title>
</head>
<body>
    <header class="header">
        <div class="div_tttk">
            <p>
                Xin chào <?= $u ?>. Chức vụ: <?= $role ?>
                <a href="dangxuatview.php" class = "a_DangXuat">Đăng xuất</a>
            </p>
        </div>
    </header>
    <nav class="navbar">
        <?php
            function chucNangKho(){
                echo '<a href="mathangview.php">Mặt hàng</a>';
                echo '<a href="danhmucview.php">Danh mục</a>';
                echo '<a href="nhacungcapview.php">Nhà cung cấp</a>';
                echo '<a href="donnhaphangview.php">Đơn nhập hàng</a>';
            }
            function chucNangThuNgan(){
                echo '<a href="hoadonview.php">Hóa đơn</a>';
                echo '<a href="spbanchayview.php">Sản phẩm bán chạy</a>';
                echo '<a href="thongkedoanhthuview.php">Thống kê doanh thu</a>';
            }

            if($role == "thu ngân") chucNangThuNgan();
            else if($role == "kho") chucNangKho();
            else if($role == "admin"){
                chucNangKho();
                chucNangThuNgan();                
                echo '<a href="nhanvienview.php">Nhân viên</a>';
                echo '<a href="bophanview.php">Bộ phận</a>';
                echo '<a href="taikhoanview.php"class = "active">Tài khoản</a>'; 
            }
        ?>
    </nav>
    <main class="main">
        <div class = "div_table">
            <table class = "table">
                <tr>
                    <th>Mã tài khoản</th>
                    <th>Tên tài khoản</th>
                    <th>Mật khẩu</th>
                    <th>Mã nhân viên</th>
                    <th>Chức vụ</th>
                    <th>Thao tác</th>
                </tr>
                <?php
                    getAllTaiKhoan();
                ?>
            </table>
        </div>
        <div class = "div_form">
            <form method="post" id = "form">
                <label>Tên tài khoản:</label>
                <input type="text" name="tentk">
                <label>Mật khẩu:</label>
                <input type="text" name="matkhau">
                <label>Mã nhân viên:</label>
                <select name="manv">
                    <option value="" disabled selected>Chọn mã nhân viên</option>
                </select>
                <label>Chức vụ:</label>
                <select name = 'chucvu'>
                    <option value="" disabled selected>Chọn chức vụ</option>
                    <option value="admin">Admin</option>
                    <option value="kho">Kho</option>
                    <option value="thu ngân">Thu ngân</option>    
                </select>
                <button type="submit" name = "timkiem">Tìm kiếm</button>
                <button type="submit" name = "them">Thêm</button>
                <button type="submit" name = "sua">Sửa</button>
                <button type="submit" name = "xoa">Xóa</button>
                <button type="submit" onclick="xoaForm()">Hủy</button>

                <script>
                    function xoaForm(){
                        document.getElementById("form").reset();
                    }
                </script>
            </form>
        </div>
    </main>
</body>
</html>