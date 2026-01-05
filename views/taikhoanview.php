<?php
    session_start();
    $user = $_SESSION["taikhoan"];
    $role = $_SESSION["chucvu"];
    require_once __DIR__."/../models/taikhoanmodel.php";
    
    function thongBao($noidung){
        echo "<script>
                alert('". $noidung ."');
                window.location.href='taikhoanview.php';
            </script>";
    }

    function createSqlTimKiem(){
        $sql = "Select * from taikhoan where 1=1";
        if(isset($_POST['button_form_timkiem'])){  

            if(isset($_POST['form_tentaikhoan']) && $_POST['form_tentaikhoan'] != ""){
                $sql .= " and tentaikhoan like '%" . $_POST['form_tentaikhoan'] . "%'";
            } 
            if(isset($_POST['form_matkhau']) && $_POST['form_matkhau'] != ""){
                $sql .= " and matkhau like '%" . $_POST['form_matkhau'] . "%'";
            } 
            if(isset($_POST['form_manhanvien'])){
                $sql .= " and manhanvien = '" . $_POST['form_manhanvien'] . "'";
            } 
            if(isset($_POST['form_chucvu'])){
                $sql .= " and chucvu = '" . $_POST['form_chucvu'] . "'";
            }
            if(isset($_POST['form_trangthai'])){
                $sql .= " and trangthai = '" . $_POST['form_trangthai'] . "'";
            }
        }
        return $sql;
    }

    if(isset($_POST['button_form_themvasua'])){
        $mataikhoan = $_POST['form_mataikhoan'];
        $tentaikhoan = $_POST['form_tentaikhoan'];
        $matkhau = $_POST['form_matkhau'];
        $chucvu = $_POST['form_chucvu'];
        $trangthai = $_POST['form_trangthai'];

        if($chucvu != "Admin"){
            if(isset($_POST['form_manhanvien'])){
                $manhanvien_sql = "'" . $_POST['form_manhanvien'] . "'";
            }
            else thongBao("Phải điền đầy đủ field");
        }
        else{
            $manhanvien_sql = "null";
        }

        if($tentaikhoan == "" || $matkhau == "" || $chucvu == "" || $trangthai == "") 
            thongBao("Phải điền đầy đủ field"); 

        if($_POST['button_form_themvasua'] == "btn_them"){
            $sql = "INSERT INTO taikhoan (tentaikhoan, matkhau, manhanvien, chucvu, trangthai) values 
                ('".$tentaikhoan."','".$matkhau."',".$manhanvien_sql.",'".$chucvu."','".$trangthai."')";
            $row = writeTaiKhoan($sql);
            if($row>0) thongBao("Thêm thành công");
            else thongBao("Thêm thất bại");
        }

        if($_POST['button_form_themvasua'] == "btn_sua"){
            $sql = "Update taikhoan SET tentaikhoan ='".$tentaikhoan."', matkhau = '".$matkhau."', 
            manhanvien = ".$manhanvien_sql.", chucvu = '".$chucvu."', trangthai = '".$trangthai."' where mataikhoan =".$mataikhoan;
            $row = writeTaiKhoan($sql);
            if($row>0) thongBao("Sửa thành công");
            else thongBao("Sửa thất bại");
        }
    }


?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/trangchu.css" >
    <link rel="stylesheet" href="../assets/taikhoan.css" >
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
                echo '<a href="taikhoanview.php" class = "active">Tài khoản</a>';
            }

            if($role == "Bán hàng") chucNangThuNgan($role);
            else if($role == "Kho") chucNangKho();
            else if($role == "Admin") chucNangAdmin($role);
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
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
                <?php
                    $sqlTimKiem = createSqlTimKiem();
                    getTaiKhoanToTable($sqlTimKiem); 
                ?>
            </table>
        </div>
        <div class = "div_form">
            <form method="post" id = "form">
                <label>Mã tài khoản:</label>
                <input type="text" name="form_mataikhoan" id='ip_mataikhoan' readonly>
                <label>Tên tài khoản:</label>
                <input type="text" name="form_tentaikhoan" id='ip_tentaikhoan'>
                <label>Mật khẩu:</label>
                <input type="text" name="form_matkhau" id='ip_matkhau'>
                <label>Mã nhân viên:</label>
                <select name="form_manhanvien" id = "sel_manhanvien">
                    <option disabled selected>Chọn mã nhân viên</option>
                    <?php getMaNhanVienToSelect(); ?> 
                </select>
                <label>Chức vụ:</label>
                <select name = 'form_chucvu' id = 'sel_chucvu'>
                    <option disabled selected>Chọn chức vụ</option>
                    <option value="Admin">Admin</option>
                    <option value="Kho">Kho</option>
                    <option value="Bán hàng">Bán hàng</option>    
                </select>
                <label>Trạng thái:</label>
                <select name = 'form_trangthai' id = 'sel_trangthai'>
                    <option disabled selected>Chọn trạng thái</option>
                    <option value="Đang hoạt động">Đang hoạt động</option>
                    <option value="Ngừng hoạt động">Ngừng hoạt động</option>
                </select>
                <br>
                <button type="submit" name='button_form_timkiem' value = "btn_timkiem">Tìm kiếm</button>
                <button type="submit" name='button_form_themvasua' value = "btn_them">Thêm</button>
                <button type="submit" name='button_form_themvasua' value = "btn_sua">Sửa</button>
                <button type="button" onclick="xoaForm()">Hủy</button>
            </form>
        </div>
    </main>
</body>
</html>

<script>
    //chọn admin thì disable sel_manhanvien
    document.getElementById("sel_chucvu").addEventListener("change", function () {
        const selMNV = document.getElementById("sel_manhanvien");
        if(this.value == "Admin"){
            selMNV.selectedIndex = 0;
            selMNV.disabled=true;
        }
        else selMNV.disabled=false;
    })

    function xoaForm(){
        document.getElementById("sel_manhanvien").disabled=false;
        document.getElementById("form").reset();
    }

    function setValueForm(mataikhoan,tentaikhoan, matkhau, manhanvien, chucvu, trangthai){
        document.getElementById("ip_mataikhoan").value=mataikhoan;
        document.getElementById("ip_tentaikhoan").value=tentaikhoan;
        document.getElementById("ip_matkhau").value=matkhau;
        if(chucvu != "Admin") document.getElementById("sel_manhanvien").value=manhanvien;
        else document.getElementById("sel_manhanvien").selectedIndex = 0;
        document.getElementById("sel_chucvu").value=chucvu;
        document.getElementById("sel_trangthai").value=trangthai;
    }
</script>

<?php
    if(isset($_POST['button_table_chon'])){
        $mataikhoan = $_POST['table_mataikhoan'];
        $tentaikhoan = $_POST['table_tentaikhoan'];
        $matkhau = $_POST['table_matkhau'];
        $manhanvien = $_POST['table_manhanvien'];
        $chucvu = $_POST['table_chucvu'];
        $trangthai = $_POST['table_trangthai'];
        echo "<script> setValueForm('" .$mataikhoan . "','" . $tentaikhoan . "','" . $matkhau . "','" 
            . $manhanvien . "','" .$chucvu. "','" .$trangthai. "')</script>";
    }
?>