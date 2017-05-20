<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="description" content="">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
</head>
<body>

<?php include("header.php"); ?>

<?php

require_once('pizza_db.php');

$topping_name = @$_GET['name'];
$topping_price = @$_GET['price'];

if ($topping_name && $topping_price) {
	$conn = getPizzaDbConnection();
	$insertSuccessful = saveTopping($conn, $topping_name, $topping_price);
	if ($insertSuccessful) {
		echo "You safely submitted $topping_name ($topping_price)";
	} else {
		echo "Error: " . var_export($insertSuccessful, true);
	}
} else {
	echo "You can submit a topping at this url with GET parameters '?name=topping_name&price=topping_price'";
}

function saveTopping($conn, $pizzaId, $toppingId) {
	$pizza_topping_sql = "INSERT INTO p_toppings (topping_category_id, topping_id) VALUES ($pizzaId, $toppingId);"; 
	$toppingSaved = $conn->query($pizza_topping_sql);
	if (!$toppingSaved) {
		throw new Exception("The topping was not saved correctly. Topping Id: $toppingId");
	}
	return $conn->insert_id;
}

?>

</body>
</html>

