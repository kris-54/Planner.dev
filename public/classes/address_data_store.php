<?php


class AddressDataStore {

    public $filename = '';

    public function __construct($filename)  {
        $this->filename = $filename;
    }

    public function write_csv($address_array)
    {
        
        $handle = fopen($this->filename, 'w');
            //$fields is an array of the row of data entered//
        foreach($address_array as $fields) 
        {
            fputcsv($handle, $fields);
        }    
        fclose($handle);// Code to read file $this->filename


    }

    public function read_address_book()
    {
        $address_book =[];
        $handle = fopen($this->filename, 'r');
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
}

?>