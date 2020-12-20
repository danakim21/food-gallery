<?php
include("includes/init.php");
$title = "Upload";
$messages = array();
const MAX_FILE_SIZE = 1000000;

$show_name_feedback = False;
$show_extension_feedback = False;
$show_source_feedback = False;

if (isset($_POST["upload_submit"])) {
   $upload_file = $_FILES["file"];
   $upload_ok = True;
   // file is uploaded
    if ($upload_file['error'] == UPLOAD_ERR_OK) {

        $upload_name = trim(filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING));
        if (empty($upload_name)) {
            $upload_ok = False;
            $show_name_feedback = True;
        }

        $upload_description = trim(filter_input(INPUT_POST, "description", FILTER_SANITIZE_STRING));

        $upload_extension = trim(filter_input(INPUT_POST, "extension", FILTER_SANITIZE_STRING));
        if (empty($upload_extension)) {
            $upload_ok = False;
            $show_extension_feedback = True;
        }

        $upload_source = trim(filter_input(INPUT_POST, "source", FILTER_SANITIZE_STRING));
        if (empty($upload_source)) {
            $upload_ok = False;
            $show_source_feedback = True;
        }

        $upload_link = trim(filter_input(INPUT_POST, "link", FILTER_SANITIZE_STRING));
        $upload_tag = $_POST['tag'];

        if (empty($upload_description)) {
            $upload_description = NULL;
        }
        if (empty($upload_link)) {
            $upload_link = NULL;
        }
        // insert into images
        if ($upload_ok) {
            $sql1 = "INSERT INTO images (name, extension, description, source, link) VALUES (:name, :extension, :description, :source, :link)";
            $params1 = array(
                ':name' => $upload_name,
                ':extension' => $upload_extension,
                ':description' => $upload_description,
                ':source' => $upload_source,
                ':link' => $upload_link,
            );
            $result1 = exec_sql_query($db, $sql1, $params1);
            // insert sql complete
            if ($result1) {
                $last_id = $db->lastInsertId("id");
                $file_location = 'uploads/images/' . $last_id . "." . $upload_extension;
                // move file complete
                if (move_uploaded_file($upload_file["tmp_name"], $file_location)) {
                    // after uploading image, insert tags into image_tags
                    $result2 = True;
                    foreach ($upload_tag as $tag) {
                        $sql2 = "INSERT INTO image_tags (image_id, tag_id) VALUES (:image_id, :tag_id)";
                        $params2 = array(
                            ':image_id' => $last_id,
                            ':tag_id' => $tag,
                        );
                        if (!exec_sql_query($db, $sql2, $params2)) {
                            $result2 = False;
                        }
                    }
                    if ($result2) {
                        array_push($messages, "Successfully uploaded new food!");
                    } else {
                        array_push($messages, "Failed to upload new food.");
                    }
                } else {
                    array_push($messages, "Failed to upload new food.");
                }
            } else {
                array_push($messages, "Failed to upload new food.");
            }
        } else {
            array_push($messages, "Failed to upload new food. Fill in all the required fields.");
        }
    } else {
        array_push($messages, "Failed to upload new food.");
    }
}

function upload_tag($tag){ ?>
    <div>
        <input type="checkbox" id="<?php echo htmlspecialchars($tag['id'])?>" name="tag[]" value="<?php echo htmlspecialchars($tag['name']) ?>">
        <label for="<?php echo htmlspecialchars($tag['name']) ?>"><?php echo htmlspecialchars($tag['name']) ?></label>
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
    <h2 class = "upload_title">Upload a New Image</h2>
    <?php
    foreach ($messages as $message) {
        echo "<p class='upload_text'>" . htmlspecialchars($message) . "<p>";
    }
    ?>
    <div class = "upload_form">
        <form id = "upload" method = "post" action = "upload.php" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
            <div class = "form-label">
                <label class="upload_label" for="upload-file">Upload Image File: </label>
                <input class="upload_input" id="upload-file" type="file" name="file"/>
            </div>
            <div class = "form-label">
                <label class="upload_label" for="upload-name">Name: </label>
                <input class="upload_input" id="upload-name" type="text" name="name"/>
                <span class="<?php echo ($show_name_feedback==True) ? 'errorContainer' : 'hidden'; ?>">Please provide a valid name.</span>
            </div>
            <div class = "form-label">
                <label class="upload_label" for="upload-description">Description: </label>
                <input class="upload_input" id="upload-description" type="text" name="description"/>
            </div>
            <div class = "form-label">
                <label class="upload_label" for="upload-extension">Extension: </label>
                <input class="upload_input" id="upload-extension" type="text" name="extension"/>
                <span class="<?php echo ($show_extension_feedback==True) ? 'errorContainer' : 'hidden'; ?>">Please provide a extension name.</span>
            </div>
            <div class = "form-label">
                <label class="upload_label" for="upload-source">Source: </label>
                <input class="upload_input" id="upload-source" type="text" name="source"/>
                <span class="<?php echo ($show_source_feedback==True) ? 'errorContainer' : 'hidden'; ?>">Please provide a valid source.</span>
            </div>
            <div class = "form-label">
                <label class="upload_label" for="upload-link">Link: </label>
                <input class="upload_input" id="upload-link" type="text" name="link"/>
            </div>
            <div class = "form-label">
                <label class="upload_label" for="upload-tags">Tags: </label>
                <div class = "form-tags">
                <!-- Place tags from database -->
                <?php
                    $tags = exec_sql_query($db, "SELECT * FROM tags")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($tags as $tag) {
                        ?>
                        <div>
                            <input type="checkbox" id="<?php echo htmlspecialchars($tag['id'])?>" name="tag[]" value="<?php echo htmlspecialchars($tag['id']) ?>">
                            <label for="<?php echo htmlspecialchars($tag['name']) ?>"><?php echo htmlspecialchars($tag['name']) ?></label>
                        </div>
                    <?php                    }

                ?>
                </div>
            </div>

            <button class="form_submit" name="upload_submit" type="submit">Upload</button>

        </form>
    </div>


  </main>
</body>

</html>
