<?php
	function markers_js($id)
	{
		global $wpdb;
		$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."g_maps WHERE id=%s",$id);
		$map = $wpdb->get_results($sql);
		foreach($map as $map)
		{
			?>
			<script>
			
				var data;
				var marker = [];
				var infowindow =[];
				var newmarker;
				var geocoder;
				var markeredit;
				
				jQuery(document).ready(function(){
					loadMarkerMap("<?php echo $map->id; ?>","#<?php echo $map->styling_hue; ?>","<?php echo $map->styling_saturation; ?>","<?php echo $map->styling_lightness; ?>","<?php echo $map->styling_gamma; ?>","<?php echo $map->zoom; ?>","<?php echo $map->type; ?>","<?php echo $map->bike_layer; ?>","<?php echo $map->traffic_layer; ?>","<?php echo $map->transit_layer; ?>");
					
				})
				function loadMarkerMap(id,hue,saturation,lightness,gamma,zoom,type,bike,traffic,transit){
						data = {
							action:'g_map_options',
							map_id:id,
							task:"getxml",
						}
						jQuery.post("<?php echo admin_url( 'admin-ajax.php' ); ?>", data, function(response){
							if(response.success)
							{
								var xml = jQuery.parseXML(response.success);						
								var maps = xml.documentElement.getElementsByTagName("map");
								for(var i = 0; i < maps.length; i++)
								{
									var mapcenter = new google.maps.LatLng(
										parseFloat(maps[i].getAttribute("center_lat")),
										parseFloat(maps[i].getAttribute("center_lng")));
									var mapOptions = {
										zoom:parseInt(zoom),
										center: mapcenter,
									}
									map = new google.maps.Map(document.getElementById('g_map_marker'), mapOptions);
									map_marker_edit = new google.maps.Map(document.getElementById('g_map_marker_edit'), mapOptions);
									
									var input_marker = document.getElementById("marker_location");
									var autocomplete_marker = new google.maps.places.Autocomplete(input_marker);
									google.maps.event.addListener(autocomplete_marker, 'place_changed', function(){
										
										var addr = jQuery("#marker_location").val();
										geocoder = new google.maps.Geocoder();
										geocoder.geocode({ 'address': addr}, function (results, status) {
											if(newmarker)
											{
												newmarker.setPosition(results[0].geometry.location)
											}
											else
											{
												placeMarker(results[0].geometry.location)
											}
											map.setCenter(results[0].geometry.location);
											updateMarkerInputs(results[0].geometry.location);
										})
									})
									
									jQuery("#marker_add_button").on("click",function(){
										jQuery("#marker_location").val("");
										jQuery("#marker_location_lat").val("");
										jQuery("#marker_location_lng").val("");
										jQuery("#marker_title").val("");
										jQuery("#marker_description").val("");
										google.maps.event.trigger(map, 'resize');
										map.setCenter(mapcenter);
										if(newmarker)
										{
											newmarker.setMap(null);
										}
									})
									
							
									google.maps.event.addListener(map, 'rightclick', function(event){
										placeMarker(event.latLng);
										updateMarkerInputs(event.latLng);
									});
									
									
									jQuery("#marker_animation").on("change",function(){
										var animation =jQuery(this).val();
										if(newmarker)
										{
											if(animation == "BOUNCE")
											{
												newmarker.setAnimation(google.maps.Animation.BOUNCE)
											}
											if(animation == "DROP")
											{
												newmarker.setAnimation(google.maps.Animation.DROP)
											}
											if(animation == "NONE")
											{
												newmarker.setAnimation(null);
											}
										}
									})
									

									jQuery("#marker_location_lat, #marker_location_lng").on("change",function(){
										var lat = parseFloat(jQuery("#marker_location_lat").val());
										var lng = parseFloat(jQuery("#marker_location_lng").val());
											var position = new google.maps.LatLng(lat,lng);
											placeMarker(position);
										
									})
									var markers = xml.documentElement.getElementsByTagName("marker");

									jQuery(".edit_marker_list_delete a").on("click",function(){
										
										var parent = jQuery(this).parent();
										var idelement = parent.find(".marker_edit_id");
										var markerid = idelement.val();
										jQuery("#g_maps > div").not("#g_map_polygon").addClass("hide");
										jQuery("#g_map_marker_edit").removeClass("hide");
										jQuery("#markers_edit_exist_section").hide(200).addClass("tab_options_hidden_section");
										jQuery(this).parentsUntil(".editing_section").find(".update_list_item").show(200).addClass("tab_options_active_section");
										jQuery("#marker_add_button").hide(200).addClass("tab_options_hidden_section");
										jQuery("#marker_get_id").val(markerid);
										var markers= xml.documentElement.getElementsByTagName("marker");
										for(var y = 0; y < markers.length; y++)
										{
											var id = markers[y].getAttribute("id");
											if(markerid == id)
											{
												var name = markers[y].getAttribute("name");
												var address = markers[y].getAttribute("address");
												var anim = markers[y].getAttribute("animation");
												var description = markers[y].getAttribute("description");												
												
												var lat = markers[y].getAttribute("lat");
												var lng  = markers[y].getAttribute("lng");
												var point = new google.maps.LatLng(
													parseFloat(markers[y].getAttribute("lat")),
													parseFloat(markers[y].getAttribute("lng")));
												
												map_marker_edit.setCenter(point);
												
												google.maps.event.trigger(map_marker_edit, 'resize');
												map_marker_edit.setCenter(point); 
												jQuery("#marker_edit_location_lat").val(lat);
												jQuery("#marker_edit_location_lng").val(lng);
												jQuery("#marker_edit_animation").val(anim);
												jQuery("#marker_edit_title").val(name);
												jQuery("#marker_edit_description").val(description);
												if(markeredit)
												{
													markeredit.setMap(null);
												}
												if(anim == 'DROP'){
													markeredit = new google.maps.Marker({
													map: map_marker_edit,
													position: point,
													title: name,
													content: description,
													animation: google.maps.Animation.DROP,
													draggable:true
													});
												}
												if(anim == 'BOUNCE'){
												markeredit = new google.maps.Marker({
													map: map_marker_edit,
													position: point,
													title: name,
													content: description,
													animation: google.maps.Animation.BOUNCE,
													draggable:true
													});
												}
												if(anim == 'NONE'){
													markeredit = new google.maps.Marker({
														map: map_marker_edit,
														position: point,
														content: description,
														title: name,
														draggable:true
													});
												}
												google.maps.event.addListener(map_marker_edit, 'rightclick', function(event){
													if(markeredit)
													{
														markeredit.setPosition(event.latLng);
													}
													updateMarkerEditInputs(event.latLng);
												});
												
												google.maps.event.addListener(markeredit, 'drag', function(event){
													
													updateMarkerEditInputs(event.latLng);
												});
												
												var input_edit_marker = document.getElementById("marker_edit_location");
												var autocomplete_edit_marker = new google.maps.places.Autocomplete(input_edit_marker);
												google.maps.event.addListener(autocomplete_edit_marker, 'place_changed', function(){
													
													var addr = jQuery("#marker_edit_location").val();
													geocoder = new google.maps.Geocoder();
													geocoder.geocode({ 'address': addr}, function (results, status) {
														if(markeredit)
														{
															markeredit.setPosition(results[0].geometry.location)
														}
														map_marker_edit.setCenter(results[0].geometry.location);
														updateMarkerEditInputs(results[0].geometry.location);
													})
												})
												
												updateMarkerEditInputs(markeredit.getPosition());
											}
										}
										google.maps.event.trigger(map_marker_edit, 'resize');
										jQuery("#marker_edit_animation").on("change",function(){
											var animation =jQuery(this).val();
											if(markeredit)
											{
												if(animation == "BOUNCE")
												{
													markeredit.setAnimation(google.maps.Animation.BOUNCE)
												}
												if(animation == "DROP")
												{
													markeredit.setAnimation(google.maps.Animation.DROP)
												}
												if(animation == "NONE")
												{
													markeredit.setAnimation(null);
												}
											}
										})
										return false;
									})
								}
								
							}
						},"json")
					}
				function placeMarker(location)
				{ 
					if(newmarker)
					{
						newmarker.setMap(map);
						newmarker.setPosition(location);
					}
					else
					{
						newmarker = new google.maps.Marker({
							map:map,
							position:location,
							title: "new point",
							draggable:true,
						})
					}
					google.maps.event.addListener(newmarker, 'drag', function(event) {
						updateMarkerInputs(event.latLng);
					});
				}
				function updateMarkerInputs(location)
				{
					jQuery("#marker_location_lat").val(location.lat());
					jQuery("#marker_location_lng").val(location.lng());
					geocoder = new google.maps.Geocoder();
					geocoder.geocode({'latLng':location},function(results, status){
						if (status == google.maps.GeocoderStatus.OK) {
							address = results[0].formatted_address;
							jQuery("#marker_location").val(address);
						}
					})
				}
				
				function updateMarkerEditInputs(location)
				{
					jQuery("#marker_edit_location_lat").val(location.lat());
					jQuery("#marker_edit_location_lng").val(location.lng());
					geocoder = new google.maps.Geocoder();
					geocoder.geocode({'latLng':location},function(results, status){
						if (status == google.maps.GeocoderStatus.OK) {
							address = results[0].formatted_address;
							jQuery("#marker_edit_location").val(address);
						}
					})
				}
			</script>
			<?php ;
		}
	}
?>