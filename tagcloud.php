<?php
/**
* @package Page
*/

// ɬ�פʥե������쵤�˼����प�ޤ��ʤ���
/**
* XOOPS�ѥե�����μ�����
*/
require_once '../../mainfile.php';
//$tagmemo_handler =& xoops_getmodulehandler('tagmemo');
// $tagmemo_handler =& xoops_getmodulehandler('memo');
$tagmemo_handler =& xoops_getmodulehandler('tagmemo');
//$tagmemo_objs =& $tagmemo_handler->getMemos();

$xoopsOption['template_main'] = 'tagmemo_tagcloud.html';

// �إå���񤯤��ޤ��ʤ���
/**
* XOOPS�Υƥ�ץ졼�ȤΥإå���
*/
include XOOPS_ROOT_PATH.'/header.php';

// �����ꥹ�ȤΥݥåץ��å׻���
$xoopsTpl->assign("taglist_popup",empty($xoopsModuleConfig['tagpopup_cloud'])? false : true);

 	//$cloud =$tagmemo_handler->_tag_handler->getTagArrayForCloud();
 	$cloud =$tagmemo_handler->getAllTagsEx();
	$xoopsTpl->assign('cloud', $cloud);
/**
* XOOPS�Υƥ�ץ졼�ȤΥեå���
*/
include(XOOPS_ROOT_PATH.'/footer.php');
?>