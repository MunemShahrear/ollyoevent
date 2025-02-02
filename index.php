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
    <!-- Pop-up Modal -->
<div id="popupModal" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3); z-index: 9999; border-radius: 10px; text-align: center;">
    <h3>Watch My Latest Work!</h3>
    <p>Visit now to see my newest projects.</p>
    <a href="https://www.imhimel.com" target="_blank" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Visit Now</a>
    <br><br>
    <button onclick="closePopup()" style="background: red; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px;">Close</button>
</div>

<!-- Overlay Background -->
<div id="popupOverlay" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9998;" onclick="closePopup()"></div>

<script>
    function showPopup() {
        document.getElementById("popupModal").style.display = "block";
        document.getElementById("popupOverlay").style.display = "block";
    }

    function closePopup() {
        document.getElementById("popupModal").style.display = "none";
        document.getElementById("popupOverlay").style.display = "none";
        localStorage.setItem("popupClosed", Date.now()); // Store last closed time
    }

    function checkPopup() {
        let lastClosed = localStorage.getItem("popupClosed");
        let now = Date.now();
        let tenMinutes = 10 * 60 * 1000;

        if (!lastClosed || now - lastClosed > tenMinutes) {
            showPopup();
        }
    }

    // Run the function when the page loads
    window.onload = checkPopup;
</script>

    
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
          
                <div class="row">
                    <div class="col">
                        <h2 class="mt-4 mb-3">Book your Event</h2><br>
                    </div>
                </div>

                <div id="shop" class="row">
                    <div class="response"></div>
                    <?php
                           
                           getEvent();
                            getCatPro();//category product
                           
                            ?>

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
            dataType: 'json', // Expect JSON response from the server
            success: function(response) {
                if (response.status === 'success') {
                    // Redirect to cart.php with the success message
                    window.location.href = 'cart.php?message=' + encodeURIComponent(response.message);
                } else {
                    // Display the response message
                    $('.response').html('<div class="alert alert-danger">' + response.message + '</div>');
                    // Automatically close the response after 5 seconds
                    setTimeout(function() {
                        $('.response').empty();
                    }, 5000); // 5000 milliseconds = 5 seconds
                }
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