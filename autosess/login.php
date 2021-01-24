<?php // Do not put any HTML above this line
session_start();
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = 'a8609e8d62c043243c4e201cbb342862';  // Pw is meow123

if (isset($_POST['who']) && isset($_POST['pass'])){
    $name = $_POST['who'];
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
        $check = hash('md5', $salt.$pass);
        if ( $check == $stored_hash ) {
            $_SESSION['name'] = $name;
            // Redirect the browser to game.php
            error_log("Login success ".$name);
            header("Location: view.php");
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
<?php require_once "bootstrap.php"; ?>
<title>86c34004</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
if (isset($_SESSION['error'])) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<form method="POST">
<label for="nam">email</label>
<input type="text" name="who" id="nam"><br/>
<label for="id_1723">password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
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
