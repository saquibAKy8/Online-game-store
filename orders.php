<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>

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
        <h3>MY ORDERS</h3>
        <p> <a href="homepage.php">home</a> / orders </p>
    </div>

    <section class="myOrders">

        <h1 class="title">Orders Placed</h1>

        <div class="boxContainer">

            <?php

            $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' ") or die('query failed');
            if (mysqli_num_rows($order_query) > 0) {
                while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
            ?>

                    <div class="box">
                        <p> Order Date : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
                        <p> Name : <span><?php echo $fetch_orders['name']; ?></span> </p>
                        <p> Phone : <span><?php echo $fetch_orders['number']; ?></span> </p>
                        <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
                        <p> Address : <span><?php echo $fetch_orders['address']; ?></span> </p>
                        <p> Payment Method : <span><?php echo $fetch_orders['method']; ?></span> </p>
                        <p> Games : <span><?php echo $fetch_orders['total_games']; ?></span> </p>
                        <p> Total Cost : <span><?php echo $fetch_orders['total_price']; ?>TK</span> </p>
                        <p> Order Status : <span style="color: <?php if ($fetch_orders['status'] == 'pending') {
                                                                    echo 'red';
                                                                } else {
                                                                    echo 'green';
                                                                } ?> ;"><?php echo $fetch_orders['status']; ?></span> </p>
                    </div>

            <?php
                }
            } else {
                echo '<p class="empty">Your have no orders</p>';
            }
            ?>

        </div>

    </section>

    <footer>
        <span class="fa-solid fa-copyright"></span><span> 2022 All rights reserved.</span>
    </footer>

    <script src="js/script.js"></script>

</body>

</html>