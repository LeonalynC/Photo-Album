<?php
require_once 'dbConfig.php';
require_once 'models.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['insertNewUserBtn'])) {
 
}

if (isset($_POST['loginUserBtn'])) {

}

if (isset($_GET['logoutUserBtn'])) {
    session_unset(); 
    session_destroy(); 
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['insertAlbumBtn'])) {
    $album_name = trim($_POST['album_name']);
    if (!empty($album_name)) {
        $insertAlbum = insertAlbum($pdo, $album_name, $_SESSION['username']);
        $_SESSION['message'] = $insertAlbum ? "Album created successfully!" : "Failed to create album.";
        $_SESSION['status'] = $insertAlbum ? '200' : '400';
        header("Location: ../yourprofile.php");
        exit;
    }
}

if (isset($_POST['updateAlbumBtn'])) {
    $album_id = $_POST['album_id'];
    $album_name = trim($_POST['album_name']);
    if (!empty($album_name)) {
        $updateAlbum = updateAlbum($pdo, $album_name, $album_id);
        $_SESSION['message'] = $updateAlbum ? "Album updated successfully!" : "Failed to update album.";
        $_SESSION['status'] = $updateAlbum ? '200' : '400';
        header("Location: ../yourprofile.php");
        exit;
    }
}

if (isset($_POST['deleteAlbumBtn'])) {
    $album_id = $_POST['album_id'];
    $deleteAlbum = deleteAlbum($pdo, $album_id);
    $_SESSION['message'] = $deleteAlbum ? "Album deleted successfully!" : "Failed to delete album.";
    $_SESSION['status'] = $deleteAlbum ? '200' : '400';
    header("Location: ../yourprofile.php");
    exit;
}

if (isset($_POST['insertPhotoBtn'])) {
    $description = $_POST['photoDescription'];
    $fileName = $_FILES['image']['name'];
    $tempFileName = $_FILES['image']['tmp_name'];
    $fileError = $_FILES['image']['error']; 
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $uniqueID = sha1(md5(rand(1, 9999999)));
    $imageName = $uniqueID . "." . $fileExtension;
    $album_id = $_POST['album_id'] ?? null;

  
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        $_SESSION['message'] = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        $_SESSION['status'] = '400';
        header("Location: ../index.php");
        exit;
    }

 
    if ($fileError !== UPLOAD_ERR_OK) {
        $_SESSION['message'] = "File upload error: " . $fileError;
        $_SESSION['status'] = '400';
        header("Location: ../index.php");
        exit;
    }

   
    if (!empty($description) && !empty($album_id)) {
        $saveImgToDb = insertPhoto($pdo, $imageName, $_SESSION['username'], $description, $album_id);

        if ($saveImgToDb && move_uploaded_file($tempFileName, "../images/" . $imageName)) {
            header("Location: ../index.php");
            exit;
        } else {
            $_SESSION['message'] = "Failed to upload image: Database or file system error.";
            $_SESSION['status'] = '400';
            header("Location: ../index.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "Description and Album ID are required.";
        $_SESSION['status'] = '400';
        header("Location: ../index.php");
        exit;
    }
}

if (isset($_POST['deletePhotoBtn'])) {
    $photo_name = $_POST['photo_name'];
    $photo_id = $_POST['photo_id'];
    $deletePhoto = deletePhoto($pdo, $photo_id);

    if ($deletePhoto) {
        unlink("../images/" . $photo_name); 
        header("Location: ../index.php");
        exit;
    } else {
        $_SESSION['message'] = "Failed to delete photo.";
        $_SESSION['status'] = '400';
        header("Location: ../index.php");
        exit;
    }
}
?>
