<?php // Do not put any HTML above this line

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = 'a8609e8d62c043243c4e201cbb342862';  // Pw is meow123

$failure = false;  // If we have no POST data
$emailFailure = false;

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['who']) && isset($_POST['pass']) ) {
    if (strlen($_POST['who']) > 1 && strpos($_POST['who'], '@') === false){
        $emailFailure = "Email must have an at-sign (@)";
    }
    elseif ( strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1 ) {
        $failure = "Email and password are required";
    }
    else {
        $check = hash('md5', $salt.$_POST['pass']);
        if ( $check == $stored_hash ) {
            // Redirect the browser to game.php
            error_log("Login success ".$_POST['who']);
            header("Location: autos.php?name=".urlencode($_POST['who']));
            return;
        } else {
            error_log("Login fail ".$_POST['who']." $check");
            $failure = "Incorrect password";
        }
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Gerard Willem de Way's Login Page</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
if ( $failure !== false ) { 
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
if ($emailFailure !== false){
    echo('<p style="color: red;">'.htmlentities($emailFailure)."</p>\n");
}
?>
<form method="POST">
<label for="nam">Email</label>
<input type="text" name="who" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
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
