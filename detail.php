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
	$userObject = & $xoopsUser;
} else {
	$uid = 0;
	$userObject = new XoopsGuestUser;
}
$tagmemo_handler->setUid($uid);
if($memo_id != ""){
	$memo =& $tagmemo_handler->get($memo_id);
	$tagmemo_related_tags = $tagmemo_handler->getRelatedTags();
global $tagmemo_related_tags;
}else{
redirect_header(XOOPS_URL."/modules/tagmemo/index.php", 3, _NOPERM);
}
/*$memo_uid = $memo["uid"];
echo $memo_uid . "<br>";
echo $userObject->getUnameFromId($memo_uid) . "<br>";
*/
$memo_owner_name = $userObject->getUnameFromId($memo["uid"]);
$xoopsOption['template_main'] = 'tagmemo_detail.html';
// �إå���񤯤��ޤ��ʤ���
/**
* XOOPS�Υƥ�ץ졼�ȤΥإå���
*/
include XOOPS_ROOT_PATH.'/header.php';
if($memo_id != ""){
	$xoopsTpl->assign("memo", $memo);
	$xoopsTpl->assign("memo_owner", $memo_owner_name);

}
/**
* XOOPS�Υƥ�ץ졼�ȤΥեå���
*/
include(XOOPS_ROOT_PATH.'/footer.php');
?>