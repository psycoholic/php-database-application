<?php // Do not put any HTML above this line
session_start();
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}
require_once("pdo.php");
require_once "util.php";

$salt = 'XyZzy12*_';

if (isset($_POST['email']) && isset($_POST['pass'])){
    $name = $_POST['email'];
    $pass = $_POST['pass'];

    if (strlen($name) > 1 && strpos($name, '@') === false){
        $emailFailure = "Email must have an at-sign (@)";
        $_SESSION['error'] = $emailFailure;
        header("Location: login.php");
        return;
    }
    elseif ( strlen($name) < 1 || strlen($pass) < 1 ) {
        $failure = "Email and password are required";
        $_SESSION['error'] = $failure;
        header("Location: login.php");
        return;
    }
    else {        
        $check = hash('md5', $salt.$_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users
            WHERE email = :em AND password = :pw');
        $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);  
        
        if ( $row !== false ) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            // Redirect the browser to index.php
            header("Location: index.php");
            return;
        } else {
            error_log("Login fail ".$name." $check");
            $failure = "Incorrect password";
            $_SESSION['error'] = $failure;
            header("Location: login.php");
            return;
        }
    }
}
// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<?php bootstrap(); ?>
<title>0bee2582</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
flash_messages();
?>
<form method="POST">
<label for="nam">email</label>
<input type="text" name="email" id="email"><br/>
<label for="id_1723">password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" onclick="return doValidate();" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the four character sound a cat
makes (all lower case) followed by 123. -->
</p>
</div>
</body>
<script>
function doValidate() {
    console.log('Validating...');
    try {
        addr = document.getElementById('email').value;
        pw = document.getElementById('id_1723').value;
        console.log("Validating addr="+addr+" pw="+pw);
        if (addr == null || addr == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if ( addr.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}
</script>