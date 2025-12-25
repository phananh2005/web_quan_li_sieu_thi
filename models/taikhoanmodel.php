<?php
    require_once __DIR__."/../database/ConnectDB.php";
    function checkTaiKhoan($u,$p){
        global $conn;
        $stmt = mysqli_prepare($conn, "SELECT chucvu FROM taikhoan WHERE tentaikhoan = ? 
        AND matkhau = ?");
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
    
    function getTaiKhoanToTable($sql = "Select * from taikhoan"){
        global $conn;
        $result = mysqli_query($conn, $sql);
        while($row=mysqli_fetch_assoc($result)){
            echo "<tr>";
            foreach($row as $value){
                echo "<td>$value</td>";
            }
            echo "<td>
                    <form method='post' action=''>";
            foreach($row as $feild => $value){
                echo "<input type='hidden' name='table_".$feild."' value='".$value."'>";
            }
            echo "<button type='submit' name='action' value='btn_Chon'>Chọn</button>
                  <button type='submit' name='action' value='btn_Xoa'>Xóa</button>
                  </form>
                </td>
                </tr>";
        }
        mysqli_free_result($result);
    }

    function deleteTaiKhoan($mataikhoan){
        global $conn;
        $stmt = mysqli_prepare($conn, "Delete FROM taikhoan WHERE mataikhoan = ?");
        mysqli_stmt_bind_param($stmt, "i", $mataikhoan);
        mysqli_stmt_execute($stmt);
        $row = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $row;
    }


?>