<!DOCTYPE html>

<html>
<head>
	<title>Todo List</title>
</head>
<body>
	<h1>$_GET</h1>
	<?php var_dump($_GET); ?>
	<h1>$_POST</h1>
	<?php var_dump($_POST); ?>

	<h1>Todo List!</h1>
	<ul>
		<li>Study</li>
		<li>Make Payments</li>
		<li>Start Halloween Costume</li>
		<li>Study Some More</li>
		<li>Find Time To Relax</li>
		<li>Read book</li>
	</ul>

	<h1>Add Items To List</h1>
	<form method="POST">
		<p>
			<label for="add_items">Add Items</label>
			<input type="text" id="add_items" name="add_items" placeholder="Add Here">
		</p>
		<p>
        <input type="submit" value="Add">
    </p>
	</form>
</body>
</html>