<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/flatly/bootstrap.min.css" integrity="sha384-qF/QmIAj5ZaYFAeQcrQ6bfVMAh4zZlrGwTPY7T/M+iTTLJqJBJjwwnsE5Y0mV7QK" crossorigin="anonymous">
<link rel="stylesheet" href="css/tree.css">
<script src="js/tree.js"></script> 
<script src="js/treeitem.js"></script> 
<script src="js/treeitemClick.js"></script> 
</head>

<?php
    if (isset($_POST['folder']) ) {
        $url = "http://127.0.0.1:8000";
        $collection_name = 'nearDuplicate';
        $data = array("foldername" => $_POST['folder'], 'filename' => $_POST['file']);
    
        $request_url = $url . '/' . $collection_name . "/?". http_build_query($data);
        $curl = curl_init($request_url);
        
        // 1. Set the CURLOPT_RETURNTRANSFER option to true
        #curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        #curl_setopt($curl, CURLOPT_GET, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // 4. Set custom headers for RapidAPI Auth and Content-Type header
        #curl_setopt($curl, CURLOPT_HTTPHEADER, [
        #'X-RapidAPI-Host: kvstore.p.rapidapi.com',
        #'X-RapidAPI-Key: [Input your RapidAPI Key Here]',
        #'Content-Type: application/json'
        #]);
        $response = curl_exec($curl);
        curl_close($curl);
    }
?>

<body>
    
<nav class="navbar navbar-expand-lg navbar-light bg-light">
<a class="navbar-brand" href="#">
    <img src="total_logo.png" width="100" height="28" class="d-inline-block align-top mr-3" alt="">
    Data Challenge
  </a>

  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">
      <a class="nav-item nav-link active" href="main.php">Near Duplicates</a>
      <a class="nav-item nav-link" href="#">Template Matching</a>
    </div>
  </div>
</nav>

<div class="container">

<div class="row" >

<div class="col-4">
    <h3 id="tree_label">
        File Viewer
      </h3>
      <ul role="tree" aria-labelledby="tree_label">
      <?php 
            $directory = "data_tri/*";
            $images = glob($directory . "/*.jpg");

            $folders = glob($directory);
            $imageByFolder = array();
            $pathByFolder = array();
            foreach ($folders as $f) {
                $folderName = explode("/", $f)[1];
                $imageByFolder[$folderName] = array();
                $pathByFolder[$folderName] = array();
             }
            foreach($images as $image)
                {
                    $partOfPath =  explode("/", $image);
                    $folder = $partOfPath[1];
                    $imageName = $partOfPath[2];
                    
                    array_push($imageByFolder[$folder], $imageName); 
                    array_push($pathByFolder[$folder], $image);
                }

            foreach ($imageByFolder as $folder => $listImages) {
        ?>
            <li role="treeitem" aria-expanded="false">
            <span>
            <?php echo $folder; ?>
            </span>
            <ul role="group">
        <?php
                for ($i=0; $i < sizeof($listImages) ; $i++) { 
        ?>
            <?php 
                $imagePaths = explode("/", $pathByFolder[$folder][$i]); 
                $imagePath = $imagePaths[1]."/".$imagePaths[2];
            ?>
            <li role="treeitem" name=<?php echo $imagePath;?> class="doc">
            <?php echo $listImages[$i]; ?>
            </li>
        <?php
                }
        ?>
        </ul>
        <?php
            }
        ?>
      </ul>

</div>
<div class="col-8"> 

<div class= "container">
    <div class= "container">

        <h3>Choix de votre image</h3>
    </div>
</div>

<div class= "container">
    <div class= "container">

    <form action="main.php" method="post">
        <input id="last_action" type="text" name= "folder"/>
        <button type="submit" name="button"  class="btn btn-primary">Near duplicates</button>
    </form>
    </div>

    <?php

    if (isset($_POST['folder'])) {
        
        $allPaths = json_decode($response, true);
    ?>
    <div class="container">
    <h4>Image de base</h4>
        <img src=<?php echo "data_tri/".$_POST['folder'] ?>>
    </div>
    <div class="container">
    <h4>Images</h4>
    <div class="row">
    

    <?php
        foreach ($allPaths as $path) {
           
    ?>
    <div class="col-3" style="pl-3">

          <img class="card-img-top" src="<?php echo $path; ?>" alt="">

    </div>


    <?php 
        
            }

    ?>
    </div>
    </div>

    <?php 

        }

    ?>
    </div>
</div>

</div>
</div>


</body>

