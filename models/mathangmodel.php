<?php
    require_once __DIR__."/../database/ConnectDB.php";

    function getMHToTable($sql){
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
                  <button type='submit' name='button_table_xoa' value='btn_xoa'>Xóa</button>
                  </form>
                </td>
                </tr>";
        }
        mysqli_free_result($result);
    }

function writeMH($sql){
    global $conn;
    $result = mysqli_query($conn, $sql);
    return mysqli_affected_rows($conn);
    }
        function getTenMHToList(){
        global $conn;
        $result = mysqli_query($conn, "Select tenmathang, gianiemyet from mathang");
        while($row=mysqli_fetch_assoc($result)){
            echo "<option value='".$row['tenmathang']."' data-gia='".$row['gianiemyet']."'></option>";
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
?>