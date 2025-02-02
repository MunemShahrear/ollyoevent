<?php
session_start();
include("database/db.php");
include('function/functions.php');
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
   <?php include('link.php');
?>

</head>

<body>
    <style>
    /* Custom CSS for product card */
    .product-card {
        border: 1px solid #f2f2f2;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .product-card:hover {
        transform: translateY(-5px);
        /* Adjust the value to control the amount of movement */
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        /* Adjust the shadow values as needed */
    }
    </style>

    <button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>



    <script>
    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("myBtn").style.display = "block";
        } else {
            document.getElementById("myBtn").style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
    </script>




    <!--main wraper-->
    <div class=".container-fluid">
        <section id="home" class="home-banner-01">




            <?php include('includes/header.php'); ?>


        </section>


        
        



        <section id="Product" class="home">
            <div class="container" style="padding:50px;">
          
                

                <div id="shop" class="row">
                    <div class="response"></div>
                    

                    <div class="container">
    <h1 class="mt-5">Manage Reservations</h1><br>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Event Details</th>
                    <th scope="col">Booking User Name</th>
                    <th scope="col">Event Date</th>
                    <th scope="col">Venue</th>
                    <th scope="col">Days</th>
                    <th scope="col">Total</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Database connection
            $db = mysqli_connect("localhost", "cnzixezfln_event_management", "Himel625646@#", "cnzixezfln_event_management");

            if (!$db) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Fetch data from the order_confirmed table
            $query = "
                SELECT 
                    order_confirmed.*, events.*
                FROM 
                    order_confirmed
                INNER JOIN 
                    events ON order_confirmed.event_id = events.id
            ";

            $result = mysqli_query($db, $query);

            if (mysqli_num_rows($result) > 0) {
                // Prepare the statement for fetching user data
                $userStmt = $db->prepare("SELECT user_name, user_email FROM user_table WHERE id = ?");
                if (!$userStmt) {
                    die("Prepare failed: (" . $db->errno . ") " . $db->error);
                }

                // Start the table
                while ($row = mysqli_fetch_assoc($result)) {
                    $status = $row["Status"];
                    $user_id = $row["user_id"];
                    
                    // Bind parameters and execute the statement
                    $userStmt->bind_param("i", $user_id);
                    $userStmt->execute();
                    
                    // Bind the result variables
                    $userStmt->bind_result($user_name, $user_email);
                    $userStmt->fetch();

                    $buttonLabel = "Cancel";
$buttonClass = "btn-danger";
$buttonDisabled = ($status == 0) ? "" : "disabled";
$sts = ($status == 0) ? "Pending" : (($status == 1) ? "Accepted" : "Rejected");
$formatted_date = date("j F Y", strtotime($row["event_date"])); 
$formatted_time = date("g A", strtotime($row["event_time"]));
                    echo '<tr>
                            <td>' . htmlspecialchars($row["title"]) . '</td>
                            <td>' . htmlspecialchars($row["name"]) . '</td>
                            <td>' . htmlspecialchars($formatted_date) . ' at ' . htmlspecialchars($formatted_time) . '</td>
                            <td>' . htmlspecialchars($row["event_venue"]) . '</td>
                            <td>' . htmlspecialchars($row["qty"]) . '</td>
                            <td>' . htmlspecialchars($row["total"]) . '</td>
                            <td>' . $sts . '</td>
                            <td>
                            <form method="post" action="rental.php">
                            <input type="hidden" name="order_id" value="' . htmlspecialchars($row["order_id"]) . '">
                            <button name="cancel" id="cancel" type="submit" class="btn ' . $buttonClass . ' btn-sm btn-action" ' . $buttonDisabled . '>' . $buttonLabel . '</button>
                        </form>                
                        </td>
                          </tr>';
                }

                // Close the statement
                $userStmt->close();
            } else {
                echo "0 results";
            }

            // Close the connection
            $db->close();
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS, Popper.js, and jQuery -->

<!-- Your existing script and PHP handling code -->

<?php
// Handle form submission for order cancellation
if (isset($_POST['order_id'])) {
    // Get the product ID from the form submission
    $order_id = $_POST['order_id'];

    // Reconnect to the database if necessary
    $db = mysqli_connect("localhost", "cnzixezfln_event_management", "Himel625646@#", "cnzixezfln_event_management");

    if (!$db) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare and execute the SQL statement to delete the product
    $sql = "DELETE FROM order_confirmed WHERE order_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        // Product deleted successfully
        echo "Order canceled";
        exit; // Make sure to stop execution after redirection
    } else {
        // Error deleting product
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $db->close();
}
?>
