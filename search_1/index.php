<html>
  
<head>
<title>掬一捧|搜索框的实现</title>
<meta charset="UTF-8">
<style type="text/css">
<!--
body {
    font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;
    font-size:14px;
}
 
h1 {
    margin-top:0px;
    margin-bottom:8px;
}
 
/* 链接 */
a {
    text-decoration:none;
    color:#1c00ff;
}
 
a:hover {
    color:#5f00e4;
}
fieldset.search {
    padding: 0px;
    border: none;
    width: 232px;
    background:#e0e0e0;
}
 
fieldset.search:hover {
    background: #a8a8a8;
}
.search input, .search button {
    border: none;
    float: left;
}
.search input.box {
    height: 28px;
    width: 200;
    margin-right: 0px;
    padding-right: 0px;
    background: #e0e0e0;
    margin: 1px;
}
.search input.box:focus {
    background: #e8e8e8 ;
    outline: none;
}
.search button.btn {
    border: none;
    width: 28px;
    height: 28px;
    margin: 0px auto;
    margin: 1px;
    background: url(http://sandbox.runjs.cn/uploads/rs/339/livk7pl5/search_blue.png) no-repeat top right;
}
.search button.btn:hover {
    background: url(http://sandbox.runjs.cn/uploads/rs/339/livk7pl5/search_black.png) no-repeat bottom right;
}
 
/* 文章样式 */
.article {
 
}
-->
</style>
<script type="text/javascript" src="jquery-1.4.2.min.js"></script>
<script type="text/javascript">
    function fuzhi(){
        alert($('#folder').val());
    }
</script>
</head>
<body>
<div>
<h2>搜索路径:</h2>
<form method="post" action="search.php" enctype="multipart/form-data">
<fieldset class="search">
        
          <input type="text" class="box" name="folder" style="width:550px;" class="inputText" value="D:\Program Files\Apache Software Foundation\Apache2.2\htdocs\" x-webkit-speech>
    </fieldset>
    <h2>关键字：</h2><fieldset class="search">
         <input type="text" class="box" name="searchText" id="s" class="inputText" placeholder="掬一捧" x-webkit-speech>
          <button class="btn" title="SEARCH"> </button>
    </fieldset>
</form>
</div>
<article class="article">
</article>
</body>
</html>