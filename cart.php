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

    <button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>






    <!--main wraper-->
    <div class=".container-fluid">
        <section id="home" class="home-banner-01">
            <?php include('includes/header.php'); ?>


        </section>




        <!--navigation bar-->



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

                    <table class="table table-hover table-bordered table-striped table-responsive">
                        <thead>
                            <tr>

                                <td colspan="8">
                                    <h4 class="Text-center text-info m-0">Confirm Reservation</hr>
                                </td>
                            </tr>
                            <tr>

                                <th scope="col">#</th>
                                <th scope="col">Image</th>
                                <th scope="col">Event Details</th>
                                <th scope="col">Price</th>
                                <th scope="col">Available seats</th>
                                <th scope="col">Seats Taken</th>
                                <th scope="col">Total</th>
                                <th scope="col">
                                    <a href="action.php?clear=all" style="background:#d20000"
                                        class="badge badge-danger p-1" onclick="return confirm('Are you Sure ?');">Clear
                                        Cart</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php 
        
              $db = mysqli_connect("localhost", "cnzixezfln_event_management", "Himel625646@#", "cnzixezfln_event_management");
        $stmt = $db->prepare("SELECT *
                            FROM cart 
                            INNER JOIN events ON cart.event_id = events.id");
        $stmt->execute();
        $grand_total=0;
        $sn=0;
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()):
        ?>
                            <tr>
                                <td><?= $sn=$sn+1 ?></td>
                                <td><img src="admin/event_images/<?= $row['banner'] ?>" alt="<?= $row['title'] ?>"
                                        style="width: 60px;"></td>
                                <td><?= $row['title'] ?></td>
                                <td><?= $row['price'] ?></td>

                                <input type="hidden" class="pid" value="<?= $row['cart_id'] ?>">
                                <input type="hidden" class="pprice" value="<?= $row['price'] ?>">
                                <td>
    <?php 
    if($row['seats'] > 0) { 
        echo $row['seats']; 
    } else { 
        echo "Unavailable!"; 
    } 
    ?>
</td>
                                <td>

                                    <input type="number" style="width:75px;" class="form-control itemQty"
                                        value="<?= $row['qty'] ?>" min="1" max="<?= $row['seats'] ?>">
                                </td>

                                <td><?= $row['total_price'] ?></td>

                                <td><a href="action.php?remove=<?=$row['cart_id'] ?>" class="text-danger lead"
                                        onclick="return confirm('Remove this item from cart?');"><i
                                            style="color:#d20000" class="fa fa-trash"></i></a></td>
                            </tr>

                            <?php
        $grand_total+=$row['total_price'] ;
        
        ?>

                            <?php endwhile ?>
                            <tr>
                                <td colspan="2"><a href="index.php#shop" style="background:#70bf40; width:70%"
                                        class="badge badge-success"><i class="fa fa-cart-plus"></i>Shop more</a></td>
                                <td colspan="4">
                                    <h4 class="Text-right text-info m-0">Grand Total:</hr>
                                </td>
                                <td colspan="1">
                                    <h4 class="Text-right text-info m-0"><?= $grand_total ?></hr>
                                </td>
                                <td>
                                    <?php 
                                       
                                        if ($grand_total < 0): ?>
                                        <span class="badge badge-secondary" style="background:#808080; width:70%"
                                        disabled>Checkout <i class="fa fa-arrow-circle-right"></i></span>
                                    <?php else: 
                                     if (!isset($_SESSION['IdValidation'])): ?>
                                    <a href="login/login.php?redirect=payment.php" class="badge badge-success"
                                        style="background:#008040; width:70%">Checkout <i
                                            class="fa fa-arrow-circle-right"></i></a>
                                    <?php else: ?>
                                    <a href="payment.php" class="badge badge-success"
                                        style="background:#008040; width:70%">Checkout <i
                                            class="fa fa-arrow-circle-right"></i></a>
                                    <?php endif;
    endif; ?>
                                </td>

                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>

        </section>



        <div style="background:#1c1c1c; color: White"><br><br>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {

        $(".itemQty").on('change', function() {
            var $el = $(this).closest('tr');
            var pid = $el.find(".pid").val();
            var pprice = parseFloat($el.find(".pprice").val());
            var qty = parseInt($el.find(".itemQty").val());
            var totalPrice = pprice * qty;
            location.reload(true);
            $.ajax({
                url: 'action.php',
                method: 'post',
                data: {
                    qty: qty,
                    pid: pid,
                    pprice: pprice
                },
                success: function(response) {
                    console.log(response);
                    // Update total price in the current row
                    $el.find(".totalPrice").text(totalPrice.toFixed(2));
                }
            });
        });


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