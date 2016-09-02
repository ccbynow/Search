<?php
/**
* 文件: search.php
* 功能: 搜索指定目录下的HTML文件
*/


/* 基本函数 */

//获取目录下文件函数
header("content-type:text/html;charset=utf-8");
class getIndex{

    
    function arr_foreach($arr)
   {error_reporting(0);
      static $tmp=array();  
 
      for($i=0; $i<count($arr); $i++)
      {  
         if(is_array($arr[$i]))
         {  
            $this->arr_foreach($arr[$i]);  
         }else{  
            $tmp[]=$arr[$i];  
         }  
      }  
 		foreach( $tmp as $k=>$v){   
		    if( !$v )   
		        unset( $tmp[$k] );   
		}   
      return array_unique($tmp);//数组去重  
   }         
	function getFile($dir)
	{
		$extarr = array('html','txt','php' );
		$dp = opendir($dir);//打开句柄
		$fileArr = array();
		while (!false == $curFile = readdir($dp)) {
		if ($curFile!="." && $curFile!=".." && $curFile!="") {
		if (is_dir($dir . "/" .$curFile)) {
		   $fileArr[] = $this->getFile($dir."/".$curFile);
		} else {
			$strtoken = explode(".",$dir."/".$curFile);
	        $ext = $strtoken[count($strtoken)-1];
	        if (in_array($ext,$extarr))
		   $fileArr[] = $dir."/".$curFile;
		}
		}
		}
		return $this->arr_foreach($fileArr);//之前返回调用数组重新构建一维数组。现还是返回
		//return $fileArr;
	}

	//获取文件内容
	function getFileContent($file)
	{
		if (!$fp = fopen($file, "r")) {
		die("Cannot open file $file");
		}
		while ($text = fread($fp, 4096)) {
		$fileContent .= $text;
		} 
		return $fileContent;
	}

	//搜索指定文件
	function searchText($file, $keyword)
	{//var_dump($file);exit;
		$text = file_get_contents($file);
		//echo $text;exit;
		if (preg_match("/$keyword/i", $text)) {
		return true;
		}
		return false;
	}


	//搜索出文章的标题
	function getFileTitle($file, $default="None subject")
	{
		//$fileContent = $this->getFileContent($file);
		$fileContent = file_get_contents($file);
		$sResult = preg_match("/<title>.*<\/title>/i", $fileContent, $matchResult);
		$title = preg_replace(array("/(<title>)/i","/(<\/title>)/i"), "", $matchResult[0]);
		if (empty($title)) {
		return $default;
		} else {
		return $title;
		}
	}

	//获取文件描述信息
	function getFileDescribe($file,$length=200, $default="None describe")
	{
		$metas = get_meta_tags($file);
		if ($meta['description'] != "") {
		return $metas['description'];
		}
		$fileContent = getFileContent($file);
		preg_match("/(<body.*<\/body>)/is", $fileContent, $matchResult);
		$pattern = array("/(<[^\x80-\xff]+>)/i","/(<input.*>)+/i", "/(<a.*>)+/i", "/(<img.*>)+/i", "/([<script.*>])+.*([<\/script>])+/i","/&amp;/i","/&quot;/i","/&#039;/i", "/\s/");
		$description = preg_replace($pattern, "", $matchResult[0]);
		$description = mb_substr($description, 0, $length)." ...";

		return $description;
	}

	//加亮搜索结果中的关键字
	function highLightKeyword($text, $keyword, $color="#C60A00")
	{
		$newword = "<font color=$color>$keyword</font>";
		$text = str_replace($keyword, $newword, $text);
		return $text;
	}

	//获取文件大小(KB)
	function getFileSize($file)
	{
		$filesize = intval(filesize($file)/1024)."K";
		return $filesize;
	}

	//获取文件最后修改的时间
	function getFileTime($file)
	{
		$filetime = date("Y-m-d", filemtime($file));
		return $filetime;
	}
	function myfunction($v) 
	{
		if ($v!="")
			{
			return true;
			}
		return false;
	}
    function array_remove_empty(&$arr, $trim = true)     
	{     
	    foreach ($arr as $key => $value) {     
	        if (is_array($value)) {     
	            $this->array_remove_empty($value);     
	        } else {     
	            $value = trim($value);     
	            if ($value == ''|| $value == NULL) {     
	                unset($arr[$key]);     
	            } elseif ($trim) {     
	                $arr[$key] = $value;     
	            }    
	        }     
	    }
	    return $arr;     
	}    

	//搜索目录下所有文件(非正则)
	function searchFile($dir, $keyword)
	{
		$sFile = $this->getFile($dir);//遍历所有的文件
		
		//$sFile = $this->array_remove_empty($sFile);
		//var_dump($sFile);exit;
		if (count($sFile) <= 0) {
		return false;
		}
		$sResult = array();
		foreach ($sFile as $file) {
		if ($this->searchText($file, $keyword)) {
		$sResult[] = $file;
		}
		}
		if (count($sResult) <= 0) {
		return false;
		} else {
		return $sResult;
		}
	}


}


/* 测试代码 */

//指定要搜索的目录
if ($_POST) {
	$searchText = trim($_POST['searchText']);
	$folder = trim($_POST['folder']);
	//var_dump($_FILES['folder']);
	//var_dump($_FILES['folder']['tmp_name']);exit;
}
$index = new getIndex();
$dir = $folder;
//要搜索的关键字
$keyword = $searchText;
//var_dump($index->getFile($dir));exit;
$fileArr = $index->searchFile($dir, $keyword);


$searchSum = count($fileArr);
if ($fileArr=='') {
	$searchSum = 0;
}

echo "搜索关键字: <b>$keyword</b> &nbsp; 搜索目录: <b>$dir</b> &nbsp; 搜索结果: <b>$searchSum</b><br><hr size=1><br>";
echo "以下文件下包含关键字".$keyword.'<br>';

if ($searchSum <= 0) { 
echo "没有搜索到任何结果";
} else {echo implode('<br>', $fileArr);
foreach ($fileArr as $file) {
echo "<a href='$file' target='_blank'>". $index->highLightKeyword($index->getFileTitle($file), $keyword) .
   "</a> - ".$index->getFileSize($file)."&nbsp;". $index->getFileTime($file) .
   "<br>\n<font size=2>".$index->highLightKeyword($index->getFileDescribe($file), $keyword) .
   "</font><br><br>";
}
}