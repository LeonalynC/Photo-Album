<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Albums</title>
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        .photoContainer {
            display: flex; 
            flex-wrap: wrap; 
            justify-content: center; 
            margin-top: 10px; 
        }
        .photoContainer img {
            width: 100%; 
            max-width: 150px; 
            margin: 5px; 
            border-radius: 5px; 
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="createAlbumForm" style="display: flex; justify-content: center; margin-bottom: 20px;">
        <form action="core/handleForms.php" method="POST">
            <p>
                <label for="album_name">Album Name</label>
                <input type="text" name="album_name" class="short-input" required> 
                <input type="submit" name="insertAlbumBtn" value="Create Album" style="margin-top: 10px;">
            </p>
        </form>
    </div>

    <div class="albumList" style="display: flex; flex-direction: column; align-items: center; margin-top: 20px;">
        <h2>Your Albums</h2>
        <?php 
        $albums = getAllAlbums($pdo, $_SESSION['username']); 
        foreach ($albums as $album) { ?>
            <div class="albumBox" style="border: 1px solid pink; border-radius: 5px; margin: 10px; padding: 10px; text-align: center;">
                <h3><?php echo htmlspecialchars($album['album_name']); ?></h3>
                <div class="photoContainer">
                    <?php 
                   
                    $photos = getAllPhotos($pdo, $_SESSION['username'], $album['album_id']); 
                    foreach ($photos as $photo) { ?>
                        <img src="images/<?php echo $photo['photo_name']; ?>" alt="">
                    <?php } ?>
                </div>
                <form action="core/handleForms.php" method="POST" style="display: flex; margin-top: 10px;">
                    <input type="hidden" name="album_id" value="<?php echo $album['album_id']; ?>">
                    <input type="text" name="album_name" value="<?php echo $album['album_name']; ?>" class="short-input" required> 
                    <input type="submit" name="updateAlbumBtn" value="Update" style="margin-left: 5px;">
                    <input type="submit" name="deleteAlbumBtn" value="Delete" style="margin-left: 5px;">
                </form>
            </div>
        <?php } ?>
    </div>

    <div class="insertPhotoForm" style="display: flex; justify-content: center;">
        <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
            <p>
                <label for="#">Description</label>
                <input type="text" name="photoDescription" class="short-input" required>
            </p>
            <p>
                <label for="#">Album</label>
                <select name="album_id" required class="short-input">
                    <option value="">Select Album</option>
                    <?php foreach ($albums as $album) { ?>
                        <option value="<?php echo $album['album_id']; ?>"><?php echo $album['album_name']; ?></option>
                    <?php } ?>
                </select>
            </p>
            <p>
                <label for="#">Photo Upload</label>
                <input type="file" name="image" required class="short-input">
                <input type="submit" name="insertPhotoBtn" value="Upload Photo" style="margin-top: 10px;">
            </p>
        </form>
    </div>

    <?php
    
    if (isset($_SESSION['message'])) {
        echo '<div class="alert ' . ($_SESSION['status'] == '400' ? 'alert-danger' : 'alert-success') . '">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']); 
    }
    ?>
</body>
</html>
