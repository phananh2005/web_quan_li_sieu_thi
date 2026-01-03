<?php
    require_once __DIR__."/../database/ConnectDB.php";
            function getDoanhThuTheoThang($nam){
        global $conn;
        $sql = "SELECT MONTH(ngayxuat) AS thang, SUM(soluong*dongiaban) AS doanhthu
                FROM hoadon hd join chitiethoadon cthd on hd.mahoadon = cthd.mahoadon
                WHERE YEAR(ngayxuat) = $nam
                GROUP BY MONTH(ngayxuat)";
        $result = mysqli_query($conn, $sql);
        // mảng 12 tháng mặc định doanh thu = 0
        $doanhThu = array_fill(1, 12, 0);

        while($row = mysqli_fetch_assoc($result)){
            $thang = (int)$row['thang'];
            $doanhThu[$thang] = (float)$row['doanhthu'];
        }

        // trả về mảng dạng [T1..T12] cho Chart.js
        return array_values($doanhThu);
    }
        function getChiPhiTheoThang($nam){
        global $conn;
        $sql = "SELECT MONTH(ngaynhaphang) AS thang, SUM(soluong*dongianhap) AS chiphi
                FROM donnhaphang dnh join chitietdonnhaphang ctdnh on dnh.madonnhaphang = ctdnh.madonnhaphang
                WHERE YEAR(ngaynhaphang) = $nam
                GROUP BY MONTH(ngaynhaphang)";
        $result = mysqli_query($conn, $sql);
        // mảng 12 tháng mặc định chi phí = 0
        $chiPhi = array_fill(1, 12, 0);

        while($row = mysqli_fetch_assoc($result)){
            $thang = (int)$row['thang'];
            $chiPhi[$thang] = (float)$row['chiphi'];
        }

        // trả về mảng dạng [T1..T12] cho Chart.js
        return array_values($chiPhi);
    }
?>