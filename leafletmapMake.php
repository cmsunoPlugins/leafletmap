<?php
if(!isset($_SESSION['cmsuno'])) exit();
?>
<?php // https://leafletjs.com/examples/quick-start/
if(file_exists('data/'.$Ubusy.'/leafletmap.json')) {
	$b = '';
	$c = '.leaflet-pane,.leaflet-top,.leaflet-bottom{z-index:9}';
	$q1 = file_get_contents('data/'.$Ubusy.'/leafletmap.json');
	$a1 = json_decode($q1,true);
	if(is_array($a1)) foreach($a1 as $k=>$v) {
		$d = '<div id="map'.$k.'"></div>';
		$Uhtml = str_replace('[[leafletmap-'.$k.']]', $d, $Uhtml);
		$Ucontent = str_replace('[[leafletmap-'.$k.']]', $d, $Ucontent);
		if($v['typ']=='gpx') {
			$b .= "var map".$k."=L.map('map".$k."');";
			$b .= "var gpx".$k."='".$v['fil']."';";
			$b .= "new L.GPX(gpx".$k.",{async:true,slope:true,marker_options:{startIconUrl:'uno/plugins/leafletmap/leaflet-gpx/pin-icon-start.png',endIconUrl:'uno/plugins/leafletmap/leaflet-gpx/pin-icon-end.png',wptIconUrl:'uno/plugins/leafletmap/leaflet-gpx/marker.png'},";
			$b .= "polyline_options:{color:'blue',opacity: 0.75,}";
			$b .= "}).on('loaded',function(e){map".$k.".fitBounds(e.target.getBounds());}).addTo(map".$k.");";
		}
		else if($v['typ']=='loc') {
			$b .= "var map".$k."=L.map('map".$k."').setView([".$v['lat'].",".$v['lon']."],".$v['zoo'].");";
			$b .= "L.marker([".$v['lat'].",".$v['lon']."]).addTo(map".$k.").bindPopup('".$v['mar']."').openPopup();";
		}
		$b .= "L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19,attribution:'&copy;<a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap</a>'}).addTo(map".$k.");"."\r\n";
		$c .= '#map'.$k.'{height:'.$v['hei'].'px;}';
	}
	if($b) {
		$Uhead .= '<link rel="stylesheet" href="uno/plugins/leafletmap/leaflet/leaflet.css" type="text/css" />'."\r\n";
		$Ufoot .= '<script src="uno/plugins/leafletmap/leaflet/leaflet.js"></script>'."\r\n";
	//	$Ufoot .= '<script src="uno/plugins/leafletmap/leaflet-gpx/gpx.min.js"></script>'."\r\n";
		$Ufoot .= '<script src="uno/plugins/leafletmap/leaflet-gpx/gpx.js"></script>'."\r\n";
		$Ufoot .= '<script>'.$b.'</script>'."\r\n";
		$Ustyle .= $c."\r\n";
	}
}
?>
