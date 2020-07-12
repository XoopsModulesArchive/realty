<?php
include("../../mainfile.php");
include("../../header.php");
OpenTable();	
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/* 		Open-Realty/Auto Modification © RealtyOne outback web creations		 */
/*			Page Based on Open-Realty 1.2.0 Unreleased © RealtyOne			 */
/* 	 Overall content based on Open-Realty © Ryan Bonham transparent tech	 */
/*	This mod and all attached files remain under the Open-Realty gpl Licence */
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
	include("include/common.php");
	include("$config[template_path]/user_top.html");
?>
<table border="<?php echo $style['admin_auto_border'] ?>" cellspacing="<?php echo $style['admin_auto_cellspacing'] ?>" cellpadding="<?php echo $style['admin_auto_cellpadding'] ?>" width="<?php echo $style['admin_table_width'] ?>" class="form_main">
	<tr>
		<td valign="top">
			<?php renderFeaturedListingsVertical(4); ?>
		</td>
		<td valign="top" align="center" width="100%">
			<h3>Search Vehicles</h3>
<!--- refers to actual autos at the moment so comment to hide it. --->
			<p><?php browse_all_autos() ?></p>
			<h4>Find the perfect Vehicle:<br>
			
			
			</h4>
			
				<form name="autosearch" action="<?=$config['baseurl']?>/auto_browse.php" method="get">
					<table>
						<?php
							// get the db object in scope
							global $conn;
							// Display The Agent Search
							searchbox_agentdropdown();
							// Get all searchable fields and display them
							$sql = "select search_label, search_type, field_name from ".$config['table_prefix']."autoformelements where searchable = 1 order by rank";
							if (!$rs = $conn->execute ($sql))
							{
								log_error($sql);
							}
							while (!$rs->EOF)
							{
								$searchfunction = 'autosearch_' . $rs->fields['search_type'];
								$searchfunction($rs->fields['search_label'], $rs->fields['field_name']);
								$rs->MoveNext();
							}
						?>
						<tr>
							<td align="center" colspan="2">
								<input type="checkbox" name="imagesOnly" value="yes"> <b>Show only Vehicles with images</b>
							</td>
						</tr>
						<tr>
							<td align="center" colspan="2">
								<input type="submit">
							</td>
						</tr>
					</table>
				</form>

		</td>
		<td valign="top">
			<?php renderFeaturedAutosVertical(4); ?>
		</td>
	</tr>
</table>

<?
include ("footer.php"); 
?>
<?
include("../../footer.php");
CloseTable();
?>	