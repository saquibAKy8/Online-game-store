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

if (isset($_POST['addGame'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'upload_img/' . $image;

    $select_game_name = mysqli_query($conn, "SELECT name FROM `games` WHERE name = '$name'") or die('query failed');

    if (mysqli_num_rows($select_game_name) > 0) {
        $message[] = 'Game already added';
    } else {
        $add_game_query = mysqli_query($conn, "INSERT INTO `games` (name, price, image) VALUES('$name', '$price', '$image')") or die('query failed');

        if ($add_game_query) {
            if ($image_size > 2000000) {
                $message[] = 'image size is too large';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Game added successfully';
            }
        } else {
            $message[] = 'Game could not be added';
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_image_query = mysqli_query($conn, "SELECT image FROM `games` WHERE id = '$delete_id' ") or die('query failed');
    $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
    unlink('upload_img/' . $fetch_delete_image['image']);
    mysqli_query($conn, "DELETE FROM `games` WHERE id = '$delete_id' ") or die('query failed');
    header('location:adminGames.php');
}

if (isset($_POST['update_game'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_price = $_POST['update_price'];

    mysqli_query($conn, "UPDATE `games` SET name = '$update_name', price = '$update_price' WHERE id = '$update_p_id' ") or die('query failed');

    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_folder = 'upload_img/' . $update_image;
    $update_old_image = $_POST['update_old_image'];

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'file size too large';
        } else {
            mysqli_query($conn, "UPDATE `games` SET image = '$update_image' WHERE id = '$update_p_id' ") or die('query failed');
            move_uploaded_file($update_image_tmp_name, $update_folder);
            unlink('upload_img/' . $update_old_image);
        }
    }
    header('location:adminGames.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Games</title>

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

    <section class="addGames">

        <h1 class="title">Games</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <h3>Add Games</h3>
            <input type="text" name="name" class="box" placeholder="Enter Game name" required>
            <input type="number" min="0" name="price" class="box" placeholder="Enter Game price" required>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
            <input type="submit" name="addGame" value="Add Game" name="addGame" class="btn">
        </form>

    </section>

    <section class="showGames">

        <div class="boxContainer">
            <?php
            $select_game = mysqli_query($conn, "SELECT * FROM `games` ") or die('query failed');
            if (mysqli_num_rows($select_game) > 0) {
                while ($fetch_games = mysqli_fetch_assoc($select_game)) {
            ?>
                    <div class="box">
                        <img src="upload_img/<?php echo $fetch_games['image']; ?>" alt="">
                        <div class="name"><?php echo $fetch_games['name']; ?></div>
                        <div class="price"><?php echo $fetch_games['price']; ?>TK</div>
                        <a href="adminGames.php?update=<?php echo $fetch_games['id']; ?>" class="option-btn">Update</a>
                        <a href="adminGames.php?delete=<?php echo $fetch_games['id']; ?>" class="delete-btn" onclick="return confirm('Remove this game?');">Remove</a>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">No Games available</p>';
            }
            ?>
        </div>

    </section>

    <section class="editGamesForm">

        <?php

        if (isset($_GET['update'])) {
            $update_id = $_GET['update'];
            $update_query = mysqli_query($conn, "SELECT * FROM `games` WHERE id = '$update_id' ") or die('query failed');
            if (mysqli_num_rows($update_query) > 0) {
                while ($fetch_update = mysqli_fetch_assoc($update_query)) {

        ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                        <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
                        <img src="upload_img/<?php echo $fetch_update['image']; ?>" alt="">
                        <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Enter Game name">
                        <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="Enter Game price">
                        <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
                        <input type="submit" value="Update" name="update_game" class="btn">
                        <input type="reset" value="Cancel" id="close-update" class="option-btn">
                    </form>

        <?php

                }
            }
        } else {
            echo '<script>document.querySelector(".editGamesForm").style.display = "none";</script>';
        }
        ?>

    </section>

    <script src="js/adminScript.js"></script>
</body>

</html>