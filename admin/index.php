<?php
include("admin_header.php");
xoops_cp_header();
?>
<?php
	include("../include/common.php");
	loginCheck('User');
        include("$config[template_path]/admin_top.html");
?>
<P><center><b>
Welcome This is the administrative area of the site!</b><br><b>When you logout. Your Xoops administration session will also be logged out.</b></center>
<P>
</P>

<?php

	$conn->Close(); // close the db connection
?>
<?
xoops_cp_footer();
?>