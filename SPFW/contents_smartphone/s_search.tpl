<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

    // 次ページへ
    function kensaku(val) {
        // hidden追加
        var ele = document.createElement('input');
        ele.setAttribute('type', 'hidden');
        ele.setAttribute('name', 'KensakuDisp');
        ele.setAttribute('value', '1');
        document.mainform.appendChild(ele);
        window.document.mainform.action = val + "#bukkensearch";
        window.document.mainform.target = "_self";
        window.document.mainform.method = "POST";
        window.document.mainform.submit();
    }

    function deleteBukken(page, editBukkenCD) {
        if (window.confirm('物件を削除しますか？')) {
            document.mainform.deleteFlg.value = true;
            document.mainform.editBukkenCD.value = editBukkenCD;
            document.mainform.action = page;
            document.mainform.submit();
        }
    }
    </script>
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
    __SHeader__
    <div class="content-all">
        <!--content-all-->
        <br>
        <div class="top-news left-yose">
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


        <form action="s_search.php?rKey=__rKey__" name="mainform" method="POST">
            <input type="hidden" name="editBukkenCD" value="">
            <input type="hidden" name="rKey" value="__rKey__">
            <input type="hidden" name="Extra1" value="__Extra1__">
            <input type="hidden" name="deleteFlg" value="">

            <div class="left-yose" id="bukkensearch">
                <h5>物件検索</h5>

                __IfError__<br>
                <font color="red">検索条件を入力してください。</font>
                __IfError__
                <table class="table table-bordered table-sm">

                    <tr>
                        <th class="bw" style="width: 100px;">物件名</th>
                        <td colspan="3"><input type="text" name="wBukkenName" value="__wBukkenName__" class="search_conditions" style="width:100%;max-width: 400px;"></td>
                    </tr>

                    <tr>
                        <th class="bw">住所</th>
                        <td><input type="text" name="wAddress" value="__wAddress__" style="width:100%;max-width: 400px;"></td>
                    </tr>

                    __IfNespe__
                    <tr>
                        <th class="bw">協力業者</th>
                        <td><input type="text" name="wCompany" value="__wCompany__" style="width:100%; max-width: 400px;"></td>
                    </tr>
                    __IfNespe__


                    <tr>
                        <th class="bw">前回作業月</th>
                        <td>
                            <select name="wTenkenMonth" id="TenkenMonth">
                                <option value="">-</option>
                                <option value="1" __TenkenSelected1__>1月</option>
                                <option value="2" __TenkenSelected2__>2月</option>
                                <option value="3" __TenkenSelected3__>3月</option>
                                <option value="4" __TenkenSelected4__>4月</option>
                                <option value="5" __TenkenSelected5__>5月</option>
                                <option value="6" __TenkenSelected6__>6月</option>
                                <option value="7" __TenkenSelected7__>7月</option>
                                <option value="8" __TenkenSelected8__>8月</option>
                                <option value="9" __TenkenSelected9__>9月</option>
                                <option value="10" __TenkenSelected10__>10月</option>
                                <option value="11" __TenkenSelected11__>11月</option>
                                <option value="12" __TenkenSelected12__>12月</option>
                            </select>
                        </td>
                    </tr>
                    <!-- <tr>
                        <th class="bw">進捗状況</th>
                        <td>
                            <select name="BukkenStatus" id="BukkenStatus">
                                <option value="">-</option>
                                <option value="1" __BukkenStatusSelected1__>作業依頼済</option>
                                <option value="2" __BukkenStatusSelected2__>実施日程済</option>
                                <option value="3" __BukkenStatusSelected3__>資料DL済</option>
                            </select>
                        </td>
                    </tr> -->
                </table>

                <button type="button" class="btn btn-primary mb-10" onclick="kensaku('s_search.php?rKey=__rKey__')">　検索　</button>
                <button type="button" class="btn btn-default mb-10" onclick="javascript:move('s_search.php?rKey=__rKey__')">リセット</button>
                <button type="button" class="btn btn-default sign-up mb-10" onclick="javascript:move('s_kihon_form.php?rKey=__rKey__&PurposeSinki=1')">＞＞ 新規物件登録はこちら</button>
                <button type="button" class="btn btn-default mb-10" onclick="javascript:move('ns_monthly.php?rKey=__rKey__')">月別スケジュール</button>

            </div>

            __IfSearch__
            <!--検索結果表示-->

            <div class="left-yose" id="bukkensearch">
                <h5>物件一覧（検索結果）</h5>
                __IfNoResults__
                データがありません
                __IfNoResults__

                <table class="table table-bordered table-sm search-table">

                    <tr>

                        <th class="bw notKaigyou">物件名</th>

                        __IfNespe__
                        <th class="bw notKaigyou">協力業者</th>
                        __IfNespe__

                        <!-- <th class="bw notKaigyou">次回消防点検</th>
                        <th class="bw">作業依頼</th> -->
                        <th class="bw notKaigyou">実施日程</th>



                        <th class="bw notKaigyou">削除</th>

                    </tr>

                    __BukkenLoop__

                    <tr class="">

                        <td class="notKaigyou">
                            <a href="#" onclick="javascript:moveWithKey('s_menu.php?rKey=__rKey__',__BukkenCD__ )">__BukkenName__</a>
                        </td>

                        __IfKanri__
                        <td class="notKaigyou">__GyosyaDisp__</td>
                        __IfKanri__


                        <!-- <td class="__IsReport__" style="text-align: center">__result_date__月</td>
                        <td class="__Now__ __IsReport__" style="text-align: center">__IfNow__ 〇 __IfNow__</td> -->
                        <td class="__Now1__ __IsReport__ notKaigyou">__IfNow1__ __KojiDate__ __IfNow1__</td>
                        <!-- <td class="__Now2__ ">__HaifuDownloadDate__</td> -->


                        <td>
                            __IfKanri2__
                            <a href=" #" class="btn btn-danger btn-sm" onclick="javascript:deleteBukken('s_search.php?rKey=__rKey__',__BukkenCD__)"> 削除 </a>
                            __IfKanri2__
                        </td>

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

            __IfSearch__
            <!--検索結果表示-->

        </form>
        <br>
        <div class="top-menu left-yose">
            <h5>マスターメンテナンス</h5>
            <table class="table table-borderless table-sm table-kintou">
                <tr>
                    <td>・<a href="s_tanto_list.php?rKey=__rKey__&ClientCD=__wClientCD__">ユーザーマスタ</a></td>
                </tr>
                __IfNespe__
                <!-- <tr>
					<td>・<a href="s_system_list.php?rKey=__rKey__">システム名称管理（ネスペのみ）</a></td>
				</tr> -->
                <!-- <tr>
					<td>・<a href="s_device_list.php?rKey=__rKey__">機器・オプション管理（ネスペのみ）</a></td>
				</tr> -->
                <tr>
                    <td>・<a href="s_gyosya_list.php?rKey=__rKey__&ClientCD=__wClientCD__">協力会社マスタ</a></td>
                </tr>
                <tr>
                    <td>・<a href="s_gyosyatanto_list.php?rKey=__rKey__&ClientCD=__wClientCD__">協力会社ユーザーマスタ</a></td>
                </tr>
                <tr>
                    <td>・<a href="s_kanricompany_list.php?rKey=__rKey__">管理会社マスタ</a></td>
                </tr>

                __IfNespe__

            </table>
        </div>
        <br>
        <a href="logout.php__QUERY__">ログアウト</a><br>

        <!-- __SFooter__
		__SCopyright__ -->
    </div>
    <!--content-all-->
</body>

</html>