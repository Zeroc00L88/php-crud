<?php
$servername = 'db:3306';
$username = 'db';
$password = 'db';

function dbConnect($connect, $servername, $username, $password)
{
    if($connect) {
        try {
            $db = new PDO("mysql:host=$servername; dbname=db", $username, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<p id='dbConnect'>db connection OK</p>";
            return $db;
        } catch (PDOException $e) {
            echo "Error : " . $e -> getMessage();
            return $db;
        }
    } else {
        $db = null;
        return $db;
    }
}

function createUser($db, $firstName, $familyName, $eMail)
{
    try {
        $sql = $db -> prepare(
            "INSERT INTO user(FirstName, FamilyName, Email)
            VALUES (:FirstName, :FamilyName, :Email)"
        );
        $sql -> bindParam(':FirstName', $firstName);
        $sql -> bindParam(':FamilyName', $familyName);
        $sql -> bindParam(':Email', $eMail);

        $sql -> execute();
    } catch (PDOException $e) {
        $db -> rollback();
        echo "Error : " . $e -> getMessage();
    }
}

function readAllUsers($db)
{
    try {
        $sql = $db -> prepare(
            "SELECT * FROM user"
        );
        $sql -> execute();
        $res = $sql -> fetchAll(PDO::FETCH_ASSOC);
        return $res;
    } catch (PDOException $e) {
        echo "Error : " . $e -> getMessage();
    }
}

function deleteUser($db, $id)
{
    try {
        $sql = $db -> prepare(
            "DELETE FROM user WHERE id = $id"
        );
        $sql -> execute();
    } catch (PDOException $e) {
        $db -> rollback();
        echo "Error : " . $e -> getMessage();
    }
}

function displayUserList($userList)
{
    $headerCols = count($userList[0]) + 1;
    echo "<div class='row header'>";
    foreach ($userList[0] as $key => $value) {
        echo "<div class='cell header'>$key</div>";
    }
    echo "<div class='cell header'>Action</div>";
    echo "</div>";
    foreach ($userList as $value) {
        echo "<div class='row'>";
        foreach ($value as $key => $v) {
            echo "<div class='cell'>$v</div>";
            if($key == "id") {
                $id = $v;
            }
        }
        echo "<div class='cell'>
                <form method='POST'>
                    <button type='submit' name='delete' value='$id'>Delete</button>
                <form>
            </div>";
        echo "</div>";
    }
}

?>

<!doctype html>
<html lang="en">
    <head>
        <title>PHP CRUD</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link href="./assets/css/style.css" rel="stylesheet" />
    </head>

    <body>
        <header>
            <h1>PHP CRUD</h1>
<?php
echo "<p>DB State : </p>";
$db = dbConnect(true, $servername, $username, $password);
if (isset($_POST["subBtn"])) {
    createUser($db, $_POST["firstName"], $_POST["name"], $_POST["mail"]);
}
?>
        </header>
        <main>
            <div id="formContainer">
                <form method="POST">
                    <div>
                        <label for="firstName">First Name</label>
                        <input id="firstName" type="text" name="firstName" value="">
                    </div>
                    <div>
                        <label for="name">Name</label>
                        <input id="name" type="text" name="name" value="">
                    </div>
                    <div>
                        <label for="mail">Email</label>
                        <input id="mail" type="text" name="mail" value="">
                    </div>
                    <div id="subBtnContainer">
                        <input id="subBtn" type="submit" name="subBtn" value="Create User">
                    </div>
                </form>
            </div>
            <div id="usersContainer">
<?php

$AllUsers = readAllUsers($db);
displayUserList($AllUsers);
if (isset($_POST["delete"])) {
    deleteUser($db, $_POST["delete"]);
    header("refresh:0");
}

?>

            </div>
        </main>
    </body>
</html>
