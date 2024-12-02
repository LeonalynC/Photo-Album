<?php
require_once 'dbConfig.php';

function checkIfUserExists($pdo, $username) {
    $response = array();
    $sql = "SELECT * FROM user_accounts WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $userInfoArray = $stmt->fetch();

    if ($stmt->rowCount() > 0) {
        $response = array(
            "result" => true,
            "status" => "200",
            "userInfoArray" => $userInfoArray
        );
    } else {
        $response = array(
            "result" => false,
            "status" => "400",
            "message" => "User doesn't exist from the database"
        );
    }

    return $response;
}

function insertNewUser($pdo, $username, $first_name, $last_name, $password) {
    $response = array();
    $checkIfUserExists = checkIfUserExists($pdo, $username);

    if (!$checkIfUserExists['result']) {
        $sql = "INSERT INTO user_accounts (username, first_name, last_name, password) 
                VALUES (?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$username, $first_name, $last_name, $password])) {
            $response = array(
                "status" => "200",
                "message" => "User successfully inserted!"
            );
        } else {
            $response = array(
                "status" => "400",
                "message" => "An error occurred with the query!"
            );
        }
    } else {
        $response = array(
            "status" => "400",
            "message" => "User already exists!"
        );
    }

    return $response;
}

function getAllUsers($pdo) {
    $sql = "SELECT * FROM user_accounts";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getUserByID($pdo, $username) {
    $sql = "SELECT * FROM user_accounts WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function insertAlbum($pdo, $album_name, $username) {
    $sql = "INSERT INTO albums (album_name, username) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$album_name, $username]);
}

function getAllAlbums($pdo, $username) {
    $sql = "SELECT * FROM albums WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    return $stmt->fetchAll();
}

function updateAlbum($pdo, $album_name, $album_id) {
    $sql = "UPDATE albums SET album_name = ? WHERE album_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$album_name, $album_id]);
}

function deleteAlbum($pdo, $album_id) {
    $sql = "DELETE FROM albums WHERE album_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$album_id]);
}

function insertPhoto($pdo, $photo_name, $username, $description, $album_id = null, $photo_id = null) {
    if (empty($photo_id)) {
        $sql = "INSERT INTO photos (photo_name, username, description, album_id) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$photo_name, $username, $description, $album_id]);
    } else {
        $sql = "UPDATE photos SET photo_name = ?, description = ?, album_id = ? WHERE photo_id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$photo_name, $description, $album_id, $photo_id]);
    }
}

function getAllPhotos($pdo, $username = null) {
    if (empty($username)) {
        $sql = "
        SELECT photos.*, albums.album_name
        FROM photos
        LEFT JOIN albums ON photos.album_id = albums.album_id
        ORDER BY photos.date_added DESC";
        $stmt = $pdo->prepare($sql);
        $executeQuery = $stmt->execute();

        if ($executeQuery) {
            return $stmt->fetchAll();
        }
    } else {
        $sql = "
        SELECT photos.*, albums.album_name
        FROM photos
        LEFT JOIN albums ON photos.album_id = albums.album_id
        WHERE photos.username = ?
        ORDER BY photos.date_added DESC";
        $stmt = $pdo->prepare($sql);
        $executeQuery = $stmt->execute([$username]);

        if ($executeQuery) {
            return $stmt->fetchAll();
        }
    }
}

function getPhotoByID($pdo, $photo_id) {
    $sql = "SELECT * FROM photos WHERE photo_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$photo_id]);
    return $stmt->fetch();
}


function deletePhoto($pdo, $photo_id) {
    $sql = "DELETE FROM photos WHERE photo_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$photo_id]);
}
?>