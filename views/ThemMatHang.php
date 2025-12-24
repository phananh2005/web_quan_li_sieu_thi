<?php
// File: them_mathang.php
header('Content-Type: text/html; charset=utf-8');

// Kết nối database
$servername = "localhost";
$username = "root";
$password = "";
$database = "sieuthi";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql_ncc = "SELECT manhacungcap, tennhacungcap FROM nhacungcap ORDER BY tennhacungcap";
$result_ncc = $conn->query($sql_ncc);

$sql_dm = "SELECT madanhmuc, tendanhmuc FROM DanhMuc ORDER BY tendanhmuc";
$result_dm = $conn->query($sql_dm);

// Xử lý thêm mặt hàng
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenmathang = $_POST['tenmathang'];
    $madanhmuc = $_POST['madanhmuc'];
    $manhacungcap = $_POST['manhacungcap'];
    $giaNiemYet = $_POST['giaNiemYet'];
    $soLuong = $_POST['soLuong'];
    $hansudung = !empty($_POST['hansudung']) ? $_POST['hansudung'] : NULL;
    $maDonNhapHang = $_POST['maDonNhapHang'];
    $ghiChu = $_POST['ghiChu'];
    
    $sql = "INSERT INTO mathang (tenmathang, madanhmuc, manhacungcap, GiaNemYet, SoLuong, hansudung, MaDonNhapHang, GhiChu) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siidiiss", $tenmathang, $madanhmuc, $manhacungcap, $giaNiemYet, $soLuong, $hansudung, $maDonNhapHang, $ghiChu);
    
    if ($stmt->execute()) {
        $message = '<div class="alert success">Thêm mặt hàng thành công!</div>';
    } else {
        $message = '<div class="alert error">Lỗi khi thêm mặt hàng: ' . $conn->error . '</div>';
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Mặt Hàng - Quản Lý Siêu Thị</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header */
        header {
            background: linear-gradient(135deg, #1a6dcc 0%, #0d4d9c 100%);
            color: white;
            padding: 15px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo i {
            font-size: 2.2rem;
        }
        
        .logo h1 {
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-info span {
            font-weight: 600;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-logout {
            background-color: #f44336;
            color: white;
        }
        
        .btn-logout:hover {
            background-color: #d32f2f;
        }
        
        /* Navigation */
        nav {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 0;
            margin-bottom: 30px;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
        }
        
        .nav-item {
            flex: 1;
            text-align: center;
        }
        
        .nav-link {
            display: block;
            padding: 18px 0;
            text-decoration: none;
            color: #444;
            font-weight: 600;
            font-size: 1.1rem;
            border-bottom: 4px solid transparent;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: #1a6dcc;
            background-color: #f8f9fa;
            border-bottom: 4px solid #ff9800;
        }
        
        .nav-link.active {
            color: #1a6dcc;
            border-bottom: 4px solid #1a6dcc;
        }
        
        .nav-link i {
            margin-right: 8px;
        }
        
        /* Main Content */
        .form-container {
            max-width: 800px;
            margin: 0 auto 50px;
        }
        
        .form-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .form-header i {
            font-size: 2rem;
            color: #1a6dcc;
        }
        
        .form-header h2 {
            font-size: 1.8rem;
            color: #333;
        }
        
        .form-card {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Alert Message */
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            font-weight: 600;
        }
        
        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #1a6dcc;
            box-shadow: 0 0 0 3px rgba(26, 109, 204, 0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        select.form-control {
            cursor: pointer;
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .required {
            color: #f44336;
        }
        
        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid #eee;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            color: white;
            border: none;
            padding: 14px 35px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            background: linear-gradient(135deg, #45a049 0%, #1B5E20 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-cancel {
            background: #f5f5f5;
            color: #666;
            border: 1px solid #ddd;
            padding: 14px 35px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover {
            background: #eee;
            color: #333;
            border-color: #ccc;
        }
        
        /* Footer */
        footer {
            background-color: #1a252f;
            color: #b0b7c3;
            padding: 30px 0;
            text-align: center;
            margin-top: 50px;
        }
        
        .footer-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }
        
        .copyright {
            font-size: 0.9rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .nav-menu {
                flex-direction: column;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-card {
                padding: 25px;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn-submit, .btn-cancel {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <div class="logo">
                <h1>Quản Lý Siêu Thị</h1>
            </div>
            <div class="user-info">
                <span>Xin chào, <strong>Nguyễn Văn A</strong></span>
                <button class="btn btn-logout" id="logoutBtn"> Đăng Xuất</button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2>Thêm Mặt Hàng Mới</h2>
            </div>
            
            <?php echo $message; ?>
            
            <div class="form-card">
                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tenmathang" class="form-label">
                                Tên mặt hàng <span class="required">*</span>
                            </label>
                            <input type="text" id="tenmathang" name="tenmathang" class="form-control" 
                                   placeholder="Nhập tên mặt hàng" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="maDonNhapHang" class="form-label">
                                Mã đơn nhập hàng
                            </label>
                            <input type="text" id="maDonNhapHang" name="maDonNhapHang" 
                                   class="form-control" placeholder="Nhập mã đơn nhập">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="madanhmuc" class="form-label">Danh mục</label>
                            <select id="madanhmuc" name="madanhmuc" class="form-control" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php 
                                if ($result_dm->num_rows > 0) {
                                    while($row = $result_dm->fetch_assoc()) {
                                        echo '<option value="' . $row['madanhmuc'] . '">' . 
                                             htmlspecialchars($row['tendanhmuc']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="manhacungcap" class="form-label">Nhà cung cấp</label>
                            <select id="manhacungcap" name="manhacungcap" class="form-control" required>
                                <option value="">-- Chọn nhà cung cấp --</option>
                                <?php 
                                if ($result_ncc->num_rows > 0) {
                                    while($row = $result_ncc->fetch_assoc()) {
                                        echo '<option value="' . $row['manhacungcap'] . '">' . 
                                             htmlspecialchars($row['tennhacungcap']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="giaNiemYet" class="form-label">
                                Giá niêm yết (VNĐ) <span class="required">*</span>
                            </label>
                            <input type="number" id="giaNiemYet" name="giaNiemYet" 
                                   class="form-control" placeholder="Nhập giá" min="0" step="1000" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="soLuong" class="form-label">
                                Số lượng <span class="required">*</span>
                            </label>
                            <input type="number" id="soLuong" name="soLuong" 
                                   class="form-control" placeholder="Nhập số lượng" min="0" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="hansudung" class="form-label">Hạn sử dụng</label>
                            <input type="date" id="hansudung" name="hansudung" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="ghiChu" class="form-label">Ghi chú</label>
                        <textarea id="ghiChu" name="ghiChu" class="form-control" 
                                  placeholder="Nhập ghi chú về mặt hàng (nếu có)"></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <a href="mathang.php" class="btn-cancel">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Lưu mặt hàng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container footer-container">
            <div class="logo">
                <i class="fas fa-store"></i>
                <h2>Quản Lý Siêu Thị</h2>
            </div>
        </div>
    </footer>

    <script>
        // Xử lý nút đăng xuất
        document.getElementById('logoutBtn').addEventListener('click', function() {
            if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                window.location.href = 'logout.php';
            }
        });
        
        // Tự động đánh dấu menu active
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === 'mathang.php') {
                    link.classList.add('active');
                }
            });
        });
        
        // Tự động đặt ngày hôm nay + 1 năm cho hansudung
        document.addEventListener('DOMContentLoaded', function() {
            const hansudungInput = document.getElementById('hansudung');
            if (hansudungInput) {
                const today = new Date();
                const nextYear = new Date(today);
                nextYear.setFullYear(today.getFullYear() + 1);
                
                const todayStr = today.toISOString().split('T')[0];
                const nextYearStr = nextYear.toISOString().split('T')[0];
                
                // Đặt giá trị mặc định là 1 năm sau
                hansudungInput.value = nextYearStr;
                hansudungInput.min = todayStr;
            }
            
            // Format giá tiền khi nhập
            const giaInput = document.getElementById('giaNemYet');
            giaInput.addEventListener('blur', function() {
                if (this.value) {
                    this.value = parseInt(this.value).toLocaleString('vi-VN');
                }
            });
            
            giaInput.addEventListener('focus', function() {
                if (this.value) {
                    this.value = this.value.replace(/\./g, '');
                }
            });
        });
    </script>
</body>
</html>