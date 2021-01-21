<?php

require_once "pdo.php";
require_once "bootstrap.php";

// Demand a GET parameter
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}

$warning_message = false;
$succes_message = false;

if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])){ 
    if (strlen($_POST['make']) < 1){
        $warning_message = "Make is required";
    }
    if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])){
        $warning_message = "Mileage and year must be numeric";
    }
    if(strlen($_POST['make']) > 1 && is_numeric($_POST['year']) && is_numeric($_POST['mileage'])) {
        $sql = "INSERT INTO autos (make,year,mileage) VALUES(:make,:year,:mileage)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':make' => htmlentities ($_POST['make']),
            ':year' => htmlentities ($_POST['year']),
            ':mileage' => htmlentities ($_POST['mileage'])
        ));
        $succes_message = "Record inserted";
    }
}


?>
<!DOCTYPE html>
<html>
<head>
<title>Gerard Willem de Way, Autos database</title>
</head>
<body>
<div class="container">
<h1>Autos database</h1>
<?php
if ( isset($_REQUEST['name']) ) {
    echo "<p>Welcome: ";
    echo htmlentities($_REQUEST['name']);
    echo "</p>\n";
}
?>

<form method="post">
        <p>make : <input type="text" name="make" size="40"></p>
        <p>year : <input type="text" name="year"></p>
        <p>mileage : <input type="text" name="mileage"></p>
        <p><input type="submit" value="add new"></p>
</form>
<?php 
if ($warning_message !== false){
    echo('<p style="color: red;">'.htmlentities($warning_message)."</p>\n");
}
if ($succes_message !== false){
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
</div>
</body>
</html>
