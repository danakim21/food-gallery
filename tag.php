<?php
include("includes/init.php");
$title = "Tag";

function show_image($food) { ?>
    <div class="tag_image">
      <a href="image.php?<?php echo http_build_query(array('food_id' => strtolower($food['image_id']))); ?>">
        <img src="uploads/images/<?php echo htmlspecialchars($food['image_id']) . "." . htmlspecialchars($food['extension']); ?>" alt="<?php echo htmlspecialchars($food['name']); ?>"/>
      </a>
      <p class="image_name"><?php echo htmlspecialchars($food['name']) ?></p>
      <?php
        if (is_null($food["link"])) { ?>
          <p class="image_source"><?php echo htmlspecialchars($food['source']) ?></p>
        <?php } else { ?>
          <a href="<?php echo htmlspecialchars($food['link'])?>"><p class="image_source"><?php echo htmlspecialchars($food['source']) ?></p><a>
        <?php }
      ?>
    </div>
  <?php }
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
        $tag_id = filter_input(INPUT_GET, "tag_id", FILTER_VALIDATE_INT);
        // Get Name
        $sql = "SELECT * FROM tags WHERE id = :tag_id";
        $params = array(':tag_id' => $tag_id,);
        $tag_names = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        foreach($tag_names as $tag_name) {
            $tag_name = $tag_name['name'];
        }
        ?>
        <h2>Tag: <?php
        echo(htmlspecialchars($tag_name));
        ?></h2>
        <div class = "tag_body">
            <div class = "tag_images">
                <?php
                    // Join tag and images
                    $sql = "SELECT * FROM images INNER JOIN image_tags ON images.id = image_tags.image_id WHERE image_tags.tag_id = :tag_id";
                    $params = array(
                        ':tag_id' => $tag_id,
                    );
                    $foods = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($foods as $food) {
                        show_image($food);
                    }
                ?>
            </div>
            <div class = "tag_sidebar">
                 <a href = "upload.php" class = "upload_button">+ Upload</a>

                <div class = "tags">
                    <h3>Tags</h3>
                    <!-- place tags from database -->
                    <ul>
                    <?php
                        $tags = exec_sql_query($db, "SELECT * FROM tags")->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($tags as $tag) {
                        ?><li><a href="tag.php?<?php echo http_build_query(array('tag_id'=>strtolower($tag['id']))); ?>"><?php echo htmlspecialchars($tag['name']) ?></a></li>
                        <?php
                        }
                        ?>
                    <ul>
                </div>
            </div>

        </div>
    </main>

</body>


</html>
