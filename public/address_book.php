<?php


define('FILENAME', 'address_book.csv');


function save_csv($address_book, $filename = FILENAME)
{
    $handle = fopen($filename, 'w');
    //$fields is an array of the row of data entered//
    foreach($address_book as $fields) 
    {
        fputcsv($handle, $fields);
    }    
    fclose($handle);
    
}

function read_file($filename = FILENAME)
{
    $address_book =[];
    $handle = fopen($filename, 'r');
    while(!feof($handle)) 
    {   
        $fields = fgetcsv($handle);
        if(!empty($fields)) 
        {
            $address_book[] = $fields;
        }
    }
    fclose($handle);
    return $address_book;
    
}






// $address_book =[];

$address_book = read_file();

if (!empty($_POST['name']) && !empty($_POST['address']) && !empty($_POST['city']) && !empty($_POST['state']) && !empty($_POST['zip'])) 
{
    $new_address = [
        $_POST['name'],
        $_POST['address'],
        $_POST['city'],
        $_POST['state'],
        $_POST['zip'],
        $_POST['phone']
    ];

    array_push($address_book, $new_address);
    save_csv($address_book);
    
} else 
    {
        foreach ($_POST  as $key => $value) 
        {
            if (empty($value)) 
            {
                echo ($key) .  " is required ";
            }
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
    save_csv($address_book);
}





?>

<html>
<head>
    <title>Address Book</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <style type="text/css">

    a:link {
        color: black;
    }
    h1 {
        text-align: center;
    }
    body {
        background-image: url(/img/brown.jpg);
        background-size: cover;
        background-repeat: no-repeat; 
        text-decoration: bold;      
    }
    .table-bordered {
        border-color: red;
        border: 4px solid;
    }
    
    </style>
</head>
<body>
    <!-- <h2>$_GET</h2>
    <?php var_dump($_GET); ?>
    <h2>$_POST</h2>
    <?php var_dump($_POST); ?> -->

    <div id="header">
    <h1>ADDRESS BOOK</h1>
    </div>

    <table class="table table-bordered">
        <tr>
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


        


    <form method="POST" action="address_book.php">
         <p>
            <label for="name">NAME</label>
            <input type="text" id="name" name="name" placeholder="required"> 
        

        
            <label for="address">ADDRESS</label>
            <input type="address" id="address" name="address" placeholder="required">
        

    
            <label for="city">CITY</label>
            <input type="city" id="city" name="city" placeholder="required">
        

    
            <label for="state">STATE</label>
            <input type="state" id="state" name="state" placeholder="required">
        

    
            <label for="zip">ZIP</label>
            <input type="zip" id="zip" name="zip" placeholder="Zip">
        

    
            <label for="phone">PHONE</label>
            <input type="phone" id="phone" name="phone" placeholder="Phone Here">

        <p>
            <button type="submit">Submit</button>
        </P>



    </form>


</body>
</html>