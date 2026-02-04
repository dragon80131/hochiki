<html>
<head>
<title>パスワード取り寄せ</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《パスワード取り寄せ》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>
パスワードを送信します。ご登録のメールアドレスをご記入ください。<br>
<br>

<form method="POST" action="reminder_send.php">
■メールアドレス<BR>
<input type="text" name="wEMail" value="__wEMail__"><br>
__IfEMailEmpty__<font color="red">メールアドレスは必須です。</font><br>__IfEMailEmpty__
__IfEMailError__<font color="red">メールアドレスの形式が正しくないようです。</font><br>__IfEMailError__
__IfNotFound__<font color="red">ご指定のアドレスでの登録はありませんでした。</font><br>__IfNotFound__
<br>
__HiddenValues__
<center><input type="submit" value="登録"></center>
</form>

<hr size="__HRSize__" color="__HRColor__">
<a href="top.php">トップ</a>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</head>
</html>
