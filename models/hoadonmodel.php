<?php
    require_once __DIR__."/../database/ConnectDB.php";

    function getHDToTable($sql){
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
            echo "<button type='submit' name='button_table_xemchitiet' value='btn_xemchitiet'>Xem chi tiết</button>
                  <button type='submit' name='button_table_xoa' value='btn_xoa'>Xóa</button>
                  </form>
                </td>
                </tr>";
        }
        mysqli_free_result($result);
    }

    function createHD(){
        global $conn;
        $result = mysqli_query($conn, "Insert hoadon (ngayxuat) values (CURDATE())");
        if(!$result){
            return null; 
        }
        return mysqli_insert_id($conn);
    }

    function write($sql){
        global $conn;
        $result = mysqli_query($conn, $sql);
        return mysqli_affected_rows($conn);
    }
?>