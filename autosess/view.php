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
<h1>Tracking Autos for csev@umich.edu</h1>
<h2>Automobiles</h2>
<ul>
<p>
</p><li>
</li></ul>
<?php
if (isset($_SESSION['warning_message'])) {
    echo('<p style="color: red;">'.htmlentities($warning_message)."</p>\n");
}
if (isset($_SESSION['succes_message'])){
    echo('<p style="color: green;">'.htmlentities($succes_message)."</p>\n");
}
?>
<h2>Autos</h2>
<?php
    $sql = "SELECT * FROM autos";
    $pdo->prepare($sql);
    $stmt = $pdo->query($sql);
    echo("<pre>\n");
    echo('<table border="1">'."\n");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<tr><td>";
        echo($row['auto_id']);
        echo "</td><td>";
        echo $row['make'];
        echo "</td><td>";
        echo $row['year'];
        echo "</td><td>";
        echo $row['mileage'];
        echo "</td>";
        echo "</tr>\n"; 
    }
    echo "</table>\n";
    echo("</pre>\n");
?>

<form method="post">
<input type="submit" name="logout" value="Logout">
</form>
<p>
<a href="add.php">Add New</a> |
<a href="logout.php">Logout</a>
</p>
</div>


</body></html>