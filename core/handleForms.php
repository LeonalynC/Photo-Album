<?php  
require_once 'dbConfig.php';
require_once 'models.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}


if (isset($_POST['insertNewUserBtn'])) {
    $username = trim($_POST['username']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            $insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT));
            $_SESSION['message'] = $insertQuery['message'];
            $_SESSION['status'] = $insertQuery['status'];
            header("Location: ../" . ($insertQuery['status'] == '200' ? "login.php" : "register.php"));
            exit;
        } else {
            $_SESSION['message'] = "Please make sure both passwords are equal";
            $_SESSION['status'] = '400';
            header("Location: ../register.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "Please make sure there are no empty input fields";
        $_SESSION['status'] = '400';
        header("Location: ../register.php");
        exit;
    }
}


if (isset($_POST['loginUserBtn'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $loginQuery = checkIfUserExists($pdo, $username);
        
        if ($loginQuery['userInfoArray'] && password_verify($password, $loginQuery['userInfoArray']['password'])) {
            $_SESSION['user_id'] = $loginQuery['userInfoArray']['user_id'];
            $_SESSION['username'] = $loginQuery['userInfoArray']['username'];
            header("Location: ../index.php");
            exit;
        } else {
            $_SESSION['message'] = "Username/password invalid";
            $_SESSION['status'] = "400";
            header("Location: ../login.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "Please make sure there are no empty input fields";
        $_SESSION['status'] = '400';
        header("Location: ../login.php");
        exit;
    }
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
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $uniqueID = sha1(md5(rand(1, 9999999)));
    $imageName = $uniqueID . "." . $fileExtension;
    $album_id = $_POST['album_id'] ?? null;
    $photo_id = $_POST['photo_id'] ?? "";

    if (!empty($description) && !empty($album_id)) {
        $saveImgToDb = insertPhoto($pdo, $imageName, $_SESSION['username'], $description, $album_id, $photo_id);

        if ($saveImgToDb && move_uploaded_file($tempFileName, "../images/" . $imageName)) {
            header("Location: ../index.php");
            exit;
        } else {
            $_SESSION['message'] = "Failed to upload image.";
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