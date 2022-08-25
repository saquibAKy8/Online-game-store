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

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id' ") or die('query failed');
    header('location:adminContacts.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messages</title>

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

    <section class="messages">
        <h1 class="title">Messages</h1>
        <div class="boxContainer">
            <?php
            $select_message = mysqli_query($conn, "SELECT * FROM `message` ") or die('query failed');
            if (mysqli_num_rows($select_message) > 0) {
                while ($fetch_message = mysqli_fetch_assoc($select_message)) {
            ?>
                    <div class="box">
                        <p>Name : <span><?php echo $fetch_message['name']; ?></span></p>
                        <p>email : <span><?php echo $fetch_message['email']; ?></span></p>
                        <p>Phone : <span><?php echo $fetch_message['number']; ?></span></p>
                        <p>Message : <span><?php echo $fetch_message['message']; ?></span></p>
                        <a href="adminContacts.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('Remove this message?');" class="delete-btn">Remove</a>
                    </div>
            <?php
                };
            } else {
                echo '<p class="empty">You have no Messages</p>';
            }
            ?>
        </div>
    </section>

    <script src="js/adminScript.js"></script>
</body>

</html>