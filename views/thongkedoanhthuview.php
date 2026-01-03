<?php
    session_start();
    $u = $_SESSION["taikhoan"];
    $role = $_SESSION["chucvu"];

    require_once __DIR__."/../models/thongkedoanhthumodel.php";

    if(isset($_POST["button_form_timkiem"])){
        if(empty($_POST["form_nam"])){
            echo "<script>
                alert('Vui lòng nhập đầy đủ thông tin tìm kiếm!');
                window.location.href = 'spbanchayview.php';
                </script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/trangchu.css" >
    <link rel="stylesheet" href="../assets/thongkedoanhthu.css" >
    <!-- Link Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                echo '<a href="hoadonview.php">Hóa đơn</a>';
                echo '<a href="spbanchayview.php">Sản phẩm bán chạy</a>';
                if ($role == "Bán hàng") echo '<a href="thongkedoanhthuview.php" class = "active">Thống kê doanh thu</a>';
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
    <main>
      <div class="div_form_timkiem">
          <form method="post" id="form_timkiem">
              <label>Năm:</label>
              <input type="number" name="form_nam" min="2000" max="2100">
              <button type="submit" name="button_form_timkiem" value="btn_timkiem">Tìm kiếm</button>
              <button type="button" onclick="xoaFormTimKiem()">Hủy</button>
          </form>
      </div>
      <div class="chart-container">
        <h2 id = "tieu_de"></h2>
        <canvas id="revenueChart"></canvas>
      </div>

  <?php
    if(isset($_POST["button_form_timkiem"])){
        $nam = $_POST["form_nam"];
    } else {
        $nam = date("Y");
    }
    $doanhThu = getDoanhThuTheoThang($nam);
    echo "<script>
            document.getElementById('tieu_de').innerText = 'Biểu đồ Doanh Thu Năm ' + $nam;
          </script>";
  ?>    

  <script>
    const ctx = document.getElementById("revenueChart").getContext("2d");
    
    const doanhThu = <?php echo json_encode($doanhThu); ?>;
    
    const revenueChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["T1", "T2", "T3", "T4", "T5", "T6", "T7", "T8", "T9", "T10", "T11", "T12"],
        datasets: [
          {
            label: "Doanh thu (triệu VNĐ)",
            data: doanhThu.map(value => value / 1000000), // Chuyển đổi sang triệu VNĐ
            backgroundColor: "rgba(54, 162, 235, 0.6)",
            borderColor: "rgba(54, 162, 235, 1)",
            borderWidth: 1
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: "top"
          }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
  </main>
</body>
</html>