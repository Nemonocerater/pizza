<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="description" content="">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
</head>
<body>

<?php include("header.php"); ?>

<h1>Make your pizza</h1>

<form method="POST" action="submit.php">

<!--
Name: <input type="text" name="name"><br>
Address: <input type="text" name="address"><br>
Phone Number: <input type="text" name="phone"><br>
-->

<h3>Cheese</h3>
<label><input type="radio" name="cheese" value="10" checked> Cheese</label>
<label><input type="radio" name="cheese" value="11"> Extra Cheese</label>

<h3>Sauce</h3>
<label><input type="radio" name="sauce" value="7" checked> Tomato Sauce</label>
<label><input type="radio" name="sauce" value="8"> Pesto Sauce</label>
<label><input type="radio" name="sauce" value="9"> Olive Oil</label>

<h3>Meat</h3>
<label><input type="checkbox" name="topping[]" value="1"> Pepperoni</label>
<label><input type="checkbox" name="topping[]" value="2"> Sausage</label>
<label><input type="checkbox" name="topping[]" value="3"> Meatballs</label>

<h3>Fruits & Veggies</h3>
<label><input type="checkbox" name="topping[]" value="4"> Peppers</label>
<label><input type="checkbox" name="topping[]" value="5"> Onions</label>
<label><input type="checkbox" name="topping[]" value="6"> Olives</label>

<br><br>
<input type="submit" value="Submit">

</form>

</body>
</html>

