<?php
//le foto andranno caricate da database tramite php
include ('dbconnection.php');

session_start();

//se non si e' loggati si viene reindirizzati nella pagina di registrazione/login
if(!isset($_SESSION["utente"])){
    header("Location: /index.php");
}

//global $mysqli;
$query = "SELECT name, description, rate, votes FROM photo ORDER BY id LIMIT 50;";
if (!$result = $mysqli->query($query)){
     echo "Errore nella query";
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>Home</title>
<meta charset="UTF-8">

<style>
ul {
    position: -webkit-sticky;
    position: sticky;
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

li {
    float: left;
}

li a {
    display: block;
    padding: 8px;
    background-color: #dddddd;
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
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/it_IT/sdk.js#xfbml=1&version=v2.10&appId=139108946732772';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<header>
  <h1><b>PHOTOLIO</b></h1>
  <p><b>A site for photo sharing</b></p>
</header>

<!-- Menu -->
<ul>
  <li><a href="<?php echo "/home.php?user=" .$_SESSION["utente"] ?>" >Home</a></li>
  <li><a href="uploadFile.html">Load Image</a></li>
  <li><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Ftest.com%2F&amp;src=sdkpreparse">Share Us</a></li>
  <li><a href="logOut.php">Log Out</a></li>
</ul>

<!-- Photo Grid -->
<div><?php /*Fetch object array */
    while($obj = $result->fetch_object()){ ?>
      <div class="gallery">
        <a href="/comments.php?photo=<?php echo $obj->name?>">
          <img src="<?php echo "/uploads/".$obj->name ?>" alt="Immagine" width="300" height="200">
        </a>
        <div class="desc"><?php echo $obj->description ?> | 
        	<div class="fb-share-button" data-href="http://photolio.com/fotopage.php?photo="<?php echo $obj->name ?>
                    data-layout="button_count" data-size="small" data-mobile-iframe="true">
        		<a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Ftest.com%2Fcomments.php%3Fphoto%3D<?php echo $obj->name?>&amp;src=sdkpreparse">Condividi</a>
        	</div>
        </div>
      </div>
    <?php } ?>
</div>

</body>
</html>

