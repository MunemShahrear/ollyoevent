<?php
session_start();
include("database/db.php");
include('function/functions.php');
?>
<!doctype html>
<html>

<head>

    <?php include('link.php');
?>
</head>

<body>

    <?php include('includes/header.php'); ?>


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 cardy">
                <form action="payment.php" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Event Details</h3>

                            <input type="hidden" name="userid"
                                value="<?php echo isset($_SESSION['UserId']) ? htmlspecialchars($_SESSION['UserId']) : ''; ?>">

                            <label for="Name"><i class="fa fa-profile"></i> Name</label>
                            <input required type="text" id="name" name="name" class="form-control"
                                placeholder="Sydney">

                            
                            <label for="mobile"><i class="fa fa-phone"></i> Phone</label>
                            <input required type="number" id="mobile" name="mobile" class="form-control"
                                placeholder="1234567">
                                <label for="license"><i class="fa fa-id-card"></i> License/NID</label>
                            <input required type="number" id="license" name="license" class="form-control"
                                placeholder="878923743">

                            <label for="address"><i class="fa fa-address-card-o"></i> Address</label>
                            <input required type="text" id="address" name="address" class="form-control"
                                placeholder="542 W. 15th Street">
                        </div>
                    </div><br><br>
                    <input type="submit" name="confirm" value="Link to confirm" class="btn btn-primary">
                    <a href="cart.php" style="text-decoration:none;color: #222327" class="btn btn-default">Back</a>
                </form>
            </div>
            <style>
            .cardy {
                border: 0px solid #c0c0c0;
                /* 1px border */
                border-radius: 4px;
                /* Rounded corners */
                box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
                /* Shadow */
                /* Additional styling if needed */

                padding: 20px;
            }
            </style>
            <div class="col-md-4 shadow">
                <div class="card cardy" style="">
                    <div class="card-header">
                        <h1 class="card-title">Invoice</h1>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Sn</th>
                                    <th>Item</th>
                                    <th>QTY</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
           include("database/db.php");
            $stmt = $db->prepare("SELECT * FROM cart INNER JOIN events ON cart.event_id = events.id");
            $stmt->execute();
            $grand_total=0;
            $sn=0;
            
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()):
        ?>
                                <tr>
                                    <td><?= ++$sn ?></td>
                                    <td><a href="details.php?pro_id=<?= $row['id'] ?>"><?= $row['title'] ?></a>
                                    </td>
                                    <td><?= $row['qty'] ?></td>
                                    <td><?= $row['total_price'] ?></td>
                                </tr>
                                <?php
                                $pro_id=$row['id'];
                                $days=$row['qty'];
            $grand_total += $row['total_price'];
            endwhile;
        ?>
                                <tr>
                                    <td colspan="3">Grand Total</td>
                                    <td><?= $grand_total ?></td>
                                </tr>
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>


        </div>
    </div>


</body>

</html><?php
if (isset($_POST['confirm'])) {
  
   

    // Fetch form data
    $user_id = $_POST['userid'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $license = $_POST['license'];
    $name = $_POST['name'];


   

    // Database connection
    
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Insert order into database
    $insert_order = "INSERT INTO order_confirmed (adress, total, mobile, qty, event_id, user_id, `name`, Status, license) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($insert_order);
    $status = 0;
    $stmt->bind_param("ssissisii", $address, $grand_total, $mobile, $days, $pro_id, $user_id, $name,  $status, $license);
    $stmt->execute();
    $stmt->close();

    // Update product stock in the database
    $stmt = $db->prepare("SELECT cart.event_id, cart.qty, events.seats FROM cart INNER JOIN events ON cart.event_id = events.id");
    $stmt->execute();
    $result = $stmt->get_result();

    // Array to hold updated quantities
    $updated_quantities = [];

    while ($row = $result->fetch_assoc()) {
        $product_id = $row['id'];
        $qty_ordered = $row['qty'];
        $current_stock = $row['stock'];

        // Calculate new stock after deducting ordered quantity
        $new_stock = $current_stock - $qty_ordered;
        $updated_quantities[$product_id] = $new_stock;

        // Update product stock in the products table
        $update_stmt = $db->prepare("UPDATE events SET seats = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $new_stock, $product_id);
        $update_stmt->execute();
        $update_stmt->close();
    }

    $stmt->close();

    // Clear the cart after updating the stock
    $clear_cart_stmt = $db->prepare("DELETE FROM cart");
    $clear_cart_stmt->execute();
    $clear_cart_stmt->close();

    // Update quantities in car.json
    $json_file = 'car.json';
    if (file_exists($json_file)) {
        $json_data = file_get_contents($json_file);
        $cars = json_decode($json_data, true);

        foreach ($cars as &$car) {
            if (isset($updated_quantities[$car['id']])) {
                $car['seats'] = $updated_quantities[$car['id']];
            }
        }

        file_put_contents($json_file, json_encode($cars, JSON_PRETTY_PRINT));
    }

    echo "<script>alert('Order successfully placed!');</script>";
    echo "<script>window.location.href='rental.php';</script>";
}
?>


