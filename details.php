<?php
session_start();
include("database/db.php");
include('function/functions.php');
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<?php 

include('link.php');
?>
</head>

<body>

    <style>
        
/* Additional styling can be added as needed */
.button-container {
    margin-top: 20px;
}

.add-to-cart-btn,
.shop-now-btn {
    display: inline-block;
    padding: 10px 20px;
    text-decoration: none;
    font-size: 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.add-to-cart-btn {
    background-color: #75d902; /* Change to your desired color */
    color: #fff;
}

.shop-now-btn {
    background-color: #009e18; /* Change to your desired color */
    color: #fff;
    margin-left: 20px;
}

.add-to-cart-btn:hover,
.shop-now-btn:hover {
    background-color: darken(#ff6347, 10%); /* Adjust the color for hover effect */
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




        <!--navigation bar-->






        <section id="Product" class="home">
            <div class="container" style="padding:50px;">

                <div class="row">
                    <div class="col">
                        
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                                    <!-- Product Details -->
                                    <div class="response"></div>
                                    <div class="col-md-4" style="border: 1px solid #878787">
                                    <?php
                            if (isset($_GET['pro_id'])) {
                                // Fetch product details
                                $product_id = $_GET['pro_id'];
                                $get_products = "select * from events where id='$product_id'";
                                $run_products = mysqli_query($db, $get_products);
                                while ($row_products = mysqli_fetch_array($run_products)) {
                                    // Fetch product data
                                    $pro_title = $row_products['title'];
                                    $pro_price = $row_products['price'];
                                    $event_date = $row_products['event_date'];
                                    $event_time = $row_products['event_time'];
                                    $pro_desc = $row_products['description'];
                                    $pro_image1 = $row_products['banner'];

                                    // Display product details
                                    echo "
                                      
                                            <div class='img-magnifier-container'>
                                                <img id='myimage' style='width:70%; height:90%' src='admin/event_images/$pro_image1' class='img-fluid' alt='Product Image'>
                                            </div>
                                           
                                           
                                
                                       ";
                                }
                            }
                            ?></div>

                            <div class="col-md-8 text-justify" style="text-align:left">

                            <?php
                                if (isset($_GET['pro_id'])) {
                                    // Fetch product details
                                    $product_id = $_GET['pro_id'];
                                    $get_products = "select * from events where id='$product_id'";
                                    $run_products = mysqli_query($db, $get_products);
                                    while ($row_products = mysqli_fetch_array($run_products)) {
                                        // Fetch product data
                                        $pro_title = $row_products['title'];
                                        $pro_price = $row_products['price'];
                                        $pro_desc = $row_products['description'];
                                        $pro_image1 = $row_products['banner'];
                                        $stock = $row_products['seats'];
                                        if($stock>0){
                                            echo "
                                            <h1>$pro_title</h1>
                                            <h2>Price: A$ $pro_price</h2>
                                            <p>$pro_desc</p>
                                            <p>date: $event_date</p>
                                             <p>Time: $event_time</p>
                                            <p>In Stock: $stock</p>
                                            <div class='button-container'>
                                            <form action='' class='form-submit'>
                                            <input type='hidden' class='pid' value='$product_id'>
                                            <input type='hidden' class='pname' value='$pro_title'>
                                            <input type='hidden' class='pprice' value='$pro_price'>
                                            <input type='hidden' class='pimage' value='$pro_image1'>
                                        <button class='btn btn-sm btn-outline-secondary addItemBtn'><i style='color:#3f6b3d' class='fa fa-ticket'></i>Book Now</button>
                                    </form>
                                              
                                               
                                            </div>
                                            ";


                                        }
                                        else{
                                            echo "
                                        <h1>$pro_title</h1>
                                        <h2>Price: A$ $pro_price</h2>
                                        <p>$pro_desc</p>
                                        <p>0 pcs </p>
                                        <div class='button-container'>
                                        <form action='' class='form-submit'>
                                        <input type='hidden' class='pid' value='$product_id'>
                                        <input type='hidden' class='pname' value='$pro_title'>
                                        <input type='hidden' class='pprice' value='$pro_price'>
                                        <input type='hidden' class='pimage' value='$pro_image1'>
                                    <button disabled class='btn btn-sm btn-outline-secondary addItemBtn'></i>Sold Out</button>
                                </form>
                                          
                                           
                                        </div>
                                        ";
                                           

                                        }
                                        
                                       
                                    }
                                }
                                ?>

                            </div><hr>
                    </div>

                    <div class="row">

                    <div class="col-md-12">
                        <!-- Related Products -->
                        <div style="margin-top:10px; margin-bottom:30px;"><hr><br>
                            <h2>Related Products</h2><br><br>
                            <div id="rel_product_page">
                                <?php
                        // Function to display related products
                        getRelPro();
                        ?>
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

</body>

</html>