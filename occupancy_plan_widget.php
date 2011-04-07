<?php
/**
 * occupancy_plan_widget Class
 */
 
global $wp_version;
define('WPV28', version_compare($wp_version, '2.8', '>='));

class occupancy_plan_widget extends WP_Widget {
   /** constructor */
   function occupancy_plan_widget() {
      $widget_ops = array( 'classname' => 'occupancy-plan', 'description' => __('This widget displays the occupancy-plan calendar for 1 month.', 'occupancyplan') );
      //$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'example-widget' );

		/* Widget control settings. */
		//$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'occupancy-plan' );
      $control_ops = array( 'id_base' => 'occupancy-plan' );

		/* Create the widget. */
		$this->WP_Widget( 'occupancy-plan', __('occupancyplan', 'occupancyplan'), $widget_ops, $control_ops );

      //parent::WP_Widget(false, $name = 'occupancy_plan_widget');	
   }

   /** @see WP_Widget::widget */
   function widget($args, $instance) {		
      extract( $args );
      
      $title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
      $oid = $instance['oid'];
      $table_width = $instance['table_width'];
      
      if ((isset($_POST['wdg_prev_cal'])) && (!empty($_POST['wdg_prev_cal']))) {
         if ((isset($_POST['wdg_time_year'])) && (!empty($_POST['wdg_time_year']))) {
            $new_year = $_POST['wdg_time_year'];
         } else {
            $new_month = 0;
            $new_year  = 0;
         }   

         if ((isset($_POST['wdg_time_month'])) && (!empty($_POST['wdg_time_month']))) {
            $new_month = $_POST['wdg_time_month'];
         } else {
            $new_month = 0;
            $new_year  = 0;
         }
   
         if ((isset($_POST['wdg_time_oid'])) && (!empty($_POST['wdg_time_oid']))) {
            $new_oid = $_POST['wdg_time_oid'];
         } else {
            $new_oid = -1;
         }

         if ($new_month == 0) {
            $zeit = localtime(time(), 1);
         } else {
            $zeit = localtime(mktime(0,0,0,$new_month, 1, $new_year), 1);
         }
      } elseif ((isset($_POST['wdg_next_cal'])) && (!empty($_POST['wdg_next_cal']))) {
         if ((isset($_POST['wdg_time_year_next'])) && (!empty($_POST['wdg_time_year_next']))) {
            $new_year = $_POST['wdg_time_year_next'];
         } else {
            $new_month = 0;
            $new_year  = 0;
         }   

         if ((isset($_POST['wdg_time_month_next'])) && (!empty($_POST['wdg_time_month_next']))) {
            $new_month = $_POST['wdg_time_month_next'];
         } else {
            $new_month = 0;
            $new_year  = 0;
         }
      } else {
         $new_month = 0;
         $new_year = 0;
      }
      if ((isset($_POST['wdg_time_oid'])) && (!empty($_POST['wdg_time_oid']))) {
         $new_oid = $_POST['wdg_time_oid'];
      } else {
         $new_oid = -1;
      }
      if ($new_month == 0) {
         $zeit = localtime(time(), 1);
      } else {
         $zeit = localtime(mktime(0,0,0,$new_month, 1, $new_year), 1);
      }
      if ($oid != $new_oid) {
         $zeit = localtime(time(), 1);
      }
      
      ?>
      <?php echo $before_widget; ?>
      <?php 
      if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };  
      // mark  position
      echo '  <a name="calendarwdg'.$oid.'"></a>'."\n";
		include_once('occupancy_plan_classes.php');
   	$getOutput = new occupancy_plan_WidgetCls($oid);
      echo $getOutput->view($zeit, $table_width);
      echo $after_widget;
   }

   /** @see WP_Widget::update */
   function update($new_instance, $old_instance) {				
      $instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['oid'] = strip_tags($new_instance['oid']);
      $instance['table_width'] = strip_tags($new_instance['table_width']);
      return $instance;
   }

   /** @see WP_Widget::form */
   function form($instance) {				
      /* Set up some default widget settings. */
		$defaults = array( 'title' => 'Kalender', 'oid' => '1', 'table_width' => '185px' );
		$instance = wp_parse_args( (array) $instance, $defaults );    
      $title = esc_attr($instance['title']);
      $oid = esc_attr($instance['oid']);
      $table_width = esc_attr($instance['table_width']);
      ?>
      <p>
         <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'occupancyplan'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
      </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'oid' ); ?>"><?php _e('Object-ID:', 'occupancyplan'); ?> <input class="widefat" id="<?php echo $this->get_field_id('oid'); ?>" name="<?php echo $this->get_field_name('oid'); ?>" type="text" value="<?php echo $oid; ?>" /></label>
      </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'table_width' ); ?>"><?php _e('Table width:', 'occupancyplan'); ?> <input class="widefat" id="<?php echo $this->get_field_id('table_width'); ?>" name="<?php echo $this->get_field_name('table_width'); ?>" type="text" value="<?php echo $table_width; ?>" /></label>
      </p>
      <?php 
   }
} // class occupancy_plan_widget
add_action('widgets_init', create_function('', 'return register_widget("occupancy_plan_widget");'));
