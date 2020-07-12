<?php
include("../../mainfile.php");
include("../../header.php");
OpenTable();
?>

<?php


	include("include/common.php");
	include("$config[template_path]/user_top.html");
	
	
	?>
		<table border="<?php echo $style[form_border] ?>" cellspacing="<?php echo $style[form_cellspacing] ?>" cellpadding="<?php echo $style[form_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main" align="center">
			<tr>
				<td valign="top">
					<?php getAllUsersData(); ?>
				</td>
			</tr>
		</table><?
include ("footer.php"); 
?>
<?
CloseTable();
include("../../footer.php");
?>