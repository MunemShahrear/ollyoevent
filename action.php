<?php 
session_start();
$db = mysqli_connect("localhost", "cnzixezfln_event_management", "Himel625646@#", "cnzixezfln_event_management");

if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    $pname = $_POST['pname'];
    $pimage = $_POST['pimage'];
    $pprice = $_POST['pprice'];
    $pqty = 1;
    $total_price = $_POST['pprice'];

    // Check if the cart already has any item
    $stmt = $db->prepare("SELECT COUNT(*) AS item_count FROM cart");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['item_count'] > 0) {
        // If there is already an item in the cart
        echo json_encode(['status' => 'error', 'message' => 'Only one item can be in the cart at a time.']);
    } else {
        // Proceed to add the item to the cart
        $query = $db->prepare("INSERT INTO cart (event_id, qty, total_price) VALUES (?, ?, ?)");
        $query->bind_param("sii", $pid, $pqty, $total_price);
        if ($query->execute()) {
            // Return a success response
            echo json_encode(['status' => 'success', 'message' => 'Item added to your cart.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add item to cart.']);
        }
    }
}


if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    $stmt = $db->prepare("DELETE FROM cart WHERE cart_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $_SESSION['showAlert'] = 'block';
    $_SESSION['massage'] = 'Item removed from the cart';
    header('location: cart.php');
}

if (isset($_GET['clear'])) {
    $stmt = $db->prepare("DELETE FROM cart");
    $stmt->execute();
    $_SESSION['showAlert'] = 'block';
    $_SESSION['massage'] = 'All items removed from the cart';
    header('location: cart.php');
}

if (isset($_POST['qty'], $_POST['pid'], $_POST['pprice'])) {
    $qty = $_POST['qty'];
    $pid = $_POST['pid'];
    $pprice = $_POST['pprice'];
    $tprice = $qty * $pprice;

    $stmt = $db->prepare("UPDATE cart SET qty = ?, total_price = ? WHERE cart_id = ?");
    $stmt->bind_param("idi", $qty, $tprice, $pid);
    if ($stmt->execute()) {
        echo "Item quantity and total price updated successfully.";
    } else {
        echo "Error updating item quantity and total price.";
    }
}
?>
