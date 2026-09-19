<?php
include "config/db.php";

$pin = $_POST['pincode'];

$sql = "SELECT * FROM pincode WHERE pincode='$pin' AND Dstatus='Available'";
$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0){
    $row = mysqli_fetch_assoc($result);

    echo "<span style='color:green'>
    Delivery Available in ".$row['city'].", ".$row['state']."
    </span>";

}else{
    echo "<span style='color:red'>
    Sorry! Delivery is not available.
    </span>";
}
?>