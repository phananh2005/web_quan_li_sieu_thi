<?php
header('Content-Type: text/html; charset=utf-8');

// Kết nối database MySQL
$servername = "localhost";
$username = "root";
$password = "";
$database = "sieuthi";  

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$whereClause = '';
if (!empty($search)) {
    $whereClause = "WHERE tenmathang LIKE '%" . $conn->real_escape_string($search) . "%' 
                    OR mamathang LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$sql = "SELECT * FROM mathang $whereClause ORDER BY mamathang DESC";
$result = $conn->query($sql);

$sql_count = "SELECT COUNT(*) as total FROM mathang";
$count_result = $conn->query($sql_count);
$total_items = $count_result->fetch_assoc()['total'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Mặt Hàng - Hệ Thống Quản Lý Siêu Thị</title>
    <link rel="stylesheet" href="Mathang.css">
    
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <div class="logo">
                <i class="fas fa-store"></i>
                <h1>Quản Lý Siêu Thị</h1>
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
                <a href="#" class="nav-link active">Trang Chủ</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Danh Mục</a>
                </li>
                <li class="nav-item">
                <a href="mathang.php" class="nav-link">Mặt Hàng</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Nhân Viên</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Đơn Hàng</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Báo Cáo</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Hoá Đơn</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <i class="fas fa-boxes"></i>
                <span>Quản Lý Mặt Hàng</span>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <form method="GET" action="mathang.php" class="search-box">
                <input type="text" name="search" placeholder="Tìm kiếm theo tên hoặc mã mặt hàng..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="search-btn">Tìm kiếm</button>
            </form>
            <a href="ThemMatHang.php" class="btn-add">Thêm Mặt Hàng</a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #2196F3 0%, #0D47A1 100%);">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3>Tổng số mặt hàng</h3>
                    <div class="stat-number"><?php echo $total_items; ?></div>
                </div>
            </div>
            
            <?php
            // Tính số mặt hàng sắp hết (số lượng < 10)
            $conn = new mysqli($servername, $username, $password, $database);
            $sql_low = "SELECT COUNT(*) as low_count FROM mathang WHERE soluong < 10";
            $result_low = $conn->query($sql_low);
            $low_count = $result_low->fetch_assoc()['low_count'];
            
            // Tính số mặt hàng sắp hết hạn (trong vòng 30 ngày)
            $sql_expiring = "SELECT COUNT(*) as expiring_count FROM MatHang 
                           WHERE hansudung IS NOT NULL AND hansudung <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
            $result_expiring = $conn->query($sql_expiring);
            $expiring_count = $result_expiring->fetch_assoc()['expiring_count'];
            
            $conn->close();
            ?>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #FF9800 0%, #EF6C00 100%);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <h3>Mặt hàng sắp hết</h3>
                    <div class="stat-number"><?php echo $low_count; ?></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f44336 0%, #b71c1c 100%);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>Sắp hết hạn</h3>
                    <div class="stat-number"><?php echo $expiring_count; ?></div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="products-table">
            <div class="table-header">
                <i class="fas fa-list"></i>
                <span>Danh sách mặt hàng</span>
            </div>
            
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Mã MH</th>
                            <th>Tên mặt hàng</th>
                            <th>Số lượng</th>
                            <th>Giá niêm yết</th>
                            <th>HSD</th>
                            <th>Mã NCC</th>
                            <th>Mã danh mục</th>
                            <th>Mã đơn nhập</th>
                            <th>Ghi chú</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><span class="product-code"><?php echo $row['mamathang']; ?></span></td>
                            <td>
                                <div class="product-name"><?php echo htmlspecialchars($row['tenmathang']); ?></div>
                            </td>
                            <td>
                                <span class="quantity <?php echo ($row['soluong'] < 10) ? 'low' : ''; ?>">
                                    <?php echo $row['soluong']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="price">
                                    <?php echo number_format($row['gianemyet'], 0, ',', '.'); ?> VNĐ
                                </span>
                            </td>
                            <td>
                                <?php 
                                if ($row['hansudung']) {
                                    $hansudung = new DateTime($row['hansudung']);
                                    $now = new DateTime();
                                    $diff = $now->diff($hansudung)->days;
                                    
                                    if ($hansudung < $now) {
                                        echo '<span class="expired">' . date('d/m/Y', strtotime($row['hansudung'])) . '</span>';
                                    } elseif ($diff <= 30) {
                                        echo '<span class="expiring">' . date('d/m/Y', strtotime($row['hansudung'])) . '</span>';
                                    } else {
                                        echo date('d/m/Y', strtotime($row['hansudung']));
                                    }
                                } else {
                                    echo 'Không có';
                                }
                                ?>
                            </td>
                            <td><?php echo $row['manhacungcap']; ?></td>
                            <td><?php echo $row['madanhmuc']; ?></td>
                            <td><?php echo $row['madonnhaphang']; ?></td>
                            <td><?php echo htmlspecialchars(substr($row['ghichu'], 0, 30)) . (strlen($row['ghichu']) > 30 ? '...' : ''); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="viewProduct(<?php echo $row['mamathang']; ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-action btn-edit" onclick="editProduct(<?php echo $row['mamathang']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-action btn-delete" onclick="deleteProduct(<?php echo $row['mamathang']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="padding: 40px; text-align: center; color: #666;">
                    <i class="fas fa-box-open" style="font-size: 4rem; color: #ddd; margin-bottom: 20px;"></i>
                    <h3>Không có mặt hàng nào</h3>
                    <p>Bấm vào nút "Thêm Mặt Hàng" để thêm mặt hàng mới</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <a href="#" class="active">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#">4</a>
            <a href="#">5</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container footer-container">
            <div class="logo">
                <i class="fas fa-store"></i>
                <h2>Quản Lý Siêu Thị</h2>
            </div>
            <div class="copyright">
                &copy; 2024 Hệ thống Quản lý Siêu thị. Tất cả các quyền được bảo lưu.
            </div>
        </div>
    </footer>

    <script>
        // Xử lý nút đăng nhập/đăng xuất
        document.getElementById('logoutBtn').addEventListener('click', function() {
            if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                window.location.href = 'logout.php';
            }
        });
        
        // Hàm xem chi tiết mặt hàng
        function viewProduct(id) {
            window.location.href = 'chitiet_mathang.php?id=' + id;
        }
        
        // Hàm sửa mặt hàng
        function editProduct(id) {
            window.location.href = 'sua_mathang.php?id=' + id;
        }
        
        // Hàm xóa mặt hàng
        function deleteProduct(id) {
            if (confirm('Bạn có chắc chắn muốn xóa mặt hàng này?')) {
                // Gửi yêu cầu xóa qua AJAX hoặc chuyển trang
                window.location.href = 'xoa_mathang.php?id=' + id;
            }
        }
        
        // Tự động đánh dấu menu active
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>