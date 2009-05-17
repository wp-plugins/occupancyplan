<?php
/*
Plugin Name: occupancyplan
Plugin URI: http://www.gods4u.de/wordpress-plugin-belegungplan-wp-occupancyplan/
Description: occupancy plan for Wordpress
Version: 1.0.1.0
Author: Peter Welz
Author URI: http://www.gods4u.de/
*/
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain('occupancyplan', 'wp-content/plugins/' . $plugin_dir, $plugin_dir );
   
register_activation_hook(__FILE__, 'occupancy_plan_install');

function occupancy_plan_install() {
    global $wpdb;
    $occupancy_plan_version = "1.0.1.0";

    $table_name_daten   = $wpdb->prefix . "belegung_daten";
    $table_name_objekte = $wpdb->prefix . "belegung_objekte";   
    $table_name_config  = $wpdb->prefix . "belegung_config";
    
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name_daten'") != $table_name_daten) {
        $sql = "CREATE TABLE " . $table_name_daten . " (
                    bd_datum date NOT NULL,
                    bd_objekt_id int(4) NOT NULL,
                    PRIMARY KEY (bd_datum,bd_objekt_id));";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $sql = "CREATE TABLE `" . $table_name_objekte . "` (
                    `bo_objekt_id` int(4) NOT NULL AUTO_INCREMENT,
                    `bo_description` varchar(100) not null,
                    PRIMARY KEY (`bo_objekt_id`),
                    UNIQUE KEY `idx_bo_description` (`bo_description`)
                );";
        dbDelta($sql);

        
        $sql = "CREATE TABLE " . $table_name_config . " (
                    bc_id int(11) NOT NULL AUTO_INCREMENT,
                    bc_name varchar(50) not null,
                    bc_wert varchar(250) not null,
                    bc_objekt_id int(4) not null,
                    PRIMARY KEY (bc_id));";
        dbDelta($sql);
        
        insert_occupancy_plan($table_name_objekte, $table_name_config, 'occupancy_plan');
        
        add_option("occupancy_plan_db_version", $occupancy_plan_version);
   }
   else {
        $installed_ver = get_option( "occupancy_plan_db_version" );
        if ($installed_ver != $occupancy_plan_version) {
            update_option( "occupancy_plan_db_version", $occupancy_plan_version );
        }
   }
}

add_action('wp_head', 'add_css');
add_action('admin_head', 'add_adm_css');

function add_css() {
?>
<style type="text/css">
table.occupancy_aussen {
    border-width: 0px 0px 0px 0px;
    border-spacing: 5px;
    border-style: none none none none;
    border-color: #000000 #000000 #000000 #000000;
    border-collapse: separate;
    text-align: center;
}

table.occupancy_kalender {
    border-width: 1px 1px 1px 1px;
    border-spacing: 1px;
    border-style: solid solid solid solid;
    border-collapse: separate;
    width: 100%;
    font-weight: normal;
    font-size: 10px;
    font-family: arial,helvetica,sans-serif;
}

table.occupancy_kalender td {
    text-align: center;
    height: 10px;
    width: 14%;
}
</style>
<?
}

function add_adm_css() {
   global $wpdb;
?>
<script language="Javascript">
     var ie = false;
     var nocolor = 'none';
     if (document.all) { ie = true; nocolor = ''; }
     function getObj(id) {
       if (ie) { return document.all[id]; }
       else {   return document.getElementById(id); }
     }

     function changeColor(id, color) {
       var link = getObj(id);
       if (color == '') {
         link.style.background = nocolor;
         link.style.color = nocolor;
         color = nocolor;
       } else {
         link.style.background = color;
         link.style.color = color;
       }
       eval(getObj(id + 'Obj').title);
     }

     function chkFormular () {
       if (document.frm_options.number_month.value == "") {
         alert("<? _e('Bitte eine Zahl zwischen 1-100 eingeben!');?>");
         document.frm_options.number_month.focus();
         return false;
       }
       var chkZ = 1;
       for (i = 0; i < document.frm_options.number_month.value.length; ++i) {
         if (document.frm_options.number_month.value.charAt(i) < "0" ||
           document.frm_options.number_month.value.charAt(i) > "9") {
           chkZ = -1;
         }
       }
       if (chkZ == -1) {
         alert("<?php printf(__('Bei \"%s\" muss eine Zahl eingegeben werden!'), __('Anzahl Monate'));?>");
         document.frm_options.number_month.focus();
         return false;
       }
       if (document.frm_options.number_month.value < 1 ||
         document.frm_options.number_month.value > 100) {
         alert("<?php _e('Bitte eine Zahl zwischen 1-100 eingeben!');?>");
         document.frm_options.number_month.focus();
         return false;
       } /*
       if (document.frm_options.number_month.value % 3 !== 0) {
         alert("Bitte eine Zahl eingeben, die durch 3 teilbar ist!");
         document.frm_options.number_month.focus();
         return false;
       }   */
     }
</script>

<style type="text/css">
table.occupancy_aussen {
    border-width: 0px 0px 0px 0px;
    border-spacing: 1px;
    border-style: none none none none;
    border-color: #000000 #000000 #000000 #000000;
    border-collapse: separate;
    width: 100%;
    text-align: center;
}

table.occupancy_kalender {
    border-width: 1px 1px 1px 1px;
    border-spacing: 1px;
    border-style: solid solid solid solid;
    border-collapse: separate;
    width: 33%;
}

table.occupancy_kalender th {
    height: 10px;
}

table.occupancy_kalender td {
    text-align: center;
    height: 40px;
    width: 15px;
    font-weight: normal;
    font-size: 10px;
    font-family: arial,helvetica,sans-serif;    
}
</style>
<?
}

add_filter('the_content', 'occupancy_plan_check_output');

function occupancy_plan_check_output($content) {
   $text = '<!-- belegungsplan ';
   $pos = strpos($content, $text);
   if ($pos !== FALSE) {
      $tmptext = substr($content, $pos-1);
      $pos = strpos($tmptext, ' -->');
      if ($pos !== FALSE) {
         $oid = (int)trim(substr($tmptext, strlen($text), $pos - strlen($text)));
         if (strpos($content, $text.$oid.' -->') !== FALSE) {
             $content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
          include_once("occupancy_plan.php");
             $content = str_replace($text.$oid.' -->', print_occupancy_plan_view($oid), $content);
         }
      }
   }
   return $content;
}

add_action('admin_menu', 'occupancy_plan_add_menu');
// anhanced adminmenu options
function occupancy_plan_add_menu() {
   add_options_page(__('Belegungsplan-Plugin'), __('Belegungsplan'), 'publish_posts', __FILE__, 'occupancy_plan_option_page'); //optionenseite hinzufügen
}

// only calls if comes from adminpagelink
function occupancy_plan_option_page() {
   unset($dbg_str);

   $occupancy_plan_id = 1;
   
   if ((isset($_POST['occupancy_plan_day'])) && (!empty($_POST['occupancy_plan_day']))) {
      $occupancy_plan_days = $_POST['occupancy_plan_day'];
   }
   if ((isset($_POST['occupancy_plan_id'])) && (!empty($_POST['occupancy_plan_id']))) {
      $occupancy_plan_id   = $_POST['occupancy_plan_id'];
   }
   if ((isset($_POST['occupancy_plan_name'])) && (!empty($_POST['occupancy_plan_name']))) {
      $occupancy_plan_name = $_POST['occupancy_plan_name'];  
   }
   if ((isset($_POST['occupancy_plan_action'])) && (!empty($_POST['occupancy_plan_action']))) {
      $action               = $_POST['occupancy_plan_action'];
   }
   if ((isset($action)) && (!empty($action))) {
      if ($action == 'update_occupancy_plan') {
         $occupancy_plan_name = update_occupancy_plan($occupancy_plan_days, $occupancy_plan_id);
       if (isset($occupancy_plan_name)) {
            $dbg_str = '<div class="updated"><p><strong>'.
                    htmlentities(sprintf(__("Die Änderungen an [%s] wurden übernommen!"), $occupancy_plan_name)).
                  '</strong></p></div>'."\n";
       } else {
            $dbg_str = '<div class="updated"><p><strong>'.
                     htmlentities(__('Die Änderungen wurden übernommen!')).'</strong></p></div>'."\n";       
       }
      } elseif ($action == 'change_occupancy_plan') {
         //
      } elseif ($action == 'settings') {
         $tmpid = -1;
         include_once('occupancy_plan_classes.php');
         $tmp = new occupancy_plan_Settings($tmpid);
         $newSettings      = $tmp->default_values;
         foreach ($newSettings as $key => $value) {
            $tmp               = $_POST[$key];
            if (isset($tmp)) {
               $newSettings[$key] = $tmp;
            }
         }
         if (isset($occupancy_plan_id)) {
            update_occupancy_plan_settings($occupancy_plan_id, $occupancy_plan_name, $newSettings);
           $dbg_str = '<div class="updated"><p><strong>'.
                     htmlentities(sprintf(__('Optionen von [%s] erfolgreich gespeichert!'),$occupancy_plan_name)).
                  '</strong></p></div>'."\n";
         }
      } elseif ($action == 'add_occupancy_plan') {
         $occupancy_plan_id   = add_occupancy_plan($occupancy_plan_name);
         if ($occupancy_plan_id == -1){
            $dbg_str = '<div class="updated"><p><strong>'.
                    htmlentities(sprintf(__('Ein Objekt mit dem Namen [%s] existiert bereits!'),$occupancy_plan_name)).
                  '</strong></p></div>'."\n";
          $occupancy_plan_id = 1;
         }
      } elseif ($action == 'del_occupancy_plan') {   
         $occupancy_plan_id   = delete_occupancy_plan($occupancy_plan_id);
        if ($occupancy_plan_id == -1){
           $occupancy_plan_id = 1;
          include_once('occupancy_plan_classes.php');
           $tmp = new occupancy_plan_Settings($occupancy_plan_id);
           $dbg_str = '<div class="updated"><p><strong>'.
                    htmlentities(sprintf(__('Das erste Objekt [%s] kann nicht gelöscht werden!'),$tmp->occupancy_plan_name)).
                  '</strong></p></div>'."\n";
       
         } else {
            $dbg_str = '<div class="updated"><p><strong>'.
                     htmlentities(sprintf(__('Das Objekt [%s] wurde gelöscht!'),$occupancy_plan_name)).
                  '</strong></p></div>'."\n";     
        }
      }  
   }
?>
  <div class="wrap">
      <h2><? _e('Belegungsplan');?></h2>
<?
   if (isset($dbg_str)) {
      echo $dbg_str;
   }
   include_once('occupancy_plan_classes.php');
   $getOutput = new occupancy_plan_Output($occupancy_plan_id, TRUE);
   echo $getOutput->view();   
?>
  </div>
<?php  
} // end function occupancy_plan_option_page()

function add_occupancy_plan($occupancy_plan_name) {
   global $wpdb;
   $table_objekte = $wpdb->prefix . "belegung_objekte";
   $table_config  = $wpdb->prefix . "belegung_config";
   return insert_occupancy_plan($table_objekte, $table_config, $occupancy_plan_name);
}

function delete_occupancy_plan($id) {
   global $wpdb;

   if ($id == 1) {
      $min_id = -1;
   } else {
      $sql = "DELETE FROM ".$wpdb->prefix."belegung_daten WHERE bd_objekt_id = ".$id.";";
      $wpdb->query($sql);
      $sql = "DELETE FROM ".$wpdb->prefix."belegung_config WHERE bc_objekt_id = ".$id.";";
      $wpdb->query($sql);
      $sql = "DELETE FROM ".$wpdb->prefix."belegung_objekte WHERE bo_objekt_id = ".$id.";";
      $wpdb->query($sql);
     $min_id = 1;
   }
   return $min_id;
}

function update_occupancy_plan($tage, $id) {
   global $wpdb;
   $ret = '';
   $sql = "DELETE FROM ".$wpdb->prefix."belegung_daten WHERE bd_objekt_id = ".$id.";";
   $wpdb->query($sql);
   if (isset($tage)) {
      foreach ($tage as $datum) {
         $wpdb->query("INSERT INTO ".$wpdb->prefix."belegung_daten (bd_datum, bd_objekt_id) VALUES('".$datum."',".$id.");");
      }
   }
   $sql = "SELECT bo_description from ".$wpdb->prefix."belegung_objekte WHERE bo_objekt_id = ".$id.";";
   $result_daten = $wpdb->get_results($sql);   
   if (!empty($result_daten)) {
      foreach ($result_daten as $daten) {
        $ret = $daten->bo_description; 
     }
   }
   return $ret;
}

function update_occupancy_plan_settings($oid, $occupancy_plan_name, $newSettings) {
   global $wpdb;

   foreach ($newSettings as $key => $value) {
      $sql  = "UPDATE ".$wpdb->prefix."belegung_config set ";
      $sql .= " bc_wert = '".$value."' ";
      $sql .= " WHERE bc_name = '".$key."' and bc_objekt_id = ".$oid;
      $wpdb->query($sql);
   }   

   $sql = "UPDATE ".$wpdb->prefix."belegung_objekte SET bo_description = '".$occupancy_plan_name."' WHERE bo_objekt_id = ".$oid;
   $wpdb->query($sql);
   return 1;
}

function insert_occupancy_plan($table_name_objekte, $table_name_config, $description) {
   global $wpdb;

   // check if object already exists
   $sql = "SELECT bo_objekt_id from ".$wpdb->prefix."belegung_objekte WHERE bo_description = '".$description."';";
   $result_daten = $wpdb->get_results($sql);
   if (empty($result_daten)) {
      $sql = "INSERT INTO " . $table_name_objekte . " (bo_description)
              VALUES('".$description."');";
      $wpdb->query($sql);
      $insert_id = $wpdb->insert_id;

      $oid = -1;
     include_once('occupancy_plan_classes.php');
      $def_settings = new occupancy_plan_Settings($oid);

      $sql = "INSERT INTO ". $table_name_config . " (bc_name, bc_wert, bc_objekt_id) ";
      foreach ($def_settings->default_values as $key => $value) {
         $sql1=      " VALUES ('".$key."','".$value."',".$insert_id.");";
         $wpdb->query($sql.$sql1);
      }
      return $insert_id;
   } else {
      return -1;
   }
}

?>
