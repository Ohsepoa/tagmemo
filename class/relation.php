<?php
/**
* �����ȥ��δ�Ϣ�򰷤����饹�����
* @package Persistence
*/
/**
* Xoops Object������ɤ߹���
*/
include_once XOOPS_ROOT_PATH."/class/xoopsobject.php";
/**
* �����ȥ��δ�Ϣ�򰷤����饹
* @package Persistence
*/
class TagmemoRelationHandler extends XoopsObjectHandler{
/**
* �����򥭡��Ȥ�����Ϣ�������Υꥹ��
* @var array
* @access public
*/
	var $tag2memo = array();
/**
* �����򥭡��Ȥ�����Ϣ�������Υꥹ��
* @var array
* @access public
*/
	var $memo2tag = array();
/**
* �ơ��֥�̾
* @var string
* @access public
*/
	var $tablename = "";
//�����ȥ����Ϣ�դ���
/**
* ���󥹥ȥ饯��
* @param mixed XoopsDBHandler
*/
	function TagmemoRelationHandler($db){
		$this->tablename = $db->prefix("tagmemo_rel");
		$this->db =$db;
	}
	function &getPopularTag($count=10){
		$ret = array();
		$sql = sprintf("select tag_id, count(tagmemo_id) as f_count from %s group by tag_id order by f_count DESC ,tag_id ASC limit %u",$this->tablename,$count);
		$result =& $this->db->query($sql);
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)){
			$wk_tag_id = intval($myrow['tag_id']);
			$wk_count = intval($myrow['f_count']);
			$wk_arr = array();
			$wk_arr["tag_id"] = $wk_tag_id;
			$wk_arr["count"] = $wk_count;
			$ret[] = $wk_arr;
			unset($wk_arr);
		}
		return $ret;
	}
/**
* DB���顡���ȥ����δ�Ϣ���ɤ߹���Ǥ��Υ��֥������Ȥ˥��åȤ��롣 
*
* @param integer ����ID >0
* @param integer ������ID >0
*/
	function setRelation($tagmemo_id, $tag_id){
		// @todo SQL ���˥��饤��
		$sql = sprintf("INSERT IGNORE INTO %s (tagmemo_id, tag_id)VALUES(%u,%u)",$this->tablename,intval($tagmemo_id),intval($tag_id));
//		echo "RelationHandler.setRelation:SQL".$sql."<br>\n";
		$this->db->queryF($sql);
	}
	
/**
* ���˴�Ϣ���륿�������ƺ������
*
* @param integer ����ID >0
*/
	function removeRelation($tagmemo_id){
		$sql = sprintf("DELETE FROM %s WHERE tagmemo_id=%u ",$this->tablename,intval($tagmemo_id));
//		echo "RelationHandler.setRelation:SQL".$sql."<br>\n";
		$this->db->query($sql);
	}
/** 
* ���ȥ����δ�Ϣ��DB�����ɤ߹���
* @param mixed CriteriaObject �������
*/
	function readRelation($criteria=null){
		$sql = sprintf("select tag_id, tagmemo_id from %s ",$this->tablename);
		// @todo impliment cirteria
		if ($criteria){
			$where = $criteria->renderWhere();
			$sql = $sql . $where ;
		}
		$result =& $this->db->query($sql);
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)){
			$wk_tag_id = intval($myrow['tag_id']);
			$wk_memo_id = intval($myrow['tagmemo_id']);
			$this->addRelation($wk_memo_id, $wk_tag_id);
		}
	}
/**
* ���Υ��֥������ȤΥե�����ɤ�$tag2memo��$memo2tag�˥ǡ����򥻥åȤ���
* @see $tag2memo
* @see $memo2tag
* @param integer ����ID >0
* @param integer ������ID >0
*/
	function addRelation($tagmemo_id, $tag_id){
		if($tagmemo_id !=0 & $tag_id != 0){
			if(!isset($this->tag2memo[$tag_id])){
				$this->tag2memo[$tag_id] = array();
			}
			if(!in_array($tagmemo_id, $this->tag2memo[$tag_id])){
				$this->tag2memo[$tag_id][]=$tagmemo_id;
			}
			if(!isset($this->memo2tag[$tagmemo_id])){
				$this->memo2tag[$tagmemo_id] = array();
			}

			if(!in_array($tag_id, $this->memo2tag[$tagmemo_id])){
				$this->memo2tag[$tagmemo_id][]=$tag_id;
			}
		}
	}
}
?>