<?php 

session_start();
$connect = mysqli_connect("127.0.0.1", "root", "", "furniture");
 
if(isset($_POST["add_to_cart"]))
{
	if(isset($_SESSION["shopping_cart"]))
	{
		$item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
		if(!in_array($_GET["id"], $item_array_id))
		{
		$count = count($_SESSION["shopping_cart"]);
		$item_array = array(
		'item_id'		=>	$_GET["id"],
		'item_name'		=>	$_POST["hidden_name"],
		'item_price'		=>	$_POST["hidden_price"],
		'item_quantity'		=>	$_POST["quantity"]
		);
		$_SESSION["shopping_cart"][$count] = $item_array;
		}
		else
		{
		echo '<script>alert("Item Already Added")</script>';
		}
	}
	else
	{
		$item_array = array(
		'item_id'		=>	$_GET["id"],
		'item_name'		=>	$_POST["hidden_name"],
		'item_price'		=>	$_POST["hidden_price"],
		'item_quantity'		=>	$_POST["quantity"]
		);
		$_SESSION["shopping_cart"][0] = $item_array;
	}
}
 
if(isset($_GET["action"]))
{
	if($_GET["action"] == "delete")
	{
		foreach($_SESSION["shopping_cart"] as $keys => $values)
		{
		if($values["item_id"] == $_GET["id"])
		{
		unset($_SESSION["shopping_cart"][$keys]);
		echo '<script>alert("Item Removed")</script>';
		echo '<script>window.location="cart.php"</script>';
		}
		}
	}
}
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simplistic Furniture</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css" >
    <script src="js/bootstrap.bundle.js"></script>
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
          <a class="nav-link active" aria-current="page" href="#inspiration">Inspiration</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#shop">Furniture</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#decor">Home Decor</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#signup">Sign up</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
	<body>
		<br />
		<div class="Container">
		<br />
		<br />
		<br />
		<div class="display">
    <h1 class="display-1">Shop Furniture</h1>
    </div><br />
		<br /><br />
		<?php
		$query = "SELECT * FROM products ORDER BY id ASC";
		$result = mysqli_query($connect, $query);
		if(mysqli_num_rows($result) > 0)
		{
		while($row = mysqli_fetch_array($result))
		{
		?>

		
		<div class="col-md-6">
		<form method="post" action="cart.php?action=add&id=<?php echo $row["id"]; ?>">
		<div style="border:1px solid black; margin:15px; padding:16px; align:canter;" align="center">
		<img src="pictures/<?php echo $row["images"]; ?>" class="img-responsive" /><br />
 
		<h4 class="text-info"><?php echo $row["title"]; ?></h4>
 
		<h4 class="text-danger">R <?php echo $row["price"]; ?></h4>
 
		<input type="text" name="quantity" value="1" class="form-control" />
 
		<input type="hidden" name="hidden_name" value="<?php echo $row["title"]; ?>" />
 
		<input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>" />
 
		<input type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-success" value="Add to Cart" />
 
		</div>
		</form>
		</div>
		<?php
		}
		}
		?>
		<div style="clear:both;"></div>
		<br />
		<div class="display">
    <h1 class="display-4">Order Details</h1>
    </div>
		
		<table class="table table-striped table-hover table-responsive-md">
		<tr>
		<th width="35%">Item Name</th>
		<th width="10%">Quantity</th>
		<th width="20%">Price</th>
		<th width="15%">Total</th>
		<th width="5%">Action</th>
		</tr>
		<?php
		if(!empty($_SESSION["shopping_cart"]))
		{
		$total = 0;
		foreach($_SESSION["shopping_cart"] as $keys => $values)
		{
		?>
		<tr>
		<td><?php echo $values["item_name"]; ?></td>
		<td><?php echo $values["item_quantity"]; ?></td>
		<td>R <?php echo $values["item_price"]; ?></td>
		<td>R <?php echo number_format($values["item_quantity"] * $values["item_price"], 2);?></td>
		<td><a href="cart.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Remove</span></a></td>
		</tr>
		<?php
		$total = $total + ($values["item_quantity"] * $values["item_price"]);
		}
		?>
		<tr>
		<td colspan="3" align="right">Total</td>
		<td align="right">R <?php echo number_format($total, 2); ?></td>
		<td></td>
		</tr>
		<?php
		}
		?>
		
		</table>
		
		</div>
	</div>
	<br />
	</body>
</html>
