<html>
<head>
<title>__TITLENAME__</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">

<!--ヘッダ-->
<div style="text-align : center ;">
<table style="margin-left : auto ; margin-right : auto ;"border="0" class=sampleTable ><br><tr><td><br><br>
<img src=./images/489_small.png>

<hr size="__HRSize__" color="__HRColor__">
<center>《ログイン》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>
ユーザー名とパスワードを入力し、ログインしてください。<br>
ログイン後、アプリインストールが可能です。<br>
<br>

<hr size="__HRSize__" color="__HRColor__">
<br>
__IfError__<font color="red">__ErrorMessage__</font><br>__IfError__

__IfOPEN__
<form method="POST" action="login_finish.php">
__IfOPEN__

__IfCLOSE__
<form method="POST" action="app_login_finish2.php">
__IfCLOSE__


ユーザー名:(半角入力)<br>
<input type="text" name="wID" value="__wID__"  istyle="3" style="ime-mode:disabled;"><br>
パスワード:(半角入力)<br>
<input type="password" name="wPasswd" value="__wPasswd__"  istyle="3" style="ime-mode:disabled;"><br>
<br>

__HiddenValues__
__COMMON_POST_QUERY__
<input type="submit" value="ログインしてアプリをインストールする">
</form>





<hr size="__HRSize__" color="__HRColor__">
<a href="top.php__QUERY__">トップ</a>
<br><br>
<hr size="__HRSize__" color="__HRColor__">

__SFooter__

__SCopyright__
<br>
</body>
</head>
</html>
