<?php
namespace LSD\Migration;

class Report_Meta extends Migration
{
    protected static $table_name;
    public $nomo;

    public function __construct()
    {
        static::$table_name = 'lsddonation_reports_meta';
        $this->version = '0.1';
        $this->create_table();

        //init first save for bypass create methode new instance 
        // $this->attributes = array("name" => "name", "messages" => "messages");
        // $this->save();
        // echo static::get_table_name() . PHP_EOL;
        
    }

    public function create_table()
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        // Usually this would be done during a plugin install / activation routine. Running create_table() also works when you need to update the structure of the table after its been created
       
        // $sql = "CREATE TABLE " . static::get_table_name() .  
        // "(	meta_id bigint(20) NOT NULL ,
		// report_id bigint(20) NOT NULL,
		// meta_key text NOT NULL,
		// meta_value longtext NOT NULL        
		// ) CHARACTER SET utf8 COLLATE utf8_general_ci;
        // ALTER TABLE " . static::get_table_name() .
        // " ADD PRIMARY KEY (meta_id),
        // ADD KEY report_id (report_id),
        // ADD KEY meta_key (meta_key(191));";

        $sql = "CREATE TABLE " . static::get_table_name() . "(
            meta_id bigint(20) NOT NULL AUTO_INCREMENT,
            report_id bigint(20) NOT NULL,
            meta_key varchar(255) DEFAULT NULL,
            meta_value longtext DEFAULT NULL,
            PRIMARY KEY (meta_id)
            
          )  CHARACTER SET utf8 COLLATE utf8_general_ci;";
        
        dbDelta($sql);



        update_option('lsdd_migration_db_version', $this->version);

    }




}
