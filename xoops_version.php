<?php
$modversion['name'] = _MI_TAGMEMO_NAME;
$modversion['version'] = 1.00;
$modversion['description'] = _MI_TAGMEMO_DESC;
$modversion['credits'] = '';
$modversion['author'] = 'argon, comodita, fugafuga, twodash, yosha_01';
$modversion['help'] = 'help.html';
$modversion['license'] = 'GPL see LICENSE';
$modversion['official'] = 0;
$modversion['image'] = 'images/tagmemo_slogo.png';
$modversion['dirname'] = 'tagmemo';
 
// Admin
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
//$modversion['adminmenu'] = "admin/menu.php";
 
// Menu
$modversion['hasMain'] = 1;
 
// Templates
/* $modversion['templates'][0]['file'] = 'index.html';
$modversion['templates'][0]['description'] = 'tagmemo'; */
$modversion['templates'][1]['file'] = 'tagmemo_edit.html';
$modversion['templates'][1]['description'] = 'tagmemo';
$modversion['templates'][2]['file'] = 'tagmemo_tagcloud.html';
$modversion['templates'][2]['description'] = 'list of tag';
$modversion['templates'][3]['file'] = 'tagmemo_list.html';
$modversion['templates'][3]['description'] = 'tagmemo_list';
$modversion['templates'][0]['file'] = 'tagmemo_detail.html';
$modversion['templates'][0]['description'] = 'detail content of memo';

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
//$modversion['sqlfile']['postgresql'] = "sql/pgsql.sql";
// Tables created by sql file (without prefix!)
$modversion['tables'][0] = "tagmemo";
$modversion['tables'][1] = "tagmemo_tag";
$modversion['tables'][3] = "tagmemo_rel";

//blocks
$modversion['blocks'][1]['file'] = "tag_block.php";
$modversion['blocks'][1]['name'] = "Popular Tags";
$modversion['blocks'][1]['show_func'] = "b_tagmemo_poulartag";
$modversion['blocks'][1]['template'] = "popular_tag.html";

$modversion['blocks'][2]['file'] = "tag_block.php";
$modversion['blocks'][2]['name'] = "Recent Tags";
$modversion['blocks'][2]['show_func'] = "b_tagmemo_recenttag"; 
$modversion['blocks'][2]['template'] = "recent_tag.html";

$modversion['blocks'][3]['file'] = "tag_block.php";
$modversion['blocks'][3]['name'] = "Related Tags";
$modversion['blocks'][3]['show_func'] = "b_tagmemo_relatedtag"; 
$modversion['blocks'][3]['template'] = "related_tag.html";

?>