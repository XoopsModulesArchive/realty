<?php
include("../../mainfile.php");
include("../../header.php");
OpenTable();	
include("include/common.php");
include("$config[template_path]/user_top.html");
?>
<table border="<?php echo $style[admin_listing_border] ?>" cellspacing="<?php echo $style[admin_listing_cellspacing] ?>" cellpadding="<?php echo $style[admin_listing_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main">
	<tr>
		<td valign="top">
			<?php renderFeaturedListingsVertical(4); ?>
		</td>
		<td valign="top">
			<h3>Search Listings</h3>

			<p><?php browse_all_listings() ?></p>
			<h4>Find the perfect property:</h4>
			
				<form name="listingsearch" action="./listing_browse.php" method="get">
					<table>
						<?php
							// get the db object in scope
							global $conn;
							// Display The Agent Search
							searchbox_agentdropdown();
							// Get all searchable fields and display them
							$sql = "select search_label, search_type, field_name from " . $config[table_prefix] . "listingsFormElements where searchable = 1 order by rank";
							if (!$rs = $conn->execute ($sql))
							{
								log_error($sql);
							}
							while (!$rs->EOF)
							{
								$searchfunction = 'searchbox_' . $rs->fields['search_type'];
								$searchfunction($rs->fields['search_label'], $rs->fields['field_name']);
								$rs->MoveNext();
							}

						?>
						<tr>
							<td align="center" colspan="2">
								<input type="checkbox" name="imagesOnly" value="yes"> <b>Show only listings with images</b>
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
	</tr>
</table>
<?
include ("footer.php"); 
?>
<?
include("../../footer.php");
CloseTable();
?>	
