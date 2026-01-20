<?php
// backend/utils/file_upload.php

function uploadImage($file) {
    // Define target directory relative to this script (assuming called from admin/)
    // We need to go up from admin/ -> backend/ -> root -> frontend/assets/images/
    $target_dir = "../../frontend/assets/images/";
    
    // Create directory if it doesn't exist (though it should)
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = basename($file["name"]);
    $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Generate unique filename to avoid overwrites
    $new_filename = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $new_filename;
    
    $uploadOk = 1;
    $message = "";

    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        return ["success" => false, "message" => "File is not an image."];
    }

    // Check file size (limit to 5MB)
    if ($file["size"] > 5000000) {
        return ["success" => false, "message" => "Sorry, your file is too large."];
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" && $imageFileType != "webp" ) {
        return ["success" => false, "message" => "Sorry, only JPG, JPEG, PNG, GIF & WEBP files are allowed."];
    }

    // Try to upload file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ["success" => true, "filename" => $new_filename];
    } else {
        return ["success" => false, "message" => "Sorry, there was an error uploading your file."];
    }
}
?>
