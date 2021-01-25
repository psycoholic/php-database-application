<?php
require_once "pdo.php";
session_start();
?>
<html>
<head></head><body>
<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
if (isset($_SESSION['name'])){
    $stmt = $pdo->query("SELECT make, year, mileage, model, auto_id FROM autos");
    if ($stmt->rowCount() > 0){
        echo('<table border="1">'."\n");
        echo ("<thead><tr><th>Make</th><th>Model</th><th>Year</th><th>Mileage</th><th>Action</th></tr></thead>");
        echo("<tbody>");
        while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr><td>";
            echo(htmlentities($row['make']));
            echo("</td><td>");
            echo(htmlentities($row['model']));
            echo("</td><td>");
            echo(htmlentities($row['year']));
            echo("</td><td>");
            echo(htmlentities($row['mileage']));
            echo("</td><td>");
            echo('<a href="edit.php?auto_id='.$row['auto_id'].'">Edit</a> / ');
            echo('<a href="delete.php?auto_id='.$row['auto_id'].'">Delete</a>');
            echo("</td></tr>\n");
        }
        echo("</tbody>");
        echo("</table>");
    }
    else{
        echo ("<p>No rows found</p>");
    }
}
?>
</table>
<title>Gerard de Way</title>
<div class="container">
<h2>Welcome to the Automobiles Database</h2>
<?php if (!isset($_SESSION['name'])){ ?>
<p><a href="login.php">Please log in</a></p>
<p>Attempt to <a href="add.php">add data</a> without logging in</p>
<?php } ?>
<?php if (isset($_SESSION['name'])){ ?>
<a href="add.php">Add New Entry</a>
<p><a href="logout.php">Logout</a></p>
<?php } ?>
</div>
