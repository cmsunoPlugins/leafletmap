<?php
session_start(); 
if(!isset($_POST['unox']) || $_POST['unox']!=$_SESSION['unox']) {sleep(2);exit;} // appel depuis uno.php
?>
<?php
include('../../config.php');
include('lang/lang.php');
if(isset($_POST['ubusy'])) $busy = preg_replace("/[^A-Za-z0-9-_]/",'',$_POST['ubusy']);
else { $q1 = file_get_contents('../../data/busy.json'); $a1 = json_decode($q1,true); $busy = $a1['nom']; }
// ********************* actions *************************************************************************
if(isset($_POST['action'])) {
	switch ($_POST['action']) {
		// ********************************************************************************************
		case 'plugin': ?>
		<link rel="stylesheet" type="text/css" media="screen" href="uno/plugins/leafletmap/leafletmap.css" />
		<div class="blocForm">
			<h2>Leafletmap</h2>
			<p><?php echo T_("This plugin allows you to add OpenStreetMap maps to your site to view a location or a GPX track."); ?></p>
			<p><?php echo T_("Just insert the code"); ?>&nbsp;<code>[[leafletmap-<?php echo T_("nameofthemap"); ?>]]</code>&nbsp;<?php echo T_("in the template or in the page content."); ?></p>
			<p><?php echo T_("You can create as many map as you need."); ?></p>
			<h3><?php echo T_("Add a leafletmap :"); ?></h3>
			<table class="hForm">
				<tr>
					<td><label><?php echo T_("Name"); ?></label></td>
					<td><input type="text" class="input" name="leafletmapName" id="leafletmapName" style="width:150px;" value="" /></td>
					<td><em><?php echo T_("Name of the leafletmap");?></em></td>
				</tr>
				<tr>
					<td><label><?php echo T_("Type");?></label></td>
					<td>
						<select name="leafletmapTyp" id="leafletmapTyp" style="max-width:200px" onChange="f_type_leafletmap(this)">
							<option value="0"><?php echo T_("A location");?></option>
							<option value="1"><?php echo T_("A GPX track");?></option>
						</select>
						<data id="leafletmapTyp0" value="<?php echo T_("A location");?>"></data>
						<data id="leafletmapTyp1" value="<?php echo T_("A GPX track");?>"></data>
					</td>
					<td><em><?php echo T_("What kind of content I want to display on this map.");?></em></td>
				</tr>
				<tr>
					<td><label><?php echo T_("Height"); ?></label></td>
					<td><input type="number" class="input" name="leafletmapHei" id="leafletmapHei" style="width:50px;" value="300" /></td>
					<td><em><?php echo T_("Height of the map in pixels. The width will be 100%. Default: 300px");?></em></td>
				</tr>
				<tr class="loc">
					<td><label><?php echo T_("Zoom level");?></label></td>
					<td><input type="number" class="input" name="leafletmapZoo" id="leafletmapZoo" style="width:50px;" min="0" max="18" value="13" /></td>
					<td><em><?php echo T_("Map scale from 0 (world) to 18 (hamlet). Default: 13");?></em></td>
				</tr>
				<tr class="loc">
					<td><label><?php echo T_("GPS location");?></label></td>
					<td>
						<input type="text" class="input" name="leafletmapLat" id="leafletmapLat" style="width:80px;" placeholder="LAT" />
						<input type="text" class="input" name="leafletmapLon" id="leafletmapLon" style="width:80px;" placeholder="LON" />
					</td>
					<td><em><?php echo T_("GPS position of the center of the map. Format: +/- decimal. Ex: -4.123");?></em></td>
				</tr>
				<tr class="loc">
					<td><label><?php echo T_("Label");?></label></td>
					<td><input type="text" class="input" name="leafletmapMar" id="leafletmapMar" style="width:180px;" /></td>
					<td><em><?php echo T_("Label for the marker on this position. No marker if it is empty.");?></em></td>
				</tr>
				<tr class="gpx" style="display:none;">
					<td><label><?php echo T_("GPX File");?></label></td>
					<td>
						<input type="text" class="input" name="leafletmapFil" id="leafletmapFil" style="width:180px;" />
						<div class="bouton finder" style="margin-left:30px;" id="leafletmapFin" onClick="f_finder_select('leafletmapFil')" title="<?php echo T_("File manager");?>">
							<img src="<?php echo $_POST['udep']; ?>includes/img/finder.png" />
						</div>
					</td>
					<td><em><?php echo T_("Select the GPX file in your file manager.");?></em></td>
				</tr>
			</table>
			<div class="bouton fr" onClick="f_save_leafletmap();" title="<?php echo T_("Add the leafletmap"); ?>"><?php echo T_("Add"); ?></div>
			<div class="clear"></div>
			<h3><?php echo T_("Existing leafletmap :"); ?></h3>
			<form id="frmLeafletmap">
				<table id="curLeafletmap"></table>
			</form>
		</div>
		<?php break;
		// ********************************************************************************************
		case 'save':
		if(file_exists('../../data/'.$busy.'/leafletmap.json')) {
			$q = file_get_contents('../../data/'.$busy.'/leafletmap.json');
			if($q) $a = json_decode($q, true);
		}
		else $a = array();
		$b = preg_replace("/[^a-zA-Z0-9]+/", "", $_POST['name']);
		if(!$b){
			echo '!'.T_('Impossible backup');
			exit;
		}
		$a[$b] = array();
		$a[$b]['hei'] = intval($_POST['hei']);
		if(empty($_POST['typ'])) {
			$a[$b]['typ'] = 'loc';
			$a[$b]['zoo'] = intval($_POST['zoo']);
			$a[$b]['lat'] = filter_var($_POST['lat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
			$a[$b]['lon'] = filter_var($_POST['lon'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
			$a[$b]['mar'] = strip_tags($_POST['mar']);
		}
		else {
			$a[$b]['typ'] = 'gpx';
			$a[$b]['fil'] = filter_var($_POST['fil'], FILTER_SANITIZE_URL);
		}
		$out = json_encode($a);
		if(file_put_contents('../../data/'.$busy.'/leafletmap.json', $out)) echo T_('Backup performed');
		else echo '!'.T_('Impossible backup');
		break;
		// ********************************************************************************************
		case 'del':
		if(file_exists('../../data/'.$busy.'/leafletmap.json')) {
			$q = file_get_contents('../../data/'.$busy.'/leafletmap.json');
			if($q) $a = json_decode($q, true);
			$b = preg_replace("/[^a-zA-Z0-9]+/", "", $_POST['name']);
			if(isset($a[$b])) {
				unset($a[$b]);
				$out = json_encode($a);
				if(file_put_contents('../../data/'.$busy.'/leafletmap.json', $out)) {
					echo T_('Backup performed');
					exit;
				}
			}
		}
		echo '!'.T_('Impossible backup');
		break;
		// ********************************************************************************************
	}
	clearstatcache();
	exit;
}
?>
