<?php
    require_once __DIR__."/../database/ConnectDB.php";
    //đức
    function getMHToTable($sql){
        global $conn;
        $result = mysqli_query($conn, $sql);

        while($row = mysqli_fetch_assoc($result)){
            echo "<tr>";

            echo "<td>{$row['mamathang']}</td>";
            echo "<td>{$row['tenmathang']}</td>";
            echo "<td>{$row['dongiaban']}</td>";
            echo "<td>{$row['soluong']}</td>";
            echo "<td>{$row['tendanhmuc']}</td>";
            echo "<td>{$row['ghichu']}</td>";

            echo "<td>
                <form method='post'>
                    <input type='hidden' name='table_mamathang' value='{$row['mamathang']}'>
                    <input type='hidden' name='table_tenmathang' value='{$row['tenmathang']}'>
                    <input type='hidden' name='table_soluong' value='{$row['soluong']}'>
                    <input type='hidden' name='table_dongiaban' value='{$row['dongiaban']}'>
                    
                    <!-- ẨN MÃ -->
                    <input type='hidden' name='table_madanhmuc' value='{$row['madanhmuc']}'>
                    <input type='hidden' name='table_ghichu' value='{$row['ghichu']}'>

                    <button type='submit' name='button_table_chon'>Chọn</button>
                    <button type='submit' name='button_table_xoa'>Xóa</button>
                </form>
            </td>";

            echo "</tr>";
        }
    }

        function getTenDanhMucToSelect(){
        global $conn;
        $result = mysqli_query($conn, "SELECT madanhmuc, tendanhmuc FROM danhmuc");
        while($row=mysqli_fetch_assoc($result)){
            echo "<option value='{$row['madanhmuc']}'>{$row['tendanhmuc']}</option>";
        }
    }

        function writeMH($sql){
        global $conn;
        $result = mysqli_query($conn, $sql);
        return mysqli_affected_rows($conn);
    }

    //     function getIDDanhMuc($name) {
    //     global $conn;
    //     $result = mysqli_query($conn, "SELECT madanhmuc FROM danhmuc WHERE tendanhmuc='" . mysqli_real_escape_string($conn, $name) . "'");
    //     if ($row = mysqli_fetch_assoc($result)) {
    //         mysqli_free_result($result);
    //         return $row['madanhmuc'];
    //     } else {
    //         mysqli_free_result($result);
    //         return null; // không tìm thấy
    //     }
    // }

    // function getIDNCC($name) {
    //     global $conn;
    //     $result = mysqli_query($conn, "SELECT manhacungcap FROM nhacungcap WHERE tennhacungcap='" . mysqli_real_escape_string($conn, $name) . "'");
    //     if ($row = mysqli_fetch_assoc($result)) {
    //         mysqli_free_result($result);
    //         return $row['manhacungcap'];
    //     } else {
    //         mysqli_free_result($result);
    //         return null; // không tìm thấy
    //     }
    // }
?>