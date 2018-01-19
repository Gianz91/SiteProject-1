<?php 
require '../initialization/dbconnection.php';
require  '../profiles/tokenize.php';

session_start();
$album = $_SESSION['album'];

if($album == NULL){
    echo "error, album not selected";
}
$idS = $album->photos_id; 
$queryfinal = "SELECT name,description FROM photo ";
$queryfinal .= tokenize($idS,"|");;

global $mysqli;
if(!$result = $mysqli->query($queryfinal)){
    echo $mysqli->error;
}
$result->data_seek(0);

$mysqli->close();
session_write_close();
 
?>
<html>
    <head>
        <?php include '../shared/meta.php'; ?>
        <script src="https://apis.google.com/js/platform.js" async defer>
          {lang: 'en-GB'}
        </script>
    </head>
    <body>
        <div class="container">
            <!-- Header -->
            <?php include '../shared/header.php' ?>
            <!-- Menu -->
            <?php include '../shared/menuProfile.php'; ?>
            <!-- Album's Photo Grid -->
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="row">
                  <?php  while($obj = $result->fetch_object()){ ?>
                    <div class="col-sm-6 col-md-4">
                      <div class="thumbnail">
                        <a href="../photo_page/comments.php?photo_id=<?php echo $obj->id;?>">
                          <img class="img-responsive img-rounded" src="<?php echo "../uploads/".$obj->name; ?>" alt="Immagine">
                        </a>
                        <div class="caption text-center">
                          <p><b><?php echo $obj->description; ?></b></p>
                          <div class="g-plus" data-action="share" data-height="24" data-href="<?php echo "http://photolio.com/photo_page/fotopage.php?photo=". $obj->name; ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
        </div>
    </body>
</html>
