<?php
	include("../../mainfile.php");
	include("include/common.php");
	$a = 0;
	$listingID = make_db_extra_safe($listingID);
	$sql = "SELECT ID, caption, description, file_name FROM " . $config[table_prefix] . "vTourImages WHERE (listing_id = $listingID) ORDER BY rank";
	$recordSet = $conn->Execute($sql);
	if ($recordSet === false)
	{
		log_error($sql);
	}
	$num_images = $recordSet->RecordCount();
	if ($num_images > 0)
	{
	$vtinit = 0;
	$vtopts = " <select id=\"tourmenu\" onchange=\"swapTour(this)\"> \n";
	$vtparams = " <param name=\"file\" value=\"ptviewer:$vtinit\"> \n";
		while (!$recordSet->EOF)
		{
			$caption = make_db_unsafe ($recordSet->fields[caption]);
			$description = $conn->qstr(make_db_unsafe($recordSet->fields[description]));
			$file_name = make_db_unsafe ($recordSet->fields[file_name]);
			$imageID = make_db_unsafe ($recordSet->fields[ID]);
			if ($caption == '') 
			{
				$caption = 'Virtual Tour Image '.$a;
			}
			$vtopts .= " <option value=\"$a\">$caption</option> \n";
			$vtparams .= " <param name=\"pano$a\" value=\"{file=$config[listings_view_vTour_path]/$file_name}{auto=0.5}{pan=-45}{fov=95}\"> \n";
			$vtjs .= " tour[$a] = $description; \n";
			$panolist .= "if (n==$a) { document.ptviewer.newPanoFromList($a);}\n";
			$a++;
			$recordSet->MoveNext();
		} // end while
	$vtopts .= " </select> \n";		
	} // end if ($num_images > 0)
?>
<html>
<head>
<title>Virtual Tour</title>
<style type="text/css">
body,table{background-color:#000080;font-family: Tahoma, Verdana, Arial; font-size: 8pt;}
#desc{overflow:auto; width:95%; height:50px; border: 1px inset;}
</style>
<script type="text/javascript">
<!--
inittour = <?php echo $vtinit; ?>;
tour = new Array();
	<?php echo $vtjs; ?>
	
swapTour=function(w) {

        si = w.selectedIndex;
        n = w.options[si].value;
        if (n != -1) {
			newText(n);
			newPano(n);
        }
}

function newPano(n) {
<?php echo $panolist; ?>
}

function newText(n,id){
if(!id){
        id='desc';
}
        if (document.layers){
                x = document.layers[id];
                x.document.open();
                x.document.write(tour[n]);
                x.document.close();
        }else if(document.all){
                x = eval('document.all.' + id);
                x.innerHTML = tour[n];
        }else if (document.getElementById){
                x = document.getElementById(id);
                x.innerHTML = '';
                x.innerHTML = tour[n];
        }
}

-->
</script>
</head>
<body onload="newText(inittour)">
<div align="center">
<table width="330" cellpadding="0" cellspacing="0" border="0">
<tr>
<td align="center" height="235">
<applet archive="ptviewer.jar" code="ptviewer.class" width="380" height="235" name="ptviewer" id="ptviewer" mayscript="TRUE">
<param name="quality" value="3">
<param name="pan" value="180">
<param name="view_height" value="210">
<param name="auto" value="0.4">
<param name="mass" value="20">
<param name="bar_y" value="225">
<param name="bar_x" value="0">
<param name="cursor" value="move">
<param name="frame" value="images/control_bkgd.gif">
<param name="wait" value="images/vtour-load.jpg">
<param name="barcolor" value="FF0000">
<param name="bar_width" value="380">
<param name="shotspot0" value=" x140 y210 a165 b235 u'ptviewer:ZoomIn()' i'images/zoom_in.gif' q- ">
<param name="shotspot1" value=" x165 y210 a190 b235 u'ptviewer:ZoomOut()' i'images/zoom_out.gif' q- ">
<param name="shotspot2" value=" x190 y210 a215 b235 u'ptviewer:' i'images/arrows.gif' q- ">
<param name="shotspot3" value=" x190 y217 a198 b225 u'ptviewer:panLeft()' i'images/left_arrow.gif' p- ">
<param name="shotspot4" value=" x207 y217 a215 b225 u'ptviewer:panRight()' i'images/right_arrow.gif' p- ">
<param name="shotspot5" value=" x197 y210 a205 b218 u'ptviewer:panUp()' i'images/up_arrow.gif' p- ">
<param name="shotspot6" value=" x197 y226 a205 b234 u'ptviewer:panDown()' i'images/down_arrow.gif' p- ">
<param name="shotspot7" value=" x215 y210 a240 b235 u'ptviewer:startAutoPan(0.4,0,1)' i'images/refresh.gif' q- ">
<?php echo $vtparams; ?>
</applet></td>
</tr>
<tr>
<form>
<td background="images/control_bkgd.gif" align="center">
<br />
<?php echo $vtopts; ?>	
<br />
<div id="desc"></div>
<br />
<br />
</td>
</form>
</tr>
</table>
</div>
</body>
</html>