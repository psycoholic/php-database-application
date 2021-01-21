<html>
    <h1>Hello worldie</h1>
</html>

<?php
require_once "pdo.php";

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])){ 
   $sql = "INSERT INTO users (name,email,password) VALUES(:name, :email, :password)";

    echo ("<pre>\n " .$sql. "</pre>");

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':name' => $_POST['name'],
        ':email' => $_POST['email'],
        ':password' => $_POST['password']
    ));
}

if (isset($_POST['user_id'])){
    $sql = "DELETE FROM users where user_id = :zip";
    echo("<pre>\n" .$sql."</pre>");
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array (
        ':zip' => $_POST['user_id']
    ));
}
?>

<html>
<head>
</head>
<body>
    <form method="post">
        <p>name : <input type="text" name="name" size="40"></p>
        <p>email : <input type="email" name="email"></p>
        <p>password : <input type="password" name="password"></p>
        <p><input type="submit" value="add new"></p>
    </form>
    <br>

<h1>Delete user</h1>
<form method="post">
<p>ID : to delete</p>
<input type="text" name="user_id">
<input type="submit" value="delete">
</form>



<h2>Users</h2>
    <?php
    echo("<pre>\n");
    $stmt = $pdo->query("SELECT * FROM USERS");
    echo('<table border="1">'."\n");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<tr><td>";
        echo($row['user_id']);
        echo "</td><td></td>";
        echo $row['name'];
        echo "</td><td>";
        echo $row['email'];
        echo "</td><td>";
        echo $row['password'];
        echo "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    echo("</pre>\n");

    ?>
    
    <?php
    try{
        $stmt = $pdo->prepare("SELECT * FROM USERS WHERE user_id = :xyz");
        $stmt->execute(array(":pizza" => $_GET['user_id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false){
            echo "<p>User not found</p>\n";
        }else{
            echo "<p>User found</p>\n";
        }
    }
    catch(Exception $ex){
        echo("Internal error, please contact support");
        error_log("error4.php, SQL error =" .$ex->getMessage());
        return;
    }
       
    ?>
</body>
</html>

