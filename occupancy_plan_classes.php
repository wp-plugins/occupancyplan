<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

define('chgname', htmlentities(__("Name ändern", 'occupancyplan')));
define('c_color_def', htmlentities(__("Textfarbe Standard", 'occupancyplan')));
define('c_color_tabborder', htmlentities(__("Rahmenfarbe", 'occupancyplan')));
define('c_color_tabinner', htmlentities(__("Hintergrundfarbe Tage Standard", 'occupancyplan')));
define('c_color_tabinnerDays', htmlentities(__("Tabellenfarbe Wochentage", 'occupancyplan')));
define('c_color_tabhead', htmlentities(__("Tabellenfarbe Kopf", 'occupancyplan')));
define('c_color_holiday', htmlentities(__("Textfarbe Feiertag", 'occupancyplan')));
define('c_color_today', htmlentities(__("Textfarbe Aktueller Tag", 'occupancyplan')));
define('c_color_nildays', htmlentities(__("Hintergrundfarbe leere Tage", 'occupancyplan')));
define('c_color_assigned', htmlentities(__("Hintergrundfarbe belegte Tage", 'occupancyplan')));
define('c_main_with', htmlentities(__("Breite der Anzeige in Pixel", 'occupancyplan')));
define('c_heading', htmlentities(__("Anzeigentext", 'occupancyplan')));
define('c_number_month', htmlentities(__("Anzahl Monate die angezeigt werden", 'occupancyplan')));

define('c_month_01', htmlentities(__("Januar", 'occupancyplan')));
define('c_month_02', htmlentities(__("Februar", 'occupancyplan')));
define('c_month_03', htmlentities(__("März", 'occupancyplan')));
define('c_month_04', htmlentities(__("April", 'occupancyplan')));
define('c_month_05', htmlentities(__("Mai", 'occupancyplan')));
define('c_month_06', htmlentities(__("Juni", 'occupancyplan')));
define('c_month_07', htmlentities(__("Juli", 'occupancyplan')));
define('c_month_08', htmlentities(__("August", 'occupancyplan')));
define('c_month_09', htmlentities(__("September", 'occupancyplan')));
define('c_month_10', htmlentities(__("Oktober", 'occupancyplan')));
define('c_month_11', htmlentities(__("November", 'occupancyplan')));
define('c_month_12', htmlentities(__("Dezember", 'occupancyplan')));
define('c_day1', htmlentities(__("Mo", 'occupancyplan')));
define('c_day2', htmlentities(__("Di", 'occupancyplan')));
define('c_day3', htmlentities(__("Mi", 'occupancyplan')));
define('c_day4', htmlentities(__("Do", 'occupancyplan')));
define('c_day5', htmlentities(__("Fr", 'occupancyplan')));
define('c_day6', htmlentities(__("Sa", 'occupancyplan')));
define('c_day7', htmlentities(__("So", 'occupancyplan')));


class occupancy_plan_Settings
{         
   private $objektid;
   private $values = array(
      "color_def"             => "#4B5D67",
      "color_tabborder"       => "#b7ccdf",
      "color_tabinner"        => "#e7ecf0",
      "color_tabinnerDays"    => "#F7Fcf0",
      "color_tabhead"         => "#b7ccdf",
      "color_holiday"         => "#ff0000",
      "color_today"           => "#ff0000",
      "color_nildays"         => "#e7ecf0",
      "color_assigned"        => "#FFCCCC",
      "main_with"             => "507px",
      "heading"               => chgname,
      "number_month"          => "12"
      );
     
   private $desc = array(
      "_color_def"             => c_color_def,
      "_color_tabborder"       => c_color_tabborder,
      "_color_tabinner"        => c_color_tabinner,
      "_color_tabinnerDays"    => c_color_tabinnerDays,
      "_color_tabhead"         => c_color_tabhead,
      "_color_holiday"         => c_color_holiday,
      "_color_today"           => c_color_today,
      "_color_nildays"         => c_color_nildays,
      "_color_assigned"        => c_color_assigned,
      "_main_with"             => c_main_with,
      "_heading"               => c_heading,
      "_number_month"          => c_number_month
      );
     
   private $default_values;

   private function get_setting_value($value) {
      // for wp
      global $wpdb;

      // add SQL
      $sql = "select bc_wert from ".$wpdb->prefix."belegung_config where bc_objekt_id = ".$this->objektid." and bc_name = '";

      // and query
      $result_daten = $wpdb->get_results($sql.$value."';");
      if (!empty($result_daten)) {
         foreach ($result_daten as $daten) {
            $this->values[$value] = $daten->bc_wert;
         }
      }
   }

   private function fill_def_propertys() {
      // set each elemt from values
      foreach ($this->values as $key => $value) {
         $this->get_setting_value($key);
      }
   }
   private function getObjectname() {
      // for wp
      global $wpdb;

      // add SQL
      $sql = "select bo_description from ".$wpdb->prefix."belegung_objekte where bo_objekt_id = ".$this->objektid.";";

      // and query
      $result_daten = $wpdb->get_results($sql);
      if (!empty($result_daten)) {
        foreach ($result_daten as $daten) {
            return $daten->bo_description;
         break;
       }
      }   
   }
   
   private function fill_all_propertys()
   {
      // for wp
      global $wpdb;

      // add SQL
      $sql = "select bc_name, bc_wert from ".$wpdb->prefix."belegung_config where bc_objekt_id = ".$this->objektid.";";

      // and query
      $result_daten = $wpdb->get_results($sql);
      if (!empty($result_daten)) {
         foreach ($result_daten as $daten) {
            if (array_key_exists($daten->bc_name, $this->values)) {
               $this->values[$daten->bc_name] = $daten->bc_wert;
            } else {
               exit('<p>'.htmlentities(sprintf(__('%s existiert nicht. Bitte prüfe deine Datenbank.', 'occupancyplan'),$daten->bc_name)).'</p>');
            }
         }
      }
   }   

   function __construct($objektid)
   {  
      if ((isset($objektid)) && ($objektid != -1)){
         $this->objektid = $objektid;
         $this->fill_all_propertys();
      }
   }

   public function __get($indexname)
   {  
      $tmp = strpos($indexname, "_");
      if (($tmp !== FALSE) && ($tmp == 0)) {
         if (array_key_exists($indexname, $this->desc)) {
            return $this->desc[$indexname];
         } else {
            exit('<p>'.htmlentities(sprintf(__('%s existiert nicht.', 'occupancyplan'),$indexname)).'</p>');
         }
      }
      // default properties
      elseif ($indexname == "default_values") {
         $this->default_values = $this->values;
         return  $this->default_values;
      } elseif ($indexname == "occupancy_plan_name") {
         return $this->getObjectname();
      } elseif (array_key_exists($indexname, $this->values)) {
         return $this->values[$indexname];
      } else {
         exit('<p>'.htmlentities(sprintf(__('%s existiert nicht.', 'occupancyplan'),$indexname)).'</p>');
      }
   }
}

class occupancy_plan_Output
{

   //view year
   private $mon_name = array (c_month_01, c_month_02, c_month_03,
                              c_month_04, c_month_05, c_month_06,
                              c_month_07, c_month_08, c_month_09,
                              c_month_10, c_month_11, c_month_12);

   private $w_days = array (c_day1,c_day2,c_day3,c_day4,c_day5,c_day6,c_day7);
   
   private $occupancy_Plan_ID;
   private $IsAdmin;
     
   function __construct($occupancy_plan_id, $isadmin)
   {
      $this->occupancy_Plan_ID = $occupancy_plan_id;
      $this->IsAdmin   = $isadmin;
   }
   
   public function view() {
      global $wpdb;

      // check if occupancy_Plan_ID exists
      $bFound = FALSE;
      $sql = "select count(*) as Anzahl from ".$wpdb->prefix."belegung_objekte where bo_objekt_id = ".$this->occupancy_Plan_ID.";";

      // and query
      $result_daten = $wpdb->get_results($sql);
      if (!empty($result_daten)) {
         foreach ($result_daten as $daten) {
            $bFound = ($daten->Anzahl == 1);
            break;
         }
      }

      if ($bFound === FALSE) {
         $ausgabe = '<!--- belegungsplan '.$this->occupancy_Plan_ID.' -->';
      } else {
         $ausgabe   = '';
         $error_str = '';
         if (!isset($this->occupancy_Plan_ID)){
            $this->occupancy_Plan_ID = 1;
         }
         $settings = new occupancy_plan_Settings($this->occupancy_Plan_ID);
         if ($this->IsAdmin === TRUE) {
            $ausgabe .= '<div class="updated"><strong><p>'."\n";
            $ausgabe .= __('Wenn ihr eine Spende in H&ouml;he von 15,00 EUR gebt, bekommt ihr einen Link zum download des Plugins ohne', 'occupancyplan')."</p><p>\n";
            $ausgabe .= __('den Copyright Vermerk. Ausserdem wird diese Spendenbitte im Admin-Interface nicht mehr angezeigt.', 'occupancyplan')."</p></strong>\n";
            $ausgabe .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">'."\n";
            $ausgabe .= '<input type="hidden" name="cmd" value="_s-xclick" />'."\n";
            $ausgabe .= '<input type="hidden" name="hosted_button_id" value="4217138" />'."\n";
            $ausgabe .= '<input type="image" src="https://www.paypal.com/de_DE/DE/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="" />'."\n";
            $ausgabe .= '<img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1" /><br />'."\n";
            $ausgabe .= '</form>'."\n";
            $ausgabe .= '</div>'."\n";
            
            $ausgabe .= '<p><strong>'.htmlentities(__('Zum anzeigen der Übersicht folgenden Text in die Seite einfügen:', 'occupancyplan')).'</strong>';
            $ausgabe .= htmlentities(' <!-- belegungsplan '.$this->occupancy_Plan_ID.' -->').'</p>'."\n";
            $listbox = "\t".'<select name="occupancy_plan_id" style="width: 80%;">'."\n";
            $sql = "select bo_description, bo_objekt_id from ".$wpdb->prefix."belegung_objekte;";
            $res = $wpdb->get_results($sql);
            foreach ($res as $daten) {
               if ($daten->bo_objekt_id == $this->occupancy_Plan_ID) {
                  $description = $daten->bo_description;
                  $listbox .= "\t\t".'<option value="'.$daten->bo_objekt_id.'" selected>'.$daten->bo_description."</option>\n";
               } else {
                  $listbox .= "\t\t".'<option value="'.$daten->bo_objekt_id.'">'.$daten->bo_description."</option>\n";
               }
            }
            $listbox .= "\t</select>\n";
            $ausgabe .= "<center>\n";
            $ausgabe .= '<form name="frmchange" method="post" action="">'."\n";
            $ausgabe .= '<table class="widefat" style="border:1px solid #aaaaaa; width:70%;">'."\n";
            $ausgabe .= '   <tr style="text-align:left;">'."\n";
            $ausgabe .= '      <td style="width: 30%;">'.
                        htmlentities(__('Eine andere Übersicht auswählen:', 'occupancyplan')).'</td>';
            $ausgabe .= '      <td>'.$listbox.'</td>';
            $ausgabe .= '      <td style="width: 20%;">';
            $ausgabe .= '        <input type="submit" name="anzeigeaktualisieren" class="button-primary" value="'.
                        htmlentities(__('Wählen', 'occupancyplan')).'" />';
            $ausgabe .= '      </td>'."\n";
            $ausgabe .= "   </tr>\n";
            $ausgabe .= "</table>";
            $ausgabe .= '<input name="occupancy_plan_action" value="change_occupancy_plan" type="hidden" />';
            $ausgabe .= "</form>\n";

            $ausgabe .= '<form name="frmdel" method="post" action="">'."\n";
            $ausgabe .= '<table class="widefat" style="border:1px solid #aaaaaa; width:70%;">'."\n";
            $ausgabe .= '   <tr style="text-align:left;">'."\n";
            $ausgabe .= '      <td style="width: 30%;">'.
                        htmlentities(__('Eine Übersicht löschen:', 'occupancyplan')).'</td>';
            $ausgabe .= '      <td>'.$listbox."</td>";
            $ausgabe .= '      <td style="width: 20%;">';
            $ausgabe .= '        <input type="submit" name="objectdelete" class="button-primary" value="'.
                        htmlentities(__('Löschen', 'occupancyplan')).'" /></td>'."\n";
            $ausgabe .= "   </tr>\n";
            $ausgabe .= $error_str;   
            $ausgabe .= "</table>";
            $ausgabe .= '<input name="occupancy_plan_action" value="del_occupancy_plan" type="hidden" />'."\n";
            $ausgabe .= '<input name="occupancy_plan_name" value="'.$description.'" type="hidden" />'."\n";
            $ausgabe .= "</form>\n";

            $ausgabe .= '<form name="frm_add" method="post" action="">';
            $ausgabe .= '<table class="widefat" style="border:1px solid #aaaaaa; width:70%;">'."\n";
            $ausgabe .= '   <tr style="text-align:left;">'."\n";    
            $ausgabe .= '      <td style="width: 30%;">'.
                        htmlentities(__('Eine neue Übersicht hinzufügen:', 'occupancyplan')).'</td>'."\n";
            $ausgabe .= '      <td><input type="text" style="width: 80%" maxlength="50" name="occupancy_plan_name" value=""></td>'."\n";
            $ausgabe .= '      <td style="width: 20%;"><input type="submit" name="anzeigeaktualisieren" class="button-primary" value="'.
                        htmlentities(__('Hinzufügen', 'occupancyplan')).'" />'."\n";
            $ausgabe .= "   </tr>\n";
            $ausgabe .= "</table>\n";
            $ausgabe .= '<input name="occupancy_plan_action" value="add_occupancy_plan" type="hidden" />'."\n";
            $ausgabe .= "</form>\n";
     
            $ausgabe .= '<form name="frm_options" method="post" action="" onsubmit="return chkFormular()">'."\n";
            $ausgabe .= '<input name="occupancy_plan_action" value="settings" type="hidden" />'."\n";
            $ausgabe .= '<input name="occupancy_plan_id" value="'.$this->occupancy_Plan_ID.'" type="hidden" />'."\n";
            $ausgabe .= '<table class="widefat" style="border:1px solid #aaaaaa; width:70%;">'."\n";
            $ausgabe .= "       <tr>\n";
            $ausgabe .= '               <td style="width: 25%; text-align: left;">'.
                        htmlentities(__('Name der Übersicht ändern:', 'occupancyplan')).'</td>'."\n";
            $ausgabe .= '               <td colspan="3" style="text-align: left;"><input type="text" size="70px" maxlength="50" name="occupancy_plan_name" value="'.$description.'"></td>'."\n";
            $ausgabe .= "       </tr>\n";
            $ausgabe .= "       <tr>\n";
            $ausgabe .= '               <td style="width: 25%; text-align: left;">'.
                        htmlentities(__('Überschrift:', 'occupancyplan'))."</td>\n";
            $ausgabe .= '               <td colspan="3" style="text-align: left;"><input type="text" size="80%" maxlength="250" name="heading" value="'.$settings->heading.'"></td>'."\n";
            $ausgabe .= "       </tr>\n";
            $ausgabe .= "       <tr>\n";
            $ausgabe .= '               <td style="width: 25%; text-align: left;">'.
                        htmlentities(__('Anzahl Monate:', 'occupancyplan'))."</td>\n";
            $ausgabe .= '               <td colspan="3" style="text-align: left;"><input type="text" size="7" maxlength="2" name="number_month" value="'.$settings->number_month.'"></td>'."\n";
            $ausgabe .= "       </tr>\n";
            foreach ($settings->default_values as $key => $value) {
               $pos = strpos($key, 'color_');
               if ($pos === FALSE) {
                  $pos = strpos($key, 'main_');
                  if ($pos !== FALSE && $pos == 0) {
                     $ausgabe .= "       <tr>\n";
                     $tmpkey   = "_".$key;
                     $ausgabe .= '               <td style="width: 25%; text-align: left;">'.$settings->{$tmpkey}.'</td>'."\n";
                     $ausgabe .= '               <td style="text-align: left;"><input type="text" name="'.$key.
                                 '" size="7" value="'.$settings->{$key}.
                                 '" \></td>'."\n";
                     $ausgabe .= '               <td>&nbsp;</td><td style="width: 65%">&nbsp;</td>'."\n";
                     $ausgabe .= "       </tr>\n";                  
                  }
               } else {
                  $ausgabe .= "       <tr>\n";
                  $tmpkey   = "_".$key;
                  $ausgabe .= '               <td style="width: 25%; text-align: left;">'.$settings->{$tmpkey}.'</td>'."\n";
                  $ausgabe .= '               <td style="text-align: left;"><input id="__'.$key.
                              'Obj" type="text" size="7" onChange="changeColor(\'__'.$key.
                              '\', this.value);" name="'.$key.
                              '" value="'.$settings->{$key}.
                              '" \></td>'."\n";
                  $ausgabe .= '               <td id="__'.$key.
                              '" style="border-width: 1px 1px 1px 1px; border-spacing: 1px; border-style: solid solid solid solid; width:15px; '.
                              'height:15px; text-decoration: none; vertical-align: middle;">'."\n";
                  $ausgabe .= '<script language="javascript">changeColor(\'__'.$key.
                              '\', getObj(\'__'.$key.'Obj\').value);</script>&nbsp;';
                  $ausgabe .= '                  </td><td style="width: 65%">&nbsp;</td>'."\n";
                  $ausgabe .= "       </tr>\n";
               }
            }
            $ausgabe .= "       <tr>\n";
            $ausgabe .= '               <td style="text-align:right;" colspan="4"><input type="submit" name="setsettings" class="button-primary" value="'.
                         htmlentities(__('Aktualisieren', 'occupancyplan')).'" /></td>'."\n";
            $ausgabe .= "       </tr>\n";
            $ausgabe .= "</table>";
            $ausgabe .= "</form>\n";
            $ausgabe .= '<form name="frm_cal" method="post" action="">'."\n";
            $ausgabe .= '<table class="widefat" style="border:1px solid #aaaaaa; width:70%;">'."\n";
            $ausgabe .= "       <tr><td>\n";
            $ausgabe .= '  <table class="occupancy_aussen">'."\n";
         } else {
            $ausgabe .= $settings->heading."\n";
            $ausgabe .= '  <table class="occupancy_aussen" style="width: '.$settings->main_with.';">'."\n";
         }
         $ausgabe .= "    <tr>\n";

         $zeit = localtime ( time (), 1 );
         $jahr_j = 1900 + $zeit['tm_year'];
         $mymonth = $zeit['tm_mon'];
         $mymonth++;

         $d = 0;
         for($z = 1; $z <= $settings->number_month; $z++) {
            if ($mymonth > 12) {
               $mymonth = 1;
               $jahr_j++;
            }
            $d++;
            if ($d > 3) {
               $ausgabe .= "    </tr>\n";
            $ausgabe .= "    <tr>\n";
               $d = 1;
            }
            if(strlen("$mymonth") == 1) {
               $mymonth = "0$mymonth";
            }
            if($mymonth < 10) {
               $z_mon = ereg_replace("0", "", $mymonth);
            } else {
               $z_mon = $mymonth;
            }        
            $ausgabe .= '      <td>'."\n";
            $ausgabe .= '        <table class="occupancy_kalender" style="border-color: '.$settings->color_tabborder.
			               '; background-color: '.$settings->color_tabborder.
                        '; color: '.$settings->color_def.';">'."\n";
            $ausgabe .= '          <tr>'."\n"; 
            $ausgabe .= '           <th colspan="7" style="background-color: '.$settings->color_tabhead.';">';
            $ausgabe .= $this->mon_name[$z_mon-1] . "&nbsp;" . $jahr_j . "</th>\n";
            $ausgabe .= "          </tr>\n";
            $ausgabe .= $this->mon_jahr($mymonth, $jahr_j, $settings);
            $ausgabe .= "        </table>\n";
            $ausgabe .= "      </td>\n";
            $mymonth++;
         }
         $ausgabe .= '      </tr>'."\n";
         $ausgabe .= '      <tr>'."\n";
         $ausgabe .= '        <td colspan="3">'."\n";         
         if ($this->IsAdmin === FALSE) {
		    /* BEGINN ZUSATZ LIZENZ (c) 2009 Peter Welz */
			/* Folgende Zeilen muessen IMMER unter der Uebersicht ausgegeben werden. Wenn dies nicht gegeben ist, haben sie keine Berechtigung, */
			/* dieses Script in irgendeiner Art und Weise zu benutzen/ zu aendern.*/
            $ausgabe .= '          <table class="unknown_" style="border-width: 0px 0px 0px 0px; '.
                        'border-spacing: 0px; border-style: none none none none; '.
                        ' border-collapse: separate; width: 100%; text-align: center; font-size: 7px;">'."\n";
            $ausgabe .= '            <tr>'."\n";
            $ausgabe .= '              <td>'."\n";
            $ausgabe .= '&copy; 2009 by Peter Welz [<a href="http://www.gods4u.de/wordpress-plugin-belegungplan-wp-occupancyplan/"'.
			            ' target="_blank" title="Belegungs- / occupancyplan" alt="Wordpress plugin Belegungsplan">Occupancy plan</a>]&nbsp;&nbsp;'.
						'<a href="http://www.ferienstrandwohnung.de" target="_blank" title="Ferien an der Ostsee" '.
                  'alt="ostssee ferienstrandwohnung ostseebadnienhagen">Ferienhaus</a>&nbsp;&nbsp;'.
			            '<a href="http://www.ostsee-villa-erika.de" target="_blank" title="ferienwohnung ostsee villa erika"'.
                     ' alt="ostssee ferienwohnung villa erika ostseebadnienhagen">Ostsee Villa</a>'."\n";
            $ausgabe .= '              </td>'."\n";
            $ausgabe .= '            </tr>'."\n";	
            $ausgabe .= '          </table>'."\n";
			/* ENDE ZUSATZ LIZENZ (c) 2009 Peter Welz */
         } else {
            $ausgabe .= '          <table>'."\n";
            $ausgabe .= '            <tr>'."\n";
            $ausgabe .= '              <td>'."\n";
            $ausgabe .= '                <input type="submit" name="anzeigeaktualisieren" class="button-primary" value="'.
                        htmlentities(__('Aktualisieren', 'occupancyplan')).'" />'."\n";
            $ausgabe .= '              </td>'."\n";
            $ausgabe .= '          </table>'."\n";         
            $ausgabe .= '          </td></tr>'."\n";
            $ausgabe .= '          </table>'."\n";
         }
         $ausgabe .= '        </td>'."\n";
         $ausgabe .= '      </tr>'."\n";         
         $ausgabe .= '    </table>'."\n";
         if ($this->IsAdmin === TRUE) {
            $ausgabe .= '<input name="occupancy_plan_id" value="'.$this->occupancy_Plan_ID.'" type="hidden" />'."\n";
            $ausgabe .= '<input name="occupancy_plan_action" value="update_occupancy_plan" type="hidden" />'."\n";
            $ausgabe .= "</form>\n";
            $ausgabe .= "</center>\n";
         }
      }
      return $ausgabe;
   }

   private function mon_jahr($mon_, $jahr_, $settings) {
      global $wpdb;

      $ausgabe = "";

      $myarray = array();

      $result_daten = array();
      $result_daten=$wpdb->get_results("SELECT bd_datum FROM ".$wpdb->prefix .
                                       "belegung_daten WHERE bd_objekt_id=".$this->occupancy_Plan_ID.
                                       " and bd_datum like '" . $jahr_ . "-" . $mon_ . "-%';");
    
      if (!empty($result_daten)) {
         foreach ($result_daten as $daten) {
            array_push($myarray, $daten->bd_datum);
         }
      }

      //tage
      //wochentage
      $ausgabe .= '          <tr>'."\n";
      for($i=0; $i<=6; $i++) {
         if($i==6) {                    
            $ausgabe .= '            <td style="background-color: '.$settings->color_tabinnerDays.
                        '; color: '.$settings->color_holiday.';">'.$this->w_days[$i] . "</td>\n";
         } else {
            $ausgabe .= '            <td style="background-color: '.$settings->color_tabinnerDays.';">' . $this->w_days[$i] . "</td>\n";
         }
      }

      //leere tage einfügen
      $timestamp=mktime(0,0,0,$mon_,1,$jahr_);
      $tage_im_mon=date("t",$timestamp);
      $dat=getdate($timestamp);

      if($dat['wday']==0) {
         $dat['wday']=7;
      }

      if ($this->IsAdmin === TRUE) {
         $var_height = 45;
      } else {
         $var_height = 15;
      }
      $ausgabe .= "          </tr>\n";
      $ausgabe .= "          <tr>\n";

      $woche=$dat['wday'];
      for($x=1; $x<$woche; $x++) {
         $ausgabe .= '            <td style="background-color: '.$settings->color_nildays.';">&nbsp;</td>'."\n";
      }

      //days on first row
      $zeit=time();
      $heute_tag=date("d",$zeit);
      $heute_mon=date("m",$zeit);
      $heute_jahr=date("Y",$zeit);
      $woche=bcsub(7,$woche - 1);
      for($i=1; $i<=$woche; $i++) {
         $i="0$i";
         if (array_search("".$jahr_."-".$mon_."-".$i."", $myarray) === FALSE) {
            $link_admin = '            <td style="background-color: '.$settings->color_tabinner.
                          ';">'.$i.'<input type="checkbox" name="occupancy_plan_day[]" value="'.$jahr_.'-'.$mon_.'-'.$i.'" /></td>'."\n";
            if($i == $heute_tag and $mon_ == $heute_mon and $jahr_ == $heute_jahr) {
               $link_else = '            <td style="background-color: '.$settings->color_tabinner.
                            '; color:'.$settings->color_today.';">'.$i.'</td>'."\n";
            } else {
               $link_else = '            <td style="background-color: '.$settings->color_tabinner.';">'.$i.'</td>'."\n";
            }
         } else {
            $link_admin = '            <td style="background-color: '.$settings->color_tabinner.
                          '; color: '.$settings->color_today.';">'.$i.
                          '<input type="checkbox" name="occupancy_plan_day[]" value="'.$jahr_."-".$mon_."-".$i.'" checked /></td>'."\n";
            if($i == $heute_tag and $mon_ == $heute_mon and $jahr_ == $heute_jahr) {
               $link_else = '            <td style="background-color: '.$settings->color_assigned.
                            '; color: '.$settings->color_today.';" title="'.
                            htmlentities(__('belegt', 'occupancyplan')).
                            '">'.$i.'</td>'."\n";
            } else {
               $link_else = '            <td style="background-color: '.$settings->color_assigned.';" title="'.
                            htmlentities(__('belegt', 'occupancyplan')).'">'.$i.'</td>'."\n";
            }
         }
         if ($this->IsAdmin === TRUE) {
            $ausgabe .= $link_admin;
         } else {
            $ausgabe .= $link_else;
         }
      }
      $ausgabe .= "          </tr>\n";
      $ausgabe .= "          <tr>\n";
      $y = 0;
      //other dazs
      for($i = $woche + 1; $i <= $tage_im_mon; $i++) {
         if(strlen($i) == 1) { $i = "0$i"; }
         if (array_search($jahr_."-".$mon_."-".$i, $myarray) === FALSE) {
            $link_admin = '            <td style="background-color: '.$settings->color_tabinner.
                          ';">'.$i.'<input type="checkbox" name="occupancy_plan_day[]" value="'.
                          $jahr_.'-'.$mon_.'-'.$i.'" /></td>'."\n";
            if($i == $heute_tag and $mon_ == $heute_mon and $jahr_ == $heute_jahr) {
               $link_else = '            <td style="background-color: '.$settings->color_tabinner.
                            '; color: '.$settings->color_today.';" title="'.htmlentities(__('heute', 'occupancyplan')).'">'.$i.'</td>'."\n";
            } else {
               $link_else = '            <td style="background-color: '.$settings->color_tabinner.';">'.$i."</td>\n";
            }
         } else {
            $link_admin = '            <td style="background-color: '.$settings->color_tabinner.
                          '; color: '.$settings->color_today.';">'.$i.
                          '<input type="checkbox" name="occupancy_plan_day[]" value="'.$jahr_.
                          '-'.$mon_.'-'.$i.'" checked /></td>'."\n";
            if($i == $heute_tag and $mon_ == $heute_mon and $jahr_ == $heute_jahr) {
               $link_else = '            <td style="background-color: '.$settings->color_assigned.'; color: '.
                            $settings->color_today.';" title="'.
                            htmlentities(__('belegt', 'occupancyplan')).'">'.$i."</td>\n";
            } else {
               $link_else = '            <td style="background-color: '.$settings->color_assigned.
                            '" title="'.
                            htmlentities(__('belegt', 'occupancyplan')).'">'.$i."</td>\n";
            }
         }
         $y++;
         if($y > 7) {
            $ausgabe .= "          </tr>\n          <tr>\n";
            $y=1;
         }
         if ($this->IsAdmin === TRUE) {
            $ausgabe .= $link_admin;
         } else {
            $ausgabe .= $link_else;
         }
      }
      //add nil days
      $sum_tage = bcadd($dat['wday'] -1, $tage_im_mon);
      if($sum_tage == 28) {
         $rest=bcsub(42, $sum_tage);
      }
      elseif(($sum_tage > 28) and ($sum_tage < 35)) {
         $rest = bcsub(42, $sum_tage);
      }
      elseif($sum_tage == 35) {
         $rest = bcsub(42, $sum_tage);
      }
      elseif($sum_tage > 35) {
         $rest = bcsub(42, $sum_tage);
      }
      for ($i = 0; $i < $rest; $i++) {
         $y++;
         if($y > 7) {
            $ausgabe .= "          </tr>\n          <tr>\n";
            $y = 1;
         }
         $ausgabe .= '            <td style="background-color: '. $settings->color_nildays.';">&nbsp;</td>'."\n";
      }
      $ausgabe .= "          </tr>\n";
      return $ausgabe;
   }
}
?>
