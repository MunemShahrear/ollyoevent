<?php
session_start();
$host="localhost"; 
$username="root"; 
$password=""; 
$db_name="affirmative"; 
$tbl_name="usertable"; 

$pro_id=$_GET['pro_id'];
$conn = new mysqli($host, $username, $password, $db_name);

$sql = "UPDATE `products` SET `cat_id`=[$pro_cat],`title`=[$pro_title],`description`=[$pro_desc],`price`=[$pro_price],`img1`=[$pro_image1],`img2`=[$pro_image2],`img3`=[$pro_image3],`date`=now WHERE product_id=$pro_id";


if ($conn->query($sql) === TRUE) {
    
   header("location:admin/update_page.php");

    ?>
    
 <script>
window.alert("Data Updeted successfully");
</script>
	?>
<?php
} else {
    
      header("location:admin/update_page.php");

    ?>
    
 <script>
window.alert("Try again");
</script>
	?>
<?php
}

$conn->close();









?>