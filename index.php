<?php
include("includes/init.php");
$title = "Home";
function show_image($food) { ?>
  <div class="index_image">
    <a href="image.php?<?php echo http_build_query(array('food_id' => strtolower($food['id']))); ?>">
      <img src="uploads/images/<?php echo htmlspecialchars($food['id']) . "." . htmlspecialchars($food['extension']); ?>" alt="<?php echo htmlspecialchars($food['name']); ?>"/>
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
<?php
include('includes/head.php');
?>

<body>
  <header>
    <nav>
      <a href = "index.php">Home</a>
    </nav>
  </header>
  <a href = "index.php"><h1>Koko Food Gallery</h1></a>
  <h2>Welcome to KoKo Food Gallery</h2>

  <main class = "index_main">
    <div class = "image_grid">
      <div class = "index_images">
        <?php
        $foods = exec_sql_query($db, "SELECT * FROM images")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($foods as $food) {
          show_image($food);
        }
        ?>
      </div>

    </div>
    <div class = "index_sidebar">
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

  </main>
</body>

</html>
