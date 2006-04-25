<?php
define("SOURCE_ENCODING","EUC-JP");
$url = (!empty($_GET['q']))? $_GET['q'] : "";

// URL��� & �к�
$url = str_replace("%26","&",$url);

$result = "var tmp = new Array();";
if ($url)
{
	include_once("include/hyp_common/hyp_common_func.php");
	include_once("include/hyp_common/hyp_kakasi.php");

	$d = new Hyp_HTTP_Request();

	$d->url = $url;
	$d->method = 'GET';
	$d->ua = 'Mozilla/4.0';
	$d->get();
	
	if ($d->rc === 200)
	{
		$data = $d->data;
		
		// ʸ��������Ƚ�� �Ѵ�
		$src_enc = HypCommonFunc::get_encoding_by_meta($data);
		$data = preg_replace("#<script(.+?)/script>#is","",$data);
		$data = preg_replace("#<style(.+?)/style>#is","",$data);
		$data = preg_replace("#<form(.+?)/form>#is","",$data);
		
		// �����ȥ륿��
		$title = "";
		if (preg_match("#<title>(.+)</title>#is",$data,$match))
		{
			$title = $match[1];
			$title = str_replace(array("&nbsp;","&lt;","&gt;","&amp;"),array(" ","<",">","&"),$title);
			$title = str_replace("\0","",mb_convert_encoding($title, SOURCE_ENCODING, $src_enc));
			
			$k = new Hyp_KAKASHI();
			$k->get_keyword($title, 3, 3, 1);
			
			if ($title) $title .= " ";
		}
		
		// HTML����
		$data = strip_tags($data);
		$data = str_replace(array("&nbsp;","&lt;","&gt;","&amp;"),array(" ","<",">","&"),$data);
		$data = str_replace("\0","",mb_convert_encoding($data, SOURCE_ENCODING, $src_enc));
				
		// ��¸�����ȤΥޥå���
		$autofile = "../../cache/tagmemo_autolink.dat";
		@list($auto,$dum,$forceignorepages) = @file($autofile);
		if (!$auto) $auto = "(?!)";
		$auto = explode("\t",trim($auto));
		// TAG��¿�����ϡ����ѥ졼�� \t ��ʣ���ѥ������ʬ�䤵��Ƥ���
		$tags = "";
		$match_tags = array();
		foreach($auto as $pat)
		{
			$pattern = "/$pat/is";
			if (preg_match_all($pattern,$data,$match,PREG_PATTERN_ORDER))
			{
				$match_tags = array_merge($match_tags,$match[0]);
			}
		}
		if ($match_tags)
		{
			$tags = join(" ",$match_tags)." ";
		}
		
		// �����ǲ��ϤǤΥޥå���
		$k = new Hyp_KAKASHI();
		$k->get_keyword($data);
		
		$data = join(" ",array_unique(explode(" ",$tags.$title.$data)));
		$data = mb_convert_encoding($data, "UTF-8", "EUC-JP");
		$result = 'var tmp = new Array("'.str_replace(array('"'," "),array('\"','","'),$data).'");';
	}
}
header ("Content-Type: text/html; charset=UTF-8");
echo $result;
?>