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
    <title>Your Profile</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <h2>Create Album</h2>
    <form action="core/handleForms.php" method="POST">
        <p>
            <label for="album_name">Album Name</label>
            <input type="text" name="album_name">
            <input type="submit" name="insertAlbumBtn" style="margin-top: 10px;">
        </p>
    </form>

    <h2>Your Albums</h2>
    <ul>
        <?php $albums = getAllAlbums($pdo, $_SESSION['username']); ?>
        <?php foreach ($albums as $album) { ?>
        <li>
            <form action="core/handleForms.php" method="POST" style="display: inline;">
                <input type="hidden" name="album_id" value="<?php echo $album['album_id']; ?>">
                <input type="text" name="album_name" value="<?php echo $album['album_name']; ?>">
                <input type="submit" name="updateAlbumBtn" value="Update">
                <input type="submit" name="deleteAlbumBtn" value="Delete">
            </form>
        </li>
        <?php } ?>
    </ul>

    <?php $getAllPhotos = getAllPhotos($pdo, $_SESSION['username']); ?>
    <?php foreach ($getAllPhotos as $row) { ?>
    <div class="images" style="display: flex; justify-content: center; margin-top: 25px;">
        <div class="photoContainer" style="background-color: ghostwhite; border-style: solid; border-color: gray;width: 50%;">

            <img src="images/<?php echo $row['photo_name']; ?>" alt="" style="width: 100%;">

            <div class="photoDescription" style="padding:25px;">
                <a href="#"><h2><?php echo $row['username']; ?></h2></a>
                <p><i><?php echo $row['date_added']; ?></i></p>
                <h4><?php echo $row['description']; ?></h4>

                <?php if ($_SESSION['username'] == $row['username']) { ?>
                    <a href="editphoto.php?photo_id=<?php echo $row['photo_id']; ?>" style="float: right;"> Edit </a>
                    <br>
                    <br>
                    <a href="deletephoto.php?photo_id=<?php echo $row['photo_id']; ?>" style="float: right;"> Delete</a>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>

</body>
</html>