<?php
/**
* @package Page
*/

// ɬ�פʥե������쵤�˼����प�ޤ��ʤ���
/**
* XOOPS�ѥե�����μ�����
*/
require_once '../../mainfile.php';
$memo_id = isset($_GET["tagmemo_id"]) ? intval($_GET["tagmemo_id"]) : "";
$tagmemo_handler =& xoops_getmodulehandler('tagmemo');
//�桼����ID���餪��
if(is_object($xoopsUser)){
	$uid = $xoopsUser->getVar("uid");
} else {
	$uid = 0;
}
$tagmemo_handler->setUid($uid);
if($memo_id != ""){
	$memo =& $tagmemo_handler->get($memo_id);
	$tagmemo_related_tags = $tagmemo_handler->getRelatedTags();
global $tagmemo_related_tags;
}
$xoopsOption['template_main'] = 'tagmemo_detail.html';
// �إå���񤯤��ޤ��ʤ���
/**
* XOOPS�Υƥ�ץ졼�ȤΥإå���
*/
include XOOPS_ROOT_PATH.'/header.php';
if($memo_id != ""){
	$xoopsTpl->assign("memo", $memo);
}

/**
* XOOPS�Υƥ�ץ졼�ȤΥեå���
*/
include(XOOPS_ROOT_PATH.'/footer.php');
?>