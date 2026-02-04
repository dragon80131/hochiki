<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>リニューアル支援</title>

    <!-- BootstrapのCSS読み込み -->
    <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link
      href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
      integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
      crossorigin="anonymous"
      rel="stylesheet"
    />

    <!-- jQuery読み込み -->
    <script src="./include/js/jquery-3.2.1.min.js"></script>

    <!-- BootstrapのJS読み込み -->
    <script src="./include/bootstrap/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="./css/rnsien.css" />
    <script type="text/javascript" src="tools.js"></script>
  </head>

  <body>
    __SHeader__

    <div class="content-all">
      <!--content-all-->

      <div class="left-yose">
        <a href="./s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__"
          >＜メニュー</a
        >
      </div>

      <div class="top-menu left-yose">
        <h5>__wBukkenName__</h5>

        <form action="#" method="POST" name="mainform">
          <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
          <!--
<h6>表紙</h6>
<a href="#" onclick="javascript:move('./doc/s_kansei_hyosi_Excel.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')" class="square_btn"><i class="far fa-file-excel"></i>表紙</a><br><br>

<h6>１．完成図</h6>
件名システムから取得お願いします。<br><br>

<h6>２．取扱説明書</h6>
<a href="#" onclick="javascript:move('s_TorisetuOyaki.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')" class="square_btn">取扱説明書（親機）</a>　

<a href="#" onclick="javascript:move('s_TorisetuKanriOyaki.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')" class="square_btn">取扱説明書（管理室親機）</a><br><br>

<h6>３．インターホン系統図</h6>
<a href="#" onclick="javascript:move('./s_kansei_keitouzu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')" class="square_btn">インターホン系統図</a><br><br>

<h6>４．工事完了確認書/試験結果報告書</h6>
<a href="#" onclick="javascript:move('./s_kan2.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')" class="square_btn">工事完了確認書</a>・・・Web経由以外は反映できません。<br><br>

<a href="#" onclick="javascript:move('./doc/s_kansei_sikenkekka_Excel.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')" class="square_btn"><i class="far fa-file-excel"></i>試験結果報告書</a><br><br>

<h6>５．保証書</h6>
<a href="#" onclick="javascript:move('./doc/s_kansei_hosyousyo_EXCEL.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')" class="square_btn"><i class="far fa-file-excel"></i>保証書</a>
・・・件名システムから取得お願いします。<br><br>
-->
          <h6>完成図書</h6>
          <!--
<a href="#" onclick="javascript:move('./s_kan2.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')" class="square_btn">工事完了確認書</a>・・・Web経由以外は反映できません。(使用可能になり次第お伝えします。)<br><br>-->

          <a
            href="#"
            onclick="javascript:move('s_kan.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')"
            class="square_btn"
            >工事写真</a
          >・・・Web経由以外は反映できません。
        </form>
      </div>
    </div>
    <!--content-all-->

    __SFooter__ __SCopyright__
  </body>
</html>
