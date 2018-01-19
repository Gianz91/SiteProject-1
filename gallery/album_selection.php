<?php
require '../initialization/dbconnection.php';
session_start();

$album =$_GET['album_id'];

$query= "SELECT title, photos_id, cover from albums where id ='$album'";

if(!$result = $mysqli->query($query)){
    die($mysqli->error);
}
$obj = $result->fetch_object();
$_SESSION['album'] = $obj;
$mysqli->close();
header('Location: ../gallery/album_page.php');
session_write_close();
?>