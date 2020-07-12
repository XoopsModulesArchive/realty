<?php
include("../../mainfile.php");
include("../../header.php");
OpenTable();
?>

<?php
	
include("include/common.php");
include("$config[template_path]/user_top.html");
?>
<i><b> <font color="#1B5943" size="4"> <font color="#42557B"><?php echo $config[company_name]?>.</font></font></b></i> 
<p> <font color="#42557B" size="2">You can contact <?php echo $config[company_name]?> in 
  a number of ways.</font> <br>
  <br>
<table width="600" border="0">
  <tr> 
    <td><font color="#42557B" size="2"><b><i>By Phone</i></b></font></td>
    <td><font color="#42557B" size="2"><?php echo $config[phone] ?></font></td>
  </tr>
  <tr> 
    <td><font color="#42557B" size="2"><b><i>By Email</i></b></font></td>
    <td><font color="#42557B" size="2"><b></b> </font><font color="#42557B" size="2"><b></b> 
      <a href="mailto:<?php echo $config[email] ?>"><?php echo $config[email] ?></a></font></td>
  </tr>
  <tr> 
    <td><font color="#42557B" size="2"><b><i>By Fax</i></b></font></td>
    <td><font color="#42557B" size="2"><?php echo $config[fax] ?></font></td>
  </tr>
  <tr> 
    <td><font color="#42557B" size="2"><b><i>By Mail</i></b></font></td>
    <td><font color="#42557B" size="2"><?php echo $config[mail] ?></font></td>
  </tr>
</table>
<p><font color="#42557B" size="2">We look forward to hearing from you!</font><i><b><font size="4" color="#006600"><br>
  &nbsp;</font></b></i><br><br><br>
<//----------Please do not remove-----------//><center>Original Software - Open-Reality By: <a href='http://www.jonroig.com' target='_blank'>Jon Roig</a> & <a href='http://www.open-realty.org' target='_blank'>Ryan Bonham (Open-Realty.org)</a><br>Scripting for E-xoops <a href='http://www.liquidgfx.com' target='_blank'>Liquid GFX Inc</a></center>
<?
CloseTable();
include("../../footer.php");
?>
