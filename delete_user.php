<?php

session_start();
include("database/db.php");






$id=$_GET['pro_id'];


// Delete data in mysql from row that has this id 
$sql="DELETE FROM events WHERE id='$id'";
$result=$db->query($sql);

// if successfully deleted
if($result){
    
    ?>
    <div align="center"><h2>Delete Page</h2></div><br/>
    <table width="400" border="2" bgcolor="#cccccc" align="center"><td align="center" >
        



            

<?php
    
    
echo "Deleted Successfully";
echo "<BR>";
echo "<a href='admin/delete_product.php'>Cotinue Deleting</a>";
}

else {
echo "ERROR";
}
?> 
        
        
    </table>

<?php
// close connection 
$db->close();
?>