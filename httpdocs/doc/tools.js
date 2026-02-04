function move(page) {
	document.mainform.action = page;
	document.mainform.submit(true);
}
function move2(page) {
	document.mainform2.action = page;
	document.mainform2.submit(true);
}

function move3(page) {
	document.mainform2.action = page;
	document.mainform2.submit(true);
}

function moveWithKensaku(page, kBukkenCD,kBukkenName,kAddress,kKanriGaisya,kRNState,kTaioPhase,dummy) {
	document.mainform3.kBukkenCD.value = kBukkenCD;
	document.mainform3.kBukkenName.value = kBukkenName;
	document.mainform3.kAddress.value = kAddress;
	document.mainform3.kKanriGaisya.value = kKanriGaisya;
	document.mainform3.kRNState.value = kRNState;
	document.mainform3.kTaioPhase.value = kTaioPhase;
}

function moveWithKensaku2(page, kBukkenCD,kBukkenName,kAddress,kKanriGaisya,kRNState,kTaioPhase,kSitenSelect) {
	document.mainform3.kBukkenCD.value = kBukkenCD;
	document.mainform3.kBukkenName.value = kBukkenName;
	document.mainform3.kAddress.value = kAddress;
	document.mainform3.kKanriGaisya.value = kKanriGaisya;
	document.mainform3.kRNState.value = kRNState;
	document.mainform3.kTaioPhase.value = kTaioPhase;
//	document.mainform3.dummy.value = dummy;
	document.mainform3.kSitenSelect.value = kSitenSelect;
	document.mainform3.action = page;
	document.mainform3.submit(true);

}


function moveWithWork(page, work) {
	document.mainform.work.value = work;
	document.mainform.action = page;
	document.mainform.submit(true);
	document.mainform.work.value = '';
}

function moveWithKey(page, key) {
	document.mainform.editBukkenCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

function moveWithKey2(page, key) {
	document.mainform.editGenbaCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

function moveWithKeyWithWork(page, key , work ) {
	document.mainform.editBukkenCD.value = key;
	document.mainform.work.value = work;
	document.mainform.action = page;
	document.mainform.submit(true);
}

function moveWithAngle(page, key ) {
	document.mainform.angle.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

function moveWithSortKey(page, key) {
	document.mainform2.SortKey.value = key;
	document.mainform2.action = page;
	document.mainform2.submit(true);
}


function moveWithTantoCD(page, key) {
	document.mainform.editTantoCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

function moveWithTaioCD(page, key) {
	document.mainform2.editTaioCD.value = key;
	document.mainform2.action = page;
	document.mainform2.submit(true);
}


function moveWithUserCD(page, key) {
	document.mainform.editUserCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

function moveWithAccessDeviceIDCD(page, key) {
	document.mainform.editAccessDeviceIDCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}


function moveWithShozokuCD(page, key) {
	document.mainform.editShozokuCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}


function moveWithEigyoshoCD(page, key) {
	document.mainform.editEigyoshoCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}


function moveWithSitenCD(page, key) {
	document.mainform.editSitenCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}


function moveWithGyosyaCD(page, key) {
	document.mainform.editGyosyaCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

function moveWithGyosyaTantoCD(page, key) {
	document.mainform.editGyosyaTantoCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

function moveWithDeviceCD(page, key) {
	document.mainform.editDeviceCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}



function moveWithSystemCD(page, key) {
	document.mainform2.editSystemCD.value = key;
	document.mainform2.action = page;
	document.mainform2.submit(true);
}

function moveWithKeibiCD(page, key) {
	document.mainform2.editKeibiCD.value = key;
	document.mainform2.action = page;
	document.mainform2.submit(true);
}

function moveWithTakuhaiCD(page, key) {
	document.mainform2.editTakuhaiCD.value = key;
	document.mainform2.action = page;
	document.mainform2.submit(true);
}



function moveWithKeyAndWork(page, key, work, msg) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform.editBukkenCD.value = key;
			document.mainform.work.value = work;
			document.mainform.action = page;
			document.mainform.submit(true);
			document.mainform.work.value = '';
		}
	}
}


function moveWithKeyAndWork2(page, key, work, msg) {
	if (work == 1){
		if (window.confirm("データ登録を確定してよろしいですか。")) {
			document.mainform.editBukkenCD.value = key;
			document.mainform.work.value = work;
			document.mainform.action = page;
			document.mainform.submit(true);
			document.mainform.work.value = '';
		}
	}
}


function moveWithKeyAndWork3(page, key, work, msg) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform.editUserCD.value = key;
			document.mainform.work.value = work;
			document.mainform.action = page;
			document.mainform.submit(true);
			document.mainform.work.value = '';
		}
	}
}

function moveWithKeyAndWorkEigyosho(page, key, work, msg) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform.editEigyoshoCD.value = key;
			document.mainform.work.value = work;
			document.mainform.action = page;
			document.mainform.submit(true);
			document.mainform.work.value = '';
		}
	}
}

function moveWithKeyAndWorkSiten(page, key, work, msg) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform.editSitenCD.value = key;
			document.mainform.work.value = work;
			document.mainform.action = page;
			document.mainform.submit(true);
			document.mainform.work.value = '';
		}
	}
}

function moveWithKeyAndWorkDevice(page, key, work, msg) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform.editDeviceCD.value = key;
			document.mainform.work.value = work;
			document.mainform.action = page;
			document.mainform.submit(true);
			document.mainform.work.value = '';
		}
	}
}


function moveWithKeyAndWork5(page, key, work, msg) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform2.editTaioCD.value = key;
			document.mainform2.work.value = work;
			document.mainform2.action = page;
			document.mainform2.submit(true);
			document.mainform2.work.value = '';
		}
	}
}


function moveWithKeyAndWork6(page, key, work, msg) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform2.editSystemCD.value = key;
			document.mainform2.work.value = work;
			document.mainform2.action = page;
			document.mainform2.submit(true);
			document.mainform2.work.value = '';
		}
	}
}


function moveWithKeyAndWork7(page, key, work, msg) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform2.editKeibiCD.value = key;
			document.mainform2.work.value = work;
			document.mainform2.action = page;
			document.mainform2.submit(true);
			document.mainform2.work.value = '';
		}
	}
}


function moveWithKeyAndWork8(page, key, work, msg) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform2.editTakuhaiCD.value = key;
			document.mainform2.work.value = work;
			document.mainform2.action = page;
			document.mainform2.submit(true);
			document.mainform2.work.value = '';
		}
	}
}

function moveWithKeyAndWork9(page, key, work) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform.editGyosyaTantoCD.value = key;
			document.mainform.work.value = work;
			document.mainform.action = page;
			document.mainform.submit(true);
			document.mainform.work.value = '';
		}
	}
}

function moveWithKeyAndWork10(page, key, work) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform.editGyosyaCD.value = key;
			document.mainform.work.value = work;
			document.mainform.action = page;
			document.mainform.submit(true);
			document.mainform.work.value = '';
		}
	}
}


function moveWithKeyAndWork11(page, key, work) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform.editFileCD.value = key;
			document.mainform.work.value = work;
			document.mainform.action = page;
			document.mainform.submit(true);
			document.mainform.work.value = '';
		}
	}
}




function changePage(page, myPage, allPage) {
	if (myPage == 0)
		myPage = document.mainform.myPage.value;

	if (myPage < 0 || myPage > allPage)
		alert("ページ指定が不正です。再度確認してみてください。");
	else{
		if (myPage > 0)
			document.mainform.myPage.value = myPage;
		document.mainform.action = page;
		document.mainform.submit(true);
		return false;
	}
}



function changePage2(page, myPage, allPage) {
	if (myPage == 0)
		myPage = document.mainform2.myPage.value;

	if (myPage < 0 || myPage > allPage)
		alert("ページ指定が不正です。再度確認してみてください。");
	else{
		if (myPage > 0)
			document.mainform2.myPage.value = myPage;
		document.mainform2.action = page;
		document.mainform2.submit(true);
		return false;
	}
}

function changePageWithName(page, myPage, allPage, pagePart) {
	var pageObj = document.getElementsByName(pagePart);

	if (myPage == 0)
		myPage = pageObj.item(0).value;

	if (myPage < 0 || myPage > allPage)
		alert("ページ指定が不正です。再度確認してみてください。");
	else{
		if (myPage > 0)
			pageObj.item(0).value = myPage;
		document.mainform.action = page;
		document.mainform.submit(true);
		return false;
	}
}

function changeSort(page, sortBy) {
	document.mainform.SortBy.value = sortBy;
	document.mainform.action = page;
	document.mainform.submit(true);
	return false;
}

function moveWithWork(file, work) {
	document.mainform.action = file;
	document.mainform.work.value = work;
	document.mainform.submit(true);
	document.mainform.work.value = '';
}

function getLocationOfCursor(id) {
	if(document.getElementById)
		obj = document.getElementById(id);
	else if(document.all)
		obj = document.all[id];

	if(obj.createTextRange)
		obj.caretPos = document.selection.createRange().duplicate();
}

function putSelectedItem(selecteditem, id) {
	if(document.getElementById)
		obj = document.getElementById(id);
	else if(document.all)
		obj = document.all[id];

	var num = selecteditem.selectedIndex;
	var text = selecteditem.options[num].value;

	if(obj.createTextRange && obj.caretPos)
	{
		caretPos = obj.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
	}
	else if(obj.getSelection && obj.caretPos)
	{
		caretPos = obj.caretPos;
		caretPos.text = caretPos.text.charat(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
	}
	else
		obj.value = obj.value + text;

	selecteditem.selectedIndex = 0;
	obj.focus();
}

function controlOutput(tmp){
	//document.mainform.reset();
	document.mainform.sSearchType.value = tmp;

	outputNothing();

	if (tmp == 1){
		document.all.blockTitle.style.display = "";
		document.all.blockName.style.display = "";
		document.all.blockAction.style.display = "";
		document.all.blockStatus.style.display = "";
	}
	else if (tmp == 2){
		document.all.blockTitle.style.display = "";
		document.all.blockEMail.style.display = "";
		document.all.blockAction.style.display = "";
		document.all.blockStatus.style.display = "";
	}
	else if (tmp == 3){
		outputAll();
	}
	else if (tmp == 4){
		document.mainform.submit(true);
	}
}

function outputNothing(){
	document.all.blockTitle.style.display = "none";
	document.all.blockName.style.display = "none";
	document.all.blockAddress.style.display = "none";
	document.all.blockAge.style.display = "none";
	document.all.blockGender.style.display = "none";
	document.all.blockEMail.style.display = "none";
	document.all.blockJoined.style.display = "none";
	document.all.blockWithdrawn.style.display = "none";
	document.all.blockCarrier.style.display = "none";
	document.all.blockStatus.style.display = "none";
	document.all.blockMukouFlg.style.display = "none";
	document.all.blockAction.style.display = "none";
}

function outputAll(){
	document.all.blockTitle.style.display = "";
	document.all.blockName.style.display = "";
	document.all.blockAddress.style.display = "";
	document.all.blockAge.style.display = "";
	document.all.blockGender.style.display = "";
	document.all.blockEMail.style.display = "";
	document.all.blockJoined.style.display = "";
	document.all.blockWithdrawn.style.display = "";
	document.all.blockCarrier.style.display = "";
	document.all.blockStatus.style.display = "";
	document.all.blockMukouFlg.style.display = "";
	document.all.blockAction.style.display = "";
}

function moveWithUserKey(page, key) {
	document.mainform.editUserCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithUserKeyAlert(page, key, work) {
	if (work == 4 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editUserCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
		document.mainform.work.value = '';
	}
	else if (work != 4) {
		document.mainform.action = page;
		document.mainform.editUserCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
		document.mainform.work.value = '';
	}
}

function moveWithDeliveryKey(page, key) {
	document.mainform.editDeliveryCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithDeliveryKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editDeliveryCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
		document.mainform.work.value = '';
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editDeliveryCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
		document.mainform.work.value = '';
	}
}

function moveWithDeliveryKeyWork2(page, key, work) {
	document.mainform.action = page;
	document.mainform.editDeliveryCD.value = key;
	document.mainform.work2.value = work;
	document.mainform.submit(true)
}

function moveWithAdminKey(page, key) {
	document.mainform.editAdminCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithAdminKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editAdminCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editAdminCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithScenarioKey(page, key) {
	document.mainform.editScenarioCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithScenarioKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editScenarioCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editScenarioCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

// プログラム移動
function movePopupPage(page, work) {
	document.mainform.action = page;
	document.mainform.target = "NEW";
	document.mainform.work.value = work;
	document.mainform.submit(true)
	document.mainform.target = "";
	document.mainform.work.value = '';
}

// コードを伴ったプログラム移動
function moveWithStepMailKey(page, key) {
	document.mainform.editStepMailCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true)
}

// コードを伴ったプログラム移動
function moveWithStepMailKeyWork(page, key, work) {
	if (work == 2)
		judge = confirm('ご指定の配信を一時的に停止します。よろしいですか。');
	else if (work == 3)
		judge = confirm('ご指定の配信を再び稼動させます。よろしいですか。');
	else if (work == 4)
		judge = confirm('ご指定の配信を削除します。よろしいですか。');

	if (judge) {
		document.mainform.work.value = work;
		document.mainform.editStepMailCD.value = key;
		document.mainform.action = page;
		document.mainform.submit(true)
	}
}

// アラート付きプログラム移動
function moveWithAlert(page, work) {
	if (work == 4 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 4) {
		document.mainform.action = page;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithInquiryKey(page, key) {
	document.mainform.editInquiryCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true)
}

function moveWithQuestionKey(page, key, max) {
	if (page == 'user_ex_property_detail.php' && key == -1 && max == 1) {
		alert('設問を設置できる上限を超えましたので、設問追加できません。');
		return false;
	}

	document.mainform.editQuestionCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true)
}

// コードを伴ったプログラム移動
function moveWithQuestionKeyAndWork(page, key, key2) {
	if (key2 == 2) {
		if (confirm('設問削除はサイト開設前以外は緊急手段です。会員データの整合が取れなくなる恐れがあります。本当に削除しますか。')) {
			document.mainform.editQuestionCD.value = key;
			document.mainform.work.value = key2;
			document.mainform.action = page;
			document.mainform.submit(true)
		}
	}
	else if (key2 != 2) {
		document.mainform.editQuestionCD.value = key;
		document.mainform.work.value = key2;
		document.mainform.action = page;
		document.mainform.submit(true)
	}
}

function moveWithClientKey(page, key) {
	document.mainform.editClientCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithClientKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editClientCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editClientCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithTemplateKey(page, key, max) {
	if (page == 'user_ex_property_detail.php' && key == -1 && max == 1) {
		alert('設問を設置できる上限を超えましたので、設問追加できません。');
		return false;
	}

	document.mainform.editTemplateCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true)
}

// コードを伴ったプログラム移動
function moveWithTemplateKeyAndWork(page, key, key2) {
	if (key2 == 2) {
		if (confirm('テンプレートの削除をしようとしています。本当に削除しますか。')) {
			document.mainform.editTemplateCD.value = key;
			document.mainform.work.value = key2;
			document.mainform.action = page;
			document.mainform.submit(true)
		}
	}
}

function moveWithURLKey(page, key) {
	document.mainform.editURLCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithURLKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editURLCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editURLCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithEnqueteKey(page, key) {
	document.mainform.editEnqueteCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithEnqueteKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editEnqueteCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editEnqueteCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithChoiceKey(page, key) {
	document.mainform.editChoiceCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithChoiceKeyAlert(page, key, work) {
	if (work == 5 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editChoiceCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 5) {
		document.mainform.action = page;
		document.mainform.editChoiceCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithAnswerKey(page, key) {
	document.mainform.editAnswerCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithAnswerKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editAnswerCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editAnswerCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithCouponKey(page, key) {
	document.mainform.editCouponCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithCouponKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editCouponCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editCouponCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithCouponLogKey(page, key) {
	document.mainform.editLogCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithCouponLogKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editLogCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editLogCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithGoodsKey(page, key) {
	document.mainform.editGoodsCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithGoodsKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editGoodsCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editGoodsCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithExchangeLogKey(page, key) {
	document.mainform.editLogCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithExchangeLogKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editLogCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editLogCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithStylistKey(page, key) {
	document.mainform.editStylistCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithStylistKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editStylistCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editStylistCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithMenuKey(page, key) {
	document.mainform.editMenuCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}
function moveWithTantoKey(page, key) {
	document.mainform.editTantoCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithMenuKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editMenuCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editMenuCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithReservationKey(page, key) {
	document.mainform.editReservationCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithReservationKeyAlert(page, key, work) {
	if ((work == 10 || work == 4) && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editReservationCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (!(work == 10 || work == 4)) {
		document.mainform.action = page;
		document.mainform.editReservationCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithCalendarKey(page, key) {
	document.mainform.editCalendarCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithCalendarKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editCalendarCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editCalendarCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithFileKey(page, key) {
	document.mainform.editFileCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithFileKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editFileCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editFileCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function moveWithContentsKey(page, key) {
	document.mainform.editContentsCD.value = key;
	document.mainform.action = page;
	document.mainform.submit(true);
}

// アラート付きプログラム移動
function moveWithContentsKeyAlert(page, key, work) {
	if (work == 2 && window.confirm("データを削除しようとしています。本当によろしいですか。")) {
		document.mainform.action = page;
		document.mainform.editContentsCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
	else if (work != 2) {
		document.mainform.action = page;
		document.mainform.editContentsCD.value = key;
		document.mainform.work.value = work;
		document.mainform.submit(true)
	}
}

function reserve(tmp) {
	window.opener.mainform.wUserCD.value = tmp;
	window.opener.userdataDisp();
	window.close();
}

function clock(flag) {
	var	year, month, day, hour, minute, second;
	// 現在時間データ取得
	var	dateobj	= new Date();

	year = dateobj.getYear();
	month = dateobj.getMonth() + 1;
	day = dateobj.getDate();

	if (month < 10)
		month = "0" + month;
	if (day < 10)
		day = "0" + day;

	// 時間取得
	hour	= dateobj.getHours();

	// 分取得。１桁の場合は十の位に０を表示
	if (( minute = dateobj.getMinutes() ) < 10)
		minute = "0" + minute;

	// 秒取得。１桁の場合は十の位に０を表示
	if (( second = dateobj.getSeconds() ) < 10)
		second  = "0" + second;

	/*if (flag) {
		// flag = true のとき、時計のフォームを表示
		document.write("<div id=\"clock3_form\"> </div>");
		flag = false;
	}*/

	clock3_form.innerText = year + "/" + month + "/" + day + " " + hour + ":" + minute + ":" + second + "";
	setTimeout("clock(" + flag + ")", 500);
}






var TimeOut         = 300;
var currentLayer    = null;
var currentitem     = null;
var currentLayerNum = 0;
var noClose         = 0;
var closeTimer      = null;

function mopen(n) {
  var l  = document.getElementById("menu"+n);
  var mm = document.getElementById("mmenu"+n);
	
  if(l) {
    mcancelclosetime();
    l.style.visibility='visible';
    if(currentLayer && (currentLayerNum != n))
      currentLayer.style.visibility='hidden';
    currentLayer = l;
    currentitem = mm;
    currentLayerNum = n;			
  } else if(currentLayer) {
    currentLayer.style.visibility='hidden';
    currentLayerNum = 0;
    currentitem = null;
    currentLayer = null;
 	}
}

function mclosetime() {
  closeTimer = window.setTimeout(mclose, TimeOut);
}

function mcancelclosetime() {
  if(closeTimer) {
    window.clearTimeout(closeTimer);
    closeTimer = null;
  }
}

function mclose() {
  if(currentLayer && noClose!=1)   {
    currentLayer.style.visibility='hidden';
    currentLayerNum = 0;
    currentLayer = null;
    currentitem = null;
  } else {
    noClose = 0;
  }
  currentLayer = null;
  currentitem = null;
}

function showLength(str, target, limit) {
	if (limit > 0) {
		remain = limit - str.length;
		document.getElementById(target).innerHTML = str.length + "文字(残り" + remain + "文字)";
	}
	else {
		document.getElementById(target).innerHTML = str.length + "文字";
	}
}

function toggle(id, target) {
	var ua;
	var show;
	ua = navigator.userAgent.toLowerCase();
	if (ua.indexOf("msie") != -1)
		show = 'block';
	else
		show = 'table-row';

	if (target == 0) {
		if (document.getElementById(id).style.display == 'none')
			document.getElementById(id).style.display = show;
		else
			document.getElementById(id).style.display = 'none';
	}
	else if (target == 'show') {
		document.getElementById(id).style.display = show;
	}
	else if (target == 'none') {
		document.getElementById(id).style.display = 'none';
	}
}




document.onclick = mclose; 
