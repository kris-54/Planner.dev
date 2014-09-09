<?php


define('FILENAME', 'data/mylist.txt');



function open_file($filename = FILENAME)
{
    $handle = fopen($filename,'r');
    //pass in the file to open
    $content = trim(fread($handle, filesize($filename)));
    //content is the the file and file size
    $content = explode("\n", $content);
    //content is a string so explode to return an array//
    // $items = $content if this is done without merge lists will not combine
    fclose($handle);
    //close the file once rendered
    return $content;
}

function save_file($items, $filename = FILENAME)
{
    $handle = fopen($filename, 'w');
        foreach($items as $item) {
            fwrite($handle,$item . PHP_EOL);
        }    
    fclose($handle);
    
}
$items = [];

$items = open_file();

?>

<!DOCTYPE html>

<html>
<head>
	<title>Todo List</title>
	<link rel="stylesheet" type="text/css" href="/css/todo.css">
</head>
<body>
	<!-- <h2>$_GET</h2>
	<?php var_dump($_GET); ?>
	<h2>$_POST</h2>
	<?php var_dump($_POST); ?> -->
	
	<div id="bucket">
	<h1 class="blood">Todo List!</h1>
	<ul>
		<?php
			
			if (isset($_POST['add_items'])) {
				$items[] = $_POST['add_items'];
				save_file($items);
			}



			// Check for key 'remove' in GET request
			if (isset($_GET['remove'])) {
			// Define variable $keyToRemove according to value
				$keyToRemove = $_GET['remove'];
				// Remove item from array according to key specified
				unset($items[$keyToRemove]);
				// Numerically reindex values in array after removing item
				$items = array_values($items);
				// Save to file
				save_file($items);
			}

 			foreach($items as $key => $item) {
 				echo '<li> <a href=' . "?remove=$key" . '>COMPLETE</a> - ' . "$item</li>";
			}

		?>
	</ul>

	<h1 class="blood">Add Items To List</h1>
	<form method="POST" action="todo_list.php">


		<p>
			<label for="add_items">Add Items</label>
			<input type="text" id="add_items" name="add_items" placeholder="Add Here">
        	<input type="submit" value="Add">
		</p>


		
	</form>
	</div>

</body>
</html>
