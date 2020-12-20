<?php
include("includes/init.php");
$title = "Edit";
$messages = array();

$food_id = filter_input(INPUT_GET, "image_id", FILTER_VALIDATE_INT);


// add existing tag
if (isset($_POST["addTag"])) {
  $addtag = filter_input(INPUT_POST, "addtag", FILTER_VALIDATE_INT);
  // check whether tag is from tag list
  $tag_valid = True;
  $sql = "SELECT * FROM tags WHERE id = :id";
  $params = array(':id' => $addtag);
  $result = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
  if (empty($result)) {
    $tag_valid = False;
  }
  // if tag is from tag list, then check if relationship already exist
  if ($tag_valid) {
    $sql1 = "SELECT * FROM image_tags WHERE image_id = :image_id AND tag_id = :tag_id";
    $params1 = array(':image_id' => $food_id, ':tag_id' => $addtag);
    $result1 = exec_sql_query($db, $sql1, $params1)->fetchAll(PDO::FETCH_ASSOC);
    $okay2addtag = True;
    if ($result1) {
      $okay2addtag = False;
    }
    // if relatinoship doesn't exist, then insert relationship
    if ($okay2addtag) {
      $sql2 = "INSERT INTO image_tags (image_id, tag_id) VALUES (:image_id, :tag_id)";
      $params2 = array(
          ':image_id' => $food_id,
          ':tag_id' => $addtag,
      );
      $result2 = exec_sql_query($db, $sql2, $params2);
      if ($result2) {
        array_push($messages, "Successfully uploaded new tag!");
      } else {
        array_push($messages, "Failed to upload a new tag");
      }
    } else {
      array_push($messages, "Cannot add an existing tag. ");
    }
  }
}

// add new tag
if (isset($_POST["newTag"])) {
  $newtagname = filter_input(INPUT_POST, "newtag", FILTER_SANITIZE_STRING);
  // check if tag exist
  $sql = "SELECT * FROM tags WHERE lower(name)=:name";
  $params = array(':name' => strtolower($newtagname));
  $result = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
  $okay2addnewtag = True;
  if ($result) {
    $okay2addnewtag = False;
  }

  // it is a new tag
  if ($okay2addnewtag) {
    $sql2 = "INSERT INTO tags (name) VALUES (:name)";
    $params2 = array(':name' => $newtagname);
    $result2 = exec_sql_query($db, $sql2, $params2);
    // inserted into tags
    if ($result2) {
      $newtagid = $db->lastInsertId("id");
      $sql3 = "INSERT INTO image_tags (image_id, tag_id) VALUES (:image_id, :tag_id)";
      $params3 = array(
        ':image_id' => $food_id,
        ':tag_id' => $newtagid,
      );
      $result3 = exec_sql_query($db, $sql3, $params3);
      // inserted into image_tags
      if ($result3) {
        array_push($messages, "Successfully uploaded new tag!");
      } else {
        array_push($messages, "Failed to upload a new tag");
      }
    } else {
      array_push($messages, "Failed to upload a new tag");
    }
  } else {
    array_push($messages, "It is an existing tag");
  }
}

// delete existing tag
if (isset($_POST["deleteTag"])) {
  $deletetag = filter_input(INPUT_POST, "deletetag", FILTER_VALIDATE_INT);
  $sql = "DELETE FROM image_tags WHERE (image_id = :image_id) AND (tag_id = :tag_id)";
  $params = array(
    ':image_id' => $food_id,
    ':tag_id' => $deletetag,
  );
  $results = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
  array_push($messages, "Successfully removed a tag");

}

?>
<!DOCTYPE html>
<html lang="en">
<?php include('includes/head.php'); ?>
<body>
  <header>
    <nav>
      <a href = "index.php">Home</a>
    </nav>
  </header>
    <a href = "index.php"><h1>Koko Food Gallery</h1></a>

  <main>
    <?php
        $food_id = filter_input(INPUT_GET, "image_id", FILTER_VALIDATE_INT);

        $foods = exec_sql_query($db, "SELECT * FROM images WHERE id=$food_id")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($foods as $food) {
    ?>
    <h2>Edit tag for <?php echo htmlspecialchars($food['name'])?></h2><?php } ?>

    <div class = "edit-body">
      <div class = "edit-left">
        <h3>Current Tags</h3>
        <ul>
            <?php
            $sql = "SELECT * FROM tags INNER JOIN image_tags ON tags.id = image_tags.tag_id WHERE image_tags.image_id = :food_id";
            $params = array(
                ':food_id' => $food_id,
            );
            $tags = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
            foreach($tags as $tag) {
                ?>
                <li><a class="edit-tag-list" href="tag.php?<?php echo http_build_query(array('tag_id'=>strtolower($tag['tag_id']))); ?>"><?php echo htmlspecialchars($tag['name'])?></a></li>
                <?php
            }
            ?>
        </ul>
      </div>

      <div class = "edit-right">

        <?php
        foreach ($messages as $message) {
            echo "<p class='edit_text'>" . htmlspecialchars($message) . "<p>";
        }
        ?>
        <!-- Add existing tag  -->
        <h3 class = "edit-h3">Add tag</h3>
        <form id = "addTag" method = "post" action = "edit.php?<?php echo http_build_query(array('image_id'=>strtolower($food_id)));?>">
          <div class = "form-label">
              <label class="upload_label" for="upload-tag">Tag: </label>
              <select name="addtag">
              <?php

                $sql = "SELECT * FROM tags WHERE tags.id NOT IN (SELECT tag_id FROM image_tags WHERE image_id = :id)";
                $params = array(':id' => $food_id);
                // $tags = exec_sql_query($db, "SELECT * FROM tags")->fetchAll(PDO::FETCH_ASSOC);
                $tags = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
                foreach ($tags as $tag) {
                  ?><option value = "<?php echo htmlspecialchars($tag['id']) ?>"><?php echo htmlspecialchars($tag['name']) ?></option>
                  <?php
                }
                ?>
              </select>
          </div>
          <button class="addTag" name="addTag" type="submit">Add Tag</button>
        </form>
        <!-- Add new tag -->
        <h3 class = "edit-h3">Create and add new tag</h3>
        <form id = "newTag" method = "post" action = "edit.php?<?php echo http_build_query(array('image_id'=>strtolower($food_id)));?>">
          <div class = "form-label">
              <label class="upload_label" for="upload-tag">Tag: </label>
              <input class="upload_input" id="newtag" type="text" name="newtag"/>
          </div>
          <button class="newTag" name="newTag" type="submit">Add New Tag</button>
        </form>
        <!-- Delete tag  -->
        <h3 class = "edit-h3">Remove existing tag</h3>
        <form id = "deleteTag" method = "post" action = "edit.php?<?php echo http_build_query(array('image_id'=>strtolower($food_id)));?>">
          <div class = "form-label">
              <label class="upload_label" for="delete-tag">Tag: </label>
              <select name="deletetag">
              <?php
                $sql = "SELECT * FROM tags INNER JOIN image_tags ON tags.id=image_tags.tag_id WHERE image_id=:image_id";
                $params = array(':image_id' => $food_id);
                $tags = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
                foreach ($tags as $tag) {
                  ?><option value = "<?php echo htmlspecialchars($tag['tag_id']) ?>"><?php echo htmlspecialchars($tag['name']) ?></option>
                  <?php
                }
                ?>
              </select>
          </div>
          <button class="deleteTag" name="deleteTag" type="submit">Remove Existing Tag</button>
        </form>
      </div>
    </div>

  </main>
</body>

</html>
