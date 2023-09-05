<?php 
require_once '../init.php';

$sql = "SELECT * FROM brand ORDER BY brand";
$results = $db->query($sql);
$errors = array();

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $sql2 = "SELECT * FROM brand WHERE id = '$edit_id'";
    $edit_result = $db->query($sql2);
    $eBrand = mysqli_fetch_assoc($edit_result);
    }

if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "DELETE FROM brand WHERE id = '$delete_id'";
    $db->query($sql);
    header('Location: brands.php');
}

if (isset($_POST['add_submit'])) {
    $brand = sanitize($_POST['brand']);
    if ($_POST['brand'] ==''){
        $errors[] .='You must enter a brand';
    }

    $sql = "SELECT * FROM brand WHERE brand ='$brand'";
    if (isset($_GET['edit'])) {
        $sql = "SELECT * FROM brand WHERE brand = '$brand' AND id != '$edit_id'";
    }
    $result = $db->query($sql);
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        $errors[] .= $brand. ' already exists. Please choose another brand.';
    }

    if (!empty($errors)) {
        echo display_errors($errors);
    }else{
        $sql ="INSERT INTO brand (brand) VALUES ('$brand')";
        if (isset($_GET['edit'])) {
            $sql = "UPDATE brand SET ='$brand' WHERE id = '$edit_id'";
        }
        $db->query($sql);
        header('Location: brands.php');

    }
}
?>

<!DOCTYPE html>
<html>
    <head>
    <title>Simplistic Threads</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css" >
    <script src="../js/bootstrap.bundle.js"></script>
        
        </head>
        <body>

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Simplistic Furniture</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="brands.php">Brands</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="categories.php">Categories</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
             

            <div class="display">
    <h1 class="display-5">Brands</h1>
    </div><br><br>
           
           <center>
            <div>
                <form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method="post">
                    <div class="form-group">

                        
                        <?php
                        $brand_value = ''; 
                        if (isset($_GET['edit'])){
                            $brand_value = $eBrand['brand'];
                        }else{
                            if (isset($_POST['brand'])) {
                                $brand_value = sanitize($_POST['brand']);
                            }
                        } ?>

                        <label for="brand"><?=((isset($_GET['edit']))?'Edit':'Add a');?> brand: </label>
                        <input type="text" name="brand" class="form-control" id="brand" placeholder="Add a brand" value="<?=((isset($_POST['brand']))?$_POST['brand']:''); ?>">
                        <?php if(isset($_GET['edit'])): ?>
                            <a href="brands.php" class="btn btn-default">Cancel</a>
                    <?php endif; ?>
                    </div>
                    <br>
                    <input type="submit" name="add_submit" value="<?=((isset($_GET))?'Edit':'Add'); ?>" class="btn btn-default">
                </form>
            </div>
            <table>
                
                <thead>
                    <th></th><th>Brand</th><th></th>
                </thead>
                <tbody>
                    <?php while($brand = mysqli_fetch_assoc($results)): ?>
                    <tr>
                        <td><a href="brands.php?edit=<?=$brand['id']; ?>" class="btn btn-xs btn-default"><button class="btn">Edit</button></a></td>
                        <td><?=$brand['brand']; ?></td>
                        <td><a href="brands.php?delete=<?=$brand['id']; ?>" class="btn btn-xs btn-default" ><button class="btn">remove</button></a></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            </center>
        	 
        </body>
        </html>
