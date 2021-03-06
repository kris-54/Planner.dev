<?php


// require_once('classes/address_data_store.php');

// class InvalidInputException extends Exception {}

//address object//
// $ads = new AddressDataStore('address_book.csv');
// var_dump($ads);
// var_dump($ads->filename);
// $address_book = $ads->read();
// var_dump($address_book);

$dbc = new PDO('mysql:host=127.0.0.1;dbname=addresses', 'codeup', 'codeuprocks');

// Tell PDO to throw exceptions on error
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



function getItems($dbc){
    return $dbc->query("SELECT n.id, n.contact_name, n.contact_phone
        AS full_name, a.address, a.city, a.state, a.zip 
        FROM names n
        JOIN names_address na
            ON na.name_id = n.id
        JOIN addresses a
            ON a.id = na.address_id")->fetchAll(PDO::FETCH_ASSOC);

}

$address_book = getItems($dbc);






if(!empty($_POST)) {
    try {
        if (
            !empty($_POST['name']) && strlen($_POST['name']) <= 125 &&
            !empty($_POST['address']) && strlen($_POST['address']) <=125 &&
            !empty($_POST['city'])  && strlen($_POST['city']) <= 125 &&
            !empty($_POST['state']) && strlen($_POST['state']) <= 125 &&
            !empty($_POST['zip'])&& strlen($_POST['zip']) <= 125
        ) {
            $new_address = [
                $_POST['name'],
                $_POST['address'],
                $_POST['city'],
                $_POST['state'],
                $_POST['zip'],
                $_POST['phone']
            ];

            
            
        } else {
                foreach ($_POST  as $key => $value) 
                {
                    if (empty($value) || strlen($value) > 125) {
                        throw new InvalidInputException('<h2>can not be empty and/or max length is 125 characters!!!!</h2>');
                    }
                }   
            }
        } 
        catch(InvalidInputException $e) {
            echo "<h1>InvalidInputException: </h1>" . $e->getMessage() . PHP_EOL;

        }
}


if (isset($errorMessage)) {
    echo "<h1>$errorMessage</h1>";
}
//upload file to address book
//save uploaded list to address book//
if (count($_FILES) > 0 && $_FILES['file1']['error'] === UPLOAD_ERR_OK) {
    if($_FILES['file1']['type'] === 'text/csv') {

        // Set the destination directory for uploads
        $upload_dir = '/vagrant/sites/planner.dev/public/uploads/';
        // Grab the filename from the uploaded file by using basename
        $filename = basename($_FILES['file1']['name']);
        // Create the saved filename using the file's original name and our upload directory
        $saved_filename = $upload_dir . $filename;
        // Move the file from the temp location to our uploads directory
        move_uploaded_file($_FILES['file1']['tmp_name'], $saved_filename);

        $uploadedList = new AddressDataStore($saved_filename);
        //uploaded list is the file...
        $newStuff = $uploadedList->read();
        // var_dump($newStuff);
        //new stuff is array of uploaded list
        $address_book = array_merge($address_book,$newStuff);
        $ads->write($address_book);
    } else {
        echo "<h1>ERROR must be a csv file ONLY</h1>";
    }

}            


if (isset($_GET['remove'])) 
{
    // Define variable $keyToRemove according to value
    $keyToRemove = $_GET['remove'];
    // Remove item from array according to key specified
    unset($address_book[$keyToRemove]);
    // Numerically reindex values in array after removing item
    $address_book = array_values($address_book);
    // Save to file
    $ads->write_csv($address_book);
}

?>

<html>
<head>
    <title>Address Book</title>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <style type="text/css">

    a:link {
        color: black;
    }
    #mainheader {
        border: 3px solid;
        text-align: center;
        margin: auto;
        width: 300px;
    }
    body {
        background-image: url(/img/brown.jpg);
        /*used the steps below to ensure image scales with browser*/
        background-repeat: no-repeat; 
        background-position: center center;
        background-attachment: fixed;    
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        text-decoration: bold;      
    }
    .table-bordered {
        font-weight: bold;
    }

    #uploads {
        border: 2px solid;
        width: 225px;
        height: 180px;
        margin-top: -300px;
        margin-left: 800px;
    }

    .btn-default {
        margin-left: 550px;
    }
    
    </style>
</head>
<body>
    <!-- <h2>$_GET</h2>
    <?php var_dump($_GET); ?>
    <h2>$_POST</h2>
    <?php var_dump($_POST); ?> -->

    
    <h1 id="mainheader">ADDRESS BOOK</h1>
    

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>Zip</th>
            <th>Phone</th>
            <th>Remove</th>
        </tr>

        <? foreach ($address_book as $key => $fields) : ?>
        <tr>
            <? foreach($fields as $value) : ?>
                <td><?= htmlspecialchars(strip_tags($value)); ?></td>
            <?endforeach; ?>
            <td><a href="<?= '?remove=' . $key; ?>">REMOVE</a></td>
        </tr>
        <? endforeach; ?>  
             
    </table>


    <form class="form-horizontal" role="form" method="POST" action="address_book.php">
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="name" name="name" placeholder="required" required>
            </div>
        </div>

        <div class="form-group">
            <label for="address" class="col-sm-2 control-label">Address</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="address" name="address" placeholder="required" required>
            </div>
        </div>

        <div class="form-group">
            <label for="city" class="col-sm-2 control-label">City</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="city" name="city" placeholder="required" required>
            </div>
        </div>

        <div class="form-group">
            <label for="state" class="col-sm-2 control-label">State</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="state" name="state" placeholder="required" required>
            </div>
        </div>

        <div class="form-group">
            <label for="zip" class="col-sm-2 control-label">Zip</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="zip" name="zip" placeholder="required" required>
            </div>
        </div>

        <div class="form-group">
            <label for="phone" class="col-sm-2 control-label">Phone</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="phone" name="phone" placeholder="required" required>
            </div>
        </div>
        
          <button type="submit" class="btn btn-default">Submit</button>
          
    </form>  

    <div id="uploads">
    <h1 >Upload File</h1>
    <form method="POST" enctype="multipart/form-data" action="/address_book.php">
        <p>
            <label for="file1">File to upload: </label>
            <input type="file" id="file1" name="file1">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
    </form>
    </div>
    


</body>
</html>