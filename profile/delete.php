<?php
require_once "pdo.php";
require_once "util.php";
session_start();
if (!isset($_SESSION['user_id'])){
    die("Not logged in");
}



$currentUser = $_SESSION['user_id'];

if(isset($_POST['profile_id'])){
    $stmt = $pdo->prepare("DELETE FROM profile where profile_id = :profile_id AND user_id = :user ");
    $stmt->execute(array(":profile_id" => $_POST['profile_id'], ":user" => $currentUser));

    if ($stmt->rowCount() > 0) {
    $_SESSION['succes_message'] = "Profile deleted";
    header("location: index.php");
    return;
    }
    else {
        $_SESSION['error'] = 'Could not delete profile';
        header( 'Location: index.php' ) ;
        return;
      }
}

$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :profile_id");
$stmt->execute(array(":profile_id" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

$firstname = htmlentities($row['first_name']);
$lastname = htmlentities($row['last_name']);
$profile = htmlentities($_GET['profile_id']);

?>
<html>
<head>
<?php bootstrap(); ?>
<title>0bee2582</title>
</head>
<body>
<div class="container">
<h1>Deleteing Profile</h1>
<form method="post" action="delete.php">
<form method="post">
<p>First Name: <?= $firstname ?></p>
<p>Last Name:<?= $lastname ?></p>
<input type="hidden" name="profile_id" value="<?php echo($profile) ?>">
<input type="submit" name="delete" value="Delete">
<input type="submit" name="cancel" value="Cancel">
<p></p>
</form>
</div>
</body>
</html>