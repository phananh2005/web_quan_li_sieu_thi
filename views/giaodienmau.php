<?php
// File: index.php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Hệ Thống Quản Lý Siêu Thị</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        .btn-login {
            background-color: #ff9800;
            color: white;
        }
        
        .btn-login:hover {
            background-color: #e68900;
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
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }
        
        .card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
        }
        
        .card-content {
            color: #666;
            font-size: 1rem;
            margin-bottom: 20px;
        }
        
        .card-stat {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1a6dcc;
            margin: 10px 0;
        }
        
        /* Color schemes for cards */
        .card-categories .card-icon {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
        }
        
        .card-employees .card-icon {
            background: linear-gradient(135deg, #2196F3 0%, #0D47A1 100%);
        }
        
        .card-orders .card-icon {
            background: linear-gradient(135deg, #FF9800 0%, #EF6C00 100%);
        }
        
        .card-revenue .card-icon {
            background: linear-gradient(135deg, #9C27B0 0%, #6A1B9A 100%);
        }
        
        /* Recent Orders */
        .recent-orders {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title i {
            color: #1a6dcc;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            text-align: left;
            padding: 15px;
            border-bottom: 2px solid #eee;
            color: #555;
            font-weight: 700;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        tr:hover {
            background-color: #f9f9f9;
        }
        
        .status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-pending {
            background-color: #FFF3E0;
            color: #EF6C00;
        }
        
        .status-completed {
            background-color: #E8F5E9;
            color: #2E7D32;
        }
        
        .status-processing {
            background-color: #E3F2FD;
            color: #1565C0;
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
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .nav-menu {
                flex-direction: column;
            }
            
            .header-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .user-info {
                flex-direction: column;
                gap: 10px;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <div class="logo">
                <i class="fas fa-store"></i>
                <h1>Hệ Thống Quản Lý Siêu Thị</h1>
            </div>
            <div class="user-info">
                <span>Xin chào, <strong>Nguyễn Văn A</strong></span>
                <button class="btn btn-logout" id="logoutBtn">
                    <i class="fas fa-sign-out-alt"></i> Đăng Xuất
                </button>
                <button class="btn btn-login" id="loginBtn" style="display: none;">
                    <i class="fas fa-sign-in-alt"></i> Đăng Nhập
                </button>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav>
        <div class="container">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#" class="nav-link active">
                        <i class="fas fa-home"></i> Trang Chủ
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-list"></i> Danh Mục
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-users"></i> Nhân Viên
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-shopping-cart"></i> Đơn Hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-bar"></i> Báo Cáo
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog"></i> Cài Đặt
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- Dashboard Cards -->
        <div class="dashboard">
            <div class="card card-categories">
                <div class="card-header">
                    <h3 class="card-title">Danh Mục Sản Phẩm</h3>
                    <div class="card-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
                <p class="card-content">Quản lý các loại sản phẩm trong siêu thị</p>
                <div class="card-stat">24</div>
                <p class="card-content">danh mục hiện có</p>
            </div>
            
            <div class="card card-employees">
                <div class="card-header">
                    <h3 class="card-title">Nhân Viên</h3>
                    <div class="card-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
                <p class="card-content">Quản lý thông tin nhân viên</p>
                <div class="card-stat">48</div>
                <p class="card-content">nhân viên đang làm việc</p>
            </div>
            
            <div class="card card-orders">
                <div class="card-header">
                    <h3 class="card-title">Đơn Hàng</h3>
                    <div class="card-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
                <p class="card-content">Quản lý đơn hàng trong ngày</p>
                <div class="card-stat">127</div>
                <p class="card-content">đơn hàng hôm nay</p>
            </div>
            
            <div class="card card-revenue">
                <div class="card-header">
                    <h3 class="card-title">Doanh Thu</h3>
                    <div class="card-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <p class="card-content">Doanh thu hôm nay</p>
                <div class="card-stat">85.2M</div>
                <p class="card-content">đồng</p>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="recent-orders">
            <h2 class="section-title">
                <i class="fas fa-history"></i> Đơn Hàng Gần Đây
            </h2>
            <table>
                <thead>
                    <tr>
                        <th>Mã Đơn Hàng</th>
                        <th>Khách Hàng</th>
                        <th>Ngày Đặt</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#DH-2023-00125</td>
                        <td>Trần Thị B</td>
                        <td>15/10/2023</td>
                        <td>1,250,000 đ</td>
                        <td><span class="status status-completed">Đã hoàn thành</span></td>
                    </tr>
                    <tr>
                        <td>#DH-2023-00124</td>
                        <td>Lê Văn C</td>
                        <td>15/10/2023</td>
                        <td>2,840,000 đ</td>
                        <td><span class="status status-processing">Đang xử lý</span></td>
                    </tr>
                    <tr>
                        <td>#DH-2023-00123</td>
                        <td>Phạm Thị D</td>
                        <td>14/10/2023</td>
                        <td>850,000 đ</td>
                        <td><span class="status status-completed">Đã hoàn thành</span></td>
                    </tr>
                    <tr>
                        <td>#DH-2023-00122</td>
                        <td>Nguyễn Văn E</td>
                        <td>14/10/2023</td>
                        <td>3,150,000 đ</td>
                        <td><span class="status status-pending">Chờ xác nhận</span></td>
                    </tr>
                    <tr>
                        <td>#DH-2023-00121</td>
                        <td>Hoàng Thị F</td>
                        <td>14/10/2023</td>
                        <td>1,750,000 đ</td>
                        <td><span class="status status-completed">Đã hoàn thành</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container footer-container">
            <div class="logo">
                <i class="fas fa-store"></i>
                <h2>Hệ Thống Quản Lý Siêu Thị</h2>
            </div>
        </div>
    </footer>

    <script>
        // Xử lý nút đăng nhập/đăng xuất (chỉ giao diện, không có chức năng thực)
        document.getElementById('logoutBtn').addEventListener('click', function() {
            // Giả lập đăng xuất - chỉ thay đổi giao diện
            this.style.display = 'none';
            document.getElementById('loginBtn').style.display = 'inline-flex';
            document.querySelector('.user-info span').innerHTML = 'Vui lòng đăng nhập để tiếp tục';
        });
        
        document.getElementById('loginBtn').addEventListener('click', function() {
            // Giả lập đăng nhập - chỉ thay đổi giao diện
            this.style.display = 'none';
            document.getElementById('logoutBtn').style.display = 'inline-flex';
            document.querySelector('.user-info span').innerHTML = 'Xin chào, <strong>Nguyễn Văn A</strong>';
        });
        
        // Xử lý menu điều hướng
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                // Xóa lớp active khỏi tất cả các liên kết
                document.querySelectorAll('.nav-link').forEach(item => {
                    item.classList.remove('active');
                });
                // Thêm lớp active vào liên kết được nhấp
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>