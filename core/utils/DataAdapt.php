<?php
namespace core\utils;
class DataAdapt
{
	// text validation
	public static function textValid($data, $html = true)
	{
		$data = trim($data);
		if ($html) $data = htmlspecialchars($data);
		if (get_magic_quotes_gpc()) $data = stripslashes($data);
		return $data;
	}
	// text validation for MySQL query
	public static function textValidMysql($data)
	{
		if (isset($data)) return mysql_escape_string(self::textValid($data));
	}
	
	public static function mailTest($email)
	{
		if (empty($email) || !preg_match("/^[a-z0-9\._-]+@[a-z0-9\._-]+\.[a-z]{2,4}$/i",$email)) return false;
		return true;
	}
	
	public static function textEditorValidMysql($data)
	{
		$allowable_tags = '<EM><U><STRONG><STRIKE><SUP><SUB><DIV><UL><OL><LI><BLOCKQUOTE><A><H1><H2><H3><H4><H5><H6><P><PRE><FONT><SPAN><BR>';
		$data = strip_tags($data,$allowable_tags);
		$data = preg_replace("/(javascript:)|(onAbort)|(onBlur)|(blur\(\))|(onChange)|(change\(\))|(onClick)|(click\(\))|(onDblClick)|(onError)|(onFocus)|(focus\(\))|(onKeyDown)|(onKeyPress)|(onKeyUp)|(onLoad)|(onMouseDown)|(onMouseMove)|(onMouseOut)|(onMouseOver)|(onMouseUp)|(onMove)|(onReset)|(reset\(\))|(onResize)|(onSelect)|(onSubmit)|(submit\(\))|(onUnload)/is","",$data);
		if (get_magic_quotes_gpc() == 0) $data = mysql_escape_string($data);
		return $data;
	}
	
}
?>