<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>リニューアル支援</title>

<!-- BootstrapのCSS読み込み -->
<link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="../css/rnsien.css">

<script type="text/javascript" src="../tools.js"></script>
<link href="../css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="../js/jquery.numberPicker.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/tools_ajax.js"></script>
<script type="text/javascript" src="../js/ConnectedSelect.js"></script>
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">


<h6>詳細工程表(日程登録処理)</h6>
<br>
<form name="mainform" method="POST" action="./s_make_kotei_EXCEL_kari.php?rKey=__rKey__">
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">

日程の登録が完了しました。<br>
詳細工程表をダウンロードする場合は下の【詳細工程表作成】をクリックしてください。
<br>
<input type="submit" value="詳細工程表作成">

</form>
</body>
</html>

