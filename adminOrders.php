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

if (isset($_POST['update_order'])) {
    $order_update_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    mysqli_query($conn, "UPDATE `orders` SET status = '$update_payment' WHERE id = '$order_update_id' ") or die('query failed');
    $message[] = 'Order Updated';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id' ") or die('query failed');
    header('location:adminOrders.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders</title>

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

    <section class="orders">

        <h1 class="title">Orders</h1>

        <div class="boxContainer">
            <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            if (mysqli_num_rows($select_orders) > 0) {
                while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
            ?>

                    <div class="box">
                        <p> User-ID : <span><?php echo $fetch_orders['user_id']; ?></span> </p>
                        <p> Date : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
                        <p> Name : <span><?php echo $fetch_orders['name']; ?></span> </p>
                        <p> Phone : <span><?php echo $fetch_orders['number']; ?></span> </p>
                        <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
                        <p> Address : <span><?php echo $fetch_orders['address']; ?></span> </p>
                        <p> Total Games : <span><?php echo $fetch_orders['total_games']; ?></span> </p>
                        <p> Total Price : <span><?php echo $fetch_orders['total_price']; ?> TK</span> </p>
                        <p> Payment Method : <span><?php echo $fetch_orders['method']; ?></span> </p>
                        <form action="" method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                            <select name="update_payment">
                                <option value="" selected disabled><?php echo $fetch_orders['status']; ?></option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                            <input type="submit" value="Update" name="update_order" class="option-btn">
                            <a href="adminOrders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Remove this order?');" class="delete-btn">Remove</a>
                        </form>
                    </div>

            <?php
                }
            } else {
                echo '<p class="empty">No Orders Recieved</p>';
            }
            ?>
        </div>

    </section>



    <script src="js/adminScript.js"></script>
</body>

</html>