<?php
include("../../mainfile.php");
include("../../header.php");
OpenTable();
?>

<?php

	include("include/common.php");
include("$config[template_path]/user_top.html");
	?>
		<table border="<?php echo $style[admin_listing_border] ?>" cellspacing="<?php echo $style[admin_listing_cellspacing] ?>" cellpadding="<?php echo $style[admin_listing_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main">
			<tr>
				<!--<td valign="top">
					<?php 
					//renderFeaturedListingsVertical(4);
					?>
				</td>-->
				<td>
					<font size="4"><b>Legal Disclaimer</b></font><br>
					<br>
					<b><i>Information Not Warranted or Guaranteed:</i></b><br>
					The official <?php echo "$config[company_name]" ?> website and all pages linked to it or from it, are PROVIDED ON AN "AS IS, AS AVAILABLE" BASIS.  <span style="text-transform: uppercase"><?php echo "$config[company_name]" ?></span> MAKES NO WARRANTIES, EXPRESSED OR IMPLIED, INCLUDING, WITHOUT LIMITATION, THOSE OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE, WITH RESPECT TO ANY INFORMATION OR USE OF INFORMATION CONTAINED IN THE WEBSITE, OR LINKED FROM IT OR TO IT.<br>
					<br>
					<?php echo "$config[company_name]" ?> does not warrant or guarantee the accuracy, adequacy, quality, currentness, completeness, or suitability of any information for any purpose; that any information will be free of infection from viruses, worms, Trojan horses or other destructive contamination; that the information presented will not be objectionable to some individuals or that this service will remain uninterrupted.<br>
					<br>
					<i><b>No Liability:</b></i><br>
					<?php echo "$config[company_name]" ?>, its agents or employees shall not be held liable to anyone for any errors, omissions or inaccuracies under any circumstances.  The entire risk for utilizing the information contained on this site or linked to this site rests solely with the users of this site.


				</td>
			</tr>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber2" height="167">
			<tr>
				<td width="100%" valign="top" height="5">
					&nbsp;
				</td>
			</tr>
		</table><br><br><br>

<?
include "footer.php";?> 
	<?
CloseTable();
include("../../footer.php");
?>
