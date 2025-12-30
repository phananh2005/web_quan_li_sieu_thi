<?php
    session_start();
    $u = $_SESSION["taikhoan"];
    $role = $_SESSION["chucvu"];
    require_once __DIR__."/../models/mathangmodel.php";
    
    function thongBao($noidung){
        echo "<script>
                alert('". $noidung ."');
                window.location.href='mathangview.php';
            </script>";
    }

    if(isset($_POST['button_table_xoa'], $_POST['table_mamathang'])){
        $rows = writeMH("Delete FROM mathang WHERE mamathang = ". $_POST['table_mamathang']);
        if($rows > 0){
            thongBao("Xóa thành công");
        }else{
            thongBao("Xóa thất bại");
        }
    }

    if(isset($_POST['table_mamathang'],$_POST['table_tenmathang'],$_POST['table_soluong'],$_POST['table_gianiemyet'],
    $_POST['table_hansudung'],$_POST['table_manhacungcap'],$_POST['table_madanhmuc'],$_POST['table_madonnhaphang'],$_POST['table_ghichu'],$_POST['button_table_chon'])){
        $mamathang = $_POST['table_mamathang'];
        $tenmathang = $_POST['table_tenmathang'];
        $soluong = $_POST['table_soluong'];
        $gianiemyet = $_POST['table_gianiemyet'];
        $hansudung = $_POST['table_hansudung'];
        $manhacungcap = $_POST['table_manhacungcap'];
        $madanhmuc = $_POST['table_madanhmuc'];
        $madonnhaphang = $_POST['table_madonnhaphang'];
        $ghichu = $_POST['table_ghichu'];
        echo "<script> setValueForm('" .$mamathang . "','" . $tenmathang . "','" 
            . $soluong . "','" . $gianiemyet . "','" .$hansudung. "','" . $manhacungcap . "', '" . $madanhmuc . "', '" . $madonnhaphang . "','" . $ghichu . "')</script>";
    }

    function createSqlTimKiem(){
        $sql = "Select * from mathang where 1=1";
        if(isset($_POST['button_form_timkiem'])){  

            if(isset($_POST['form_tenmathang']) && $_POST['form_tenmathang'] != ""){
                $sql .= " and tenmathang like '%" . $_POST['form_tenmathang'] . "%'";
            } 
            if(isset($_POST['form_soluong'])){
                $sql .= " and soluong like '%" . $_POST['form_soluong'] . "%'";
            } 
            if(isset($_POST['form_gianiemyet']) && $_POST['form_gianiemyet'] != ""){
                $sql .= " and gianiemyet like '%" . $_POST['form_gianiemyet'] . "%'";
            } 
            if(isset($_POST['form_hansudung'])){
                $sql .= " and hansudung like '%" . $_POST['form_hansudung'] . "%'";
            } 
            if(isset($_POST['form_manhacungcap'])){
                $sql .= " and manhacungcap like '%" . $_POST['form_manhacungcap'] . "%'";
            } 
            if(isset($_POST['form_madanhmuc'])){
                $sql .= " and madanhmuc like '%" . $_POST['form_madanhmuc'] . "%'";
            } 
            if(isset($_POST['form_madonnhaphang'])){
                $sql .= " and madonnhaphang like '%" . $_POST['form_madonnhaphang'] . "%'";
            } 
            if(isset($_POST['form_ghichu'])){
                $sql .= " and ghichu like '%" . $_POST['form_ghichu'] . "%'";
            } 
        }
        return $sql;
    }

    function kiemTraDuLieuInput ($s){
        if(empty($s)) return "null";
        else return "'".$s."'";
    }

    if(isset($_POST['form_mamathang'],$_POST['form_tenmathang'],$_POST['form_soluong'],$_POST['form_gianiemyet'],
            $_POST['form_hansudung'],$_POST['form_manhacungcap'],$_POST['form_madanhmuc'],$_POST['form_madonnhaphang'],$_POST['form_ghichu'],$_POST['button_form_themvasua'])){
        
        $mamathang = $_POST['form_mamathang'];
        $tenmathang = $_POST['form_tenmathang'];
        $soluong = kiemTraDuLieuInput($_POST['form_soluong']);
        $gianiemyet = kiemTraDuLieuInput($_POST['form_gianiemyet']);
        $hansudung = kiemTraDuLieuInput($_POST['form_hansudung']);
        $manhacungcap = kiemTraDuLieuInput($_POST['form_manhacungcap']);
        $madanhmuc = kiemTraDuLieuInput($_POST['form_madanhmuc']);
        $madonnhaphang = kiemTraDuLieuInput($_POST['form_madonnhaphang']);
        $ghichu = kiemTraDuLieuInput($_POST['form_ghichu']);

        if($_POST['button_form_themvasua'] == "btn_them"){
            if(empty($tenmathang)) thongBao("Điền tên mặt hàng ");
            else{
                $sql = "INSERT INTO mathang (tenmathang, soluong, gianiemyet, hansudung, manhacungcap, madanhmuc, madonnhaphang, ghichu) values 
                ('".$tenmathang."',".$soluong.",".$gianiemyet.",".$hansudung.",".$manhacungcap.", ".$madanhmuc.", ".$madonnhaphang.", ".$ghichu.")";
                $row = writeMH($sql);
                if($row>0) thongBao("Thêm thành công");
                else thongBao("Thêm thất bại");
            }     
        }

        if($_POST['button_form_themvasua'] == "btn_sua"){
            if(empty($tenmathang)) thongBao("Điền tên nhà cung cấp");
            else {
                $sql = "Update mathang SET tenmathang ='".$tenmathang."', 
                soluong = ".$soluong.", gianiemyet = ".$gianiemyet.", hansudung = ".$hansudung.",
                 manhacungcap = ".$manhacungcap.", madanhmuc = ".$madanhmuc.", madonnhaphang = ".$manhacungcap3.", ghichu =".$ghichu.
                " where mamathang =".$mamathang;
                // var_dump($sql);
                // die();
                $row = write($sql);
                if($row>0) thongBao("Sửa thành công");
                else thongBao("Sửa thất bại");
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/trangchu.css" >
    <link rel="stylesheet" href="../assets/MatHang1.css" >
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
                echo '<a href="mathangview.php"  class = "active" >Mặt hàng</a>';
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
                echo '<a href="taikhoanview.php">Tài khoản</a>';
            }
        ?>
    </nav>
    <main class="main">
        <div class = "div_form">
            <form method="post" id = "form">  
                <div class="form-row">  
                    <label>Mã Măt Hàng :</label>
                    <input type="number" name="form_mamathang" id='ip_mamathang' readonly>
                </div>
                <div class="form-row">
                    <label>Tên Măt Hàng:</label>
                    <input type="text" name="form_tenmathang" id='ip_tenmathang'>
                </div>
                <div class="form-row">
                    <label>Số Lượng:</label>
                    <input type="number" name="form_soluong" id='ip_soluong'>
                </div>
                <div class="form-row">
                    <label>Giá Niêm Yết:</label>
                    <input type="number" name="form_gianiemyet" id='ip_gianiemyet'>
                </div>
                <div class="form-row">
                    <label>HSD:</label>
                    <input type="text" name="form_hansudung" id='ip_hansudung'>
                </div>
                <div class="form-row">
                    <label>Nhà Cung Cấp:</label>
                    <input type="number" name="form_manhacungcap" id='ip_manhacungcap'>
                </div>
                <div class="form-row">
                    <label>Mã Danh Mục:</label>
                    <input type="number" name="form_madanhmuc" id='ip_madanhmuc'>
                </div>
                <div class="form-row">
                    <label>Mã Đơn Nhập Hàng:</label>
                    <input type="number" name="form_madonnhaphang" id='ip_madonnhaphang'>
                </div>
                <div class="form-row">
                    <label>Ghi chú:</label>
                    <textarea rows="6" cols="40" style="resize: none;"   name="form_ghichu" id='ta_ghichu'></textarea>
                </div>
                <br>
                <button type="submit" name='button_form_timkiem' value = "btn_timkiem">Tìm kiếm</button>
                <button type="submit" name='button_form_themvasua' value = "btn_them">Thêm</button>
                <button type="submit" name='button_form_themvasua' value = "btn_sua">Sửa</button>
                <button type="button" onclick="xoaForm()">Hủy</button>
            </form>
        </div>
        <div class = "div_table">
            <table class = "table">
                <tr>
                    <th>Mã Măt Hàng </th>
                    <th>Tên Măt Hàng</th>
                    <th>Giá Niêm Yết</th>
                    <th>Số Lượng</th>
                    <th>HSD</th>
                    <th>Nhà Cung Cấp</th>
                    <th>Mã Danh Mục</th>
                    <th>Mã Đơn Nhập Hàng</th>
                    <th>Ghi chú</th>
                    <th>Thao tác</th>
                </tr>
                <?php
                    getMHToTable(createSqlTimKiem());
                ?>
            </table>
        </div>
    </main>
</body>
</html>

<script>
    function xoaForm(){
        document.getElementById("form").reset();
    }

    function setValueForm(mamathang, tenmathang, soluong, gianiemyet, hansudung, manhacungcap,madanhmuc, madonnhaphang, ghichu){
        document.getElementById("ip_mamathang").value=mamathang;
        document.getElementById("ip_tenmathang").value=tenmathang;
        document.getElementById("ip_soluong").value=soluong;
        document.getElementById("ip_gianiemyet").value=gianiemyet;
        document.getElementById("ip_hansudung").value=hansudung;
        document.getElementById("ip_manhacungcap").value=manhacungcap;
        document.getElementById("ip_madanhmuc").value=madanhmuc;
        document.getElementById("ip_madonnhaphang").value=madonnhaphang;
        document.getElementById("ta_ghichu").value=ghichu;
    }
</script>