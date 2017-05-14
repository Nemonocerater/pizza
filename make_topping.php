<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="description" content="">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
</head>
<body>

<?php

require_once('pizza_db.php');

$topping_name = @$_GET['name'];
$topping_price = @$_GET['price'];

if ($topping_name && $topping_price) {
	$conn = getPizzaDbConnection();
	$insertSuccessful = insertTopping($conn, $topping_name, $topping_price);
	if ($insertSuccessful) {
		echo "You safely submitted $topping_name ($topping_price)";
	} else {
		echo "Error: " . var_export($insertSuccessful, true);
	}
} else {
	echo "You can submit a topping at this url with GET parameters '?name=topping_name&price=topping_price'";
}

?>

</body>
</html>

