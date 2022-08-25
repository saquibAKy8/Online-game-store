<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];

    mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id' ") or die('query failed');
    $message[] = 'Cart updated';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id' ") or die('query failed');
    header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id' ") or die('query failed');
    header('location:cart.php');
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
                <a href="orders.php">Orders</a>
                <a href="contact.php">Contact</a>
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
        <p> <a href="homepage.php">home</a> / cart </p>
    </div>

    <section class="shoppingCart">

        <h1 class="title">Game added</h1>

        <div class="boxContainer">

            <?php

            $grand_total = 0;

            $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id' ") or die('query failed');
            if (mysqli_num_rows($select_cart) > 0) {
                while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            ?>
                    <div class="box">
                        <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fa-solid fa-circle-xmark" onclick="return confirm('Remove this games from cart?');"></a>
                        <img src="upload_img/<?php echo $fetch_cart['image']; ?>" alt="">
                        <div class="name"><?php echo $fetch_cart['name']; ?></div>
                        <div class="price"><?php echo $fetch_cart['price']; ?>TK</div>
                        <form action="" method="POST">
                            <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                            <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                            <input type="submit" name="update_cart" value="Update" class="option-btn">
                        </form>
                        <div class="subTotal"> SubTotal : <span><?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?>TK</span> </div>
                    </div>
            <?php
                    $grand_total += $sub_total;
                }
            } else {
                echo '<p class="empty">Your cart is empty</p>';
            }
            ?>

        </div>

        <div style="margin-top: 2rem; text-align:center;">
            <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>" onclick="return confirm('Remove all games from cart?');">Remove All</a>
        </div>

        <div class="cartTotal">
            <p>Grand Total : <span><?php echo $grand_total; ?>TK</span></p>
            <div class="flex">
                <a href="store.php" class="option-btn">Continue Shopping</a>
                <a href="checkout.php" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to checkout</a>
            </div>
        </div>

    </section>

    <footer>
        <span class="fa-solid fa-copyright"></span><span> 2022 All rights reserved.</span>
    </footer>

    <script src="js/script.js"></script>

</body>

</html>