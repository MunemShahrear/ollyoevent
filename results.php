<?php
session_start();
include("database/db.php");
include('function/functions.php');
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('link.php');?>


</head>

<body>











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




        <!--navigation bar-->



        <!--poaster raper-->
        <?php
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    echo '<div class="alert alert-success">' . $message . '</div>';
}
?>



        <section id="Product" class="home">
            <div class="container" style="padding:50px;">
            <div class="row">
                    <div class="col">
                        <div style="display:<?php if(isset($_SESSION['showAlert'])){echo $_SESSION['showAlert'];}else {echo'none';} unset($_SESSION['showAlert']) ?>"
                            class="alert alert-success">
                            <strong><?php if(isset($_SESSION['massage'])){echo $_SESSION['massage'];} unset($_SESSION['massage']) ?></strong>.
                        </div>
                    </div>
                </div>




                <div class="row">

                    <div class="col-md-12">
                        <!-- Related Products -->
                        <div class="response"></div>
                        <h2>Discover Events</h2><br><br>
                        <div id="rel_product_page">

                            <div class="content_wraper">


                                <div id="product_page">
                                    <div id="products_box">
                                        <?php


if(isset($_GET['user_query'])){
    $user_keyword = $_GET['user_query'];

    // The SQL query to search products along with category and brand names
    $get_products = "
        SELECT *
        FROM events 
        INNER JOIN categories ON events.cat_id = categories.cat_id
       
        WHERE 
            events.keywords LIKE '%$user_keyword%' OR 
            events.title LIKE '%$user_keyword%' OR 
            events.description LIKE '%$user_keyword%' OR
            categories.cat_title LIKE '%$user_keyword%'
           
        LIMIT 0,9
    ";

    $run_products = mysqli_query($db, $get_products);
    
    // Check if any products found
    if(mysqli_num_rows($run_products) > 0) {
        while ($row_products = mysqli_fetch_array($run_products)) {
            $pro_id = $row_products['id'];
            $pro_title = $row_products['title'];
            $pro_cat = $row_products['cat_id'];
            $pro_desc = $row_products['description'];
            $pro_price = $row_products['price'];
            $pro_image = $row_products['banner'];
            $event_venue = $row_products['event_venue'];
            $event_time = $row_products['event_time'];
            $event_date = $row_products['event_date'];
            $stock = $row_products['seats'];

            $formatted_date = date("j F Y", strtotime($event_date)); 
            $formatted_time = date("g A", strtotime($event_time));

            // Display matching products
            echo "<a style='color:#3d3d3d' href='details.php?pro_id=$pro_id'>
            <div class='col-md-4'>
                <div class='card mb-3 shadow-sm product-card' style='padding=20px; margin=50px;'>
                    <a style='color:#3d3d3d' href='details.php?pro_id=$pro_id'>
                        <img src='admin/event_images/$pro_image' class='bd-placeholder-img card-img-top' width ='100%' alt='$pro_title'>
                    </a>
                    <div class='card-body'>
                        <a style='color:#3d3d3d' href='details.php?pro_id=$pro_id'>
                            <h3 class='card-title'>$pro_title</h3>
                        </a>
                   

<p class='card-text'>Date: $formatted_date</p>
<p class='card-text'>Time: $formatted_time</p>
   <p class='card-text'>Venue: $event_venue</p>
                        <p class='card-text'>Price: BDT $pro_price</p>
                        <div class='d-flex justify-content-between align-items-center'>
                            <div class='btn-group'>
                                <form action='' class='form-submit'>
                                    <input type='hidden' class='pid' value='$pro_id'>
                                    <input type='hidden' class='pname' value='$pro_title'>
                                    <input type='hidden' class='pprice' value='$pro_price'>
                                    <input type='hidden' class='pimage' value='$pro_image'>
                                    <p>Seats: $stock Person</p>
                                    <button class='btn btn-sm btn-outline-secondary bookTicketBtn'>
                                                <i style='color:#3f6b3d' class='fa fa-ticket'></i>Book Now
                                            </button>
                                    </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ";
        }
    } else {
        // No exact matches found, display random products
       echo"<h1>No Events Found!</h1>";
        }
    }

?>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>




            </div>

        </section>



        <div style="background:#1c1c1c; color: White"><br><br>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <script type="text/javascript">
    $(document).ready(function() {
        $('.form-submit').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            var form = $(this);
            var pid = form.find('.pid').val();
            var pname = form.find('.pname').val();
            var pprice = form.find('.pprice').val();
            var pimage = form.find('.pimage').val();

            // Send the form data via AJAX
            $.ajax({
                type: 'POST',
                url: 'action.php',
                data: {
                    pid: pid,
                    pname: pname,
                    pprice: pprice,
                    pimage: pimage
                },
                success: function(response) {
                    // Display the response from the server
                    $('.response').html(response);
                    // Automatically close the response after 5 seconds
                    setTimeout(function() {
                        $('.response').empty();
                    }, 5000); // 5000 milliseconds = 5 seconds
                }
            });
        });

        // Close response on button click
        $(document).on('click', '.close-btn', function() {
            $('.response').empty();
        });
    });
    </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>

</html>