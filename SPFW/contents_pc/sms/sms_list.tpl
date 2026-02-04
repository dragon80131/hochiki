<html>
<head>
<title>連絡先登録</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《連絡先登録》</center>


<hr size="__HRSize__" color="__HRColor__">

	<form action="" method="post">
		<h4>アドレス一覧</h4>
		<table border=1>
			<tr>
				<th>名前</th>
				<th>TEL</th>
			</tr>
			__MessageLoop__
				<tr>
					<td><a href="./sms_message.php?address=__address_cd__" >__name__</a></td>
					<td>__tel__</td>
				</tr>
			__MessageLoop__
	</form>


__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</head>
</html>
