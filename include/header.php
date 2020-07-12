<?php
// $Id: header.php,v 1.1 2004/05/22 21:30:00 Farsus Exp $
// -------------------------------------------------------------------------//
//			       RUNCMS                                                   //
//                                                                          //
//	 Relaible - Unique - Nocost &  Simplicity & ease off use                //
//                       < http://www.runcms.org >                          //
// -------------------------------------------------------------------------//

if ( !defined('XOOPS_HEADER_INCLUDED') ) {
	define('XOOPS_HEADER_INCLUDED', 1);

site_cache('read');
xoops_header(false);

$currenttheme = getTheme();
include_once(XOOPS_ROOT_PATH.'/themes/'.$currenttheme.'/theme.php');

if ( @file_exists(XOOPS_ROOT_PATH.'/themes/'.$currenttheme.'/language/lang-'.$xoopsConfig['language'].'.php') ) {
	include_once(XOOPS_ROOT_PATH.'/themes/'.$currenttheme.'/language/lang-'.$xoopsConfig['language'].'.php');
	} elseif ( @file_exists(XOOPS_ROOT_PATH.'/themes/'.$currenttheme.'/language/lang-english.php') ) {
		include_once(XOOPS_ROOT_PATH.'/themes/'.$currenttheme.'/language/lang-english.php');
	}

$xoopsOption['show_rblock'] = (!empty($xoopsOption['show_rblock'])) ? $xoopsOption['show_rblock'] : 0;
themeheader($xoopsOption['show_rblock']);
make_cblock(XOOPS_CENTERBLOCK_TOPALL);
?>

<!-- START MODULE SPAN CSS -->
<span id="<?php if ($xoopsModule) { echo $xoopsModule->dirname();}?>_dom" name="<?php if ($xoopsModule) { echo $xoopsModule->dirname();}?>_dom" class="<?php if ($xoopsModule) { echo $xoopsModule->dirname();}?>_css">
<!-- START MODULE SPAN CSS -->

<?php
}
?>
