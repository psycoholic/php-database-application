<?php
session_start();
if (!isset($_SESSION['name'])){
    die("Not logged in");
}
require_once "pdo.php";

// If the user requested logout go back to index.php
if ( isset($_POST['Cancel']) ) {
    header("Location: view.php");
    return;
}

$warning_message = false;
$succes_message = false;

if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])){
    $make = htmlentities($_POST['make']);
    $year = htmlentities($_POST['year']);
    $mileage = htmlentities($_POST['mileage']);

    if (strlen($make) < 1){
        $warning_message = "Make is required";
        $_SESSION['warning_message'] = $warning_message;
        header("Location: add.php");
        return;
    }
    if (!is_numeric($year) || !is_numeric($mileage)){
        $warning_message = "Mileage and year must be numeric";
        $_SESSION['warning_message'] = $warning_message;
        header("Location: add.php");
        return;
    }
    if(strlen($make) > 1 && is_numeric($year) && is_numeric($mileage)) {
        $sql = "INSERT INTO autos (make,year,mileage) VALUES(:make,:year,:mileage)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':make' => htmlentities ($make),
            ':year' => htmlentities ($year),
            ':mileage' => htmlentities ($mileage)
        ));
        $succes_message = "Record inserted";
        $_SESSION['succes_message'] = $succes_message;
        header("Location: view.php");
        return;
    }
}


?>
<!DOCTYPE html>
<html>
<head>
<title>86c34004</title>
</head>
<body>
<div class="container">
<h1>Autos database</h1>

<?php
if (isset($_SESSION['warning_message'])){
    echo('<p style="color: red;">'.htmlentities($_SESSION['warning_message'])."</p>\n");
    unset($_SESSION['warning_message']);
}
?>

<form method="post">
        <p>make : <input type="text" name="make" size="40"></p>
        <p>year : <input type="text" name="year"></p>
        <p>mileage : <input type="text" name="mileage"></p>
        <p><input type="submit" name="Add" value="Add"><input type="submit" name="Cancel" value="Cancel"></p>
</form>
</div>
</body>
</html>