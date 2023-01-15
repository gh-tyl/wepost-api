<?php
include "../common/header.php";
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $pass = $_POST["pass"];
    $db = new dbServices($mysql_host, $mysql_username, $mysql_password, $mysql_database);
    if ($dbCon = $db->dbConnect()) {
        $userInfo = $db->select('user_table', array('*'), "email='$email'"); //Get the user login info in db
        if ($userInfo) {
            $userInfo = $userInfo->fetch_assoc(); //transform sql query result in associative array
            if ($userInfo['password'] == $pass) { //Check form pass with password from db
                $_SESSION['logUser'] = $userInfo;
                echo '
                    {
                        "statusCode": 200, 
                        "status": "success",
                        "message": "Login successful"
                        "isHashed": false
                    }
                ';
                exit();
            } else {
                $hashPass = password_verify($pass, $userInfo['password']); //verify password. If returns true means that password is correct
                if ($hashPass) { //On correct password
                    $_SESSION['logUser'] = $userInfo;
                    // echo "Pass already hashed";
                    echo '
                        {
                            "statusCode": 200,
                            "status": "success",
                            "message": "Login successful"
                            "isHashed": true
                        }
                    ';
                    exit();
                }
            }
        }
        $dbcon->close();
        echo '
            {
                "statusCode": 400,
                "status": "error",
                "message": "Wrong email or password"
            }
        '; //Will run on password wrong or email that is not on db
    } else {
        echo '
            {
                "statusCode": 500,
                "status": "error",
                "message": "Internal server error"
            }
        ';
    }
}
?>