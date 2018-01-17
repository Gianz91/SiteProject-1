<?php
    require '../initialization/dbconnection.php';

    session_start();
    global $photo_id;

    $photo_id = $_GET['photo_id'];
    $_SESSION['photo_id'] = $photo_id;

    global $mysqli;
    $query = "SELECT name, user_id, description, (rate/votes) AS finalrate FROM photo WHERE id ='$photo_id';";
    if(!$result = $mysqli->query($query)){
        die($mysqli->error);
    }
    else{
        $obj = $result->fetch_object();
        $photo_name = $obj->name;
        $photographer_id = $obj->user_id;
        $desc = $obj->description;
        $rate = $obj->finalrate;
        if($rate == NULL){
            $rate = 0;
        }
    }

    $query = "SELECT firstname, email FROM login WHERE id = '$photographer_id';";
    $query .= "SELECT comments.comment, login.id, login.email, login.firstname FROM login INNER JOIN comments ON comments.user_id = login.id AND comments.photo_id = '$photo_id'";
    if ($mysqli->multi_query($query)){
      if($result = $mysqli->store_result()){
          $photographer = $result->fetch_object();
          if($photographer->firstname == NULL){
            $fuser = $photographer->email;
          }
          else{
            $fuser = $photographer->firstname;
          }
      }
      if($mysqli->next_result()){
        $comments = $mysqli->store_result();
      }
    }
    $mysqli -> close();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../shared/meta.php'; ?>
        <script src="https://apis.google.com/js/platform.js" async defer>
          {lang: 'en-GB'}
        </script>
    </head>
    <body>
      <div class="container">
        <?php include '../shared/header.php'; ?>
      <!-- Menu -->
        <?php include '../shared/menuProfile.php'; ?>
      <!-- Photo Div -->
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                <img class="img-responsive img-rounded" src="<?php echo "/uploads/" .$photo_name; ?>" alt="Immagine" class='img-responsive center-block'>
              </div>
              <div class="panel-body">
                <div class="col-md-6 text-center">
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="col-md-12 text-center">
                        <h3><b>Photographer:</b> <a href="../profiles/profile.php?user=<?php echo $photographer_id; ?>"><?php echo $fuser; ?></a></h3>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 text-center">
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="col-md-12 text-center center-block">
                        <p><b>Description:</b> <?php echo $desc; ?> </p>
                      </div>
                      <div class="col-md-6 center-block text-center">
                          <p><b>Rating:</b> <?php echo round($rate, 2); ?>/5</p>
                      </div>
                      <div class="col-md-6 text-center">
                        <div class="g-plus" data-action="share" data-height="24" data-href="<?php echo "http://photolio.com/fotopage.php?photo=". $photo_id ?>"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <form action="saveComment.php" method="post">
                  <div class="col-md-12 text-center">
                    <div class="form-group">
                      <p><b>Rate:</b></p>
                      <label class="radio-inline">
                        <input type="radio" name="rate" id="inlineRadio1" value="1"> 1
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="rate" id="inlineRadio2" value="2"> 2
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="rate" id="inlineRadio3" value="3"> 3
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="rate" id="inlineRadio4" value="4"> 4
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="rate" id="inlineRadio5" value="5"> 5
                      </label>
                    </div>
                    <button class="btn btn-primary" type="submit">Add new vote</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3>Comments</h3>
              </div>
              <div class="panel-body">
                <div class="col-md-6 center-block">
                  <ul class="list-group">
                    <li class="list-group-item">
                      <?php if($comments->num_rows){
                          while($obj = $comments->fetch_object()){
                            if ($obj->firstname != NULL){
                              echo "<p><b><a href='../profiles/profile.php?user=" . $obj->id . "'>" . $obj->firstname . "</a></b>: " . $obj->comment . "</p>";
                            }
                            else{
                              echo "<p><b><a href='../profiles/profile.php?user=" . $obj->id . "'>" . $obj->email . "</a></b>: " . $obj->comment . "</p>";
                            }
                          }
                        }
                        else {
                          echo "<p>No comments yet. Be the first one to comment!!</p>";
                        }?>
                    </li>
                  </ul>
                </div>
                <form action="saveComment.php" method="post">
                  <div class="col-md-6 text-center">
                    <div class="form-group">
                      <textarea name="comment" id="insertComment" rows="3" class="form-control" placeholder="Comment..."></textarea>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 text-center">
                      <button class="btn btn-primary" type="submit">Add new comment</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </body>
</html>
