<?php
    require_once __DIR__."/../database/ConnectDB.php";

    function getDNHToTable($sql){
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

    function getMHToTableDNH($sql){
        global $conn;
        $result = mysqli_query($conn, $sql);
        while($row=mysqli_fetch_assoc($result)){
            echo "<tr>";
            foreach($row as $value){
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        mysqli_free_result($result);
    }

    function createDNH($manhacungcap, $u){
        global $conn;
        $sql = "INSERT INTO donnhaphang (manhacungcap, mataikhoan, ngaynhaphang) 
                VALUES (".$manhacungcap.",(SELECT mataikhoan FROM taikhoan WHERE tentaikhoan = '".$u."'),CURDATE())";
        $result = mysqli_query($conn, $sql);
        if(!$result){
            return null; 
        }
        return mysqli_insert_id($conn);
    }

    function getMaMH($tenmathang){
        global $conn;
        
        $tenmathang = mysqli_real_escape_string($conn, $tenmathang);
        $sql = "SELECT mamathang FROM mathang WHERE tenmathang = '$tenmathang' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        
        if($row = mysqli_fetch_assoc($result)){
            return $row['mamathang'];
        }
        return 0;
    }


    function write($sql){
        global $conn;
        $result = mysqli_query($conn, $sql);
        return mysqli_affected_rows($conn);
    }

    function getNhaCungCapToSelect(){
        global $conn;
        $rs = mysqli_query($conn,"SELECT manhacungcap, tennhacungcap FROM nhacungcap");
        while($r = mysqli_fetch_assoc($rs)){
            echo "<option value='{$r['manhacungcap']}'>{$r['tennhacungcap']}</option>";
        }
    }

    function getTenMHToList(){
        global $conn;
        $result = mysqli_query($conn, "Select tenmathang from mathang");
        while($row=mysqli_fetch_assoc($result)){
            echo "<option value='{$row['tenmathang']}'>{$row['tenmathang']}</option>";
        }
        mysqli_free_result($result);
    }
?>