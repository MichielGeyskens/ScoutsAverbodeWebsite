jQuery(document).ready(function(){
	var pridat;
	var map;
	var geocoder;
	var marker =[];
	var polygonmarker = [];
	var polycoords = [];
	var polymark = [];
	var infowindow = [];
	var coords = [];
	var newpolygone;
	var polygoneOptions=[];
	var polygonpoints;
	var	polygoncoords =[];
	var newpolygoncoords = [];
	var polylinemarker = [];
	var polygone = [];
	var polylinemarkerloc = [];
	var polylinecoords = [];
	var newpolyline;
	var circlepoint;
	var newcircle = [];
	var newcirclemarker =[];
	var circleinfowindow = [];
	var polylinepoints;
	var circlemarker;
	var data;
	var polyline =[];
	var circle;
	var newmarker;
	var i= 0;   var q=0;  var t=0;   var j=0;  var y = 0;
	var editcircleposition;
	var map_id=jQuery("#map_id").val();
	
	data={
		action:'g_map_options',
		map_id:map_id
	}
	jQuery.post(ajax_object.ajax_url, data, function(response){
		if(response.success)
		{
			var xml = jQuery.parseXML(response.success);
			
////////////////////////////////////////////////MAP////////////////////////////////////
			var maps = xml.documentElement.getElementsByTagName("map");
			for(var i = 0; i < maps.length; i++)
			{
				var maptype = maps[i].getAttribute("type");
				var mapzoom = maps[i].getAttribute("zoom");
				var mapcenterlat = maps[i].getAttribute("center_lat");
				var mapcenterlat = maps[i].getAttribute("center_lng");
				var mapcenter = new google.maps.LatLng(
					parseFloat(maps[i].getAttribute("center_lat")),
					parseFloat(maps[i].getAttribute("center_lng")));
					if(maptype == 'ROADMAP'){
						var mapOptions = {
							zoom: parseInt(mapzoom),
							center: mapcenter,
							mapTypeId: google.maps.MapTypeId.ROADMAP
						}
					}
					if(maptype == 'SATELLITE'){
						var mapOptions = {
							zoom: parseInt(mapzoom),
							center: mapcenter,
							mapTypeId: google.maps.MapTypeId.SATELLITE
						}						

					}
					if(maptype == 'HYBRID'){
						var mapOptions={
							zoom: parseInt(mapzoom),
							center: mapcenter,
							mapTypeId: google.maps.MapTypeId.HYBRID
						}
					}
					if(maptype == 'TERRAIN'){
						var mapOptions={
							zoom: parseInt(mapzoom),
							center: mapcenter,
							mapTypeId: google.maps.MapTypeId.TERRAIN
						}
					} 
					mappolygone = new google.maps.Map(document.getElementById('g_map_polygon'), mapOptions);
					map = new google.maps.Map(document.getElementById('g_map_canvas'), mapOptions);
					mappolyline = new google.maps.Map(document.getElementById('g_map_polyline'), mapOptions);
					mapcircle = new google.maps.Map(document.getElementById('g_map_circle'), mapOptions);
					enterCirleAddr();
					changeCircleOptions();
					map_marker_edit = new google.maps.Map(document.getElementById('g_map_marker_edit'), mapOptions);
					map_polygone_edit = new google.maps.Map(document.getElementById('g_map_polygone_edit'),mapOptions);
					map_polyline_edit = new google.maps.Map(document.getElementById('g_map_polyline_edit'),mapOptions);
					map_circle_edit = new google.maps.Map(document.getElementById('g_map_circle_edit'),mapOptions);
					google.maps.event.addListener(map, 'rightclick', function(event) {
						placeMarker(event.latLng);
					});
					google.maps.event.addListener(mappolygone, 'rightclick', function(event) {
						placepolygone(event.latLng);
					});
					google.maps.event.addListener(mappolyline, 'rightclick', function(event) {
						placepolyline(event.latLng);
					});
					google.maps.event.addListener(mapcircle, 'rightclick', function(event) {
						placecircle(event.latLng);
					});
					
					jQuery("#map_center_addr").on("change",function(){
						var addr = jQuery(this).val();
						var geocoder = geocoder = new google.maps.Geocoder();
						geocoder.geocode({'address':addr},function(results, status){
							if (status == google.maps.GeocoderStatus.OK) {
								address = results[0].geometry.location;
								map.setCenter(results[0].geometry.location);
								jQuery("#map_center_lat").val(address.lat());
								jQuery("#map_center_lng").val(address.lng());
							}
						})
					})
					jQuery("#polygon_add_button").on('click',function(e){
						jQuery(this).hide(100);
						jQuery("#g_maps > div").not("#g_map_polygon").addClass("hide");
						jQuery("#g_map_polygon").removeClass("hide");
						jQuery("#polygone_edit_exist_section").addClass("hide");
						jQuery("#g_map_polygone_options .hidden_edit_content").show(200);
						google.maps.event.trigger(mappolygone, 'resize');
						mappolygone.setCenter(mapcenter); 
						return false;
					})
					jQuery("#polyline_add_button").on('click',function(e){
						e.preventDefault;
						jQuery(this).hide("fast");
						jQuery("#g_maps > div").not("#g_map_polygon").addClass("hide");
						jQuery("#g_map_polyline").removeClass("hide");
						jQuery("#polyline_edit_exist_section").addClass("hide");
						jQuery("#g_map_polyline_options .hidden_edit_content").show(200);
						google.maps.event.trigger(mappolyline, 'resize');
						mappolyline.setCenter(mapcenter); 
						return false;
					})
					jQuery("#circle_add_button").on("click",function(e){
						jQuery(this).hide("fast");
						jQuery("#g_maps > div").not("#g_map_polygon").addClass("hide");
						jQuery("#g_map_circle").removeClass("hide");
						jQuery("#circle_edit_exist_section").hide(200);
						jQuery("#g_map_circle_options .hidden_edit_content").show(200);
						google.maps.event.trigger(mapcircle, 'resize');
						mapcircle.setCenter(mapcenter); 
						return false;
					})
					jQuery("#marker_add_button").on("click",function(e){
						jQuery(this).hide("fast");
						jQuery("#g_maps > div").not("#g_map_polygon").addClass("hide");
						jQuery("#g_map_canvas").removeClass("hide");
						jQuery("#markers_edit_exist_section").addClass("hide");
						jQuery(".update_marker_list_item").hide(200);
						jQuery("#g_map_marker_options .hidden_edit_content").show(200);
						
						return false;
					})
					jQuery(".edit_polyline_list_delete a").on("click",function(){
						var parent = jQuery(this).parent();
						var idelement = parent.find(".polyline_edit_id");
						var polylineid = idelement.val();
						jQuery("#g_maps > div").not("#g_map_polygon").addClass("hide");
						jQuery("#g_map_polyline_edit").removeClass("hide");
						jQuery("#polyline_edit_exist_section").hide(200);
						jQuery("#polyline_add_button").hide(200);
						jQuery(this).parent().parent().parent().parent().parent().find(".update_list_item").show(200);
						jQuery("#polyline_get_id").val(polylineid);
						google.maps.event.trigger(map_polyline_edit, 'resize');
						map_polyline_edit.setCenter(mapcenter);
						var polylines = xml.documentElement.getElementsByTagName("polyline");
						for(var i = 0; i < polylines.length; i++)
						{
							var id = polylines[i].getAttribute("id");
							if(polylineid == id)
							{
								var name = polylines[i].getAttribute("name");
								var line_opacity = polylines[i].getAttribute("line_opacity");
								var line_color = polylines[i].getAttribute("line_color");
								var line_width = polylines[i].getAttribute("line_width");
								jQuery("#polyline_edit_name").val(name);
								jQuery("#polyline_edit_line_opacity").val(line_opacity);
								jQuery("#polyline_edit_line_color").val(line_color);
								jQuery("#polyline_edit_line_width").val(line_width);
								var latlngs = polylines[i].getElementsByTagName("latlng");
								var polylineditcoords =[];
								var textedit ="";
								var polylineeditmarker = [];
								
								for(var j = 0; j < latlngs.length; j++)
								{
									var lat =latlngs[j].getAttribute("lat");
									var lng =latlngs[j].getAttribute("lng");
									var polylineditpoint = new google.maps.LatLng(parseFloat(latlngs[j].getAttribute("lat")),
										parseFloat(latlngs[j].getAttribute("lng")));
									var textcoords = "("+lat+","+lng+")";
									if(j==0){
										textedit = textedit+""+textcoords;
									}
									else{
										textedit = textedit+";"+textcoords;
									}
									polylineeditmarker[j] = new google.maps.Marker({
										position:polylineditpoint,
										map:map_polyline_edit,
										title:"#"+j,
										draggable:true,
									})
									polylineditcoords.push(polylineditpoint);
									google.maps.event.addListener(polylineeditmarker[j], 'click', function(e){
										var position = this.getPosition();
										var title = this.getTitle();
										var index = title.replace("#","");
										var lat =  position.lat();
										var lng = position.lng();
										
										
										this.setMap(null);
										polylineeditmarker.splice(index, 1);
										
										var array =jQuery("#polyline_edit_coords").val().split(";");
										var textvalue = "";
										var polylineditcoords =[];
										for (u=0;u<array.length;u++){
											if(u==0){
												if("#"+u == title){
													textvalue = textvalue;
												}
												else{
													textvalue = textvalue+""+array[u];
													var latlngarray = array[u].split(",");
													var latarray = latlngarray[0].replace("(", "");
													var lngarray = latlngarray[1].replace(")", "");
													polylineditcoords.push(new google.maps.LatLng(parseFloat(latarray),
													parseFloat(lngarray)));
												}
											}
											else{
												if(u == 1 && title== "#0")
												{
													if("#"+u == title){
														textvalue = textvalue;
													}
													else{
														textvalue = textvalue+""+array[u];
														var latlngarray = array[u].split(",");
														var latarray = latlngarray[0].replace("(", "");
														var lngarray = latlngarray[1].replace(")", "");
														polylineditcoords.push(new google.maps.LatLng(parseFloat(latarray),
														parseFloat(lngarray)));
													}
												}
												else{
													if("#"+u == title){
														textvalue = textvalue;
													}
													else{
														textvalue = textvalue+";"+array[u];
														var latlngarray = array[u].split(",");
														var latarray = latlngarray[0].replace("(", "");
														var lngarray = latlngarray[1].replace(")", "");
														polylineditcoords.push(new google.maps.LatLng(parseFloat(latarray),
														parseFloat(lngarray)));
													}
												}
											}
										}
										jQuery("#polyline_edit_coords").val(textvalue)
										polylinedit.setPath(polylineditcoords);
										for(var t=0;t < polylineeditmarker.length; t++)
										{
											polylineeditmarker[t].setTitle("#"+t);
										}
									})
									google.maps.event.addListener(polylineeditmarker[j], 'drag' , function(e){
										var position = this.getPosition();
										var title = this.getTitle();
										var lat =  position.lat();
										var lng = position.lng();
										var array =jQuery("#polyline_edit_coords").val().split(";");
										var textvalue = "";
										var polylineditcoords =[];
										for (u=0;u<array.length;u++){
											if(u==0){
												if("#"+u == title){
													textvalue = textvalue+""+"("+lat+","+lng+")";
													polylineditcoords.push(position);
												}
												else{
													textvalue = textvalue+""+array[u];
													var latlngarray = array[u].split(",");
													var latarray = latlngarray[0].replace("(", "");
													var lngarray = latlngarray[1].replace(")", "");
													polylineditcoords.push(new google.maps.LatLng(parseFloat(latarray),
													parseFloat(lngarray)));
												}
											}
											else{
												if("#"+u == title){
													textvalue = textvalue+";"+"("+lat+","+lng+")";
													polylineditcoords.push(position);
												}
												else{
													textvalue = textvalue+";"+array[u];
													var latlngarray = array[u].split(",");
													var latarray = latlngarray[0].replace("(", "");
													var lngarray = latlngarray[1].replace(")", "");
													polylineditcoords.push(new google.maps.LatLng(parseFloat(latarray),
													parseFloat(lngarray)));
												}
											}
										}
										jQuery("#polyline_edit_coords").val(textvalue)
										polylinedit.setPath(polylineditcoords);
										jQuery(".polyline_edit_options_input").on('change',function(){
											if(polylinedit){
												polylinedit.setMap(null);
												var polyline_line_color = "#"+jQuery('#polyline_edit_line_color').val();
												var polyline_line_opacity = jQuery('#polyline_edit_line_opacity').val();
												var polyline_line_width = jQuery('#polyline_edit_line_width').val();
												polylinedit= new google.maps.Polyline({
													path: polylineditcoords,
													map:map_polyline_edit,
													strokeColor: polyline_line_color,
													strokeOpacity: polyline_line_opacity,
													strokeWeight: polyline_line_width,
													draggable:false
												});	
											}
										})
									})
								}
								jQuery("#polyline_edit_coords").val(textedit);
								var polylinedit = new google.maps.Polyline({
									path : polylineditcoords,
									map: map_polyline_edit,
									strokeOpacity: line_opacity,
									strokeColor:"#"+line_color,
									strokeWeight:line_width,
									draggable:false,
								});
							}
						}
						google.maps.event.addListener(map_polyline_edit, 'rightclick', function(event){
							
							var lat = event.latLng.lat();
							var lng = event.latLng.lng();
							
							var index = polylineeditmarker.length;
							polylineeditmarker[index] = new google.maps.Marker({
								map:map_polyline_edit,
								position:event.latLng,
								title:"#"+index,
								draggable:false,
							})
							var title = this.getTitle;
							var value = jQuery("#polyline_edit_coords").val();
							var newvalue = value+";("+lat+","+lng+")";
							jQuery("#polyline_edit_coords").val(newvalue);
							polylineditcoords.push(event.latLng);
							/*var array =jQuery("#polyline_edit_coords").val().split(";");
							for (u=0;u<array.length;u++){
									var latlngarray = array[u].split(",");
									var latarray = latlngarray[0].replace("(", "");
									var lngarray = latlngarray[1].replace(")", "");
									polylineditcoords.push(new google.maps.LatLng(parseFloat(latarray),
									parseFloat(lngarray)));
							}*/
							polylinedit.setPath(polylineditcoords);
						})
					})
					jQuery(".edit_circle_list_delete a").on("click",function(){
						var parent = jQuery(this).parent();
						var idelement = parent.find(".circle_edit_id");
						var circleid = idelement.val();
						jQuery("#g_maps > div").not("#g_map_polygon").addClass("hide");
						jQuery("#g_map_circle_edit").removeClass("hide");
						jQuery("#circle_edit_exist_section").hide(200);
						jQuery(this).parent().parent().parent().parent().parent().find(".update_list_item").show(200);
						jQuery("#circle_add_button").hide(200);
						google.maps.event.trigger(map_circle_edit, 'resize');
						map_circle_edit.setCenter(mapcenter);
						jQuery("#circle_get_id").val(circleid);
						var circles = xml.documentElement.getElementsByTagName("circle");
						for(var i = 0; i < circles.length; i++)
						{
							var id = circles[i].getAttribute("id");
							if(circleid == id)
							{
								var name = circles[i].getAttribute("name");
								var center_lat = circles[i].getAttribute("center_lat");
								var center_lng = circles[i].getAttribute("center_lng");
								var radius = circles[i].getAttribute("radius");
								var line_width = circles[i].getAttribute("line_width");
								var line_color = circles[i].getAttribute("line_color");
								var line_opacity = circles[i].getAttribute("line_opacity");
								var fill_color = circles[i].getAttribute("fill_color");
								var fill_opacity = circles[i].getAttribute("fill_opacity");
								var show_marker = circles[i].getAttribute("show_marker");
								jQuery("#circle_edit_name").val(name);
								jQuery("#circle_edit_center_lat").val(center_lat);
								jQuery("#circle_edit_center_lng").val(center_lng);
								
								//jQuery("#circle_edit_center_addr").val(name);
								if(show_marker == '1'){
									jQuery("#circle_edit_marker_show_true").attr("checked","checked");
								}
								else{
									jQuery("#circle_edit_marker_show_false").attr("checked","checked");
								}
								jQuery("#circle_edit_radius").val(radius);
								jQuery("#circle_edit_line_width").val(line_width);
								jQuery("#circle_edit_line_color").val(line_color);
								jQuery("#circle_edit_line_opacity").val(line_opacity);
								jQuery("#circle_edit_fill_color").val(fill_color);
								jQuery("#circle_edit_fill_opacity").val(fill_opacity);
								
								editcircleposition = new google.maps.LatLng(parseFloat(circles[i].getAttribute("center_lat")),
									parseFloat(circles[i].getAttribute("center_lng")));
								var geocoder= new google.maps.Geocoder();
								geocoder.geocode({'latLng':editcircleposition},function(results, status){
									if (status == google.maps.GeocoderStatus.OK) {
										address = results[0].formatted_address;
										jQuery("#circle_edit_center_addr").val(address);
									}
								})
								var circlemarkeredit = new google.maps.Marker({
									position:editcircleposition,
									map:map_circle_edit,
									title:name,
									draggable:true,
								})
								google.maps.event.addListener(circlemarkeredit, 'drag' , function(e){
									var lat, lng
									editcircleposition = this.getPosition();
									lat = this.getPosition().lat();
									lng =this.getPosition().lng();
									jQuery("#circle_edit_center_lat").val(lat);
									jQuery("#circle_edit_center_lng").val(lng);
									editcircle.setCenter(editcircleposition);
									var geocoder= new google.maps.Geocoder();
									geocoder.geocode({'latLng':editcircleposition},function(results, status){
										if (status == google.maps.GeocoderStatus.OK) {
											address = results[0].formatted_address;
											jQuery("#circle_edit_center_addr").val(address);
										}
									})
									jQuery(".circle_edit_options_input").on("change",function(){
										if(editcircle){
											editcircle.setMap(null);
											var circle_edit_radius = jQuery("#circle_edit_radius").val();
											var lat = jQuery("#circle_edit_center_lat").val();
											var lng = jQuery("#circle_edit_center_lng").val();
											var circle_editlocation = new google.maps.LatLng(jQuery("#circle_edit_center_lat").val(),
											jQuery("#circle_edit_center_lng").val());
											var circle_edit_line_width = jQuery("#circle_edit_line_width").val();
											var circle_edit_line_color = jQuery("#circle_edit_line_color").val();
											var circle_edit_line_opacity = jQuery("#circle_edit_line_opacity").val();
											var circle_edit_fill_color = jQuery("#circle_edit_fill_color").val();
											var circle_edit_fill_opacity = jQuery("#circle_edit_fill_opacity").val();
											editcircle = new google.maps.Circle({
												map:map_circle_edit,
												center:editcircleposition,
												radius:parseInt(circle_edit_radius),
												strokeColor:"#"+circle_edit_line_color,
												strokeOpacity:circle_edit_line_opacity,
												strokeWeight:circle_edit_line_width,
												fillColor:"#"+circle_edit_fill_color,
												fillOpacity:circle_edit_fill_opacity
											})
										}
									})
								})
								var editcircle = new google.maps.Circle({
									map:map_circle_edit,
									center:editcircleposition,
									title:name,
									radius:parseInt(radius),
									strokeColor:"#"+line_color,
									strokeOpacity:line_opacity,
									strokeWeight:line_width,
									fillColor:"#"+fill_color,
									fillOpacity:fill_opacity
								})
								jQuery(".circle_edit_options_input").on("change",function(){
										if(editcircle){
											editcircle.setMap(null);
											var circle_edit_radius = jQuery("#circle_edit_radius").val();
											var lat = jQuery("#circle_edit_center_lat").val();
											var lng = jQuery("#circle_edit_center_lng").val();
											var circle_editlocation = new google.maps.LatLng(jQuery("#circle_edit_center_lat").val(),
											jQuery("#circle_edit_center_lng").val());
											var circle_edit_line_width = jQuery("#circle_edit_line_width").val();
											var circle_edit_line_color = jQuery("#circle_edit_line_color").val();
											var circle_edit_line_opacity = jQuery("#circle_edit_line_opacity").val();
											var circle_edit_fill_color = jQuery("#circle_edit_fill_color").val();
											var circle_edit_fill_opacity = jQuery("#circle_edit_fill_opacity").val();
											editcircle = new google.maps.Circle({
												map:map_circle_edit,
												center:editcircleposition,
												radius:parseInt(circle_edit_radius),
												strokeColor:"#"+circle_edit_line_color,
												strokeOpacity:circle_edit_line_opacity,
												strokeWeight:circle_edit_line_width,
												fillColor:"#"+circle_edit_fill_color,
												fillOpacity:circle_edit_fill_opacity
											})
										}
									})
							}
						}
						
					})
					jQuery(".edit_polygone_list_delete a").on("click",function(e){
						var parent = jQuery(this).parent();
						var idelement = parent.find(".polygone_edit_id");
						var polygoneid = idelement.val();
						jQuery("#g_maps > div").not("#g_map_polygon").addClass("hide");
						jQuery("#g_map_polygone_edit").removeClass("hide");
						
						jQuery("#polygone_edit_exist_section").hide(200);
						jQuery(this).parent().parent().parent().parent().parent().find(".update_list_item").show(200);
						jQuery("#polygon_add_button").hide(200);
						google.maps.event.trigger(map_polygone_edit, 'resize');
						map_polygone_edit.setCenter(mapcenter);
						jQuery("#polygone_get_id").val(polygoneid);
						var polygones = xml.documentElement.getElementsByTagName("polygone");
						for(var i = 0; i < polygones.length; i++)
						{
							var id = polygones[i].getAttribute("id");
							if(polygoneid == id)
							{
								var name=polygones[i].getAttribute("name");
								var line_opacity=polygones[i].getAttribute("line_opacity");
								var line_color=polygones[i].getAttribute("line_color");
								var fill_opacity=polygones[i].getAttribute("fill_opacity");
								var fill_color=polygones[i].getAttribute("fill_color");
								var line_width = polygones[i].getAttribute("line_width");
								var latlngs = polygones[i].getElementsByTagName("latlng");
								jQuery("#polygone_edit_name").val(name);
								jQuery("#polygone_edit_line_opacity").val(line_opacity);
								jQuery("#polygone_edit_line_color").val(line_color);
								jQuery("#polygone_edit_line_width").val(line_width);
								jQuery("#polygone_edit_fill_opacity").val(fill_opacity);
								jQuery("#polygone_edit_fill_color").val(fill_color);
								var polygoneditcoords =[];
								var textedit ="";
								var polygoneditmarker = [];
								
								for(var j = 0; j < latlngs.length; j++)
								{
									var lat =latlngs[j].getAttribute("lat");
									var lng =latlngs[j].getAttribute("lng");
									var polygoneditpoint = new google.maps.LatLng(parseFloat(latlngs[j].getAttribute("lat")),
										parseFloat(latlngs[j].getAttribute("lng")));
										var textcoords = "("+lat+","+lng+")";
										if(j==0){
											textedit = textedit+""+textcoords;
										}
										else{
											textedit = textedit+";"+textcoords;
										}
									polygoneditmarker[j] = new google.maps.Marker({
										position:polygoneditpoint,
										map:map_polygone_edit,
										title:"#"+j,
										draggable:true,
									})
									polygoneditcoords.push(polygoneditpoint)
									google.maps.event.addListener(polygoneditmarker[j], 'click', function(e){
										var position = this.getPosition();
										var title = this.getTitle();
										var index = title.replace("#","");
										var lat =  position.lat();
										var lng = position.lng();
										polygoneditcoords = [];
										
										this.setMap(null);
										polygoneditmarker.splice(index, 1);
										
										var array =jQuery("#polygone_edit_coords").val().split(";");
										var textvalue = "";
										var polylineditcoords =[];
										for (u=0;u<array.length;u++){
											if(u==0){
												if("#"+u == title){
													textvalue = textvalue;
												}
												else{
													textvalue = textvalue+""+array[u];
													var latlngarray = array[u].split(",");
													var latarray = latlngarray[0].replace("(", "");
													var lngarray = latlngarray[1].replace(")", "");
													polygoneditcoords.push(new google.maps.LatLng(parseFloat(latarray),
													parseFloat(lngarray)));
												}
											}
											else{
												if(u == 1)
												{
													if("#"+u == title){
														textvalue = textvalue;
													}
													else{
														textvalue = textvalue+""+array[u];
														var latlngarray = array[u].split(",");
														var latarray = latlngarray[0].replace("(", "");
														var lngarray = latlngarray[1].replace(")", "");
														polygoneditcoords.push(new google.maps.LatLng(parseFloat(latarray),
														parseFloat(lngarray)));
													}
												}
												else{
													if("#"+u == title){
														textvalue = textvalue;
													}
													else{
														textvalue = textvalue+";"+array[u];
														var latlngarray = array[u].split(",");
														var latarray = latlngarray[0].replace("(", "");
														var lngarray = latlngarray[1].replace(")", "");
														polygoneditcoords.push(new google.maps.LatLng(parseFloat(latarray),
														parseFloat(lngarray)));
													}
												}
											}
											
										}
										jQuery("#polygone_edit_coords").val(textvalue)
										polygonedit.setPaths(polygoneditcoords);
										for(var t=0;t < polygoneditmarker.length; t++)
										{
											polygoneditmarker[t].setTitle("#"+t);
										}
									})
									google.maps.event.addListener(polygoneditmarker[j], 'drag' , function(e){
										var position = this.getPosition();
										var title = this.getTitle();
										var lat =  position.lat();
										var lng = position.lng();
										var array =jQuery("#polygone_edit_coords").val().split(";");
										var textvalue = "";
										var polygoneditcoords =[];
										for (u=0;u<array.length;u++){
											if(u==0){
												if("#"+u == title){
													textvalue = textvalue+""+"("+lat+","+lng+")";
													polygoneditcoords.push(position);
												}
												else{
													textvalue = textvalue+""+array[u];
													var latlngarray = array[u].split(",");
													var latarray = latlngarray[0].replace("(", "");
													var lngarray = latlngarray[1].replace(")", "");
													polygoneditcoords.push(new google.maps.LatLng(parseFloat(latarray),
													parseFloat(lngarray)));
												}
											}
											else{
												if("#"+u == title){
													textvalue = textvalue+";"+"("+lat+","+lng+")";
													polygoneditcoords.push(position);
												}
												else{
													textvalue = textvalue+";"+array[u];
													var latlngarray = array[u].split(",");
													var latarray = latlngarray[0].replace("(", "");
													var lngarray = latlngarray[1].replace(")", "");
													polygoneditcoords.push(new google.maps.LatLng(parseFloat(latarray),
													parseFloat(lngarray)));
												}
											}
										}
										
										jQuery("#polygone_edit_coords").val(textvalue)
										polygonedit.setPaths(polygoneditcoords);
										
									})
								}
								jQuery("#polygone_edit_coords").val(textedit);
								var polygonedit = new google.maps.Polygon({
									paths : polygoneditcoords,
									map: map_polygone_edit,
									strokeOpacity: line_opacity,
									strokeColor:"#"+line_color,
									fillOpacity:fill_opacity,
									fillColor:"#"+fill_color,
									draggable:false,
								});
								jQuery(".polygone_edit_options_input").on('change',function(){
									if(polygonedit){
										polygonedit.setMap(null);
										var polygone_line_color = "#"+jQuery('#polygone_edit_line_color').val();
										var polygone_line_opacity = jQuery('#polygone_edit_line_opacity').val();
										var polygone_fill_color = "#"+jQuery('#polygone_edit_fill_color').val();
										var polygone_fill_opacity = jQuery('#polygone_edit_fill_opacity').val();
										var polygone_line_width = jQuery('#polygone_edit_line_width').val();
										polygonedit= new google.maps.Polygon({
											paths: polygonedit.getPaths(),
											map:map_polygone_edit,
											strokeColor: polygone_line_color,
											strokeOpacity: polygone_line_opacity,
											strokeWeight: polygone_line_width,
											fillColor: polygone_fill_color,
											fillOpacity: polygone_fill_opacity,
										});	
									}
								})
							}
						}
						google.maps.event.addListener(map_polygone_edit, 'rightclick', function(event){
							
							var lat = event.latLng.lat();
							var lng = event.latLng.lng();
							
							var index = polygoneditmarker.length;
							polygoneditmarker[index] = new google.maps.Marker({
								map:map_polygone_edit,
								position:event.latLng,
								title:"#"+index,
								draggable:false,
							})
							var title = this.getTitle;
							var value = jQuery("#polygone_edit_coords").val();
							var newvalue = value+";("+lat+","+lng+")";
							jQuery("#polygone_edit_coords").val(newvalue);
							polygoneditcoords.push(event.latLng);
							/*var array =jQuery("#polyline_edit_coords").val().split(";");
							for (u=0;u<array.length;u++){
									var latlngarray = array[u].split(",");
									var latarray = latlngarray[0].replace("(", "");
									var lngarray = latlngarray[1].replace(")", "");
									polylineditcoords.push(new google.maps.LatLng(parseFloat(latarray),
									parseFloat(lngarray)));
							}*/
							polygonedit.setPath(polygoneditcoords);
						})
						return false;
					})
					jQuery(".edit_marker_list_delete a").on("click",function(e){
						var parent = jQuery(this).parent();
						var idelement = parent.find(".marker_edit_id");
						var markerid = idelement.val();
						jQuery("#g_maps > div").not("#g_map_polygon").addClass("hide");
						jQuery("#g_map_marker_edit").removeClass("hide");
						jQuery("#markers_edit_exist_section").hide(200);
						jQuery(this).parentsUntil(".editing_section").find(".update_list_item").show(200);
						jQuery("#marker_add_button").hide(200);
						jQuery("#marker_get_id").val(markerid);
						var markers= xml.documentElement.getElementsByTagName("marker");
						for(var i = 0; i < markers.length; i++)
						{
							var id = markers[i].getAttribute("id");
							
							if(markerid == id)
							{
								var name = markers[i].getAttribute("name");
								var address = markers[i].getAttribute("address");
								var anim = markers[i].getAttribute("animation");
								var description = markers[i].getAttribute("description");
								var markimg = markers[i].getAttribute("img");
								var lat = markers[i].getAttribute("lat");
								var lng  = markers[i].getAttribute("lng");
								 var img_size = markers[i].getAttribute("size");
								 if(img_size == "16")
								 {
									jQuery("#image_edit_size_16").attr("selected","selected")
								 }
								 if(img_size == "24")
								 {
									jQuery("#image_edit_size_24").attr("selected","selected")
								 }
								 if(img_size == "48")
								 {
									jQuery("#image_edit_size_48").attr("selected","selected")
								 }
								 if(img_size == "64")
								 {
									jQuery("#image_edit_size_64").attr("selected","selected")
								 }
								 if(img_size == "256")
								 {
									jQuery("#image_edit_size_256").attr("selected","selected")
								 }
								var point = new google.maps.LatLng(
									parseFloat(markers[i].getAttribute("lat")),
									parseFloat(markers[i].getAttribute("lng")));
								google.maps.event.trigger(map_marker_edit, 'resize');
								map_marker_edit.setCenter(point); 
								var html = "<b>" + name + "</b> <br/>" + address;
								jQuery("#marker_edit_location_lat").val(lat);
								jQuery("#marker_edit_location_lng").val(lng);
								jQuery("#marker_edit_animation").val(anim);
								jQuery("#marker_edit_title").val(name);
								jQuery("#marker_edit_description").val(description);
								if(anim == 'DROP'){
									markeredit = new google.maps.Marker({
									map: map_marker_edit,
									position: point,
									title: name,
									icon: markimg,
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
									icon: markimg,
									animation: google.maps.Animation.BOUNCE,
									draggable:true
									});
								}
								if(anim == 'NONE'){
									markeredit = new google.maps.Marker({
										map: map_marker_edit,
										position: point,
										icon: markimg,
										content: description,
										title: name,
										draggable:true
									});
								}
								var geocoder = geocoder = new google.maps.Geocoder();
								geocoder.geocode({'latLng':markeredit.getPosition()},function(results, status){
									if (status == google.maps.GeocoderStatus.OK) {
										address = results[0].formatted_address;
										jQuery("#marker_edit_location").val(address);
									}
								})
								google.maps.event.addListener(markeredit, "drag", function (e){
									var lat, lng;
									lat = markeredit.getPosition().lat();
									lng = markeredit.getPosition().lng();
									jQuery("#marker_edit_location_lat").val(lat);
									jQuery("#marker_edit_location_lng").val(lng);
									var geocoder = geocoder = new google.maps.Geocoder();
									geocoder.geocode({'latLng':markeredit.getPosition()},function(results, status){
										if (status == google.maps.GeocoderStatus.OK) {
											address = results[0].formatted_address;
											jQuery("#marker_edit_location").val(address);
										}
									})
								});
								infowindow[i] = new google.maps.InfoWindow;
								bindInfoWindow(marker[i], map_marker_edit, infowindow[i], description);
							}
						}
						return false;
					})
					jQuery("#marker_location").on('change',function(){
						var geocoder = geocoder = new google.maps.Geocoder();
						var loc = jQuery("#marker_location").val();
						geocoder.geocode({ 'address': loc }, function (results, status) {
							if (status == google.maps.GeocoderStatus.OK) {
								map.setCenter(results[0].geometry.location);
								if (newmarker) {
								  newmarker.setPosition(results[0].geometry.location);
								} 
								else {
									newmarker = new google.maps.Marker({
										position: results[0].geometry.location,
										map: map,
										title: 'New point',
										draggable: true,
									});
									var lat, lng
									lat = newmarker.getPosition().lat();
									lng = newmarker.getPosition().lng();
									jQuery("#marker_location_lat").val(lat);
									jQuery("#marker_location_lng").val(lng);
								}
								google.maps.event.addListener(newmarker, "drag", function (e) {
									var lat, lng
									lat = newmarker.getPosition().lat();
									lng = newmarker.getPosition().lng();
									jQuery("#marker_location_lat").val(lat);
									jQuery("#marker_location_lng").val(lng);
									var geocoder = geocoder = new google.maps.Geocoder();
									geocoder.geocode({'latLng':newmarker.getPosition()},function(results, status){
										if (status == google.maps.GeocoderStatus.OK) {
											address = results[0].formatted_address;
											jQuery("#marker_location").val(address);
										}
									})
								});
							}
							else {
								alert("Geocode was not successful for the following reason: " + status);
							}
						});
					})	
///////////////////////////////////////////////// MARKERS	/////////////////////////////
				var markers = xml.documentElement.getElementsByTagName("marker");
				for(j = 0; j < markers.length; j++)
				{
					var name = markers[j].getAttribute("name");
					var address = markers[j].getAttribute("address");
					var anim = markers[j].getAttribute("animation");
					var description = markers[j].getAttribute("description");
					var markimg = markers[j].getAttribute("img");
					var img = new google.maps.MarkerImage(markimg,
					 new google.maps.Size(20, 20));
					var point = new google.maps.LatLng(
						parseFloat(markers[j].getAttribute("lat")),
						parseFloat(markers[j].getAttribute("lng")));
					var html = "<b>" + name + "</b> <br/>" + address;
					if(anim == 'DROP'){
						marker[j] = new google.maps.Marker({
						map: map,
						position: point,
						title: name,
						icon: markimg,
						content: description,
						animation: google.maps.Animation.DROP,
						});
					}
					if(anim == 'BOUNCE'){
					marker[j] = new google.maps.Marker({
						map: map,
						position: point,
						title: name,
						content: description,
						icon: markimg,
						animation: google.maps.Animation.BOUNCE
						});
					}
					if(anim == 'NONE'){
						marker[j] = new google.maps.Marker({
							map: map,
							position: point,
							icon: markimg,
							content: description,
							title: name,
						});
					}
					infowindow[j] = new google.maps.InfoWindow;
					bindInfoWindow(marker[j], map, infowindow[j], description);
					jQuery(".edit_list_delete_submit").on("click",function(){
						var parent = jQuery(this).parent();
						var typeelement = parent.find(".edit_list_delete_type");
						var type =typeelement.val();
						var parent = jQuery(this).parent();
						var idelement = parent.find(".edit_list_delete_id");
						var tableelement = parent.find(".edit_list_delete_table");
						var id=idelement.val();
						var table = tableelement.val();
						var li = jQuery(this).parent().parent().parent();
						var x = li.index();
						if(type=="marker")
						{
								marker[x].setMap(null);
								deleteItem(id,table,li,x);

						}
						return false;
					})
				}
////////////////////////////////////////////POLYGONES//////////////////////////////////////////////////////////
				var polygones = xml.documentElement.getElementsByTagName("polygone");
				for(var k = 0; k < polygones.length; k++)
				{
					var name = polygones[k].getAttribute("name");
					var line_opacity = polygones[k].getAttribute("line_opacity");
					var line_color = "#"+polygones[k].getAttribute("line_color");
					var fill_opacity = polygones[k].getAttribute("fill_opacity");
					var line_width = polygones[k].getAttribute("line_width");
					var fill_color = "#"+polygones[k].getAttribute("fill_color");
					var latlngs = polygones[k].getElementsByTagName("latlng");
					polygoncoords = [];
					for(var g = 0; g < latlngs.length; g++)
					{
						polygonpoints = new google.maps.LatLng(parseFloat(latlngs[g].getAttribute("lat")),
							parseFloat(latlngs[g].getAttribute("lng")))
						polygoncoords.push(polygonpoints)
					}
					//alert(polygoncoords);
					polygone[k] = new google.maps.Polygon({
						paths : polygoncoords,
						map: map,
						strokeOpacity: line_opacity,
						strokeColor:line_color,
						strokeWeight:line_width,
						fillOpacity:fill_opacity,
						fillColor:fill_color,
						draggable:false,
					});
					jQuery(".edit_list_delete_submit").on("click",function(){
						var parent = jQuery(this).parent();
						var typeelement = parent.find(".edit_list_delete_type");
						var type =typeelement.val();
						var parent = jQuery(this).parent();
							var idelement = parent.find(".edit_list_delete_id");
							var tableelement = parent.find(".edit_list_delete_table");
							var id=idelement.val();
							var table = tableelement.val();
							var li = jQuery(this).parent().parent().parent();
							var x = li.index();
						if(type=="polygone")
						{
							
							
								polygone[x].setMap(null);
								deleteItem(id,table,li,x);
						}
						return false;
					})
				}
//////////////////////////////////////////POLYLINES///////////////////////////////////////
				var polylines = xml.documentElement.getElementsByTagName("polyline");
				for(var i = 0; i< polylines.length; i++)
				{
					var name = polylines[i].getAttribute("name");
					var line_opacity = polylines[i].getAttribute("line_opacity");
					var line_color = polylines[i].getAttribute("line_color");
					var line_width = polylines[i].getAttribute("line_width");
					var latlngs = polylines[i].getElementsByTagName("latlng");
					var newpolylinecoords =[];
					for(var g = 0; g < latlngs.length; g++)
					{
						polylinepoints = new google.maps.LatLng(parseFloat(latlngs[g].getAttribute("lat")),
							parseFloat(latlngs[g].getAttribute("lng")))
						newpolylinecoords.push(polylinepoints)
					}
					polyline[i] = new google.maps.Polyline({
						path:newpolylinecoords,
						map:map,
						strokeColor:"#"+line_color,
						strokeOpacity:line_opacity,
						strokeWeight:line_width,
					})
					jQuery(".edit_list_delete_submit").on("click",function(){
						var parent = jQuery(this).parent();
						var typeelement = parent.find(".edit_list_delete_type");
						var type =typeelement.val();
						var parent = jQuery(this).parent();
						var idelement = parent.find(".edit_list_delete_id");
						var tableelement = parent.find(".edit_list_delete_table");
						var id=idelement.val();
						var table = tableelement.val();
						var li = jQuery(this).parent().parent().parent();
						var x = li.index();
						if(type == "polyline")
						{
								polyline[x].setMap(null);
								deleteItem(id,table,li,x);
							
						}
						return false;
					})
				}
				var circles = xml.documentElement.getElementsByTagName("circle");
				for(var i = 0; i< circles.length; i++)
				{
					var circle_name =circles[i].getAttribute("name");
					var circle_center_lat = circles[i].getAttribute("center_lat");
					var circle_center_lng = circles[i].getAttribute("center_lng");
					var circle_radius = circles[i].getAttribute("radius");
					var circle_line_width = circles[i].getAttribute("line_width");
					var circle_line_color = circles[i].getAttribute("line_color");
					var circle_line_opacity = circles[i].getAttribute("line_opacity");
					var circle_fill_color = circles[i].getAttribute("fill_color");
					var circle_fill_opacity = circles[i].getAttribute("fill_opacity");
					var circle_show_marker = parseInt(circles[i].getAttribute("show_marker"));
					circlepoint = new google.maps.LatLng(parseFloat(circles[i].getAttribute("center_lat")),
					parseFloat(circles[i].getAttribute("center_lng")));
					newcircle[i] = new google.maps.Circle({
						map:map,
						center:circlepoint,
						title:name,
						radius:parseInt(circle_radius),
						strokeColor:"#"+circle_line_color,
						strokeOpacity:circle_line_opacity,
						strokeWeight:circle_line_width,
						fillColor:"#"+circle_fill_color,
						fillOpacity:circle_fill_opacity
					})
					jQuery(".edit_list_delete_submit").on("click",function(){
						var parent = jQuery(this).parent();
						var typeelement = parent.find(".edit_list_delete_type");
						var type =typeelement.val();
						var parent = jQuery(this).parent();
						var idelement = parent.find(".edit_list_delete_id");
						var tableelement = parent.find(".edit_list_delete_table");
						var id=idelement.val();
						var table = tableelement.val();
						var li = jQuery(this).parent().parent().parent();
						var x = li.index();
						if(type=="circle")
						{
						
								newcircle[x].setMap(null);
								deleteItem(id,table,li,x);
						}
						return false;
					})
					if(circle_show_marker == '1')
					{
						newcirclemarker[i] = new google.maps.Marker({
							position:circlepoint,
							map:map,
							title:circle_name,
						})
					}
					//bindcircleinfowindow(newcirclemarker[i], map ,circleinfowindow[i], address);
					
				}
					
			}
		}
	}
	,"json"
	);

	function deleteItem(id,table,li,x)
	{
			var delete_data = {
				action:'g_map_options',
				id:id,
				table:table,
			}
			jQuery.post(ajax_object.ajax_url, delete_data, function(response){
				if(response.success)
				{
					li.remove();
				}
			},"json")
			
	}
	
	function changepolygoneedit(position,title,lat,lng,polygonedit){
		
	}
	function bindInfoWindow(marker, map, infowindow, description){
		google.maps.event.addListener(marker, 'click', function() {
			infowindow.setContent(description);
			infowindow.open(map, marker);
		});
	}
	function placecircle(location){
		if(circlemarker){
			circlemarker.setPosition(location);
		}
		else{
			circlemarker = new google.maps.Marker({
				position:location,
				map:mapcircle,
				title:"circle center",
				draggable:true
			})
		}
		jQuery("#circle_center_lat").val(circlemarker.getPosition().lat());
		jQuery("#circle_center_lng").val(circlemarker.getPosition().lng());
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({'latLng':circlemarker.getPosition()},function(results, status){
			if (status == google.maps.GeocoderStatus.OK) {
				address = results[0].formatted_address;
				jQuery("#circle_center_addr").val(address);
			}
		})
		var circle_radius = jQuery("#circle_radius").val();
		var circle_line_width = jQuery("#circle_line_width").val();
		var circle_line_color = jQuery("#circle_line_color").val();
		var circle_line_opacity = jQuery("#circle_line_opacity").val();
		var circle_fill_color = jQuery("#circle_fill_color").val();
		var circle_fill_opacity = jQuery("#circle_fill_opacity").val();
		if(circle)
		{
			circle.setCenter(location)
		}
		else{
			circle = new google.maps.Circle({
				map:mapcircle,
				center:location,
				title:"circle",
				radius:parseInt(circle_radius),
				strokeColor:"#"+circle_line_color,
				strokeOpacity:circle_line_opacity,
				strokeWeight:circle_line_width,
				fillColor:"#"+circle_fill_color,
				fillOpacity:circle_fill_opacity
			})
		}
		google.maps.event.addListener(circlemarker, "drag", function (e){
			var lat, lng
			lat = circlemarker.getPosition().lat();
			lng =circlemarker.getPosition().lng();
			jQuery("#circle_center_lat").val(lat);
			jQuery("#circle_center_lng").val(lng);
			circle.setCenter(circlemarker.getPosition());
			var geocoder = geocoder = new google.maps.Geocoder();
			geocoder.geocode({'latLng':circlemarker.getPosition()},function(results, status){
				if (status == google.maps.GeocoderStatus.OK) {
					address = results[0].formatted_address;
					jQuery("#circle_center_addr").val(address);
					changeCircleOptions
				}
			})
		});
		
		enterCirleAddr();
		changeCircleOptions();
		
	}
	function changeCircleOptions(){
		jQuery(".circle_options_input").on("change",function(){
			if(circle){
				circle.setMap(null);
				var circle_radius = jQuery("#circle_radius").val();
				var lat = jQuery("#circle_center_lat").val();
				var lng = jQuery("#circle_center_lng").val();
				var circlelocation = new google.maps.LatLng(lat,lng);
				var circle_line_width = jQuery("#circle_line_width").val();
				var circle_line_color = jQuery("#circle_line_color").val();
				var circle_line_opacity = jQuery("#circle_line_opacity").val();
				var circle_fill_color = jQuery("#circle_fill_color").val();
				var circle_fill_opacity = jQuery("#circle_fill_opacity").val();
				circle = new google.maps.Circle({
					map:mapcircle,
					center:circlelocation,
					title:"circle",
					radius:parseInt(circle_radius),
					strokeColor:"#"+circle_line_color,
					strokeOpacity:circle_line_opacity,
					strokeWeight:circle_line_width,
					fillColor:"#"+circle_fill_color,
					fillOpacity:circle_fill_opacity
				})
			}
		})
	}
	function enterCirleAddr(loc){
		jQuery("#circle_center_addr").on("change",function(){
			var loc = jQuery("#circle_center_addr").val();
			var geocoder= new google.maps.Geocoder();
			var circle_radius = jQuery("#circle_radius").val();
			var circle_line_width = jQuery("#circle_line_width").val();
			var circle_line_color = jQuery("#circle_line_color").val();
			var circle_line_opacity = jQuery("#circle_line_opacity").val();
			var circle_fill_color = jQuery("#circle_fill_color").val();
			var circle_fill_opacity = jQuery("#circle_fill_opacity").val();
			geocoder.geocode({ 'address': loc }, function (results, status) {
				if (status == google.maps.GeocoderStatus.OK)
				{
					mapcircle.setCenter(results[0].geometry.location);
					if (circlemarker) {
					 circlemarker.setPosition(results[0].geometry.location);
					} 
					else {
						circlemarker = new google.maps.Marker({
							position: results[0].geometry.location,
							map: mapcircle,
							title: 'Circle senter',
							draggable: true,
						});
						var lat, lng
						lat = circlemarker.getPosition().lat();
						lng = circlemarker.getPosition().lng();
						var circlelatlng = circlemarker.getPosition();
						jQuery("#circle_center_lat").val(lat);
						jQuery("#circle_center_lng").val(lng);
					}
					if(circle)
					{
						circle.setMap(null);
						
					}
						circle = new google.maps.Circle({
							map:mapcircle,
							center:results[0].geometry.location,
							title:"circle",
							radius:parseInt(circle_radius),
							strokeColor:"#"+circle_line_color,
							strokeOpacity:circle_line_opacity,
							strokeWeight:circle_line_width,
							fillColor:"#"+circle_fill_color,
							fillOpacity:circle_fill_opacity
						})
					google.maps.event.addListener(circlemarker, "drag", function (e) {
						var lat, lng
						lat = circlemarker.getPosition().lat();
						lng = circlemarker.getPosition().lng();
						circle.setCenter(this.getPosition());
						jQuery("#circle_center_lat").val(lat);
						jQuery("#circle_center_lng").val(lng);
						var geocoder = geocoder = new google.maps.Geocoder();
						geocoder.geocode({'latLng':circlemarker.getPosition()},function(results, status){
							if (status == google.maps.GeocoderStatus.OK) {
								address = results[0].formatted_address;
								jQuery("#circle_center_addr").val(address);
							}
						})
					});
				}
				else {
					alert("Geocode was not successful for the following reason: " + status);
				}
			});
		})
	}
	function placeMarker(location){
		if (newmarker){
		 newmarker.setPosition(location);
		} 
		else {
		 newmarker = new google.maps.Marker({
			position: location,
			map: map,
			title: 'New point',
			draggable: true,
			});
		}
		jQuery("#marker_location_lat").val(newmarker.getPosition().lat());
		jQuery("#marker_location_lng").val(newmarker.getPosition().lng());
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({'latLng':newmarker.getPosition()},function(results, status){
			if (status == google.maps.GeocoderStatus.OK) {
				address = results[0].formatted_address;
				jQuery("#marker_location").val(address);
			}

		})
		google.maps.event.addListener(newmarker, "drag", function (e){
			var lat, lng
			lat = newmarker.getPosition().lat();
			lng = newmarker.getPosition().lng();
			jQuery("#marker_location_lat").val(lat);
			jQuery("#marker_location_lng").val(lng);
			var geocoder = geocoder = new google.maps.Geocoder();
			geocoder.geocode({'latLng':newmarker.getPosition()},function(results, status){
				if (status == google.maps.GeocoderStatus.OK) {
					address = results[0].formatted_address;
					jQuery("#marker_location").val(address);
				}
			})
		});
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function placepolyline(location){
		polylinemarker[y] = new google.maps.Marker({
			position:location,
			map: mappolyline,
			title:"#"+q,
			draggable:true,
		});
		google.maps.event.addListener(polylinemarker[y], 'drag' , function(e){
			var position = this.getPosition();
			var title = this.getTitle();
			changepolyline(position,title,newpolyline);
		})
		polylinemarkerloc[q] = polylinemarker[y].getPosition();
		var value = jQuery("#polyline_coords").val();
		if(value == ""){
			jQuery("#polyline_coords").val(value+""+polylinemarkerloc[i])
		}
		else{
			jQuery("#polyline_coords").val(value+";"+polylinemarkerloc[i])
		}
		var split = jQuery("#polyline_coords").val().split(";");
		polylinecoords = [];
		for (i=0;i<split.length;i++)
		{
			var splitlatlng=split[i].split(",");
			var splitlat = splitlatlng[0].replace("(","");
			var splitlng = splitlatlng[1].replace(")","");
			polylinecoords.push(new google.maps.LatLng(splitlat, splitlng));
		}
		if(newpolyline)
		{
			newpolyline.setMap(null);
		}
		
		var polyline_line_color = jQuery("#polyline_line_color").val();
		var polyline_line_opacity = jQuery("#polyline_line_opacity").val();
		var polyline_line_width = jQuery("#polyline_line_width").val();
		newpolyline = new google.maps.Polyline({
			path:polylinecoords,
			map:mappolyline,
			strokeColor:"#"+polyline_line_color,
			strokeOpacity:polyline_line_opacity,
			strokeWeight:polyline_line_width,
		})

///////////////////////////////////CHANGE OPTIONS/////////////////////////////////////////////
		jQuery(".polyline_options_input").on("change",function(){
			if(newpolyline){
				newpolyline.setMap(null);
				var polyline_line_color = jQuery("#polyline_line_color").val();
				var polyline_line_opacity = jQuery("#polyline_line_opacity").val();
				var polyline_line_width = jQuery("#polyline_line_width").val();
				newpolyline = new google.maps.Polyline({
					path:polylinecoords,
					map:mappolyline,
					strokeColor:"#"+polyline_line_color,
					strokeOpacity:polyline_line_opacity,
					strokeWeight:polyline_line_width,
				})
			}
		})
		y++;
		q++;
	}
	//polygon adding
	function placepolygone(location){
		polygonmarker[q] = new google.maps.Marker({
			position: location,
			map: mappolygone,
			title: "#"+t,
			draggable: true,
		});
		google.maps.event.addListener(polygonmarker[q], 'drag' , function(e){
			var position = this.getPosition();
			var title = this.getTitle();
			changePolygone(position,title,newpolygone);
		})
		var polygonmarkerloc = polygonmarker[q].getPosition();
		var value = jQuery("#polygone_coords").val();
		if(value == '')
		{
			jQuery("#polygone_coords").val(value+""+polygonmarkerloc);
		}
		else
		{
			jQuery("#polygone_coords").val(value+";"+polygonmarkerloc);
		}
		coords = [];
		var split = jQuery("#polygone_coords").val().split(";");
		for (i=0;i<split.length;i++)
		{
			var splitlatlng=split[i].split(",");
			var splitlat = splitlatlng[0].replace("(","");
			var splitlng = splitlatlng[1].replace(")","");
			coords.push(new google.maps.LatLng(splitlat, splitlng));
		}
		var polygone_line_color = "#"+jQuery('#polygone_line_color').val();
		var polygone_line_opacity = jQuery('#polygone_line_opacity').val();
		var polygone_fill_color = "#"+jQuery('#polygone_fill_color').val();
		var polygone_fill_opacity = jQuery('#polygone_fill_opacity').val();
		var polygone_line_width = jQuery('#polygone_line_width').val();
		if(newpolygone)
		{
			newpolygone.setPaths(coords);
		}
		else{
			newpolygone = new google.maps.Polygon({
				paths: coords,
				map:mappolygone,
				strokeColor: polygone_line_color,
				strokeOpacity: polygone_line_opacity,
				strokeWeight:polygone_line_width,
				fillColor:polygone_fill_color,
				fillOpacity: polygone_fill_opacity,
			});
		}
///////////////////////////////////////// OPTIONS CHANGED ///////////////////////////////////////////////////////////////
		jQuery(".polygone_options_input").on('change',function(){
			if(newpolygone)
			{
				newpolygone.setMap(null);
				var polygone_line_color = "#"+jQuery('#polygone_line_color').val();
				var polygone_line_opacity = jQuery('#polygone_line_opacity').val();
				var polygone_fill_color = "#"+jQuery('#polygone_fill_color').val();
				var polygone_fill_opacity = jQuery('#polygone_fill_opacity').val();
				var polygone_line_width = jQuery('#polygone_line_width').val();
				newpolygone = new google.maps.Polygon({
					paths: coords,
					map:mappolygone,
					strokeColor: polygone_line_color,
					strokeOpacity: polygone_line_opacity,
					strokeWeight: polygone_line_width,
					fillColor: polygone_fill_color,
					fillOpacity: polygone_fill_opacity,
				});	
			}
		})
		q++;
		t++;
	}
	function changepolyline(position,title,polyline){
		var positionlat = position.lat();
		var positionlng = position.lng();
		var array=jQuery('#polyline_coords').val().split(";");
		var textvalue = "";
		var polylinecoords =[];
		for (u=0;u<array.length;u++){
			if(u == 0){
				if("#"+u == title)
				{
					textvalue = textvalue+""+"("+positionlat+","+positionlng+")";
					polylinecoords.push(position);
				}
				else
				{
					textvalue = textvalue+""+array[u];
					var latlngarray = array[u].split(",");
					var latarray = latlngarray[0].replace("(", "");
					var lngarray = latlngarray[1].replace(")", "");
					polylinecoords.push(new google.maps.LatLng(parseFloat(latarray),
					parseFloat(lngarray)));
				}
			}
			else{
				if("#"+u == title)
				{
					textvalue = textvalue+";"+"("+positionlat+","+positionlng+")";
					polylinecoords.push(position);
				}
				else
				{
					textvalue = textvalue+";"+array[u];
					var latlngarray =array[u].split(",");
					var latarray = latlngarray[0].replace("(", "");
					var lngarray =latlngarray[1].replace(")", "");
					polylinecoords.push(new google.maps.LatLng(parseFloat(latarray),
					parseFloat(lngarray)));
				}
			}	
		}
		polyline.setPath(polylinecoords);
		jQuery('#polyline_coords').val(textvalue);
		jQuery(".polyline_options_input").on("change",function(){
			if(newpolyline){
				newpolyline.setMap(null);
				var polyline_line_color = jQuery("#polyline_line_color").val();
				var polyline_line_opacity = jQuery("#polyline_line_opacity").val();
				var polyline_line_width = jQuery("#polyline_line_width").val();
				newpolyline = new google.maps.Polyline({
					path:polylinecoords,
					map:mappolyline,
					strokeColor:"#"+polyline_line_color,
					strokeOpacity:polyline_line_opacity,
					strokeWeight:polyline_line_width,
				})
			}
		})
	}
	function changePolygone(position,title,polygone)
	{
			var positionlat = position.lat();
			var positionlng = position.lng();
			var array=jQuery('#polygone_coords').val().split(";");
			var textvalue = "";
			var coords =[];
			for (u=0;u<array.length;u++){
				if(u == 0){
					if("#"+u == title)
					{
						textvalue = textvalue+""+"("+positionlat+","+positionlng+")";
						coords.push(position);
					}
					else
					{
						textvalue = textvalue+""+array[u];
						var latlngarray = array[u].split(",");
						var latarray = latlngarray[0].replace("(", "");
						var lngarray = latlngarray[1].replace(")", "");
						coords.push(new google.maps.LatLng(parseFloat(latarray),
						parseFloat(lngarray)));
					}
				}
				else{
					if("#"+u == title)
					{
						textvalue = textvalue+";"+"("+positionlat+","+positionlng+")";
						coords.push(position);
					}
					else
					{
						textvalue = textvalue+";"+array[u];
						var latlngarray =array[u].split(",");
						var latarray = latlngarray[0].replace("(", "");
						var lngarray =latlngarray[1].replace(")", "");
						coords.push(new google.maps.LatLng(parseFloat(latarray),
						parseFloat(lngarray)));
					}
				}	
			}
			polygone.setPaths(coords);
			jQuery('#polygone_coords').val(textvalue);
			jQuery(".polygone_options_input").on('change',function(){
				if(newpolygone)
				{
					newpolygone.setMap(null);
					var polygone_line_color = "#"+jQuery('#polygone_line_color').val();
					var polygone_line_opacity = jQuery('#polygone_line_opacity').val();
					var polygone_fill_color = "#"+jQuery('#polygone_fill_color').val();
					var polygone_fill_opacity = jQuery('#polygone_fill_opacity').val();
					var polygone_line_width = jQuery('#polygone_line_width').val();
					newpolygone = new google.maps.Polygon({
						paths: coords,
						map:mappolygone,
						strokeColor: polygone_line_color,
						strokeOpacity: polygone_line_opacity,
						strokeWeight: polygone_line_width,
						fillColor: polygone_fill_color,
						fillOpacity: polygone_fill_opacity,
					});	
				}
			})
	}
})