<?php

include 'Sql.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$db = new Sql();
$mysqli = new mysqli($db->host, $db->user, $db->password, $db->database);

if ($mysqli->connect_errno) {
    echo "99";
} else {

    $result = $mysqli->query("CALL sp_login('$request->usuario','$request->contrasena')");
    if ($result->num_rows > 0) {
        $json = [];
        while ($row = $result->fetch_assoc()) {
            $json[] = $row;
        }
        if (session_start()) {
            session_start();
            $_SESSION["usuario"] = $json;
            echo json_encode($json);
        }else{
            echo '99';
        }
    } else {
        echo '0';
    }
    $mysqli->close();
}
?>