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

    function getMaMH($tenmathang){
        global $conn;
        $result = mysqli_query($conn, "Select mamathang from mathang where tenmathang='".$tenmathang."'");
        if($row=mysqli_fetch_assoc($result)){
            return $row['mamathang'];
        }
        else return null;
    }

    function getDonGiaBan($mamathang){
        global $conn;
        $result = mysqli_query($conn, "Select dongiaban from mathang where mamathang='".$mamathang."'");
        if($row=mysqli_fetch_assoc($result)){
            return $row['dongiaban'];
        }
        else return null;
    }

    function createHD($u){
        global $conn;
        $result = mysqli_query($conn, "Insert hoadon (ngayxuat, mataikhoan) values (CURDATE(), (SELECT mataikhoan FROM taikhoan WHERE tentaikhoan = '$u'))");
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

    function getMHToTableHD($sql){
        global $conn;
        $result = mysqli_query($conn, $sql);
        while($row=mysqli_fetch_assoc($result)){
            echo "<tr>";
            foreach($row as $value){
                echo "<td>$value</td>";
            }
            echo"</tr>";
        }
        mysqli_free_result($result);
    }

    function getTenMHToList(){
        global $conn;
        $result = mysqli_query($conn, "Select tenmathang, dongiaban, soluong from mathang where soluong > 0");
        while($row=mysqli_fetch_assoc($result)){
            echo "<option value='".$row['tenmathang']."' 
            label='".$row['tenmathang']." - ".number_format($row['dongiaban'])." VNĐ - Kho: ".$row['soluong']."' 
            data-gia='".$row['dongiaban']."' 
            data-soluong='".$row['soluong']."'></option>";
        }
        mysqli_free_result($result);
    }
?>