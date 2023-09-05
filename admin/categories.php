<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/furniture/init.php';




$sql = "SELECT * FROM categories WHERE parent = 0";
$result = $db->query($sql);
$errors = array();
$category='';

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
	$edit_id = (int)$_GET['edit'];
	$edit_id = sanitize($edit_id);
	$edit_sql = "SELECT * FROM categories WHERE id = '$edit_id'";
	$edit_result = $db->query($edit_sql);
	$edit_category = mysqli_fetch_assoc($edit_result);
}

if (isset($_GET['delete']) && !empty($_GET['delete'])) {
	$delete_id = (int)$_GET['delete'];
	$delete_id = sanitize($delete_id);
	$dsql = "DELETE FROM categories WHERE id = '$delete_id'";
	$db->query($dsql);
	header('Location: categories.php');
}

if (isset($_POST) && !empty($_POST)){
	$post_parent = sanitize($_POST['parent']);
	$category = sanitize($_POST['category']);
	$sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent'";
	$fresult = $db->query($sqlform);
	$count = mysqli_num_rows($fresult);

	if ($category == '') {
		$errors[] .= 'The category cannot be blank';
	}

	if ($count > 0) {
		$errors[] .= $category. ' Already exists.Need to add a new category';
	}

	if (!empty($errors)) {
		$display = display_errors($errors); ?>
		<script>
			jquery('document').ready(function(){
				jquery('#errors').html('<?=$display; ?>');
			});
		</script>
	<?php }else{
		$updatesql = "INSERT INTO categories (category, parent) VALUES ('$category','$parent')";
		$db->query($updatesql);
		header('Location: categories.php');

	}
}
$category_value='';
$parent_value = 0;
if(isset($_GET['edit'])){
	$category_value =$edit_category['category'];
	$parent_value = $edit_category['parent'];
}else{
	if(isset($_POST)){
		$category_value = $category;
	}
}
?>
<!doctype html>
<html>
    <head>
    <title>Simplistic Threads</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/bootstrap.min.css" />
        <link href="../style.css" rel="stylesheet">
        <script src="../js/bootstrap.min.js"></script>
        
        </head>
        <body>

            <div class="menu">

                <li><a  class="active"href="categories.php">Categories</a></li>
                <li><a  href="brands.php">Brands</a></li>
                </div>
             

            <div class="contant">
            
                <h1>Categories</h1>
            </div>
            <div id="errors"></div>

           
			<div class="row">
            	<div class="col-md-6">
            		<form class="form" action="categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
            			<legend><?=((isset($_GET['edit']))?'Edit' : 'Add a'); ?> Category</legend><br><br>
            			<div class="form-group">
            				<label for="parent">Parent</label>
            				<select class="form-control" name="parent" id="parent">
            					<option value="0">Parent</option>
            					<?php while($post_parent = mysqli_fetch_assoc($result)) : ?>
            					<option value="<?=$post_parent['id'];?>"><?=$post_parent['category'];?></option>
            				<?php endwhile; ?>
            				</select><br><br>
							<div class="from-group">
            				<label for="category">Category</label>
            				<input type="text" class="form-control"  id="category" name="category" value="<?=$category_value;?>">
							</div>
							<div class="form-group">
            				<input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add A');?> Category" class="btn btn-primary">
							</div>
            			</div>
            		</form>
            		
            	</div>

				 <div class="col-md-6">
            	<table class="table table-bordered">
            		<thead>
            		<th>Category</th><th></th>
            		</thead>
            		<tbody>
            			<?php 
            			$sql = "SELECT * FROM categories WHERE parent = 0";
						$result = $db->query($sql);
            			while ($post_parent = mysqli_fetch_assoc($result)):
            				$parent_id = (int)$post_parent['id'];
            				$sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
            				$cresult = $db->query($sql2);
            			 ?>
            			<tr>
            				<td><?=$post_parent['category']; ?></td>
            				<td>
            					<a href="categories.php?edit=<?=$post_parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-edit">Edit</span></a>
            					<a href="categories.php?delete=<?=$post_parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove">Remove</span></a>
            				</td>
            			</tr>
            			<?php while ($child = mysqli_fetch_assoc($cresult)): ?>
            				<tr>
            				<td><?=$child['category']; ?></td>
            				<td>
            					<a href="categories.php?edit=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-edit">edit</span></a>
            					<a href="categories.php?delete=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove">Remove</span></a>
            				</td>
            			</tr>
            			<?php endwhile; ?>
            		<?php endwhile; ?>
            		</tbody>
            	</table>
            </div>
            </div>
			
        </body>
        </html>
