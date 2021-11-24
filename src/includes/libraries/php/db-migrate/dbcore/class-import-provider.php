<?php

namespace LSD\Migration;

interface ImportInterface
{
    public function import();
}

class Import_From_JSON implements ImportInterface
{
    private $source;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function import()
    {
        $result = json_decode($this->source,true);
        return $result;
    }
}


class Import_From_CSV implements ImportInterface
{
    private $source;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function import()
    {
        // Split $data by "\n"
        $lines = explode("\n", $this->source);
        
        // Prepare variable $output
        $output = array();
        
        foreach ($lines as $line) 
        {
            $line = preg_replace("/\r|\n/", "", $line);
            $tokens = explode(",", $line); 
            array_push($output,$tokens);
        }

         //get field name
         $fields = $output[0];

         // cut first array element to process data array only
         array_shift($output);
 
         $data = array();
         $c = count($output) - 1;
 
         for ($i = 0; $i < $c; $i++) 
         {
             $data[$i] = array_combine($fields, $output[$i]);
         }
         
        return $data;
    }

}
