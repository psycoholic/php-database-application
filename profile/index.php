<?php
session_start();
require_once("pdo.php");
require_once("util.php");

?>
<!DOCTYPE html>
<html>
<head>
<title>0bee2582</title>
<?php bootstrap(); ?>
</head>
<body>
<div class="container">
<p>

<?php 
flash_messages();
if (!isset($_SESSION['user_id'])){
  echo ('<a href="login.php">Please log in</a>');
}
if (isset($_SESSION['user_id'])){
    echo ('<a href="logout.php">Log out</a>');
  }
?>
</p>

<?php

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    $sqlSelect = 'SELECT * FROM profile p inner join users u on u.user_id=p.user_id';
    $stmt = $pdo->query($sqlSelect);

    $profiles = $stmt->fetchAll();

?>   
    <table border="1">
    <tbody>
        <tr>
        <th>Name</th>
        <th>Headline</th>
        <?php if (isset($_SESSION['user_id'])){ ?>
        <th>Action</th>
        <?php } ?>
        </tr>
    <?php 
        foreach($profiles as $profile){
            echo("<tr>");
            $profile_id = $profile['profile_id'];
            $name = $profile['first_name'] . " " . $profile['last_name'];
            $headline = $profile['headline'];
            echo "<td><a href='view.php?profile_id=$profile_id'>$name</a></td>";
            echo "<td>$headline</td>";
            if (isset($_SESSION['user_id']) && intval($_SESSION['user_id']) == intval($profile["user_id"])){
            echo "<td><a href='edit.php?profile_id=$profile_id'>Edit </a><a href='delete.php?profile_id=$profile_id'> Delete</a></td>";
            }
            echo("</tr>");
        }
    ?>
    </tbody>
    </table>
    <?php if (isset($_SESSION['user_id'])) { ?>
    <p><a href="add.php">Add New Entry</a></p>
    <?php } ?>
</div>
</body>

