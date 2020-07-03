<?php
function polylines_js($id)
{
	global $wpdb;
	$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."g_maps WHERE id=%s",$id);
	$map = $wpdb->get_results($sql);
	foreach($map as $map)
	{
		?>
		<script>
			var data;
			//var geocoder;
			var polyline=[];
			var polylinemarker= [];
			var i  = 0;
			var newpolyline;
			var polylineedit;
			var newpolylinecoords = [];
			var polylineeditmarker= [];
			var polylineeditcoords = [];
			jQuery(document).ready(function(){
				loadPolylineMap("<?php echo $map->id; ?>","#<?php echo $map->styling_hue; ?>","<?php echo $map->styling_saturation; ?>","<?php echo $map->styling_lightness; ?>","<?php echo $map->styling_gamma; ?>","<?php echo $map->zoom; ?>","<?php echo $map->type; ?>","<?php echo $map->bike_layer; ?>","<?php echo $map->traffic_layer; ?>","<?php echo $map->transit_layer; ?>");
				
			})
			function loadPolylineMap(id,hue,saturation,lightness,gamma,zoom,type,bike,traffic,transit){
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
							mappolyline = new google.maps.Map(document.getElementById('g_map_polyline'), mapOptions);
							map_polyline_edit = new google.maps.Map(document.getElementById('g_map_polyline_edit'),mapOptions);
							
							jQuery("#polyline_add_button").on("click",function(){
								google.maps.event.trigger(mappolyline, 'resize');
								mappolyline.setCenter(mapcenter);
								if(newpolyline){
									newpolyline.setMap(null);
									
									newpolylinecoords = [];
									for(var i = 0; i < polylinemarker.length ; i++)
									{
										polylinemarker[i].setMap(null);
									}
									polylinemarker = [];
								}
							})
							google.maps.event.addListener(mappolyline, 'rightclick', function(event){
								placePolyline(event.latLng);
								updatePolylineInputs(event.latLng);
							});
							
							jQuery(".polyline_options_input").on("change",function(){
								var polyline_line_color = "#"+jQuery('#polyline_line_color').val();
								var polyline_line_opacity = jQuery('#polyline_line_opacity').val();
								var polyline_line_width = jQuery('#polyline_line_width').val();
								if(newpolyline)
								{
									newpolyline.setOptions({ 
										strokeColor:polyline_line_color,
										strokeWeight:polyline_line_width,
										strokeOpacity:polyline_line_opacity,
									}); 
								}
							})
							
							
							jQuery(".edit_polyline_list_delete a").on("click",function(){
								if(polylineedit)
								{
									polylineedit.setMap(null);
									for(var i = 0; i < polylineeditmarker.length ; i++)
									{
										polylineeditmarker[i].setMap(null);
									}
									polylineeditmarker = [];
									polylineeditcoords = [];
								}
								var parent = jQuery(this).parent();
								var idelement = parent.find(".polyline_edit_id");
								var polylineid = idelement.val();
								jQuery("#g_maps > div").addClass("hide");
								jQuery("#g_map_polyline_edit").removeClass("hide");
								jQuery("#polyline_edit_exist_section").hide(200).addClass("tab_options_hidden_section");
								jQuery(this).parent().parent().parent().parent().parent().find(".update_list_item").show(200).addClass("tab_options_active_section");
								jQuery("#polyline_add_button").hide(200).addClass("tab_options_hidden_section");
								google.maps.event.trigger(map_polyline_edit, 'resize');
								
								jQuery("#polyline_get_id").val(polylineid);
								var polylines = xml.documentElement.getElementsByTagName("polyline");
								for(var e = 0; e < polylines.length; e++)
								{
									var id = polylines[e].getAttribute("id");
									if(polylineid == id)
									{
										var name=polylines[e].getAttribute("name");
										var line_opacity=polylines[e].getAttribute("line_opacity");
										var line_color=polylines[e].getAttribute("line_color");
										var line_width = polylines[e].getAttribute("line_width");
										var latlngs = polylines[e].getElementsByTagName("latlng");
										jQuery("#polyline_edit_name").val(name);
										jQuery("#polyline_edit_line_opacity").simpleSlider("setValue", line_opacity);
										jQuery("#polyline_edit_line_color").val(line_color);
										jQuery("#polyline_edit_line_width").simpleSlider("setValue", line_width);
										for(var j = 0; j < latlngs.length; j++)
										{
											var lat =latlngs[j].getAttribute("lat");
											var lng =latlngs[j].getAttribute("lng");
											var polylineeditpoint = new google.maps.LatLng(parseFloat(latlngs[j].getAttribute("lat")),
												parseFloat(latlngs[j].getAttribute("lng")));
												if(j==0){
													map_polyline_edit.setCenter(polylineeditpoint);
												}
											polylineeditmarker[j] = new google.maps.Marker({
												position:polylineeditpoint,
												map:map_polyline_edit,
												title:"#"+j,
												draggable:true,
											})
											polylineeditcoords.push(polylineeditpoint);
											
											google.maps.event.addListener(polylineeditmarker[j], 'click', function(event){
												var title = this.getTitle();
												var index = title.replace("#","");
												polylineeditcoords.splice(index,1);
												polylineeditmarker.splice(index,1);
												polylineedit.setPath(polylineeditcoords);
												this.setMap(null);
												updatePolylineEditInputs();
												for(var z=0; z < polylineeditcoords.length; z++)
												{
													polylineeditmarker[z].setTitle("#"+z);
												}
											});
											google.maps.event.addListener(polylineeditmarker[j],"drag",function(event){
												var title = this.getTitle();
												var index = title.replace("#","")
												var position = this.getPosition();
												polylineeditcoords[index] = position;
												polylineedit.setPath(polylineeditcoords);
												updatePolylineEditInputs();
											})
											
										}
										polylineedit = new google.maps.Polyline({
											path : polylineeditcoords,
											map: map_polyline_edit,
											strokeOpacity: line_opacity,
											strokeColor:"#"+line_color,
											draggable:false,
										});
										jQuery(".polyline_edit_options_input").on("change",function(){
											var line_opacity = jQuery("#polyline_edit_line_opacity").val();
											var line_color = jQuery("#polyline_edit_line_color").val();
											var line_width = jQuery("#polyline_edit_line_width").val();
											polylineedit.setOptions({ 
												strokeColor:"#"+line_color,
												strokeWeight:line_width,
												strokeOpacity:line_opacity,
											}); 
										})
										google.maps.event.addListener(map_polyline_edit, "rightclick",function(event){
											//alert(event.latLng);
											var edit_array_index = polylineeditmarker.length;
											polylineeditmarker[edit_array_index] = new google.maps.Marker({
												map:map_polyline_edit,
												position: event.latLng,
												title:"#"+edit_array_index,
												draggable:true,
											})
											polylineeditcoords.push(event.latLng);
											polylineedit.setPath(polylineeditcoords);
											google.maps.event.addListener(polylineeditmarker[edit_array_index], 'click', function(event){
												var title = this.getTitle();
												var index = title.replace("#","");
												polylineeditcoords.splice(index,1);
												polylineeditmarker.splice(index,1);
												polylineedit.setPath(polylineeditcoords);
												this.setMap(null);
												updatePolylineEditInputs();
												for(var z=0; z < polylineeditcoords.length; z++)
												{
													polylineeditmarker[z].setTitle("#"+z);
												}
											});
											google.maps.event.addListener(polylineeditmarker[edit_array_index],"drag",function(event){
												var title = this.getTitle();
												var index = title.replace("#","")
												var position = this.getPosition();
												polylineeditcoords[index] = position;
												polylineedit.setPath(polylineeditcoords);
												updatePolylineEditInputs();
											})
											updatePolylineEditInputs();
										})
										
										updatePolylineEditInputs();
										
									}
								}
								return false;
							})
						}
					}
				},"json")
			}
			function updatePolylineInputs(location)
			{
				var temp_array = "";
				newpolylinecoords.forEach(function(latLng, index) { 
				//temp_array = temp_array + " ["+ index +"] => "+ latLng + ", ";
				temp_array = temp_array + latLng + ",";
				}); 
				jQuery("#polyline_coords").val(temp_array);
			}
			function updatePolylineEditInputs()
			{
				var temp_array = "";
				polylineeditcoords.forEach(function(latLng, index) { 
				//temp_array = temp_array + " ["+ index +"] => "+ latLng + ", ";
				temp_array = temp_array + latLng + ",";
				}); 
				jQuery("#polyline_edit_coords").val(temp_array);
			}
			function placePolyline(location)
			{
				array_index = polylinemarker.length;
				polylinemarker[array_index] = new google.maps.Marker({
					position: location,
					map: mappolyline,
					title: "#"+polylinemarker.length,
					draggable: true,
				});
				google.maps.event.addListener(polylinemarker[array_index], 'click', function(event){
					var title = this.getTitle();
					var index = title.replace("#","");
					newpolylinecoords.splice(index,1);
					polylinemarker.splice(index,1);
					newpolyline.setPath(newpolylinecoords);
					this.setMap(null);
					updatePolylineInputs();
					for(var z=0; z < newpolylinecoords.length; z++)
					{
						polylinemarker[z].setTitle("#"+z);
					}
				});
				newpolylinecoords.push(polylinemarker[array_index].getPosition());
				google.maps.event.addListener(polylinemarker[array_index], "drag",function(e){
					var title = this.getTitle();
					var index = title.replace("#","")
					var position = this.getPosition();
					newpolylinecoords[index] = position;
					newpolyline.setPath(newpolylinecoords);
					updatePolylineInputs(position);
				})
				var polyline_line_color = "#"+jQuery('#polyline_line_color').val();
				var polyline_line_opacity = jQuery('#polyline_line_opacity').val();
				var polyline_line_width = jQuery('#polyline_line_width').val();
				if(newpolyline)
				{
					newpolyline.setMap(mappolyline);
					newpolyline.setPath(newpolylinecoords);
				}
				else
				{
					newpolyline = new google.maps.Polyline({
						map:mappolyline,
						path:newpolylinecoords,
						strokeColor:polyline_line_color,
						strokeWeight:polyline_line_width,
						strokeOpacity:polyline_line_opacity,
					})
				}
				i++
			}
			function deleteItem(id,table,li,x)
			{
				var delete_data = {
					action:'g_map_options',
					id:id,
					table:table,
				}
				jQuery.post("<?php echo admin_url( 'admin-ajax.php' ); ?>", delete_data, function(response){
					if(response.success)
					{
						li.remove();
					}
				},"json")
					
			}
		</script>
		<?php ;
	}
}
?>