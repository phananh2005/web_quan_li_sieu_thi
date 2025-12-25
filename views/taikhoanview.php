<?php
    session_start();
    $user = $_SESSION["taikhoan"];
    $role = $_SESSION["chucvu"];
    require_once __DIR__."/../models/nhanvienmodel.php";
    require_once __DIR__."/../models/taikhoanmodel.php";
    
    function thongBaoVaReload($noidung){
        echo "<script>
                alert('". $noidung ."');
                window.location.href='taikhoanview.php';
            </script>";
        exit();
    }

    if(isset($_POST['table_mataikhoan'])){
        if($_POST['action'] == 'btn_Xoa'){
            $rows = deleteTaiKhoan($_POST['table_mataikhoan']);
            if($rows > 0){
                thongBaoVaReload("Xóa thành công");
            }else{
                thongBaoVaReload("Xóa thất bại");
            }
        }
    }

    if(isset($_POST['form_mataikhoan'],$_POST['form_tentaikhoan'],$_POST['form_matkhau']
            ,$_POST['form_manhanvien'],$_POST['form_chucvu'])){
        
        $mataikhoan = $_POST['form_mataikhoan'];
        $tentaikhoan = $_POST['form_tentaikhoan'];
        $matkhau = $_POST['form_matkhau'];
        $manhanvien = $_POST['form_manhanvien'];
        $chucvu = $_POST['form_chucvu'];
        
        if($_POST['action'] == 'btn_timkiem'){
            $sql = "Select * from taikhoan where 1=1";
            if(!empty($tentaikhoan)) $sql .= " and tentaikhoan like '%" . $tentaikhoan . "%'";
            if(!empty($matkhau)) $sql .= " and matkhau like '%" . $matkhau . "%'";
            if(!empty($manhanvien)) $sql .= " and manhanvien = '" . $manhanvien . "'";
            if(!empty($chucvu)) $sql .= " and chucvu = '" . $chucvu . "'";
        }
    }
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
                Xin chào <?= $user ?>. Chức vụ: <?= $role ?>
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

            if($role == "Thu ngân") chucNangThuNgan();
            else if($role == "Kho") chucNangKho();
            else if($role == "Admin"){
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
                    if(isset($sql)) getTaiKhoanToTable($sql); 
                    else getTaiKhoanToTable();
                ?>
            </table>
        </div>
        <div class = "div_form">
            <form method="post" id = "form">
                <label>Mã tài khoản:</label>
                <input type="text" name="form_mataikhoan" id='ip_mataikhoan' disabled>
                <label>Tên tài khoản:</label>
                <input type="text" name="form_tentaikhoan" id='ip_tentaikhoan'>
                <label>Mật khẩu:</label>
                <input type="text" name="form_matkhau" id='ip_matkhau'>
                <label>Mã nhân viên:</label>
                <select name="form_manhanvien" id = "sel_manhanvien">
                    <option value="" disabled selected>Chọn mã nhân viên</option>
                    <?php getMaNhanVienToSelect(); ?> 
                </select>
                <label>Chức vụ:</label>
                <select name = 'form_chucvu' id = 'sel_chucvu'>
                    <option value="" disabled selected>Chọn chức vụ</option>
                    <option value="Admin">Admin</option>
                    <option value="Kho">Kho</option>
                    <option value="Thu ngân">Thu ngân</option>    
                </select>
                <button type="submit" name='action' value = "btn_timkiem">Tìm kiếm</button>
                <button type="submit" name='action' value = "btn_them">Thêm</button>
                <button type="submit" name='action' value = "btn_sua">Sửa</button>
                <button type="button" onclick="xoaForm()">Hủy</button>
            </form>
        </div>
    </main>
</body>
</html>

<script>
    function xoaForm(){
        document.getElementById("form").reset();
    }

    function setValueForm(mataikhoan,tentaikhoan, matkhau, manhanvien, chucvu){
        document.getElementById("ip_mataikhoan").value=mataikhoan;
        document.getElementById("ip_tentaikhoan").value=tentaikhoan;
        document.getElementById("ip_matkhau").value=matkhau;
        document.getElementById("sel_manhanvien").value=manhanvien;
        document.getElementById("sel_chucvu").value=chucvu;
    }
</script>

<?php
    if(isset($_POST['table_mataikhoan'],$_POST['table_tentaikhoan'],$_POST['table_matkhau']
            ,$_POST['table_manhanvien'],$_POST['table_chucvu'])){
        $mataikhoan = $_POST['table_mataikhoan'];
        $tentaikhoan = $_POST['table_tentaikhoan'];
        $matkhau = $_POST['table_matkhau'];
        $manhanvien = $_POST['table_manhanvien'];
        $chucvu = $_POST['table_chucvu'];
        if($_POST['action'] == 'btn_Chon'){
            echo "<script> setValueForm('" .$mataikhoan . "','" . $tentaikhoan . "','" . $matkhau . "','" 
                . $manhanvien . "','" .$chucvu. "')</script>";
        }
    }
?>