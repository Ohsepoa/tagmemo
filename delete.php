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
$memo_id = isset($_POST["tagmemo_id"]) ? $_POST["tagmemo_id"] :0;
//�ϥ�ɥ��Ĥ��äƤߤ�衣
$tagmemo_handler =& xoops_getmodulehandler("tagmemo");
//���Υ��֥������Ȥ��äƤߤ��
// echo "checkpoint 3.5 <br>\n";

$module_id = $xoopsModule->mid();

//�桼����ID���餪��
if(is_object($xoopsUser)){
	$uid = $xoopsUser->getVar("uid");
} else {
	$uid = 0;
}
if($memo_id != 0){
	$memo_obj =& $tagmemo_handler->getMemoObj($memo_id);
	$memo_owner = $memo_obj->getVar('uid');

 	if(($uid == 0) or (($memo_owner!= $uid) and !($xoopsUser->isAdmin($module_id)))){
		redirect_header(XOOPS_URL."/modules/tagmemo/index.php", 3, _NOPERM);
	}
}else{
		redirect_header(XOOPS_URL."/modules/tagmemo/index.php", 3, _NOPERM);

}
	if ( ! $xoopsGTicket->check() ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}


//���֥������Ȥ��ͤ����ꤷ�Ƥߤ�衣

//������ᡪ
$tagmemo_handler->deleteMemo($memo_obj);
// echo "checkpoint 7 <br>\n";

// echo "OK";

// �إå���񤯤��ޤ��ʤ���
//  include XOOPS_ROOT_PATH.'/header.php';
//  include(XOOPS_ROOT_PATH.'/footer.php');

redirect_header(XOOPS_URL.'/modules/tagmemo/', 1, _MD_TAGMEMO_MESSAGE_DELETE);
?>