<?php
include("include/db.php");
if (isset($_FILES["files"])) {
    $uploadDirectory = "../content/themes/";

    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
        $fileName = $_FILES["files"]["name"][$key];
        $filePath = $uploadDirectory . $fileName;

        if (move_uploaded_file($tmp_name, $filePath)) {
            echo "File $fileName uploaded successfully.<br>";

            if (pathinfo($fileName, PATHINFO_EXTENSION) === 'zip') {
                $folderName = pathinfo($fileName, PATHINFO_FILENAME);

                // Clean up folder name to remove numeric prefixes or suffixes
                $folderName = preg_replace('/^(\d+\s*)|(\s*\(\d+\))$/', '', $folderName);

                $extractPath = $uploadDirectory . $folderName;

                if (!file_exists($extractPath)) {
                    mkdir($extractPath, 0777, true);

                    $zip = new ZipArchive();
                    if ($zip->open($filePath) === true) {
                        // Extract the zip file
                        $extractedFilesPath = dirname($filePath) . '/';
                        $zip->extractTo($extractedFilesPath);
                        $zip->close();
                        echo "Zip file $fileName extracted successfully.<br>";
                        unlink($filePath); // Delete the zip file
                        echo "Zip file $fileName deleted.<br>";
                        $insert = "INSERT INTO `themes_detail`(`folder_name`) VALUES ('$folderName')";
                        $insert = $connect->prepare($insert);
                        $insert->execute();
                    } else {
                        echo "Error extracting $fileName.<br>";
                    }
                } else {
                    echo "Folder $folderName already exists.<br>";
                    unlink($filePath); // Delete the zip file
                    echo "Zip file $fileName deleted.<br>";
                }
            }
        } else {
            echo "Error uploading $fileName.<br>";
        }
    }
}
?>
