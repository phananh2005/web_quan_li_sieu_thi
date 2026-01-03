<script>
    function xoaForm(){
        document.getElementById("form").reset();
    }

    function setValueForm(mamathang, tenmathang, soluong, dongiaban,madanhmuc, ghichu){
        document.getElementById("ip_mamathang").value=mamathang;
        document.getElementById("ip_tenmathang").value=tenmathang;
        document.getElementById("ip_soluong").value=soluong;
        document.getElementById("ip_dongiaban").value=dongiaban;
        document.getElementById("sel_tendanhmuc").value=madanhmuc;
        document.getElementById("ta_ghichu").value=ghichu;
    }
</script>

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
    
    function createSqlTimKiem(){
        $sql = "
        SELECT 
            mh.mamathang,
            mh.tenmathang,
            mh.soluong,
            mh.dongiaban,

            dm.tendanhmuc,
            dm.madanhmuc,

            mh.ghichu
        FROM mathang mh
        JOIN danhmuc dm ON mh.madanhmuc = dm.madanhmuc
        WHERE 1=1";

        if(isset($_POST['button_form_timkiem'])){

            if(!empty($_POST['form_tenmathang'])){
                $sql .= " AND mh.tenmathang LIKE '%".$_POST['form_tenmathang']."%'";
            }
            if($_POST['form_soluong'] !== ""){
                $sql .= " AND mh.soluong = ".$_POST['form_soluong'];
            }
            if($_POST['form_dongiaban'] !== ""){
                $sql .= " AND mh.dongiaban = ".$_POST['form_dongiaban'];
            }
            if(!empty($_POST['form_tendanhmuc'])){
                $sql .= " AND dm.madanhmuc = ".$_POST['form_tendanhmuc'];
            }
        }
        $sql .= "order by mh.mamathang";
        return $sql;
    }


    function createSqlTable(){
        return "
        SELECT 
            mh.mamathang,
            mh.tenmathang,
            mh.soluong,
            mh.dongiaban,

            dm.tendanhmuc,
            dm.madanhmuc,

            mh.ghichu
        FROM mathang mh
        JOIN danhmuc dm ON mh.madanhmuc = dm.madanhmuc
        ";
    }



    function kiemTraDuLieuInput ($s){
        if(empty($s)) return "null";
        else return "'".$s."'";
    }

    if(isset($_POST['form_mamathang'],$_POST['form_tenmathang'],$_POST['form_soluong'],$_POST['form_dongiaban'],
            $_POST['form_tendanhmuc'],$_POST['form_ghichu'],$_POST['button_form_themvasua'])){
        
        $mamathang = $_POST['form_mamathang'];
        $tenmathang = $_POST['form_tenmathang'];
        $soluong = kiemTraDuLieuInput($_POST['form_soluong']);
        $dongiaban = kiemTraDuLieuInput($_POST['form_dongiaban']);
        $madanhmuc = kiemTraDuLieuInput($_POST['form_tendanhmuc']);
        $ghichu = kiemTraDuLieuInput($_POST['form_ghichu']);

        if($_POST['button_form_themvasua'] == "btn_them"){
            if(empty($tenmathang)) thongBao("Điền tên mặt hàng ");
            else{
                $sql = "INSERT INTO mathang (tenmathang, soluong, dongiaban, madanhmuc, ghichu) values 
                ('".$tenmathang."',".$soluong.",".$dongiaban.", ".$madanhmuc.", ".$ghichu.")";
                $row = writeMH($sql);
                if($row>0) thongBao("Thêm thành công");
                else thongBao("Thêm thất bại");
            }     
        }

        if($_POST['button_form_themvasua'] == "btn_sua"){
            if(empty($tenmathang)) thongBao("Điền tên nhà cung cấp");
            else {
                $sql = "Update mathang SET tenmathang ='".$tenmathang."', 
                soluong = ".$soluong.", dongiaban = ".$dongiaban.",
                 madanhmuc = ".$madanhmuc.", ghichu =".$ghichu."
                where mamathang =".$mamathang;
                $row = writeMH($sql);
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
            echo '<a href="mathangview.php" class = "active">Mặt hàng</a>';

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
                    <label>Đơn Giá:</label>
                    <input type="number" name="form_dongiaban" id='ip_dongiaban'>
                </div>
                <div class="form-row">
                    <label>Danh Mục:</label>
                    <select name="form_tendanhmuc" id = "sel_tendanhmuc">
                        <option disabled selected>Chọn Danh Mục</cite></option>
                        <?php getTenDanhMucToSelect(); ?> 
                    </select>
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
                    <th>Đơn Giá</th>
                    <th>Số Lượng</th>
                    <th>Danh Mục</th>
                    <th>Ghi chú</th>
                    <th>Thao tác</th>
                </tr>
                    <?php
                    if(isset($_POST['button_form_timkiem'])){
                        $sql = createSqlTimKiem();
                    } else {
                        $sql = createSqlTable();
                    }
                    getMHToTable($sql);
                ?>
            </table>
        </div>
    </main>
</body>
</html>

<?php
if(isset($_POST['button_table_chon'])){
    echo "<script>
        setValueForm(
            '{$_POST['table_mamathang']}',
            '{$_POST['table_tenmathang']}',
            '{$_POST['table_soluong']}',
            '{$_POST['table_dongiaban']}',
            '{$_POST['table_madanhmuc']}',
            '{$_POST['table_ghichu']}'
        );
    </script>";
}

?>