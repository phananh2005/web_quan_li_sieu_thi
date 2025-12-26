<?php
    require_once __DIR__."/../database/ConnectDB.php";
    function getMaNhanVienToSelect(){
        global $conn;
        $result = mysqli_query($conn, "Select manhanvien from nhanvien order by manhanvien");
        while($row=mysqli_fetch_assoc($result)){
            echo "<option value='".$row["manhanvien"]."'>".$row["manhanvien"]."</option>";
        }
        mysqli_free_result($result);
    }
?>