<?php
include("../../config/config.php");
include("../../services/db.php");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Header: *');
header('Content-Type: application/json');
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $db = new dbServices($mysql_host, $mysql_username, $mysql_password, $mysql_database);
    $dbConnected = $db->connect();
    if ($dbConnected) {
        $articles = $dbConnected->query("SELECT a.id,a.title,a.content_path,a.genre_id_01,a.genre_id_02,a.genre_id_03,a.likes,a.stores,a.datetime,u.first_name,u.last_name,u.email,g.genre FROM article_table a INNER JOIN user_table u ON u.id = a.user_id INNER JOIN genre_table g ON g.id = a.genre_id_01 ORDER BY `id` ASC");
        $articles = $articles->fetch_all(MYSQLI_ASSOC);
        $db->close();
        if ($articles) {
            $res = array(
                "statusCode" => 200,
                "status" => "success",
                "data" => array(
                    "articles" => $articles
                )
            );
            echo json_encode($res);
            exit();
        } else {
            $res = array(
                "statusCode" => 204,
                "status" => "No Content",
                "data" => array(
                    "articles" => []
                )
            );
            echo json_encode($res);
            exit();
        }
    } else {
        $res = array(
            "statusCode" => 500,
            "status" => "Internal Server Error",
            "data" => array(
                "articles" => []
            )
        );
        echo json_encode($res);
        exit();
    }
}
?>