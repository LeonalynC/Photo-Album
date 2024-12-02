<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>
<?php  
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
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
        <form action="core/handleForms.php" method="POST" style="display: flex; margin-bottom: 10px;">
            <input type="hidden" name="album_id" value="<?php echo $album['album_id']; ?>">
            <input type="text" name="album_name" value="<?php echo $album['album_name']; ?>" class="short-input" required> 
            <input type="submit" name="updateAlbumBtn" value="Update" style="margin-left: 5px;">
            <input type="submit" name="deleteAlbumBtn" value="Delete" style="margin-left: 5px;">
        </form>
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
                <?php $albums = getAllAlbums($pdo, $_SESSION['username']); ?>
                <?php foreach ($albums as $album) { ?>
                    <option value="<?php echo $album['album_id']; ?>"><?php echo $album['album_name']; ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <label for="#">Photo Upload</label>
            <input type="file" name="image" required class="short-input">
            <input type="submit" name="insertPhotoBtn" style="margin-top: 10px;">
        </p>
    </form>
</div>

    <?php $getAllPhotos = getAllPhotos($pdo); ?>
    <?php foreach ($getAllPhotos as $row) { ?>
    <div class="images" style="display: flex; justify-content: center; margin-top: 25px;">
        <div class="photoContainer" style="background-color: ghostwhite; border-style: solid; border-color: gray;width: 50%;">
            <img src="images/<?php echo $row['photo_name']; ?>" alt="" style="width: 100%;">
            <div class="photoDescription" style="padding:25px;">
                <a href="profile.php?username=<?php echo $row['username']; ?>"><h2><?php echo $row['username']; ?></h2></a>
                <p><i><?php echo $row['date_added']; ?></i></p>
                <h4><?php echo $row['description']; ?></h4>
                <h5>Album: <?php echo isset($row['album_name']) ? $row['album_name'] : 'No Album'; ?></h5> 

                <?php if ($_SESSION['username'] == $row['username']) { ?>
    <a href="editphoto.php?photo_id=<?php echo $row['photo_id']; ?>" class="edit-link" style="float: right;"> Edit </a> 
    <br>
    <br>
    <a href="deletephoto.php?photo_id=<?php echo $row['photo_id']; ?>" class="delete-link" style="float: right;"> Delete</a>
<?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
</body>
</html>