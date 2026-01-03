<?php
    session_start();
    $u = $_SESSION["taikhoan"];
    $role = $_SESSION["chucvu"];

    if(isset($_POST["button_form_timkiem"])){
        if(empty($_POST["form_thang"]) || empty($_POST["form_nam"])){
            echo "<script>
                alert('Vui lòng nhập đầy đủ thông tin tìm kiếm!');
                window.location.href = 'spbanchayview.php';
                </script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/trangchu.css" >
    <link rel="stylesheet" href="../assets/spbanchay.css" >
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
                echo '<a href="spbanchayview.php" class = "active">Sản phẩm bán chạy</a>';
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

    <main>
        <div class="div_form_timkiem">
            <form method="post" id="form_timkiem">
                <label>Tháng:</label>
                <input type="number" name="form_thang" min="1" max="12">
                <label>Năm:</label>
                <input type="number" name="form_nam" min="2000" max="2100">
                <button type="submit" name="button_form_timkiem" value="btn_timkiem">Tìm kiếm</button>
                <button type="button" onclick="xoaFormTimKiem()">Hủy</button>
            </form>
        </div>
        <script>
            function xoaFormTimKiem(){
                document.getElementById("form_timkiem").reset();
            }
        </script>
        <div class="div_table_spbanchay">
            <h3 id="h3_thongtin">
                SẢN PHẨM BÁN CHẠY
            </h3>
            <table>
                <tr>
                    <th>Mã mặt hàng</th>
                    <th>Tên mặt hàng</th>
                    <th>Danh mục</th>
                    <th>Số lượng bán</th>
                </tr>
                <script>
                    function updateTitle(thang, nam){
                        document.getElementById('h3_thongtin').innerText = "SẢN PHẨM BÁN CHẠY THÁNG " + thang + " NĂM " + nam;
                    }
                </script>
                <?php
                    require_once("../models/spbanchaymodel.php");
                    if(isset($_POST["button_form_timkiem"])){
                        $thang = $_POST["form_thang"];
                        $nam = $_POST["form_nam"];
                    }
                    else{
                        $today = getdate();
                        $thang = $today["mon"];
                        $nam = $today["year"];
                    }
                    echo "<script>updateTitle($thang, $nam);</script>";
                    getTopMatHangBanChayToTable($thang, $nam);
                ?>
            </table>
        </div>
    </main>
</body>
</html>