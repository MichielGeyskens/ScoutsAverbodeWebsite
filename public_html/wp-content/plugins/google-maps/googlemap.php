<?php

/*
Plugin Name: Huge IT Google Maps
Plugin URI: http://huge-it.com/google-map
Description: This easy to use Google Map plugin gives you opportunity to show anything on the map with fantastic tools of Google Maps.
Version: 2.0.5
Author: Huge-IT
Author URI: http://huge-it.com
License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/
 
add_action('init', 'hugemaps_do_output_buffer');
function hugemaps_do_output_buffer() {
    ob_start();
}
 
add_action('media_buttons_context', 'add_google_maps_my_custom_button');

function add_google_maps_my_custom_button($context) {
wp_enqueue_script("simple_slider",plugins_url("js/simple-slider.js",__FILE__), FALSE);
wp_enqueue_style("simple_slider_style",plugins_url("style/simple-slider.css",__FILE__), FALSE);
	$img = plugins_url('images/google-maps-18-x-18.png' , __FILE__ );


	$container_id = 'huge_it_google_map';


	$title = 'Select Huge IT Google Map to insert into post';

	$context .= '<a class="button thickbox" title="Select Google Map to insert into post"    href="#TB_inline?width=400&inlineId='.$container_id.'">
		<span class="wp-media-buttons-icon" style="background: url('.$img.'); background-repeat: no-repeat; background-position: left bottom;"></span>
	Add Google Map
	</a>';

	return $context;
}

add_action('admin_footer', 'add_google_maps_inline_popup_content');

function add_google_maps_inline_popup_content() {
?>
<script type="text/javascript">
		jQuery(document).ready(function() {
		  jQuery('#hugeitmapinsert').on('click', function() {
			var id = jQuery('#huge_it_map_select option:selected').val();
	
			window.send_to_editor('[huge_it_maps id="' + id + '"]');
			tb_remove();
			var name = jQuery("#map_name").val();
			var type = jQuery("#map_type").val();
			var width = jQuery("#map_width").val();
			var height = jQuery("#map_height").val();
			var align = jQuery("#map_align").val();
			var map_id = id;
			var data = {
				action:"g_map_options",
				id:map_id,
				name:name,
				type:type,
				width:width,
				height:height,
				align:align,
			}
			jQuery.post("<?php echo admin_url( 'admin-ajax.php' ); ?>",data,function(response){
				if(response.success)
				{
				}
			},"json")
			return false;
		  })
		  jQuery("#huge_it_map_select").on("change",function(){
			var name = jQuery("#map_name").val();
			var type = jQuery("#map_type").val();
			var width = jQuery("#map_width").val();
			var height = jQuery("#map_height").val();
			var align = jQuery("#map_align").val();
			id = jQuery('#huge_it_map_select option:selected').val();
			var data = {
				action:"g_map_options",
				task:"post_shortcode_change_map",
				id:id,
				name:name,
				type:type,
				width:width,
				height:height,
				align:align,
			}
			jQuery.post("<?php echo admin_url( 'admin-ajax.php' ); ?>",data,function(response){
				if(response.success){
					jQuery("#map_name").val(response.name);
					if(response.type == "ROADMAP"){
						jQuery("#map_type option").eq(0).attr("selected","selected");
					}
					if(response.type == "SATELLITE"){
						jQuery("#map_type option").eq(1).attr("selected","selected");
					}
					if(response.type == "HYBRID"){
						jQuery("#map_type option").eq(2).attr("selected","selected");
					}
					if(response.type == "TERRAIN"){
						jQuery("#map_type option").eq(3).attr("selected","selected");
					}
					jQuery("#map_width").simpleSlider("setValue", response.width);
					if(response.align == "left"){
						jQuery("#map_align option").eq(0).attr("selected","selected");
					}
					if(response.align == "center"){
						jQuery("#map_align option").eq(1).attr("selected","selected");
					}
					if(response.align == "right"){
						jQuery("#map_align option").eq(2).attr("selected","selected");
					}
					jQuery("#map_height").val(response.height);
					jQuery("#map_border_radius").val(response.border_radius);
				}
			},"json")
			return false;
		})
		  jQuery('#map_width').bind("slider:changed", function (event, data) {
				 jQuery(this).parent().find('span').html(data.value+"%");
				 jQuery(this).val(data.value);
			});	
		});
</script>
<style>
	#huge_it_map_select {
		width:51%;
	}
	
	
	.tb_popup_form {
		position:relative;
		display:block;
	}
	
	.tb_popup_form li {
		display:block;
		height:35px;
		width:70%;
	}
	
	.tb_popup_form li label {
		float:left;
		width:35%
	}
	
	.tb_popup_form li input, .tb_popup_form li select {
		float:left;
		width:60%;
	}

	
	.slider, .slider-container {
		display:block;
		position:relative;
		height:35px;
		line-height:35px;
	}



	
</style>
<div id="huge_it_google_map" style="display:none;">

	<form method="post" action="">
  <h3>Select The Map</h3>
  <?php 
  	  global $wpdb;
	  $query="SELECT * FROM ".$wpdb->prefix."g_maps order by id ASC";
			   $shortcodemaps=$wpdb->get_results($query);
			   ?>

 <?php 	if (count($shortcodemaps)) {
			echo "<select id='huge_it_map_select'>";
			foreach ($shortcodemaps as $shortcodemap) 
			{
				?>
				<option value="<?php echo $shortcodemap->id; ?>"><?php echo $shortcodemap->name; ?></option>
				<?php 
			}
			?>
			</select>
			<button class='button primary' id='hugeitmapinsert'>Insert Map</button> 
			<ul class="tb_popup_form">
				<li class="has_background">
					<label for="map_name">Map name</label>
					<input type="text" name="map_name" id="map_name" value="<?php echo $shortcodemap->name; ?>" />
				</li>
				<li>
					<label for="map_type" >Map type</label>
					<?php $type = $shortcodemap->type; ?>
					<select id="map_type" name="map_type" >
						<option value="ROADMAP" <?php if($type == "ROADMAP"){ echo "selected"; } ?> >Roadmap</option>
						<option value="SATELLITE" <?php if($type == "SATELLITE"){ echo "selected"; } ?> >Satellite</option>
						<option value="HYBRID" <?php if($type == "HYBRID"){ echo "selected"; } ?> >Hybrid</option>
						<option value="TERRAIN" <?php if($type == "TERRAIN"){ echo "selected"; } ?> >Terrain</option>
					</select>
				</li>
				
				<li class="has_background">
					<label for="map_width">Map width</label>
					<div class="slider-container" style="float:left; width:60%; height:25px; ">
						<input name="map_width" id="map_width" data-slider-highlight="true"  data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true" value="<?php echo $shortcodemap->width; ?>" />
						<span style="position:absolute; top: -1px; right: 0px;"><?php echo $shortcodemap->width; ?>%</span>
					</div>
				</li>
				<li>
					<label for="map_height" >Map height</label>
					<input type="text" name="map_height" id="map_height" value="<?php echo $shortcodemap->height; ?>" />
				</li>
				<li class="has_background">
					<label for="map_align">Map align</label>
					<select name="map_align" id="map_align">
						<option value="left" <?php if($shortcodemap->align == 'left'){echo 'selected' ; } ; ?>>left</option>
						<option value="center" <?php if($shortcodemap->align == 'center'){echo 'selected' ; } ; ?>>center</option>
						<option value="right" <?php if($shortcodemap->align == 'right'){echo 'selected' ; } ; ?>>right</option>
					</select>
				</li>
				<li>
					<label for="map_border_radius" >Border radius</label>
					<input type="text" name="map_border_radius" id="map_border_radius" value="<?php echo $shortcodemap->border_radius; ?>" />
				</li>
			</ul>
			</form>
		</div>	
		
			<?php
		} 
		else 
		{
			echo "No Map found", "huge_it_map";
		}
		?>
	
<?php
}
 
add_action('admin_menu', 'hugeitgooglemapss_options_panel');
 
function hugeitgooglemapss_options_panel()
{
    $page_cat = add_menu_page('Theme page title', 'Google Maps', 'manage_options', 'hugeitgooglemaps_main', 'hugeitgooglemaps_main', plugins_url('images/google-maps-20-x-20.png', __FILE__));
	//$page_option = add_submenu_page('hugeitgooglemaps_main', 'General Options', 'General Options', 'manage_options', 'Option_hugeitgooglemaps', 'Option_hugeitgooglemaps');
	
	add_submenu_page('hugeitgooglemaps_main', 'Featured Plugins', 'Featured Plugins', 'manage_options', 'huge_it__google_maps_plugins', 'huge_it__google_maps_featured_plugins');
	add_submenu_page('hugeitgooglemaps_main', 'Licensing', 'Licensing', 'manage_options', 'huge_it_google_mapss_Licensing', 'huge_it_google_mapss_Licensing');
	add_action('admin_print_styles-' . $page_cat, 'hugeitgooglemapss_admin_script');
	//add_action('wp_head','wp_admin_head');
}

function huge_it_google_mapss_Licensing(){
	if ( is_plugin_active( 'google-map-wp/googlemap.php' ) ) {
		header('Location:admin.php?page=huge_it_google_maps_Licensing');
	}
?>
    <div style="width:95%">
    <p>
	This plugin is the non-commercial version of the Huge IT Google Map. If you want to customize to the styles and colors of your website,than you need to buy a license.
	Purchasing a license will add possibility to customize the pro options of the Huge IT Google Map.
 </p>
<br /><br />
<a href="http://huge-it.com/google-maps/" class="button-primary" target="_blank">Purchase a License</a>
<br /><br /><br />
<p>After the purchasing the commercial version follow this steps:</p>
<ol>
	<li>Deactivate Huge IT Google Map Plugin</li>
	<li>Delete Huge IT Google Map Plugin</li>
	<li>Install the downloaded commercial version of the plugin</li>
</ol>
</div>
<?php
}



function huge_it__google_maps_featured_plugins()
{
	if ( is_plugin_active( 'google-map-wp/googlemap.php' ) ) {
		header('Location:admin.php?page=huge_it__google_map_plugins');
	}
	include_once("admin/huge_it_featured_plugins.php");
}

function hugeitgooglemapss_admin_script()
{ 	
	wp_enqueue_media();
	wp_enqueue_script('media-upload');
	wp_enqueue_style("maps_admin_css", plugins_url("style/style.css", __FILE__), FALSE);
	wp_enqueue_style("simple_slider_style",plugins_url("style/simple-slider.css",__FILE__), FALSE);
	wp_enqueue_script("maps_admin_js", plugins_url("js/js.js", __FILE__), FALSE);
	wp_enqueue_script("gmap", 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places');
	wp_enqueue_script("simple_slider",plugins_url("js/simple-slider.js",__FILE__), FALSE);
	//wp_enqueue_script( 'ajax_script', plugins_url( 'js/maps_ajax.js', __FILE__ ), array('jquery'));
	wp_enqueue_script('param_block2', plugins_url("jscolor/jscolor.js", __FILE__));
	//wp_localize_script( 'ajax_script', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => $email_nonce ) );
?>
<?php ; }

function hugeitgooglemapss_option_admin_script()
{ ?>
	
<?php ; }

function hugeitgooglemaps_main()
{ 
	if ( is_plugin_active( 'google-map-wp/googlemap.php' ) ) {
		header('Location:admin.php?page=hugeitgooglemap_main');
	}
	require_once("admin/maps_func.php");
	require_once("admin/maps_view.php");
	global $wpdb;
	if(!isset($_GET['task']))
	{
		show_maps();
	}
	else
	{
		if(isset($_GET['id']))
		{
			$id=$_GET['id'];
		}
		$task = $_GET['task'];
		switch($task){
			case 'add_cat':
			add_maps();
			break;
			
			case 'edit_cat':
			edit_maps();
			break;
			
			case 'remove_cat':
			remove_maps($id);
			show_maps();
			break;
		}
	}
}

add_action("wp_enqueue_scripts","mapss_front_end_scripts");

function mapss_front_end_scripts()
{
	wp_enqueue_script("jquery");
	wp_enqueue_script("gmap", 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places');
	//wp_enqueue_script( 'ajax_script', plugins_url( 'js/front_end.js', __FILE__ ), array('jquery'));
	//wp_localize_script( 'ajax_script', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => $email_nonce ) );
}

add_shortcode('huge_it_maps','huge_it_google_mapss_shortcode');

function huge_it_google_mapss_shortcode($atts)
{
	require_once("Front_end/maps_front_end_view.php");
	$atts = shortcode_atts(
		array(
			'id'=> ''
		),$atts);
	return showpublishedmap($atts['id']);
}


add_action("wp_ajax_nopriv_g_map_options","g_maps_nopriv_options_callback");

function g_maps_nopriv_options_callback(){
	function parseToXML($htmlStr)
	{
		$xmlStr=str_replace('<','&lt;',$htmlStr);
		$xmlStr=str_replace('>','&gt;',$xmlStr);
		$xmlStr=str_replace('"','&quot;',$xmlStr);
		$xmlStr=str_replace("'",'&#39;',$xmlStr);
		$xmlStr=str_replace("&",'&amp;',$xmlStr);
		return $xmlStr;
	}
	if(isset($_POST['task'])){
		if($_POST['task']=="getxml"){
			$id=$_POST['map_id'];
			$response ="<?xml version='1.0' encoding='UTF-8'?>
					<maps>";
			global $wpdb;
			
			
			$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."g_maps WHERE id=%s", $id);
			$getMap = $wpdb->get_results($sql);
			
			if(isset($getMap))
			{
				foreach($getMap as $mapinfo)
				{
					$response = $response."<map name='". parseToXML($mapinfo->name) ."' info_type='".$mapinfo->info_type ."' pan_controller='".$mapinfo->pan_controller ."' zoom_controller='".$mapinfo->zoom_controller ."' type_controller='".$mapinfo->type_controller ."' scale_controller='".$mapinfo->scale_controller ."' street_view_controller='".$mapinfo->street_view_controller ."' overview_map_controller='".$mapinfo->overview_map_controller ."' type='". $mapinfo->type ."' zoom='". $mapinfo->zoom ."' center_lat='". $mapinfo->center_lat."' center_lng='". $mapinfo->center_lng."'  />";
				}
				$sql =$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."g_markers WHERE map=%d", $id);
				$getMarkers = $wpdb->get_results($sql);

					if(isset($getMarkers))
					{

						foreach($getMarkers as $marker)
						{
							$response = $response."<marker id='". $marker->id ."' size='".$marker->size."' name='".parseToXML($marker->title)."' animation='".$marker->animation."' lat='".$marker->lat."' lng='".$marker->lng."' description='".parseToXML($marker->description) ."' img='".parseToXML($marker->img) ."' />";
						}	
					}
				$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."g_polygones WHERE map=%d",$id);
				$getPolygone = $wpdb->get_results($sql);
				if(isset($getPolygone))
				{
					foreach($getPolygone as $polygone)
					{
						$response = $response."<polygone id='". $polygone->id ."' name='".parseToXML($polygone->name) ."'  url='".parseToXML($polygone->url) ."' line_width='" . $polygone->line_width ."'  line_opacity='".$polygone->line_opacity ."' line_color='".$polygone->line_color ."' fill_opacity='".$polygone->fill_opacity ."' fill_color='".$polygone->fill_color ."' hover_line_color='".$polygone->hover_line_color ."' hover_line_opacity='".$polygone->hover_line_opacity ."' hover_fill_color='".$polygone->hover_fill_color ."' hover_fill_opacity='".$polygone->hover_fill_opacity ."' >";
						preg_match_all('/\(([^\)]*)\)/', $polygone->data, $matches);
						foreach($matches[1] as $latlng)
						{
								preg_match_all("/[^,]+[\d+][.?][\d+]*/",$latlng,$results);
								foreach($results as $latlng)
								{
									$response = $response."<latlng lat='".$latlng[0]."' lng='".$latlng[1]."' />";
								}
						}
						$response = $response."</polygone>";
					}
				}
				$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."g_polylines WHERE map=%s",$id);
				$getPolyline = $wpdb->get_results($sql);
				if(isset($getPolyline))
				{
					foreach($getPolyline as $polyline)
					{
						$response = $response."<polyline id='".$polyline->id ."' name='".parseToXML($polyline->name) ."' hover_line_color='".$polyline->hover_line_color ."' hover_line_opacity='".$polyline->hover_line_opacity ."' line_opacity='".$polyline->line_opacity."' line_color='".$polyline->line_color."' line_width='".$polyline->line_width."' >";
						preg_match_all('/\(([^\)]*)\)/', $polyline->data, $matches);
						foreach($matches[1] as $latlng)
						{
							preg_match_all("/[^,]+[\d+][.?][\d+]*/",$latlng,$results);
							foreach($results as $latlng){
								$response = $response."<latlng lat='".$latlng[0]."' lng='".$latlng[1]."' />";
							}
						}
						$response = $response."</polyline>";
					}
				}
				$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."g_circles WHERE map=%d", $id);
				$getCircle = $wpdb->get_results($sql);
				if($getCircle)
				{
					foreach($getCircle as $circle)
					{
						$response = $response."<circle id='".$circle->id."' name='".parseToXML($circle->name) ."' center_lat='".$circle->center_lat."' center_lng='".$circle->center_lng."' radius='".$circle->radius."' hover_fill_color='".$circle->hover_fill_color ."' hover_fill_opacity='".$circle->hover_fill_opacity ."' hover_line_color='".$circle->hover_line_color ."' hover_line_opacity='".$circle->hover_line_opacity ."' line_width='".$circle->line_width."' line_color='".$circle->line_color."' line_opacity='".$circle->line_opacity."' fill_color='".$circle->fill_color."' fill_opacity='".$circle->fill_opacity."' show_marker='".$circle->show_marker."' />";
					}
				}
				$response = $response."</maps>";
				echo json_encode(array("success"=>$response));
				die();
			}
		}
	}
}


add_action("wp_ajax_g_map_options","g_maps_options_callback");

function g_maps_options_callback()
{
	function parseToXML($htmlStr)
	{
		$xmlStr=str_replace('<','&lt;',$htmlStr);
		$xmlStr=str_replace('>','&gt;',$xmlStr);
		$xmlStr=str_replace('"','&quot;',$xmlStr);
		$xmlStr=str_replace("'",'&#39;',$xmlStr);
		$xmlStr=str_replace("&",'&amp;',$xmlStr);
		return $xmlStr;
	}
	if(isset($_POST['task'])){
		if($_POST['task']=='post_shortcode_change_map'){
			global $wpdb;
			$sql=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix ."g_maps WHERE id=%d",$_POST['id']);
			$getMap=$wpdb->get_row($sql);
			if($getMap){
				echo json_encode(array("success"=>1,
										"name"=>$getMap->name,
										"type"=>$getMap->type,
										"width"=>$getMap->width,
										"height"=>$getMap->height,
										"align"=>$getMap->align,
										"border_radius"=>$getMap->border_radius,
										));
				die();
			}
		}
	}
	if(isset($_POST['table'])){
		global $wpdb;
		$table = $_POST['table'];
		if($table=="g_markers" || $table=="g_polygones" || $table=="g_polylines" || $table=="g_circles"){
			$table_name=$wpdb->prefix.$table;
			$sql = $wpdb->prepare("DELETE FROM %s WHERE id=%d",$table_name,$_POST['id']);
			$sql=str_replace("'","",$sql);
			if($wpdb->query($sql)){
				echo json_encode(array("success"=>1));
				die();
			}else{
				echo json_encode(array("error"=>$wpdb->last_error."        ".$sql));
				die();
			}
		}else{
			echo json_encode(array("error"=>"table name wrong"));
			die();
		}
		
	}
	if(isset($_POST['task'])){
		if($_POST['task']=="styling_submit"){
			global $wpdb;
			$sql=$wpdb->prepare("UPDATE ".$wpdb->prefix."g_maps SET styling_lightness=%d, styling_hue='%s', styling_gamma=%d, styling_saturation=%d WHERE id=%d",
								$_POST['g_map_styling_lightness'],$_POST['g_map_styling_hue'],$_POST['g_map_styling_gamma'],$_POST['g_map_styling_saturation'],$_POST['id']);
			if($wpdb->query($sql)){
				$map_params=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix ."g_maps WHERE id=%s",$_POST['id']));
				foreach($map_params as $param){
					echo json_encode(array(
											"success"=>1,
											"hue"=>$param->styling_hue,
											"saturation"=>$param->styling_saturation,
											"lightness"=>$param->styling_lightness,
											"gamma"=>$param->styling_gamma,
											"zoom"=>$param->zoom,
											"type"=>$param->type,
											"bike"=>$param->bike_layer,
											"traffic"=>$param->traffic_layer,
											"transit"=>$param->transit_layer
										)
									);
					die();
				}
			}
		}
	}
	if(isset($_POST['task'])){
		if($_POST['task']=="submit_circle_edit"){
			global $wpdb;
			$sql= $wpdb->prepare("UPDATE ".$wpdb->prefix."g_circles SET hover_line_color=%s,hover_line_opacity=%s,hover_fill_color=%s,hover_fill_opacity=%s,name=%s,center_lat=%s, center_lng=%s,radius=%s,line_width=%s,line_color=%s,line_opacity=%s,fill_color=%s,fill_opacity=%s,show_marker=%s WHERE id=%d",
													$_POST['hover_circle_edit_line_color'],$_POST['hover_circle_edit_line_opacity'],$_POST['hover_circle_edit_fill_color'],$_POST['hover_circle_edit_fill_opacity'],$_POST['circle_edit_name'],$_POST['circle_edit_center_lat'],$_POST['circle_edit_center_lng'],$_POST['circle_edit_radius'],$_POST['circle_edit_line_width'],$_POST['circle_edit_line_color'],$_POST['circle_edit_line_opacity'],$_POST['circle_edit_fill_color'],$_POST['circle_edit_fill_opacity'],$_POST['circle_edit_marker_show'],$_POST['id']);
			if($wpdb->query($sql)){
				$map_params=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix ."g_maps WHERE id=%s",$_POST['map_id']));
				foreach($map_params as $param){
					echo json_encode(array(
											"success"=>1,
											"hue"=>$param->styling_hue,
											"saturation"=>$param->styling_saturation,
											"lightness"=>$param->styling_lightness,
											"gamma"=>$param->styling_gamma,
											"zoom"=>$param->zoom,
											"type"=>$param->type,
											"bike"=>$param->bike_layer,
											"traffic"=>$param->traffic_layer,
											"transit"=>$param->transit_layer
										)
									);
					die();
				}
			}
		}
	}
	if(isset($_POST['task'])){
		if($_POST['task']=="submit_circle"){
			global $wpdb;
			$sql = $wpdb->prepare("INSERT INTO ".$wpdb->prefix."g_circles (map, name, center_lat, center_lng, radius ,hover_line_color ,hover_line_opacity ,hover_fill_color ,hover_fill_opacity , line_width, line_color, line_opacity, fill_color, fill_opacity, show_marker) VALUES(%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%d)",
				$_POST['id'],$_POST['circle_name'],$_POST['circle_center_lat'],$_POST['circle_center_lng'],$_POST['circle_radius'],$_POST['hover_circle_line_color'],$_POST['hover_circle_line_opacity'],$_POST['hover_circle_fill_color'],$_POST['hover_circle_fill_opacity'],$_POST['circle_line_width'],$_POST['circle_line_color'],$_POST['circle_line_opacity'],$_POST['circle_fill_color'],$_POST['circle_fill_opacity'],$_POST['circle_marker_show']);
			if($wpdb->query($sql)){
				$map_params=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix ."g_maps WHERE id=%s",$_POST['id']));
				foreach($map_params as $param){
					$last = $wpdb->get_row("SHOW TABLE STATUS LIKE '".$wpdb->prefix ."g_circles'");
					$last_id = $last->Auto_increment - 1;
					echo json_encode(array(
											"success"=>1,
											"hue"=>$param->styling_hue,
											"saturation"=>$param->styling_saturation,
											"lightness"=>$param->styling_lightness,
											"gamma"=>$param->styling_gamma,
											"zoom"=>$param->zoom,
											"type"=>$param->type,
											"bike"=>$param->bike_layer,
											"traffic"=>$param->traffic_layer,
											"transit"=>$param->transit_layer,
											"last_id"=>$last_id,
										)
									);
					die();
				}
			}
		}
	}
	if(isset($_POST['task'])){
		if($_POST['task']=="polyline_edit_submit"){
			global $wpdb;
			$sql = $wpdb->prepare("UPDATE ".$wpdb->prefix."g_polylines SET name=%s, data=%s ,hover_line_color=%s,hover_line_opacity=%s, line_opacity=%s, line_color=%s,line_width=%d WHERE id=%d",
								$_POST['polyline_edit_name'],$_POST['polyline_edit_coords'],$_POST['hover_polyline_edit_line_color'],$_POST['hover_polyline_edit_line_opacity'],$_POST['polyline_edit_line_opacity'],$_POST['polyline_edit_line_color'],$_POST['polyline_edit_line_width'],$_POST['id']);
			if($wpdb->query($sql)){
				$map_params=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix ."g_maps WHERE id=%s",$_POST['map_id']));
				foreach($map_params as $param){
					echo json_encode(array(
											"success"=>1,
											"hue"=>$param->styling_hue,
											"saturation"=>$param->styling_saturation,
											"lightness"=>$param->styling_lightness,
											"gamma"=>$param->styling_gamma,
											"zoom"=>$param->zoom,
											"type"=>$param->type,
											"bike"=>$param->bike_layer,
											"traffic"=>$param->traffic_layer,
											"transit"=>$param->transit_layer
										)
									);
					die();
				}
			}
		}
	}
	if(isset($_POST['task'])){
		if($_POST['task']== "submit_polyline"){
			global $wpdb;
			$sql = $wpdb->prepare("INSERT INTO ".$wpdb->prefix."g_polylines (map,name,data,hover_line_color,hover_line_opacity,line_opacity,line_color ,line_width) VALUES (%d,%s,%s,%s,%s,%s,%s,%s)",
							$_POST['id'],$_POST['polyline_name'],$_POST['polyline_coords'],$_POST['hover_polyline_line_color'],$_POST['hover_polyline_line_opacity'],$_POST['polyline_line_opacity'],$_POST['polyline_line_color'],$_POST['polyline_line_width']);
			if($wpdb->query($sql)){
				$map_params=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix ."g_maps WHERE id=%s",$_POST['id']));
				foreach($map_params as $param){
					$last = $wpdb->get_row("SHOW TABLE STATUS LIKE '".$wpdb->prefix ."g_polylines'");
					$last_id = $last->Auto_increment - 1;
					echo json_encode(array(
											"success"=>1,
											"hue"=>$param->styling_hue,
											"saturation"=>$param->styling_saturation,
											"lightness"=>$param->styling_lightness,
											"gamma"=>$param->styling_gamma,
											"zoom"=>$param->zoom,
											"type"=>$param->type,
											"bike"=>$param->bike_layer,
											"traffic"=>$param->traffic_layer,
											"transit"=>$param->transit_layer,
											"last_id"=>$last_id,
										)
									);
					die();
				}
			}
		}
	}
	if(isset($_POST['task'])){
		if($_POST['task']=="submit_polygon_edit"){
			global $wpdb;
			$sql = $wpdb->prepare("UPDATE ".$wpdb->prefix ."g_polygones SET url=%s, hover_line_opacity=%s,hover_line_color=%s,hover_fill_opacity=%s,hover_fill_color=%s,  name=%s, data=%s, line_opacity=%s, line_color=%s, line_width=%s, fill_opacity=%s, fill_color=%s WHERE id=%s",
					$_POST['polygone_edit_url'],$_POST['hover_polygone_edit_line_opacity'],$_POST['hover_polygone_edit_line_color'],$_POST['hover_polygone_edit_fill_opacity'],$_POST['hover_polygone_edit_fill_color'],$_POST['polygone_edit_name'],$_POST['polygone_edit_coords'],$_POST['polygone_edit_line_opacity'],$_POST['polygone_edit_line_color'],$_POST['polygone_edit_line_width'],$_POST['polygone_edit_fill_opacity'],$_POST['polygone_edit_fill_color'],$_POST['id']);
			if($wpdb->query($sql)){
				$map_params=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix ."g_maps WHERE id=%s",$_POST['map_id']));
				foreach($map_params as $param){
					echo json_encode(array(
											"success"=>1,
											"hue"=>$param->styling_hue,
											"saturation"=>$param->styling_saturation,
											"lightness"=>$param->styling_lightness,
											"gamma"=>$param->styling_gamma,
											"zoom"=>$param->zoom,
											"type"=>$param->type,
											"bike"=>$param->bike_layer,
											"traffic"=>$param->traffic_layer,
											"transit"=>$param->transit_layer
										)
									);
					die();
				}
			}
		}
	}
	if(isset($_POST['task'])){
		if($_POST['task']=="submit_polygon"){
			global $wpdb;
			$sql = $wpdb->prepare("INSERT INTO ".$wpdb->prefix."g_polygones (map , name, url , data ,hover_line_opacity ,hover_line_color,hover_fill_opacity ,hover_fill_color  , line_opacity , line_color , fill_opacity , fill_color, line_width) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
										$_POST['id'],$_POST['polygone_name'],$_POST['polygone_url'],$_POST['polygone_coords'],$_POST['hover_polygone_line_opacity'],$_POST['hover_polygone_line_color'],$_POST['hover_polygone_fill_opacity'],$_POST['hover_polygone_fill_color'],$_POST['polygone_line_opacity'],$_POST['polygone_line_color'],$_POST['polygone_fill_opacity'],$_POST['polygone_fill_color'],$_POST['polygone_line_width']);
			if($wpdb->query($sql)){
				$map_params=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix ."g_maps WHERE id=%s",$_POST['id']));
				foreach($map_params as $param){
					$last = $wpdb->get_row("SHOW TABLE STATUS LIKE '".$wpdb->prefix ."g_polygones'");
					$last_id = $last->Auto_increment - 1;
					echo json_encode(array(
											"success"=>1,
											"hue"=>$param->styling_hue,
											"saturation"=>$param->styling_saturation,
											"lightness"=>$param->styling_lightness,
											"gamma"=>$param->styling_gamma,
											"zoom"=>$param->zoom,
											"type"=>$param->type,
											"bike"=>$param->bike_layer,
											"traffic"=>$param->traffic_layer,
											"transit"=>$param->transit_layer,
											"last_id"=>$last_id,
										)
									);
					die();
				}
			}
		}
		
	}
	if(isset($_POST['task'])){
		if($_POST['task']=="submit_marker_edit"){
			global $wpdb;
			$sql=$wpdb->prepare("UPDATE ".$wpdb->prefix ."g_markers SET title=%s,description=%s,size=%s, lat=%s, lng=%s, animation=%s, img=%s WHERE id=%s",
				$_POST['marker_edit_title'],$_POST['marker_edit_description'],$_POST['marker_edit_image_size'],$_POST['marker_edit_location_lat'],$_POST['marker_edit_location_lng'],$_POST['marker_edit_animation'],$_POST['marker_edit_image'],$_POST['id']);
			if($wpdb->query($sql)){
				$map_params=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix ."g_maps WHERE id=%s",$_POST['map_id']));
				foreach($map_params as $param){
					echo json_encode(array(
											"success"=>1,
											"hue"=>$param->styling_hue,
											"saturation"=>$param->styling_saturation,
											"lightness"=>$param->styling_lightness,
											"gamma"=>$param->styling_gamma,
											"zoom"=>$param->zoom,
											"type"=>$param->type,
											"bike"=>$param->bike_layer,
											"traffic"=>$param->traffic_layer,
											"transit"=>$param->transit_layer
										)
									);
					die();
				}
			}
		}
	}
	if(isset($_POST['task'])){
		if($_POST['task'] == "submit_marker"){
			global $wpdb;
			$sql = $wpdb->prepare("INSERT INTO ".$wpdb->prefix."g_markers (map,title, animation,lat, lng, description, img, size) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)",
								$_POST['id'], $_POST['marker_title'], $_POST['marker_animation'], $_POST['marker_location_lat'], $_POST['marker_location_lng'], $_POST['marker_description'], $_POST['marker_image'], $_POST['marker_image_size']);
			if($wpdb->query($sql)){
				$map_params=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix ."g_maps WHERE id=%s",$_POST['id']));
				foreach($map_params as $param){
					$last = $wpdb->get_row("SHOW TABLE STATUS LIKE '".$wpdb->prefix ."g_markers'");
					$last_id = $last->Auto_increment - 1;
					echo json_encode(array(
											"success"=>1,
											"hue"=>$param->styling_hue,
											"saturation"=>$param->styling_saturation,
											"lightness"=>$param->styling_lightness,
											"gamma"=>$param->styling_gamma,
											"zoom"=>$param->zoom,
											"type"=>$param->type,
											"bike"=>$param->bike_layer,
											"traffic"=>$param->traffic_layer,
											"transit"=>$param->transit_layer,
											"last_id"=>$last_id,
										)
									);
					die();
				}
			}
		}
	}
	if(isset($_POST['filename'])){
		$filename = $_POST['filename'];
		$size = $_POST['size'];
		$url = $_POST['url'];
		$imagesize = $size.",".$size;
		$image = wp_get_image_editor($url);
		$ext = pathinfo($url, PATHINFO_EXTENSION);
		$image->resize( $size, $size, true );
		$filenameimage = $image->save('huge-it-google-map-custom-icons/'.$filename."".$size.".".$ext);
		echo json_encode(array("success"=>$filenameimage['path']));
		die();
	}
	if(isset($_POST['task'])){
		if($_POST['task']=="submit_general_options"){
			global $wpdb;
			$sql=$wpdb->prepare("UPDATE ".$wpdb->prefix ."g_maps SET name=%s,zoom=%s,border_radius=%s,center_lat=%s,center_lng=%s,pan_controller=%s,zoom_controller=%s,type_controller=%s,scale_controller=%s,street_view_controller=%s,overview_map_controller=%s,width=%s,height=%s,align=%s,min_zoom=%s,max_zoom=%s WHERE id=%s",
						$_POST['map_name'],$_POST['map_zoom'],$_POST['map_border_radius'],$_POST['map_center_lat'],$_POST['map_center_lng'],$_POST['map_controller_pan'],$_POST['map_controller_zoom'],$_POST['map_controller_type'],$_POST['map_controller_scale'],$_POST['map_controller_street_view'],$_POST['map_controller_overview'],$_POST['map_width'],$_POST['map_height'],$_POST['map_align'],$_POST['min_zoom'],$_POST['max_zoom'],$_POST['id']);
			if($wpdb->query($sql)){
				$map_params=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix ."g_maps WHERE id=%s",$_POST['id']));
				foreach($map_params as $param){
					echo json_encode(array(
											"success"=>1,
											"hue"=>$param->styling_hue,
											"saturation"=>$param->styling_saturation,
											"lightness"=>$param->styling_lightness,
											"gamma"=>$param->styling_gamma,
											"zoom"=>$param->zoom,
											"type"=>$param->type,
											"bike"=>$param->bike_layer,
											"traffic"=>$param->traffic_layer,
											"transit"=>$param->transit_layer
										)
									);
					die();
				}
			}															
		}
	}
	
	if(isset($_POST['task'])){
		if($_POST['task'] == "change_name"){
			global $wpdb;
			$name =$_POST['name'];
			$id = $_POST['id'];
			if($wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."g_maps SET name='$name' WHERE id=%s", $id)))
			{
				echo json_encode(array("success"=>1));
				die();
			}
		}
	}
	

	if(isset($_POST['task'])){
		if($_POST['task'] == "front_end_submit"){
			global $wpdb;
			$name = $_POST['name'];
			$type = $_POST['type'];
			$zoom = $_POST['zoom'];
			$width = $_POST['width'];
			$height = $_POST['height'];
			$id = $_POST['id'];
			$align = $_POST['align'];
			$sql = $wpdb->prepare("UPDATE ".$wpdb->prefix ."g_maps SET name=%s, type=%d, zoom = %d, width=%d, height=%d, align=%d WHERE id=%d", $name, $type,$zoom,$width,$height,$align,$id );
			$update = $wpdb->query($sql);
			if($update)
			{
				echo json_encode(array("success"=>1));
				die();
			}
		}
	}
		
	if(isset($_POST['task'])){
		if($_POST['task'] == "getxml")
		{
			$id=$_POST['map_id'];
			$response ="<?xml version='1.0' encoding='UTF-8'?>
					<maps>";
			global $wpdb;
			
			
			$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."g_maps WHERE id=%s", $id);
			$getMap = $wpdb->get_results($sql);
			
			if(isset($getMap))
			{
				foreach($getMap as $mapinfo)
				{
					$response = $response."<map name='". parseToXML($mapinfo->name) ."' info_type='".$mapinfo->info_type ."' pan_controller='".$mapinfo->pan_controller ."' zoom_controller='".$mapinfo->zoom_controller ."' type_controller='".$mapinfo->type_controller ."' scale_controller='".$mapinfo->scale_controller ."' street_view_controller='".$mapinfo->street_view_controller ."' overview_map_controller='".$mapinfo->overview_map_controller ."' type='". $mapinfo->type ."' zoom='". $mapinfo->zoom ."' center_lat='". $mapinfo->center_lat."' center_lng='". $mapinfo->center_lng."'  />";
				}
				$sql =$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."g_markers WHERE map=%d", $id);
				$getMarkers = $wpdb->get_results($sql);

					if(isset($getMarkers))
					{

						foreach($getMarkers as $marker)
						{
							$response = $response."<marker id='". $marker->id ."' size='".$marker->size."' name='".parseToXML($marker->title)."' animation='".$marker->animation."' lat='".$marker->lat."' lng='".$marker->lng."' description='".parseToXML($marker->description) ."' img='".parseToXML($marker->img) ."' />";
						}	
					}
				$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."g_polygones WHERE map=%d",$id);
				$getPolygone = $wpdb->get_results($sql);
				if(isset($getPolygone))
				{
					foreach($getPolygone as $polygone)
					{
						$response = $response."<polygone id='". $polygone->id ."' name='".parseToXML($polygone->name) ."'  url='".parseToXML($polygone->url) ."' line_width='" . $polygone->line_width ."'  line_opacity='".$polygone->line_opacity ."' line_color='".$polygone->line_color ."' fill_opacity='".$polygone->fill_opacity ."' fill_color='".$polygone->fill_color ."' hover_line_color='".$polygone->hover_line_color ."' hover_line_opacity='".$polygone->hover_line_opacity ."' hover_fill_color='".$polygone->hover_fill_color ."' hover_fill_opacity='".$polygone->hover_fill_opacity ."' >";
						preg_match_all('/\(([^\)]*)\)/', $polygone->data, $matches);
						foreach($matches[1] as $latlng)
						{
								preg_match_all("/[^,]+[\d+][.?][\d+]*/",$latlng,$results);
								foreach($results as $latlng)
								{
									$response = $response."<latlng lat='".$latlng[0]."' lng='".$latlng[1]."' />";
								}
						}
						$response = $response."</polygone>";
					}
				}
				$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."g_polylines WHERE map=%s",$id);
				$getPolyline = $wpdb->get_results($sql);
				if(isset($getPolyline))
				{
					foreach($getPolyline as $polyline)
					{
						$response = $response."<polyline id='".$polyline->id ."' name='".parseToXML($polyline->name) ."' hover_line_color='".$polyline->hover_line_color ."' hover_line_opacity='".$polyline->hover_line_opacity ."' line_opacity='".$polyline->line_opacity."' line_color='".$polyline->line_color."' line_width='".$polyline->line_width."' >";
						preg_match_all('/\(([^\)]*)\)/', $polyline->data, $matches);
						foreach($matches[1] as $latlng)
						{
							preg_match_all("/[^,]+[\d+][.?][\d+]*/",$latlng,$results);
							foreach($results as $latlng){
								$response = $response."<latlng lat='".$latlng[0]."' lng='".$latlng[1]."' />";
							}
						}
						$response = $response."</polyline>";
					}
				}
				$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."g_circles WHERE map=%d", $id);
				$getCircle = $wpdb->get_results($sql);
				if($getCircle)
				{
					foreach($getCircle as $circle)
					{
						$response = $response."<circle id='".$circle->id."' name='".parseToXML($circle->name) ."' center_lat='".$circle->center_lat."' center_lng='".$circle->center_lng."' radius='".$circle->radius."' hover_fill_color='".$circle->hover_fill_color ."' hover_fill_opacity='".$circle->hover_fill_opacity ."' hover_line_color='".$circle->hover_line_color ."' hover_line_opacity='".$circle->hover_line_opacity ."' line_width='".$circle->line_width."' line_color='".$circle->line_color."' line_opacity='".$circle->line_opacity."' fill_color='".$circle->fill_color."' fill_opacity='".$circle->fill_opacity."' show_marker='".$circle->show_marker."' />";
					}
				}
				$response = $response."</maps>";
				echo json_encode(array("success"=>$response));
				die();
			}
		}
	}
}
					


class Huge_it_google_mapss_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'Huge_it_google_mapss_Widget', 
			'Huge IT google maps', 
			array( 'description' => __( 'Huge IT google maps', 'huge_it_google_maps' ), ) 
		);
	}
	
	public function widget( $args, $instance ) {
		extract($args);

		if (isset($instance['g_map_id'])) {
			$g_map_id = $instance['g_map_id'];

			$title = apply_filters( 'widget_title', $instance['title'] );

			echo $before_widget;
			if ( ! empty( $title ) )
				echo $before_title . $title . $after_title;

			echo do_shortcode("[huge_it_maps id='{$g_map_id}']");
			echo $after_widget;
		}
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['g_map_id'] = strip_tags( $new_instance['g_map_id'] );
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}
	
	public function form( $instance ) {
		$selected_map = 0;
		$title = "";
		$maps = false;

		if (isset($instance['g_map_id'])) {
			$selected_portfolio = $instance['g_map_id'];
		}

		if (isset($instance['title'])) {
			$title = $instance['title'];
		}
		
		?>
		<p>
			
				<p>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
					<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				</p>
				<label for="<?php echo $this->get_field_id('g_map_id'); ?>"><?php _e('Select map:', 'huge_it_google_maps'); ?></label> 
				<select id="<?php echo $this->get_field_id('g_map_id'); ?>" name="<?php echo $this->get_field_name('g_map_id'); ?>">
				
				<?php
				 global $wpdb;
				$query="SELECT * FROM ".$wpdb->prefix."g_maps";
				$rowwidget=$wpdb->get_results($query);
				foreach($rowwidget as $rowwidgetecho){
				
				selected
				?>
					<option <?php if($rowwidgetecho->id == $instance['g_map_id']){ echo 'selected'; } ?> value="<?php echo $rowwidgetecho->id; ?>"><?php echo $rowwidgetecho->name; ?></option>

					<?php } ?>
				</select>

		</p>
		<?php 
	}
	
}


add_action('widgets_init', 'register_Huge_it_google_mapss_Widget');  

function register_Huge_it_google_mapss_Widget() {  
    register_widget('Huge_it_google_mapss_Widget'); 
}

function huge_it_google_mapss_activate(){
	
	wp_mkdir_p('huge-it-google-map-custom-icons');
	
	
	global $wpdb;
	$sql_huge_it_google_maps_maps = "
	CREATE TABLE IF NOT EXISTS `". $wpdb->prefix ."g_maps` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(50) NOT NULL,
	`type` varchar(50) NOT NULL,
	`zoom` int(5) NOT NULL,
	`min_zoom` varchar(55) DEFAULT 0 NOT NULL,
	`max_zoom` varchar(55) DEFAULT 22 NOT NULL,
	`border_radius` int(5) NOT NULL,
	`center_lat` varchar(255) DEFAULT 0 NOT NULL,
	`pan_controller` varchar(5) DEFAULT 'true' NOT NULL,
	`zoom_controller` varchar(5) DEFAULT 'true' NOT NULL,
	`type_controller` varchar(5) DEFAULT 'true' NOT NULL,
	`scale_controller` varchar(5) DEFAULT 'true' NOT NULL,
	`street_view_controller` varchar(5) DEFAULT 'true' NOT NULL,
	`overview_map_controller` varchar(5) DEFAULT 'true' NOT NULL,
	`center_lng` varchar(255) DEFAULT 0 NOT NULL,
	`width` varchar(5) DEFAULT 100 NOT NULL,
	`height` varchar(5) DEFAULT 450 NOT NULL,
	`align` varchar(11) DEFAULT 'left' NOT NULL,
	`info_type` varchar(9) DEFAULT 'click' NOT NULL,
	`traffic_layer` varchar(55) DEFAULT 'false' NOT NULL,
	`bike_layer` varchar(55) DEFAULT 'false' NOT NULL,
	`transit_layer` varchar(55) DEFAULT 'false' NOT NULL,
	`styling_hue` text NOT NULL,
	`styling_lightness` varchar(55) DEFAULT '0' NOT NULL,
	`styling_gamma` varchar(55) DEFAULT 1 NOT NULL,
	`styling_saturation` varchar(55) DEFAULT '0' NOT NULL,
	PRIMARY KEY (`id`)
	)   DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ";
	
	
	
	$sql_huge_it_google_maps_markers = "
	CREATE TABLE IF NOT EXISTS `". $wpdb->prefix ."g_markers` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`map` int(11) NOT NULL,
	`title` varchar(50) NOT NULL,
	`lat` varchar(255) DEFAULT 0 NOT NULL,
	`lng` varchar(255) DEFAULT 0 NOT NULL,
	`animation` varchar(255) DEFAULT 'NONE' NOT NULL,
	`description` text NOT NULL ,
	`img` varchar(255) NOT NULL,
	`size` varchar(11) NOT NULL,
	PRIMARY KEY (`id`)
	)   DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ";
	
	
	$sql_huge_it_google_maps_polygones = "
	CREATE TABLE IF NOT EXISTS `". $wpdb->prefix ."g_polygones` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`map` int(11) NOT NULL,
	`name` varchar(50) NOT NULL,
	`data` text NOT NULL,
	`line_opacity` varchar(5) NOT NULL,
	`line_color` varchar(9) NOT NULL,
	`fill_opacity` varchar(5) NOT NULL,
	`fill_color` varchar(9) NOT NULL,
	`url` text NOT NULL,
	`hover_line_opacity` varchar(5) NOT NULL,
	`hover_line_color` varchar(9) NOT NULL,
	`hover_fill_opacity` varchar(5) NOT NULL,
	`hover_fill_color` varchar(9) NOT NULL,
	`line_width` varchar(9) NOT NULL,
	PRIMARY KEY (`id`)
	)   DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ";
	
	
	
	$sql_huge_it_google_maps_polylines = "
	CREATE TABLE IF NOT EXISTS `". $wpdb->prefix ."g_polylines` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`map` int(11) NOT NULL,
	`name` varchar(50) NOT NULL,
	`data` text NOT NULL,
	`line_opacity` varchar(5) NOT NULL,
	`line_color` varchar(7) NOT NULL,
	`line_width` varchar(5) NOT NULL,
	`hover_line_color` varchar(9) NOT NULL,
	`hover_line_opacity` varchar(9) NOT NULL,
	PRIMARY KEY (`id`)
	)   DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ";
	
	
	$sql_huge_it_google_maps_circles = "
	CREATE TABLE IF NOT EXISTS `". $wpdb->prefix ."g_circles` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`map` int(11) NOT NULL,
	`name` varchar(50) NOT NULL,
	`center_lat` varchar(255) NOT NULL,
	`center_lng` varchar(255) NOT NULL,
	`radius` varchar(255) NOT NULL,
	`line_width` varchar(5) NOT NULL,
	`line_opacity` varchar(5) NOT NULL,
	`line_color` varchar(7) NOT NULL,
	`fill_color` varchar(7) NOT NULL,
	`fill_opacity` varchar(7) NOT NULL,
	`hover_line_opacity` varchar(5) NOT NULL,
	`hover_line_color` varchar(7) NOT NULL,
	`hover_fill_color` varchar(7) NOT NULL,
	`hover_fill_opacity` varchar(7) NOT NULL,
	`show_marker` int(7) NOT NULL,
	PRIMARY KEY (`id`)
	)   DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ";

	
	$wpdb->query($sql_huge_it_google_maps_maps);

	$wpdb->query($sql_huge_it_google_maps_markers);

	$wpdb->query($sql_huge_it_google_maps_polygones);

	$wpdb->query($sql_huge_it_google_maps_polylines);

	$wpdb->query($sql_huge_it_google_maps_circles);
	
	
	$sqlInsertMap1 = "INSERT INTO ".$wpdb->prefix."g_maps (name ,info_type , type, zoom, center_lat, center_lng, width, height, align, border_radius) VALUES ('My First Map' ,'click' , 'ROADMAP', '7', '40.7127837', '-74.00594130000002', '100', '300', 'center', '0')";
	
	
	$sqlInsertMarker1 = "INSERT INTO ".$wpdb->prefix."g_markers (map,title,lat,lng,animation,description) VALUES ('1', 'New York', '40.7127837', '-74.00594130000002', 'BOUNCE', 'New York City')";
	
	$sqlInsertMarker2 = "INSERT INTO ".$wpdb->prefix."g_markers (map,title,lat,lng,animation,description) VALUES ('1', 'Delaver', '39.189690821096804', '-75.7562255859375', 'DROP', 'Delaver')";
	
	$sqlInsertPolygone = "INSERT INTO ".$wpdb->prefix."g_polygones (map ,url , name, data, hover_line_opacity ,hover_line_color,hover_fill_opacity ,hover_fill_color ,line_opacity ,line_color , fill_opacity, fill_color, line_width) VALUES 
	('1' ,'http://www.huge-it.com' , 'My First Polygon','(40.538851525354666, -74.3060302734375),(40.16208338164619, -73.9764404296875),(39.40224434029277, -74.3499755859375),(38.950865400920016, -74.8883056640625),(39.13858199058352, -75.0091552734375),(39.46164364205549, -75.5035400390625),(39.774769485295465, -75.4815673828125),(39.86758762451019, -75.0201416015625)',
																'0.8' ,'FF80B7' ,'0.5' ,'75FF7E'  , '0.9', 'E2574C', '0.5', 'F6C37A', '5')";
	
	$sqlInsertPolyline = "INSERT INTO ".$wpdb->prefix."g_polylines (map, name ,hover_line_opacity ,hover_line_color ,line_opacity, line_color, line_width, data) VALUES ('1', 'My First Polyline' ,'0.5' ,'11A000' , '0.9', '18A326', '4' , '(42.24071874922666, -71.81488037109375),(42.1532233123986, -71.95770263671875),(42.13082130188811, -72.06207275390625),(42.14507804381756, -72.125244140625),(42.18579390537848, -72.2186279296875),(42.16340342422401, -72.2845458984375),(42.1837587346522, -72.3175048828125),(42.1552594657786, -72.36968994140625),(42.169510705216595, -72.4822998046875),(42.157295553651636, -72.630615234375),(42.13896840458089, -72.72674560546875),(42.165439250064324, -72.850341796875),(42.173581898327754, -72.92312622070312),(42.2366518803206, -73.00277709960938),(42.24478535602799, -73.10714721679688),(42.30169032824452, -73.17306518554688),(42.3016903282445, -73.34884643554688),(42.37883631647602, -73.45596313476562),(42.41940144722477, -73.54385375976562),(42.47209690919285, -73.63174438476562),(42.482225570025925, -73.67294311523438),(42.50652766705062, -73.78005981445312),(42.34027515373573, -73.85421752929688),(42.173581898327754, -73.93112182617188),(41.9921602333763, -73.99703979492188),(41.91249742196845, -74.04098510742188),(41.83682786072714, -74.17831420898438),(41.79179268262892, -74.23599243164062),(41.75492216766298, -74.36782836914062),(41.70777900286713, -74.38430786132812),(41.582579601430346, -74.48318481445312),(41.36238012945534, -74.70291137695312)')";
	
	
	$sqlInsertCircle = "INSERT INTO ".$wpdb->prefix."g_circles (map, name ,hover_fill_color ,hover_fill_opacity ,hover_line_color ,hover_line_opacity , center_lat, center_lng, radius, line_width, line_opacity, line_color, fill_color, fill_opacity, show_marker) VALUES 
																('1', 'My First Circle' ,'96FFA1' ,'0.3' ,'FF5C5C' ,'0.6' , '40.805493843894155', '-76.3165283203125', '50000', '5', '0.8', 'FF2B39', '4FFF72', '0.4', '0')";
	
	if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "g_maps")) {
		$wpdb->query($sqlInsertMap1);
    }
	if(!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "g_markers"))
	{
		$wpdb->query($sqlInsertMarker1);
		$wpdb->query($sqlInsertMarker2);
	}
	if(!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "g_polygones"))
	{
		$wpdb->query($sqlInsertPolygone);
	}
	if(!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "g_polylines"))
	{
		$wpdb->query($sqlInsertPolyline);
	}
	if(!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "g_circles"))
	{
		$wpdb->query($sqlInsertCircle);
	}
	//ALTER 1********************
	$sql=$wpdb->get_results("SHOW columns FROM ".$wpdb->prefix ."g_maps");
	$update=1;
	foreach($sql as $a){
		if($a->Field=="styling_hue"){
			$update=0;
		}
	}
	if($update==1){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."g_maps ADD styling_hue TEXT NOT NULL AFTER info_type");
	}
	//********************
	
	//ALTER 2********************
	$update=1;
	foreach($sql as $a){
		if($a->Field=="styling_lightness"){
			$update=0;
		}
	}
	if($update==1){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."g_maps ADD styling_lightness varchar(55) DEFAULT 0 NOT NULL AFTER info_type");
	}
	//********************

	//ALTER 3********************
	$update=1;
	foreach($sql as $a){
		if($a->Field=="styling_saturation"){
			$update=0;
		}
	}
	if($update==1){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."g_maps ADD styling_saturation varchar(55) DEFAULT 0 NOT NULL AFTER info_type");
	}
	//********************
	
	//ALTER 4********************
	$update=1;
	foreach($sql as $a){
		if($a->Field=="styling_gamma"){
			$update=0;
		}
	}
	if($update==1){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."g_maps ADD styling_gamma varchar(55) DEFAULT 1 NOT NULL  AFTER info_type");
	}
	//********************

	//ALTER 5********************
	$update=1;
	foreach($sql as $a){
		if($a->Field=="transit_layer"){
			$update=0;
		}
	}
	if($update==1){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix ."g_maps ADD transit_layer varchar(55) DEFAULT 'false' NOT NULL  AFTER info_type");
	}
	//********************
	
	//ALTER 6********************
	$update=1;
	foreach($sql as $a){
		if($a->Field=="bike_layer"){
			$update=0;
		}
	}
	if($update==1){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."g_maps ADD bike_layer varchar(55) DEFAULT 'false' NOT NULL  AFTER info_type");
	}
	//********************
	
	//ALTER 7********************
	$update=1;
	foreach($sql as $a){
		if($a->Field=="traffic_layer"){
			$update=0;
		}
	}
	if($update==1){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."g_maps ADD traffic_layer varchar(55) DEFAULT 'false' NOT NULL  AFTER info_type");
	}
	//********************
	
	//ALTER 8********************
	$update=1;
	foreach($sql as $a){
		if($a->Field=="min_zoom"){
			$update=0;
		}
	}
	if($update==1){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."g_maps ADD min_zoom varchar(55) DEFAULT 0 NOT NULL  AFTER info_type");
	}
	//********************

	//ALTER 9********************
	$update=1;
	foreach($sql as $a){
		if($a->Field=="max_zoom"){
			$update=0;
		}
	}
	if($update==1){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."g_maps ADD max_zoom varchar(55) DEFAULT 22 NOT NULL  AFTER info_type");
	}
	//********************

}


register_activation_hook(__FILE__, 'huge_it_google_mapss_activate');

?>