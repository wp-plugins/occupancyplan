<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

define('chgname', htmlentities(__("Change Name", 'occupancyplan')));
define('c_color_def', htmlentities(__("Textcolor default", 'occupancyplan')));
define('c_color_tabborder', htmlentities(__("Bordercolor", 'occupancyplan')));
define('c_color_tabinner', htmlentities(__("Backgroundcolor days default", 'occupancyplan')));
define('c_color_tabinnerDays', htmlentities(__("Tablecolor days of week", 'occupancyplan')));
define('c_color_tabhead', htmlentities(__("Tablecolor Header", 'occupancyplan')));
define('c_color_holiday', htmlentities(__("Textcolor Holiday", 'occupancyplan')));
define('c_color_today', htmlentities(__("Textcolor current day", 'occupancyplan')));
define('c_color_nildays', htmlentities(__("Backgroundcolor empty days", 'occupancyplan')));
define('c_color_assigned', htmlentities(__("Backgroundcolor occupied days", 'occupancyplan')));
define('c_main_with', htmlentities(__("Viewwidth in pixel", 'occupancyplan')));
define('c_heading', htmlentities(__("Viewtext", 'occupancyplan')));
define('c_number_month', htmlentities(__("Count of month to view", 'occupancyplan')));
define('c_view_columns', htmlentities(__("Count of columns to view", 'occupancyplan')));

define('c_month_01', htmlentities(__("January", 'occupancyplan')));
define('c_month_02', htmlentities(__("February", 'occupancyplan')));
define('c_month_03', htmlentities(__("March", 'occupancyplan')));
define('c_month_04', htmlentities(__("April", 'occupancyplan')));
define('c_month_05', htmlentities(__("May", 'occupancyplan')));
define('c_month_06', htmlentities(__("June", 'occupancyplan')));
define('c_month_07', htmlentities(__("July", 'occupancyplan')));
define('c_month_08', htmlentities(__("August", 'occupancyplan')));
define('c_month_09', htmlentities(__("September", 'occupancyplan')));
define('c_month_10', htmlentities(__("October", 'occupancyplan')));
define('c_month_11', htmlentities(__("November", 'occupancyplan')));
define('c_month_12', htmlentities(__("December", 'occupancyplan')));
define('c_day1', htmlentities(__("Mon", 'occupancyplan')));
define('c_day2', htmlentities(__("Tue", 'occupancyplan')));
define('c_day3', htmlentities(__("Wed", 'occupancyplan')));
define('c_day4', htmlentities(__("Thu", 'occupancyplan')));
define('c_day5', htmlentities(__("Fri", 'occupancyplan')));
define('c_day6', htmlentities(__("Sat", 'occupancyplan')));
define('c_day7', htmlentities(__("Sun", 'occupancyplan')));


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
      "number_month"          => "12",
      "view_columns"          => "3"
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
      "_number_month"          => c_number_month,
      "_view_columns"          => c_view_columns
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
               exit('<p>'.htmlentities(sprintf(__('%s not exists. please check your database.', 'occupancyplan'),$daten->bc_name)).'</p>');
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
            exit('<p>'.htmlentities(sprintf(__('%s not exists.', 'occupancyplan'),$indexname)).'</p>');
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
         exit('<p>'.htmlentities(sprintf(__('%s not exists.', 'occupancyplan'),$indexname)).'</p>');
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
   
   public function view($zeit) {
      global $wpdb;
      $ausgabe = '';

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
         $ausgabe .= '<!--- belegungsplan '.$this->occupancy_Plan_ID.' -->';
      } else {
         $error_str = '';
         if (!isset($this->occupancy_Plan_ID)){
            $this->occupancy_Plan_ID = 1;
         }
         $settings = new occupancy_plan_Settings($this->occupancy_Plan_ID);
         if ($this->IsAdmin === TRUE) {
            $ausgabe .= '<div class="updated"><strong><p>'."\n";
            $ausgabe .= sprintf(htmlentities(__('There is the possibility of acquiring an additional license (cost: 15.00 EUR). With the acquisition of an additional license is granted %s the right to remove the advertising links below the calendar issue. The copyright and the link to %s must remain the same.', 'occupancyplan')),
            /* 'Es gibt die M&ouml;glichkeit, eine Zusatzlizenz zu erwerben (Kosten: 15.00 EUR). Mit dem Erwerb einer Zusatzlizenz wird das Recht einger&auml;mt, %s die Werbelinks unterhalb der Kalenderausgabe zu entfernen. Das Copyright und der Link auf %s muessen aber erhalten bleiben.', 'occupancyplan'), */
	                        '</p><p>'."\n",
	                        '<a href="http://www.gods4u.de" target="_blank">www.gods4u.de</a>')."</p></strong>\n";
            $ausgabe .= '<strong><p>';
            /* $ausgabe .= sprintf(__('Soll der Hinweis auf %s auch entfernt werden, muss eine erweiterte Zusatzlizenz erworben werden (Kosten: 20.00 EUR).', 'occupancyplan'), */
            $ausgabe .= sprintf(htmlentities(__('Should the reference to %s will also be removed, you need to buy an extended additional license (cost: 20.00 EUR).', 'occupancyplan')),
	                        '<a href="http://www.gods4u.de" target="_blank">www.gods4u.de</a>')."</p><p>\n";
            /* $ausgabe .= __('Damit ist das Plugin dann voellig Werbefrei.', 'occupancyplan')."</p><p>\n"; */
            $ausgabe .= htmlentities(__('Thus the plug is then completely free of advertising.', 'occupancyplan'))."</p><p>\n";
            /* $ausgabe .= __('Fuer beide Lizenzen werden auch Rechnungen ausgestellt wobei die Mehrwertsteuer nicht ausgewiesen wird.', 'occupancyplan'); */
            $ausgabe .= htmlentities(__('Invoices are issued for both licenses. VAT will not be shown here.', 'occupancyplan'));
            $ausgabe .= '</p></strong>';
            $ausgabe .= '<strong><p>';	    
            /* $ausgabe .= sprintf(__('Um eine dieser Zusatzlizenzen zu erwerben, melde dich per Mail bei mir. (%s)', 'occupancyplan'), */
            $ausgabe .= sprintf(htmlentities(__('To purchase any of these additional licenses, register via email. (%s)', 'occupancyplan')),
	                        sprintf('<a href="mailto:wordpress%su.de">wordpress%su.de</a>','@gods4','@gods4'));
            $ausgabe .= '</p></strong>';
            $ausgabe .= '</div>'."\n";
            
            /* $ausgabe .= '<p><strong>'.htmlentities(__('Zum anzeigen der Übersicht folgenden Text in die Seite einfügen:', 'occupancyplan')).'</strong>'   */
            $ausgabe .= '<p><strong>'.htmlentities(__('To view the Overview, insert following text to page:', 'occupancyplan')).'</strong>';
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
                        htmlentities(__('Select Overview', 'occupancyplan')).'</td>';
            $ausgabe .= '      <td>'.$listbox.'</td>';
            $ausgabe .= '      <td style="width: 20%;">';
            $ausgabe .= '        <input type="submit" name="anzeigeaktualisieren" class="button-primary" value="'.
                        htmlentities(__('Select', 'occupancyplan')).'" />';
            $ausgabe .= '      </td>'."\n";
            $ausgabe .= "   </tr>\n";
            $ausgabe .= "</table>";
            $ausgabe .= '<input name="occupancy_plan_action" value="change_occupancy_plan" type="hidden" />';
            $ausgabe .= "</form>\n";

            $ausgabe .= '<form name="frmdel" method="post" action="">'."\n";
            $ausgabe .= '<table class="widefat" style="border:1px solid #aaaaaa; width:70%;">'."\n";
            $ausgabe .= '   <tr style="text-align:left;">'."\n";
            $ausgabe .= '      <td style="width: 30%;">'.
                        htmlentities(__('delete Overview:', 'occupancyplan')).'</td>';
            $ausgabe .= '      <td>'.$listbox."</td>";
            $ausgabe .= '      <td style="width: 20%;">';
            $ausgabe .= '        <input type="submit" name="objectdelete" class="button-primary" value="'.
                        htmlentities(__('delete', 'occupancyplan')).'" /></td>'."\n";
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
                        htmlentities(__('Add new Overview:', 'occupancyplan')).'</td>'."\n";
            $ausgabe .= '      <td><input type="text" style="width: 80%" maxlength="50" name="occupancy_plan_name" value=""></td>'."\n";
            $ausgabe .= '      <td style="width: 20%;"><input type="submit" name="anzeigeaktualisieren" class="button-primary" value="'.
                        htmlentities(__('Add', 'occupancyplan')).'" />'."\n";
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
                        htmlentities(__('Change Overviewname:', 'occupancyplan')).'</td>'."\n";
            $ausgabe .= '               <td colspan="3" style="text-align: left;"><input type="text" size="70px" maxlength="50" name="occupancy_plan_name" value="'.$description.'"></td>'."\n";
            $ausgabe .= "       </tr>\n";
            $ausgabe .= "       <tr>\n";
            $ausgabe .= '               <td style="width: 25%; text-align: left;">';
            $ausgabe .= htmlentities(__('Headline', 'occupancyplan'))."</td>\n";
            $ausgabe .= '               <td colspan="3" style="text-align: left;"><input type="text" size="80%" maxlength="250" name="heading" value="'.$settings->heading.'"></td>'."\n";
            $ausgabe .= "       </tr>\n";
            $ausgabe .= "       <tr>\n";
            $ausgabe .= '               <td style="width: 25%; text-align: left;">'.
                        htmlentities(__('Count of month:', 'occupancyplan'))."</td>\n";
            $ausgabe .= '               <td colspan="3" style="text-align: left;"><input type="text" size="7" maxlength="2" name="number_month" value="'.$settings->number_month.'"></td>'."\n";
            $ausgabe .= "       </tr>\n";
            $ausgabe .= "       <tr>\n";
            $ausgabe .= '               <td style="width: 25%; text-align: left;">'.
                        htmlentities(__('Count of columns:', 'occupancyplan'))."</td>\n";
            $ausgabe .= '               <td colspan="3" style="text-align: left;"><input type="text" size="7" maxlength="2" name="view_columns" value="'.$settings->view_columns.'"></td>'."\n";
            $ausgabe .= "       </tr>\n";

            $devval = $settings->default_values;
            foreach ($devval as $key => $value) {
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
                         htmlentities(__('refresh', 'occupancyplan')).'" /></td>'."\n";
            $ausgabe .= "       </tr>\n";
            $ausgabe .= "</table>";
            $ausgabe .= "</form>\n";
            $ausgabe .= '<form name="frm_cal" method="post" action="">'."\n";
            $ausgabe .= '<table class="widefat" style="border:1px solid #aaaaaa; width:70%;">'."\n";
            $ausgabe .= "       <tr><td>\n";
            $ausgabe .= '  <table class="occupancy_aussen">'."\n";
         } else {
            $ausgabe .= $settings->heading."\n";
            $ausgabe .= '      <a name="occuplan'.$this->occupancy_Plan_ID.'"></a>'."\n";
            //$ausgabe .= '  <table class="occupancy_aussen" border="0" style="width: '.$settings->main_with.';">'."\n";
            $ausgabe .= '  <table class="occupancy_aussen" style="width: '.$settings->main_with.'; border-spacing: 5px; border-collapse: separate;">'."\n";
         }
         $ausgabe .= "    <tr>\n";

         /* Time is now set by function call - supports now prev/next */
         /* $zeit = localtime ( time (), 1 ); */

         $jahr_j = 1900 + $zeit['tm_year'];
         $mymonth = $zeit['tm_mon'];
         $mymonth++;

         $jahr_norm = $jahr_j;
         $mymonth_norm = $mymonth;

         $zeit_prev = mktime(0,0,0,$mymonth - $settings->number_month, 1, $jahr_j);

         $d = 0;
         for($z = 1; $z <= $settings->number_month; $z++) {
            if ($mymonth > 12) {
               $mymonth = 1;
               $jahr_j++;
            }
            $d++;

            //if ($d > 3) {
            if ($d > $settings->view_columns) {
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
                        '; color: '.$settings->color_def.'; width: 100%;">'."\n";
                        //'; color: '.$settings->color_def.';">'."\n";
            $ausgabe .= '          <tr>'."\n"; 
            $ausgabe .= '           <th colspan="7" style="background-color: '.$settings->color_tabhead.'; color: '.$settings->color_def.'; '.
                        'text-align: left;">';
            $ausgabe .= $this->mon_name[$z_mon-1] . "&nbsp;" . $jahr_j . "</th>\n";
            $ausgabe .= "          </tr>\n";
            $ausgabe .= $this->mon_jahr($mymonth, $jahr_j, $settings);
            $ausgabe .= "        </table>\n";
            $ausgabe .= "      </td>\n";
            $mymonth++;
         }
         $ausgabe .= '      </tr>'."\n";
         $ausgabe .= '      <tr>'."\n";
         //colspan
         $ausgabe .= '        <td colspan="'.$settings->view_columns.'">'."\n";         
         if ($this->IsAdmin === FALSE) {
		   /* BEGINN ZUSATZ LIZENZ (c) 2009 Peter Welz */
			/* Folgende Zeilen muessen IMMER unter der Uebersicht ausgegeben werden. Wenn dies nicht gegeben ist, haben sie keine Berechtigung, */
			/* dieses Script in irgendeiner Art und Weise zu benutzen/ zu aendern. */
			/* Sind sie im Besitz einer Rechnung ueber eine erweiterte Zusatzlizenz (Kosten: 20.00 EUR), dann */
			/* duerfen folgende Zeilen auskommentiert werden. */
            $ausgabe .= '          <table class="unknown_" style="border-width: 0px 0px 0px 0px; '.
                        'border-spacing: 0px; border-style: none none none none; '.
                        ' border-collapse: separate; width: 100%; text-align: center; font-size: 7px;">'."\n";
            $ausgabe .= '            <tr>'."\n";
            $ausgabe .= '              <td>'."\n";
            $ausgabe .= '&copy; 2009 by Peter Welz <a href="http://www.gods4u.de/"'.
                        ' target="_blank" title="Belegungsplan - Occupancyplan - Plugin f&uuml;r Wordpress" alt="Belegungsplan Plugin f&uuml;r Wordpress - Occupancyplan">Belegungsplan</a>';
			/* Sind sie im Besitz einer Rechnung über die Zusatzlizenz (Kosten: 15.00 EUR), dann */
			/* duerfen folgende Zeilen auskommentiert werden. Die Zeilen ueber diesem Kommentar muessen aber erhalten bleiben. */			
            $ausgabe .= '&nbsp;&nbsp;';
            $ausgabe .= '<a href="http://www.ferienstrandwohnung.de" target="_blank" title="Ferien an der Ostsee" '.
                        'alt="ostssee ferienstrandwohnung ostseebadnienhagen">Ferienhaus</a>&nbsp;&nbsp;'.
                        '<a href="http://www.ostsee-fewo-nienhagen.de" target="_blank" title="ferienwohnung ostsee mecklenburg" alt="ostssee ferienwohnung mecklenburgn">Ostsee</a>'.
                        '<a href="http://www.ostsee-villa-erika.de" target="_blank" title="ferienwohnung ostsee villa erika" alt="ostssee ferienwohnung villa erika ostseebadnienhagen"> Villa</a>'."\n";
            $ausgabe .= '              </td>'."\n";
            $ausgabe .= '            </tr>'."\n";	
            $ausgabe .= '          </table>'."\n";
			/* ENDE ZUSATZ LIZENZ (c) 2009 Peter Welz */
         } else {
            $ausgabe .= '          <table>'."\n";
            $ausgabe .= '            <tr>'."\n";
            $ausgabe .= '              <td>'."\n";
            $ausgabe .= '                <input type="submit" name="anzeigeaktualisieren" class="button-primary" value="'.
                        htmlentities(__('refresh', 'occupancyplan')).'" />'."\n";
            $ausgabe .= '              </td>'."\n";
            $ausgabe .= '          </table>'."\n";         
            $ausgabe .= '          </td></tr>'."\n";
            $ausgabe .= '          </table>'."\n";
         }
         $ausgabe .= '        </td>'."\n";
         $ausgabe .= '      </tr>'."\n";         
         $ausgabe .= '      <tr>'."\n";
         $ausgabe .= '        <td colspan="'.$settings->view_columns.'">'."\n";
         $ausgabe .= '          <table class="occupancy_btns" style="border-width: 0px 0px 0px 0px; '.
                     'border-spacing: 0px; border-style: none none none none; '.
                     ' border-collapse: separate; width: 100%;">'."\n";
         $ausgabe .= '            <tr>'."\n";
         $ausgabe .= '              <td style="text-align:left;">'."\n";                     
         if ($this->IsAdmin === FALSE) {	 
            $ausgabe .= '      <form name="prev_next'.$this->occupancy_Plan_ID.'" method="post" action="#occuplan'.$this->occupancy_Plan_ID.'">'."\n";	 
         } 
         $ausgabe .= '        <input class="button" type="submit" name="prev_cal" value="&lt;" />'."\n";
         $ausgabe .= '        <input type="hidden" name="time_year" value="'.date("Y", $zeit_prev).'" />'."\n";
         $ausgabe .= '        <input type="hidden" name="time_month" value="'.date("n", $zeit_prev).'" />'."\n";
         $ausgabe .= '        <input type="hidden" name="time_oid" value="'.$this->occupancy_Plan_ID.'" />'."\n";
         if ($this->IsAdmin === FALSE) {	 
            $ausgabe .= '      </form>'."\n";	 
         }
         $ausgabe .= '              </td>'."\n";
         $ausgabe .= '              <td style="text-align:right;">'."\n";
         if ($this->IsAdmin === FALSE) {	 
            $ausgabe .= '      <form name="prev_next'.$this->occupancy_Plan_ID.'" method="post" action="#occuplan'.$this->occupancy_Plan_ID.'">'."\n";	 
         }         
         $ausgabe .= '        <input type="hidden" name="time_year_next" value="'.$jahr_j.'" />'."\n";
         $ausgabe .= '        <input type="hidden" name="time_month_next" value="'.$mymonth.'" />'."\n";
         $ausgabe .= '        <input type="hidden" name="time_oid" value="'.$this->occupancy_Plan_ID.'" />'."\n";
         if ($this->IsAdmin === TRUE) {	 	 
            $ausgabe .= '        <input type="hidden" name="time_month_norm" value="'.$mymonth_norm.'" />'."\n";
            $ausgabe .= '        <input type="hidden" name="time_year_norm" value="'.$jahr_norm.'" />'."\n";	    
         }
         $ausgabe .= '        <input class="button" type="submit" name="next_cal" value="&gt;" />'."";	
         if ($this->IsAdmin === FALSE) {	 
            $ausgabe .= '      </form>'."\n";	 
         }
         $ausgabe .= '              </td>'."\n";
         $ausgabe .= '            </tr>'."\n";
         $ausgabe .= '          </table>'."\n";
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
      $woche=7-($woche - 1);
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
                            htmlentities(__('busy', 'occupancyplan')).
                            '">'.$i.'</td>'."\n";
            } else {
               $link_else = '            <td style="background-color: '.$settings->color_assigned.';" title="'.
                            htmlentities(__('busy', 'occupancyplan')).'">'.$i.'</td>'."\n";
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
      //other days
      for($i = $woche + 1; $i <= $tage_im_mon; $i++) {
         if(strlen($i) == 1) { $i = "0$i"; }
         if (array_search($jahr_."-".$mon_."-".$i, $myarray) === FALSE) {
            $link_admin = '            <td style="background-color: '.$settings->color_tabinner.
                          ';">'.$i.'<input type="checkbox" name="occupancy_plan_day[]" value="'.
                          $jahr_.'-'.$mon_.'-'.$i.'" /></td>'."\n";
            if($i == $heute_tag and $mon_ == $heute_mon and $jahr_ == $heute_jahr) {
               $link_else = '            <td style="background-color: '.$settings->color_tabinner.
                            '; color: '.$settings->color_today.';" title="'.htmlentities(__('today', 'occupancyplan')).'">'.$i.'</td>'."\n";
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
                            htmlentities(__('busy', 'occupancyplan')).'">'.$i."</td>\n";
            } else {
               $link_else = '            <td style="background-color: '.$settings->color_assigned.
                            '" title="'.
                            htmlentities(__('busy', 'occupancyplan')).'">'.$i."</td>\n";
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
      $sum_tage = ($dat['wday'] - 1) + $tage_im_mon;
      if($sum_tage == 28) {
         $rest=42 - $sum_tage;
      }
      elseif(($sum_tage > 28) and ($sum_tage < 35)) {
         $rest = 42 - $sum_tage;
      }
      elseif($sum_tage == 35) {
         $rest = 42 - $sum_tage;
      }
      elseif($sum_tage > 35) {
         $rest = 42 - $sum_tage;
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

class occupancy_plan_WidgetCls
{

   //view year
   private $mon_name = array (c_month_01, c_month_02, c_month_03,
                              c_month_04, c_month_05, c_month_06,
                              c_month_07, c_month_08, c_month_09,
                              c_month_10, c_month_11, c_month_12);

   private $w_days = array (c_day1,c_day2,c_day3,c_day4,c_day5,c_day6,c_day7);
   
   private $occupancy_Plan_ID;
     
   function __construct($occupancy_plan_id)
   {
      $this->occupancy_Plan_ID = $occupancy_plan_id;
   }
   
   public function view($zeit, $widget_main_with) {
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
         $ausgabe .= '  <table class="occupancy_aussen" style="width: '.$widget_main_with.';">'."\n";

         $ausgabe .= "    <tr>\n";

         /* Time is now set by function call - supports now prev/next */
         /* $zeit = localtime ( time (), 1 ); */

         $jahr_j = 1900 + $zeit['tm_year'];
         $mymonth = $zeit['tm_mon'];
         $mymonth++;
	 
         $jahr_norm = $jahr_j;
         $mymonth_norm = $mymonth;

         $zeit_prev = mktime(0,0,0,$mymonth - 1, 1, $jahr_j);

         $d = 0;
         for($z = 1; $z <= 1; $z++) {
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
            $ausgabe .= '      <td colspan="2">'."\n";
            $ausgabe .= '        <table class="occupancy_kalender" style="border-color: '.$settings->color_tabborder.
			               '; background-color: '.$settings->color_tabborder.
                        '; color: '.$settings->color_def.'; width: 100%;">'."\n";
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
         $ausgabe .= '        <td colspan="2">'."\n";         

		   /* BEGINN ZUSATZ LIZENZ (c) 2009 Peter Welz */
			/* Folgende Zeilen muessen IMMER unter der Uebersicht ausgegeben werden. Wenn dies nicht gegeben ist, haben sie keine Berechtigung, */
			/* dieses Script in irgendeiner Art und Weise zu benutzen/ zu aendern. */
			/* Sind sie im Besitz einer Rechnung ueber eine erweiterte Zusatzlizenz (Kosten: 20.00 EUR), dann */
			/* duerfen folgende Zeilen auskommentiert werden. */
         $ausgabe .= '          <table class="unknown_" style="border-width: 1px 1px 1px 1px; '.
                     'border-spacing: 0px; border-style: none none none none; '.
                     ' border-collapse: separate; width: 100%; text-align: center; font-size: 7px;">'."\n";
         $ausgabe .= '            <tr>'."\n";
         $ausgabe .= '              <td>'."\n";
         $ausgabe .= '&copy; 2009 by Peter Welz <a href="http://www.gods4u.de/"'.
                     ' target="_blank" title="Belegungsplan - Occupancyplan - Plugin f&uuml;r Wordpress" alt="Belegungsplan Plugin f&uuml;r Wordpress - Occupancyplan">Belegungsplan</a>';
			/* Sind sie im Besitz einer Rechnung über die Zusatzlizenz (Kosten: 15.00 EUR), dann */
			/* duerfen folgende Zeilen auskommentiert werden. Die Zeilen ueber diesem Kommentar muessen aber erhalten bleiben. */			
         $ausgabe .= '&nbsp;&nbsp;';
         $ausgabe .= '<a href="http://www.ferienstrandwohnung.de" target="_blank" title="Ferien an der Ostsee" '.
                     'alt="ostssee ferienstrandwohnung ostseebadnienhagen">Ferienhaus</a>&nbsp;&nbsp;'.
                     '<a href="http://www.ostsee-fewo-nienhagen.de" target="_blank" title="ferienwohnung ostsee mecklenburg" alt="ostssee ferienwohnung mecklenburgn">Ostsee</a>'.
                     '<a href="http://www.ostsee-villa-erika.de" target="_blank" title="ferienwohnung ostsee villa erika" alt="ostssee ferienwohnung villa erika ostseebadnienhagen"> Villa</a>'."\n";
         $ausgabe .= '              </td>'."\n";
         $ausgabe .= '            </tr>'."\n";	
         $ausgabe .= '          </table>'."\n";
			/* ENDE ZUSATZ LIZENZ (c) 2009 Peter Welz */

         $ausgabe .= '        </td>'."\n";
         $ausgabe .= '      </tr>'."\n";         
         $ausgabe .= '      <tr>'."\n";
         $ausgabe .= '      <form name="wdg_prev_next" method="post" action="#calendarwdg'.$this->occupancy_Plan_ID.'">'."\n";	 
         $ausgabe .= '        <td style="text-align:left;">'."\n";	 
         $ausgabe .= '        <input class="button" type="submit" name="wdg_prev_cal" value="&lt;" />'."\n";
         $ausgabe .= '        <input type="hidden" name="wdg_time_year" value="'.date("Y", $zeit_prev).'" />'."\n";
         $ausgabe .= '        <input type="hidden" name="wdg_time_month" value="'.date("n", $zeit_prev).'" />'."\n";
         $ausgabe .= '        <input type="hidden" name="wdg_time_oid" value="'.$this->occupancy_Plan_ID.'" />'."\n";
         $ausgabe .= '        </td>'."\n";
         $ausgabe .= '        <td style="text-align:right;">'."\n";
         $ausgabe .= '        <input class="button" type="submit" name="wdg_next_cal" value="&gt;" />'."\n";	 	 
         $ausgabe .= '        <input type="hidden" name="wdg_time_year_next" value="'.$jahr_j.'" />'."\n";
         $ausgabe .= '        <input type="hidden" name="wdg_time_month_next" value="'.$mymonth.'" />'."\n";
         $ausgabe .= '        </td>'."\n";
         $ausgabe .= '      </form>'."\n";	 
         $ausgabe .= '      </tr>'."\n";
         $ausgabe .= '    </table>'."\n";
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

      $var_height = 15;

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
      $woche=7-($woche - 1);
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
                            htmlentities(__('busy', 'occupancyplan')).
                            '">'.$i.'</td>'."\n";
            } else {
               $link_else = '            <td style="background-color: '.$settings->color_assigned.';" title="'.
                            htmlentities(__('busy', 'occupancyplan')).'">'.$i.'</td>'."\n";
            }
         }
         $ausgabe .= $link_else;
      }
      $ausgabe .= "          </tr>\n";
      $ausgabe .= "          <tr>\n";
      $y = 0;
      //other days
      for($i = $woche + 1; $i <= $tage_im_mon; $i++) {
         if(strlen($i) == 1) { $i = "0$i"; }
         if (array_search($jahr_."-".$mon_."-".$i, $myarray) === FALSE) {
            $link_admin = '            <td style="background-color: '.$settings->color_tabinner.
                          ';">'.$i.'<input type="checkbox" name="occupancy_plan_day[]" value="'.
                          $jahr_.'-'.$mon_.'-'.$i.'" /></td>'."\n";
            if($i == $heute_tag and $mon_ == $heute_mon and $jahr_ == $heute_jahr) {
               $link_else = '            <td style="background-color: '.$settings->color_tabinner.
                            '; color: '.$settings->color_today.';" title="'.htmlentities(__('today', 'occupancyplan')).'">'.$i.'</td>'."\n";
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
                            htmlentities(__('busy', 'occupancyplan')).'">'.$i."</td>\n";
            } else {
               $link_else = '            <td style="background-color: '.$settings->color_assigned.
                            '" title="'.
                            htmlentities(__('busy', 'occupancyplan')).'">'.$i."</td>\n";
            }
         }
         $y++;
         if($y > 7) {
            $ausgabe .= "          </tr>\n          <tr>\n";
            $y=1;
         }
         $ausgabe .= $link_else;
      }
      //add nil days
      $sum_tage = ($dat['wday'] - 1) + $tage_im_mon;
      if($sum_tage == 28) {
         $rest=42 - $sum_tage;
      }
      elseif(($sum_tage > 28) and ($sum_tage < 35)) {
         $rest = 42 - $sum_tage;
      }
      elseif($sum_tage == 35) {
         $rest = 42 - $sum_tage;
      }
      elseif($sum_tage > 35) {
         $rest = 42 - $sum_tage;
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
