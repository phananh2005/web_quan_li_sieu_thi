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
            echo "<button type='submit' name='button_table_chon' value='btn_chon'>Chọn</button>
                  <button type='submit' name='button_table_xoa' value='btn_xoa'>Xóa</button>
                  <button type='submit' name='button_table_xem' value='btn_xoa'>Xem Chi Tiết</button>
                  </form>
                </td>
                </tr>";
        }
        mysqli_free_result($result);
    }

    function getDetailToTable($sql){
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
            echo "</form>";
        }
        mysqli_free_result($result);
    }

function writeDNH($sql){
    global $conn;
    $result = mysqli_query($conn, $sql);
    return mysqli_affected_rows($conn);
    }
?>