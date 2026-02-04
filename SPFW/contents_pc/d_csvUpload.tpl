<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>__TITLENAME__</title>
<!-- BootstrapのCSS読み込み -->
<link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery読み込み -->
<script src="../include/js/jquery-3.2.1.min.js"></script>

<!-- BootstrapのJS読み込み -->
<script src="../include/bootstrap/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="../css/rnsien.css">
<script type="text/javascript" src="../tools.js"></script>

<link href="../css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" >
<script type="text/javascript" src="../js/tools_ajax.js"></script>
<script type="text/javascript" src="../js/ConnectedSelect.js"></script>
<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
				__SHeader__
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
				__SAdminHeader__
				<h2 class="admin-title">仮日程データのインポート</h2>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
				<a href="./s_make_kanryo2.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" class="btn btn-primary">もどる</a>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
				仮日程のファイルをアップロードしてください。仮日程のExcelサンプルは、<a href="__samplefile__" >こちら</a><br>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center mt-5">
				<img src="../images/formatsetumei.jpg" width="300px">
				A列に部屋、B列に日付、C列に開始時刻をセット
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-5">
				※専有部期間：<font size=5><b>__SenyuStartDate__　～　__SenyuEndDate__</b></font> の間に作業日がセットします。
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
				__IfErrmisFile__   <font color="#ff0000">日程ファイルがExcelではありません。</font><br> __IfErrmisFile__
				__IfErrNoUP__ <font color="#ff0000">ファイルをアップロードできません。</font><br>   __IfErrNoUP__
				<!--__IfErrNoFile__   <font color="#ff0000">ファイルがアップロードされていません。</font><br> __IfErrNoFile__-->

				__IfError__ <font color="#ff0000">__ErrorStringAll__</font><br> __IfError__

				__IfUp__
				<form action="d_csvUpload.php?editBukkenCD=__editBukkenCD__&rkey=__rkey__" method="post" enctype="multipart/form-data">
					仮日程ファイル(Excelファイル)：<br />
					<input type="hidden" name="rKey" value="__rKey__" >
					<input type="file" name="upfile" size="30"   /><br /><br />
					<input type="submit" value="アップロード"  class="btn btn-primary blue" />
				</form>
				__IfUp__


				__IfDb__
				ファイルをアップロードしました。部屋のもれや日付まちがいがないか確認後、「 次へ 」ボタンをクリックします。
				<form action="d_csvImport.php?editBukkenCD=__editBukkenCD__" method="post" >
					<input type="hidden" name="rKey" value="__rKey__" ><br />
					<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" ><br />
					<input type="hidden" name="Upfile" value="__template_filepath__" ><br />
					<input type="submit" value=" 次へ "  class="btn btn-primary"/>
				</form>
				アップされたファイルの内容<br>
				<table border=1 >
				<tr><td>部屋番号</td><td>日程</td><td>開始時間</td></tr>
				__CellLoop__
				<tr><td>__wUserCD__</td><td>__wTimeFromDate__</td><td>__wTimeFromTime__</td></tr>
				__CellLoop__
				</table>
				__IfDb__



            </div>
        </div>

    </div>
__SFooter__
__SCopyright__

</body>
</html>