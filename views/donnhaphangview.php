<?php
    session_start();
    $u = $_SESSION["taikhoan"];
    $role = $_SESSION["chucvu"];
    require_once __DIR__."/../models/donnhaphangmodel.php";
    
    function thongBao($noidung){
        echo "<script>
                alert('". $noidung ."');
                window.location.href='donnhaphangview.php';
            </script>";
    }

    if(isset($_POST['button_table_xoa'], $_POST['table_madonnhaphang'])){
        $rows = writeDNH("Delete FROM donnhaphang WHERE madonnhaphang = ". $_POST['table_madonnhaphang']);
        if($rows > 0){
            thongBao("Xóa thành công");
        }else{
            thongBao("Xóa thất bại");
        }
    }

    if(isset($_POST['table_mamathang'],$_POST['table_tenmathang'],$_POST['table_soluong'],$_POST['table_gianiemyet'],
    $_POST['table_hansudung'],$_POST['table_manhacungcap'],$_POST['table_madanhmuc'],$_POST['table_madonnhaphang'],$_POST['table_ghichu'],$_POST['button_table_xem'])){
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
    
    $madonnhaphang_xem = null;

    if (isset($_POST['button_table_xem'], $_POST['table_madonnhaphang'])) {
        $madonnhaphang_xem = $_POST['table_madonnhaphang'];
    }


    function createSqlTimKiem(){
        $sql = "Select * from donnhaphang where 1=1";
        if(isset($_POST['button_form_timkiem'])){  
            if(isset($_POST['form_ngaynhaphang'])){
                $sql .= " and ngaynhaphang like '%" . $_POST['form_ngaynhaphang'] . "%'";
            } 
        }
        return $sql;
    }

    function createSqlXem($madonnhaphang){
        if($madonnhaphang == null) return null;
        $sql = "SELECT * FROM mathang WHERE madonnhaphang = $madonnhaphang";
        return $sql;
    }


    function kiemTraDuLieuInput ($s){
        if(empty($s)) return "null";
        else return "'".$s."'";
    }

    if(isset($_POST['form_madonnhaphang'],$_POST['form_ngaynhaphang'],$_POST['form_ghichu'],$_POST['button_form_themvasua'])){
        $madonnhaphang = kiemTraDuLieuInput($_POST['form_madonnhaphang']);
        $ngaynhaphang = kiemTraDuLieuInput($_POST['form_ngaynhaphang']);
        $ghichu = kiemTraDuLieuInput($_POST['form_ghichu']);

        if($_POST['button_form_themvasua'] == "btn_them"){
                $sql = "INSERT INTO mathang ( madonnhaphang, ngaynhaphang, ghichu) values 
                (".$ngaynhaphang.", ".$ghichu.")";
                $row = writeDNH($sql);
                if($row>0) thongBao("Thêm thành công");
                else thongBao("Thêm thất bại");
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
                echo '<a href="mathangview.php">Mặt hàng</a>';
                echo '<a href="danhmucview.php">Danh mục</a>';
                echo '<a href="nhacungcapview.php">Nhà cung cấp</a>';
                echo '<a href="donnhaphangview.php"  class = "active" >Đơn nhập hàng</a>';
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
                    <label>Mã Đơn Nhập Hàng</label>
                    <input type="number" name="form_madonnhaphang" id='ip_madonnhaphang' readonly>
                </div>
                <div class="form-row">
                    <label>HSD:</label>
                    <input type="text" name="form_ngaynhaphang" id='ip_ngaynhaphang'>
                </div>
                <div class="form-row">
                    <label>Ghi chú:</label>
                    <textarea rows="6" cols="40" style="resize: none;"   name="form_ghichu" id='ta_ghichu'></textarea>
                </div>
                <br>
                <button type="submit" name='button_form_timkiem' value = "btn_timkiem">Tìm kiếm</button>
                <button type="submit" name='button_form_themvasua' value = "btn_them">Thêm</button>
                <button type="button" onclick="xoaForm()">Hủy</button>
            </form>
        </div>
        <div class = "div_table">
            <table class = "table">
                <tr>
                    <th>Mã Đơn Nhập Hàng</th>
                    <th>Ngày Nhập Hàng</th>
                    <th>Ghi chú</th>
                    <th>Thao tác</th>
                </tr>
                <?php
                    getDNHToTable(createSqlTimKiem());
                ?>
            </table>
            <br>
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
                </tr>
                <?php
                    if($madonnhaphang_xem != null){
                        getDetailToTable(createSqlXem($madonnhaphang_xem));
                    }
                    else echo '<div style="padding: 40px; text-align: center; color: #666;">
                                <i style="font-size: 4rem; color: #ddd; margin-bottom: 20px;"></i>
                                <h3>Hãy Chọn 1 Đơn Nhập Hàng</h3>
                                </div>'
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

    function setValueForm(madonnhaphang,ngaynhaphang, ghichu){
        document.getElementById("ip_madonnhaphang").value=madonnhaphang;
        document.getElementById("ip_ngaynhaphang").value=ngaynhaphang;
        document.getElementById("ta_ghichu").value=ghichu;
    }
</script>

<?php
    if(isset($_POST['table_madonnhaphang'], $_POST['table_ngaynhaphang'],$_POST['table_ghichu'],$_POST['button_table_chon'])){
        $madonnhaphang = $_POST['table_madonnhaphang'];
        $ngaynhaphang = $_POST['table_ngaynhaphang'];
        $ghichu = $_POST['table_ghichu'];
        echo "<script> setValueForm('" . $madonnhaphang . "','" .$ngaynhaphang. "', '" . $ghichu . "')</script>";
    }
?>