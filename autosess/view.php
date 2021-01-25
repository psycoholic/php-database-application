<?php 
session_start();
if (!isset($_SESSION['name'])){
    die("Not logged in");
}
require_once "pdo.php";
?>
<html><head>
<title>86c34004</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>
<body>
<div class="container">
<h1>Tracking Autos for <?php echo($_SESSION['name']) ?></h1>
<?php
if (isset($_SESSION['succes_message'])){
    echo('<p style="color: green;">'.htmlentities($_SESSION['succes_message'])."</p>\n");
    unset($_SESSION['succes_message']);
}
?>
<h2>Automobiles</h2>
<?php
    $sql = "SELECT * FROM autos";
    $pdo->prepare($sql);
    $stmt = $pdo->query($sql);
    echo('<ul>'."\n");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<li>";
        echo($row['auto_id']);
        echo " ";
        echo $row['make'];
        echo " ";
        echo $row['year'];
        echo " ";
        echo $row['mileage'];
    }
    echo "</ul>\n";
?>
<p>
<a href="add.php">Add New</a> |
<a href="logout.php">Logout</a>
</p>
</div>


</body></html>