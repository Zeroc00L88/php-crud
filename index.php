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

function updateUser($db, $id)
{
    $firstName = $_POST['FirstName'];
    $familyName = $_POST['FamilyName'];
    $email = $_POST['Email'];
    try {
        $sql = $db -> prepare(
            "UPDATE user SET FirstName = '$firstName', FamilyName = '$familyName', Email = '$email' WHERE id = $id"
        );
        $sql -> execute();
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
        echo "Error : " . $e -> getMessage();
    }
}

function displayUserList($userList)
{
    echo "<div class='row header'>";
    $colCount = 1;
    foreach ($userList[0] as $key => $value) {
        echo "<div class='cell$colCount header'>$key</div>";
        $colCount++;
    }
    echo "<div class='cell5 header'>Action</div>";
    echo "</div>";
    foreach ($userList as $value) {
        echo "<div class='rowWrap'>";
        echo "<div class='row'>";
        $colCount = 1;
        foreach ($value as $key => $v) {
            echo "<div class='cell$colCount'>$v</div>";
            if($key == "id") {
                $id = $v;
            }
            $colCount++;
        }
        echo "<div class='cell5' id='actionContainer'>
                <form id='action' method='POST'>
                    <button id='update' type='submit' name='update' value='$id'>Update</button>
                    <button id='delete' type='submit' name='delete' value='$id'>Delete</button>
                <form>
            </div>";
        echo "</div>";
        if(isset($_POST["update"])) {
            if ($id == $_POST["update"]) {
                echo "<div class='updateRow'>";
                echo "<form method='POST'>";
                $colCount = 1;
                foreach ($value as $key => $v) {
                    if($key == "id") {
                        echo "<div class='cell$colCount'></div>";
                    } else {
                        echo "<div class='cell$colCount'>
                        <input type='text' name=$key value=$v>
                        </div>";
                    }
                    $colCount++;
                }
                echo "<div class='cell5'><button id='confirm' type='submit' name='confirm' value='$id'>Confirm</button></div>";
                echo "</form>";
                echo "</div>";
            }
        }
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
                        <input id="mail" type="email" name="mail" value="">
                    </div>
                    <div id="subBtnContainer">
                        <input id="subBtn" type="submit" name="subBtn" value="Create User">
                    </div>
                </form>
            </div>
            <div id="usersContainer">
<?php

if (isset($_POST["delete"])) {
    deleteUser($db, $_POST["delete"]);
    header("refresh:0");
}
if (isset($_POST["confirm"])) {
    updateUser($db, $_POST['confirm']);
    header("refresh:0");
}
displayUserList(readAllUsers($db));

?>

            </div>
        </main>
    </body>
</html>
