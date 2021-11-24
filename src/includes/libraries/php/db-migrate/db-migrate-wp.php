<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              
 * @since             1.0.0
 * @package           Plugin_Test_BRo
 *
 * @wordpress-plugin
 * Plugin Name:       DB Migrate Plugin Test
 * Plugin URI:        
 * Description:       DB Migrate Plugin Test : Migrasi , Impor, Ekspor CSV dan JSON
 * Version:           1.0.0
 * Author:            aikhacode
 * Author URI:        http://fastkrisna.my.id/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       aikhacode-test
 * Domain Path:       /languages
 */

use LSD\Migration\Export_To_CSV;
use LSD\Migration\Import_From_CSV;
use LSD\Migration\Report;
use LSD\Migration\Report_Meta;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('AIKHACODE_TEST_VERSION', '1.0.0');

require 'dbcore/bootstrap.php';

class MyDBMigrate
{


    public $r_meta;
    public $r_reports;
    public $tbname1, $tbname2;

    function __construct()
    {
        $this->r_meta = new Report_Meta();
        $this->r_reports = new Report();
        $this->tbname1 = $this->r_reports->get_table_name();
        $this->tbname2 = $this->r_meta->get_table_name();


        register_activation_hook(__FILE__, [$this, 'activate_plugin_dbmigrate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate_dbmigrate']);


        wp_enqueue_style('db_migrate_wp', plugin_dir_url(__FILE__) . 'lib/datatables.min.css', array(), '1.0.0', 'all');
        wp_enqueue_script('db_migrate_wp', plugin_dir_url(__FILE__) . 'lib/datatables.min.js', array(), '1.0.0', false);

        add_action('wp_ajax_my_action', [$this, 'my_action']);
        add_action('wp_ajax_nopriv_my_action', [$this, 'my_action']);

        add_action('admin_menu', [$this, 'test_plugin_setup_menu']);
    }

    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-plugin-name-activator.php
     */
    public function activate_plugin_dbmigrate()
    {
    }

    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-plugin-name-deactivator.php
     */
    public function deactivate_dbmigrate()
    {
    }

    public function test_plugin_setup_menu()
    {
        add_menu_page('Test Plugin Page', 'Test Plugin', 'manage_options', 'test-plugin', [$this, 'test_init']);
    }

    public function test_init()
    {


        $tbname1 = $this->r_reports->get_table_name();
        $tbname2 = $this->r_meta->get_table_name();

        echo '<h1>Test Page case from Table wp_lsddonation_report to meta </h1>';
        echo "<h3><span style='vertical-align:top'>Table Name <select name='table_name' id='table_name'><option value='$tbname1'>$tbname1</option><option value='$tbname2'>$tbname2</option></select></span>";

        ?>
        <style>
            button {
                margin: 15px;
            }
        </style>

        <span style="margin-left:10px;vertical-align:top"> || Aksi <select id='aksi'>
            <option value='emptytable'>Empty Current Table</option>
            <option value='refreshtable'>Refresh Current Table View</option>
            <option value='imporcsv'>Impor From CSV To Reports</option>
            <option value='exporcsv'>Ekspor To CSV From Reports</option>
        </select>

        <button type="submit" id="jalankan" class="button">JALANKAN Aksi</button>
    </span>
</h3>

<h3>Test Case -> <button style="vertical-align:middle" class="button" type='submit' id='migrate_now'>Migrasi To Report Meta</button>
    <p style="display:inline;margin-left:10px;" id="msg_migrate"></p>
</h3>


<!-- <h2>Pilih untuk menfilter table Dari Tgl <input type='date' id='from_date'> s/d Tgl <input type='date' id='to_date'></h2> -->


<div id="isi"></div>


<script type="text/javascript">
    var from_date;
    var to_date;
    var tables, columns, titles;
    var curr_table_name = "<?= $tbname1; ?>";
    var curr_columns;

    function refresh_tables() {

    }

    function write_table() {
        jQuery.ajax({
            url: ajaxurl,
            dataType: 'json',
            type: 'POST',
            data: {
                action: 'my_action',
                atype: 'get_arr_titles',
                fname: curr_table_name
            }
        }).done(function(data) {
            var ret = "<table id='tables' class='display' width='100%'><thead><tr>";
            var th = "";
            data.forEach(function(val) {
                th = th + `<th>${val.data}</th>`;
            });

            ret = ret + th + "</tr></thead><tbody></tbody></table>";
            curr_columns = data;

            if (tables !== undefined) tables.destroy();

            jQuery('#isi').html(ret);

            tables = jQuery('#tables').DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "ajax": {
                    "url": ajaxurl,
                    "data": {
                        'action': "my_action",
                        'atype': 'get_data',
                        'fname': curr_table_name
                                // 'from_date' : function(){ return from_date;},
                                // 'to_date' : function(){ return to_date;}


                            },
                            "dataType": "json",
                            "type": "POST"
                        },
                        "columns": curr_columns


                    });
        });



    }

            //change file name auto refresh 
            jQuery('#table_name').change(function() {
                var fn = jQuery(this).val();
                curr_table_name = fn;
                t1 = "<?= $tbname1; ?>";
                t2 = "<?= $tbname2; ?>";

                if (fn === t1) {
                    jQuery("option[value='imporcsv']").removeAttr('disabled');
                    jQuery("option[value='exporcsv']").removeAttr('disabled');
                } else
                if (fn === t2) {
                    jQuery("option[value='imporcsv']").attr('disabled', 'disabled');
                    jQuery("option[value='exporcsv']").attr('disabled', 'disabled');

                }

                write_table();


            });
            var w1;
            jQuery('#jalankan').click(function() {
                var aksi = jQuery('#aksi').val();
                switch (aksi) {
                    case 'emptytable':
                    jQuery.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            action: 'my_action',
                            atype: 'emptytable',
                            fname: curr_table_name
                        }
                    }).done(function(data) {
                        console.log(data);
                        tables.ajax.reload();
                    });
                    break;
                    case 'refreshtable':
                    tables.ajax.reload();

                    break;
                    case 'imporcsv':
                    w1 = new Date().getTime();
                    jQuery.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            action: 'my_action',
                            atype: 'imporcsv'
                        },
                        success: function() {

                        }

                    }).done(function(data) {
                        console.log((new Date().getTime() - w1) / 1000, 'sec');
                        tables.ajax.reload();
                    }).fail(function(err) {
                        console.log(err);
                    });
                    break;
                    case 'exporcsv':
                    location.href = ajaxurl + "?action=my_action&atype=exporcsv";
                    break;
                }
            });

            var selesai;

            jQuery('#migrate_now').click(function(e) {
                e.preventDefault();

                jQuery('#msg_migrate').html("Running...");
                selesai = new Date().getTime();

                jQuery.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'my_action',
                        atype: 'migrate'
                    },
                    
                    success: function(data) {


                    }

                }).done(function(data) {
                    console.log(data);
                    waktu = (new Date().getTime() - selesai) / 1000;
                    str = `done.. for ${waktu} sec`;
                    jQuery('#msg_migrate').html(str);


                }).fail(function(err) {
                    console.log(err);
                });
            });

            jQuery(document).ready(function() {

                write_table();




            });
        </script>
        <?php

    }

    public function get_titles($fname)
    {

        $fields = $this->run_query_as_array("show columns from $fname");
        $columns = array();
        foreach ($fields as $fld) {
            $columns[] = $fld['Field'];
        }
        return $columns;
    }

    public function get_arr_data_title()
    {
        $fn = $_POST['fname'];
        $datacol = $this->get_titles($fn);
        $ret = [];
        foreach ($datacol as $dt) {
            $ret[] = array('data' => $dt);
        }
        return $ret;
    }

    // public function write_table()
    // {
    //     $ret = "<table id='tables' class='display' width='100%'><thead><tr>";
    //     $columns = $this->get_titles();
    //     $th = '';
    //     foreach ($columns as $col) {
    //         $th = $th . "<th>$col</th>";
    //     }
    //     $ret = $ret . $th . "</tr></thead><tbody></tbody></table>";

    //     return trim(static::$curr_table_name);
    // }

    protected function exporcsv()
    {
        $out = new Export_To_CSV($this->r_reports->query()->get_results());
        $this->r_reports->export($out);
    }

    protected function imporcsv()
    {
        //$fn = $_POST['fname'];
        for ($i = 0; $i < 100; $i++) {
            $out = new Import_From_CSV(file_get_contents(__DIR__ . '/test/file.csv'));
            $this->r_reports->import($out);
        }


        // $d = $this->r_reports->query()->get_results();
        // $fld = array_keys($d[0]);

        // foreach ($d as &$d1) {
        //     $d1 = array_values($d1);
        // }
        // $c = array();
        // foreach ($fld as $fld1) {
        //     $c[] = array('title' => $fld1);
        //}

        $json_data = array();

        return $json_data;
        // return array($fn,$this->tbname1,$this->tbname2);
    }

    protected function emptytable()
    {
        $fn = $_POST['fname'];
        $this->run_query_as_array("TRUNCATE TABLE $fn");

        return array('empty' => $fn);
    }



    protected function run_query_as_array($sql)
    {
        global $wpdb;
        return $wpdb->get_results($sql, 'ARRAY_A');
    }

    protected function get_data()
    {


        $tbname = $_POST['fname'];
        $fields = $this->run_query_as_array("show columns from $tbname");
        //get column name
        $columns = array();
        foreach ($fields as $fld) {
            $columns[] = $fld['Field'];
        }

        $id = $columns[0];
        $sql = "SELECT count($id) as jumlah FROM $tbname";
        $q = $this->run_query_as_array($sql);

        $totalData = $q[0]['jumlah'];
        $totalFiltered = $totalData;

        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order = $columns[$_POST['order']['0']['column']];
        $dir = $_POST['order']['0']['dir'];
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $wheredate1 = "";
        $wheredate2 = "";


        // if (empty($from_date)) { $d1 = 'nol';} else {$d1=$from_date;}
        // if (empty($to_date)) {$d2 = 'nol'; } else {$d2=$to_date;}
        // if ($d1!=='nol' && $d2!=='nol'){
        //     $wheredate1="where date between '$d1' and '$d2'";
        //     $wheredate2="date>='$d1' and date<='$d2' and";

        // }



        if (empty($_POST['search']['value'])) {
            $sqldate = "SELECT * FROM $tbname $wheredate1 order by $order $dir LIMIT $limit OFFSET $start";

            $query = $this->run_query_as_array($sqldate);
            $querycount = $this->run_query_as_array("SELECT count($id) as jumlah FROM $tbname $wheredate1");
            $datacount = $querycount;
            $totalFiltered = $datacount[0]['jumlah'];
        } else {
            $search = $_POST['search']['value'];
            $query = $this->run_query_as_array("SELECT * FROM $tbname WHERE $wheredate2 name LIKE %$search%' order by $order $dir LIMIT $limit OFFSET $start");


            $querycount = $this->run_query_as_array("SELECT count($id) as jumlah FROM $tbname WHERE $wheredate2 name LIKE '%$search%' ");
            $datacount = $querycount;
            $totalFiltered = $datacount[0]['jumlah'];
        }



        return array(
            "draw"            => intval($_POST['draw']),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $query,
            "opt" => array("fromdate" => $d1, "todate" => $d2, "tbname" => $tbname)


        );
    }

    public function migrate_now()
    {
        return $this->r_reports->migrate_to_meta($this->r_meta);
    }


    public function my_action()
    {
        if (isset($_GET['atype'])) $atype = $_GET['atype'];
        elseif (isset($_POST['atype']))  $atype = $_POST['atype'];

        $response = array();
        switch ($atype) {

            case 'get_data':
            $response = $this->get_data();
            break;

            case 'get_arr_titles':
            $response = $this->get_arr_data_title();
            break;

            case 'emptytable':
            $response = $this->emptytable();
            break;

            case 'imporcsv':
            $response = $this->imporcsv();
            break;

            case 'exporcsv':
            $this->exporcsv();

            break;
            case 'migrate':
            $response = $this->migrate_now();
            break;
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}


new MyDBMigrate();
