function emojiPalette() {
	this.targetText;		// 対象テキスト
	this.switchObj;	// 表示切替ボタン
	this.paletElm;		// 絵文字パレット

	// 対象テキストをセットする
	this.setTargetText = function(targetID) {
		this.targetText = document.getElementById(targetID);
	}

	// 表示切替ボタンをセットする
	this.setSwitch = function(switchID) {
		this.switchObj = document.getElementById(switchID);
	}

	// 絵文字パレットをセットする
	this.setPaletteID = function(paletteID, wrapper) {
		this.paletElm = document.getElementById(paletteID);
		this.paletElm.style.position = "absolute";
		this.paletElm.style.visibility = "hidden";

		var tmp = "";
		var number = "";
		for (i = 1; i <= 206; i++) {
			number = this.adjustNumber(i);
			tmp += "<span onclick=\""+wrapper+"('"+number+"')\"><img src=\"../emjimg/docomo/"+i+".gif\" /></span>";
		}
		for (i = 1; i <= 76; i++) {
			number = this.adjustNumber(1000 + i);
			tmp += "<span onclick=\""+wrapper+"('"+number+"')\"><img src=\"../emjimg/docomo/e"+i+".gif\" /></span>";
		}
		this.paletElm.innerHTML = tmp;
	}

	// 絵文パレットの表示・非表示
	this.show = function() {
		if (this.paletElm.style.visibility == "hidden") {
			this.paletElm.style.top = "25px";
			this.paletElm.style.left = "0px";
			this.paletElm.style.visibility = "visible";
			this.targetText.focus();
		} else {
			this.paletElm.style.visibility = "hidden";
		}
	}

	// 絵文字記号を挿入する
	this.setEmoji = function(emojiId) {
		// 絵文字記号の挿入
		var emojiCode = "{emj_d_" + emojiId + "}";
		var message = this.targetText.value;
		var selection = new Selection(this.targetText);
		var s = selection.create();
		var msg1 = message.substr(0, s.start);
		var msg2 = message.substr(s.end, message.length);
		this.targetText.value = msg1 + emojiCode + msg2;

		// カーソル移動
		this.targetText.focus();
		if (document.selection != null && this.targetText.selectionStart == null) {
			var range = this.targetText.createTextRange();
			range.move('character', s.start + emojiCode.length);
			range.select();
		} else {
			this.targetText.setSelectionRange(s.start + emojiCode.length, s.start + emojiCode.length);
		}

		this.show();
	}

	this.adjustNumber = function(value) {
		if (parseInt(value) < 10)
			return '000' + value;
		else if (parseInt(value) < 100)
			return '00' + value;
		else if (parseInt(value) < 1000)
			return '0' + value;
		else
			return value;
	}
}





// Cross Browser selectionStart/selectionEnd
// Version 0.2
// Copyright (c) 2005-2007 KOSEKI Kengo
// 
// This script is distributed under the MIT licence.
// http://www.opensource.org/licenses/mit-license.php

function Selection(textareaElement) {
    this.element = textareaElement;
}

Selection.prototype.create = function() {
    if (document.selection != null && this.element.selectionStart == null) {
        return this._ieGetSelection();
    } else {
        return this._mozillaGetSelection();
    }
}

Selection.prototype._mozillaGetSelection = function() {
    return { 
        start: this.element.selectionStart, 
        end: this.element.selectionEnd 
    };
}

Selection.prototype._ieGetSelection = function() {
    this.element.focus();

    var range = document.selection.createRange();
    var bookmark = range.getBookmark();

    var contents = this.element.value;
    var originalContents = contents;
    var marker = this._createSelectionMarker();
    while(contents.indexOf(marker) != -1) {
        marker = this._createSelectionMarker();
    }

    var parent = range.parentElement();
    if (parent == null || parent.type != "textarea") {
        return { start: 0, end: 0 };
    }
    range.text = marker + range.text + marker;
    contents = this.element.value;

    var result = {};
    result.start = contents.indexOf(marker);
    contents = contents.replace(marker, "");
    result.end = contents.indexOf(marker);

    this.element.value = originalContents;
    range.moveToBookmark(bookmark);
    range.select();

    return result;
}

Selection.prototype._createSelectionMarker = function() {
    return "##SELECTION_MARKER_" + Math.random() + "##";
}
