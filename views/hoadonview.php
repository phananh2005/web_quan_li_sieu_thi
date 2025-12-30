<?php
    session_start();
    $u = $_SESSION["taikhoan"];
    $role = $_SESSION["chucvu"];

    require_once __DIR__."/../models/hoadonmodel.php";
    require_once __DIR__."/../models/mathangmodel.php";

    function thongBao($noidung){
    echo "<script>
            alert('". $noidung ."');
            window.location.href='hoadonview.php';
        </script>";
    }

    if(isset($_POST['button_table_xoa'], $_POST['table_mahoadon'])){
        $rows = write("Delete FROM hoadon WHERE mahoadon = ". $_POST['table_mahoadon']);
        if($rows > 0){
            thongBao("Xóa thành công");
        }else{
            thongBao("Xóa thất bại");
        }
    }

    function createSqlTimKiem(){
        $sql = "select hd.mahoadon, ngayxuat, sum(cthd.soluong*gianiemyet) as 'tongtien' 
                from hoadon hd join chitiethoadon cthd on hd.mahoadon = cthd.mahoadon 
                join mathang mh on mh.mamathang = cthd.mamathang
                group by hd.mahoadon, ngayxuat 
                Having 1=1";
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
            if(isset($_POST['form_tongtientu']) && $_POST['form_tongtientu'] != ""){
                if(!is_numeric($_POST['form_tongtientu'])) thongBao("Nhập số nhá"); 
                $sql .= " and tongtien >=" . $_POST['form_tongtientu'];
            }
            if(isset($_POST['form_tongtienden']) && $_POST['form_tongtienden'] != ""){
                if(!is_numeric($_POST['form_tongtienden'])) thongBao("Nhập số nhá");
                $sql .= " and tongtien <=" . $_POST['form_tongtienden'];
            }
        }  
        return $sql;
    }

    if(isset($_POST['button_tao_hd'],$_POST['mh_ten'],$_POST['mh_sl'])){
        $tenArr = $_POST['mh_ten'];
        $slArr  = $_POST['mh_sl'];

        $mahoadon_vuatao = createHD();

        foreach($tenArr as $i => $tenmathang){
            $tenmathang = trim($tenmathang);
            $soluong = (int)($slArr[$i] ?? 0);

            $mamathang = getMaMH($tenmathang);

            $sql = "INSERT INTO chitiethoadon(mahoadon, mamathang, soluong) VALUES (".$mahoadon_vuatao.",".$mamathang.",".$soluong.")";

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
            function chucNangKho(){
                echo '<a href="mathangview.php">Mặt hàng</a>';
                echo '<a href="danhmucview.php">Danh mục</a>';
                echo '<a href="nhacungcapview.php">Nhà cung cấp</a>';
                echo '<a href="donnhaphangview.php">Đơn nhập hàng</a>';
            }
            function chucNangThuNgan(){
                echo '<a href="hoadonview.php" class = "active">Hóa đơn</a>';
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
        <!-- form tìm kiếm -->
        <div class="div_form">
            <form method="post" id="form_hd">
                <label>Ngày:</label>
                <input type="text" name="form_ngay">
                <label>Tháng:</label>
                <input type="text" name="form_thang">
                <label>Năm:</label>
                <input type="text" name="form_nam">
                <label>Tổng tiền từ:</label>
                <input type="text" name="form_tongtientu">
                <label>đến:</label>
                <input type="text" name="form_tongtienden">
                <button type="submit" name="button_form_timkiem" value="btn_timkiem">Tìm kiếm</button>
                <button type="button" onclick="xoaForm()">Hủy</button>
                <button type="button" onclick="hien_form_ctdh(false)">Thêm hóa đơn</button>
            </form>
        </div>

        <!-- form chi tiết hóa đơn -->
        <div class="div_form" style="display: none;" id="div_cthd">
            <form method="post" id="form_ct">
                <h3>Chi tiết hóa đơn</h3>
                <?php
                    if(isset($_POST['table_mahoadon'])) echo "<h3 id = 'h_mahoadon'> Mã hóa đơn: ".$_POST['table_mahoadon']."</h3>";
                ?>
                <table border="1" class = "table_form" id = "tb_cthd">
                    <thead>
                        <tr>
                            <th>Tên mặt hàng</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="tb_cthd_body">
                        <?php
                        if(isset($_POST['table_mahoadon'],$_POST['button_table_xemchitiet'])){
                            getMHToTable("Select tenmathang, cthd.soluong, gianiemyet, cthd.soluong*gianiemyet as'thanhtien'
                                        from mathang mh join chitiethoadon cthd on mh.mamathang = cthd.mamathang
                                        where mahoadon = ".$_POST['table_mahoadon']);
                        }
                        ?>
                    </tbody>
                </table>
                <div class="form-row">
                    <label id = "lb_tenmathang">Tên mặt hàng:</label>
                    <input type="text" list="products" name="ct_tenmathang" id="ip_cttenmathang">
                    <datalist id="products">
                        <?php
                            getTenMHToList();
                        ?>
                    </datalist>
                    <label id = "lb_dongia">Đơn giá:</label>
                    <input type="text" name="ct_dongia" id="ip_ctdongia" readonly>
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
                    <input type="text" name="ct_soluong" id="ip_ctsoluong">
                </div>
                <div class="btn-row">
                    <button type="button" id="btn_them_mh" onclick="them_mh()">Thêm mặt hàng</button>
                    <button type="submit" name="button_tao_hd" id="btn_tao_hd">Tạo hóa đơn</button>
                    <button type="button" onclick="an_form_cthd()">Hủy</button>
                </div>
            </form>
        </div>

        <!-- bảng hóa đơn -->
        <div class = "div_table">
            <table class = "table">
                <tr>
                    <th>Mã hóa đơn</th>
                    <th>Ngày xuất</th>
                    <th>Tổng tiền</th>
                    <th>Thao tác</th>
                </tr>
                <?php
                    getHDToTable(createSqlTimKiem());
                ?>
            </table>
        </div>
    </main>      
</body>
</html>

<script>
    function xoaForm(){
        document.getElementById("form_hd").reset();
        an_form_cthd();
    }
    function hien_form_ctdh(readonly){
        document.getElementById("div_cthd").style.display="block";
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
            document.getElementById("form_ct").reset();
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
        //reset mã hóa đơn
        document.getElementById("h_mahoadon").innerText="";
        // reset table
        const table = document.getElementById("tb_cthd");
        while (table.rows.length > 1) {
            table.deleteRow(1);
        }
        // reset form
        document.getElementById("form_ct").reset();
    }

    function an_form_cthd(){
        reset_form_cthd();

        // ẩn form
        document.getElementById("div_cthd").style.display="none";
    }

    function them_mh(){
        tenmathang = document.getElementById("ip_cttenmathang").value.trim();
        soluong = Number(document.getElementById("ip_ctsoluong").value.trim());
        dongia = Number(document.getElementById("ip_ctdongia").value.trim());

        if(!tenmathang){ 
            alert("Chưa nhập tên mặt hàng"); 
            return; 
        }
        if(isNaN(soluong) || soluong <= 0){ 
            alert("Số lượng phải > 0"); 
            return; 
        }
        if(isNaN(dongia) || dongia < 0){ 
            alert("Đơn giá không hợp lệ"); 
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
            <td><button type="button" class="btn_xoa">Xóa</button></td>

            <input type="hidden" name="mh_ten[]" value="${tenmathang}">
            <input type="hidden" name="mh_sl[]" value="${soluong}">
        `;

        // xóa dòng
        row.querySelector(".btn_xoa").addEventListener("click", () => row.remove());

        tbody.appendChild(row);

        // clear input nhập
        document.getElementById("ip_cttenmathang").value = "";
        document.getElementById("ip_ctsoluong").value = "";
        document.getElementById("ip_ctdongia").value = "";
    }
</script>
<?php
    if(isset($_POST['table_mahoadon'],$_POST['table_ngayxuat'],$_POST['table_tongtien'],$_POST['button_table_xemchitiet'])){
        $mahoadon = $_POST['table_mahoadon'];
        $ngayxuat = $_POST['table_ngayxuat'];
        $tongtien = $_POST['table_tongtien'];
        echo"<script>hien_form_ctdh(true);</script>";
    }
?>