<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['send'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = $_POST['number'];
    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg' ") or die('query failed');

    if (mysqli_num_rows($select_message) > 0) {
        $message[] = 'Message already sent';
    } else {
        mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg') ") or die('query failed');
        $message[] = 'Message sent successfully';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>

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
        <h3>CONTACT ME</h3>
        <p> <a href="homepage.php">home</a> / contact </p>
    </div>

    <section class="contact">

        <form action="" method="POST">
            <h3>Send Message</h3>
            <input type="text" name="name" required placeholder="Enter your name" class="box">
            <input type="email" name="email" required placeholder="Enter your email" class="box">
            <input type="number" name="number" required placeholder="Enter your phone number" class="box">
            <textarea name="message" class="box" placeholder="Enter your message" id="" cols="30" rows="10"></textarea>
            <input type="submit" value="Send" name="send" class="btn">
        </form>

    </section>

    <footer>
        <span class="fa-solid fa-copyright"></span><span> 2022 All rights reserved.</span>
    </footer>

    <script src="js/script.js"></script>

</body>

</html>