<?php
    require_once __DIR__."/../database/ConnectDB.php";
    
    function getTaiKhoanToTable($sql){
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
            echo "<button type='submit' name='button_table_chon' value='btn_chon'>Chọn</button>
                  </form>
                </td>
                </tr>";
        }
        mysqli_free_result($result);
    }

    function writeTaiKhoan($sql){
        global $conn;
        $result = mysqli_query($conn, $sql);
        return mysqli_affected_rows($conn);
    }

    function getMaNhanVienToSelect(){
        global $conn;
        $result = mysqli_query($conn, "Select manhanvien from nhanvien order by manhanvien");
        while($row=mysqli_fetch_assoc($result)){
            echo "<option value='".$row["manhanvien"]."'>".$row["manhanvien"]."</option>";
        }
        mysqli_free_result($result);
    }
?>