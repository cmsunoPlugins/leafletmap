//
// CMSUno
// Plugin LeafletMap
//
function f_save_leafletmap(){
	let c=document.getElementById('leafletmapTyp'),x=new FormData();
	x.set('action','save');
	x.set('unox',Unox);
	x.set('name',document.getElementById('leafletmapName').value);
	x.set('hei',document.getElementById('leafletmapHei').value);
	x.set('typ',c.options[c.selectedIndex].value);
	x.set('zoo',document.getElementById('leafletmapZoo').value);
	x.set('lat',document.getElementById('leafletmapLat').value);
	x.set('lon',document.getElementById('leafletmapLon').value);
	x.set('mar',document.getElementById('leafletmapMar').value);
	x.set('fil',document.getElementById('leafletmapFil').value);
	fetch('uno/plugins/leafletmap/leafletmap.php',{method:'post',body:x})
	.then(r=>r.text())
	.then(function(r){
		f_alert(r);
		f_load_leafletmap();
	});
}
//
function f_load_leafletmap(){
	document.getElementById('curLeafletmap').innerHTML='';
	fetch("uno/data/"+Ubusy+"/leafletmap.json?r="+Math.random())
	.then(r=>r.json())
	.then(function(data){
		for(k in data){
			let d=data[k],o;
			o='<td style="text-align:center;padding:10px;"><strong>'+k+'</strong><br />[[leafletmap-'+k+']]</td>';
			if(d.typ=='loc'){
				o+='<td>'+document.getElementById('leafletmapTyp0').value+'</td>';
				o+='<td>Height: '+d.hei+'px<br />Zoom: '+d.zoo+'<br />Lat: '+d.lat+'<br />Lon: '+d.lon+'<br />Label: '+d.mar+'</td>';
			}else{
				o+='<td>'+document.getElementById('leafletmapTyp1').value+'</td>';
				o+='<td>Height: '+d.hei+'px<br />GPX: '+d.fil+'</td>';
			}
			o+='<td width="30px" style="cursor:pointer;background:transparent url(\''+Udep+'includes/img/close.png\') no-repeat scroll center center;" onClick="f_del_leafletmap(\''+k+'\');"></td>';
			document.getElementById('curLeafletmap').insertAdjacentHTML('beforeend','<tr>'+o+'</tr>');
		}
	});
}
//
function f_del_leafletmap(f){
	let x=new FormData();
	x.set('action','del');
	x.set('unox',Unox);
	x.set('name',f);
	fetch('uno/plugins/leafletmap/leafletmap.php',{method:'post',body:x})
	.then(r=>r.text())
	.then(function(r){
		f_alert(r);
		f_load_leafletmap();
	});
}
//
function f_type_leafletmap(f){
	let a;
	if(f.options[f.selectedIndex].value=='0'){
		document.querySelectorAll(".loc").forEach(a=>a.style.display='table-row');
		document.querySelectorAll(".gpx").forEach(a=>a.style.display='none');
	}
	else {
		document.querySelectorAll(".loc").forEach(a=>a.style.display='none');
		document.querySelectorAll(".gpx").forEach(a=>a.style.display='table-row');
	}
}
//
f_load_leafletmap();
