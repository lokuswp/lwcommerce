<?php

namespace LSD\Migration;

class Report extends Migration
{
    // private $replaced_import = false;
    protected static $table_name;

    public function __construct()
    {
        static::$table_name = 'lsddonation_reports';
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
        $sql = "CREATE TABLE " . static::get_table_name() . " (
        report_id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        program_id bigint(20) NOT NULL,
        name mediumtext NOT NULL,
        phone mediumtext NOT NULL,
        email mediumtext NOT NULL,
        anonim tinytext NOT NULL,
        messages longtext NOT NULL,
        subtotal mediumtext NOT NULL,
        total mediumtext NOT NULL,
        currency tinytext NOT NULL,
        gateway tinytext NOT NULL,
        ip tinytext NOT NULL,
        status varchar(30) NOT NULL,
        date datetime NOT NULL,
        reference mediumtext NOT NULL,
        extra_fields longtext NOT NULL,
        PRIMARY KEY  (report_id)
    ) CHARACTER SET utf8 COLLATE utf8_general_ci;";



    dbDelta($sql);


    update_option('lsdd_migration_db_version', $this->version);
}

    /**
     * Migrasi Data dari table ini ke table B dengan copy column ke table b yg sudah terdefinisi column fieldnya
     * berupa simple array [,,,]
     */
    public function migrate_to_meta(Migration $table_meta)
    {
        $fields = $this->get_column_titles();

        $id = $fields[0];
        $tblname1 = $this->get_table_name();
        $tblname2 = $table_meta->get_table_name();

        $limit = 10000;
        $offset = 0;
        $rownum = $this->query()->execute("select count($id) as jml from $tblname1");


        $jmlrow = $rownum[0]['jml'];
        
        $max_execution_time = ini_get('max_execution_time');
        ini_set('max_execution_time', 0);

        while ($offset < $jmlrow) {
            $rows = $this->query()->execute("select * from $tblname1 limit $limit offset $offset");
            $data = array();
            $i = 0;
            foreach ($rows as $row) {
                $data[$i] = array(
                    'report_id' => $row['report_id'],
                    'meta_key' => 'user_id',
                    'meta_value' => $row['user_id']
                );
                $i++;
                $data[$i] = array(
                    'report_id' => $row['report_id'],
                    'meta_key' => 'program_id',
                    'meta_value' => $row['program_id']
                );
                $i++;
                $data[$i] = array(
                    'report_id' => $row['report_id'],
                    'meta_key' => 'name',
                    'meta_value' => $row['name']
                );
                $i++;
                $data[$i] = array(
                    'report_id' => $row['report_id'],
                    'meta_key' => 'messages',
                    'meta_value' => $row['messages']
                );
                $i++;
                $data[$i] = array(
                    'report_id' => $row['report_id'],
                    'meta_key' => 'date',
                    'meta_value' => $row['date']
                );
            }

            $table_meta->insert($data);


            $lendata = count($data);

            $offset = $offset + $limit;
        }

        

        ini_set('max_execution_time', $max_execution_time);
         return array(
            "fields" => $fields,
            "jml_row" => $rownum,
            
            

        );
    }
}

//Dependency Inversion
// $source = array("test" => "test");
// $report = new Report;
// var_dump($report->export(new Export_To_JSON($source)));
// var_dump($report->export(new Export_To_CSV($source)));

// $json = '{"test":"test"}';
// $csv = 'satu, dua, tiga';
// var_dump($report->import(new Import_From_JSON($json)));
// var_dump($report->import(new Import_From_CSV($csv)));
