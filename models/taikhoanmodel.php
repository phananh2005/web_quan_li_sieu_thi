<?php
    require_once __DIR__."/../database/ConnectDB.php";
    function checkTaiKhoan($u,$p){
        global $conn;
        $stmt = mysqli_prepare($conn, "SELECT chucvu FROM taikhoan WHERE tentaikhoan = ? 
        AND matkhau = ?");
        mysqli_stmt_bind_param($stmt, "ss", $u, $p);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result)>0){
            $row=mysqli_fetch_assoc($result);   
            return $row["chucvu"];
        }
        else return 0;
    }
    function getAllTaiKhoan(){
        global $conn;
        $result = mysqli_query($conn, "Select * from taikhoan");
        while($row=mysqli_fetch_assoc($result)){
            echo "<tr>";
            foreach($row as $value){
                echo "<td>$value</td>";
            }
            echo "<td>
                    <form method='get' action='taikhoanview.php'>";
            foreach($row as $feild => $value){
                echo "<input type='hidden' name='".$feild."' value='".$value."'>";
            }
            echo "<button type='submit' class = 'btn_chon'>Chọn</button>
                    </form>
                </td>
                </tr>";
        }
    }
?>