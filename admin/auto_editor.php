<?php
include("admin_header.php");
xoops_cp_header();
?>
<?php
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/* 		Open-Realty/Auto Modification © RealtyOne outback web creations		 */
/*			Page Based on Open-Realty 1.2.0 Unreleased © RealtyOne			 */
/* 	 Overall content based on Open-Realty © Ryan Bonham transparent tech	 */
/*	This mod and all attached files remain under the Open-Realty gpl Licence */
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
	global $action, $id, $conn, $lang, $config;
	include("../include/common.php");
	loginCheck('canEditForms');


	include("$config[template_path]/admin_top.html");

	// Check for update of an existing element
	if ($action == "updateAutosFormElement")
	{
		$id = $_REQUEST['id'];
		$sql_field_type = make_db_safe($_REQUEST['field_type']);
		$sql_field_name = make_db_safe($_REQUEST['field_name']);
		$sql_field_caption = make_db_safe($_REQUEST['field_caption']);
		$sql_default_text = make_db_safe($_REQUEST['default_text']);
		$sql_field_elements = make_db_safe($_REQUEST['field_elements']);
		$sql_rank = make_db_safe($_REQUEST['rank']);
		$sql_required = make_db_safe($_REQUEST['required']);
		$sql_location = make_db_safe($_REQUEST['location']);
		$sql_display_on_browse = make_db_safe($_REQUEST['display_on_browse']);
		$sql_searchable = make_db_safe($_REQUEST['searchable']);
		$sql_search_type = make_db_safe($_REQUEST['search_type']);
		$sql_search_label = make_db_safe($_REQUEST['search_label']);
		$sql_search_step = make_db_safe($_REQUEST['search_step']);

		$sql = "UPDATE ".$config['table_prefix']."autoformelements SET field_type = $sql_field_type, ";
		$sql .= "field_name = $sql_field_name, ";
		$sql .= "field_caption = $sql_field_caption, ";
		$sql .= "default_text = $sql_default_text, ";
		$sql .= "field_elements = $sql_field_elements, ";
		$sql .= "rank = $sql_rank, ";
		$sql .= "required = $sql_required, ";
		$sql .= "location = $sql_location, ";
		$sql .= "searchable = $sql_searchable, ";
		$sql .= "search_type = $sql_search_type, ";
		$sql .= "search_label = $sql_search_label, ";
		$sql .= "search_step = $sql_search_step, ";
		$sql .= "display_on_browse = $sql_display_on_browse ";
		$sql .= "WHERE id = $id";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		log_action ("$lang[log_updated_auto_form_element]: $id");
		echo "<p>$lang[admin_template_editor_autos_form_element] '$id' $lang[has_been_updated]</p>";
	} // end if $action == "updateAutosFormElement"

	// Check for deletion of an element
	if ($action == "DeleteUserFormElement")
	{
		$sql_id = make_db_safe($id);
		$sql = "DELETE FROM ".$config['table_prefix']."autoformelements where id = $id";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		log_action ("$lang[log_deleted_auto_form_element]: $id");
		Print "$lang[admin_template_editor_autos_form_element] '$id' $lang[has_been_deleted]";
	} // $action == "DeleteUserFormElement"

	// Check for addition of a new element
	if ($action == "makeNewElement")
	{
		$sql_field_type = make_db_safe($field_type);
		$sql_field_name = make_db_safe($field_name);
		$sql_field_caption = make_db_safe($field_caption);
		$sql_default_text = make_db_safe($default_text);
		$sql_field_elements = make_db_safe($field_elements);
		$sql_required = make_db_safe($required);
		$sql_location = make_db_safe($location);
		$sql_display_on_browse = make_db_safe($display_on_browse);
		$sql_rank = make_db_safe($rank);

		$sql = "INSERT INTO ".$config['table_prefix']."autoformelements ";
		$sql .= "(field_type, field_name, field_caption, default_text, field_elements, rank, required, location, display_on_browse) VALUES ";
		$sql .= "($sql_field_type, $sql_field_name, $sql_field_caption, $sql_default_text, $sql_field_elements, $sql_rank, $sql_required, $sql_location, $sql_display_on_browse)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		log_action ("$lang[log_made_new_auto_form_element]: $field_name");
		echo "<P>$lang[admin_template_editor_new_element_created]";

	} // $action == "makeNewElement"

	?>
		<table border="<?php echo $style['form_border'] ?>" cellspacing="<?php echo $style['form_cellspacing'] ?>" cellpadding="<?php echo $style['form_cellpadding'] ?>" width="<?php echo $style['admin_table_width'] ?>" class="form_main">

			<tr><td colspan="2" class="row_main">

			<h3><?php echo $lang['admin_template_editor_listings_name'] ?></h3>
			</td></tr>
			<form action="<?php $php_self ?>" method="post">
				<input type="hidden" name="action" value="makeNewElement">

				<tr><td colspan="2" class="templateEditorHead"><?php echo $lang['admin_template_editor_add_a_new_form_item'] ?>:</td></tr>
				<tr>

					<td align="right" class="templateEditorNew" valign="top"><b><?php echo $lang['admin_template_editor_field_name'] ?>:</b></td>
					<td class="templateEditorNew"><input type=text name="field_name" value =""></td>
				</tr>

				<tr>
					<td align="right" class="templateEditorNew" valign="top"><b><?php echo $lang['admin_template_editor_field_type'] ?>:</b></td>
					<td class="templateEditorNew">
					<select name="field_type" size="1">
						<OPTION VALUE="text" SELECTED="SELECTED">Text</OPTION>
						<OPTION VALUE="textarea" >Textarea</OPTION>
						<OPTION VALUE="select" >Select List</OPTION>
						<OPTION VALUE="select-multiple">Select Multiple</OPTION>
						<OPTION VALUE="option" >Option Box</OPTION>
						<OPTION VALUE="checkbox" >Check Box</OPTION>
						<OPTION VALUE="divider">Divider</option>
						<OPTION VALUE="">-----</OPTION>
						<OPTION VALUE="price">Price</option>
						<OPTION VALUE="url">URL</option>
						<OPTION VALUE="email">Email</option>
						<OPTION VALUE="number">Number</option>
						<OPTION VALUE="date">Date</option>
					</select>
					</td>
				</tr>

				<tr>
					<td align="right" class="templateEditorNew" valign="top"><b><?php echo $lang['admin_template_editor_field_required'] ?>:</b></td>
					<td class="templateEditorNew">
					<select name="required" size="1">
						<OPTION VALUE="No" SELECTED="SELECTED">No</OPTION>
						<OPTION VALUE="Yes" >Yes</OPTION>
					</select>


					</td>
				</tr>

				<tr>
					<td align="right" class="templateEditorNew" valign="top"><b><?php echo $lang['admin_template_editor_field_caption'] ?>:</b></td>
					<td class="templateEditorNew"><input type="index" name="field_caption" value = ""></td>
				</tr>


				<tr>
					<td align="right" class="templateEditorNew" valign="top"><b><?php echo $lang['admin_template_editor_field_elements'] ?>:</b><BR><font size=1>(<?php echo $lang['admin_template_editor_choices_separated'] ?>)</font></td>
					<td class="templateEditorNew"><input type=text name="field_elements" value = ""></td>
				</tr>


				<tr>
					<td align="right" class="templateEditorNew" valign="top"><b><?php echo $lang['admin_template_editor_field_default_text'] ?>:</b></td>
					<td class="templateEditorNew"><input type=text name="default_text" value = ""></td>
				</tr>

				<tr>
					<td align="right" bgcolor="EEEEFF"valign="top"><b><?php echo $lang['admin_template_editor_field_display_location'] ?>:</b></td>
					<td class="templateEditorNew"align="left">
					<select name="location" size="1">
						<OPTION VALUE="top_right">top_right</OPTION>
						<OPTION VALUE="top_left" >top_left</OPTION>
						<OPTION VALUE="bottom_right" >bottom_right</OPTION>
						<OPTION VALUE="bottom_left">bottom_left</option>
						<OPTION VALUE="headline">headline</option>
						<OPTION VALUE="center">center</option>
						<OPTION VALUE="feature1">feature1</option>
						<OPTION VALUE="feature2">feature2</option>
					</select>
					</td>
				</tr>

				<tr>
					<td align="right" class="templateEditorNew" valign="top"><b><?php echo $lang['admin_template_editor_field_display_browse'] ?>:</b></td>
					<td class="templateEditorNew">
					<select name="display_on_browse" size="1">
						<OPTION VALUE="No" SELECTED="SELECTED">No</OPTION>
						<OPTION VALUE="Yes" >Yes</OPTION>
					</select>


					</td>
				</tr>

				<tr>
					<td align="right" class="templateEditorNew" valign="top"><b><?php echo $lang['admin_template_editor_field_rank'] ?>:</b></td>
					<td class="templateEditorNew"><input type=text name="rank" value = "5"></td>
				</tr>

				<tr>
					<td colspan="2" align="center" class="templateEditorNew" valign="top"><hr><B>Search Options</b></td>
				</tr>
				<tr>
					<td align="right" class="templateEditorNew" valign="top"><b>Allow Searching:</b></td>
					<td class="templateEditorNew"><input type="checkbox" name="searchable" value = "1"></td>
				</tr>
				<tr>
					<td align="right" class="templateEditorNew" valign="top"><b>Search Type:</b></td>
					<td class="templateEditorNew">
						<select name="search_type">
						<option></option>
						<option value="optionlist">Option list of individual values</option>
						<option value="fcheckbox">CheckBox list of individual values</option>
						<option value="fpulldown">Pull down list of individual values</option>
						<option value="select">distinct list - allow multiple</option>
						<option value="pulldown">Pull down menu, 1 selection only</option>
						<option value="checkbox">Checkbox list of distinct values</option>
						<option value="option">Radio button list of distinct values</option>
						<option value="minmax">Two pull downs for min/max ++</option>
						<option value="daterange">Date range; two text fields</option>
						</select><BR><font size="1">
						** e.g. price >= 100000
					</td>
				<tr>
					<td align="right" class="templateEditorNew" valign="top"><font size="1">++ </font><b>Step by:</b></td>
					<td class="templateEditorNew"><input type="text" name="search_step" value = "">
					<BR><font size="1">++ Used for range selections only</font>
					</td>
				</tr>
				<tr>
					<td align="right" class="templateEditorNew" valign="top"><b>Search label:</b></td>
					<td class="templateEditorNew"><input type="text" name="search_label" value = ""></td>
				</tr>

				</tr>


			<tr>
				<td align="left" class="templateEditorNew" valign="top">&nbsp;<input type="submit" name="Submit" value="<?php echo $lang['admin_template_editor_add_to_form_button'] ?>"></td>
				<td class="templateEditorNew">&nbsp;</td>
			</tr>
			</form>



			<tr height="15"><td colspan="2" height="15">&nbsp;</td></tr>
			<tr><td colspan="2" class="templateEditorHead"><?php echo $lang['admin_template_editor_form_builder'] ?>:</td></tr>

		<?php

		// output the rest of the elements
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$sql = "SELECT *, ID as id FROM ".$config['table_prefix']."autoformelements ORDER BY rank, field_name";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$count = 0;
		while (!$recordSet->EOF)
		{
			$count++;
			$id = $recordSet->fields['ID'];
			$field_type = make_db_unsafe($recordSet->fields['field_type']);
			$field_name = make_db_unsafe($recordSet->fields['field_name']);
			$field_caption = make_db_unsafe($recordSet->fields['field_caption']);
			$default_text = make_db_unsafe($recordSet->fields['default_text']);
			$field_elements = make_db_unsafe($recordSet->fields['field_elements']);
			$rank = $recordSet->fields['rank'];
			$required = $recordSet->fields['required'];
			$location = make_db_unsafe($recordSet->fields['location']);
			$display_on_browse = $recordSet->fields['display_on_browse'];
			$search_step = make_db_unsafe($recordSet->fields['search_step']);
			$searchable = make_db_unsafe($recordSet->fields['searchable']);
			$search_label = make_db_unsafe($recordSet->fields['search_label']);
			$search_type = make_db_unsafe($recordSet->fields['search_type']);
			?>
				<form action="<?php echo $PHP_SELF ?>" method="post">
				<input type="hidden" name="action" value="updateAutosFormElement">
				<input type="hidden" name="id" value="<?php echo $id ?>">

				<tr><td colspan=2 class="row_main" align="left"><?php echo $lang['admin_template_editor_field'] ?>: <?php echo $count ?></td></tr>
				<tr>
					<td align="right" class="templateEditorHead" valign="top"><b><?php echo $lang['admin_template_editor_field_name'] ?>:</b></td>
					<td class="templateEditorHead" align="left"><input type=text name="field_name" value="<?php echo $field_name ?>"></td>
				</tr>

				<tr>
					<td align="right" class="templateEditorHead" valign="top"><b><?php echo $lang['admin_template_editor_field_type'] ?>:</b></td>
					<td class="templateEditorHead" align="left">
					<select name="field_type" size="1">
						<OPTION VALUE="<?php echo $field_type ?>" SELECTED><?php echo $field_type ?></OPTION>
						<OPTION VALUE="">-----</OPTION>
						<OPTION VALUE="text">Text</OPTION>
						<OPTION VALUE="textarea" >Textarea</OPTION>
						<OPTION VALUE="select" >Select List</OPTION>
						<OPTION VALUE="select-multiple">Select Multiple</OPTION>
						<OPTION VALUE="option" >Option Box</OPTION>
						<OPTION VALUE="checkbox" >Check Box</OPTION>
						<OPTION VALUE="divider">Divider</option>
						<OPTION VALUE="">-----</OPTION>
						<OPTION VALUE="price">Price</option>
						<OPTION VALUE="url">URL</option>
						<OPTION VALUE="email">Email</option>
						<OPTION VALUE="number">Number</option>
						<OPTION VALUE="date">Date</option>
					</select>
					</td>
				</tr>

				<tr>
					<td align="right" class="templateEditorHead" valign="top"><b><?php echo $lang['admin_template_editor_field_required'] ?>:</b></td>
					<td class="templateEditorHead" align="left">
					<select name="required" size="1">
						<OPTION VALUE="<?php echo $required ?>" SELECTED><?php echo $required ?></OPTION>
						<OPTION VALUE="">-----</OPTION>
						<OPTION VALUE="No">No</OPTION>
						<OPTION VALUE="Yes" >Yes</OPTION>
					</select>


					</td>
				</tr>



				<tr>
					<td align="right" class="templateEditorHead" valign="top"><b><?php echo $lang['admin_template_editor_field_caption'] ?>:</b></td>
					<td class="templateEditorHead" align="left"><input type=text name="field_caption" value = "<?php echo $field_caption ?>"></td>
				</tr>

				<tr>
					<td align="right" class="templateEditorHead" valign="top"><b><?php echo $lang['admin_template_editor_field_elements'] ?>:</b><BR><div class="small">(<?php echo $lang['admin_template_editor_choices_separated'] ?>)</div></td>
					<td class="templateEditorHead" align="left"><input type=text name="field_elements" value = "<?php echo $field_elements ?>"></td>
				</tr>

				<tr>
					<td align="right" class="templateEditorHead" valign="top"><b><?php echo $lang['admin_template_editor_field_default_text'] ?>:</b></td>
					<td class="templateEditorHead" align="left"><input type=text name="default_text" value = "<?php echo $default_text ?>"></td>
				</tr>

				<tr>
					<td align="right" class="templateEditorHead" valign="top"><b><?php echo $lang['admin_template_editor_field_display_location'] ?>:</b></td>
					<td class="templateEditorHead" align="left">
					<select name="location" size="1">
						<OPTION VALUE="<?php echo $location ?>" SELECTED><?php echo $location ?></OPTION>
						<OPTION VALUE="">-----</OPTION>
						<OPTION VALUE="top_right">top_right</OPTION>
						<OPTION VALUE="top_left" >top_left</OPTION>
						<OPTION VALUE="bottom_right" >bottom_right</OPTION>
						<OPTION VALUE="bottom_left">bottom_left</option>
						<OPTION VALUE="headline">headline</option>
						<OPTION VALUE="center">center</option>
						<OPTION VALUE="feature1">feature1</option>
						<OPTION VALUE="feature2">feature2</option>
					</select>
					</td>
				</tr>

				<tr>
					<td align="right" class="templateEditorHead" valign="top"><b><?php echo $lang['admin_template_editor_field_display_browse'] ?>:</b></td>
					<td class="templateEditorHead" align="left">
					<select name="display_on_browse" size="1">
						<OPTION VALUE="<?php echo $display_on_browse ?>" SELECTED><?php echo $display_on_browse ?></OPTION>
						<OPTION VALUE="">-----</OPTION>
						<OPTION VALUE="No">No</OPTION>
						<OPTION VALUE="Yes" >Yes</OPTION>
					</select>


					</td>
				</tr>

				<tr>
					<td align="right" class="templateEditorHead" valign="top"><b><?php echo $lang['admin_template_editor_field_rank'] ?>:</b></td>
					<td class="templateEditorHead" align="left" ><input type=text name="rank" value = "<?php print $rank ?>"></td>
				</tr>

				<tr>
					<td colspan="2" align="center" class="templateEditorNew" valign="top"><hr><B>Search Options</b></td>
				</tr>
				<tr>
					<td align="right" class="templateEditorNew" valign="top"><b>Allow Searching:</b></td>
					<td class="templateEditorNew"><input type="checkbox" name="searchable" value = "1" <?php if ($searchable)  {echo 'CHECKED';} ?>></td>
				</tr>
				<tr>
					<td align="right" class="templateEditorNew" valign="top"><b>Search Type:</b></td>
					<td class="templateEditorNew">
						<select name="search_type">
						<option><?=$search_type;?></option>
						<option></option>
						<option value="optionlist">Option list of individual values</option>
						<option value="fcheckbox">CheckBox list of individual values</option>
						<option value="fpulldown">Pull down list of individual values</option>
						<option value="select">distinct list - allow multiple</option>
						<option value="pulldown">Pull down menu, 1 selection only</option>
						<option value="checkbox">Checkbox list of distinct values</option>
						<option value="option">Radio button list of distinct values</option>
						<option value="minmax">Two pull downs for min/max ++</option>
						<option value="daterange">Date range; two text fields</option>
						</select><BR><font size="1">
						also, this can <u>only be used</u> for things like home features.
					</td>
				<tr>
					<td align="right" class="templateEditorNew" valign="top"><font size="1">++ </font><b>Step by:</b></td>
					<td class="templateEditorNew"><input type="text" name="search_step" value = "<?php echo $search_step; ?>">
					<BR><font size="1">++ Used for range selections only</font>
					</td>
				</tr>
				<tr>
					<td align="right" class="templateEditorNew" valign="top"><b>Search label:</b></td>
					<td class="templateEditorNew"><input type="text" name="search_label" value="<?=htmlspecialchars($search_label);?>"></td>
				</tr>


				<tr>
					<td align="right" class="templateEditorHead" valign="top">&nbsp;</td>
					<td class="templateEditorHead" align="left"><input type="submit" name="Submit" value="<?php echo $lang['update_button'] ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $PHP_SELF ?>?action=DeleteUserFormElement&id=<?php print $id ?>" onClick="return confirmDelete()"><?php echo $lang['delete'] ?></a></td>
				</tr>

				</form>
				<tr height="5"><td colspan="2" height="5">&nbsp;</td></tr>
			<?php
			$recordSet->MoveNext();
		} // end while
	print "</table>";
	include("$config[template_path]/admin_bottom.html");
	$conn->Close(); // close the db connection
?>
<?
xoops_cp_footer();
include("../../../footer.php");
?>