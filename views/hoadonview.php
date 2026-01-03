<?php
    session_start();
    $u = $_SESSION["taikhoan"];
    $role = $_SESSION["chucvu"];

    require_once __DIR__."/../models/hoadonmodel.php";

    function thongBao($noidung){
    echo "<script>
            alert('". $noidung ."');
            window.location.href='hoadonview.php';
        </script>";
    }

    if(isset($_POST['button_table_xoa'])){
        $rows = write("Delete FROM hoadon WHERE mahoadon = ". $_POST['table_mahoadon']);
        if($rows > 0){
            thongBao("Xóa thành công");
        }else{
            thongBao("Xóa thất bại");
        }
    }

    function createSqlTimKiem(){
        $sql = "select hd.mahoadon, DATE_FORMAT(ngayxuat, '%d/%m/%Y') AS ngay, tk.tentaikhoan, nv.tennhanvien, sum(COALESCE(soluong, 0)*COALESCE(dongiaban,0)) as 'tongtien'
                from hoadon hd 
                left join chitiethoadon cthd on hd.mahoadon = cthd.mahoadon
                join taikhoan tk on tk.mataikhoan = hd.mataikhoan
                left join nhanvien nv on nv.manhanvien = tk.manhanvien
                Where 1=1";
        if(isset($_POST['button_form_timkiem'])){  
            if(isset($_POST['form_ngay']) && $_POST['form_ngay'] != ""){
                $sql .= " and day(ngayxuat) ='" . $_POST['form_ngay'] . "'";
            } 
            if(isset($_POST['form_thang']) && $_POST['form_thang'] != ""){
                $sql .= " and month(ngayxuat) ='" . $_POST['form_thang'] . "'";
            } 
            if(isset($_POST['form_nam']) && $_POST['form_nam'] != ""){
                $sql .= " and year(ngayxuat) ='" . $_POST['form_nam'] . "'";
            } 
            if(isset($_POST['form_tentaikhoantaodon']) && $_POST['form_tentaikhoantaodon'] != ""){
                $sql .= " and tentaikhoan like '%" . $_POST['form_tentaikhoantaodon'] . "%'";
            } 
            if(isset($_POST['form_nhanvientaodon']) && $_POST['form_nhanvientaodon'] != ""){
                $sql .= " and tennhanvien like '%" . $_POST['form_nhanvientaodon'] . "%'";
            }
        }
        $sql .= " group by hd.mahoadon";
        if(isset($_POST['button_form_timkiem'])){
            if(isset($_POST['form_tongtientu']) || isset($_POST['form_tongtienden'])){
                $sql .= " having 1=1";
                if(isset($_POST['form_tongtientu']) && $_POST['form_tongtientu'] != ""){
                $sql .= " and tongtien >= " . $_POST['form_tongtientu'];
                }
                if(isset($_POST['form_tongtienden']) && $_POST['form_tongtienden'] != ""){
                    $sql .= " and tongtien <= " . $_POST['form_tongtienden'];
                } 
            }
        }
        $sql .= " ORDER BY hd.mahoadon";

        return $sql;
    }

    if(isset($_POST['button_tao_hd'])){
        $tenArr = $_POST['mh_ten'];
        $slArr  = $_POST['mh_sl'];

        if(empty($tenArr)){
            thongBao("Chưa có mặt hàng nào trong hóa đơn");
            exit();
        }

        $mahoadon_vuatao = createHD($u);

        foreach($tenArr as $i => $tenmathang){
            $tenmathang = trim($tenmathang);
            $soluong = (int)($slArr[$i] ?? 0);

            $mamathang = getMaMH($tenmathang);
            $dongiaban = getDonGiaBan($mamathang);

            $sql = "INSERT INTO chitiethoadon(mahoadon, mamathang, soluong, dongiaban) VALUES (".$mahoadon_vuatao.",".$mamathang.",".$soluong.",".$dongiaban.")";
            // var_dump($sql);
            // die();
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
    <link rel="stylesheet" href="../assets/hoadon.css" >
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
                echo '<a href="hoadonview.php" class = "active">Hóa đơn</a>';
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
        <!-- form tìm kiếm -->
        <div class="div_form_timkiem">
            <form method="post" id="form_timkiem">
                <label>Ngày:</label>
                <input type="number" name="form_ngay" min="1" max="31">
                <label>Tháng:</label>
                <input type="number" name="form_thang" min="1" max="12">
                <label>Năm:</label>
                <input type="number" name="form_nam" min="2000" max="2100">
                <label>Tổng tiền từ:</label>
                <input type="number" name="form_tongtientu" style="width: 60px;">
                <label>đến:</label>
                <input type="number" name="form_tongtienden" style="width: 60px;">
                <label>Tên tài khoản tạo đơn:</label>
                <input type="text" name="form_tentaikhoantaodon"style="width: 120px;">
                <label>Nhân viên tạo đơn:</label>
                <input type="text" name="form_nhanvientaodon">
                <button type="submit" name="button_form_timkiem" value="btn_timkiem">Tìm kiếm</button>
                <button type="button" onclick="xoaFormTimKiem()">Hủy</button>
                <button type="button" onclick="hien_form_ctdh(false)">Thêm hóa đơn</button>
            </form>
        </div>
        <script>
            function xoaFormTimKiem(){
                document.getElementById("form_timkiem").reset();
            }
        </script>

        <!-- bảng hóa đơn -->
        <div class = "div_table">
            <table class = "table">
                <tr>
                    <th>Mã hóa đơn</th>
                    <th>Ngày xuất</th>
                    <th>Tên tài khoản tạo</th>
                    <th>Nhân viên tạo</th>
                    <th>Tổng tiền</th>
                    <th>Thao tác</th>
                </tr>
                <?php
                    getHDToTable(createSqlTimKiem());
                ?>
            </table>
        </div>

        <!-- form chi tiết hóa đơn -->
        <div id="modal_cthd" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close" onclick="an_form_cthd()">&times;</span>
                <div class="div_form" id="div_cthd">

            <form method="post" id="form_ct">
                <h3>Chi tiết hóa đơn</h3>
                <?php
                    if(isset($_POST['button_table_xemchitiet'])) 
                        echo "<h3 id = 'h_mahoadon'> Mã hóa đơn: ".$_POST['table_mahoadon']."
                            Ngày xuất: ".$_POST['table_ngay']."</h3>";
                ?>
                <table border="1" id = "tb_cthd" style="text-align: center;">
                    <thead>
                        <tr>
                            <th>Tên mặt hàng</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody id="tb_cthd_body">
                        <?php
                        if(isset($_POST['button_table_xemchitiet'])){
                            getMHToTableHD("Select tenmathang, cthd.soluong, cthd.dongiaban, cthd.soluong*cthd.dongiaban as'thanhtien'
                                        from mathang mh join chitiethoadon cthd on mh.mamathang = cthd.mamathang
                                        where mahoadon = ".$_POST['table_mahoadon']);
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" id="tb_tongtien" style="text-align: right;">
                                <?php
                                if(isset($_POST['button_table_xemchitiet'])){
                                    echo "Tổng tiền: " . $_POST['table_tongtien']." VND";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" id="tb_tennhanvien" style="text-align: right;">
                                <?php
                                if(isset($_POST['button_table_xemchitiet'])){
                                    echo "Nhân viên tạo: " . $_POST['table_tentaikhoan'] . " - " . $_POST['table_tennhanvien'];
                                }
                                else{
                                    echo "Nhân viên tạo đơn: " . $u;
                                }
                                ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                
                <!-- nhập mặt hàng vào hóa đơn mới -->
                <div class="form-row">
                    <label id = "lb_tenmathang">Tên mặt hàng:</label>
                    <input type="text" list="products" id="ip_cttenmathang" style="width: 300px;">
                    <datalist id="products">
                        <?php
                            getTenMHToList();
                        ?>
                    </datalist>

                    <label id = "lb_dongia">Đơn giá:</label>
                    <input type="number" id="ip_ctdongia" readonly>
                    <!-- tư động nhập giá  -->
                    <script>
                        document.getElementById("ip_cttenmathang").addEventListener("input", function(){
                            const ten = this.value;
                            const options = document.querySelectorAll("#products option");

                            let gia = "";
                            options.forEach(opt => {
                                if(opt.value === ten){
                                    gia = opt.dataset.gia;
                                }
                            });

                            document.getElementById("ip_ctdongia").value = gia;
                        });
                    </script>

                    <label id = "lb_soluong">Số lượng:</label>
                    <input type="number" id="ip_ctsoluong">
                </div>
                <div class="btn-row">
                    <button type="button" id="btn_them_mh" onclick="them_mh()">Thêm mặt hàng</button>
                    <button type="submit" name="button_tao_hd" id="btn_tao_hd">Tạo hóa đơn</button>
                    <button type="button" onclick="an_form_cthd()">Hủy</button>
                </div>
            </form>
            
            <script>
            function tinhTongTien(){
                const tbody = document.getElementById("tb_cthd_body");
                let tongtien = 0;
                for(let i=0; i<tbody.rows.length; i++){
                    tongtien += Number(tbody.rows[i].cells[3].innerText);
                }
                return tongtien;
            }

            function them_mh(){
                tenmathang = document.getElementById("ip_cttenmathang").value.trim();
                soluong = Number(document.getElementById("ip_ctsoluong").value.trim());
                dongia = Number(document.getElementById("ip_ctdongia").value.trim());

                if(!tenmathang){ 
                    alert("Chưa nhập tên mặt hàng"); 
                    return; 
                }
                if(dongia === 0 ){ 
                    alert("Chon mặt hàng không hợp lệ"); 
                    return; 
                }
                if(soluong <= 0){ 
                    alert("Số lượng phải > 0"); 
                    return;
                }

                // lấy số lượng trong kho
                const ten = document.getElementById("ip_cttenmathang").value;
                const options = document.querySelectorAll("#products option");

                let soluongtrongkho ="";
                options.forEach(opt => {
                    if(opt.value === ten){
                        soluongtrongkho = opt.dataset.soluong;
                    }
                });
                if(soluong > soluongtrongkho){
                    document.getElementById("ip_ctsoluong").value = soluongtrongkho;
                    alert("Số lượng trong kho không đủ. Số lượng hiện có: " + soluongtrongkho);
                    return;
                }

                thanhtien = dongia * soluong;
                tbody = document.getElementById("tb_cthd_body");
                row = document.createElement("tr");

                row.innerHTML = `
                    <td>${tenmathang}</td>
                    <td>${soluong}</td>
                    <td>${dongia}</td>
                    <td>${thanhtien}</td>
                    <td><button type="button" class="btn_xoa" onClick="xoaDong(this)">Xóa</button></td>

                    <input type="hidden" name="mh_ten[]" value="${tenmathang}">
                    <input type="hidden" name="mh_sl[]" value="${soluong}">
                `;

                tbody.appendChild(row);

                tinhTongTien();
                document.getElementById("tb_tongtien").innerText = "Tổng tiền: " + tinhTongTien() + " VND";

                // clear input nhập
                document.getElementById("ip_cttenmathang").value = "";
                document.getElementById("ip_ctsoluong").value = "";
                document.getElementById("ip_ctdongia").value = "";
            }

            function xoaDong(btn){
                btn.closest("tr").remove();  // tìm tr gần nhất và xóa
                tinhTongTien();
                document.getElementById("tb_tongtien").innerText = "Tổng tiền: " + tinhTongTien();
            }
            </script>
        </div> 
        </div>
        </div>  
    </main>   
</body>
</html>

<script>
    function hien_form_ctdh(readonly){
        document.getElementById("modal_cthd").style.display="flex";
        if(readonly){
            document.getElementById("lb_tenmathang").style.display="none";
            document.getElementById("lb_dongia").style.display="none";
            document.getElementById("lb_soluong").style.display="none";
            document.getElementById("ip_cttenmathang").style.display="none";
            document.getElementById("ip_ctdongia").style.display="none";
            document.getElementById("ip_ctsoluong").style.display="none";
            document.getElementById("btn_tao_hd").style.display="none";
            document.getElementById("btn_them_mh").style.display="none";
        }
        else{
            reset_form_cthd();
            document.getElementById("lb_tenmathang").style.display="block";
            document.getElementById("lb_dongia").style.display="block";
            document.getElementById("lb_soluong").style.display="block";
            document.getElementById("ip_cttenmathang").style.display="block";
            document.getElementById("ip_ctdongia").style.display="block";
            document.getElementById("ip_ctsoluong").style.display="block";
            document.getElementById("btn_tao_hd").style.display="block";
            document.getElementById("btn_them_mh").style.display="block";
        }
    }

    function reset_form_cthd(){
        //reset thông tin mã hóa đơn
        const h = document.getElementById("h_mahoadon");
        if(h) h.innerText="";

        // reset table
        const tbody = document.getElementById("tb_cthd_body");
        while (tbody.rows.length > 0) {
            tbody.deleteRow(0);
        }

        // reset tổng tiền và nhân viên tạo đơn
        document.getElementById("tb_tongtien").innerText = "Tổng tiền: " + tinhTongTien();
        document.getElementById("tb_tennhanvien").innerText = "Nhân viên tạo đơn: " + laySsTenTaiKhoan();

        // reset form
        document.getElementById("form_ct").reset();
    }

    function laySsTenTaiKhoan(){
        <?php
            echo "return '" . $u ."';";
        ?>
    }

    function an_form_cthd(){
        reset_form_cthd();

        // ẩn form
        document.getElementById("modal_cthd").style.display="none";
    }

    window.onclick = function(event) {
        const modal = document.getElementById("modal_cthd");
        if (event.target === modal) {
            an_form_cthd();
        }
    }

</script>
<?php
    if(isset($_POST['button_table_xemchitiet'])){
        echo"<script>hien_form_ctdh(true);</script>";
    }
?> 