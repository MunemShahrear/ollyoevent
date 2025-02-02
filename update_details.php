<?php
include("database/db.php");
include('function/functions.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Affirmative</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="style/style.css"> <!-- Your custom style sheet -->
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="row mt-3">
        <div class="col">
		<a href="admin/update_page.php" class="float-right mt-3 mr-3"><button class="btn btn-warning"><i class="fas fa-arrow-left"></i> Back</button></a>

        </div>
    </div>
    <!-- Content -->
    <div class="row mt-3">
        <div class="col">
            <div id="headline">Update Products</div>
            <div id="headline2"></div>
            <div id="product_page">
                <div id="products_box">
                    <?php
                    if (isset($_GET['pro_id'])) {
                        $prod_id = $_GET['pro_id'];
                        $get_products = "select * from events where id='$prod_id'";
                        $run_products = mysqli_query($db, $get_products);
                        while ($row_products = mysqli_fetch_array($run_products)) {

                            $pro_id = $row_products['id'];
                            $pro_title = $row_products['title'];
                            $pro_cat = $row_products['cat_id'];
                            $pro_desc = $row_products['description'];
                            $pro_price = $row_products['price'];
                            $pro_image1 = $row_products['banner'];
                            
                            $stock = $row_products['seats'];
                            echo "
                            <div id='single_product'>
                                <form method='post' action='update_details.php' enctype='multipart/form-data'>
                                    <div class='form-group'>
                                        <label for='product_title'>Product Title:</label>
                                        <input type='text' class='form-control' id='product_title' name='product_title' value='$pro_title'>
                                    </div>
                                    <div class='form-group'>
                                        <label for='product_title'>QTY:</label>
                                        <input type='number'  id='stock' name='stock' value='$stock'>
                                    </div>
                                    <div class='form-group'>
                                        <label for='product_img1'>New Image 1:</label>
										<img src='admin/event_images/$pro_image1' class='bd-placeholder-img card-img-top' style='width:20%;' alt='$pro_title'>

                                        <input type='file' class='form-control-file' id='product_img1' name='product_img1'>
                                    </div>
                                   
                                    <div class='form-group'>
                                        <label for='product_price'>Product Price:</label>
                                        <input type='text' class='form-control' id='product_price' name='product_price' value='$pro_price'>
                                    </div>
                                    <div class='form-group'>
                                        <label for='product_desc'>Product Description:</label>
                                        <textarea class='form-control' id='product_desc' name='product_desc' rows='5'>$pro_desc</textarea>
                                    </div>
                                    <input type='hidden' name='pro_id' value='$pro_id'>
                                    <button type='submit' class='btn btn-primary' name='insert_product'>Update</button>
                                </form>
                            </div>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<a href="admin/update_page.php" class="float-right mt-3 mr-3"><button class="btn btn-warning"><i class="fas fa-arrow-left"></i> Back</button></a>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html><?php
// Database connection
$db = mysqli_connect("localhost", "cnzixezfln_event_management", "Himel625646@#", "cnzixezfln_event_management");

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['insert_product'])) {
    // Assigning values to variables
    $product_id = $_POST['pro_id'];
    $product_title = $_POST['product_title'];
    $product_price = $_POST['product_price'];
    $product_desc = $_POST['product_desc'];
    $stock = $_POST['stock'];

    // Check if new images are uploaded
    $product_img1 = $product_img2 = $product_img3 = null;

    if ($_FILES['product_img1']['name']) {
        $product_img1 = $_FILES['product_img1']['name'];
        $temp_name1 = $_FILES['product_img1']['tmp_name'];
        move_uploaded_file($temp_name1, "product_image/$product_img1");
    }

   

    // Update product information in the database
    $update_fields = "title='$product_title', description='$product_desc', price='$product_price', seats='$stock'";
    if ($product_img1) {
        $update_fields .= ", img1='$product_img1'";
    }
   

    $update_product = "UPDATE events SET $update_fields, created_at=NOW() WHERE id=$product_id";
    $run_product = mysqli_query($db, $update_product);

    if ($run_product) {
        // Retrieve the updated product details
        $query = "SELECT * FROM events WHERE id = $product_id";
        $result = mysqli_query($db, $query);
        $updated_product = mysqli_fetch_assoc($result);

        // Read existing data from car.json
        $json_file = 'car.json';
        if (file_exists($json_file)) {
            $json_data = file_get_contents($json_file);
            $products = json_decode($json_data, true);
        } else {
            $products = array();
        }

        // Find and update the corresponding product record
        foreach ($products as &$product) {
            if ($product['product_id'] == $product_id) {
                $product['title'] = $updated_product['title'];
                $product['description'] = $updated_product['description'];
                $product['price'] = $updated_product['price'];
                $product['stock'] = $updated_product['stock'];
                if (isset($updated_product['img1'])) $product['img1'] = $updated_product['img1'];
                if (isset($updated_product['img2'])) $product['img2'] = $updated_product['img2'];
                if (isset($updated_product['img3'])) $product['img3'] = $updated_product['img3'];
                $product['date'] = $updated_product['date'];
                break;
            }
        }

        // Save the updated data back to car.json
        if (file_put_contents($json_file, json_encode($products, JSON_PRETTY_PRINT))) {
            echo 'JSON file updated successfully';
        } else {
            echo 'Failed to update JSON file';
        }

        // Redirect to update_page.php if product update is successful
        header("Location: admin/update_page.php");
        exit();
    } else {
        // Redirect to update_details.php with pro_id if product update failed
        header("Location: admin/update_details.php?pro_id=$product_id");
        exit();
    }
}

$db->close();
?>
