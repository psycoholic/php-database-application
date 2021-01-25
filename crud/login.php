<?php // Do not put any HTML above this line
session_start();
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is meow123

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
        $failure = "User name and password are required";
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
<title>Gerard de Way</title>
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
User Name <input type="text" name="email"><br/>
Password <input type="text" name="pass"><br/>
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
