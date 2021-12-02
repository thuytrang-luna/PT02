<?php
/*
Plugin Name:Events Shortcodes - The Events Calendar Addon
Plugin URI:https://eventscalendartemplates.com/
Description:<a href="http://wordpress.org/plugins/the-events-calendar/">📅 The Events Calendar Addon</a> - Events Shortcodes Addon provides events list & timeline templates and shortcode generator for The Events Calendar (by Modern Tribe) plugin.
Version:1.9.3
Requires at least: 4.5
Tested up to:5.8
Requires PHP:5.6
Stable tag:trunk
Author:Cool Plugins
Author URI:https://coolplugins.net/
License URI:https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain:ect
*/

if (!defined('ABSPATH')) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
if (!defined('ECT_VERSION')) {
    define('ECT_VERSION', '1.9.3');
}
/*** Defined constent for later use */
define('ECT_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('ECT_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
/*** EventsCalendarTemplates main class by CoolPlugins.net */
if (!class_exists('EventsCalendarTemplates')) {
   final class EventsCalendarTemplates {

	/**
     * The unique instance of the plugin.
     *
     */
    private static $instance;

    /**
     * Gets an instance of our plugin.
     *
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

	/**
     * Constructor.
     */
    private function __construct()
    {

	
    }

    	// register all hooks
        public function registers() {

			/*** Installation and uninstallation hooks */
			register_activation_hook(__FILE__, array('EventsCalendarTemplates', 'activate'));
			register_deactivation_hook(__FILE__, array('EventsCalendarTemplates', 'deactivate'));

			add_action('admin_init',array(self::$instance,'ect_settings_migration'));
			add_action('admin_init',array(self::$instance,'onInit'));

			/*** Check The Event Calendar is installed or not */
			add_action( 'plugins_loaded', array(self::$instance, 'ect_check_event_calender_installed' ));

			/*** Load required files */
			add_action( 'plugins_loaded',array(self::$instance,'ect_load_files'));

			add_action('admin_enqueue_scripts',array(self::$instance,'ect_tc_css'));
			/*** Template Setting Page Link */
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(self::$instance,'ect_template_settings_page'));


			foreach (array('post.php','post-new.php') as $hook) {
				add_action("admin_head-$hook", array(self::$instance,'ect_rest_url'));
			}

			add_action('admin_head','ect_admin_menu_custom_styles');
		
			/*** Include Gutenberg Block */
			require_once(ECT_PLUGIN_DIR.'admin/gutenberg-block/ect-block.php' );

			/***Include Share Buttons*/
			require_once(ECT_PLUGIN_DIR.'/includes/ect-share-functions.php' );


		}



		/*** Load required files */
		public function ect_load_files() {

			if (  class_exists( 'Tribe__Events__Main' ) or  defined( 'Tribe__Events__Main::VERSION' )) {
				if( file_exists( plugin_dir_path( __DIR__ ) . "elementor/elementor.php"  ) ){
	                include_once( ABSPATH . "wp-admin/includes/plugin.php" );
					if( is_plugin_active( "elementor/elementor.php" ) ){
						require_once(ECT_PLUGIN_DIR. 'admin/elementor/ect-elementor.php' );
					}
				}

				if (  defined( 'WPB_VC_VERSION' ) ) {
					require_once(ECT_PLUGIN_DIR.'admin/visual-composer/ect-class-vc.php');
				}
			}

			if( is_admin() ){
				/*** Plugin review notice file */ 
				require_once(ECT_PLUGIN_DIR . '/admin/notices/admin-notices.php');
				require_once ECT_PLUGIN_DIR . 'admin/feedback/admin-feedback-form.php';
				require_once ECT_PLUGIN_DIR . 'admin/notices/plugin-upgrade-notice.php';

				require_once __DIR__ . "/admin/events-addon-page/events-addon-page.php";
				cool_plugins_events_addon_settings_page('the-events-calendar','cool-plugins-events-addon' ,'📅 Events Addons For The Events Calendar');

				require_once ECT_PLUGIN_DIR . 'admin/ectcsf-framework/ectcsf-framework.php';
				require_once ECT_PLUGIN_DIR . 'admin/ect-codestar-settings.php';
				$settings_panel=new ECTSettings();
				
				
			}

			/*** Include helpers functions*/
			require_once(ECT_PLUGIN_DIR.'includes/ect-functions.php');

			 require_once(ECT_PLUGIN_DIR.'includes/events-shortcode.php');
			 EventsShortcode::registers();
			 require_once ECT_PLUGIN_DIR . 'admin/ect-event-shortcode.php';

		}

		public static function onInit(){

			if(version_compare(get_option('ect-v'),'1.8', '<')){		
					ect_create_admin_notice( array(
						'id'=>'ect-free-setting-migration',
						'message'=>__('<strong>Important Update</strong>:- <strong>Events Shortcodes & Templates</strong> plugin has integrated new settings panel. Please save your settings and check events views.','ect'),
						'review_interval'=>0,  
					) );
				}
				if ( did_action( 'elementor/loaded' ) && !class_exists('Events_Calendar_Addon') ) {				
					ect_create_admin_notice( array(
						'id'=>'ect-elementor-addon-notice',
						'message'=>__('Hi! We checked that you are using <strong>Elementor Page Builder</strong>.
						<br/>Please try latest <a target="_blank" href="https://wordpress.org/plugins/events-widgets-for-elementor-and-the-events-calendar/"><strong>The Events Calendar Widgets For Elementor</strong></a> plugin developed by <a href="https://coolplugins.net">Cool Plugins</a>
						   & <br/> represent The Events Calendar events in the Elementor page builder pages.','ect'),
						'review_interval'=>3,					
						'logo'=>ECT_PLUGIN_URL.'assets/images/events-widgets-elementor-logo.png',  
					) );				
				}
			/*** Plugin review notice file */
				ect_create_admin_notice( 
					array(
						'id'=>'ect_review_box',  // required and must be unique
						'slug'=>'ect',      // required in case of review box
						'review'=>true,     // required and set to be true for review box
					'review_url'=>esc_url('https://wordpress.org/support/plugin/template-events-calendar/reviews/#new-post'), // required
					'plugin_name'=>'Events Shortcodes  Addon',    // required
					'logo'=>ECT_PLUGIN_URL.'assets/images/ect-icon.png',    // optional: it will display logo
					'review_interval'=>3                    // optional: this will display review notice
															//   after 5 days from the installation_time
																// default is 3
					)
				);
		}

		public function shortcodes_submenu()
        {
            add_submenu_page('cool-plugins-events-addon','Shortcodes & Template', '<strong>Shortcodes & Template</strong>', 'manage_options', 'admin.php?page=tribe_events-events-template-settings', false, 15);
		}

		/*** Check The Events calender is installled or not. If user has not installed yet then show notice */
		public  function ect_check_event_calender_installed(){
			if ( ! class_exists( 'Tribe__Events__Main' ) or ! defined( 'Tribe__Events__Main::VERSION' )) {
				add_action( 'admin_notices', array( $this, 'Install_ECT_Notice' ) );
			}
		}
        public function Install_ECT_Notice(){
        	if ( current_user_can( 'activate_plugins' ) ) {
        		$url = 'plugin-install.php?tab=plugin-information&plugin=the-events-calendar&TB_iframe=true';
        		$title = __( 'The Events Calendar', 'tribe-events-ical-importer' );
        		echo '<div class="error CTEC_Msz"><p>' . sprintf( __( 'In order to use this addon, Please first install the latest version of <a href="%s" class="thickbox" title="%s">%s</a> and add an event.', 'ect' ), esc_url( $url ), esc_attr( $title ),esc_attr( $title ) ) . '</p></div>';
        	}
		}

		/*** Admin side shortcode generator style CSS */
		public function ect_tc_css() {
			wp_enqueue_style('sg-btn-css', plugins_url('assets/css/shortcode-generator.css', __FILE__));
		}
		/*** Add links in plugin install list */
		public function ect_template_settings_page($links){
			$links[] = '<a style="font-weight:bold" href="'. esc_url( get_admin_url(null, 'admin.php?page=tribe_events-events-template-settings') ) .'">Shortcodes Settings</a>';
			// $links[] = '<a  style="font-weight:bold" href="https://eventscalendartemplates.com/" target="_blank">View Demos</a>';
			return $links;
		}


		// set settings on plugin activation
  		 public static function activate() {
			update_option("ect-v",ECT_VERSION);
			update_option("ect-type","FREE");
			update_option("ect-free-installDate",date('Y-m-d h:i:s') );
			update_option("ect-ratingDiv","no");
        }
		public static function deactivate(){
			delete_option("settings_migration_status");
			delete_option("ect-v");
			delete_option("ect-type");
			delete_option("ect-free-installDate");
			delete_option("ect-ratingDiv");

		}


		public function ect_rest_url() {
			?>
			<!-- TinyMCE Shortcode Plugin -->
			<script type='text/javascript'>
			var ectRestUrl='<?php echo get_rest_url(null, '/tribe/events/v1/');?>'
			</script>
			<!-- TinyMCE Shortcode Plugin -->
			<?php
		}

		/*
			Old settings migration
		*/

			// old titan settings panel fields data
			function get_titan_settings(){
				$new_settings=[];
				if(get_option('ect_options')!=false){
					$titan_raw_data=get_option('ect_options');
					if(is_serialized($titan_raw_data)){
						$titan_settings=array_filter(maybe_unserialize($titan_raw_data));
						if(is_array($titan_settings)){
							foreach($titan_settings as $key=>$val){
								$new_settings[$key]=maybe_unserialize($val);
							}
						}
					}
					return $new_settings;
				}else{
					return false;
				}
			}

	function ect_settings_migration()
	{

				if(version_compare(get_option('ect-v'),'1.8', '>')){		
					return;
				}
				if(get_option('settings_migration_status'))
				{
					return;
				}

				$old_settings=$this->get_titan_settings();
				if($old_settings==false){
					return;
				}
			if(is_array($old_settings))
				{

				$req_settings=['font-family','font-size','font-weight',
				'font-style','line-height','letter-spacing','text-transform',
				'color','font-type'];
				//	 echo'<pre>';
				// print_r($old_settings);
				// echo'</pre>';

				$webSafeFonts = array(
					'Arial, Helvetica, sans-serif' => 'Arial',
					'"Arial Black", Gadget, sans-serif' => 'Arial Black',
					'"Comic Sans MS", cursive, sans-serif' => 'Comic Sans MS',
					'"Courier New", Courier, monospace' => 'Courier New',
					'Georgia, serif' => 'Geogia',
					'Impact, Charcoal, sans-serif' => 'Impact',
					'"Lucida Console", Monaco, monospace' => 'Lucida Console',
					'"Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Lucida Sans Unicode',
					'"Palatino Linotype", "Book Antiqua", Palatino, serif' => 'Palatino Linotype',
					'Tahoma, Geneva, sans-serif' => 'Tahoma',
					'"Times New Roman", Times, serif' => 'Times New Roman',
					'"Trebuchet MS", Helvetica, sans-serif' => 'Trebuchet MS',
					'Verdana, Geneva, sans-serif' => 'Verdana',
				);
				$old_font_arr=array_flip($webSafeFonts);

				$new_settings=[];
				foreach($old_settings as $key=>$field_val){
						if(is_array($field_val)){
								foreach($field_val as $index=>$val ){
									if(in_array($index,$req_settings)){
										if($index=="font-type"){
											$index="type";
										}else if($index=="font-size"){
											$val=str_replace("px","",$val);
										}else if($index=="line-height"){
											$val=str_replace("em","",$val);
										}else if($index=="letter-spacing"){
											$val=str_replace("em","",$val);
										}else if($index=="font-family"){
											$found=array_search($val,$old_font_arr);
											$val=$found?$found:$val;

										}

									$new_settings[$key][$index]=$val;
									}
								}
								$new_settings[$key]['line_height_unit']='em';
								$new_settings[$key]['unit']='px';
								$new_settings[$key]['subset']='';
								$new_settings[$key]['text-align']='';
								$new_settings[$key]['font-variant']='';

						}else{
						$new_settings[$key]=$field_val;
						}
				}
				update_option('ects_options',$new_settings);
				update_option('settings_migration_status','done');
				delete_option('ect_options');
			 }	

			}
    }

}
/*** EventsCalendarTemplates main class - END */


/*** THANKS - CoolPlugins.net ) */
$ect=EventsCalendarTemplates::get_instance();
$ect->registers();

