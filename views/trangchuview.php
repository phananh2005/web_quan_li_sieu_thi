<?php
    session_start();
    $u = $_SESSION["taikhoan"];
    $role = $_SESSION["chucvu"];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/trangchu.css" >
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
            echo '<a href="mathangview.php">Mặt hàng</a>';

            function chucNangKho(){
                echo '<a href="danhmucview.php">Danh mục</a>';
                echo '<a href="nhacungcapview.php">Nhà cung cấp</a>';
                echo '<a href="donnhaphangview.php">Đơn nhập hàng</a>';
            }
            function chucNangThuNgan($role){
                echo '<a href="hoadonview.php">Hóa đơn</a>';
                echo '<a href="spbanchayview.php">Sản phẩm bán chạy</a>';
                if ($role == "Bán hàng") echo '<a href="thongkedoanhthuview.php">Thống kê doanh thu</a>';
            }
            function chucNangAdmin($role){
                chucNangKho();
                chucNangThuNgan($role);
                echo '<a href="thongkethuchiview.php">Thống kê thu - chi</a>';
                echo '<a href="nhanvienview.php">Nhân viên</a>';
                echo '<a href="bophanview.php">Bộ phận</a>';
                echo '<a href="taikhoanview.php">Tài khoản</a>';
            }

            if($role == "Bán hàng") chucNangThuNgan($role);
            else if($role == "Kho") chucNangKho();
            else if($role == "Admin") chucNangAdmin($role);
        ?>
    </nav>
</body>
</html>