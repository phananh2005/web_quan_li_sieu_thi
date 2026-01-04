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
        $rows = write("Delete FROM donnhaphang WHERE madonnhaphang = ". $_POST['table_madonnhaphang']);
        if($rows > 0){
            thongBao("Xóa thành công");
        }else{
            thongBao("Xóa thất bại");
        }
    }

    function createSqlTimKiem(){
        $sql = "
        SELECT 
            dnh.madonnhaphang,
            ncc.tennhacungcap,
            dnh.ngaynhaphang,
            tk.manhanvien,
            SUM(ctdnh.soluong * ctdnh.dongianhap) AS tongtien,
            dnh.ghichu
        FROM donnhaphang dnh
        JOIN chitietdonnhaphang ctdnh ON dnh.madonnhaphang = ctdnh.madonnhaphang
        JOIN nhacungcap ncc ON dnh.manhacungcap = ncc.manhacungcap
        JOIN taikhoan tk ON dnh.mataikhoan = tk.mataikhoan
        WHERE 1=1
        ";
        if(isset($_POST['button_form_timkiem'])){
            if($_POST['form_ngay'] != "")
                $sql .= " AND DAY(dnh.ngaynhaphang) = ".$_POST['form_ngay'];
            if($_POST['form_thang'] != "")
                $sql .= " AND MONTH(dnh.ngaynhaphang) = ".$_POST['form_thang'];
            if($_POST['form_nam'] != "")
                $sql .= " AND YEAR(dnh.ngaynhaphang) = ".$_POST['form_nam'];
            if($_POST['form_tennhacungcap'] != "")
                $sql .= " AND ncc.manhacungcap LIKE '%".$_POST['form_tennhacungcap']."%'";
            if($_POST['form_ghichu'] != "")
                $sql .= " AND dnh.ghichu LIKE '%".$_POST['form_ghichu']."%'";
        }
        $sql .= " GROUP BY dnh.madonnhaphang";
        return $sql;
    }

    if(isset($_POST['button_tao_dnh'],$_POST['mh_ten'],$_POST['mh_sl'],
    $_POST['ct_nhacungcap'],$_POST['mh_dongianhap'])){

        if (empty($_POST['mh_ten'])) {
            thongBao("Phải thêm ít nhất 1 mặt hàng");
            exit;
        }

        $tenArr = $_POST['mh_ten'];
        $slArr  = $_POST['mh_sl'];
        $dongianhapArr = $_POST['mh_dongianhap'];
        $mancc  = $_POST['ct_nhacungcap'];

        if (empty($mancc)) {
            thongBao("Chưa chọn nhà cung cấp");
        }

        $madonnhaphang_vuatao = createDNH($mancc,$u);
        if(!$madonnhaphang_vuatao){
            thongBao("Tạo đơn nhập hàng thất bại");
            exit;
        }
        
        foreach($tenArr as $i => $tenmathang){
            $tenmathang = trim($tenmathang);
            $soluong = (int)($slArr[$i] ?? 0);
            $dongianhap = (int)($dongianhapArr[$i] ?? 0);
            $mamathang = getMaMH($tenmathang);
            $sql = "INSERT INTO chitietdonnhaphang(mamathang,madonnhaphang, soluong,dongianhap) 
            VALUES (".$mamathang.",".$madonnhaphang_vuatao.",".$soluong.",".$dongianhap.")";

            $row = write($sql);
            if($row == 0) thongBao("Tạo ko thành công");
        }
        thongBao("Tạo thành công");
    }

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/trangchu.css" >
    <link rel="stylesheet" href="../assets/DNH.css" >
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
                echo '<a href="donnhaphangview.php" class="active">Đơn nhập hàng</a>';
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
        <!-- tìm kiếm -->
        <div class="div_form_timkiem">
            <form method="post" id="form_dnh">
                <label>Ngày:</label>
                <input type="text" name="form_ngay">
                <label>Tháng:</label>
                <input type="text" name="form_thang">
                <label>Năm:</label>
                <input type="text" name="form_nam">
                <label>Tên Nhà Cung Cấp:</label>
                <select name="form_tennhacungcap">
                    <option value="">-- Chọn nhà cung cấp --</option>
                    <?php getNhaCungCapToSelect(); ?>
                </select>
                <label>Ghi chú:</label>
                <input type="text" name="form_ghichu">
                <br>
                <button type="submit" name="button_form_timkiem" value="btn_timkiem">Tìm kiếm</button>
                <button type="button" onclick="xoaForm()">Hủy</button>
                <button type="button" onclick="hien_form_ctdnh(false)">Thêm đơn nhập hàng</button>
            </form>
        </div>

        <!-- chi tiết đơn nhập hàng -->
        <div id="modal_ctdnh" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="an_form_ctdnh()">&times;</span>
            <div class="div_form" id="div_ctdnh">

            <form method="post" id="form_ct">
                <div class="modal-header">
                    <h3>Chi tiết Đơn Nhập Hàng</h3>
                    <?php
                        if(isset($_POST['table_madonnhaphang'])) echo "<h3 id = 'h_madonnhaphang'> Mã Đơn Nhập Hàng: ".$_POST['table_madonnhaphang']."</h3>";
                    ?>
                    <table border="1" class = "table_form" id = "tb_ctdnh">
                        <thead>
                            <tr>
                                <th>Tên mặt hàng</th>
                                <th>Số lượng</th>
                                <th>Giá Nhập</th>
                                <th>Thành tiền</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="tb_ctdnh_body">
                            <?php
                            if(isset($_POST['table_madonnhaphang'],$_POST['button_table_xemchitiet'])){
                                getMHToTableDNH("
                                    SELECT 
                                        mh.tenmathang,
                                        ctdnh.soluong,
                                        ctdnh.dongianhap,
                                        ctdnh.soluong * ctdnh.dongianhap AS thanhtien
                                    FROM chitietdonnhaphang ctdnh
                                    JOIN mathang mh ON mh.mamathang = ctdnh.mamathang
                                    WHERE ctdnh.madonnhaphang = ".$_POST['table_madonnhaphang']
                                );
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="form-row">
                        <label id= "lb_tennhacungcap"><b>Nhà cung cấp:</b></label>
                            <select name="ct_nhacungcap" id="ip_ctnhacungcap_main">
                                <option value="">-- Chọn nhà cung cấp --</option>
                                <?php getNhaCungCapToSelect(); ?>
                            </select>
                        <hr>
                        <label id="lb_tenmathang">Tên mặt hàng:</label>
                        <input name="ct_tenmathang" type="text" id="ip_cttenmathang" list="ds_mathang" autocomplete="off">
                        <datalist id="ds_mathang">
                            <?php getTenMHToList(); ?>
                        </datalist>
                        <!-- Chạy tên mặt hàng bằng select option thay vì input -->
                        <!-- <label id="lb_tenmathang"><b>Tên Mặt Hàng:</b></label>
                            <select name="ct_tenmathang" id="ip_cttenmathang">
                                <option value="">-- Chọn nhà cung cấp --</option>
                                gọi hàm getTenMHToList(); để test
                            </select> -->
                        <label id = "lb_dongianhap">Giá Nhập:</label>
                        <input type="number" name="ct_dongianhap" id="ip_ctdongianhap">
                        <label id = "lb_soluong">Số lượng:</label>
                        <input type="text" name="ct_soluong" id="ip_ctsoluong">
                        <label id="lb_thanhtien">Thành Tiền:</label>
                        <input type="text" name="ct_thanhtien" id="ip_ctthanhtien" readonly>
                        <script>
                            function tinhThanhTien(){
                                const sl = Number(document.getElementById("ip_ctsoluong").value) || 0;
                                const dg = Number(document.getElementById("ip_ctdongianhap").value) || 0;
                                document.getElementById("ip_ctthanhtien").value = sl * dg;
                            }

                            document.getElementById("ip_ctsoluong").addEventListener("input", tinhThanhTien);
                            document.getElementById("ip_ctdongianhap").addEventListener("input", tinhThanhTien);
                        </script>
                    </div>
                    <div class="btn-row">
                        <button type="button" id="btn_them_mh" onclick="them_mh()">Thêm mặt hàng</button>
                        <button type="submit" name="button_tao_dnh" id="btn_tao_dnh">Tạo Đơn Nhập Hàng</button>
                        <button type="button" onclick="an_form_ctdnh()">Hủy</button>
                    </div>
                </div>
            </form>
        </div>

        
        </div> 
        </div>
        <!-- bảng hóa đơn -->
        <div class = "div_table">
            <table class = "table">
                <tr>
                    <th>Mã Đơn Nhập Hàng</th>
                    <th>Tên Nhà Cung Cấp</th>
                    <th>Ngày Nhập Hàng</th>
                    <th>Người Tạo Nhập Hàng</th>
                    <th>Tổng tiền</th>
                    <th>Ghi Chú</th>
                    <th>Thao tác</th>
                </tr>
                <?php
                    getDNHToTable(createSqlTimKiem());
                ?>
            </table>
        </div>
    </main>      
</body>
</html>

<script>
    function xoaForm(){
        document.getElementById("form_dnh").reset();
        an_form_ctdnh();
    }

    function hien_form_ctdnh(readonly){
        document.getElementById("modal_ctdnh").style.display = "block";

        if(readonly){
            // Ẩn các input thêm mới
            document.getElementById("lb_tenmathang").style.display = "none";
            document.getElementById("lb_tennhacungcap").style.display = "none";
            document.getElementById("lb_dongianhap").style.display = "none";
            document.getElementById("lb_soluong").style.display = "none";
            document.getElementById("lb_thanhtien").style.display = "none";
            document.getElementById("ip_cttenmathang").style.display = "none";
            document.getElementById("ip_ctdongianhap").style.display = "none";
            document.getElementById("ip_ctsoluong").style.display = "none";
            document.getElementById("ip_ctthanhtien").style.display = "none";
            document.getElementById("ip_ctnhacungcap_main").style.display = "none";
            // Ẩn nút tạo & thêm
            document.getElementById("btn_them_mh").style.display = "none";
            document.getElementById("btn_tao_dnh").style.display = "none";
        } 
        else {
            reset_form_ctdnh();
            document.getElementById("form_ct").reset();
            document.getElementById("lb_tenmathang").style.display = "block";
            document.getElementById("lb_tennhacungcap").style.display = "block";
            document.getElementById("lb_dongianhap").style.display = "block";
            document.getElementById("lb_soluong").style.display = "block";
            document.getElementById("lb_thanhtien").style.display = "block";
            document.getElementById("ip_cttenmathang").style.display = "block";
            document.getElementById("ip_ctdongianhap").style.display = "block";
            document.getElementById("ip_ctsoluong").style.display = "block";
            document.getElementById("ip_ctthanhtien").style.display = "block";
            document.getElementById("ip_ctnhacungcap_main").style.display = "block";
            document.getElementById("btn_them_mh").style.display = "inline-block";
            document.getElementById("btn_tao_dnh").style.display = "inline-block";
        }
    }
    


    function reset_form_ctdnh(){
        const h = document.getElementById("h_madonnhaphang");
        if(h) h.innerText = "";

        const table = document.getElementById("tb_ctdnh");
        while (table.rows.length > 1) {
            table.deleteRow(1);
        }

        document.getElementById("form_ct").reset();
    }

    function an_form_ctdnh(){
        reset_form_ctdnh();
        document.getElementById("modal_ctdnh").style.display="none";
    }

    window.onclick = function(event) {
        const modal = document.getElementById("modal_ctdnh");
        if (event.target === modal) {
            an_form_ctdnh();
        }
    }

    function isInDatalist(inputId, datalistId) {
        const input = document.getElementById(inputId);
        const list = document.getElementById(datalistId);

        const value = input.value.trim();
        if (!value) return false;

        return Array.from(list.options).some(opt => opt.value.trim() === value);
    }


    function them_mh(){
        const tenmathang = document.getElementById("ip_cttenmathang").value.trim();
        const soluong = Number(document.getElementById("ip_ctsoluong").value.trim());
        const dongianhap = Number(document.getElementById("ip_ctdongianhap").value.trim());

        const selectNCC = document.getElementById("ip_ctnhacungcap_main");
        const mancc = selectNCC.value;

        if(!mancc){
            alert("Vui lòng chọn nhà cung cấp trước");
            return;
        }
        if(!tenmathang){ 
            alert("Chưa nhập tên mặt hàng"); 
            return; 
        }
        if(!isInDatalist("ip_cttenmathang", "ds_mathang")){
            alert("Tên mặt hàng không hợp lệ! Vui lòng chọn từ danh sách.");
            document.getElementById("ip_cttenmathang").focus();
            return;
        }
        if(isNaN(soluong) || soluong <= 0){ 
            alert("Số lượng phải > 0"); 
            return; 
        }
        if(isNaN(dongianhap) || dongianhap < 0){ 
            alert("Đơn giá không hợp lệ"); 
            return; 
        }

        const thanhtien = dongianhap * soluong;
        const tbody = document.getElementById("tb_ctdnh_body");
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${tenmathang}</td>
            <td>${soluong}</td>
            <td>${dongianhap}</td>
            <td>${thanhtien}</td>
            <td><button type="button" class="btn_xoa">Xóa</button></td>

            <input type="hidden" name="mh_ten[]" value="${tenmathang}">
            <input type="hidden" name="mh_sl[]" value="${soluong}">
            <input type="hidden" name="mh_dongianhap[]" value="${dongianhap}">
        `;

        row.querySelector(".btn_xoa").addEventListener("click", () => row.remove());
        tbody.appendChild(row);

        // clear input
        document.getElementById("ip_cttenmathang").value = "";
        document.getElementById("ip_ctsoluong").value = "";
        document.getElementById("ip_ctdongianhap").value = "";
        document.getElementById("ip_ctthanhtien").value = "";
        document.getElementById("ip_ctnhacungcap_main").style.pointerEvents = "none";
        document.getElementById("ip_ctnhacungcap_main").style.backgroundColor = "#eee";
    }
</script>
<?php
    if(isset($_POST['table_madonnhaphang'],$_POST['button_table_xemchitiet'])){
        echo"<script>hien_form_ctdnh(true);</script>";
        
    }
?>