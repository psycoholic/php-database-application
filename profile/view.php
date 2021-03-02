<?php 
session_start();
require_once "pdo.php";
require_once "util.php";
?>
<html>
<head>
<title>0bee2582</title>
<?php bootstrap(); ?>
</head>
<body>
<div class="container">
<?php
if (isset($_SESSION['succes_message'])){
    echo('<p style="color: green;">'.htmlentities($_SESSION['succes_message'])."</p>\n");
    unset($_SESSION['succes_message']);
}
?>
<h2>Profile information</h2>
<?php
    $profile_id = $_GET["profile_id"];
    $sql = "SELECT first_name,last_name,email,headline,summary FROM profile where profile_id = :profile_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':profile_id' => $profile_id
    ));

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo("<p>First Name: " . $row['first_name'] . "</p>");
        echo("<p>Last Name: " . $row['last_name'] . "</p>");
        echo("<p>Email: " . $row['email'] . "</p>");
        echo("<p>Headline: " . $row['headline'] . "</p>");
        echo("<p>Summary: " . $row['summary'] . "</p>");
    }

    $positions = loadPos($pdo, $profile_id);

    if (count($positions) > 0){
        echo ("<p>Position</p>");
        echo("<ul>");
        foreach($positions as $pos){
            echo("<li>".$pos['year'].":".$pos['description']."</li>"); 
        }
        echo("<ul>");
    }


    // $sql = "SELECT year,description FROM position where profile_id =:profid ";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute(array(
    //     ':profid' => $profile_id
    // ));
    // if ($stmt->rowCount() > 0) {
    //     echo ("<p>Position</p>");
    //     echo("<ul>");
    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    //     echo("<li>".$row['year'].":".$row['description']."</li>");
    // }
    // echo("<ul>");
    // }
?>
<p>
<a href="index.php">Done</a>
</p>
</div>


</body></html>