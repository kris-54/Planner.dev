<?php


define('FILENAME', 'address_book.csv');

$address_book = [
    ['The White House', '1600 Pennsylvania Avenue NW', 'Washington', 'DC', '20500'],
    ['Marvel Comics', 'P.O. Box 1527', 'Long Island City', 'NY', '11101'],
    ['LucasArts', 'P.O. Box 29901', 'San Francisco', 'CA', '94129-0901']
];



function save_csv($address_book, $filename = FILENAME)
{
    $handle = fopen($filename, 'w');
    foreach($address_book as $fields) {
        fputcsv($handle, $fields);
    }    
    fclose($handle);
    
}


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
    
}
   



?>

<html>
<head>
	<title>Address Book</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <style>
        


    </style>
</head>
<body>
    <!-- <h2>$_GET</h2>
    <?php var_dump($_GET); ?>
    <h2>$_POST</h2>
    <?php var_dump($_POST); ?> -->
    
    <h1>ADDRESS BOOK</h1>

    <table class="table table-striped">

        <tr>
            <th>Name</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>Zip</th>
            <th>Phone</th>
        </tr>

        <? foreach ($address_book as $fields) : ?>
        <tr>
            <? foreach($fields as$key => $value) : ?>
                <td><?= htmlspecialchars(strip_tags($value)); ?></td>
            <?endforeach; ?>
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