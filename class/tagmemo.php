<?php
/**
* �������Υ��饹���
* �ڡ�������ȥ����餫��Ϥ��Υե�����Υ��饹�Τߥ��󥹥��󥹲����뤳��
* 
* 
* 
* @package Persistence
*/
/**
* Xoops Object������ɤ߹���
*/
include_once XOOPS_ROOT_PATH."/class/xoopsobject.php";
/**
* �������Υ��֥������ȥϥ�ɥ�
* @package Persistence
* @author twodash <twodash@twodash.net>
*/
class TagmemoTagmemoHandler extends XoopsObjectHandler{
//public vars
//Nothing
//public function	
//Constructor
/**
* ���󥹥ȥ饯��
*/
	function TagmemoTagmemoHandler($db){
			$this->XoopsObjectHandler($db);
		$this->db =& $db;
		$this->_tag_handler =& xoops_getmodulehandler('tag');
		$this->_memo_handler =& xoops_getmodulehandler('memo');
		$this->_rel_handler =& xoops_getmodulehandler('relation');
	}
//Constructor
/**
* ���󥹥ȥ饯��
*/
	function &getInstance(&$db){
		static $instance;
		if (!isset($instance)) {
			$instance = new TagmemoTagmemoHandler($db);
		}
		return $instance;
	}

/**
* �����˥����Υ��֥������Ȥ����
* 
* @param bool �����˺���
* @access public
* @return TagmemoTagObject
*/
	function &createTag($isNew = true){
		$this->_ready(true, false, false);
		$ret =& $this->_tag_handler->create($isNew);
		return $ret;
	}

/**
* �����˥��Υ��֥������Ȥ����
* @access public
* @param bool �����˺���
* @return TagmemoMemoObject
*/
	function &createMemo($isNew = true){
		$this->_ready(false, true, false);
		$ret =& $this->_memo_handler->create($isNew);
		return $ret;
	}

/**
* ��⥪�֥������Ȥȥ�������Ͽ
* @access public
* @param TagmemoMemoObject
* @param string �����Υꥹ�Ȥ�ʸ����
* @param bool
* @return integer ������������ID
*/
	function insert(&$arg_memo, $arg_tags, $force = false){
		$this->_ready();
		$this->_memo_handler->insert($arg_memo, $force);
		$arg_memo->isnew();
		$wk_memo_id = $arg_memo->getVar("tagmemo_id");
		$arr_tags =& $this->_tag2array($arg_tags);
		$this->_rel_handler->removeRelation($wk_memo_id);
		foreach($arr_tags as $wk_tag){
		 $wk_tag_id=$this->getTagId($wk_tag);
		 $this->setRelation($wk_memo_id, $wk_tag_id);
		}
		return $wk_memo_id;
	}
/**
* ������
* @access public
* @param TagmemoTagObject
*/
	function deleteMemo($memoObj){
		$wk_memo_id = $memoObj->getVar("tagmemo_id");
		$this->_rel_handler->removeRelation($wk_memo_id);
		$this->_memo_handler->delete($memoObj);
	}
/**
* $tag_var��¸�߳�ǧ
* @access public
* @return bool
*/
	/* $tag_var��¸�߳�ǧ */
	function isExistTag($tag_var){
		$this->_getTag2Cache();
		$ret =& in_array($tag_var, $this->_tags);
		//$ret =& in_array($this->_tags); hey!hey!	
		return $ret;
	}
	
/**
* $tag_var��tag_id�μ����ʤ���Х��󥵡���
* @access public
* @param string �����Υǡ���
* @param bool
* @return integer tagmemo_id
*/
 	function getTagId($tag_var, $force = false){
		$this->_getTag2Cache();
		//�������鲼������������
		$wk_criteria = new Criteria("tag",$tag_var);
		$wk_tags = $this->_tag_handler->getObjects($wk_criteria);
		if(count($wk_tags) > 0){
			$ret = $wk_tags[0]->getVar("tag_id");
		//�����ޤǡ��䤪����������
		}else{
			$this->_ready(true, false, false);
			$wk_obj_tag =& $this->_tag_handler->create(true);
			$wk_obj_tag->setVar("tag",$tag_var);
			$this->_tag_handler->insert($wk_obj_tag, $force);
		$wk_obj_tag->isnew();
			$ret = $wk_obj_tag->getVar("tag_id");
		}
		return $ret;
	} 

/**
* �����ȥ����Ϣ�դ���
* @param integer ����ID
* @param integer ������ID
* @access public
*/
	function setRelation($tagmemo_id, $tag_id){
		if($tagmemo_id !=0 & $tag_id != 0){
		$this->_rel_handler->setRelation($tagmemo_id, $tag_id);
		}
	}

/**
* ���object�����
* @access public
* @deprecated
*/
//���object���֤�
	function &getMemos(){
		$this->_getMemo2Cache();
		$ret =& $this->_memos;
		return $ret;
	}
	
/* 1�ĤΥ����֤� */
/**
* 1�ĤΥ���ǡ�������������
* @access public
* @param integer ����ID
* @return array
*/
	function &get($memo_id){
	$memo_id= intval($memo_id);
//		$this->_ready();
		$wk_objmemo =& $this->getMemoObj($memo_id);
		$this->_set_condition_memo($memo_id);
		$this->_getTag2Cache();
		$rel_criteria = new Criteria('tagmemo_id',$memo_id);
		$this->_rel_handler->readRelation($rel_criteria);
		$ret = $this->_memoObj2Array($wk_objmemo);
		return $ret;
	}
	
/* �Խ��ѤΥ����֤� */
/**
* �Խ��ѤΥ��Υǡ�����������
* @param integer ����ID
* @access public
* @return array
*/
	function &getMemo4Edit($memo_id){
	$memo_id= intval($memo_id);
//		$this->_ready();
		$wk_objmemo =& $this->getMemoObj($memo_id);
		$this->_getTag2Cache();
		$rel_criteria = new Criteria('tagmemo_id',$memo_id);
		$this->_rel_handler->readRelation($rel_criteria);
		$ret = $this->_memoObj2Array($wk_objmemo, 'e');
		return $ret;
	}

/**
* ��⥪�֥������Ȥ����
* @param integer ���ID >0
* @access public
* @return TagmemoMemoObject
*/
	function &getMemoObj($memo_id){
//		$this->_getMemo2Cache();
$this->_set_condition_memo($memo_id);
		$ret =& $this->_memo_handler->get($memo_id);
		return $ret;
	}
	
/**
* ������������
* @access public
* @param ����ID
* @return array
*/
//
	function &getMemosArray($tag_id=null, $count=0,$start=0){
		$ret = array();
//		$this->_ready();
		$this->_set_condition_tag($tag_id);
		$this->_getMemo2Cache($count, $start);
		$this->_set_condition_memo(array_keys($this->_memos));
		$this->_getTag2Cache();
		$this->_rel_handler->readRelation();
		$wk_memo_key = array_keys($this->_memos);
		$wk_memo_list = $wk_memo_key;
// 		if($tag_id != null){
// 			$wk_memo_list = $this->_rel_handler->tag2memo[$tag_id];
// 		}
// 		arsort($wk_memo_list);
		foreach($wk_memo_list as $wk_memo_id){
			$objMemo =& $this->_memos[$wk_memo_id];
			$ret[]= $this->_memoObj2Array($objMemo);
		}
		return $ret;
	}
/**
* ���ƤΥ��������
* @access public
* @return array ����ID�򥭡��Ȥ��ƥǡ����˥������������
*/
	function &getAllTags(){
			$wk_tags =& $this->_tag_handler->getObjects(null,true);
			foreach($wk_tags as $wk_obj_tag){
				$wk_tag_id = $wk_obj_tag->getVar("tag_id");
				$wk_tag = $wk_obj_tag->getVar("tag");
				$ret [$wk_tag_id] = $wk_tag;
			}
		asort($ret);
		return $ret;
	}

/**
* ��ĥ�����ƤΥ��������
* @access public
* @return array ����ID�򥭡��Ȥ��ƥǡ����˥������������
*/
	function &getAllTagsEx(){
		$ret = array();
		$ret = $this->_tag_handler->getAllTagsEx();
		return $ret;
	}

/**
* �͵����������
* @access public
* @param integer ������
* @return array ��̤򥭡��Ȥ��ƥǡ����˥���ID�ȥ������������
*/
	function getPopularTag($count = 10,$start = 0){
		$ret = array();
		$ret = $this->_tag_handler->getPopularTag($count,$start);
		return $ret;
	}
/**
* �ǿ����������
* @access public
* @param integer ������
* @return array ��̤򥭡��Ȥ��ƥǡ����˥���ID�ȥ������������
*/
	function getResentTag($count = 10,$start = 0){
		$ret = array();
		$ret = $this->_tag_handler->getResentTag($count,$start);
		return $ret;
	}

/**
* ��Ϣ���������
* @access public
* @return array
*/
function getRelatedTags(){
	$ret=array();
	if(!($this->_flg_chenge_condition_tag)){
		$this->_getTag2Cache();
		$wk_tags = $this->_tags;
		$wk_keys = array_keys($wk_tags); 
		$wk_keys = array_diff($wk_keys,($this->_condition_tag));
		foreach($wk_keys as $wk_key){
			$ret[$wk_key] = $wk_tags[$wk_key];
		}
	}
	return $ret;
}

/**
* �����θ����оݤ�����
* @access public
* @param array ����ID������
*/
//
	function setTagCondition($tags){
		/**  @todo impliment cirteria */
		$wk_tags = array();
		foreach($tags as $wk_tag){
			if(is_numeric($wk_tag ) and $wk_tag > 0){
			$wk_tags[] = $wk_tag;
			}
		}
		$this->_set_condition_tag($wk_tags);
	}
	/**
	*�桼����ID�򥻥å�
	* @param int Xoops Uid
	*/
	function setUid($uid = 0){
		$this->_condition_uid = intval($uid);
	}
/**
* �����ѤΥ����������ؿ�
* @access public
* @param string �����о�
*/
	function search($keyword){
		/**  @todo impliment cirteria */
		$this->_keyword = $this->_kwd2array($keyword);
		$this->_flg_chenge_condition_memo = true;
	}

/**
* ������ɤ���о��
* @access public
*/
	function getQueryCondition(){
	 return implode($this->_keyword, " ");
	}
/**
* ��������о��
* @access public
*/
	function getTagCondition(){
		$this->_getTag2Cache();
		$ret = array();
		$simple_condition = implode($this->_condition_tag, ",");
		$ret['simple'] =  $simple_condition ;
		$ret['url'] = (strlen($simple_condition) > 0) ? ($simple_condition . ",") : $simple_condition  ;
		$wk_tagcondition = ($this->_condition_tag);
		foreach($wk_tagcondition as $condition){
			$wk_array = array();
			$wk_condition = implode(array_diff($wk_tagcondition ,array($condition)), ",");
			$wk_array['url'] = $wk_condition;
			$wk_array['id'] = $condition;
			$wk_array['string'] = $this->_tags[$condition]["tag"];
			$ret['detail'][] = $wk_array;
			unset($wk_array);
		}
		return $ret;
//		return implode($this->_condition_tag, ",");
	}

	function getMemoCount(){
		return $this->_memo_handler->getCount();
	}

//private vars
/**
* @access private
* @var $_tag_handler
*/
	var $_tag_handler;
/**
* @access private
* @var $_memo_handler
*/
	var $_memo_handler;
/**
* @access private
* @var $_rel_handler
*/
	var $_rel_handler;
/**
* @access private
* @var $_tags
*/
	var $_tags = array();
/**
* @access private
* @var $_memos
*/
	var $_memos = array();
//	var $_tag2memo = array();
//	var $_memo2tag = array();

/**
* @access private
* @var $_flg_ready
*/
	var $_flg_ready = false;
/**
* @access private
* @var $_flg_get_tags
*/
	var $_flg_get_tags = false;
/**
* @access private
* @var $_flg_get_memos
*/
	var $_flg_get_memos = false;
/**
* @access private
* @var $_condition_tag
*/
	var $_condition_tag = array();
/**
* @access private
* @var $_condition_memo
*/
	var $_condition_memo ;
/**
* @access private
* @var $_condition_uid
*/
	var $_condition_uid = 0;
/**
* @access private
* @var $_condition_tag
*/
	var $_flg_chenge_condition_tag = true;
/**
* @access private
* @var $_condition_memo
*/
	var $_flg_chenge_condition_memo = true;
/**
* @access private
* @var $_keyword
*/
	var $_keyword = array();

//private function
//
/**
* ���ϥ�ɥ�ȥ����ϥ�ɥ��ɬ�פ˱����ƺ���
* @see $_tag_handler
* @see $_memo_handler
* @see $_rel_handler
* @deprecated
* @access protected
* @param bool �����ϥ�ɥ���������
* @param bool ���ϥ�ɥ���������
* @param bool ��Ϣ�ϥ�ɥ���������
*/
	function _ready($use_tag = true, $use_memo = true, $use_rel=true){
		if($use_tag = true){
			if(!isset($this->_tag_handler)){
				$this->_tag_handler =& xoops_getmodulehandler('tag');
			}
		}
		if($use_memo = true){
			if(!isset($this->_memo_handler)){
				$this->_memo_handler =& xoops_getmodulehandler('memo');
			}
		}
		if($use_rel = true){
			if(!isset($this->_rel_handler)){
				$this->_rel_handler =& xoops_getmodulehandler('relation');
			}
		}
	}
/**
* $_memos�˥�⥪�֥������Ȥ��ɤ߹���
* @see $_memos
* @access protected
*/
	function _getMemo2Cache($count=0, $start=0){
		if(!($this->_flg_get_memos)or($this->_flg_chenge_condition_memo)){
//		$this->_ready(false, true, false);
		// @todo impliment cirteria
		$wk_having = "";
			$wk_criteria = new CriteriaCompo;
		if(count($this->_condition_tag) > 0){
			$criteria_count = 0;
			foreach($this->_condition_tag as $wk_tag_id){
				if($wk_tag_id>0){
			//echo "wk_tag_id= $wk_tag_id<br>";
					$wk_tagid_criteria = new Criteria('tag_id', $wk_tag_id,'=', 'rel');
					$wk_criteria->add($wk_tagid_criteria,'OR');
					unset($wk_tagid_criteria);
					//echo "<br>" . $wk_criteria->render() . "<br>";
					$criteria_count +=1;
				}
			}
			if($criteria_count > 1){
				$wk_having = "count(tag_id) = ".$criteria_count;
			}
		}
		if(count($this->_keyword) > 0){
			foreach($this->_keyword as $wk_kwd){
			//echo "wk_tag_id= $wk_tag_id<br>";
				$wk_kwd_criteria = new Criteria('content', '%'.$wk_kwd.'%','like');
				$wk_criteria->add($wk_kwd_criteria,'AND');
				unset($wk_kwd_criteria);
				//echo "<br>" . $wk_criteria->render() . "<br>";
			}
			}
		
		$wk_criteria->setSort('timestamp');
		$wk_criteria->setOrder('DESC');
		$wk_criteria->setLimit($count);
		$wk_criteria->setStart($start);
//		$this->_memos =& $this->_memo_handler->getObjects(null,true);
		$this->_memos =& $this->_memo_handler->getMemo($wk_criteria,$wk_having);
		$this->_flg_get_memos = true;
		$this->_flg_chenge_condition_memo = false;
//		echo $this->_memo_handler->getLastSql();
		}
	}
/**
* $_tags�ɤ߹���
* @see $_tags
* @access protected
*/
	function _getTag2Cache(){
		if(!($this->_flg_get_tags) or ($this->_flg_chenge_condition_tag)){
			if(count($this->_condition_memo)>0){
				$this->_tags = $this->_tag_handler->getTags($this->_condition_memo);
			}
			$this->_flg_get_tags = true;
			$this->_flg_chenge_condition_tag = false;
		}
	}
	
/**
* ����ʸ�����������������
* @param string �����򥹥ڡ����ޤ��ϥ���ޤ�ʬ�����ꥹ��
* @access protected
* @return array ����ID�Υꥹ��
*/
	function &_tag2array($tags){
		$wk_tagstr = strval($tags);
		$wk_tagstr = mb_convert_kana($wk_tagstr,"asKHV");
		$pattern[] = "/,/";
		$replacement[] = " ";
		$pattern[] = "/\s+/";
		$replacement[] = " ";
		$pattern[] = "/^\s+/";
		$replacement[] = "";
		$pattern[] = "/\s+$/";
		$replacement[] = "";
		$wk_tagstr = preg_replace($pattern, $replacement, $wk_tagstr);
		$ret = preg_split("/\s/",$wk_tagstr);
		$ret = array_unique ($ret);
		return $ret;
	}

/**
* �������ʸ�����������������
* @param string �����򥹥ڡ����ޤ��ϥ���ޤ�ʬ�����ꥹ��
* @access protected
* @return array ����ID�Υꥹ��
*/
	function &_kwd2array($arg_kwd){
		$wk_kwd = strval($arg_kwd);
		$wk_kwd = mb_convert_kana($arg_kwd,"s");
		$pattern[] = "/,/";
		$replacement[] = " ";
		$pattern[] = "/\s+/";
		$replacement[] = " ";
		$pattern[] = "/^\s+/";
		$replacement[] = "";
		$pattern[] = "/\s+$/";
		$replacement[] = "";
		$wk_kwd = preg_replace($pattern, $replacement, $wk_kwd);
		$ret = preg_split("/\s/",$wk_kwd);
		$ret = array_unique ($ret);
		return $ret;
	}

/**
* memo�˴�Ϣ���륿������������
* @access protected
*/
	function _parseRelatedTags($memo_id){
		$this->_getTag2Cache();
		$ret = array();
		$wk_memo2tag =& $this->_rel_handler->memo2tag;
		$wk_tagids =& $wk_memo2tag[$memo_id];
		if(!is_null($wk_tagids)){
			foreach($wk_tagids as $wk_tag_id){
				$ret[$wk_tag_id] = $this->_tags[$wk_tag_id]['tag'];
			}
		}
		return $ret;
	}

/**
* ��⥪�֥������Ȥ�����Ѥ�������֤��ؿ�
* @param TagmemoMemoObject
* @param string ���ϥե����ޥå�
* @access protected
*/
	function &_memoObj2Array(&$objMemo, $format = 's'){
		$ret = array();
		$memo_id = $objMemo->getVar("tagmemo_id");
		$ret["tagmemo_id"] = $memo_id;
		$wk_uid = intval($objMemo->getVar("uid", $format));
		$ret["uid"]        = $wk_uid;
		if((intval($this->_condition_uid) > 0) and ($wk_uid == intval($this->_condition_uid))){
			$ret["owner"] = 1;
		}else{
			$ret["owner"]  = 0;
		}
		$ret["title"]      = $objMemo->getVar("title", $format);
		$ret["content"]    = $objMemo->getVar("content", $format);
		$ret["timestamp"]  = formatTimestamp($objMemo->getVar("timestamp", $format), "mysql");
		$ret["public"]     = $objMemo->getVar("public", $format);
		$ret["tags"]       = $this->_parseRelatedTags($memo_id);
		return $ret;
	}
	/**
* @access protected
	*@param mixed
	*@return void
	*/
	function _set_condition_tag($tag_ids){
	$wk_array = array();
		if(is_array($tag_ids)){
			foreach($tag_ids as $wk_tag_id){
				$wk_id = intval($wk_tag_id);
				if($wk_id>0){
					$wk_array[] = $wk_id;
				}
			}
		}else{
			$wk_id = intval($tag_ids);
			if($wk_id>0){}
			$wk_array[] = $wk_id;
		}
		$this->_flg_chenge_condition_memo = true;
	$this->_condition_tag = $wk_array;
	}
	/**
* @access protected
	*@param mixed
	*@return void
	*/
	function _set_condition_memo($memo_ids){
		$this->_condition_memo = $memo_ids;
		$this->_flg_chenge_condition_tag = true;
	}
}//end of class define of TagmemoHandler
?>