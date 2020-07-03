<?php
add_action('admin_menu', 'any_mobile_create_menu');
add_action('admin_notices', 'amts_pro_notification');


if (isset($_GET['hidemsg']) == 1){
	update_option('amts_hide_pro_notice','yes');
}

function amts_pro_notification(){
	if (get_option('amts_hide_pro_notice') != 'yes'){
		 echo '<div class="updated">
       <p><b>Any Mobile Theme Swticher Pro</b> now works with <b>W3 Total Cache</b>, have support for <b>QR code</b> and can serve different <b>Home Page</b> based on mobile devices.<br/>Click <a href="http://dnesscarkey.com/any-mobile-theme-switcher-pro/" target="_blank">here</a> for details.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="options-general.php?pageany-mobile-theme-switcher-pro/plugin_interface.php&hidemsg=1">Hide This Message</a></p>
	   </div>';
	}
}

function any_mobile_create_menu() {
	add_options_page('Any Mobile Theme', 'Any Mobile Theme', 'administrator', __FILE__, 'am_settings_page');
	add_action('admin_init', 'register_mysettings_theme');
}

function register_mysettings_theme() {
	register_setting('am-settings-group', 'iphone_theme');
	register_setting('am-settings-group', 'ipad_theme');
	register_setting('am-settings-group', 'android_theme');
	register_setting('am-settings-group', 'android_tab_theme');
	register_setting('am-settings-group', 'blackberry_theme');
	register_setting('am-settings-group', 'windows_theme');
	register_setting('am-settings-group', 'opera_theme');
	register_setting('am-settings-group', 'parm_os_theme');
	register_setting('am-settings-group', 'other_theme');
	register_setting('am-settings-group', 'mobile_view_theme_link_text');
	register_setting('am-settings-group', 'desktop_view_theme_link_text');
	register_setting('am-settings-group', 'show_switch_link_for_desktop');
	register_setting('am-settings-group', 'forced_layout_expire_time');
}

function custom_get_themelist(){
	$themes = wp_get_themes();
	$wp_themes = array();

	foreach ( $themes as $theme ) {
		$name = $theme->get('Name');
		if ( isset( $wp_themes[ $name ] ) )
			$wp_themes[ $name . '/' . $theme->get_stylesheet() ] = $theme;
		else
			$wp_themes[ $name ] = $theme;
	}

	return $wp_themes;
}

function am_settings_page() {	
	global $amts_force_param;
	include('includes/amts-header.php');
	include('includes/amts-theme-select.php');
	include('includes/amts-force-switch.php');
	include('includes/amts-readme.php');
	include('includes/amts-footer.php');
}