<?php
session_start();
if (!isset($_SESSION['name'])){
    die("Not logged in");
}
require_once "pdo.php";
require_once "util.php";

// If the user requested logout go back to index.php
if ( isset($_POST['Cancel']) ) {
    header("Location: index.php");
    return;
}

$warning_message = false;
$succes_message = false;

if (isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}

if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])){
    $firstname = htmlentities($_POST['first_name']);
    $lastname = htmlentities($_POST['last_name']);
    $email = htmlentities($_POST['email']);
    $headline = htmlentities($_POST['headline']);
    $summary = htmlentities($_POST['summary']);

    $msg = validatePos();

    if (is_string($msg)){
        $_SESSION['warning_message'] = $msg;
        header("Location: add.php");
        return;
    }

    if ((strlen($firstname) < 1 || strlen($lastname) < 1 || strlen($email) < 1 || strlen($headline) < 1 || strlen($summary) < 1) ){
        $warning_message = "All fields are required";
        $_SESSION['warning_message'] = $warning_message;
        header( "Location: add.php");
        return;
    }
    if (strlen($email) > 1 && strpos($email, '@') === false){
        $emailFailure = "Email must have an at-sign (@)";
        $_SESSION['warning_message'] = $emailFailure;
        header("Location: add.php");
        return;
    }

    $sql = "INSERT INTO profile(user_id, first_name, last_name, email, headline, summary) VALUES (:user_id,:firstname,:lastname,:email,:headline,:summary)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':user_id' => $user_id,
        ':firstname' => $firstname,
        ':lastname' => $lastname,
        ':email' => $email,
        ':headline' => $headline,
        ':summary' => $summary
    ));

    $profile_id = $pdo->lastInsertId();

    for($i=1; $i<=9; $i++) {
        if (!isset($rank)){
            $rank = 1;
        }
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
    
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        $stmt = $pdo->prepare('INSERT INTO Position
        (profile_id, rank, year, description) 
        VALUES ( :pid, :rank, :year, :desc)');
        $stmt->execute(array(
            ':pid' => $profile_id,
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc)
        );
        $rank++;
      }


    if ($stmt->rowCount() > 0) {
        $_SESSION['succes_message'] = 'Profile added';
        header( 'Location: index.php' ) ;
        return;
        } else {
        $_SESSION['error'] = 'Could not add profile';
        header( 'Location: index.php' ) ;
        return;
        }         
}


?>
<!DOCTYPE html>
<html>
<head>
<title>0bee2582</title>
<?php bootstrap(); ?>
</head>
<body>
<div class="container">
<h1>Add Users</h1>

<?php
flash_messages();
?>

<form method="post">
        <p>First Name: <input type="text" name="first_name" size="40"></p>
        <p>Last Name: <input type="text" name="last_name"></p>
        <p>Email: <input type="text" name="email"></p>
        <p>Headline: <input type="text" name="headline"></p>
        <p>Summary: <textarea name="summary" rows="8" cols="80"></textarea></p>
        <p>Education: <input type="submit" id="addEdu" value="+"></p>
        <div id="edu_fields">
        <p>Position: <input type="submit" id="addPos" value="+"></p>
        <div id="position_fields"></div>
        <p><input type="submit" name="Add" value="Add"><input type="submit" name="Cancel" value="Cancel"></p>
</form>
</div>
</body>

<script>
countPos = 0;

// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
$(document).ready(function(){
    window.console && console.log('Document ready called');
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