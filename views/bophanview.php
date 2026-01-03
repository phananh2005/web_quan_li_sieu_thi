<?php
    session_start();
    $u = $_SESSION["taikhoan"];
    $role = $_SESSION["chucvu"];
    require_once __DIR__."/../models/bophanmodel.php";

    function thongBao($noidung){
        echo "<script>
                alert('". $noidung ."');
                window.location.href='bophanview.php';
            </script>";
    }

    if(isset($_POST['button_table_xoa'], $_POST['table_mabophan'])){
        $rows = writeBoPhan("Delete FROM bophan WHERE mabophan = ". $_POST['table_mabophan']);
        if($rows > 0){
            thongBao("Xóa thành công");
        }else{
            thongBao("Xóa thất bại");
        }
    }

    function createSqlTimKiem(){
        $sql = "Select * from bophan where 1=1";
        if(isset($_POST['button_form_timkiem'])){  
            if(isset($_POST['form_tenbophan']) && $_POST['form_tenbophan'] != ""){
                $sql .= " and tenbophan like '%" . $_POST['form_tenbophan'] . "%'";
            } 
        }
        return $sql;
    }

    if(isset($_POST['button_form_themvasua'], $_POST['form_mabophan'],$_POST['form_tenbophan'])){
        $mabophan = $_POST['form_mabophan'];
        $tenbophan = $_POST['form_tenbophan'];
        if($tenbophan == "") thongBao("Phải điền đầy đủ field");

        if($_POST['button_form_themvasua'] == "btn_them"){
            $sql = "INSERT INTO bophan (mabophan, tenbophan) values 
                ('".$mabophan."','".$tenbophan."')";
            $row = writeBoPhan($sql);
            if($row>0) thongBao("Thêm thành công");
            else thongBao("Thêm thất bại");
        }

        if($_POST['button_form_themvasua'] == "btn_sua"){
            $sql = "Update bophan SET tenbophan ='".$tenbophan."' where mabophan =".$mabophan;
            $row = writeBoPhan($sql);
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
    <link rel="stylesheet" href="../assets/bophan.css" >
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
                echo '<a href="bophanview.php" class="active">Bộ phận</a>';
                echo '<a href="taikhoanview.php">Tài khoản</a>';
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
                    <th>Mã bộ phận</th>
                    <th>Tên bộ phận</th>
                    <th>Thao tác</th>
                </tr>
                <?php
                    $sqlTimKiem = createSqlTimKiem();
                    getBoPhanToTable($sqlTimKiem); 
                ?>
            </table>
        </div>
        <div class = "div_form">
            <form method="post" id = "form">
                <label>Mã bộ phận:</label>
                <input type="text" name="form_mabophan" id='ip_mabophan' readonly>
                <label>Tên bộ phận:</label>
                <input type="text" name="form_tenbophan" id='ip_tenbophan'>
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
    function xoaForm(){
        document.getElementById("form").reset();
    }
    function setValueForm(mabophan, tenbophan){
        document.getElementById("ip_mabophan").value=mabophan;
        document.getElementById("ip_tenbophan").value=tenbophan;
    }
</script>

<?php
    if(isset($_POST['button_table_chon'])){
        $mabophan = $_POST['table_mabophan'];
        $tenbophan = $_POST['table_tenbophan'];
        echo "<script> setValueForm('" .$mabophan . "','" . $tenbophan . "')</script>";
    }
?>