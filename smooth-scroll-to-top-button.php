<?php
/**
 * Plugin Name: Smooth Scroll to top Button
 * Plugin URI: https://wordpress.org/plugins/smooth-scroll-to-top-button/
 * Version:     1.2.4
 * Description: This is a quite simple and light weight plugin to for "Scroll to top" button with maximum customization options. Install, setup, enjoy :)
 * Author: TeamEngineers
 * Author URI:  https://www.fiverr.com/mahmud_remal/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: smooth-scroll-to-top-button
 * Domain Path: /languages
 * 
 * Requires PHP: 5.6
 * Requires at least: 4.8
 * Tested up to: 5.9
 *
 * @package TeamEngineers plugin for GoToTop.
 */

class TEs_Addons_FOR_GO_TO_TOP_WORDPRESS {

	private static $instance	= null;
	public $plugin;
	private $scroll_to_top_button_setup_options;
	private $scroll_to_top_button_setup_option_name_field;

	public static function get_instance() {
		
		if( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	

	private function __construct() {
		load_plugin_textdomain( 'smooth-scroll-to-top-button', false, 'languages' );
		if( is_admin() ) {
			register_activation_hook( __FILE__, [$this, 'activate_plugin'] );
			add_action( 'admin_init', [ $this, 'scroll_to_top_button_plugin_do_activation_redirect' ] );
			register_deactivation_hook( __FILE__, [$this, 'deactivate_plugin'] );
			$this->plugin = get_plugin_data( __FILE__ );
			// settings pages fields
			$this->init();
			$this->render();
			add_action( 'admin_menu', [ $this, 'scroll_to_top_button_setup_add_plugin_page' ] );
			add_action( 'admin_init', [ $this, 'page_init' ] );
			// else Inject button on frontend.
		}else{add_action( 'wp_footer', [ $this, 'hook_goto_top_content_on_footer' ], 0 );}
	}
	public function init() {
		$this->scroll_to_top_button_setup_option_name_field = [
			[
				'id' => 'enable',
				'type' => 'switcher',
				'title' => __( 'Enable', 'smooth-scroll-to-top-button' ),
				'default' => ( ! get_option( 'scroll_to_top_button_setup_option_name', false ) ) ? 'on' : false
			],
			[
				'id' => 'location',
				'type' => 'radio',
				'title' => __( 'Location', 'smooth-scroll-to-top-button' ),
				'default' => 'right',
				'options' => [
					'left' => __( 'Left side', 'smooth-scroll-to-top-button' ),
					'right' => __( 'Right side', 'smooth-scroll-to-top-button' )
				]
			],
			[
				'id' => 'locationOffset',
				'type' => 'number',
				'title' => __( 'Location offset', 'smooth-scroll-to-top-button' ),
				'default' => 150
			],
			[
				'id' => 'bottomOffset',
				'type' => 'number',
				'title' => __( 'Bottom offset', 'smooth-scroll-to-top-button' ),
				'default' => 100
			],
			[
				'id' => 'containerSize',
				'type' => 'number',
				'title' => __( 'Container size', 'smooth-scroll-to-top-button' ),
				'default' => 40
			],
			[
				'id' => 'containerRadius',
				'type' => 'number',
				'title' => __( 'Container radius', 'smooth-scroll-to-top-button' ),
				'default' => 10
			],
			[
				'id' => 'trigger',
				'type' => 'number',
				'title' => __( 'Trigger', 'smooth-scroll-to-top-button' ),
				'default' => 250
			],
			[
				'id' => 'entryAnimation',
				'type' => 'radio',
				'title' => __( 'Animation', 'smooth-scroll-to-top-button' ),
				'default' => 'fade',
				'options' => [
					'fade' => __( 'FadeIn', 'smooth-scroll-to-top-button' ),
					'slide' => __( 'Slide', 'smooth-scroll-to-top-button' )
				]
			],
			[
				'id' => 'goupSpeed',
				'type' => 'radio',
				'title' => __( 'Speed', 'smooth-scroll-to-top-button' ),
				'default' => 'slow',
				'options' => [
					'slow' => __( 'Slow', 'smooth-scroll-to-top-button' ),
					'fast' => __( 'Fast', 'smooth-scroll-to-top-button' )
				]
			],
			[
				'id' => 'second',
				'type' => 'number',
				'title' => __( 'Duration', 'smooth-scroll-to-top-button' ),
				'default' => 0.3
			],
			[
				'id' => 'hideUnderWidth',
				'type' => 'number',
				'title' => __( 'Hide under width', 'smooth-scroll-to-top-button' ),
				'default' => 500
			],
			[
				'id' => 'acolor',
				'type' => 'color',
				'title' => __( 'SVG color', 'smooth-scroll-to-top-button' ),
				'default' => '#eee'
			],
			[
				'id' => 'bcolor',
				'type' => 'color',
				'title' => __( 'SVG fill', 'smooth-scroll-to-top-button' ),
				'default' => '#333333c2'
			],
			[
				'id' => 'img',
				'type' => 'radio-image',
				'title' => __( 'Choose Icon', 'smooth-scroll-to-top-button' ),
				'default' => 4,
				'options' => [ 1, 2, 3, 4 ,5 ]
			],
			[
				'id' => 'stylesheet-form',
				'type' => 'style',
				'content' => '.choose_image_radio[type=radio] {position: absolute;opacity: 0;width: 0;height: 0;}.choose_image_radio[type=radio] + img, .choose_image_radio[type=radio] + svg {cursor: pointer;width: 70px;height: 70px;margin: 5px;padding: 10px;}.choose_image_radio[type=radio]:checked + img, .choose_image_radio[type=radio]:checked + svg {outline: 2px solid #f00;}.choose_radio {margin: auto;margin-right: 10px;margin-top: 10px;}.choose_radio, .choose_switcher{cursor: pointer;user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;}.choose_radio > input, .choose_switcher > input{position: absolute;opacity: 0;width: 0;height: 0;}.choose_radio > i, .choose_switcher > i{display: inline-block;vertical-align: middle;width: 16px;height: 16px;border-radius: 50%;transition: 0.2s;box-shadow: inset 0 0 0 16px #fff;border: 1px solid gray;background: gray;}.choose_radio:hover > i{box-shadow: inset 0 0 0 3px #fff;background: gray;}.choose_radio > input:checked + i{box-shadow: inset 0 0 0 3px #fff;background: orange;}.choose_switcher > i {width: 50px;border-radius: 3px;height: 25px;}.choose_switcher > input + i:after {content: "";display: block;height: 20px;width: 20px;margin: 2.5px;border-radius: inherit;transition: inherit;background: gray;}.choose_switcher > input:checked + i:after {margin-left: 27px;background: orange;}'
			]
		];
	}
	public function activate_plugin() {
		// redirect to option page because set up is mendatory for this.
		add_option( 'scroll_to_top_button_plugin_do_activation_redirect', true);
		// Restore settings from previous installation.
		$opt = get_option( 'scroll_to_top_button_setup_option_name', false );
		if( $opt ) {$opt['enable'] = 'on';update_option( 'scroll_to_top_button_setup_option_name', $opt, null );}
	}
	public function scroll_to_top_button_plugin_do_activation_redirect() {
		if( get_option( 'scroll_to_top_button_plugin_do_activation_redirect', false ) ) {
			delete_option( 'scroll_to_top_button_plugin_do_activation_redirect' );
			if( ! isset( $_GET['activate-multi'] ) ){
				wp_redirect( admin_url( 'tools.php?page=scroll-to-top-button-setup' ) );
				exit;
			}
	 }
	}
	public function deactivate_plugin() {
		add_action( 'admin_notices', function() {
			?>
			<div class="notice notice-success is-dismissible">
				<p>
					<?php
					// translators: placeholders are opening and closing <a> tag, linking to BuddyPress plugin
					echo '"' . $this->plugin['Name'] . '" ';
					esc_html_e( 'deactivated successfully. Need any help?', 'smooth-scroll-to-top-button' );
					?>
					<a href="https://www.fiverr.com/mahmud_remal/" target="_blank"><?php _e( 'Here we are', 'smooth-scroll-to-top-button' ); ?></a>
				</p>
				<button type="button" class="notice-dismiss" onclick="return this.parentNode.remove();">
					<span class="screen-reader-text">Dismiss this notice.</span>
				</button>
			</div>
			<?php
		} );
		// delete_option( 'scroll_to_top_button_setup_option_name' );
	}
	public function hook_goto_top_content_on_footer() {
		if( ! is_admin() ) {
			?>
			<script>
				<?php
				$totop_opt = wp_parse_args( get_option( 'scroll_to_top_button_setup_option_name', ['enable' => 'on'] ), [
					'enable' => 'off',
					'bg' => '#333',
					'acolor' => '#bbb',
					'location' => 'right',
					'locationOffset' => 150,
					'bottomOffset' => 100,
					'containerSize' => 40,
					'containerRadius' => 10,
					'containerClass' => 'goup-container',
					'alwaysVisible' => !1,
					'trigger' => 250,
					'entryAnimation' => 'fade',
					'goupSpeed' => 'slow',
					'hideUnderWidth' => 500,
					'second' => .3,
					'img' => 4
				] );
				( $totop_opt['enable'] != 'on' ) || wp_enqueue_script( 'gototop-addon', plugin_dir_url( __FILE__ ) . 'assets/te-scroll-top.js', ['jquery'], true );
				echo ( $totop_opt['enable'] == 'on' ) ? '
				jQuery(document).ready(function(){
					jQuery.goup( {
            acolor: "' . str_replace( "'", '"', $totop_opt['acolor'] ) . '",
						bcolor: "' . str_replace( "'", '"', $totop_opt['bg'] ) . '",
						img: \'' . str_replace( "'", '"', $this->scroll_to_top_button_get_svg( $totop_opt['img'], $totop_opt ) ) . '\',
						location: "' . str_replace( "'", '"', $totop_opt['location'] ) . '",
            locationOffset: "' . str_replace( "'", '"', $totop_opt['locationOffset'] ) . '",
            bottomOffset: "' . str_replace( "'", '"', $totop_opt['bottomOffset'] ) . '",
            containerSize: "' . str_replace( "'", '"', $totop_opt['containerSize'] ) . '",
            containerRadius: "' . str_replace( "'", '"', $totop_opt['containerRadius'] ) . '",
            trigger: "' . str_replace( "'", '"', $totop_opt['trigger'] ) . '",
            entryAnimation: "' . str_replace( "'", '"', $totop_opt['entryAnimation'] ) . '",
            goupSpeed: "' . str_replace( "'", '"', $totop_opt['goupSpeed'] ) . '",
            hideUnderWidth: "' . str_replace( "'", '"', $totop_opt['hideUnderWidth'] ) . '",
            second: "' . str_replace( "'", '"', $totop_opt['second'] ) . '",
            containerClass: "goup-container",
            alwaysVisible: !1
					} );
				});' : '';
				?>
			</script>
			<style>.goup-container{max-width: 60px;max-height: 60px;}</style>
			<?php
		}
	}
	public function admin_option_page() {}

	
	public function scroll_to_top_button_setup_add_plugin_page() {
		add_management_page(
			'Scroll to Top Button setup', // page_title
			'Scroll to Top', // menu_title
			'manage_options', // capability
			'scroll-to-top-button-setup', // menu_slug
			array( $this, 'scroll_to_top_button_setup_create_admin_page' ) // function
		);
	}

	public function scroll_to_top_button_setup_create_admin_page() {
		$this->scroll_to_top_button_setup_options = get_option( 'scroll_to_top_button_setup_option_name', ['404'] );
		// echo '<pre>';print_r($this->scroll_to_top_button_setup_options);echo '</pre>';
		?>

		<div class="wrap">
			<h2>Scroll to Top Button setup</h2>
			<p>Setup / customize your website scroll to the top button.
				Make sure all fields are valid. If you can't see any button on the front-end, recheck which fields are wrong here.</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'scroll_to_top_button_setup_option_group' );
					do_settings_sections( 'scroll-to-top-button-setup-admin' );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}

	public function page_init() {
		register_setting(
			'scroll_to_top_button_setup_option_group', // option_group
			'scroll_to_top_button_setup_option_name', // option_name
			[ $this, 'sanitize' ] // sanitize_callback
		);
		add_settings_section(
			'scroll_to_top_button_setup_setting_section', // id
			'Settings', // title
			array( $this, 'scroll_to_top_button_setup_section_info' ), // callback
			'scroll-to-top-button-setup-admin' // page
		);
	}

	public function render() {
		foreach( $this->scroll_to_top_button_setup_option_name_field as $field ){
			add_settings_field(
				$field['id'], // id
				$field['title'], // title
				[ $this, 'field' ], // callback
				'scroll-to-top-button-setup-admin', // page
				'scroll_to_top_button_setup_setting_section', // section
				$field // args
			);
		}
	}
	private function value( $nm, $def ) {
		return isset( $this->scroll_to_top_button_setup_options[ $nm ] ) ? $this->scroll_to_top_button_setup_options[ $nm ] : $def ;
	}
	private function is_checked( $field, $val ) {
		if( isset( $this->scroll_to_top_button_setup_options[ $field['id'] ] ) && $this->scroll_to_top_button_setup_options[ $field['id'] ] == $val ){
			return 'checked';
		}else{
			if( ! isset( $this->scroll_to_top_button_setup_options[ $field['id'] ] ) && $field['default'] == $val ){
				return 'checked';
			}
		}
		return '';
	}
	public function field( $field ) {
		switch( $field['type'] ) {
			case 'text' :
			case 'number' :
			case 'color' :
			case 'date' :
			case 'datetime' :
			case 'time' :
				$this->input( $field );
				break;
			case 'radio' :
				$this->radio( $field );
				break;
			case 'radio-image' :
				$this->radio_image( $field );
				break;
			case 'switcher' :
				$this->switcher( $field );
				break;
			case 'style' :
				$this->style( $field );
				break;
			default :
				break;
		};
	}
	public function input( $field ) {
		printf(
			'<input class="regular-text" type="%s" name="scroll_to_top_button_setup_option_name[%s]"  value="%s">',
			$field['type'],$field['id'],
			$this->value( $field['id'], $field['default'] )
		);
	}
	public function switcher( $field ) {
		printf(
			'<label class="choose_switcher">
				<input type="checkbox" name="scroll_to_top_button_setup_option_name[%s]" %s>
				<i></i>
			</label>',
			$field['id'],
			$this->is_checked( $field, 'on' )
		);
	}
	public function radio( $field ) {
		foreach( $field['options'] as $i => $title ) {
			printf(
				'<label class="choose_radio">
					<input type="radio" name="scroll_to_top_button_setup_option_name[%s]" value="%s" %s >
					<i></i> %s
				</label>',
				$field['id'],
				$i,
				$this->is_checked( $field, $i ),
				$title
			);
		}
	}
	public function radio_image( $field ) {
		?><fieldset><?php
		foreach( $field['options'] as $i ) {
			printf(
				'<label for="%s">
					<input class="choose_image_radio" type="radio" name="scroll_to_top_button_setup_option_name[%s]" id="%s" value="%s" %s >
					%s
				</label>',
				$field['id'] . '_' . $i,
				$field['id'],
				$field['id'] . '_' . $i,
				$i, $this->is_checked( $field, $i ),
				$this->scroll_to_top_button_get_svg( $i )
			);
		} ?>
		</fieldset>
		<?php
	}
	public function style( $field ) {
		printf(
			'<style id="%s" t ype="text/stylesheet">%s</style>',
			$field['id'],
			$field['content']
		);
	}
	public function sanitize($input) {
		$sanitary_values = array();
		foreach( $this->scroll_to_top_button_setup_option_name_field as $field ){
			if( isset( $input[ $field['id'] ] ) ) {
				if( in_array( $field['type'], [ 'text', 'textarea' ] ) ) {
					$sanitary_values[ $field['id'] ] = sanitize_text_field( $input[ $field['id'] ] );
				}else{
					$sanitary_values[ $field['id'] ] = $input[ $field['id'] ];
				}
			}
		}
		return $sanitary_values;
	}

	public function scroll_to_top_button_setup_section_info() {}
	public function scroll_to_top_button_get_svg( $id, $options = false ) {
		if( $options && is_array( $options ) && isset( $options['acolor'] ) && isset( $options['bcolor'] ) ){$this->scroll_to_top_button_setup_options = $options;}
		$svg = file_exists( plugin_dir_path( __FILE__ ) . 'assets/' . $id . '.svg' ) ? file_get_contents( plugin_dir_path( __FILE__ ) . 'assets/' . $id . '.svg' ) : '';
		$svg = str_replace( [
			'{bcolor}',
			'{acolor}'
		], [
			isset( $this->scroll_to_top_button_setup_options[ 'bcolor' ] ) ? $this->scroll_to_top_button_setup_options[ 'bcolor' ] : '#eee',
			isset( $this->scroll_to_top_button_setup_options[ 'acolor' ] ) ? $this->scroll_to_top_button_setup_options[ 'acolor' ] : '#333333c1'
		], $svg );
		return $svg;
	}

}
TEs_Addons_FOR_GO_TO_TOP_WORDPRESS::get_instance();