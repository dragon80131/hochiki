<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>__TITLENAME__</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="./include/js/jquery-3.2.1.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="./include/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/rnsien.css">
    <script type="text/javascript" src="tools.js"></script>

    <style>
    .notKaigyou {
        white-space: nowrap;
    }

    .IsReport {
        background-color: #c0c0c0 !important;
    }


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


    </style>
    <script>
    $(function() {
        $('input:checkbox[name="allchk"]').change(function() {
            var prop = $('#allchk').prop('checked');
            if (prop) {
                $('input:checkbox[name="wSitenCD[]"]').prop('checked', true);
            } else {
                $('input:checkbox[name="wSitenCD[]"]').prop('checked', false);
            }
        });
        $('.search_conditions').keypress(function(e) {
            if (e.which == 13) {
                kensaku('s_search.php?rKey=__rKey__');
                return false;
            }
        });

        //協力業者の場合の制御
        var UserKbn = "__UserKbn__";
        if (UserKbn) {
            if (UserKbn == "3") { //協力業者の場合
                $(".sign-up").hide();
            }
        }
    });



    </script>
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <a class="logout" href="logout_kanri.php__QUERY__">ログアウト</a><br>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <div class="left-yose">
                    <font size="2" color="gray">ログインユーザ：__LastName__</font>
                </div>
                <h5>__IfSystemNespeUser__<a href="./noticememo.php?rKey=__rKey__"><b>◆</a>__IfSystemNespeUser__お知らせ</b></h5>
                __NoticeLoop__
                <div class="topnews-box">
                    <div class="top-news-memo">
                        ■__wUpdated__
                    </div>
                    <div class="top-news-memo">
                        __Memo__
                    </div>
                </div>
                __NoticeLoop__
            </div>
        </div>
    </div>
    <form action="s_d2_search.php?rKey=__rKey__" name="mainform" method="POST">
        <input type="hidden" name="editBukkenCD" value="">
        <input type="hidden" name="rKey" value="__rKey__">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="left-yose" id="bukkensearch">
                    <h5>物件検索</h5>

                    __IfError__<br>
                    <font color="red">検索条件を入力してください。</font>
                    __IfError__
                    <table class="table table-bordered table-sm">

                        <tr>
                            <th class="bw" style="width: 100px;">物件名</th>
                            <td colspan="3"><input type="text" id="searchBukkenName" name="wBukkenName" value="__wBukkenName__" class="form-control form-control-sm search_conditions" style="width:100%;max-width: 400px;"></td>
                        </tr>


                    </table>

                    <button type="button" class="btn btn-primary mb-10" onclick="kensaku('s_d2_search.php?rKey=__rKey__')">検索　</button>
                    <button type="button" class="btn btn-default mb-10" onclick="javascript:move('s_d2_search.php?rKey=__rKey__')">リセット</button>

                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row mt-2">
            <div class="col-12">
                <!--検索結果表示-->

                <div class="left-yose" id="bukkensearch">
                    <h5>物件一覧（検索結果）</h5>
                    __IfNoResults__
                    データがありません
                    __IfNoResults__

                    <table class="table table-bordered search-table">

                        <tr>

                            <th class="bw" width="50%">物件名</th>
                            <th class="bw" width="50%">作業開始日</th>

    <!-- 

                            <th class="bw">削除</th> -->

                        </tr>

                        __BukkenLoop__

                        <tr class="">

                            <td class="notKaigyou">
                                <a href="#" onclick="javascript:moveWithKey('s_d2.php?rKey=__rKey__',__BukkenCD__ )">__BukkenName__</a>
                            </td>

        
                            <td class="__Now1__ __IsReport__">__KojiDate__</td>


                            <!-- <td class="__Now2__ ">__HaifuDownloadDate__</td> -->

    <!-- 
                            <td>
                                __IfKanri2__
                                <a href=" #" class="btn btn-danger btn-sm" onclick="javascript:deleteBukken('s_search.php?rKey=__rKey__',__BukkenCD__)"> 削除 </a>
                                __IfKanri2__
                            </td> -->

                        </tr>

                        __BukkenLoop__

                    </table>

                    <div class="left-yose" style="float:left">
                        __IfToTop__<a href="#" onClick="javascript:changePage('s_search.php?rKey=__rKey__&KensakuDisp=1', 1, __AllPages__)">__IfToTop__&lt;&lt;__IfToTop__</a>__IfToTop__　
                        __IfToPre__<a href="#" onClick="javascript:changePage('s_search.php?rKey=__rKey__&KensakuDisp=1', __PreviousPage__, __AllPages__)">__IfToPre__&lt;__IfToPre__</a>__IfToPre__　
                        <input type="text" name="myPage" value="__myPage__" size="4" __IME_OFF__ class="form">/__AllPages__
                        <input type="button" value="ページジャンプ" class="button" onclick="javascript:changePage('s_search.php?rKey=__rKey__&KensakuDisp=1', '0', __AllPages__)">　
                        __IfToNext__<a href="#" onClick="javascript:changePage('s_search.php?rKey=__rKey__&KensakuDisp=1', __NextPage__, __AllPages__)">__IfToNext__&gt;__IfToNext__</a>__IfToNext__　
                        __IfToLast__<a href="#" onClick="javascript:changePage('s_search.php?rKey=__rKey__&KensakuDisp=1', __LastPage__, __AllPages__)">__IfToLast__&gt;&gt;__IfToLast__</a>__IfToLast__
                    </div>

                </div>


                <!--検索結果表示-->
            </div>
        </div>
    </div>

    </form>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- __SFooter__
                __SCopyright__ -->
            </div>
        </div>
    </div>

</body>
<script>

        // 次ページへ
    function kensaku(val) {
        // hidden追加
        var ele = document.createElement('input');
        ele.setAttribute('type', 'hidden');
        ele.setAttribute('name', 'searchBukkenName');
        searchBukkenName = document.getElementById('searchBukkenName').value
        ele.setAttribute('value', searchBukkenName );
        document.mainform.appendChild(ele);
        window.document.mainform.action = val + "#bukkensearch";
        window.document.mainform.target = "_self";
        window.document.mainform.method = "POST";
        window.document.mainform.submit();
    }

</script>
</html>