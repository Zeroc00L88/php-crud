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
            echo "db connection OK";
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

// function createTable()
// {
//
// }

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
            $db = dbConnect(true, $servername, $username, $password);
?>
        </header>
        <main>
            <div>
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
                    <div>
                        <input id="subBtn" type="submit" name="subBtn" value="Submit">
                    </div>
                </form>
            </div>
        </main>
    </body>
</html>
