<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/sidebar.php");

?>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12 title">
      <h1><i class="fa fa-bars"></i> Add New Posts <button class="btn btn-sm btn-default">Add New</button></h1>
    </div>
    <div class="search-div">
      <div class="col-sm-9">
        All(6) | <a href="#">Published (6)</a> | <a href="#">Trashed Post (6)</a>
      </div>
<?php echo trashed(); ?>
      <div class="col-sm-3">
        <input type="text" id="search" name="search" class="form-control" placeholder="Search Posts">
      </div>
    </div>

    <div class="clearfix"></div>


    <div class="col-sm-12">
      <div class="content">
        <div id="drag-container">
          <div id="drop-area">
            <h3>Drag and Drop Files Here</h3>
            <p>or</p>
              <label style="width: 100px;border: 2px dotted lightblue;/* padding: 8px; */font-style: italic;" for="file-input" class="btn btn-default form-control">
                Select Files
              </label>
              <input type="file" id="file-input" name="file" accept="image/*">
              <span id="message"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<footer>
  <div class="col-sm-6">
    Copyright &copy; 2018 <a href="http://www.webtrickshome.com">Webtrickshome.com</a> All rights reserved.
  </div>
  <div class="col-sm-6">
    <span class="pull-right">Version 2.2.3</span>
  </div>
</footer>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/app.min.js"></script>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript">
// Variables
const dropArea = document.getElementById("drop-area");
const fileInput = document.getElementById("file-input");

// Highlight drop area when a file is dragged over it
dropArea.addEventListener("dragover", (event) => {
event.preventDefault();
dropArea.classList.add("highlight");
});

// Remove highlight when a file is dragged outside the drop area
dropArea.addEventListener("dragleave", () => {
dropArea.classList.remove("highlight");
});

// Handle file drop
dropArea.addEventListener("drop", (event) => {
event.preventDefault();
dropArea.classList.remove("highlight");
const files = event.dataTransfer.files;
handleFiles(files);
});

// Handle file selection from file input
fileInput.addEventListener("change", () => {
const files = fileInput.files;
handleFiles(files);
});

// Handle the selected files
function handleFiles(files) {
for (let i = 0; i < files.length; i++) {
  uploadFile(files[i]);
}
}

// Upload a file using AJAX
function uploadFile(file) {
const formData = new FormData();
formData.append("file", file);

const xhr = new XMLHttpRequest();
xhr.open("POST", "upload.php", true);

xhr.onload = function () {
  if (xhr.status === 200) {
    //console.log("File uploaded successfully.");
    // Update message in HTML element
    document.getElementById("message").textContent = "File uploaded successfully";

  } else {
    console.error("Error uploading file.");
  }
};

xhr.send(formData);
}

</script>
<style>
  #drop-area {
    border: 2px dashed #ccc;
    /* width: 300px; */
    height: 200px;
    padding: 25px;
    text-align: center;
    font-family: Arial;
  }
  .custom-file-input {
  display: inline-block;
  padding: 8px 16px;
  background-color: #e0e0e0;
  color: #333;
  cursor: pointer;
  border-radius: 4px;
}

input[type="file"] {
  display: none;
}

</style>
</body>
</html>
