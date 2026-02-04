<html>
<head>
<title>ログイン</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《ログイン》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>
先に、お客様連絡先情報の登録をお願いします。<br> 
パスワードを入力後、お客様の連絡情報の登録フォームが表示されます<br>
<br>

<hr size="__HRSize__" color="__HRColor__">
<br>
__IfError__<font color="red">__ErrorMessage__</font><br>__IfError__
<form method="POST" action="form.php">
パスワード:<br>
<input type="text" name="vPasswd" value="__vPasswd__" istyle="1"><br>
__IfPasswdError__<font color="red">パスワードが正しくないようです。</font><br>__IfPasswdError__
<br>

__HiddenValues__
__COMMON_POST_QUERY__
<center><input type="submit" value="認証する"></center>
</form>
<hr size="__HRSize__" color="__HRColor__">
<a href="top.php__QUERY__">トップ</a>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</head>
</html>
