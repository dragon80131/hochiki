<html>
<head>
<title>メールアドレス変更</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《メールアドレス変更》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>
新しいメールアドレスを記入してください。記入して変更ボタンを押すと、確認のメールが届きますので、そちらのURLをクリックすると変更完了になります。<br>
<br>

<hr size="__HRSize__" color="__HRColor__">
<br>
<form method="POST" action="email_finish.php">
新メールアドレス:<br>
<input type="text" name="wEMail" value="__wEMail__" istyle="3"><br>
__IfEMailEmpty__<font color="RED">メールアドレスの入力は必須です。</font><br>__IfEMailEmpty__
__IfEMailError__<font color="RED">メールアドレスの入力形式がおかしいようです。</font><br>__IfEMailError__
__IfEMailUsed__<font color="RED">ご希望のメールアドレスは既に他の方に利用されています。</font><br>__IfEMailUsed__
<br>

__HiddenValues__
__COMMON_POST_QUERY__
<center><input type="submit" value="変更する"></center>
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
