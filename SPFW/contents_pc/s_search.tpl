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

	<!--datepicker-->
	<link href="css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" />
	<link href="css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet" />
	<link href="css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet" />
	<script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="js/jquery-ui/datepicker-ja.js"></script>

    <style>
    .notKaigyou {
        white-space: nowrap;
    }

    .IsReport {
        background-color: #c0c0c0 !important;
    }

    .btn-90{
        width:90px;
    }

    #btn_sel_delete{
        display:none;
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
        .right-yose{
            display:none;
        }
    }


    </style>
    <script>
    //協力会社の場合の制御
    var UserKbn = "__UserKbn__";
    var m = "__m__";
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

        if (UserKbn) {
            if (UserKbn == "3" || m == '1') { //協力会社の場合、スマホ用
                $(".sign-up").hide();
            }
        }

		$(".datepicker").datepicker({
			showButtonPanel: true
		});

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
            document.mainform.deleteFlg.value = "1";
            document.mainform.editBukkenCD.value = editBukkenCD;
            document.mainform.action = page;
            document.mainform.submit();
        }
    }
    </script>
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
        <div class="row mt-3">
            <div class="col-12">
                <div class="top-news left-yose">
                    <div class="left-yose">
                    __IfDevelper__
                        <font size="2" color="gray">幹事企業名：__ClientName__</font><br>
                    __IfDevelper__
                    __IfWorker__
                        <font size="2" color="gray">協力会社名：__wGyosyaName__</font><br>
                    __IfWorker__
                        <font size="2" color="gray">ログインユーザ：__LastName__</font>
                        __IfPC__
                        <div class="right-yose">スマホ用QRコード：<img src="__file__" height="70px"></div>
                        __IfPC__
                    </div>
                    <!-- <h5>__IfSystemNespeUser__<a href="./noticememo.php?rKey=__rKey__"><b>◆</a>__IfSystemNespeUser__お知らせ</b></h5>
                    __NoticeLoop__
                    <div class="topnews-box">
                        <div class="top-news-memo">
                            ■__wUpdated__
                        </div>
                        <div class="top-news-memo">
                            __Memo__
                        </div>
                    </div>
                    __NoticeLoop__ -->
                </div>
            </div>
        </div>
    </div>

    <form action="s_search.php?rKey=__rKey__&m=__m__" name="mainform" method="POST">
    <input type="hidden" name="editBukkenCD" value="">
    <input type="hidden" name="rKey" value="__rKey__">
    <input type="hidden" name="Extra1" value="__Extra1__">
    <input type="hidden" name="deleteFlg" value="">
    <input type="hidden" name="m" value="__m__">
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
                            <th class="bw" width="30%">物件名</th>
                            <td width="70%"><input type="text" name="wBukkenName" value="__wBukkenName__" class="search_conditions form-control form-control-sm"></td>
                        </tr>

                        <tr>
                            <th class="bw" width="30%">物件名ふりがな</th>
                            <td width="70%"><input type="text" name="wBukkenNameKana" value="__wBukkenNameKana__" class="search_conditions form-control form-control-sm"></td>
                        </tr>

                        <tr>
                            <th class="bw">住所</th>
                            <td><input type="text" name="wAddress" value="__wAddress__" class="form-control form-control-sm"></td>
                        </tr>

                        __IfNespe__
                        <tr>
                            <th class="bw">協力会社</th>
                            <td><input type="text" name="wGyosyaCompany" value="__wGyosyaCompany__" class="form-control form-control-sm" style="width:100%; max-width: 400px;"></td>
                        </tr>
                        __IfNespe__
                        <tr>
                            <th class="bw">管理会社</th>
                            <td><input type="text" name="wKanriCompany" value="__wKanriCompany__" class="form-control form-control-sm" style="width:100%; max-width: 400px;"></td>
                        </tr>
                        <tr>
                            <th class="bw">支店・支社</th>
                            <td>
								<select class="form-control form-control-sm" name="wBrancheCompany" id="wBrancheCompany">
									<option value="">-</option>
									__BrancheCompanyLoop__
									<option value="__aBrancheCD__" __BrancheSelected__ >__BrancheName__</option>
									__BrancheCompanyLoop__
								</select>
                            </td>
                        </tr>


                        <!-- <tr>
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
                        </tr> -->
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

                        <tr>
                            <th class="bw">実施日程(期間)</th>
                            <td class="d-flex align-items-center">
								<input type="text" id="wSenyuStartDate" name="wSenyuStartDate" class="datepicker form-control form-control-sm" value="__wSenyuStartDate__" autocomplete="off">～
								<input type="text" id="wSenyuEndDate" name="wSenyuEndDate" class="datepicker form-control form-control-sm" value="__wSenyuEndDate__" autocomplete="off">
                            </td>
                        </tr>

                        <tr>
                            <th class="bw">実施日程(月)</th>
                            <td class="d-flex align-items-center">
                                <select name="wSenyuMonth" id="SenyuMonth" onchange="changeSenyuMonth()">
                                    <option value="">-</option>
                                    <option value="1" __SenyuMonthSelected1__>1月</option>
                                    <option value="2" __SenyuMonthSelected2__>2月</option>
                                    <option value="3" __SenyuMonthSelected3__>3月</option>
                                    <option value="4" __SenyuMonthSelected4__>4月</option>
                                    <option value="5" __SenyuMonthSelected5__>5月</option>
                                    <option value="6" __SenyuMonthSelected6__>6月</option>
                                    <option value="7" __SenyuMonthSelected7__>7月</option>
                                    <option value="8" __SenyuMonthSelected8__>8月</option>
                                    <option value="9" __SenyuMonthSelected9__>9月</option>
                                    <option value="10" __SenyuMonthSelected10__>10月</option>
                                    <option value="11" __SenyuMonthSelected11__>11月</option>
                                    <option value="12" __SenyuMonthSelected12__>12月</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-primary btn-90 mb-1" onclick="kensaku('s_search.php?rKey=__rKey__&m=__m__')" style="margin-left:10px">検索</button>
                <button type="button" class="btn btn-default btn-90 mb-1" onclick="javascript:move('s_search.php?rKey=__rKey__&m=__m__')">リセット</button>
                <button type="button" class="btn btn-default sign-up mb-1" onclick="javascript:move('s_kihon_form.php?rKey=__rKey__&m=__m__&PurposeSinki=1')">＞＞ 新規物件登録はこちら</button>
                <!-- <button type="button" class="btn btn-default mb-1" onclick="javascript:move('ns_monthly.php?rKey=__rKey__&m=__m__')">月別スケジュール</button> -->
                __IfDeleteHead__
                <button id="btn_sel_delete" type="button" class="btn btn-danger mb-1" onClick="delete_chks();">選択した物件を削除</button>            
                __IfDeleteHead__
            </div>    
        </div>
    </div>
    <div class="container">
        <div class="row mt-2">
            <div class="col-12">
                __IfSearch__
                <!--検索結果表示-->

                <div class="left-yose" id="bukkensearch">
                    <h5>物件一覧（検索結果）</h5>
                    __IfNoResults__
                    データがありません
                    __IfNoResults__

                    <table class="table table-bordered search-table">

                        <tr>
                            __IfCheckboxHead__
                            <th class="bw">
                                <input type="checkbox" id="chkall">
                            </th>
                            __IfCheckboxHead__
                            <th class="bw">物件名</th>

                            __IfNespe__
                            <th class="bw">協力会社</th>
                            __IfNespe__

                            <!-- <th class="bw">次回消防点検</th>
                            <th class="bw">作業依頼</th> -->
                            <th class="bw">管理会社</th>
                            <th class="bw">実施日程</th>

                            __IfDeleteHead__
                            <th class="bw text-center">削除</th>
                            __IfDeleteHead__

                        </tr>

                        __BukkenLoop__

                        <tr class="">
                            __IfCheckboxCell__
                            <td>
                                <input type="checkbox" class="chk_item" name="chk_item[]" value="__BukkenCD__">
                            </td>
                            __IfCheckboxCell__ 
                            <td>
                                <a href="#" onclick="javascript:moveWithKey('s_menu.php?rKey=__rKey__&m=__m__',__BukkenCD__ )">__BukkenName__</a>
                            </td>

                            __IfNespeCell__
                            <td>__GyosyaDisp__</td>
                            __IfNespeCell__
                            <td>__KanriCompanyName__</td>

                            <!-- <td class="__IsReport__" style="text-align: center">__result_date__月</td>
                            <td class="__Now__ __IsReport__" style="text-align: center">__IfNow__ 〇 __IfNow__</td> -->
                            <td class="__Now1__ __IsReport__">__KojiDate__</td>
                            <!-- <td class="__Now2__ ">__HaifuDownloadDate__</td> -->


                            __IfDeleteCell__
                            <td class="text-center">
                                __IfKanri2__
                                <a href=" #" class="btn btn-danger btn-sm" onclick="javascript:deleteBukken('s_search.php?rKey=__rKey__&m=__m__',__BukkenCD__)"> 削除 </a>
                                __IfKanri2__
                            </td>
                            __IfDeleteCell__

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

            </div>
        </div>
    </div>

    </form>
    __IfMasterMaintenance__
    <div class="container">
        <div class="row mt-2">
            <div class="col-12">
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
                            <td>・<a href="s_branche_list.php?rKey=__rKey__&ClientCD=__wClientCD__">支店・支社マスタ</a></td>
                        </tr>
                        <tr>
                            <td>・<a href="s_gyosya_list.php?rKey=__rKey__&ClientCD=__wClientCD__">協力会社マスタ</a></td>
                        </tr>
                        <tr>
                            <td>・<a href="s_gyosyatanto_list.php?rKey=__rKey__&ClientCD=__wClientCD__">協力会社ユーザーマスタ</a></td>
                        </tr>
                        <tr>
                            <td>・<a href="s_kanricompany_list.php?rKey=__rKey__&ClientCD=__wClientCD__">管理会社マスタ</a></td>
                        </tr>

                        __IfNespe__

                    </table>
                </div>
            </div>
        </div>
    </div>
    __IfMasterMaintenance__
    __IfWorker__
    <br>
    __IfWorker__
    <div class="container">
        <div class="row mt-3">
            <div class="col-12 text-center">
                <!-- __SFooter__
                __SCopyright__ -->
            </div>
        </div>
    </div>
    <script>
    function changeSenyuMonth(){
        if($("#SenyuMonth").val() == ''){
            $('#wSenyuStartDate').removeAttr('disabled');            
            $('#wSenyuEndDate').removeAttr('disabled');            
        }else{
            $('#wSenyuStartDate').attr('disabled', true);
            $('#wSenyuEndDate').attr('disabled', true);
        }
    }

    $("#chkall").on("change", function() {
        $(".chk_item").prop("checked", this.checked);
        if ($(".chk_item:checked").length > 0) {
            $("#btn_sel_delete").show();
        }else{
            $("#btn_sel_delete").hide();
        }
    });

    function delete_chks(){
        if ($(".chk_item:checked").length > 0) {
            if (window.confirm('選択した物件を削除しますか？')) {
                document.mainform.deleteFlg.value = "2";
                document.mainform.submit();
            }
        } else {
            alert("削除する物件を選択してください。");
        }
    }

    $(".chk_item").on("change", function() {
        if ($(".chk_item:checked").length > 0) {
            $("#btn_sel_delete").show();
        }else{
            $("#btn_sel_delete").hide();
        }
    });

    </script>
</body>

</html>