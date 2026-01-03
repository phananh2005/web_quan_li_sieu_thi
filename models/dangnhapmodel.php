<?php
    require_once __DIR__."/../database/ConnectDB.php";
    function checkTaiKhoan($u,$p){
        global $conn;
        $stmt = mysqli_prepare($conn, "SELECT chucvu FROM taikhoan WHERE tentaikhoan COLLATE utf8mb4_bin = ? 
        AND matkhau COLLATE utf8mb4_bin = ?");
        mysqli_stmt_bind_param($stmt, "ss", $u, $p);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt); //đóng statement
        if(mysqli_num_rows($result)>0){
            $row=mysqli_fetch_assoc($result);   
            return $row["chucvu"];
        }
        else return 0;
    }
?>