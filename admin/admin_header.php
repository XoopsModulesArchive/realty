<?php
// ------------------------------------------------------------------------- //
//               E-Xoops: Content Management for the Masses                  //
//                       < http://www.e-xoops.com > 
// Modified to songwords module by :Dzulhelmi@Apek,www.rojak.org//
// ------------------------------------------------------------------------- //


include("../../../mainfile.php");
include_once(XOOPS_ROOT_PATH."/class/xoopsmodule.php");
include(XOOPS_ROOT_PATH."/include/cp_functions.php");
if ( $xoopsUser ) {
	$xoopsModule = XoopsModule::getByDirname("realty");
	if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
		redirect_header(XOOPS_URL."/",3,_NOPERM);
		exit();
	}
} else {
	redirect_header(XOOPS_URL."/",3,_NOPERM);
	exit();
}
if ( @file_exists("../../../language/".$xoopsConfig['language']."/admin.php") ) {
	include("../../../language/".$xoopsConfig['language']."/admin.php");
} else {
	include("../../../language/english/admin.php");
}
?>
