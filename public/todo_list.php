<?php

define('FILENAME', 'data/mylist.txt');

// function open_file($filename = FILENAME)
// {
//     $handle = fopen($filename,'r');
//     //pass in the file to open
//     $content = trim(fread($handle, filesize($filename)));
//     //content is the the file and file size
//     $content = explode("\n", $content);
//     //content is a string so explode to return an array//
//     // $items = $content if this is done without merge lists will not combine
//     fclose($handle);
//     //close the file once rendered
//     return $content;
// }

// function save_file($items, $filename = FILENAME)
// {
//     $handle = fopen($filename, 'w');
//     foreach($items as $item) {
//         fwrite($handle,$item . PHP_EOL);
//     }    
//     fclose($handle);
    
// }

// $items = open_file();


require_once "../inc/filestore.php";

$todo_list = new Filestore(FILENAME);


    
$items = $todo_list->read(); 

// Update your TODO list application to throw an if someone tries to enter a TODO item that is
// empty or is longer than 240 characters. Research and use PHP string function(s) to perform this task.

//adding items to list
if (isset($_POST['add_items']))  {
    try {
        if(strlen($_POST['add_items']) > 240) {
            throw new Exception('item entered must be under 240 characters');
        } elseif (strlen($_POST['add_items']) == 0) {
            throw new Exception('Item is too small');
        }
        $items[] = $_POST['add_items'];
        $todo_list->write_lines($items);

    } catch(Exception $e) {
        $errorMessage = $e->getMessage();
    }
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
    $todo_list->write($items);
}



if (count($_FILES) > 0 && $_FILES['file1']['error'] === UPLOAD_ERR_OK) {
    if($_FILES['file1']['type'] === 'text/plain') {
    
        // Set the destination directory for uploads
        $upload_dir = '/vagrant/sites/planner.dev/public/uploads/';
        // Grab the filename from the uploaded file by using basename
        $filename = basename($_FILES['file1']['name']);
        // Create the saved filename using the file's original name and our upload directory
        $saved_filename = $upload_dir . $filename;
        // Move the file from the temp location to our uploads directory
        move_uploaded_file($_FILES['file1']['tmp_name'], $saved_filename);
        $newObject = new Filestore($saved_filename);        
        $uploadedList = $newObject->read();
        $items = array_merge($items,$uploadedList);
        $todo_list->write($items);

    // $items = array_merge($saved_filename,$items);
    } else {
        echo "<h1>ERROR must be a text/plain file ONLY</h1>";
    }
}

// Check if we saved a file
// if (isset($saved_filename)) {
//     // If we did, show a link to the uploaded file
//     echo "<p>You can download your file <a href='/uploads/{$filename}'>here</a>.</p>";
// }
if (isset($errorMessage)) {
    echo "<h1>$errorMessage</h1>";
}
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

        <?php foreach($items as $key => $item) : ?>
             <li>  <a href="?remove=<?= $key; ?>" >REMOVE</a> - <?= htmlspecialchars(strip_tags($item)); ?></li>           
        <?php endforeach; ?>
        
    </ul>

    <h1 class="blood">Add Items To List</h1>   
    <form method="POST" action="todo_list.php">
           <p>
            <label for="add_items">Add Items</label>
            <input type="text" id="add_items" name="add_items" placeholder="Add Here">
            <input type="submit" value="Add">
           </p>
    </form>

    <h1 class="blood">Upload File</h1>
    <form method="POST" enctype="multipart/form-data" action="/todo_list.php">
        <p>
            <label for="file1">File to upload: </label>
            <input type="file" id="file1" name="file1">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
    </form>

</body>
</html>
