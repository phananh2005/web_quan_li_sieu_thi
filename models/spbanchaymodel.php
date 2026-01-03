<?php
    require_once __DIR__."/../database/ConnectDB.php";

        function getTopMatHangBanChayToTable($thang, $nam){
        global $conn;
        $sql = "SELECT mh.mamathang, mh.tenmathang, dm.tendanhmuc, SUM(cthd.soluong) AS soluongban
                FROM mathang mh
                JOIN danhmuc dm ON mh.madanhmuc = dm.madanhmuc
                JOIN chitiethoadon cthd ON mh.mamathang = cthd.mamathang
                JOIN hoadon hd ON cthd.mahoadon = hd.mahoadon
                WHERE MONTH(hd.ngayxuat) = $thang AND YEAR(hd.ngayxuat) = $nam
                GROUP BY mh.mamathang, mh.tenmathang, dm.tendanhmuc
                ORDER BY soluongban DESC
                LIMIT 10";
        $result = mysqli_query($conn, $sql);
        while($row=mysqli_fetch_assoc($result)){
            echo "<tr>";
            foreach($row as $value){
                echo "<td>$value</td>";
            }
            echo"</tr>";
        }
    }
?>