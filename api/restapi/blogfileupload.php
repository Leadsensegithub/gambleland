<?php
include '../canfig.php';

// Your main PHP code here
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $targetDir = "C:/xamppnew/htdocs/publander/assets/blog/"; // Directory where files will be saved
    $targetFile = $targetDir . basename($_FILES["file"]["name"]); // Path to save the file

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "File already exists.";
    } else {
        // Try to move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            // File uploaded successfully, now insert the file path into the database
            $filePath = $targetFile;
            $author_name = $author_name['author_name'];
            $insta = $insta['insta'];
            $fb = $fb['fb'];
            $linkedin = $linkedin['linkedin'];
            $meta_title = $meta_title['meta_title'];
            $meta_keyword = $meta_keyword['meta_keyword'];
            $meta_desc = $meta_desc['meta_desc'];
            $blog_title = $blog_title['blog_title'];
            $blog_desc = $blog_desc['blog_desc'];
            $blog_status = $blog_status['blog_status'];


            $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            // Insert query
            $sql = "INSERT INTO blog (blog_img,author_name,insta,fb,linkedin,meta_title,meta_keyword,meta_desc,blog_title,blog_desc,blog_status) VALUES (:filePath,:author_name,:insta,:fb,:linkedin,:meta_title,:meta_keyword,:meta_desc,:blog_title,:blog_desc,:blog_status)";
            // Prepare statement
            $stmt = $pdo->prepare($sql);
            // Bind parameters
            $stmt->bindParam(':filePath', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':author_name', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':insta', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':fb', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':linkedin', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':meta_title', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':meta_keyword', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':meta_desc', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':blog_title', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':blog_desc', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':blog_status', $filePath, PDO::PARAM_STR);
            // Execute statement
            if ($stmt->execute()) {
                echo "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded and file path inserted into the database.";
            } else {
                echo "Error inserting file path into database.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    exit(); // Stop further execution
} else {
    echo "Sorry,.";
}


?>