<?php

include("includes/init.php");
$title = "Image";
$food_id = filter_input(INPUT_GET, "food_id", FILTER_VALIDATE_INT);
$sql = "SELECT * FROM images WHERE id=:food_id";
$params = array(
    ':food_id' => $food_id,
);
$results = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
foreach ($results as $result) {
    $food_extension = $result['extension'];
}
$file_path = "uploads/images/" . $food_id . "." . $food_extension;

if (isset($_POST["delete_submit"])) {
    unlink($file_path);

    $sql1 = "DELETE FROM images WHERE id = :food_id";
    $params = array(':food_id' => $food_id);
    exec_sql_query($db, $sql1, $params);

    $sql2 = "DELETE FROM image_tags WHERE image_id=:food_id";
    $params = array(':food_id' => $food_id);
    exec_sql_query($db, $sql2, $params);

    header( "Location: index.php" );
    exit ;

}

?>
<!DOCTYPE html>
<html lang="en">
<?php include('includes/head.php');?>
<body>
    <header>
        <nav>
        <a href = "index.php">Home</a>
        </nav>
    </header>
    <a href = "index.php"><h1>Koko Food Gallery</h1></a>

  <main>
    <?php
       $food_id = filter_input(INPUT_GET, "food_id", FILTER_VALIDATE_INT);
        $sql = "SELECT * FROM images WHERE id = :food_id";
        $params = array(
            ':food_id' => $food_id,
        );
        $foods = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        foreach($foods as $food){
            $food = $food;
        }
        if (is_null($food["link"])) {
            $source = "<p>" . $food["source"] . "</p>";
        } else {
            $source = "<a href='" . $food["link"] . "'>" . "<p>" . $food["source"] . "</p></a>";
        }
    ?>
    <h2>Food: <?php
        echo(htmlspecialchars($food['name']));
    ?></h2>
    <div class = "image_main">
        <div class = "single_image">
            <?php echo "<img src=\"uploads/images/" . htmlspecialchars($food["id"]) . "." . htmlspecialchars($food["extension"]) . "\"/>"; ?>
        </div>
        <div class = "single_description">
            <div class = "description">
                <p class = "description_title">Name: </p>
                <p class = "image_t"><?php echo htmlspecialchars($food["name"]); ?></p>
            </div>
            <div class = "description">
                <p class = "description_title">Description: </p>
                <p class = "image_t"><?php echo htmlspecialchars($food["description"]); ?></p>
            </div>
            <div class = "description">
                <p class = "description_title">Extension: </p>
                <p class = "image_t"><?php echo htmlspecialchars($food["extension"]); ?></p>
            </div>
            <div class = "description">
                <p class = "description_title">Source: </p>
                <?php echo $source; ?>
            </div>
            <div class = "description">
                <p class = "description_title">Tags: </p>
                <ul>
                    <?php
                    $sql = "SELECT * FROM tags INNER JOIN image_tags ON tags.id = image_tags.tag_id WHERE image_tags.image_id = :food_id";
                    $params = array(
                        ':food_id' => $food_id,
                    );
                    $tags = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
                    foreach($tags as $tag) {
                        ?>
                        <li><a class="image_tag_list" href="tag.php?<?php echo http_build_query(array('tag_id'=>strtolower($tag['tag_id']))); ?>"><?php echo htmlspecialchars($tag['name'])?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class = "image_sidebar">
            <form id = "delete" method = "post" action = "image.php?<?php echo http_build_query(array('food_id'=>strtolower($food_id))); ?>">
                <button class="delete_button" name="delete_submit" type="submit">Delete</button>
            </form>

            <a class = "edit_button" href="edit.php?<?php echo http_build_query(array('image_id'=>strtolower($food_id))); ?>">Edit Tags</a>


        </div>
    </div>
  </main>
</body>

</html>
