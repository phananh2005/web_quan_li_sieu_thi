<?php
    session_start();
    $user = $_SESSION["taikhoan"];
    $role = $_SESSION["chucvu"];
    require_once __DIR__."/../models/danhmucmodel.php";

    function thongBaoDanhMuc($noidung){
        echo "<script>
                alert('". $noidung ."');
                window.location.href='danhmucview.php';
            </script>";
    }

    if(isset($_POST['button_table_xoa'], $_POST['table_madanhmuc'])){
        $rows = writeDanhMuc("Delete FROM danhmuc WHERE madanhmuc = ". $_POST['table_madanhmuc']);
        if($rows > 0){
            thongBaoDanhMuc("Xóa thành công");
        }else{
            thongBaoDanhMuc("Xóa thất bại");
        }
    }

    function createSqlTimKiemDanhMuc(){
        $sql = "Select * from danhmuc where 1=1";
        if(isset($_POST['button_form_timkiem_danhmuc'])){  

            if(isset($_POST['form_tendanhmuc']) && $_POST['form_tendanhmuc'] != ""){
                $sql .= " and tendanhmuc like '%" . $_POST['form_tendanhmuc'] . "%'";
            } 
            if(isset($_POST['form_ghichu']) && $_POST['form_ghichu'] != ""){
                $sql .= " and ghichu like '%" . $_POST['form_ghichu'] . "%'";
            } 
           
        }
        return $sql;
    }

    if(isset($_POST['form_madanhmuc'],$_POST['form_tendanhmuc'],$_POST['form_ghichu'],$_POST['button_form_themvasua_danhmuc'])){
        $madanhmuc = $_POST['form_madanhmuc'];
        $tendanhmuc = $_POST['form_tendanhmuc'];
        $ghichu = $_POST['form_ghichu'];
        
        if(isset($_POST['form_tendanhmuc'])){
            $madanhmuc_sql = "'" . $_POST['form_tendanhmuc'] . "'";
        }
        else thongBaoDanhMuc("Phải điền đầy đủ field");
        

        if($_POST['button_form_themvasua_danhmuc'] == "btn_them"){
            $sql = "INSERT INTO danhmuc (tendanhmuc, ghichu) 
                    VALUES ('".$tendanhmuc."', '".$ghichu."')";
            $row = writeDanhMuc($sql);
            if($row>0) thongBaoDanhMuc("Thêm thành công");
            else thongBaoDanhMuc("Thêm thất bại");
        }

        if($_POST['button_form_themvasua_danhmuc'] == "btn_sua"){
            $sql = "UPDATE danhmuc 
                    SET tendanhmuc = '".$tendanhmuc."', 
                        ghichu = '".$ghichu."' 
                    WHERE madanhmuc = ".$madanhmuc;
            $row = writeDanhMuc($sql);
            if($row>0) thongBaoDanhMuc("Sửa thành công");
            else thongBaoDanhMuc("Sửa thất bại");
        }
    }

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/trangchu.css" >
    <link rel="stylesheet" href="../assets/danhmuc.css" >
    <title>Trang chủ - Hệ Thống Quản Lý Siêu Thị</title>
</head>
<body>
    <header class="header">
        <div class="div_tttk">
            <p>
                Xin chào: <?= $user ?>. Chức vụ: <?= $role ?>
                <a href="dangxuatview.php" class = "a_DangXuat">Đăng xuất</a>
            </p>
        </div>
    </header>
    <nav class="navbar">
        <?php
            echo '<a href="mathangview.php">Mặt hàng</a>';

            function chucNangKho(){
                echo '<a href="danhmucview.php" class="active">Danh mục</a>';
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
    <main class = "main_danhmuc">
            <div class = "div_table_danhmuc">
                <table class = "table_danhmuc">
                    <tr>
                        <th>Mã danh mục</th>
                        <th>Tên danh mục</th>
                        <th>Ghi chú</th>
                        <th>Thao tác</th>
                    </tr>
                    <?php
                        $sqlTimKiem = createSqlTimKiemDanhMuc();
                        getDanhMucToTable($sqlTimKiem); 
                    ?>
                </table>
            </div>

            <div class = "div_form_danhmuc">
                <form method="post" id = "form_danhmuc">
                    <label>Mã danh mục:</label>
                    <input type="text" name="form_madanhmuc" id='ip_madanhmuc' readonly>
                    <label>Tên danh mục:</label>
                    <input type="text" name="form_tendanhmuc" id='ip_tendanhmuc'>
                    <label>Ghi chú:</label>
                    <input type="text" name="form_ghichu" id='ip_ghichu'>
                    

                    <br>
                    <div id = "button_danhmuc">
                        <button type="submit" name='button_form_timkiem_danhmuc' value = "btn_timkiem">Tìm kiếm</button>
                        <button type="submit" name='button_form_themvasua_danhmuc' value = "btn_them">Thêm</button>
                        <button type="submit" name='button_form_themvasua_danhmuc' value = "btn_sua">Sửa</button>
                        <button type="button" onclick="xoaForm()">Hủy</button>
                    </div>
                    
                    
                </form>
        </div>

    </main>
</body>
</html>

<!-- Xử lý sự kiện -->
<script>

    function xoaForm(){
        document.getElementById("form_danhmuc").reset();
    }

    function setValueForm(madanhmuc,tendanhmuc, ghichu){
        document.getElementById("ip_madanhmuc").value=madanhmuc;
        document.getElementById("ip_tendanhmuc").value=tendanhmuc;
        document.getElementById("ip_ghichu").value=ghichu;
    }
</script>

<?php
    if(isset($_POST['table_madanhmuc'],$_POST['table_tendanhmuc'],$_POST['table_ghichu']
                ,$_POST['button_table_chon'])){
        $madanhmuc = $_POST['table_madanhmuc'];
        $tendanhmuc = $_POST['table_tendanhmuc'];
        $ghichu = $_POST['table_ghichu'];
        echo "<script> setValueForm('" .$madanhmuc . "','" . $tendanhmuc . "','" . $ghichu . "')</script>";
    }
?>