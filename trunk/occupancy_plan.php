<?php

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

function print_occupancy_plan_view($occupancy_plan_id) {
   include_once('occupancy_plan_classes.php');
   
   $getOutput = new occupancy_plan_Output($occupancy_plan_id, FALSE);
   return $getOutput->view();
}

?>
