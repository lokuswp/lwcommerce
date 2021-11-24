<?php

namespace LSD\Migration;

use Active_Record\ActiveRecord;


/**
 * Kit Buat Berhubungan dengan Database
 * 
 */
abstract class Migration extends ActiveRecord
{
    //use ActiveRecord;
    //protected static $table_name;
    public $version;


    public function create_table()
    {
    }

    public function drop_table()
    {
        static::wpdb()->query("DROP TABLE " . $this->get_table_name());
    }

    public function empty_table()
    {
        static::wpdb()->query("TRUNCATE TABLE " . $this->get_table_name());
    }



    

    /**
     * Source data Dari ORM, bisa by Date, Selected
     * Harus bisa di Export, ke CSV, Excel, JSON
     */
    public function export(ExportInterface $export)
    {
        $export->export();
    }

    /**
     * Replace Import | Fresh Import
     * Bisa Import dari JSON, CSV, Excel
     */
    public function import(ImportInterface $import)
    {
        //echo json_encode($import->import(),JSON_PRETTY_PRINT);
        $this->insert($import->import());

        //return $import->import();

        //    $fp = fopen("file.json","w");
        //    fwrite($fp,json_encode($import->import(),JSON_PRETTY_PRINT));
        //    fclose($fp);

    }

    public function get_column_titles()
    {
        $fname = static::get_table_name();
        $fields = $this->query()->execute("show columns from $fname");
        $columns = array();
        foreach ($fields as $fld) {
            $columns[] = $fld['Field'];
        }
        return $columns;
    }

    public function run()
    {
    }
}
