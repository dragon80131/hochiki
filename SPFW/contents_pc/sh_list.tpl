<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>__TITLENAME__</title>
	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
	
  <!-- BootstrapのCSS読み込み -->
  <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="./css/rnsien.css" />


  <!-- jQuery読み込み -->
  <script src="./include/js/jquery-3.2.1.min.js"></script>
  <!-- BootstrapのJS読み込み -->
  <script src="./include/bootstrap/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="./css/scroll-hint.css">
  <script src="./include/js/scroll-hint.min.js"></script>
<script>
  var gFromAddressConfirmed = '1';
  function cancel_change() {
    document.getElementById("hiddenHenkoDate").value = document.getElementById("span5").textContent;
    document.getElementById("hiddenTimeFromTime").value = document.getElementById("hiddenOldTimeFromTime").value;
    document.getElementById("hiddenHanNo").value = document.getElementById("span8").textContent;
    document.getElementById("hiddenViewOrderNo").value = document.getElementById("hiddenOldViewOrderNo").value;

    document.getElementById("span15").textContent = "";
    document.getElementById("span16").textContent = "";
    document.getElementById("span18").textContent = "";

    document.getElementById("after").style.display = "none";
    document.getElementById("after_buttons").style.display = "none";
    if(gFromAddressConfirmed == '2'){
      document.getElementById("confirmBtn").style.display = "block";
      document.getElementById("declineBtn").style.display = "block";
      document.getElementById("decline_blank").style.display = "none";
      document.getElementById("befor").style.display = "flex";
      document.getElementById("befor_buttons").style.display = "flex";
    }else if(gFromAddressConfirmed == '3'){
      document.getElementById("confirmBtn").style.display = "none";
      document.getElementById("declineBtn").style.display = "none";
      document.getElementById("decline_blank").style.display = "block";
      document.getElementById("befor").style.display = "none";
      document.getElementById("befor_buttons").style.display = "none";
    }else{
      document.getElementById("confirmBtn").style.display = "none";
      document.getElementById("declineBtn").style.display = "block";
      document.getElementById("decline_blank").style.display = "none";
      document.getElementById("befor").style.display = "flex";
      document.getElementById("befor_buttons").style.display = "flex";
    }

    $('.link_cell_blank').removeClass('active');
  }

  function clickBtn7(HenkoDate,TimeFromTime,DispWaku,BanNo,ViewOrderNo) {
    if(document.getElementById("befor").style.display == "flex" || document.getElementById("decline_blank").style.display == "block"){
      document.getElementById("hiddenHenkoDate").value = HenkoDate;
      document.getElementById("hiddenTimeFromTime").value = TimeFromTime;
      document.getElementById("hiddenHanNo").value = BanNo;
      document.getElementById("hiddenViewOrderNo").value = ViewOrderNo;

      document.getElementById("span15").textContent = document.getElementById("hiddenHenkoDate").value;
      document.getElementById("span16").textContent = DispWaku;
      document.getElementById("span18").textContent = BanNo;

      document.getElementById("after").style.display = "flex";
      document.getElementById("after_buttons").style.display = "flex";
      document.getElementById("confirmBtn").style.display = "none";
      document.getElementById("declineBtn").style.display = "none";

      document.getElementById("decline_blank").style.display = "none";

      document.getElementById("work").value = 1;
    }

    // IDを設定してページ内リンクを行う
    // 特定の行にスクロールする
/*    
      const targetRow = document.getElementById(`row${HenkoDate}`);
    if (targetRow) {
        targetRow.scrollIntoView({ behavior: 'smooth' });
    }
*/

  }



  function clickBtn8(RoomNo,LastName,TEL,Memo, HenkoDate,TimeFromTime,DispWaku,TimeExactHour,TimeExactMinutes,TimeMeaning, BanNo, ViewOrderNo, ReplyFlg, ConfirmFlg,EMail) {
    document.getElementById("RoomNo").value = RoomNo;
    document.getElementById("LastName").value = LastName;
    document.getElementById("TEL").value = TEL;
    document.getElementById("EMail").value = EMail;
    document.getElementById("Memo").value = Memo;
    if(TimeExactHour)
      document.getElementById("TimeExactHour").value = TimeExactHour;
    if(TimeExactMinutes)
      document.getElementById("TimeExactMinutes").value = TimeExactMinutes;
    document.getElementById("TimeMeaning").value = TimeMeaning;
    document.getElementById("span8").textContent = BanNo;

    let afterElement = document.getElementById("after");
    let afterStyle = window.getComputedStyle(afterElement);
    afterElement.style.display = "none";
    document.getElementById("after_buttons").style.display = "none";

    if(ReplyFlg == '3'){ // 辞退
      document.getElementById("span9").textContent = '「辞退」 ';
      document.getElementById("span9").className = "decline";
      document.getElementById("span20").textContent = '(辞退) ';
      document.getElementById("span20").className = "decline";

      document.getElementById("declineBtn").style.display = "none";
      document.getElementById("changeBtn").style.display = "none";
      document.getElementById("recoveryBtn").style.display = "block";

      if(afterStyle.display == "none"){
        document.getElementById("befor").style.display = "none";
        document.getElementById("befor_buttons").style.display = "none";
        document.getElementById("decline_blank").style.display = "block";
      }else{
        document.getElementById("befor").style.display = "flex";
        document.getElementById("befor_buttons").style.display = "flex";
        document.getElementById("decline_blank").style.display = "none";
      }
      gFromAddressConfirmed = '3';

    }else if(ReplyFlg == '1' || ReplyFlg == '2' || ConfirmFlg == '1'){
      document.getElementById("span9").textContent = '「確定」 ';
      document.getElementById("span9").className = "confirmed";
      document.getElementById("span20").textContent = '(確定) ';
      document.getElementById("span20").className = "confirmed";

      document.getElementById("confirmBtn").style.display = "none";

      if(afterStyle.display == "none"){
        document.getElementById("declineBtn").style.display = "block";
      }else{
        document.getElementById("declineBtn").style.display = "none";
      }
      document.getElementById("changeBtn").style.display = "block";
      document.getElementById("recoveryBtn").style.display = "none";

      document.getElementById("befor").style.display = "flex";
      document.getElementById("befor_buttons").style.display = "flex";
      document.getElementById("decline_blank").style.display = "none";

      gFromAddressConfirmed = '1';
    }else{
      document.getElementById("span9").textContent = '「仮日程」 ';
      document.getElementById("span9").className = "temporary";
      document.getElementById("span20").textContent = '(仮日程) ';
      document.getElementById("span20").className = "temporary";

      if(afterStyle.display == "none"){
        document.getElementById("confirmBtn").style.display = "block";
        document.getElementById("declineBtn").style.display = "block";
      }else{
        document.getElementById("confirmBtn").style.display = "none";
        document.getElementById("declineBtn").style.display = "none";
      }
      document.getElementById("changeBtn").style.display = "block";
      document.getElementById("recoveryBtn").style.display = "none";

      document.getElementById("befor").style.display = "flex";
      document.getElementById("befor_buttons").style.display = "flex";
      document.getElementById("decline_blank").style.display = "none";

      gFromAddressConfirmed = '2';
    }
	
    document.getElementById("hiddenHenkoDate").value = HenkoDate;
    document.getElementById("hiddenTimeFromTime").value = TimeFromTime;
    document.getElementById("hiddenHanNo").value = BanNo;
    document.getElementById("hiddenViewOrderNo").value = ViewOrderNo;

    document.getElementById("hiddenOldTimeFromTime").value = TimeFromTime;
    document.getElementById("hiddenOldViewOrderNo").value = ViewOrderNo;

    document.getElementById("span4").textContent = document.getElementById("RoomNo").value;

    document.getElementById("span5").textContent = document.getElementById("hiddenHenkoDate").value;
    document.getElementById("span6").textContent = DispWaku;
    
    document.getElementById("work").value = 1;
  }
</script>
	
  <style>
    .extra_times{
      display:flex;
      flex-direction:row;
      align-items:center;
      justify-content:flex-start;
      margin-bottom:20px;
    }
    .extra_times .form-control{
      margin-bottom:0;
    }
    #befor{
      display:none;
      flex-direction:row;
      align-items:center;
      justify-content:flex-start;
      flex-wrap:wrap;
      font-size:1rem;
      margin-bottom:5px;
    }
    #befor_buttons{
      display:none;
      flex-direction:row;
      align-items:center;
      justify-content:flex-start;
    }

    #after{
      display:none;
      flex-direction:row;
      align-items:center;
      justify-content:flex-start;
      flex-wrap:wrap;
      font-size:1rem;
      margin-bottom:5px;
    }
    #after_buttons{
      display:none;
      flex-direction:row;
      align-items:center;
      justify-content:flex-start;
    }

    .state-lbl{
      color:#959393;
    }
    .confirmed{
      color:#ef1616;
    }
    .temporary{
      color:#0070c0;
    }
    .decline{
      color:#002060;
    }
    #decline_blank{
      display:none;
      color:#ee0000;
      font-weight:normal;
      font-size:1rem;
    }
    .tooltip-text .confirmed{
      color:#f76060;
    }
    .tooltip-text .temporary{
      color:#7dc4f7;
    }
    table{
      max-width:100%;
    }
    td{
      padding:5px;
    }
    .declineInfo{
      display:flex;
      flex-direction:row;
      align-items:center;
      margin-bottom:5px;
    }
    .declineInfo .label{
      white-space: nowrap;
    }
    .declineInfo .list{
      display:flex;
      flex-direction:row;
      flex-wrap:wrap;
      align-items:flex-start;
      justify-content:flex-start;
    }
    .declineInfo .declineNo{
      background-color: #7f7f7f;
      padding: 4px 10px;
      margin:3px 5px;
      display: block;
      line-height: 1;
      color: #fff;
    }
    .reserve_day{
      font-size:14px;
    }
    .bigo{
      display:flex;
      flex-direction:row;
      align-items:flex-start;
      justify-content:flex-start;
    }
    .bigo textarea{
      max-width:100%;
      width:600px;
      margin-left:5px;
    }
    .header-fix .bigo{
      display:none;
    }

    @media (max-width: 768px) {
      .table td, .table th {
        font-size: 0.9em;
        padding: 8px;
        white-space: nowrap;
      }
      .scroll-hint-icon{
        top:30vh;
      }
    }

    #schedule_content{
      display:block;
      margin:0 auto;
      width:fit-content;
      min-width:1140px;        
      max-width:99vw;
      padding-right: 15px;
      padding-left: 15px;
    }

    @media (max-width: 1200px) {
      #schedule_content{
        width:100%;
        min-width:unset;
        max-width:1140px;
        overflow-x:auto;
      }
    }
    @media (max-width: 992px) {
      #schedule_content{
        max-width:960px;
      }
    }
    @media (max-width: 768px) {
      #schedule_content{
        max-width:720px;
      }
    }
    @media (max-width: 576px) {
      #schedule_content{
        max-width:540px;
      }
    }    

    .serch_panel{
      display:flex;
      align-items:center;
      justify-content:flex-start;
      font-size:14px;
    }
    #srch_room{
      width:100px;
      margin-bottom:0;
      margin-left:30px;
      margin-right:5px;
      padding:.2rem .5rem;
    }
    .serch_panel .btn{
      margin-left:5px;
    }

    .header-fix .serch_panel{
      display:none;
    }

    .botton-group{
      display:flex;
      flex-direction:row;
      align-items:center;
    }

    
  </style>
	
</head>


<body id="main">
  <div class="container" id="header-container">
      <div class="row">
          <div class="col-12 text-center">
              __SHeaderKanri2__
          </div>
      </div>
  </div>
<form id="myForm" action="sh_list.php?editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__" method="POST" name="main">
  <div class="top-part">
    <div class="container">
        <div class="row">
            <div class="col-12">
              <div class="d-flex align-items-end flex-wrap">
                <a href="s_menu.php__QUERY__&rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-info mb-1 mr-1">メニュー</a>
                __IfWorker__
                <a href="s_kihon_form.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-brown mb-1 mr-1">物件基本情報</a>
                <a href="sh_henko_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-success mb-1 mr-1">作業工程表</a>
                <a href="s_kanryo.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-dark-blue mb-1 mr-1">完了報告</a>
                __IfWorker__
                &nbsp;
                <ul class="nav nav-tabs mb-1 building_nav">
                  __IfBuildingExist__
                  <li class="nav-item">
                    <a class="nav-link __mainNaviClass__" href="sh_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">__wBuildingName__</a>
                  </li>
                  __IfBuildingExist__
                  __BuildingLoop__
                  <li class="nav-item">
                    <a class="nav-link __naviClass__" href="sh_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__BuildingCD__">__BuildingName__</a>
                  </li>
                  __BuildingLoop__
                </ul>		
              </div>
              <div class="mt-1 bigo">
              <span style="white-space:nowrap">備考:</span>
              <textarea class="form-control" name="Biko" id="Biko" rows="2">__wBiko__</textarea>
              <button type="submit" class="btn btn-success btn-sm ml-2" name="bikoreg" value="yes">登録</button>
              </div> 
              <div class="mt-1">
              部屋番号をクリック、次に、「空き」をクリックしてください。<a href="#reg_form_start">ページ下部</a>のお客さま情報を入力し登録します。
              </div> 
              <div class="mt-1 serch_panel">
              <input type="text" id="srch_room" class="form-control">
              号室
              <button type="button" class="btn btn-success btn-sm" onclick="search_room();">検索</button>
              </div> 

              <div class="top_controls" style="font-weight:bold; margin-top:10px;">
                <span id="span9"></span>
                部屋番号: <span id="span4" style="font-size: 24px;"></span>
                <div id="decline_blank">
                復活する場合は「空き」枠を選択してください
                </div>
                <div id="befor">
                  <span class="state-lbl">【変更前】</span>
                  選択日: <span id="span5" style="font-size: 20px;"></span>&nbsp;&nbsp;&nbsp;
                  時間帯: <span id="span6" style="font-size: 20px;"></span>&nbsp;&nbsp;&nbsp;
                  担当班: <span id="span8" style="font-size: 24px;"></span>班&nbsp;&nbsp;&nbsp;
                </div>  
                <div id="after">
                  <span class="state-lbl">【変更後】</span>
                  選択日: <span id="span15" style="font-size: 20px;"></span>&nbsp;&nbsp;&nbsp;
                  時間帯: <span id="span16" style="font-size: 20px;"></span>&nbsp;&nbsp;&nbsp;
                  担当班: <span id="span18" style="font-size: 24px;"></span>班&nbsp;&nbsp;&nbsp;
                </div>  
              </div>
            </div>

          </div>
      </div>
  </div>
  <div class="container">
      <div class="row">
          <div class="col-12">
            <div>
              <!-- 全空き枠: <span id="span7" style="font-size: 22px;">__EmptyFrameCount__</span>件 -->
              <div class="declineInfo">
                <span class="label">辞退希望:</span><div class="list">__declineResult__</div>
              </div>  
            </div>  
          </div>  
      </div>  
  </div>  
              <div id="schedule_content">
                <div class="js-scrollable">
                  __Koteihyou__
                </div>    
              </div>

  <div class="container">
      <div class="row">
          <div class="col-12">              
              __IfErr__ 
                <center>
                排水管洗浄の日程変更をネットから受け付けるシステムです。<br>
                システム利用などご用の方は、06-7777-3186 <a href="https://www.nespe-jp.com">株式会社ネスペ</a>までお問合せください。
                </center>
              __IfErr__

                <input type="hidden" name="rKey" value="__rKey__"  />
                <input type="hidden" name="HenkoDate" value="red" id="hiddenHenkoDate" />
                <input type="hidden" name="TimeFromTime" value="red" id="hiddenTimeFromTime" />
                <input type="hidden" name="HanNo" value="1" id="hiddenHanNo" />
                <input type="hidden" name="ViewOrderNo" value="0" id="hiddenViewOrderNo" />

                <input type="hidden" name="OldTimeFromTime" value="" id="hiddenOldTimeFromTime" />
                <input type="hidden" name="OldViewOrderNo" value="0" id="hiddenOldViewOrderNo" />
                
                <input type="hidden" name="work" value="" id="work" />				
                
                <div class="mt-3">
                  <span id="span20"></span>
                </div>           
                <div class="mt-4">
                  <a href="#main">ページ上部に移動</a>
                </div>           

                <div id="reg_form_start" class="form-group mt-1">
                  <label for="" class="midashi2">部屋番号</label>
                  <input type="text" name="RoomNo"  id="RoomNo" value="" class="form-control" style="width:200px;" readonly>
                </div>

                <div class="form-group">
                  <label for="" class="midashi">名前</label>
                  <input type="text" name="Name"  id="LastName"  value="" class="form-control" style="width:200px;">
                  <!-- <font color="gray" size="2">※マンション名のみをご記入ください。</font> -->
                </div>

                <div class="form-group">
                  <label for="" class="midashi">連絡先</label>
                  <input type="text" name="TEL"  id="TEL"  value="" class="form-control" style="width:200px;">
                </div>

                <div class="form-group">
                  <label for="" class="midashi">メールアドレス</label>
                  <input type="email" name="EMail"  id="EMail"  value="" class="form-control" style="width:200px;">
                </div>

                <div class="form-group">
                  <label for="" class="midashi">時間指定</label>
                  <div class="extra_times">
                    <select class="form-control" id="TimeExactHour" name="TimeExactHour" style="width:70px;">
                      <option value=""></option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                      <option value="9">9</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                      <option value="12">12</option>
                      <option value="13">13</option>
                      <option value="14">14</option>
                      <option value="15">15</option>
                      <option value="16">16</option>
                      <option value="17">17</option>
                      <option value="18">18</option>
                      <option value="19">19</option>
                      <option value="20">20</option>
                      <option value="21">21</option>
                    </select>
                    :
                    <select class="form-control" id="TimeExactMinutes" name="TimeExactMinutes" style="width:70px;">
                      <option value=""></option>
                      <option value="00">00</option>
                      <option value="15">15</option>
                      <option value="30">30</option>
                      <option value="45">45</option>
                    </select>
                    <select class="form-control" id="TimeMeaning" name="TimeMeaning" style="width:150px; margin-left:10px">
                      <option value=""></option>
                      <option value="指定">指定</option>
                      <option value="頃">頃</option>
                      <option value="早め">早め</option>
                      <option value="遅め">遅め</option>
                      <option value="までに完了">までに完了</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="" class="midashi">備考</label>
                  <input type="text" name="Memo"  id="Memo"  value="" class="form-control" style="width:600px; max-width:100%">
                </div>

                <div class="botton-group">
                  <div id="befor_buttons">
                    <input id="confirmBtn" type="button" class="btn btn-success ml-1" value="登録" onclick="show_confirm();">
                    <input id="declineBtn" type="button" class="btn btn-dark ml-1" value="辞退" onclick="declineAction();">
                  </div>  

                  <div id="after_buttons">
                    <input id="changeBtn" type="button" class="btn btn-success ml-1" value="変更" onclick="show_confirm2();">
                    <input id="recoveryBtn" type="button" class="btn btn-warning ml-1" value="復活" onclick="recoveryAction();">
                    <input type="button" class="btn btn-danger ml-1" value="キャンセル" onclick="cancel_change();">
                  </div>  
                </div>

          </div><!-- /col-12 -->
      </div><!-- /row -->
  </div><!-- /container -->


  <div class="content-all">
	</div>
</form>

<div id="confirm_modal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">登録確認</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p style="padding-left:0">登録情報は以下の内容でよろしいでしょうか。</p>

        <table cellpadding="5" cellspacing="10">
          <tr>
            <td width="20%">部屋番号:</td>
            <td width="80%" id="confirm_RoomNo"></td>
          </tr>
          <tr>
            <td>選択日時:</td>
            <td id="confirm_Date"></td>
          </tr>
          <tr>
            <td>担当班:</td>
            <td id="confirm_Hansu"></td>
          </tr>
          <tr>
            <td>名前:</td>
            <td id="confirm_LastName"></td>
          </tr>
          <tr>
            <td>連絡先:</td>
            <td id="confirm_TEL"></td>
          </tr>
          <tr>
            <td>メールアドレス:</td>
            <td id="confirm_EMail"></td>
          </tr>
          <tr>
            <td>時間指定:</td>
            <td id="confirm_Time"></td>
          </tr>
          <tr>
            <td>備考:</td>
            <td id="confirm_Memo"></td>
          </tr>

        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
        <button type="button" class="btn btn-success" onclick="regist();">登録</button>
      </div>
    </div>
  </div>
</div>

<div id="confirm2_modal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">変更確認</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>変更情報は以下の内容でよろしいでしょうか。</p>

        <table cellpadding="5" cellspacing="10">
          <tr>
            <td width="25%" valign="top">【変更前】</td>
            <td width="75%" id="confirm_Prev">
            </td>
          </tr>
          <tr>
            <td valign="top">【変更後】</td>
            <td id="confirm_Next">
            </td>
          </tr>

        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
        <button type="button" class="btn btn-success" onclick="modifyAction();">変更</button>
      </div>
    </div>
  </div>
</div>
    __SFooter__ __SCopyright__
  </body>

  <script>
    document.getElementById('myForm').addEventListener('submit', function(event) {
      // 名前のフィールドを取得
    //   const nameInput = document.getElementById('LastName');
    //   const nameError = document.getElementById('nameError');

    //   // エラーメッセージをリセット
    //   nameError.textContent = '';

    //   // 名前が空であるかをチェック
    //   if (nameInput.value.trim() === '') {
    //     // エラーメッセージを表示
    //     nameError.textContent = '名前を入力してください。';
        
    //     // フォームの送信をキャンセル
    //     event.preventDefault();
    //     return false;
    //   }

      // 名前が入力されていればフォームを送信
      return true;
    });

    $(function () {
      $('a[href^="#"]').click(function() {
          var wWidth = $(window).width();
          if($(this).attr('href') == "#")
            return false;
          if ($($(this).attr('href')).length) {
              var p = $($(this).attr('href')).offset();
              $('html,body').animate({
                  scrollTop: p.top
              }, 500);
          }
          return false;
      });
    });

    $('.link_cell').click(function() {
      $('.link_cell').removeClass('active');
      $(this).addClass('active');
    });

    $('.link_cell_blank').click(function() {
      $('.link_cell_blank').removeClass('active');
      $(this).addClass('active');
    });

    function show_confirm(){
      if($("#RoomNo").val() == ""){
        alert("部屋番号を選択してください。");
        return;
      }
      $('#confirm_RoomNo').html($('#RoomNo').val());
      let HenkoDateStr = $('#hiddenHenkoDate').val();
      let HenkoTimeStr = $('#hiddenTimeFromTime').val();
      if(HenkoTimeStr == 'red')
        HenkoTimeStr = '';
      $('#confirm_Date').html(getDateString(HenkoDateStr)+' '+HenkoTimeStr);
      $('#confirm_Hansu').html($('#hiddenHanNo').val()+'班');
      $('#confirm_LastName').html($('#LastName').val());
      $('#confirm_TEL').html($('#TEL').val());
      $('#confirm_EMail').html($('#EMail').val());
      let confirm_Time = '';
      if($('#TimeExactHour').val() || $('#TimeExactMinutes').val() || $('#TimeMeaning').val()){
        confirm_Time = $('#TimeExactHour').val() + ':' + $('#TimeExactMinutes').val() + " " +$('#TimeMeaning').val();
      }
      $('#confirm_Time').html(confirm_Time);
      $('#confirm_Memo').html($('#Memo').val());
      $('#confirm_modal').modal('show');
    }

    function regist(){
      document.getElementById("work").value = 1;
      $("#myForm").submit();
    }

    function show_confirm2(){
      var sHTML1 = $('#span9').html() + '<br>' +
              '部屋番号: ' + $('#span4').html() +'<br>' +
              '選択日: ' + $('#span5').html() + '<br>' +
              '時間帯: ' + $('#span6').html() + '<br>' +
              '担当班: ' + $('#span8').html() + '班';
      $("#confirm_Prev").html(sHTML1);

      let confirm_Time = '';
      if($('#TimeExactHour').val() || $('#TimeExactMinutes').val() || $('#TimeMeaning').val()){
        confirm_Time = $('#TimeExactHour').val() + ':' + $('#TimeExactMinutes').val() + " " +$('#TimeMeaning').val();
      }

      var sHTML2 = '「確定」' + '<br>' +
              '部屋番号: ' + $('#span4').html() +'<br>' +
              '選択日: ' + $('#span15').html() + '<br>' +
              '時間帯: ' + $('#span16').html() + '<br>' +
              '担当班: ' + $('#span18').html() + '班' + '<br>' +
              '名前: ' + $('#LastName').val() + '<br>' +
              '連絡先: ' + $('#TEL').val() + '<br>' +
              'メールアドレス: ' + $('#EMail').val() + '<br>' +
              '時間指定: ' + confirm_Time + '<br>' +
              '備考: ' + $('#Memo').val();

      $("#confirm_Next").html(sHTML2);

      $('#confirm2_modal').modal('show');
    }

    function modifyAction(){
      document.getElementById("work").value = 3;
      $("#myForm").submit();
    }

    function confirmAction(){
      if(confirm("この日程を確定しますか？")){
        document.getElementById("work").value = 2;
        $("#myForm").submit();
      }
    }

    function declineAction(){
      if(confirm("この日程を辞退しますか？")){
        document.getElementById("work").value = 4;
        $("#myForm").submit();
      }
    }

    function recoveryAction(){
      if(confirm("この日程を復活しますか？")){
        document.getElementById("work").value = 5;
        $("#myForm").submit();
      }
    }

    function getDateString(HenkoDateStr){
      if(HenkoDateStr == "" || HenkoDateStr == "red")
        return '';
      let HenkoDateVal = new Date(HenkoDateStr);
      let HenkoDateYear = HenkoDateVal.getFullYear();
      if(isNaN(HenkoDateYear))
        return '';
      let HenkoDateMonth = HenkoDateVal.getMonth() + 1;
      if(isNaN(HenkoDateMonth))
        return '';
      let HenkoDateDay = HenkoDateVal.getDate();
      if(isNaN(HenkoDateDay))
        return '';
      let daysOfWeek = ['日', '月', '火', '水', '木', '金', '土'];
      let dayOfWeek = daysOfWeek[HenkoDateVal.getDay()];
      if(dayOfWeek == undefined)
        return '';
      return HenkoDateYear+'年'+HenkoDateMonth+'月'+HenkoDateDay+'日'+'('+dayOfWeek+')';
    }

    var nWindowWidth = window.innerWidth 
         || document.documentElement.clientWidth 
         || document.body.clientWidth;
    if(nWindowWidth < 768){
      new ScrollHint('.js-scrollable');
    }

    window.addEventListener('scroll', function(){
      var header = document.getElementsByTagName('body')[0];
      if (document.body.scrollTop > 198 || document.documentElement.scrollTop > 198) {
          if (!header.classList.contains('header-fix')) {
              header.classList.add('header-fix');
          }
      }
      else {
          if (header.classList.contains('header-fix')) {
              header.classList.remove('header-fix');
          }
      }
    });

    function search_room(){
      var roomID = $("#srch_room").val();
      if(roomID){
        if($("#room_"+roomID).length){
          let el = document.getElementById("room_"+roomID);
          let y = el.getBoundingClientRect().top - $("#header-container").height() - $(".top-part").height() - 90; 
          // const y = el.getBoundingClientRect().top; 
          if(y < 0)
            y = 0;
          window.scrollTo({ top: y, behavior: "smooth" });
          el.click();
        }
      }
    }

  $('#srch_room').on('keypress', function (e) {
      if (e.which === 13) {
          e.preventDefault();
          search_room();
      }
  });    

  </script>

</html>
