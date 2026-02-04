<html>
<head>
<title>会員登録</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《会員登録》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>
メールアドレスを入力していただくと、会員登録メールが届きます。<br>
<br>

<hr size="__HRSize__" color="__HRColor__">
<br>
__IfError__<font color="red">__ErrorMessage__</font><br>__IfError__
<form method="POST" action="pcemail_finish.php">
メールアドレス:<br>
<input type="text" name="wEMail" value="__wEMail__" istyle="3"><br>
__IfEMailEmpty__<font color="RED">メールアドレスの入力は必須です。</font><br>__IfEMailEmpty__
__IfEMailError__<font color="RED">メールアドレスの入力形式がおかしいようです。</font><br>__IfEMailError__
<br>

__HiddenValues__
__COMMON_POST_QUERY__
<center><input type="submit" value="登録する"></center>
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
