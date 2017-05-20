<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="description" content="">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
</head>
<body>

<?php include("header.php"); ?>

<h2>Orders</h2>

<style>
table {
	width: 100%;
}
thead td {
	background-color: #eeeeee;
	font-size: 1.4em;
}
</style>


<?php

require_once('pizza_db.php');

$conn = getPizzaDbConnection();
$orders = getOrders($conn);

function getOrders($conn) {
	$order_sql = <<< SQL
		SELECT o.order_id,
			o.order_date,
			os.order_status_cd_desc,
			s.size_desc,
			p.price,
			p.pizza_desc,
			t.topping_desc
		FROM p_orders o,
			p_order_details d,
			p_pizza p,
			p_pizza_topping pt,
			p_toppings t,
			p_pizza_sizes s,
			p_order_status_codes os
		WHERE d.order_id = o.order_id
			AND p.pizza_id = d.pizza_id
			AND pt.pizza_id = p.pizza_id
			AND t.topping_id = pt.topping_id
			AND s.size_id = d.size_id
			AND os.order_status_cd = o.order_status_cd
		ORDER BY o.order_id;
SQL;

	$orders = [];

	$order_response = $conn->query($order_sql);
	foreach ($order_response as $order) {
		$id = $order['order_id'];
		$date = $order['order_date'];
		$status = $order['order_status_cd_desc'];
		$size = $order['size_desc'];
		$price = $order['price'];
		$desc = $order['pizza_desc'];
		$topping = $order['topping_desc'];

		if (array_key_exists($id, $orders)) {
			$orders[$id]['toppings'] []= $topping;
		} else {
			$orders[$id] = [
				"id" => $id,
				"date" => $date,
				"status" => $status,
				"size" => $size,
				"price" => $price,
				"desc" => $desc,
				"toppings" => [$topping]
			];
		}
	}

	return $orders;
}

?>

<table>

<thead>
	<td>Id</td>
	<td>Date</td>
	<td>Status</td>
	<td>Size</td>
	<td>Price</td>
	<td>Description</td>
	<td>Topping Name</td>
</thead>

<?php
foreach ($orders as $order) {
	echo "<tr>";
	echo "<td>" . $order['id'] . "</td>";
	echo "<td>" . $order['date'] . "</td>";
	echo "<td>" . $order['status'] . "</td>";
	echo "<td>" . $order['size'] . "</td>";
	echo "<td>$" . $order['price'] . "</td>";
	echo "<td>" . $order['desc'] . "</td>";
	echo "<td><ul>";
	foreach ($order['toppings'] as $topping) {
	   echo "<li>$topping</li>";
	}
	echo "</ul></td>";
	echo "</tr>";
}
?>

</table>

</body>
</html>

