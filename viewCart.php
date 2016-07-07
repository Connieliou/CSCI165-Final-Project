<?php 
session_start();	// Start the session before you write your HTML page
?>

<?php
//$totalPrice;

$found = 0;
// This function displays the contents of the shopping cart 

function show_cart() {
ini_set('display_errors','On');
error_reporting(E_ALL);
$con = mysqli_connect("dbserver.engr.scu.edu", "jmarcond", "00001025029", "sdb_jmarcond");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
    if (isset($_SESSION['cart'])){
		echo "These items are currently in your shopping cart:<br/><br/>"; 
		$mycart = $_SESSION['cart'];
		foreach ($mycart as $key => $value){
		if ($value >0) {
		    $sql = "SELECT * FROM Products where code = '$key'";
		    $result = $con -> query($sql);
		    if (!$result) {
		      die('Error: ' . mysqli_error($con));
		    }
		    $row = mysqli_fetch_assoc($result);
		    $fullname = $row['title'];
		    print("$fullname = $value"."<a href="."viewCart.php?drop=$key".
			">    Remove</a><br/>");
		}
		}
	}
	else {
		echo "No items in the cart";
	}
}

// This function removes an item from the shopping cart
function drop() {
ini_set('display_errors','On');
error_reporting(E_ALL);
$con = mysqli_connect("dbserver.engr.scu.edu", "jmarcond", "00001025029", "sdb_jmarcond");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

	 if (isset($_GET['drop'])){
	 	$dropItemId = $_GET['drop'];	 		 		
		if (isset($_SESSION['cart'])){
			$mycart = $_SESSION['cart'];
			if ($mycart[$dropItemId] > 1)
			  $mycart[$dropItemId] -= 1;
			else
			unset ($mycart[$dropItemId]);			
			$_SESSION['cart'] = $mycart; 			
		} 
	}  
} 

// Adds an item to the shopping cart
function addToCart() {
ini_set('display_errors','On');
error_reporting(E_ALL);
$con = mysqli_connect("dbserver.engr.scu.edu", "jmarcond", "00001025029", "sdb_jmarcond");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

	$addItemId = $_GET['add'];
	$sql="SELECT * FROM Products WHERE code= '$addItemId'";
	$result = $con->query($sql);
	if (!$result) {
	  die('Error: ' . mysqli_error($con));
	}
	$row = mysqli_fetch_assoc($result);
		 		 		
	if (isset($_SESSION['cart'])){
		$mycart = $_SESSION['cart'];
		$itemname = $row['title'];
		// if the item already exists, increment the count
		if (isset($mycart[$addItemId])){
		  if ($row['instock'] > $mycart[$addItemId]) {
		    $mycart[$addItemId]+= 1;
		    $_SESSION['cart'] = $mycart;
		    echo "$itemname has been added to your cart" ;
		   }		
		  else {
		    echo "Insufficient Stock";
		  } 
		}
		// if the item does not exist, add it to the cart
		  else {
		    if ($row['instock'] > 0) {
			$mycart[$addItemId] = 1;
			$_SESSION['cart'] = $mycart;
			echo "$itemname has been added to your cart<br/><br/>";
		    }
		    else {
		      echo "insufficient stock";
		    }
		  }		
	}
	else {
	  if ($row['instock'] > 0) {
		$itemname = $row['title'];
		$mycart = array();
		$mycart[$addItemId] = 1;
		$_SESSION['cart'] = $mycart; 
		echo "$itemname added to cart<br/>";
	}
	 else {
	    echo "Insufficient stock";
	 }  
}
}

function clearCart(){
ini_set('display_errors','On');
error_reporting(E_ALL);
$con = mysqli_connect("dbserver.engr.scu.edu", "jmarcond", "00001025029", "sdb_jmarcond");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
	if (isset($_GET['clear'])){
	 	if (isset($_SESSION['cart'])){
		      $mycart = ($_SESSION['cart']); 
		      unset($_SESSION['cart']);
		      echo "Shopping Cart Cleared ";
		} 
	}
}

function checkout() {
$con = mysqli_connect("dbserver.engr.scu.edu", "jmarcond", "00001025029", "sdb_jmarcond");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
	 if (isset($_SESSION['cart'])){
		$totalPrice = 0;
		$subtotal = 0;
		$totPrice = 0;
		global $found;
		echo "Checking out:<br/>"; 
		$mycart = $_SESSION['cart'];
		foreach ($mycart as $key => $value){
		  $sql = "SELECT * FROM Products WHERE code = '$key'";
		  $result = $con -> query($sql);
		  if (!$result) {
		    die('Error: ' . mysqli_error($con));
		  }
		  $row = mysqli_fetch_assoc($result);
		if ($value >0) {
		    // get the full widget name from the widgets array;
			$fullname = $row['title'];
			$itemcode = $row['code'];
			$itemquantity = $mycart[$key];
			$subtotal = $row['price'] * $itemquantity;
		}
		print("$itemquantity $fullname (#$itemcode) ="."      "."$"."$subtotal"."<br/>");
		  $totalPrice += $subtotal;
}
print("Subtotal = $".$totalPrice);
echo "<form action='checkoutPage.php'><button type='submit'>Checkout</button></form>";
}
if(isset($_SESSION['f'])) {
  echo "Thank you for being a loyal customer! Your discounted price is: <br/>";
  $totPrice = $totalPrice - ($totalPrice * .1);
  echo "$ $totPrice";
  
}
unset($_SESSION['f']);

}

function verifyMemberId() {
$con = mysqli_connect("dbserver.engr.scu.edu", "cliou", "00000956189", "sdb_cliou");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$check = $_POST['memId'];
global $found;

$sql="SELECT * FROM Member";
$result = $con->query($sql);
  if (!$result)
  {
    die('Error: ' . mysqli_error($con));
  } 
while($row = mysqli_fetch_assoc($result)) {
  if ($row['num'] == $check) {
    $found = 1;
$_SESSION['f'] = $found;
    print("Welcome back! <br/>");
    print("Your discount will be processed");
}
}
if(!$found) {
print ("Sorry, we did not find that member ID in our database");
}
}




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns = "http://www.w3.org/1999/xhtml">
<head><title>ViewCart</title></head>
<body>
<?php
	// if user has chosen "add"
	if ( isset($_GET['add'])) { 
		addToCart();
		unset($_GET['add']);
	}
	// if user has chosen "show cart"	
	elseif (isset($_GET['show'])){ 
       		
		show_cart();
		unset($_GET['show']);	
	}
	// if user has chosen "clear cart"	
	elseif (isset($_GET['clear'])){ 
		clearCart();
		unset($_GET['clear']);	
	}
	// if user has chosen "remove item from cart"		
	elseif (isset($_GET['drop'])){ 
		drop();
		unset($_GET['drop']);	
	}// if user has chosen "remove item from cart"		
	elseif (isset($_GET['checkout'])){ 
		checkout();
		unset($_GET['checkout']);	
	}	
	elseif (isset($_GET['verify'])) {
		verifyMemberId();
		unset($_GET['verify']);
	}
?>
<p> 
    <a href="ProductPage.php?">Back to the Catalog</a> 
</p> 
 </body>
</html>
