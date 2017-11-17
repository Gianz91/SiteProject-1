<?php

include ('../dbconnection.php');
include("../tokenize.php");

session_start();
$name = $_GET['user'];

$date_right;
global $mysqli;
$query = "SELECT * from users where name= '$name';";
if(!isset($_GET['idS'])||$_GET['idS']== null){
$query .= "SELECT name, description from photo where user ='$name';";
}
else{
    $ids = $_GET['idS'];
    $querypart= tokenize($ids,"|");
    $query .= "SELECT name,description from photo ";
    $query .=$querypart;
}
$query .= "SELECT * from album where user='$name';";
$obj;
if ($mysqli->multi_query($query)){
    if($result = $mysqli->store_result())
        //Store first query result(profile info)
        $obj = $result->fetch_object();
    if($mysqli->next_result()){
        $resultfoto= $mysqli->store_result();
    }
    if($mysqli-> next_result()){
     $resultalbum =$mysqli->store_result();
    }
}
$_SESSION['profile'] = $obj;
$_SESSION['utente'] = $nome;
$date_from_sql = $obj->birth;
if($date_from_sql != null)
 $date_right = date('d-m-Y',strtotime($date_from_sql));
session_write_close();
?>


<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
<head>
<title><?php echo $obj->name;?>'s Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<meta charset="UTF-8">
<style>
    nav {
        float: left;
        max-width: 160px;
        margin: 0;
        padding: 1em;
    }

    nav ul {
        display: block;
        list-style-type: none;
        padding: 0;
    }
    div.profile_img{
        margin: left;
        border: 5px blue;
        padding: 10px;
    }
    div.profile_info{
        margin:left;
        border: 1px solid lightgray;
        padding: 5px;
        width: 200px;
        font-size: 10px;
    }
div.gallery {
    margin-top: 5px;
    border: 1px solid #ccc;
    width: 25%;
    float: left;
}

div.gallery:hover {
    border: 1px solid #777;
}

div.gallery img {
    width: 100%;
    height: auto;
}

div.desc {
    padding: 15px;
    text-align: center;
}
</style>
</head>
<body>

<header>
  <h1><b>PHOTOLIO</b></h1>
  <p><b>A site for photo sharing</b></p>
  <p>Hi, <?php echo $obj->name;?></p>
</header>
<!-- Profile Info -->

<div class="profile_img"><img style="border-radius: 50%; " src="<?php echo "profile_images/". $obj->profile_image;?>" width="75" height="75"></div>
<div class="profile_info">
    Nome e Cognome:<?php echo  $obj->firstname." ".$obj->lastname;?><br>
    Email: <?php echo $obj->email;?><br>
    Birth Date : <?php echo $date_right;?><br>
    Level : <?php echo $obj->level; ?>
</div>
<div class="profile_info">
    About Me: <?php echo $obj->description ?>
</div>
<!-- Menu -->
<?php include 'menuProfile.php' ?>
<!--Album's List-->
<nav>
    <div class="row">
        <div class='panel panel-default' style="width: fit-content;">
            <div class='panel-heading'><p>Your Albums</p>
                <div class='panel-body'>
                    <div class=" panel panel-default">
    <?php
        while($ra = $resultalbum->fetch_object()){
            ?>
        <div class='panel-heading'><ul><?php  echo $ra->titolo; ?></ul></div>
        <div class='panel-body' align="center">
            <a href='profile.php?user=<?php echo $_GET['user']?>&idS=<?php echo $ra->idFoto;?>&id=<?php echo$ra->id;?>'><img src='<?php if($ra->Cover==null){
        echo "../google-login/images/album.png";} else {
            echo "../uploads/".$ra->Cover;
            }?>' alt='Immagine' height="80" width="80"></a>
            </div>
        <?php
        } ?>        </div>
                </div>
            </div>
       </div>
    </div>

</nav>
<!--Gallery-->

<!-- Photo Grid -->
<div><?php /*Fetch object array */
    while($foto = $resultfoto->fetch_object()){ ?>
      <div class="gallery">
        <a href="http://photolio.com/comments.php?photo=<?php echo $foto->name?>">
          <img src="<?php echo "../uploads/". $foto->name; ?>" alt="Immagine" width="300" height="200" align="left">
        </a>
         <div class="desc"> <?php echo $foto->description ?> </br> <div class="g-plus" data-action="share" data-height="24"
                      data-href="<?php echo "http://photolio.com/fotopage.php?photo=". $foto->name ?>"></div>
        </div>
      </div>
    <?php } ?>
</div>
<script src="https://apis.google.com/js/platform.js" async defer>
  {lang: 'en-GB'}
</script>
</body>
</html>
