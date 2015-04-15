<?php
/**
* @package Page
*/
// ɬ�פʥե������쵤�˼����प�ޤ��ʤ���
/**
* XOOPS�ѥե�����μ�����
*/
require_once '../../mainfile.php';

//GIJOE ����Υ�󥿥�������å�
include_once "./include/gtickets.php" ;

//�ͤ�����Ƥߤ�衣
$memo_id = isset($_POST["tagmemo_id"]) ? intval($_POST["tagmemo_id"]) :0;
//�ϥ�ɥ��Ĥ��äƤߤ�衣
$tagmemo_handler =& xoops_getmodulehandler("tagmemo");

$module_id = $xoopsModule->mid();

//�桼����ID���餪��
if(is_object($xoopsUser)){
	$uid = $xoopsUser->getVar("uid");
	$isAdmin = $xoopsUser->isAdmin($module_id);
} else {
	$uid = 0;
	$isAdmin = false;
}

$tagmemo_handler->setUid($uid);
if ($tagmemo_handler->isReadonly()) {
	redirect_header(XOOPS_URL."/modules/tagmemo/index.php", 3, _NOPERM);
}

if($memo_id != 0){
	$memo_obj =& $tagmemo_handler->getMemoObj($memo_id);
	if (!is_object($memo_obj)) {
		redirect_header(XOOPS_URL."/modules/tagmemo/index.php", 3, 'Such memo does not exist.');
	}
	$memo_owner = $memo_obj->getVar('uid');

	if (!$isAdmin) {
		if ($memo_owner == 0) {
			//@future password check
			//$password = isset($_POST["password"]) ? $_POST["password"] : "";
			//(method_exists('MyTextSanitizer', 'sGetInstance') and $ts =& MyTextSanitizer::sGetInstance()) || $ts =& MyTextSanitizer::getInstance();
			//if ($ts->stripSlashesGPC($password) != $memo_obj->getVar('password', 'n')) {
				redirect_header(XOOPS_URL."/modules/tagmemo/index.php", 3, _NOPERM);
			//}
		} elseif ($memo_owner != $uid) {
			redirect_header(XOOPS_URL."/modules/tagmemo/index.php", 3, _NOPERM);
		}
	}
} else {
	redirect_header(XOOPS_URL."/modules/tagmemo/index.php", 3, 'memo is not selected.');
}
if ( ! $xoopsGTicket->check() ) {
	redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
}

//do deletion!
if ($tagmemo_handler->deleteMemo($memo_obj)) {
	$tagmemo_handler->makeAutolinkData();
	redirect_header(XOOPS_URL.'/modules/tagmemo/', 1, _MD_TAGMEMO_MESSAGE_DELETE);
} else {
	//get error message.
	$message = $tagmemo_handler->getErrors(false);
	$message = ($message == '') ? 'This memo is not deleted.' : $message; 
	//redirect to memo which is not deleted.
	redirect_header(XOOPS_URL.'/modules/tagmemo/detail.php?tagmemo_id='.$memo_id, 3, $message);
}
?>