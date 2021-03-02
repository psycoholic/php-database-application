<?php
require_once "pdo.php";
require_once "util.php";
session_start();
if (!isset($_SESSION['user_id'])){
    die("Not logged in");
}


if ( isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['profile_id']) ) {

    // Data validation
    if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1
        || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }

    $msg = validatePos();
    
    if (is_string($msg)){
        $_SESSION['warning_message'] = $msg;
        header("Location: edit.php");
        return;
    }

    $sql = "DELETE FROM Position WHERE profile_id=:pid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));


        // // Insert the position entries
        $rank = 1;
        for($i=1; $i<=9; $i++) {
            if ( ! isset($_POST['year'.$i]) ) continue;
            if ( ! isset($_POST['desc'.$i]) ) continue;
            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];

            $stmt = $pdo->prepare('INSERT INTO Position
                (profile_id, rank, year, description)
            VALUES ( :pid, :rank, :year, :desc)');
            $stmt->execute(array(
                ':pid' => $_REQUEST['profile_id'],
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $desc)
            );
        $rank++;
        }
    
        if ($stmt->rowCount() > 0) {
            $_SESSION['succes_message'] = 'Profile updated';
            header( 'Location: index.php' ) ;
            return;
        }

    //insert profile entries
    $profid = $_POST['profile_id'];
    $currentUser = $_SESSION['user_id'];

    $sql = "UPDATE profile 
            SET first_name = :firstname,
            last_name = :lastname,
            email = :email, 
            headline = :headline,
            summary = :summary
            WHERE profile_id = :profile_id
            AND user_id = :user";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':firstname' => $_POST['first_name'],
        ':lastname' => $_POST['last_name'],
        ':email' => $_POST['email'],
        ':headline' => $_POST['headline'],
        ':summary' => $_POST['summary'],
        ':profile_id' => $profid, 
        ':user' => $currentUser
    ));        
    if ($stmt->rowCount() > 0) {
        $_SESSION['succes_message'] = 'Profile updated';
        header( 'Location: index.php' ) ;
        return;
      } else {
        $_SESSION['error'] = 'Could not update profile';
        header( 'Location: index.php' ) ;
        return;
      }
   
}

if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :profile_id");
$stmt->execute(array(":profile_id" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$firstname = htmlentities($row['first_name']);
$lastname = htmlentities($row['last_name']);
$email = htmlentities($row['email']);
$headline = htmlentities($row['headline']);
$summary = $row['summary'];
$profile_id = $row['profile_id'];
$positions = loadPos($pdo, $profile_id);

?>
<html>
<head>
<title>0bee2582</title>
<?php bootstrap(); ?>
</head>
<body>
<h1>Editing Profile for <?= $_SESSION['name'] ?></h1>
<form method="post">
<p>First Name:
<input type="text" name="first_name" value="<?= $firstname ?>"></p>
<p>Last Name:
<input type="text" name="last_name" value="<?= $lastname ?>"></p>
<p>Email:
<input type="text" name="email" value="<?= $email ?>"></p>
<p>Headline:
<input type="text" name="headline" value="<?= $headline ?>"></p>
<p>Summary:
<input type="text" name="summary" value="<?= $summary ?>"></p>
<p>Position: <input type="submit" id="addPos" value="+"></p>
<div id="position_fields">
<?php
echo("<br>");
if (count($positions) > 0){    
    foreach($positions as $pos){
        echo("<div id='position".$pos['rank']."'>");
        echo("<p>Year: <input type='text' name='year".$pos['rank']."'value='".$pos['year']."' />");
        echo("<input type=\"button\" value=\"-\" onclick=\"$('#position".$pos['rank']."').remove();return false;\"></p>");
        echo("<textarea name=\"desc".$pos['rank']."\" rows=\"8\" cols=\"80\">".$pos['description']."</textarea></div>");
    }
    $endPos = end($positions);
    $endPos['rank'];
}
?>
</div>
<?php isset($endPos['rank']) ? $el = $endPos['rank'] : $el = 0 ?>
    <input type="text" style="display:none;" id="countEdit" value="<?= $el ?>">
<input type="hidden" name="profile_id" value="<?= $profile_id ?>">
<p><input type="submit" value="Save"/></p>
<p></p><a href="index.php">Cancel</a></p>
</form>
</body>
<script>
countPos = 0;

// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
$(document).ready(function(){
    if($('#countEdit').val() > 0){
        var countPos = $('#countEdit').val();
        console.log("greate than 0 " + countPos);
    }
    else {
        console.log("not greater than 0 " + countPos);
        countPos = 0;
    }
    $('#addPos').click(function(event){
        // http://api.jquery.com/event.preventdefault/
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" \
            onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });

    
});
</script>
</html>
