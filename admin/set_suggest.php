<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
/**
* @package adminPage
*/

/**
* ���������ѤΥإå��ե������ɤ߹���
* s
*/

require_once('../../../include/cp_header.php');

//(DB update section)

xoops_cp_header();
// add Hiro
include('./mymenu.php');
echo "<h4>Set Suggest</h4>";

if ($_GET['mode'] == "set")
{
	tagmemo_admin_set_suggest();
}
else
{
	tagmemo_admin_set_suggest_init();
}

function tagmemo_admin_set_suggest_init()
{
	echo "<p><a href='?mode=set'>Click to Set Suggest in empty suggest filed.</a></p>";
}

function tagmemo_admin_set_suggest()
{
	include_once("../include/hyp_kakasi.php");
	$ka = new Hyp_KAKASHI();
	global $xoopsDB;
	$query = "SELECT * FROM `".$xoopsDB->prefix("tagmemo_tag")."` WHERE `suggest` = ''";
	$res = $xoopsDB->query($query);
	if ($res)
	{
		while($data = mysql_fetch_row($res))
		{
			$suggest = $data[1];
			$ka->get_hiragana($suggest);
			$query = "UPDATE `".$xoopsDB->prefix("tagmemo_tag")."` SET `suggest` = '".addslashes($suggest)."' WHERE `tag_id` = ".$data[0]." LIMIT 1";
			echo htmlspecialchars($data[1])." -> ".htmlspecialchars($suggest)."<br />";
			$xoopsDB->queryF($query);
		}
		echo "<hr />End of data.<br />";
	}
}

xoops_cp_footer();
?>
?>