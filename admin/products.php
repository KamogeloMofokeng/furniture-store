<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/furniture/init.php';



if(isset($_GET['delete'])){
    $id = sanitize($_GET['delete']);
    $db->query("UPDATE products SET deleted = 1 WHERE id = '$id'");
    header('Location: products.php');
}

$dbpath = '';
if (isset($_GET['add']) || isset($_GET['edit'])){
$brandQuery = $db->query("SELECT * FROM brand ORDER BY brand");
$parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY categories");
$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
$brand =((isset($_POST['brand']) && !empty($_POST['brand']))? sanitize($_POST['brand']): '');
$parent =((isset($_POST['parent']) && !empty($_POST['parent']))? sanitize($_POST['parent']): '');
$category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):'');
$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
$description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):'');
$sizes = rtrim($sizes,',');
$saved_photo = '';

if (isset($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $productResults = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
    $product = mysqli_fetch_assoc($productResults);
    if(isset($_GET['delete_image'])){
        $image_url = $_SERVER['DOCUMENT_ROOT'].$product['images']; echo $image_url;
        unlink($image_url);
        $db->query("UPDATE products SET images = '' WHERE id = '$edit_id'");
        header('Location: products.php?edit='.$edit_id);

    }
    $category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$product['categories']);
    $title =((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$product['title']);
    $brand =((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):$product['brand']);
    $parentQ = $db->query("SELECT * FROM categories WHERE id = '$category'");
    $parentResult = mysqli_fetch_assoc($parentQ);
    $parent =((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):$parentResult['parent']);
    $price =((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$product['price']);
    $description =((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):$product['description']);
    $sizes =((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):$product['sizes']);
    $sizes = rtrim($sizes,',');
    $saved_photo = (($product['images'] != '')?$product['images']:'');
    $dbpath = $saved_photo;
    
}
if(!empty($sizes)){
    $sizeString = sanitize($sizes);
    $sizeString = rtrim($sizeString,',');
    $sizesArray = explode(',',$sizeString);
    $sArray = array();
    $qArray = array();
    foreach($sizesArray as $ss){
        $s = explode(':',$ss);
        $sArray[]= $s[0];
        $qArray[] = $s[1];
    }
}else{$sizesArray = array();}
if ($_POST){
    $dbpath ='';
    $errors = array();
    $required = array('title', 'brand','price','child','sizes');
    foreach($required as $field){
        if($_POST[$field]==''){
            $errors[] = 'All fields with an astriks are required';
            break;
        }
    }

    if(!empty($_FILES)){
        $photo = $_FILES['photo'];
        $name = $photo['name'];
        $nameArray = explode('.',$name);
        $fileName = $nameArray[0];
        $fileExt = $nameArray[1];
        $mime = explode('/',$photo['type']);
        $mimetype = $mime[0];
        $mimeExt = $mime[1];
        $tmpLoc = $photo['tmp_name'];
        $fileSize = $photo['size'];
        $allowed = array('png','jpg','jpeg','gif');
        $uploadName = md5(microtime().'.'.$fileExt);
        $uploadPath = BASEURL.'images/products/';
        $dbpath = 'furniture/images/products/'.$uploadName;
        if($mimetype != 'image'){
            $errors[] = 'The file must be an image';
        }
        if(!in_array($fileExt, $allowed)){
            $errors[] = "The file extension must be a png, jpg, jpeg or gif";
        }
        if($fileSize > 15000000){
            $errors[] = 'The file size must be under 15mb';
        }
        if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
            $errors[] = 'File does not match the file extension';
        }
    }
    if(!empty($errors)){
        echo display_errors($errors);
    }else{
        move_uploaded_file($tmpLoc,$uploadPath);
        $insertSql = "INSERT INTO products (`title`,`price`, `brand`, `categories`, `sizes`, `image`,`description`) 
        VALUES ('$title','$price','$brand','$category','$sizes','$dbpath', '$description')";
        if(isset($_GET['edit'])){
            $insertSql = "UPDATE products SET title = '$title' , price = '$price', brand = '$brand', categories = '$category', sizes = '$sizes', images = '$dbpath', description = '$description' WHERE id = '$edit_id'";
        }
        $db->query($insertSql);
        header('Location:products.php');
    }
}

?>
    <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add a New');?>Product</h2>
    <form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="POST" enctype="multipart/form-data">
        <div class="form-group col-md-3">
            <label for="title">Title*:</label>
            <input type="text" name="title" class="form-control" id="title" value="<?=$title;?>">
        </div>
        <div class="form-group col-md-3">
            <label for="brand">Brand*:</label>
            <select class="form-control"name="brand" id="brand">
                <option value=""<?=(($brand =='')?'selected':'');?>></option>
                <?php while($b = mysqli_fetch_assoc($brandQuery)): ?>
                    <option value="<?=$b['id'];?>"><?=(($brand ==$b['id'])?'selected':'');?><?=$b['brand'];?></option>
                    <?php endwhile; ?>

            </select>
        </div>
        <div class="from-group col-md-3">
            <label for="parent">Parent Category*:</label>
            <select class="form-control"name="parent" id="parent">
                <option value=""<?=(($parent =='')?'selected':'');?>></option>
                <?php while($p = mysqli_fetch_assoc($parentQuery)): ?>
                    <option value="<?=$p['id'];?>"<?=((isset($_POST['parent']) && $_POST['parent']==$p['id'])?'selected':'');?>><?=$p['category'];?></option>
                    <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group col-md-3">
        <label for="price">Price*:</label>
        <input type="text" id="price" class="form-control" value="<?=$price;?>">
        </div>
        <div class="form-group col-md-3">
        <button class="btn btn-default" data-bs-target="#sizesModal" onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity</button>
        </div>
        <div class="form-group col-md-3">
        <label for="sizes">Quantity Preview</label>
        <input type="text" class="form-control" name="sizes" id="size" value="<?=$sizes;?>" readonly>
        </div>
        <div class="form-group col-md-6">
        <?php if($saved_photo !=''): ?>
            <div class="saved-photo">
            <img src="../pictures/<?=$saved_photo;?>" alt="saved photo"><br>
            <a href="products.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger">Delete image</a>
            </div>
        <?php else: ?>
            <label for="photo">Product Photo:</label>
            <input type="file" name="photo" id="photo" class="form-control">
        <?php endif; ?>
        </div>
        <div class="form-group col-md-6">
        <label for="description">Description:</label>
        <textarea name="description" id="description" class="form-control"rows="6"><?=$description;?></textarea>
        </div>
        <div class="form-group pull-right">
        <a href="products.php" class=" btn btn-default">Cancel</a>
            <input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> product" class="btn btn-default">
        </div>
        
    </form>
    <div class="modal fade bs-example-modal-lg" id="sizesModal" tabindex="-1" aria-labelledby="sizesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sizesModalLabel">Quantity</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="container-fluid">
        <?php for($i = 1;$i <= 12;$i++): ?>
        <div class="form-group col-md-4">
        <label for="size<?=$i;?>">Size:</label>
        <input type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>" class="form-control">
        </div>
        <div class="form-group col-md-2">
        <label for="qty<?=$i;?>">Quantity:</label>
        <input type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'');?>" min="0" class="form-control">
        </div>

        <?php endfor; ?>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle'); return false;">Save changes</button>
      </div>
    </div>
  </div>
</div>
<?php }else{

}
$sql = "SELECT * FROM products WHERE deleted != 1";
$presults = $db->query($sql);
if (isset($_GET['feature'])){
    $id = (int)$_GET['id'];
    $feature = (int)$_GET['feature'];
    $featuresql = "UPDATE products SET feature = '$feature' WHERE id ='$id' ";
    $db->query($featuresql);
    header('Location: products.php');

}
?>

<!doctype html>
<html>
    <head>
    <title>Simplistic Threads</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
        <link href="../style.css" rel="stylesheet">
        <script src="../js/bootstrap.min.js"></script>
        
        </head>
        <body>

            <div class="menu">

                <li><a  href="categories.php">Categories</a></li>
                <li><a  href="brands.php">Brands</a></li>
                <li><a  class="active" href="products.php">Products</a></li>
                </div>

                <div class="contant">
            
                <h1>Products</h1>
            </div>

            <a href="products.php?add=1" class="btn btn-default pull-right" id="add-product-btn">Add Product</a><div class="clearfix"></div>

            <table class="table table-bordered table-condensed table-striped">
            <thead>
            <th></th>
            <th>Product</th>
            <th>Price</th>
            <th>Categories</th>
            <th>Featured</th>
            <th>Sold</th>
            </thead>
            <tbody>
            <?php while($product = mysqli_fetch_assoc($presults)): 
             $childID = $product['categories'];
             $catsql = "SELECT  * FROM categories WHERE id= $childID" ;
             $result = $db->query($catsql);
             $child = mysqli_fetch_assoc($result);
             $parentID = $child['parent'];
             $pSql = "SELECT * FROM categories WHERE id= $parentID";
             $presult = $db->query($pSql);
             $parent = mysqli_fetch_assoc($presult);
             $category  = $parent['category'].'~'.$child['category'];
            ?>
            <tr>
            <td><a href="products.php?edit=<?=$product['id'];?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span><br>
            <a href="products.php?delete=<?=$product['id'];?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span>
            </td>
            <td><?=$product['title'];?></td>
            <td><?=money($product['price']);?></td>
            <td><?=$category;?></td>
            <td><a href="products.php?feature=<?=(($product['feature']==0)?'1' :'0');?>&id=<?=$product['id'];?>" class="btn btn-default btn-sm">
            <span class="glyphicon glyphicon-<?=(($product['feature']==1)?'minus':'plus');?>"></span>
                 </a>&nbsp <?=(($product['feature']== 1)?'Featured Product':'');?></td>
            <td>0</td>
            </tr>

            <?php endwhile; ?>
            </tbody>
            </table>
            <script>
            function updateSizes(){
                var sizeString='';
                for(var i=1;i<=12;i++){
                    if(jQuery('#size'+i).val() !=''){
                        sizeSttring+= jQuery('#size' +i).val()+':'+jQuery('#qty'+i).val()+',';
                    }
                }
                jQuery('#sizes').val(sizesString);
            }
            </script>
                </body>
                </html>