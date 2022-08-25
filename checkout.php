<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['order_btn'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = $_POST['number'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_games[] = '';

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id' ") or die('query failed');
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_games[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_games = implode(', ', $cart_games);

    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_games = '$total_games' AND total_price = '$cart_total' ") or die('query failed');

    if ($cart_total == 0) {
        $message[] = 'Cart empty';
    } else {
        if (mysqli_num_rows($order_query) > 0) {
            $message[] = 'Order already placed';
        } else {
            mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_games, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_games', '$cart_total', '$placed_on') ") or die('query failed');
            $message[] = 'Order placed';
            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id' ") or die('query failed');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <link href="trainericon.png" rel="icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

    <link rel="stylesheet" href="css/stylesheet.css">
</head>

<body>

    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '
            <div class="message">
                <span>' . $message . '</span>
                <i class="fa-solid fa-circle-xmark" onclick="this.parentElement.remove();"></i>
            </div>
            ';
        }
    }
    ?>

    <header class="header">
        <div class="flex">
            <a href="homepage.php" class="logo">Kappa<span>Games</span></a>

            <nav class="navbar">
                <a href="homepage.php">Home</a>
                <a href="store.php">Store</a>
                <a href="contact.php">Contact</a>
                <a href="orders.php">Orders</a>
            </nav>

            <div class="icons">
                <div id="menuBtn" class="fa-solid fa-bars"></div>
                <a href="searchPage.php" class="fa-solid fa-magnifying-glass"></a>
                <?php
                $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                $cart_rows_number = mysqli_num_rows($select_cart_number);
                ?>
                <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i><span>(<?php echo $cart_rows_number; ?>)</span></a>
                <div id="userBtn" class="fa-solid fa-user"></div>
            </div>

            <div class="userBox">
                <p>Username : <span><?php echo $_SESSION['user_name']; ?></span></p>
                <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
                <a href="logout.php" class="delete-btn">Logout</a>
            </div>

        </div>
    </header>

    <div class="heading">
        <h3>CHECKOUT</h3>
        <p> <a href="homepage.php">home</a> / checkout </p>
    </div>

    <section class="displayOrders">

        <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id' ") or die('query failed');
        if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                $grand_total += $total_price;
        ?>
                <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo $fetch_cart['price'] . 'TK' . ' x ' . $fetch_cart['quantity']; ?>)</span> </p>
        <?php
            }
        } else {
            echo '<p class="empty">Your cart is empty</p>';
        }
        ?>
        <div class="grandTotal"> Grand Total : <span><?php echo $grand_total; ?>TK</span> </div>

    </section>

    <section class="checkout">

        <form action="" method="POST">
            <h3>Place your order</h3>
            <div class="flex">
                <div class="inputBox">
                    <span>Name : </span>
                    <input type="text" name="name" required placeholder="Enter your name">
                </div>
                <div class="inputBox">
                    <span>Phone : </span>
                    <input type="number" name="number" required placeholder="Enter your number">
                </div>
                <div class="inputBox">
                    <span>email : </span>
                    <input type="email" name="email" required placeholder="Enter your email">
                </div>
                <div class="inputBox">
                    <span>Payment Method : </span>
                    <select name="method">
                        <option value="Cash on Delivery">Cash on Delivery</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="bKash">bKash</option>
                    </select>
                </div>
                <div class="inputBox">
                    <span>Address : </span>
                    <input type="text" name="address" required placeholder="Enter your address">
                </div>
            </div>
            <input type="submit" value="Order" class="btn" name="order_btn">
        </form>

    </section>

    <footer>
        <span class="fa-solid fa-copyright"></span><span> 2022 All rights reserved.</span>
    </footer>

    <script src="js/script.js"></script>

</body>

</html>