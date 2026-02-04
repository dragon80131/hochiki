<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>過去物件情報からコピー</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="./include/js/jquery-3.2.1.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="./include/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/rnsien.css">
    <script type="text/javascript" src="tools.js"></script>

    <style>

    /* デフォルトのスタイル（PC向け） */
    .content-all {
        width: 80%;
        margin: 0 auto;
    }

    /* スマホ向けのスタイル */
    @media (max-width: 768px) {
        .content-all {
            width: 100%;
            padding: 0 10px;
        }
    }

    .search_form{
        display:flex;
        flex-direction:row;
        flex-wrap:wrap;
        align-items:center;
        justify-content:flex-start;
    }
    .search_form input[type=text]{
        width:200px;
        margin-right:5px;
    }

    </style>
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                __SHeaderKanri2__
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row mt-2">
            <div class="col-12">
                <h5>登録済み物件情報をコピー</h5>
                物件情報の複写を行います。<br>
                （１）複写したい物件を検索してください。<br>
                （２）該当物件にチェックをいれて「複写する」ボタンをクリックしてください。<br>
                （３）物件基本情報登録にチェックした物件情報が反映されます。<br>
            </div>
        </div>
        <form action="s_kihon_copy.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="search_form" method="POST">
            <input type="text" name="wBukkenName" value="__wBukkenName__" class="form-control form-control-sm">
            <button type="submit" name="search" value="yes" class="btn btn-blue">検索</button>
        </form>
        <form action="s_kihon_form.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" name="mainform" method="POST">
        <input type="hidden" name="work" value="1">
        <div class="row mt-2">
            <div class="col-12">
                <!--検索結果表示-->
                __IfList__
                最新の50件が表示されています。
                __IfList__
                __IfSearch__
                __searchCount__件が表示されています。
                __IfSearch__
                <div class="left-yose" id="bukkensearch">
                    __IfNoResults__
                    データがありません
                    __IfNoResults__
                    __IfResults__
                    <table class="table table-bordered search-table">

                        <tr>
                            <th class="bw">選択</th>
                            <th class="bw">物件名</th>
                            <th class="bw">協力業者</th>
                            <th class="bw">実施日程</th>
                        </tr>

                        __BukkenLoop__

                        <tr class="">
                            <td style="text-align:center"><input type="radio" name="checkcopy" value="__BukkenCD__"></td>
                            <td>
                                __BukkenName__
                            </td>
                            <td>__GyosyaDisp__</td>
                            <td>__KojiDate__</td>
                        </tr>

                        __BukkenLoop__

                    </table>
                    __IfResults__

                </div>

            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <input type="submit" onclick="return check_value();" value="複写する" class="btn btn-blue"><br>
                <font size="2" color="gray">※ 既に入力している場合は、上書きされます。</font>
            </div>
        </div>
        </form>
        <div class="row mt-5">
            <div class="col-12">
                <a href="s_kihon_form.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-info">物件基本情報登録に戻る</a>
                <a href="s_search.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-info">トップへ</a>

            </div>
        </div>

    </div>

    <div class="container">
        <div class="row mt-3">
            <div class="col-12 text-center">
                <!-- __SFooter__
                __SCopyright__ -->
            </div>
        </div>
    </div>

</body>
<script>
    function check_value(){
        if ($('input[name="checkcopy"]:checked').length > 0) {
            return true;
        } else {
            alert("複写対象の物件をチェックしてください。");
        }
        return false;
    }
</script>
</html>