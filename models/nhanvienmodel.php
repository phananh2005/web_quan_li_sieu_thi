<?php
    require_once __DIR__."/../database/ConnectDB.php";

    function getMaNhanVienToSelect(){
        global $conn;
        $result = mysqli_query($conn, "Select manhanvien from nhanvien order by manhanvien");
        while($row=mysqli_fetch_assoc($result)){
            echo "<option value='"
                .htmlspecialchars($row["manhanvien"], ENT_QUOTES, 'UTF-8').
                "'>".htmlspecialchars($row["manhanvien"], ENT_QUOTES, 'UTF-8')."</option>";
        }
        mysqli_free_result($result);
    }

    function getTrangThaiToSelect(){
        global $conn;
        $result = mysqli_query($conn, "Select trangthai from nhanvien order by trangthai");
        while($row=mysqli_fetch_assoc($result)){
            echo "<option value='"
                .htmlspecialchars($row["trangthai"], ENT_QUOTES, 'UTF-8').
                "'>".htmlspecialchars($row["trangthai"], ENT_QUOTES, 'UTF-8')."</option>";
        }
        mysqli_free_result($result);
    }

    function getNhanVienToTable($sql){
        global $conn;
        $result = mysqli_query($conn, $sql);

        if($result === false){
            die("LỖI SQL: " . mysqli_error($conn));
        }

        while($row = mysqli_fetch_assoc($result)){
            echo "<tr>";
            foreach($row as $value){
                echo "<td>".htmlspecialchars($value, ENT_QUOTES, 'UTF-8')."</td>";
            }
            echo "<td>
                    <form method='post' action=''>";
            foreach($row as $field => $value){
                echo "<input type='hidden' name='table_".$field."' value='"
                    .htmlspecialchars($value, ENT_QUOTES, 'UTF-8')."'>";
            }
            echo "<button type='submit' name='button_table_chon' value='btn_chon'>Chọn</button>
                
                </form>
                </td>
                </tr>";
        }
        mysqli_free_result($result);
    }

    function writeNhanVien($sql){
        global $conn;
        $result = mysqli_query($conn, $sql);
        return mysqli_affected_rows($conn);
    }

    function getOneRow($sql){
        global $conn; // hoặc biến kết nối bạn đang dùng
        $result = mysqli_query($conn, $sql);
        if($result && mysqli_num_rows($result) > 0){
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

?>