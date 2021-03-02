<?php
if ( isset($_GET['pin']) ) {
    if (!is_numeric($_GET['pin'])){
        $_SESSION['error'] = 'Input must numeric';
    }
    if (strlen($_GET['pin']) > 4){
        $_SESSION['error'] = 'Input must be exactly four characters';
    }
    else if (is_numeric($_GET['pin'])){
        $md5 = hash('md5', $_GET['pin']);
    }
}
?>
<!DOCTYPE html>
<head><title>Charles Severance MD5</title></head>
<body>
<h1>MD5 PIN Maker</h1>
<?php echo(isset($md5) ? 'MD5 value: ' . $md5 : '') ?>
<br>
<?php
if (isset($_SESSION['error'])) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<form _lpchecked="1">
<input type="text" name="pin" value="">
<input type="submit" value="Compute MD5 for PIN">
</form>
<ul>
<li><a href="makepin.php">Reset this page</a></li>
<li><a href="index.php">Back to Cracking</a></li>
</ul>
</body>
</html>