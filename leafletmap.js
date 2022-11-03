//
// CMSUno
// Plugin LeafletMap
//
function f_save_leafletmap(){
	var h=[],c=document.getElementById('leafletmapTyp');
	h.push({name:'action',value:'save'});
	h.push({name:'unox',value:Unox});
	h.push({name:'name',value:document.getElementById('leafletmapName').value});
	h.push({name:'hei',value:document.getElementById('leafletmapHei').value});
	h.push({name:'typ',value:c.options[c.selectedIndex].value});
	h.push({name:'zoo',value:document.getElementById('leafletmapZoo').value});
	h.push({name:'lat',value:document.getElementById('leafletmapLat').value});
	h.push({name:'lon',value:document.getElementById('leafletmapLon').value});
	h.push({name:'mar',value:document.getElementById('leafletmapMar').value});
	h.push({name:'fil',value:document.getElementById('leafletmapFil').value});
	jQuery.post('uno/plugins/leafletmap/leafletmap.php',h,function(r){
		f_alert(r);
		f_load_leafletmap();
	});
}
//
function f_load_leafletmap(){
	jQuery(document).ready(function(){
		jQuery('#curLeafletmap').empty();
		jQuery.getJSON("uno/data/"+Ubusy+"/leafletmap.json?r="+Math.random(),function(data){
			jQuery.each(data,function(k,d){
				var o='<td style="text-align:center;padding:10px;"><strong>'+k+'</strong><br />[[leafletmap-'+k+']]</td>';
				if(d.typ=='loc'){
					o+='<td>'+document.getElementById('leafletmapTyp0').value+'</td>';
					o+='<td>Height: '+d.hei+'px<br />Zoom: '+d.zoo+'<br />Lat: '+d.lat+'<br />Lon: '+d.lon+'<br />Label: '+d.mar+'</td>';
				}else{
					o+='<td>'+document.getElementById('leafletmapTyp1').value+'</td>';
					o+='<td>Height: '+d.hei+'px<br />GPX: '+d.fil+'</td>';
				}
				o+='<td width="30px" style="cursor:pointer;background:transparent url(\''+Udep+'includes/img/close.png\') no-repeat scroll center center;" onClick="f_del_leafletmap(\''+k+'\');"></td>';
				jQuery('#curLeafletmap').append('<tr>'+o+'</tr>');
			});
		});
	});
}
//
function f_del_leafletmap(f){
	jQuery.post('uno/plugins/leafletmap/leafletmap.php',
		{'action':'del','unox':Unox,
		'name':f
		},function(r){
			f_alert(r);
			f_load_leafletmap();
		}
	);
}
//
function f_type_leafletmap(f){
	if(f.options[f.selectedIndex].value=='0'){
		jQuery('.loc').show();
		jQuery('.gpx').hide();
	}
	else {
		jQuery('.loc').hide();
		jQuery('.gpx').show();
	}
}
//
f_load_leafletmap();
