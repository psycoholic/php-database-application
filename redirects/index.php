<?php

session_start();

if (isset($_POST['where'])){
    if ( $_POST['where'] == '1'){
        header("Location: index.php");
        return;
    }
    if ( $_POST['where'] == '2'){
        header("Location: redirect2.php?param=47");
        return;
    }
    else {
        header("Location: redirect3.php");
        return;
    }
}
?>

<html>
<body>
<h1>content</h1>
<form method="post">
<p><label for="in9">Where to go? (1-3)</label>
<input type="text" name="where" id="in9" size="5"></p>
<input type="submit">
</form>
</body>
</html>