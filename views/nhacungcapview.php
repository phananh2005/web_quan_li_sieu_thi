<?php
    session_start();
    $u = $_SESSION["taikhoan"];
    $role = $_SESSION["chucvu"];
    require_once __DIR__."/../models/nhacungcapmodel.php";
    
    function thongBao($noidung){
        echo "<script>
                alert('". $noidung ."');
                window.location.href='nhacungcapview.php';
            </script>";
    }

    if(isset($_POST['button_table_xoa'], $_POST['table_manhacungcap'])){
        $rows = writeNhaCungCap("Delete FROM nhacungcap WHERE manhacungcap = ". $_POST['table_manhacungcap']);
        if($rows > 0){
            thongBao("Xóa thành công");
        }else{
            thongBao("Xóa thất bại");
        }
    }

    function createSqlTimKiem(){
        $sql = "Select * from nhacungcap where 1=1";
        if(isset($_POST['button_form_timkiem'])){  

            if(isset($_POST['form_tennhacungcap']) && $_POST['form_tennhacungcap'] != ""){
                $sql .= " and tennhacungcap like '%" . $_POST['form_tennhacungcap'] . "%'";
            } 
            if(isset($_POST['form_masothue']) && $_POST['form_masothue'] != ""){
                $sql .= " and masothue like '%" . $_POST['form_masothue'] . "%'";
            } 
            if(isset($_POST['form_sodienthoai'])){
                $sql .= " and sodienthoai like '%" . $_POST['form_sodienthoai'] . "%'";
            } 
            if(isset($_POST['form_diachi'])){
                $sql .= " and diachi like '%" . $_POST['form_diachi'] . "%'";
            } 
            if(isset($_POST['form_quocgia'])){
                $sql .= " and quocgia like '%" . $_POST['form_quocgia'] . "%'";
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

    if(isset($_POST['button_form_themvasua'])){
        if(isset($_POST['form_manhacungcap'],$_POST['form_tennhacungcap'],$_POST['form_masothue'],$_POST['form_sodienthoai'],
                $_POST['form_diachi'],$_POST['form_quocgia'],$_POST['form_ghichu'])){
            
            $manhacungcap = $_POST['form_manhacungcap'];
            $tennhacungcap = $_POST['form_tennhacungcap'];

            $masothue = kiemTraDuLieuInput($_POST['form_masothue']);
            $sodienthoai = kiemTraDuLieuInput($_POST['form_sodienthoai']);
            $diachi = kiemTraDuLieuInput($_POST['form_diachi']);
            $quocgia = kiemTraDuLieuInput($_POST['form_quocgia']);
            $ghichu = kiemTraDuLieuInput($_POST['form_ghichu']);

            if($_POST['button_form_themvasua'] == "btn_them"){
                if(empty($tennhacungcap)) thongBao("Điền tên nhà cung cấp");
                else{
                    $sql = "INSERT INTO nhacungcap (tennhacungcap, masothue, sodienthoai, diachi, quocgia, ghichu) values 
                    ('".$tennhacungcap."',".$masothue.",".$sodienthoai.",".$diachi.",".$quocgia.",".$ghichu.")";
                    $row = writeNhaCungCap($sql);
                    if($row>0) thongBao("Thêm thành công");
                    else thongBao("Thêm thất bại");
                }     
            }

            if($_POST['button_form_themvasua'] == "btn_sua"){
                if(empty($tennhacungcap)) thongBao("Điền tên nhà cung cấp");
                else {
                    $sql = "Update nhacungcap SET tennhacungcap ='".$tennhacungcap."', masothue = ".$masothue.", 
                    sodienthoai = ".$sodienthoai.", diachi = ".$diachi.", quocgia = ".$quocgia.", ghichu =".$ghichu.
                    " where manhacungcap =".$manhacungcap;
                    // var_dump($sql);
                    // die();
                    $row = writeNhaCungCap($sql);
                    if($row>0) thongBao("Sửa thành công");
                    else thongBao("Sửa thất bại");
                }
            }
        }
        else thongBao("Lỗi gửi request");
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/php/web_quan_li_sieu_thi/assets/trangchu.css" >
    <link rel="stylesheet" href="/php/web_quan_li_sieu_thi/assets/nhacungcap.css" >
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
                echo '<a href="nhacungcapview.php" class = "active">Nhà cung cấp</a>';
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
                    <label>Mã nhà cung cấp:</label>
                    <input type="text" name="form_manhacungcap" id='ip_manhacungcap' readonly>
                </div>
                <div class="form-row">
                    <label>Tên nhà cung cấp:</label>
                    <input type="text" name="form_tennhacungcap" id='ip_tennhacungcap'>
                </div>
                <div class="form-row">
                    <label>Mã số thuế:</label>
                    <input type="text" name="form_masothue" id='ip_masothue'>
                </div>
                <div class="form-row">
                    <label>Số điện thoại:</label>
                    <input type="text" name="form_sodienthoai" id='ip_sodienthoai'>
                </div>
                <div class="form-row">
                    <label>Địa chỉ:</label>
                    <input type="text" name="form_diachi" id='ip_diachi'>
                </div>
                <div class="form-row">
                    <label>Quốc gia:</label>
                    <input type="text" name="form_quocgia" id='ip_quocgia'>
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
                    <th>Mã nhà cung cấp</th>
                    <th>Tên nhà cung cấp</th>
                    <th>Mã số thuế</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Quốc gia</th>
                    <th>Ghi chú</th>
                    <th>Thao tác</th>
                </tr>
                <?php
                    getNCCToTable(createSqlTimKiem());
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

    function setValueForm(manhacungcap, tennhacungcap, masothue, sodienthoai, diachi, quocgia, ghichu){
        document.getElementById("ip_manhacungcap").value=manhacungcap;
        document.getElementById("ip_tennhacungcap").value=tennhacungcap;
        document.getElementById("ip_masothue").value=masothue;
        document.getElementById("ip_sodienthoai").value=sodienthoai;
        document.getElementById("ip_diachi").value=diachi;
        document.getElementById("ip_quocgia").value=quocgia;
        document.getElementById("ta_ghichu").value=ghichu;
    }
</script>

<?php
    if(isset($_POST['table_manhacungcap'],$_POST['table_tennhacungcap'],$_POST['table_masothue'],$_POST['table_sodienthoai'],
    $_POST['table_diachi'],$_POST['table_quocgia'],$_POST['table_ghichu'],$_POST['button_table_chon'])){
        $manhacungcap = $_POST['table_manhacungcap'];
        $tennhacungcap = $_POST['table_tennhacungcap'];
        $masothue = $_POST['table_masothue'];
        $sodienthoai = $_POST['table_sodienthoai'];
        $diachi = $_POST['table_diachi'];
        $quocgia = $_POST['table_quocgia'];
        $ghichu = $_POST['table_ghichu'];
        echo "<script> setValueForm('" .$manhacungcap . "','" . $tennhacungcap . "','" . $masothue . "','" 
            . $sodienthoai . "','" .$diachi. "','" . $quocgia . "','" . $ghichu . "')</script>";
    }
?>