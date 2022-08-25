<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['add_to_cart'])) {
    $game_name = $_POST['game_name'];
    $game_price = $_POST['game_price'];
    $game_image = $_POST['game_image'];
    $game_quantity = $_POST['game_quantity'];

    $check_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$game_name' AND user_id = '$user_id' ") or die('query failed');

    if (mysqli_num_rows($check_cart_number) > 0) {
        $message[] = 'Already added to cart';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$game_name', '$game_price', '$game_quantity', '$game_image') ") or die('query failed');
        $message[] = 'Game added to cart';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>

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
        <h3>SEARCH GAMES</h3>
        <p> <a href="homepage.php">home</a> / search </p>
    </div>

    <section class="searchForm">
        <form action="" method="POST">
            <input type="text" name="search" placeholder="Search Games...." class="box">
            <input type="submit" name="submit" value="Search" class="btn">
        </form>
    </section>

    <section class="games" style="padding-top: 0;">

        <div class="boxContainer">
            <?php

            if (isset($_POST['submit'])) {
                $search_game = $_POST['search'];
                $select_games = mysqli_query($conn, "SELECT * FROM `games` WHERE name LIKE '%{$search_game}%' ") or die('query failed');
                if (mysqli_num_rows($select_games) > 0) {
                    while ($fetch_games = mysqli_fetch_assoc($select_games)) {
            ?>
                        <form action="" method="POST" class="box">
                            <img src="upload_img/<?php echo $fetch_games['image']; ?>" alt="">
                            <div class="name"><?php echo $fetch_games['name']; ?></div>
                            <div class="price"><?php echo $fetch_games['price']; ?>TK</div>
                            <input type="number" min="1" name="game_quantity" value="1" class="quantity">
                            <input type="hidden" name="game_name" value="<?php echo $fetch_games['name']; ?>">
                            <input type="hidden" name="game_price" value="<?php echo $fetch_games['price']; ?>">
                            <input type="hidden" name="game_image" value="<?php echo $fetch_games['image']; ?>">
                            <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
                        </form>
            <?php
                    }
                } else {
                    echo '<p class="empty">No Games with that name found</p>';
                }
            } else {
                echo '<p class="empty">Search for a game</p>';
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