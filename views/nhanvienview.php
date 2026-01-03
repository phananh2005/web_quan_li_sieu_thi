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

    // sự kiện nút xoá
    // if(isset($_POST['button_table_xoa'], $_POST['table_manhanvien'])){
    //     $manhanvien = $_POST['table_manhanvien'];
    //     $rows = writeNhanVien("Delete FROM nhanvien WHERE manhanvien = '".$manhanvien."'");
    //     if($rows > 0){
    //         thongBaoNhanVien("Xóa thành công");
    //     }else{
    //         thongBaoNhanVien("Xóa thất bại");
    //     }
    // }

    // sự kiện nút tìm kiếm
    function createSqlTimKiemNhanVien(){
        $sql = "SELECT nv.manhanvien, nv.tennhanvien, nv.ngaysinh, nv.sodienthoai, nv.gioitinh,
                    nv.quequan, bp.tenbophan, nv.trangthai
                FROM nhanvien nv
                JOIN bophan bp ON bp.mabophan = nv.mabophan
                WHERE 1=1";

        // chỉ cần POST là lọc (không phụ thuộc nút)
        if (isset($_POST['button_form_timkiem_nhanvien'])) {

            if (!empty($_POST['form_manhanvien'])) {
                $sql .= " AND nv.manhanvien = '" . $_POST['form_manhanvien'] . "'";
            }

            if (!empty($_POST['form_tennhanvien'])) {
                $sql .= " AND nv.tennhanvien LIKE '%" . $_POST['form_tennhanvien'] . "%'";
            }

            if (!empty($_POST['form_ngaysinh'])) {
                $sql .= " AND nv.ngaysinh = '" . $_POST['form_ngaysinh'] . "'";
            }

            if (!empty($_POST['form_sodienthoai'])) {
                $sql .= " AND nv.sodienthoai LIKE '%" . $_POST['form_sodienthoai'] . "%'";
            }

            if (!empty($_POST['form_gioitinh']) && $_POST['form_gioitinh'] !== 'Không chọn') {
                $sql .= " AND nv.gioitinh = '" . $_POST['form_gioitinh'] . "'";
            }

            if (!empty($_POST['form_quequan'])) {
                $sql .= " AND nv.quequan LIKE '%" . $_POST['form_quequan'] . "%'";
            }

            if (!empty($_POST['form_tenbophan']) && $_POST['form_tenbophan'] !== 'Không chọn') {
                $sql .= " AND bp.tenbophan = '" . $_POST['form_tenbophan'] . "'";
            }

            if (!empty($_POST['form_trangthai']) && $_POST['form_trangthai'] !== 'Không chọn') {
                $sql .= " AND nv.trangthai = '" . $_POST['form_trangthai'] . "'";
            }
        }

        return $sql;
    }


    // hàm lấy mabophan từ tenbophan
    function getMaBoPhanByTen($tenbophan){
        $sql = "SELECT mabophan FROM bophan WHERE tenbophan = '$tenbophan' LIMIT 1";
        $row = getOneRow($sql); // hàm này bạn cần có trong model
        return $row['mabophan'] ?? null;
    }

    // sự kiện nút thêm
    // sự kiện nút sửa
    if(isset($_POST['form_manhanvien'],$_POST['form_tennhanvien'],$_POST['form_ngaysinh']
        ,$_POST['form_sodienthoai'],$_POST['form_gioitinh'],$_POST['form_quequan']
        ,$_POST['form_tenbophan'],$_POST['form_trangthai'],$_POST['button_form_themvasua_nhanvien'])){

        $manhanvien = $_POST['form_manhanvien'];
        $tennhanvien = $_POST['form_tennhanvien'];
        $ngaysinh = $_POST['form_ngaysinh'];
        $sodienthoai = $_POST['form_sodienthoai'];
        $gioitinh = $_POST['form_gioitinh'];
        $quequan = $_POST['form_quequan'];
        $tenbophan = $_POST['form_tenbophan'];
        $trangthai = $_POST['form_trangthai'];
        
        if(isset($_POST['form_manhanvien'])){
            $manhanvien_sql = "'" . $_POST['form_manhanvien'] . "'";
        }
        if(isset($_POST['form_tennhanvien'])){
            $tennhanvien_sql = "'" . $_POST['form_tennhanvien'] . "'";
        }
        if(isset($_POST['form_ngaysinh'])){
            $mangaysinh_sql = "'" . $_POST['form_ngaysinh'] . "'";
        }
        if(isset($_POST['form_sodienthoai'])){
            $masodienthoai_sql = "'" . $_POST['form_sodienthoai'] . "'";
        }
        if(isset($_POST['form_gioitinh'])){
            $magioitinh_sql = "'" . $_POST['form_gioitinh'] . "'";
        }
        if(isset($_POST['form_quequan'])){
            $maquequan_sql = "'" . $_POST['form_quequan'] . "'";
        }
        if(isset($_POST['form_tenbophan'])){
            $tenbophan_sql = "'" . $_POST['form_tenbophan'] . "'";
        }
        if(isset($_POST['form_trangthai'])){
            $trangthai_sql = "'" . $_POST['form_trangthai'] . "'";
        }
        else thongBaoNhanVien("Phải điền đầy đủ thông tin nhân viên");
        
        $mabophan = getMaBoPhanByTen($tenbophan);

        // sự kiện nút thêm
        if($_POST['button_form_themvasua_nhanvien'] == "btn_them"){
            

            if (!$mabophan) {
                thongBaoNhanVien("Tên bộ phận không tồn tại");
                huyVaTaiLai();
            }
            if ($gioitinh === 'Không chọn' || empty($gioitinh)) {
                thongBaoNhanVien("Vui lòng chọn Giới tính hợp lệ");
                huyVaTaiLai();
            }
            if ($tenbophan === 'Không chọn' || empty($tenbophan)) {
                thongBaoNhanVien("Vui lòng chọn Bộ phận hợp lệ");
                huyVaTaiLai();
            }
            if ($trangthai === 'Không chọn' || empty($trangthai)) {
                thongBaoNhanVien("Vui lòng chọn Trạng thái hợp lệ");
                huyVaTaiLai();
            }

            $sql = "
                INSERT INTO nhanvien
                (manhanvien, tennhanvien, ngaysinh, sodienthoai, gioitinh, quequan, mabophan, trangthai)
                VALUES
                ('$manhanvien', '$tennhanvien', '$ngaysinh', '$sodienthoai',
                '$gioitinh', '$quequan', '$mabophan', '$trangthai')
            ";

            $row = writeNhanVien($sql);
            if($row>0) thongBaoNhanVien("Thêm nhân viên thành công");
            else thongBaoNhanVien("Thêm nhân viên thất bại");
        }

        $mabophansua = getMaBoPhanByTen($tenbophan);
        // sự kiện nút sửa
        if($_POST['button_form_themvasua_nhanvien'] === "btn_sua"){
            $sql = "UPDATE nhanvien 
                    SET manhanvien = '$manhanvien', 
                        tennhanvien = '$tennhanvien', 
                        ngaysinh = '$ngaysinh', 
                        sodienthoai = '$sodienthoai', 
                        gioitinh = '$gioitinh', 
                        quequan = '$quequan', 
                        mabophan = '$mabophansua',
                        trangthai = '$trangthai'
                    WHERE manhanvien = '$manhanvien'
                    ";
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
                echo '<a href="nhanvienview.php" class="active">Nhân viên</a>';
                echo '<a href="bophanview.php">Bộ phận</a>';
                echo '<a href="taikhoanview.php">Tài khoản</a>';
            }

            if($role == "Bán hàng") chucNangThuNgan($role);
            else if($role == "Kho") chucNangKho();
            else if($role == "Admin") chucNangAdmin($role);
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
                        <th>Tên bộ phận</th>
                        <th>Trạng thái</th>
                        <th>Tháo tác</th>
                    </tr>
                    <?php
                        $sqlTimKiem = createSqlTimKiemNhanVien();
                        getNhanVienToTable($sqlTimKiem); 
                    ?>
                </table>
            </div>

            <div class = "div_form_nhanvien">
                <form method="post" id = "form_nhanvien">
                    <label>Mã nhân viên:</label>
                    <input type="text" name="form_manhanvien" id='ip_manhanvien'>
                    <label>Tên nhân viên:</label>
                    <input type="text" name="form_tennhanvien" id='ip_tennhanvien'>

                    <label>Ngày sinh:</label>
                    <input type="date" name="form_ngaysinh" id='ip_ngaysinh'>
                    
                    <label>SĐT:</label>
                    <input type="text" name="form_sodienthoai" id='ip_sodienthoai'>
                    <label>Giới tính:</label>
                    <select name="form_gioitinh" id="ip_gioitinh">
                        <option value="" disabled>Chọn giới tính</option>
                        <option value="Không chọn">Không chọn</option>
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                    </select>
                    <label>Quê quán:</label>
                    <input type="text" name="form_quequan" id='ip_quequan'>

                    <label>Tên bộ phận:</label>
                    <select name="form_tenbophan" id="ip_tenbophan">
                        <option value="" disabled>-- Chọn bộ phận --</option>
                            <option value="Không chọn">Không chọn</option>
                            <option value="Bán hàng">Bán hàng</option>
                            <option value="Kho">Kho</option>
                            <option value="Kỹ thuật">Kỹ thuật</option>
                    </select>
                    
                    <label>Trạng thái:</label>
                    <select name="form_trangthai" id="form_trangthai">
                        <option value="" disabled>-- Chọn trạng thái --</option>
                        <option value="Không chọn">Không chọn</option>
                        <option value="Đang làm việc">Đang làm việc</option>
                        <option value="Đã nghỉ việc">Đã nghỉ việc</option>
                    </select>

                    <br>
                    <div id = "button_nhanvien">
                        <button type="submit" name='button_form_timkiem_nhanvien' value = "btn_timkiem">Tìm kiếm</button>
                        <button type="submit" name='button_form_themvasua_nhanvien' value = "btn_them">Thêm</button>
                        <button type="submit" name='button_form_themvasua_nhanvien' value = "btn_sua">Sửa</button>
                        <button type="button" onclick="huyVaTaiLai()">Hủy</button>
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
        document.getElementById("form_nhanvien").reset();
    }

    function huyVaTaiLai(){
        // xoaForm();
        window.location.href = "nhanvienview.php";
    }

    function setValueForm(manhanvien,tennhanvien, ngaysinh, sodienthoai, gioitinh, quequan, tenbophan, trangthai){
        document.getElementById("ip_manhanvien").value=manhanvien;
        document.getElementById("ip_tennhanvien").value=tennhanvien;
        document.getElementById("ip_ngaysinh").value=ngaysinh;
        document.getElementById("ip_sodienthoai").value=sodienthoai;
        document.getElementById("ip_gioitinh").value=gioitinh;
        document.getElementById("ip_quequan").value=quequan;
        document.getElementById("ip_tenbophan").value = tenbophan;
        document.getElementById("form_trangthai").value = trangthai;
    }

</script>

<?php

    // Hiển thị dữ liệu lên form khi nhấn chọn
    if(isset($_POST['table_manhanvien'],$_POST['table_tennhanvien'],$_POST['table_ngaysinh']
            ,$_POST['table_sodienthoai'],$_POST['table_gioitinh'],$_POST['table_quequan']
            ,$_POST['table_tenbophan'],$_POST['table_trangthai'],$_POST['button_table_chon'])){
        $manhanvien = $_POST['table_manhanvien'];
        $tennhanvien = $_POST['table_tennhanvien'];
        $ngaysinh = $_POST['table_ngaysinh'];
        $sodienthoai = $_POST['table_sodienthoai'];
        $gioitinh = $_POST['table_gioitinh'];
        $quequan = $_POST['table_quequan'];
        $tenbophan = $_POST['table_tenbophan'];
        $trangthai = $_POST['table_trangthai'];
        echo "<script> setValueForm('" .$manhanvien . "','" . $tennhanvien . "','" . $ngaysinh . "','" . $sodienthoai . "'
            ,'" . $gioitinh . "','" . $quequan . "','" . $tenbophan . "','" . $trangthai . "')
        
        </script>";
    }
?>