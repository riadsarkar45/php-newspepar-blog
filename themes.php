<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/sidebar.php");
?>
<style>
    h4.title {
    font-size: 18px;
}
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12 title">
      <h1><i class="fa fa-bars"></i> Add New themes</h1>
    </div>

    <div class="clearfix"></div>
    <div id="dropzone">
        Drag and drop to upload new themes
    </div>
    <div id="progress-container">
        <progress id="progress-bar" value="0" max="100"></progress>
        <span id="progress-text">Uploading and Extracting...</span>
    </div>
    <div id="status"></div>
    <div class="filter-div">
    
<div class="plugins">
    <div style="display: flex;gap: 16px;width: 100%; align-items: center;" class="themes">
    <?php
$parentFolder = __DIR__ . '/../content/themes';

if (is_dir($parentFolder)) {
    $subfolders = array_diff(scandir($parentFolder), ['.', '..']);

    foreach ($subfolders as $subfolder) {
        if (is_dir($parentFolder . '/' . $subfolder)) {
            $stmt = $connect->prepare("SELECT * FROM themes");
            $stmt->execute();
            $themes = $stmt->fetchAll();

            foreach ($themes as $row) {
                $folder = $row["folder_name"];
                $btn = "Activate";
                $stl = "background-color: lightgray";
                $shadow = "";

                if ($subfolder == $row["folder_name"] && $row["status"] == "2") {
                    $btn = "Selected";
                    $stl = "background-color: steelblue";
                    $shadow = "box-shadow: 0px 0px 6px 3px;";
                }

                echo '<div class="folder-icon">
                    <div style="' . $shadow . ' padding: 61px;width: 221px;height: 122px; ' . $stl . '" class="flex">
                        <div style="display: flex" class="activate-button">
                            <div style="display: flex; gap: 20px; margin-left: -20px;
                            " class="black">
                                <div class="act">
                                    <a style="background-color: rgb(40, 167, 69);padding: 7px;color: white;border-radius: 7px;
                                    font-weight: 600;" class="button" style="background-color: rgb(40, 167, 69)" href=activate-theme.php?theme=' . $subfolder . '&active-theme=1>' . $btn . '</a>
                                </div>
                                <div style="" class="act">
                                    <a style="background-color: olivedrab;padding: 7px;color: white;border-radius: 7px;
                                    font-weight: 600;" href=../content/themes/' . $subfolder . '>Preview</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        }
    }
} else {
    echo "Parent folder '$parentFolder' does not exist.";
}
?>


<!-- ... (previous HTML code) ... -->


    <style>
       #dropzone {
            border: 2px dashed #ccc;
            padding: 62px;
            text-align: center;
            cursor: pointer;
            margin-right: 5px;
        }

        #progress-container {
            display: none;
            margin-top: 10px;
        }
    </style>
        </div>
    </div>
</div>
<style>
.folder-icon {
    display: flex;
    align-items: center;
    margin: 5px 0;
}

.folder-icon img {
    width: 100%;
    margin-right: 5px;
}
</style>
    <div class="clearfix"></div>
  </div>
</div>
<script>
    const dropzone = document.getElementById('dropzone');
    const status = document.getElementById('status');

    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.style.borderColor = 'blue';
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.style.borderColor = '#ccc';
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.style.borderColor = '#ccc';
        handleDrop(e.dataTransfer.items);
    });

    function handleDrop(items) {
        const formData = new FormData();

        for (const item of items) {
            if (item.kind === 'file') {
                const file = item.getAsFile();
                if (file.name.endsWith('.zip')) {
                    formData.append('files[]', file);
                } else {
                    status.innerHTML = `Invalid file: ${file.name}. Only zip files are allowed.`;
                    return;
                }
            } else if (item.kind === 'directory') {
                const directory = item.webkitGetAsEntry();
                traverseDirectory(directory, formData);
            }
        }

        fetch('folder-upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(message => {
            status.innerHTML = message;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function traverseDirectory(directory, formData, path = '') {
        const reader = directory.createReader();

        reader.readEntries(entries => {
            entries.forEach(entry => {
                if (entry.isFile) {
                    entry.file(file => {
                        if (file.name.endsWith('.zip')) {
                            formData.append('files[]', file, path + file.name);
                        }
                    });
                } else if (entry.isDirectory) {
                    traverseDirectory(entry, formData, path + entry.name + '/');
                }
            });
        });
    }
</script>
<?php include("include/footer.php"); ?>













