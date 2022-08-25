<?php

include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <link href="trainericon.png" rel="icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

    <link rel="stylesheet" href="css/adminStylesheet.css">
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
            <a href="adminPage.php" class="logo">Admin<span>Panel</span></a>

            <nav class="navbar">
                <a href="adminPage.php">Home</a>
                <a href="adminGames.php">Games</a>
                <a href="adminOrders.php">Orders</a>
                <a href="adminUsers.php">Users</a>
                <a href="adminContacts.php">Messages</a>
            </nav>

            <div class="icons">
                <div id="menuBtn" class="fa-solid fa-bars"></div>
                <div id="userBtn" class="fa-solid fa-user"></div>
            </div>

            <div class="accountBox">
                <p>Username : <span><?php echo $_SESSION['admin_name']; ?></span></p>
                <p>email : <span><?php echo $_SESSION['admin_email']; ?></span></p>
                <a href="logout.php" class="delete-btn">Logout</a>
            </div>
        </div>
    </header>

    <section class="dashboard">
        <h1 class="title">DASHBOARD</h1>

        <div class="boxContainer">
            <div class="box">
                <?php

                $total_pendings = 0;
                $selected_pending = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE status = 'pending'") or die('query failed');
                if (mysqli_num_rows($selected_pending) > 0) {
                    while ($fetch_pendings = mysqli_fetch_assoc($selected_pending)) {
                        $total_price = $fetch_pendings['total_price'];
                        $total_pendings += $total_price;
                    };
                };
                ?>
                <h3><?php echo $total_pendings; ?></h3>
                <p>Orders Pending</p>
            </div>

            <div class="box">
                <?php

                $total_completed = 0;
                $selected_completed = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE status = 'completed'") or die('query failed');
                if (mysqli_num_rows($selected_completed) > 0) {
                    while ($fetch_completed = mysqli_fetch_assoc($selected_completed)) {
                        $total_price = $fetch_completed['total_price'];
                        $total_completed += $total_price;
                    };
                };
                ?>
                <h3><?php echo $total_completed; ?></h3>
                <p>Orders Completed</p>
            </div>

            <div class="box">
                <?php
                $selected_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
                $number_of_orders = mysqli_num_rows($selected_orders);
                ?>
                <h3><?php echo $number_of_orders; ?></h3>
                <p>Orders Placed</p>
            </div>

            <div class="box">
                <?php
                $selected_games = mysqli_query($conn, "SELECT * FROM `games`") or die('query failed');
                $number_of_games = mysqli_num_rows($selected_games);
                ?>
                <h3><?php echo $number_of_games; ?></h3>
                <p>Total Games</p>
            </div>

            <div class="box">
                <?php
                $selected_users = mysqli_query($conn, "SELECT * FROM `user` WHERE user_type = 'user' ") or die('query failed');
                $number_of_users = mysqli_num_rows($selected_users);
                ?>
                <h3><?php echo $number_of_users; ?></h3>
                <p>Number of Users</p>
            </div>

            <div class="box">
                <?php
                $selected_admins = mysqli_query($conn, "SELECT * FROM `user` WHERE user_type = 'admin' ") or die('query failed');
                $number_of_admins = mysqli_num_rows($selected_admins);
                ?>
                <h3><?php echo $number_of_admins; ?></h3>
                <p>Number of Admins</p>
            </div>

            <div class="box">
                <?php
                $selected_account = mysqli_query($conn, "SELECT * FROM `user` ") or die('query failed');
                $number_of_account = mysqli_num_rows($selected_account);
                ?>
                <h3><?php echo $number_of_account; ?></h3>
                <p>Total Users</p>
            </div>

            <div class="box">
                <?php
                $selected_messages = mysqli_query($conn, "SELECT * FROM `message` ") or die('query failed');
                $number_of_messages = mysqli_num_rows($selected_messages);
                ?>
                <h3><?php echo $number_of_messages; ?></h3>
                <p>New Messages</p>
            </div>

        </div>
    </section>

    <script src="js/adminScript.js"></script>
</body>

</html>