<?php

session_start();
include("database/db.php");


if (isset($_GET['query'])) {
    $user_query = $_GET['query'];
    $user_query = mysqli_real_escape_string($db, $user_query);

    $query = "
        SELECT DISTINCT title AS suggestion
        FROM products 
        WHERE 
            title LIKE '%$user_query%' OR 
            keywords LIKE '%$user_query%'
        UNION
        SELECT DISTINCT cat_title AS suggestion
        FROM categories 
        WHERE 
            cat_title LIKE '%$user_query%'
        UNION
        SELECT DISTINCT brand_name AS suggestion
        FROM brand 
        WHERE 
            brand_name LIKE '%$user_query%'
        LIMIT 5
    ";

    $result = mysqli_query($db, $query);

    $suggestions = array();
    while ($row = mysqli_fetch_array($result)) {
        $suggestions[] = $row['suggestion'];
    }

    echo json_encode($suggestions);
}
?>
