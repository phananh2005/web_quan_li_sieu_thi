<?php
    require_once __DIR__."/../database/ConnectDB.php";
    function checkTaiKhoan($u,$p){
        global $conn;
        $stmt = mysqli_prepare($conn, "SELECT role FROM taikhoan WHERE tentaikhoan = ? 
        AND matkhau = ?");
        mysqli_stmt_bind_param($stmt, "ss", $u, $p);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result)>0){
            $row=mysqli_fetch_assoc($result);
            return $row["role"];
        }
        else return 0;
    }
?>