<?php
  require '../initialization/dbconnection.php';
  require "../profiles/tokenize.php";

  $current_user = $_SESSION['current_user'];

  $query = "SELECT * FROM albums WHERE user_id='$current_user' ORDER BY 'id';";
  if (!$albums = $mysqli->query($query)){
       echo $mysqli->error;
  }

?>

<!DOCTYPE html>
<html>
<head><?php include '../shared/meta.php'; ?></head>
<body>
  <div class="container">
    <?php include '../shared/header.php'; ?>
    <?php include '../shared/menuProfile.php'; ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <?php if($albums->num_rows != 0){
          while($ra = $albums->fetch_object()){ ?>
            <div class="col-sm-6 col-md-4">
              <div class="thumbnail">
                  <a href='album_selection.php?album_id=<?php echo $ra->id;?>'>
                  <img class="img-responsive img-rounded" src='<?php
                  if($ra->cover==null){
                    echo "../google-login/images/album.png";}
                  else {
                    echo "../uploads/".$ra->cover;
                  }?>'></a>
                <div class="caption">
                  <h3><?php echo $ra->title; ?></h3>
                </div>
              </div>
            </div>
          <?php }
          }
          else{ ?>
            <div class="col-sm-12">
              <div class="panel panel-default">
                <div class="panel-body">
                  <h4 class = 'text-center'>No albums to show</h4>
                </div>
              </div>
            </div>
        <?php  } ?>
      </div>
    </div>
  </div>
</body>
</html>
