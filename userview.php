<?php
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/* 		Open-Realty/Auto Modification © RealtyOne outback web creations		 */
/*			Page Based on Open-Realty 1.2.0 Unreleased © RealtyOne			 */
/* 	 Overall content based on Open-Realty © Ryan Bonham transparent tech	 */
/*	This mod and all attached files remain under the Open-Realty gpl Licence */
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
	include("include/common.php");
	include("$config[template_path]/user_top.html");
	?>
	
	
	<table border="<?php echo $style['form_border'] ?>" cellspacing="<?php echo $style['form_cellspacing'] ?>" cellpadding="<?php echo $style['form_cellpadding'] ?>" width="<?php echo $style['admin_table_width'] ?>" class="form_main" align="center">
		<tr>
			<td colspan="2" class="row_main">
				<?php getMainUserData($user) ?>
				
			</td>
		</tr>
		<tr>
			
				
				<?php
				renderUserImages($user)
				?>
			<td class="row_main" valign="top">
			<?php getUserEmail($user) ?>
			<br>
			<?php renderUserInfo($user) ?>
			<br><br>
			<?php userListings($user) ?>
			<?php userautos($user) ?>
			</td>
		</tr>
			<td colspan="2" class="row_main" align="center">
				<?php userHitcount($user) ?>
			</td>
		</tr>
 	</table>
	
	<?php
	include("$config[template_path]/user_bottom.html");
?>