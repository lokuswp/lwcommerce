<?php
namespace LSD\Migration;

interface ExportInterface
{
    public function export();
}

class Export_To_JSON implements ExportInterface
{
    private $source;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function export()
    {
        header( 'Content-Type: application/json' );
        header( 'Content-Disposition: attachment; filename=export_report.json' );
    
        // clean output buffer
        ob_end_clean();
        
        echo json_encode($this->source);
    
        // flush buffer
        ob_flush();
        
        // use exit to get rid of unexpected output afterward
        exit();
    }
}

class Export_To_CSV implements ExportInterface
{
    private $source;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function export()
    {
        
         header( 'Content-Type: application/csv' );
         header( 'Content-Disposition: attachment; filename=export_report.csv' );
    
        // // clean output buffer
        ob_end_clean();
          
        $fields = array_keys($this->source[0]);
        echo implode(",",$fields) . PHP_EOL;
        $con = PHP_EOL;
        for ($i=0; $i < count($this->source); $i++) 
        { 
            if ($i>=count($this->source)-1) $con = ""; 
            echo implode(",",$this->source[$i]) . $con;
        }
        
            


    
        // // flush buffer
         ob_flush();
        
        // // use exit to get rid of unexpected output afterward
        // exit();
    
    }
}
