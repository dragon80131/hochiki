function userdataDisp(param) {
	if (document.mainform.wUserCD.value == '') {
		UserData.innerHTML = '';
		toggle('UserData', 'none');
		return true;
	}

	var date = new Date();
	var timestamp = date.getTime();
	var url = "get_user.php?ct=" + timestamp;
	var paramList = "sUserCD=" + document.mainform.wUserCD.value;

	new Ajax.Request(url,
	{
			method: 'post',
			onSuccess: getData,
			onFailure: showErrMsg,
			parameters: paramList
	});

	function getData(data){
		var response = data.responseXML.getElementsByTagName('Response');
		var item = response[0].getElementsByTagName('Item');
		var tmpHtml="";

		var useritem = new Array(1);
		useritem[0] = 'Body';

		for (i = 0; i < useritem.length; i++) {
			var value = item[0].getElementsByTagName(useritem[i]);
			if (value[0].firstChild != null)
				tmpHtml += value[0].firstChild.nodeValue;
		}

		tmpHtml = tmpHtml.replace(/&amp;/g,'&').replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&nbsp;/g,' ');

		UserData.innerHTML = tmpHtml;
		toggle('UserData', 'show');
	}

	function showErrMsg(){
		UserData.innerHTML = "値の取得に失敗しました。<br />\n";
	}
}