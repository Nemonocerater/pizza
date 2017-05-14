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

$saveSuccessful = false;
$description = "custom pizza";
$size = 3;

$toppings = $_POST['topping'];
$sauce = $_POST['sauce'];
$cheese = $_POST['cheese'];

$allToppings = $toppings;
$allToppings []= $sauce;
$allToppings []= $cheese;

$conn = getPizzaDbConnection();
try {
	$conn->begin_transaction();
	$toppingValues = getToppingsByIds($conn, $allToppings);
	$price = getPriceOfPizza($conn, $toppingValues);
	$pizzaId = savePizza($conn, $description, $allToppings, $price);
	$orderId = saveOrder($conn, $pizzaId, $size, $price);
	$conn->commit();
	$saveSuccessful = true;
} catch (Exception $e) {
	$conn->rollback();
	throw $e;
}
$conn->close();

function getToppingsByIds($conn, $toppingIds) {
	$toppingList = implode(',', $toppingIds);
	$topping_sql = "SELECT topping_id, topping_desc, topping_price FROM p_toppings WHERE topping_id IN ($toppingList);";
	$topping_return = $conn->query($topping_sql);

	$toppingValues = [];
	foreach ($topping_return as $topping) {
		$id = $topping['topping_id'];
		$toppingValues[$id] = [
			"topping_id" => $id,
			"topping_price" => floatval($topping['topping_price']),
			"topping_desc" => $topping['topping_desc']
		];
	}
	return $toppingValues;
}

function savePizza($conn, $description, $toppings, $price) {
	$pizza_sql = "INSERT INTO p_pizza (pizza_desc, price) VALUES ('$description', $price);";
	$pizzaSaved = $conn->query($pizza_sql);
	if (!$pizzaSaved) {
		throw new Exception("The pizza was not saved correctly!");
	}
	$pizzaId = $conn->insert_id;

 	foreach ($toppings as $toppingId) {
		savePizzaTopping($conn, $pizzaId, $toppingId);
	}

	return $pizzaId;
}

function getPriceOfPizza($conn, $toppings) {
	$total = 0;
	foreach ($toppings as $topping) {
		$price = $topping['topping_price'];
		$total += $price;
	}
	return $total;
}

function savePizzaTopping($conn, $pizzaId, $toppingId) {
	$pizza_topping_sql = "INSERT INTO p_pizza_topping (pizza_id, topping_id) VALUES ($pizzaId, $toppingId);"; 
	$toppingSaved = $conn->query($pizza_topping_sql);
	if (!$toppingSaved) {
		throw new Exception("The topping was not saved correctly. Topping Id: $toppingId");
	}
	return $conn->insert_id;
}

function saveOrder($conn, $pizzaId, $size, $price) {
	$order_sql = "INSERT INTO p_orders (order_date, customer_id, order_type_cd, order_status_cd) VALUES (CURDATE(), 1, 0, 1);"; 
	$orderSaved = $conn->query($order_sql);
	if (!$orderSaved) {
		throw new Exception("The order was not saved correctly: " . $conn->error);
	}
	$orderId = $conn->insert_id;

	$order_details_sql = "INSERT INTO p_order_details (order_id, pizza_id, size_id, pizza_price) VALUES " .
		"($orderId, $pizzaId, $size, $price);"; 
	$orderDetailsSaved = $conn->query($order_details_sql);
	if (!$orderDetailsSaved) {
		throw new Exception("The order details record was not saved correctly.");
	}

	return $orderId;
}

function getToppingDesc($id) {
	global $toppingValues;
	return $toppingValues[$id]['topping_desc'];
}

?>

<?php if ($saveSuccessful): ?>
	<h2>Your order:</h2>
	<h4>Price: <?php echo $price; ?></h4>
	<p><strong>Cheese:</strong> <?php echo getToppingDesc($cheese); ?></p>
	<p><strong>Sauce:</strong> <?php echo getToppingDesc($sauce); ?></p>
	<p>
		<strong>Toppings:</strong>
		<ul>
			<?php
			foreach ($toppings as $topping) {
				$desc = getToppingDesc($topping);
				echo "<li>$desc</li>";
			}
			?>
		</ul>
	</p>
<?php else: ?>
	<p style="color: red;">Your order was not processed successfully.  Please try again in a few minutes.</p>
<?php endif; ?>

</body>
</html>

