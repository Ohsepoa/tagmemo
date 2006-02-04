<?php
/**
*�֥��ץ��֥������ȤΥ��饹���
* @package Persistence
*/
/**
* XoopsTableObject�Ѿ�
*/
include_once dirname(__FILE__).'/xoopstableobject.php';

/**
* ���Υǡ������֥�������
* @package Persistence
* @author twodash <twodash@twodash.net>
*/
class TagmemoMemo extends XoopsTableObject
{
/**
* ���󥹥ȥ饯��
*/
	function TagmemoMemo()
	{
		$this->XoopsObject();
		$this->initVar('tagmemo_id', XOBJ_DTYPE_INT, null, true);
		$this->initVar('uid', XOBJ_DTYPE_INT, null, true);
		$this->initVar('title', XOBJ_DTYPE_TXTBOX, null, true, 120);
		$this->initVar('content', XOBJ_DTYPE_TXTAREA, null, true);
		$this->initVar('timestamp', XOBJ_DTYPE_INT, null, true);
		$this->initVar('public', XOBJ_DTYPE_INT, null, true);
		//�ץ饤�ޥ꡼���������
		$this->setKeyFields(array('tagmemo_id'));

		//AUTO_INCREMENT°���Υե���������
		// - ��ĤΥơ��֥���ˤϡ�AUTO_INCREMENT°������ĥե�����ɤ�
		//   ��Ĥ����ʤ�����Ǥ���
		$this->setAutoIncrementField('tagmemo_id');
	} 
}

/**
* ���Υ��֥������ȥϥ�ɥ�
* @package Persistence
* @author twodash <twodash@twodash.net>
*/
class TagmemoMemoHandler extends XoopsTableObjectHandler
{
/**
* ���󥹥ȥ饯��
*/
    function TagmemoMemoHandler(&$db)
    {
    ////////////////////////////////////////
    // �ƥ��饹������ʬ(������)
    ////////////////////////////////////////

        //�ƥ��饹�Υ��󥹥ȥ饯���ƽ�
        $this->XoopsTableObjectHandler($db);
        
    ////////////////////////////////////////
    // �������饹��ͭ�������ʬ
    ////////////////////////////////////////

        //�ϥ�ɥ���оݥơ��֥�̾���
        $this->tableName = $this->db->prefix('tagmemo');

        //��Ϣ���饹̾�Τ�ʸ�������
        // - ɸ��Υ͡��ߥ󥰤˽�򤷤Ƥ�����ˤ���������
     $this->objectClassName = 'tagmemomemo';
    }
	//�ߴ����Τ���Ĥ��Ƥߤ�
    function &getInstance(&$db)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new TagmemoMemoHandler($db);
        }
        return $instance;
    }
/**
*�������Ѥ��ò�
* @param CriteriaElement
* @return array
*/
	function &getMemo($criteria=null,$having=""){
		$fieldlist=" main.tagmemo_id, uid, title, content, public, timestamp";
		if($criteria == null){
			$criteria = new CriteriaCompo;
		}
		$criteria->setGroupby($fieldlist);
		$joindef = new XoopsJoinCriteria($this->db->prefix('tagmemo_rel'), 'tagmemo_id','tagmemo_id', 'LEFT','main','rel');
		return parent::getObjects($criteria, true, $fieldlist, false, $joindef,$having);	
	}
}
?>