<?php
    session_start();
    $user = $_SESSION["taikhoan"];
    $role = $_SESSION["chucvu"];
    require_once __DIR__."/../models/nhanvienmodel.php";

    function thongBaoNhanVien($noidung){
        echo "<script>
                alert('". $noidung ."');
                window.location.href='nhanvienview.php';
            </script>";
    }

    if(isset($_POST['button_table_xoa'], $_POST['table_manhanvien'])){
        $rows = writeNhanVien("Delete FROM nhanvien WHERE manhanvien = ". $_POST['table_manhanvien']);
        if($rows > 0){
            thongBaoNhanVien("Xóa thành công");
        }else{
            thongBaoNhanVien("Xóa thất bại");
        }
    }

    function createSqlTimKiemNhanVien(){
        $sql = "Select * from nhanvien where 1=1";
        if(isset($_POST['button_form_timkiem_nhanvien'])){  

            
            if(isset($_POST['form_selectmanhanvien']) && $_POST['form_selectmanhanvien'] != ""){
                $sql .= " and manhanvien = '" . $_POST['form_selectmanhanvien'] . "'";
            }
            if(isset($_POST['form_tennhanvien']) && $_POST['form_tennhanvien'] != ""){
                $sql .= " and tennhanvien like '%" . $_POST['form_tennhanvien'] . "%'";
            } 
            if(isset($_POST['form_ngaysinh']) && $_POST['form_ngaysinh'] != ""){
                $sql .= " and ngaysinh like '%" . $_POST['form_ngaysinh'] . "%'";
            }
            if(isset($_POST['form_sdt']) && $_POST['form_sdt'] != ""){
                $sql .= " and sdt like '%" . $_POST['form_sdt'] . "%'";
            }
            if(isset($_POST['form_gioitinh']) && $_POST['form_gioitinh'] != ""){
                $sql .= " and gioitinh like '%" . $_POST['form_gioitinh'] . "%'";
            }
            if(isset($_POST['form_quequan']) && $_POST['form_quequan'] != ""){
                $sql .= " and quequan like '%" . $_POST['form_quequan'] . "%'";
            }
            if(isset($_POST['form_mabophan']) && $_POST['form_mabophan'] != ""){
                $sql .= " and mabophan like '%" . $_POST['form_mabophan'] . "%'";
            }
            
        }
        return $sql;
    }

    if(isset($_POST['form_manhanvien'],$_POST['form_tennhanvien'],$_POST['form_ngaysinh'],$_POST['form_sdt']
        ,$_POST['form_gioitinh'],$_POST['form_quequan'],$_POST['form_mabophan']
        ,$_POST['button_form_themvasua_nhanvien'])){

        $manhanvien = $_POST['form_manhanvien'];
        $tennhanvien = $_POST['form_tennhanvien'];
        $ngaysinh = $_POST['form_ngaysinh'];
        $sdt = $_POST['form_sdt'];
        $gioitinh = $_POST['form_gioitinh'];
        $quequan = $_POST['form_quequan'];
        $mabophan = $_POST['form_mabophan'];
        
        if(isset($_POST['form_manhanvien'])){
            $manhanvien_sql = "'" . $_POST['form_manhanvien'] . "'";
        }
        if(isset($_POST['form_tennhanvien'])){
            $tennhanvien_sql = "'" . $_POST['form_tennhanvien'] . "'";
        }
        if(isset($_POST['form_ngaysinh'])){
            $mangaysinh_sql = "'" . $_POST['form_ngaysinh'] . "'";
        }
        if(isset($_POST['form_sdt'])){
            $masdt_sql = "'" . $_POST['form_sdt'] . "'";
        }
        if(isset($_POST['form_gioitinh'])){
            $magioitinh_sql = "'" . $_POST['form_gioitinh'] . "'";
        }
        if(isset($_POST['form_quequan'])){
            $maquequan_sql = "'" . $_POST['form_quequan'] . "'";
        }
        if(isset($_POST['form_mabophan'])){
            $mamabophan_sql = "'" . $_POST['form_mabophan'] . "'";
        }
        else thongBaoNhanVien("Phải điền đầy đủ thông tin nhân viên");
        

        if($_POST['button_form_themvasua_nhanvien'] == "btn_them"){
            $sql = "INSERT INTO nhanvien (manhanvien, tennhanvien, ngaysinh, sdt, gioitinh, quequan, mabophan) 
                    VALUES ('".$manhanvien. "','".$tennhanvien."', '".$ngaysinh."', '".$sdt."', '".$gioitinh."'
                        , '".$quequan."', '".$mabophan."')";
            $row = writeNhanVien($sql);
            if($row>0) thongBaoNhanVien("Thêm nhân viên thành công");
            else thongBaoNhanVien("Thêm nhân viên thất bại");
        }

        if($_POST['button_form_themvasua_nhanvien'] == "btn_sua"){
            $sql = "UPDATE nhanvien 
                    SET manhanvien = '".$manhanvien."', 
                        tennhanvien = '".$tennhanvien."', 
                        ngaysinh = '".$ngaysinh."', 
                        sdt = '".$sdt."', 
                        gioitinh = '".$gioitinh."', 
                        quequan = '".$quequan."', 
                        mabophan = '".$mabophan."', 
                    WHERE manhanvien = ".$manhanvien;
            $row = writeNhanVien($sql);
            if($row>0) thongBaoNhanVien("Sửa nhân viên thành công");
            else thongBaoNhanVien("Sửa nhân viên thất bại");
        }
    }

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/trangchu.css" >
    <link rel="stylesheet" href="../assets/nhanvien.css" >
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
                echo '<a href="nhanvienview.php" class = "active" >Nhân viên</a>';
                echo '<a href="bophanview.php">Bộ phận</a>';
                echo '<a href="taikhoanview.php">Tài khoản</a>';
            }
        ?>
    </nav>
    <main class = "main_nhanvien">
            <div class = "div_table_nhanvien">
                <table class = "table_nhanvien">
                    <tr>
                        <th>Mã nhân viên</th>
                        <th>Tên nhân viên</th>
                        <th>ngày sinh</th>
                        <th>SĐT</th>
                        <th>Giới tính</th>
                        <th>Quê quán</th>
                        <th>Mã bộ phận</th>
                        <th>Tháo tác</th>
                    </tr>
                    <?php
                        $sqlTimKiem = createSqlTimKiemNhanVien();
                        getNhanVienToTable($sqlTimKiem); 
                    ?>
                </table>
            </div>

            <div class = "div_form_nhanvien">
                <form method="post" id = "form">
                    <label>Mã nhân viên:</label>
                    <input type="text" name="form_manhanvien" id='ip_manhanvien'>
                    <label>Tên nhân viên:</label>
                    <input type="text" name="form_tennhanvien" id='ip_tennhanvien'>
                    <label>Ngày sinh:</label>
                    <input type="text" name="form_ngaysinh" id='ip_ngaysinh'>
                    <label>SĐT:</label>
                    <input type="text" name="form_sdt" id='ip_sdt'>
                    <label>Giới tính:</label>
                    <input type="text" name="form_gioitinh" id='ip_gioitinh'>
                    <label>Quê quán:</label>
                    <input type="text" name="form_quequan" id='ip_quequan'>
                    <label>Mã bộ phận:</label>
                    <input type="text" name="form_mabophan" id='ip_mabophan'>
                    <select name="form_selectmanhanvien" id = "sel_manhanvien">
                        <option disabled selected>Chọn mã nhân viên</option>
                        <?php getMaNhanVienToSelect(); ?> 
                    </select>

                    <br>
                    <div id = "button_nhanvien">
                        <button type="submit" name='button_form_timkiem_nhanvien' value = "btn_timkiem">Tìm kiếm</button>
                        <button type="submit" name='button_form_themvasua_nhanvien' value = "btn_them">Thêm</button>
                        <button type="submit" name='button_form_themvasua_nhanvien' value = "btn_sua">Sửa</button>
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
        document.getElementById("sel_manhanvien").disabled=false;
        document.getElementById("form").reset();
    }

    function setValueForm(manhanvien,tennhanvien, ngaysinh, sdt, gioitinh, quequan, mabophan){
        document.getElementById("ip_manhanvien").value=manhanvien;
        document.getElementById("ip_tennhanvien").value=tennhanvien;
        document.getElementById("ip_ngaysinh").value=ngaysinh;
        document.getElementById("ip_sdt").value=sdt;
        document.getElementById("ip_gioitinh").value=gioitinh;
        document.getElementById("ip_quequan").value=quequan;
        document.getElementById("ip_mabophan").value=mabophan;
    }
</script>

<?php
    if(isset($_POST['table_manhanvien'],$_POST['table_tennhanvien'],$_POST['table_ngaysinh'],$_POST['table_sdt']
            ,$_POST['table_gioitinh'],$_POST['table_quequan'],$_POST['table_mabophan'],$_POST['button_table_chon'])){
        $manhanvien = $_POST['table_manhanvien'];
        $tennhanvien = $_POST['table_tennhanvien'];
        $ngaysinh = $_POST['table_ngaysinh'];
        $sdt = $_POST['table_sdt'];
        $gioitinh = $_POST['table_gioitinh'];
        $quequan = $_POST['table_quequan'];
        $mabophan = $_POST['table_mabophan'];
        echo "<script> setValueForm('" .$manhanvien . "','" . $tennhanvien . "','" . $ngaysinh . "','" . $sdt . "'
            ,'" . $gioitinh . "','" . $quequan . "','" . $mabophan . "')
        
        </script>";
    }
?>