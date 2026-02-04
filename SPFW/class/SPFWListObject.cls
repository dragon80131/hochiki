<?php
/**
 * ListViewクラス
 *
 * テーブル内容をリスティングするクラスです
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/01/31
 * @version 0.1
 **/

class SPFWListObject {
	/**
	 * データベースコネクション
	 */
	var $myDB;
	/**
	 * 値を求める SQL (select x,y,z,d まで)
	 */
	var $SelectSQL;
	/**
	 * FROM 句以降の SQL (from xxx where yyy)
	 */
	var $Condition;
	/**
	 * ORDER By
	 */
	var $Order;
	/**
	 * GROUP By //kamiya added 2003-05-06
	 */
	var $Group;
	/**
	 * 総行数(総数)
	 */
	var $Count;
	/**
	 * 総ページ数
	 */
	var $Pages;
	/**
	 * 1ページあたり行数
	 */
	var $Rows;
	/**
	 * 1ページあたりカラム数
	 */
	var $Cols;
	/**
	 * リミット(1ページあたりの指定行数)
	 */
	var $Limit;
	/**
	 * 現在ページ番号
	 */
	var $Page;

	/**
	 * 値(二次元配列) Value[row][col] = value(row,col)
	 */
	var $Value;

	/**
	 * エラーメッセージ
	 */
	var $Err;
	
	/* コンストラクタ */
	function __construct($myDB = NULL) {
		$this->Err = NULL;

		if ($myDB == NULL) {
			$this->Err = "データベースへのコネクションがありません";
			return FALSE;
		}

		$this->myDB = $myDB;

		// Limit 初期値
		$this->Limit = 10;

		return TRUE;
	
	}

	/* リスト取得 */
	function GetList($myPage = 1) {
		$this->Err = NULL;

		//設定
		if ($this->myDB == NULL) {
			$this->Err = "データベースへのコネクションがありません";
			return FALSE;
		}

		// myPage チェック1
		if ($myPage < 1) {
			$this->Err = "ページ指定は 1 から始めてください";
			return FALSE;
		}

		// SQL チェック
		if ($this->SelectSQL == NULL) {
			$this->Err = "Select 用の SQL をセットしてください";
			return FALSE;
		}

		// Condition チェック
		if ($this->Condition == NULL) {
			$this->Err = "Condition をセットしてください";
			return FALSE;
		}

		$con = $this->Connection;

		$sql = "SELECT COUNT(*) AS CNT ";
		$sql .= $this->Condition;
		if($this->Group != NULL)
			$sql .= " GROUP BY " . $this->Group;

		$rtn = $this->myDB->executeQuery($sql);
		if (!$rtn) {
			$this->Err = "$sql<br>総行数の取得に失敗しました";
			return FALSE;
		}
		else {
			$this->Count = $this->myDB->getResult($rtn,0,0); // 総行数

			if ($this->Limit != "allpage")
				$this->Pages = ceil($this->Count / $this->Limit);
		}

		// myPage チェック2
		// 指定ページが総ページ数を超えていた場合、指定ページを変更
		if ($myPage > $this->Pages) {
			$myPage = $this->Pages;
			if ($myPage < 1) $myPage = 1;
		}
		$this->Page = $myPage;

		// Offset 計算
if($this->Limit == "allpage"){
		$tmpOffset = ($myPage - 1);
}else{
		$tmpOffset = ($myPage - 1) * $this->Limit;
}
		// SQL より値を求める
		$sql = $this->SelectSQL;
		$sql .= $this->Condition;
		if ($this->Group != NULL)
			$sql .= " GROUP BY " . $this->Group;
		if ($this->Order != NULL)
			$sql .= " ORDER BY " . $this->Order;
		if ($this->Limit != "allpage")
			$sql .= " LIMIT $this->Limit OFFSET $tmpOffset";

		$rtn = $this->myDB->executeQuery($sql);
		if (!$rtn) {
			$this->Err = "$sql<br>データの取得に失敗しました";
			return FALSE;
		}
		else {
			// step 1. 行数を求める
			$n = $this->myDB->getNumberOfRows($rtn);
			if ($n == 0) {
				$this->Rows = 0;
				return TRUE;
			}
			else {
				$this->Rows = $n;
			}

			// step 2. カラム数を求める
			$m = $this->myDB->getNumberOfColumns($rtn);
			$this->Cols = $m;

			// step 3. 配列に格納
			for ($i=0;$i<$n;$i++) {
				for ($j=0;$j<$m;$j++) {
					$this->Value[$i][$j] = $this->myDB->getResult($rtn,$i,$j);
				}
			}
		}
		$this->myDB->freeResult($rtn);

		return TRUE;
	}

	/* 値取得関数 */
	function GetValue($row,$col) {
		return $this->Value[$row][$col];
	}
}
?>
