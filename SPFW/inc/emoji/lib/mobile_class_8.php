<?php

###############################################################################
# 携帯絵文字変換ﾗｲﾌﾞﾗﾘ 2008
# Potora/inaken(C) 2003-2009.
# MAIL: support@potora.dip.jp
#       inaken@jomon.ne.jp
# URL : http://potora.dip.jp/
#       http://www.jomon.ne.jp/~inaken/
###############################################################################
# 2008.10.07 v.8.00.00 全面改訂
# 2008.10.20 v.8.01.00 SMTPﾒｰﾙ送信機能追加(通常版のみ)
# 2008.10.23 v.8.01.01 SMTPﾒｰﾙ送信時CC,BCC指定不具合修正(通常版のみ)
# 2008.11.02 v.8.01.02 絵文字ﾒｰﾙ送信不具合修正(通常版のみ)
# 2008.11.28 v.8.01.03 DB仕様Heapﾃｰﾌﾞﾙｻｲｽﾞ取得方法修正
# 2008.11.28 v.8.01.04 ｸﾞﾛｰﾊﾞﾙ変数扱い変更
# 2009.02.28 v.8.01.05 画像表示位置ｽﾞﾚ修正
# 2009.03.17 v.8.01.06 replace_emoji_form引数指定不具合修正
# 2009.03.23 v.8.01.07 EUC-JPｺｰﾄﾞ絵文字ｴﾝｺｰﾄﾞ不具合修正
# 2009.04.11 v.8.01.08 ﾘｸｴｽﾄ前処理不具合,入力文字ｺｰﾄﾞ指定不具合修正
# 2009.04.11 v.8.01.09 DoCoMo絵文字Unicode指定変換不具合修正
# 2009.05.01 v.8.01.10 SoftBank絵文字ｴﾝｺｰﾄﾞ不具合修正
# 2009.07.02 v.8.01.11 半角ｶﾀｶﾅEUC-JPｺｰﾄﾞ認識不具合修正
# 2009.07.30 v.8.01.12 emj_change関数ﾃﾞﾘﾐﾀ仕様追加
# 2009.09.02 v.8.02.00 XHTML仕様対応,設定ﾌｧｲﾙﾊﾟｽﾌｧｲﾙ対応,画像代替ﾃｷｽﾄ不具合修正
# 2009.09.29 v.8.02.01 ﾃﾞﾘﾐﾀ処理不具合修正
###############################################################################
# これまでの来歴
###############################################################################
# 2003.05.01 v.1.00.00 新規
# 2003.05.07 v.1.00.01 携帯絵文字表示不具合修正
# 2003.07.18 v.1.00.02 未対応文字適用不具合修正、PC画像枠消去
# 2003.07.24 v.1.00.03 au携帯HTML対応化
# 2003.09.01 v.1.01.00 au携帯HTML自動対応化
# 2003.09.05 v.1.01.01 URLｴﾝｺｰﾄﾞ見直し
# 2003.10.02 v.1.01.02 ﾊﾞｸﾞ修正
# 2003.11.11 v.1.01.03 AU認識修正
# 2004.02.06 v.2.00.00 ﾊｯｼｭ展開見直し、EUCｺｰﾄﾞ対応化
# 2004.09.17 v.3.00.00 PHP版作成
# 2005.01.17 v.3.01.00 PHP版au機種絵文字表示不具合修正
# 2005.01.22 v.3.02.00 処理見直し、一括変換機能、絵文字削除機能追加
# 2005.01.23 v.4.00.00 新ﾊﾞｰｼﾞｮﾝﾃﾞｰﾀﾍﾞｰｽ対応
# 2005.01.28 v.4.00.01 DoCoMo,au絵文字変換順序見直し
# 2005.02.04 v.4.00.02 ｴﾝｺｰﾄﾞ,ﾃﾞｺｰﾄﾞ変換不具合見直し
# 2005.02.07 v.4.00.03 ｸﾞﾛｰﾊﾞﾙ変数処理方法変更
# 2005.02.07 v.4.00.04 au拡張絵文字一時ﾌｨﾙﾀｰ処理追加
# 2005.02.13 v.4.00.05 au端末認識ﾊﾞｸﾞ修正
# 2005.02.13 v.4.01.00 固定絵文字ﾃﾞｰﾀ生成機能追加
# 2005.03.04 v.4.02.00 Vodafone新ﾕｰｻﾞｰｴｰｼﾞｪﾝﾄ対応
# 2005.03.24 v.5.00.00 ﾃﾞｰﾀﾍﾞｰｽ ver.6 対応
# 2005.04.20 v.5.00.01 絵文字ｴﾝｺｰﾄﾞ不具合修正
# 2005.04.22 v.5.00.02 携帯ﾃﾞｰﾀ取得時の不足ﾃﾞｰﾀに対する処理方法変更
# 2005.05.24 v.5.01.00 DoCoMo絵文字ｶﾗｰ化、DoCoMo拡張絵文字処理適正化、au絵文字ﾌｫｰﾑ表示対応化
# 2005.06.13 v.5.01.01 au固定絵文字表示不具合修正
# 2005.07.28 v.5.01.02 DoCoMo絵文字Unicode記述対応不具合修正
# 2005.08.18 v.5.01.03 DoCoMo絵文字Unicode記述処理不具合修正
# 2005.09.23 v.6.00.00 ｸﾗｽﾗｲﾌﾞﾗﾘ化
# 2005.11.08 v.6.00.03 文字ｺｰﾄﾞ扱い不具合修正
# 2006.02.02 v.6.01.00 ﾒｰﾙ対応
# 2006.02.14 v.6.01.01 旧ｴﾝｺｰﾄﾞﾃﾞｰﾀ対応追加
# 2006.02.14 v.6.01.02 指定ｷｬﾘｱ強制変換機能追加
# 2006.02.15 v.6.01.03 携帯無限ﾙｰﾌﾟ不具合修正
# 2006.02.20 v.6.01.04 自動初期化時の機種判別結果の変数渡し追加、au携帯処理不具合修正
# 2006.02.21 v.6.01.05 DoCoMo絵文字ｺｰﾄﾞUnicode扱いﾊﾞｸﾞ修正
# 2006.03.01 v.6.01.06 固定絵文字格納変数ｸﾞﾛｰﾊﾞﾙ変数化
# 2006.03.13 v.6.01.07 Vodafone 3G UTF-8ｺｰﾄﾞ絵文字対応
# 2006.03.18 v.6.01.08 携帯詳細情報取得関数追加
# 2006.04.05 v.6.01.09 ﾃﾞｺｰﾄﾞ状態絵文字削除処理追加
# 2006.05.09 v.6.01.10 絵文字ﾒｰﾙ送信関数不具合修正
# 2006.05.09 v.6.01.11 DoCoMo固体識別番号取得不具合修正
# 2006.05.12 v.6.01.12 PCﾌｫｰﾑ表示不具合暫定対策
# 2006.05.14 v.6.01.13 DoCoMo宛絵文字ﾒｰﾙ変換不具合修正
# 2006.05.15 v.6.01.14 ﾒｰﾙ絵文字変換不具合、ﾒｰﾙ送信関数不具合修正
# 2006.05.18 v.6.01.15 auﾒｰﾙｴﾝｺｰﾄﾞ→ﾃﾞｺｰﾄﾞ不具合修正
# 2006.05.18 v.6.01.16 携帯絵文字変換不具合修正
# 2006.05.29 v.6.01.17 初期化(DoCoMoﾒｰﾙ用ﾃﾞｰﾀﾊｯｼｭ展開)不具合修正
# 2006.06.10 v.6.01.18 ﾒｰﾙ処理不具合(au絵文字ｺｰﾄﾞ誤判定)修正
# 2006.06.12 v.6.01.19 絵文字HTMLﾒｰﾙ送信機能追加
# 2006.06.14 v.6.01.20 Vodafone 3G UTF-8ｺｰﾄﾞ絵文字変換不具合修正
# 2006.06.18 v.6.01.21 絵文字ﾒｰﾙ送信ｺｰﾄﾞ不具合修正
# 2006.08.14 v.6.01.22 Willcomﾌﾗｸﾞ追加、PC HTMLﾒｰﾙ処理不具合修正
# 2006.08.18 v.6.01.23 DoCoMo個体識別番号取得不具合修正
# 2006.08.19 v.6.01.24 絵文字ﾌｫｰﾑ表示不具合修正
# 2006.10.05 v.6.02.00 SoftBank対応,Get_PhoneData関数修正,Get_Hardware関数追加
# 2006.10.18 v.6.02.01 絵文字数ｶｳﾝﾄ不具合修正
# 2006.10.19 v.6.02.02 絵文字ﾒｰﾙ送信関数不具合修正
# 2006.10.22 v.6.02.03 絵文字ﾒｰﾙBASE64ｴﾝｺｰﾄﾞ対応
# 2006.11.13 v.6.02.04 au宛ﾒｰﾙ送信絵文字ｺｰﾄﾞ不具合修正
# 2006.11.22 v.6.02.05 絵文字ﾒｰﾙ送信関数不具合修正
# 2006.11.24 v.6.02.06 絵文字削除関数、下駄変換関数不具合修正
# 2006.11.28 v.6.02.07 ﾊﾞｰｼﾞｮﾝ処理、初期化不具合修正
# 2006.12.26 v.6.02.08 DoCoMo拡張文字ｶﾗｰ処理不具合修正
# 2006.12.27 v.6.02.09 携帯情報取得不具合修正
# 2007.01.09 v.6.02.10 DoCoMo拡張文字ﾌｫｰﾑ表示置換え処理不具合修正
# 2007.01.15 v.6.02.11 DoCoMo拡張文字ﾌｫｰﾑ表示置換え処理不具合修正2
# 2007.01.16 v.6.02.12 DoCoMo拡張文字処理、絵文字削除不具合修正
# 2007.01.16 v.6.02.13 絵文字削除不具合修正2
# 2007.02.09 v.6.02.15 ﾒｰﾙ送信関数ﾌｧｲﾙ添付機能追加
# 2007.02.11 v.6.02.16 ﾒｰﾙ送信関数不具合修正
# 2007.06.24 v.6.02.17 個体識別番号取得関数引渡し変数追加
# 2007.08.01 v.7.00.00 全面改訂
# 2007.08.08 v.7.00.01 ｸﾗｽ変数宣言不具合修正
# 2007.08.08 v.7.00.02 au表示不具合対策
# 2007.08.09 v.7.00.03 DBﾌｧｲﾙ読込み不具合処理,au入力不具合修正
# 2007.08.10 v.7.00.04 絵文字ｴﾝｺｰﾄﾞ時ｺｰﾄﾞ変換不具合修正
# 2007.08.11 v.7.01.00 emj_strimwidth,emj_change関数追加
# 2007.08.16 v.7.01.01 DoCoMo扱い絵文字ｺｰﾄﾞShift_JISﾃｷｽﾄ→Unicodeﾃｷｽﾄ変更
# 2007.08.17 v.7.01.02 絵文字変換(ﾒｰﾙ用)不具合修正
# 2007.08.24 v.7.01.03 出力文字ｺｰﾄﾞ処理不具合修正
# 2007.08.25 v.7.02.00 UTF-8ｺｰﾄﾞ対応不具合修正
# 2007.08.27 v.7.02.01 SoftBank UTF-8ｺｰﾄﾞ対応不具合,auﾒｰﾙｺｰﾄﾞ設定不具合修正
# 2007.08.28 v.7.02.02 絵文字ｴﾝｺｰﾄﾞ不具合修正
# 2007.08.28 v.7.02.03 ﾗｲﾌﾞﾗﾘ初期化不具合修正
# 2007.08.28 v.7.02.04 SoftBank絵文字ｴﾝｺｰﾄﾞﾊﾞｸﾞ修正
# 2007.08.28 v.7.02.05 絵文字ｴﾝｺｰﾄﾞ不具合修正(UTF-8ｺｰﾄﾞ対応による不具合対策)
# 2007.09.05 v.7.02.06 ﾃﾞｰﾀﾍﾞｰｽｵﾌﾞｼﾞｪｸﾄ指定不具合修正
# 2007.09.05 v.7.02.07 SoftBank UTF-8ｺｰﾄﾞ処理不具合修正
# 2007.10.03 v.7.02.08 文字ｺｰﾄﾞ認識順位最適化,ﾌｧｲﾙDBﾊﾞｰｼﾞｮﾝ認識化
# 2007.10.04 v.7.02.09 文字ｺｰﾄﾞ認識不具合修正,UTF-8ｴﾝｺｰﾄﾞ不具合修正
# 2007.10.05 v.7.02.10 au絵文字ｴﾝｺｰﾄﾞ不具合修正
# 2007.10.09 v.7.02.11 出力ｺｰﾄﾞ変換不具合修正
# 2007.10.10 v.7.02.12 絵文字ｴﾝｺｰﾄﾞTYPE-2ｴﾝｺｰﾄﾞ不具合修正
# 2007.10.11 v.7.02.13 au絵文字ｴﾝｺｰﾄﾞ不具合修正
# 2007.10.17 v.7.03.00 絵文字ﾒｰﾙ送信関数追加
# 2007.10.23 v.7.03.01 数字絵文字化機能追加
# 2007.11.05 v.7.03.02 SoftBankUTF-8 TYPE2ｴﾝｺｰﾄﾞﾊﾟﾀｰﾝ不具合修正,ﾌｫｰﾑ表示用HTMLｴﾝﾃｨﾃｨ処理不具合修正
# 2007.12.04 v.7.03.03 emoji_send_mail3関数不具合修正
# 2007.12.05 v.7.03.04 emoji_send_mail3関数ｷｬﾘｱ指定不具合修正
# 2007.12.11 v.7.03.05 DoCoMo絵文字ﾃｷｽﾄｺｰﾄﾞｴﾝｺｰﾄﾞ処理追加
# 2007.12.14 v.7.04.00 ﾃﾞｺﾒ対応,SoftBank3G入力処理不具合修正
# 2007.12.16 v.7.04.01 ﾃﾞｺﾒ送信emoji_decome2関数因数設定修正
# 2007.12.16 v.7.04.02 ﾃﾞｺﾒｸﾗｽ組込み不具合修正
# 2007.12.18 v.7.04.03 絵文字ｴﾝｺｰﾄﾞ無限ﾙｰﾌﾟ不具合修正
# 2007.12.20 v.7.04.04 au絵文字ｴﾝｺｰﾄﾞ無限ﾙｰﾌﾟ不具合修正
# 2007.12.23 v.7.04.05 MIME取得不具合修正不具合修正
# 2007.12.25 v.7.04.06 MIME取得不具合修正不具合修正
# 2008.03.29 v.7.04.07 変換引渡し値不具合見直し
# 2008.03.30 v.7.04.08 個体識別番号取得関数の任意ﾕｰｻﾞｰｴｰｼﾞｪﾝﾄ指定不具合修正,DoCoMo DB不具合修正
# 2008.04.01 v.7.05.00 ﾌｫｰﾑ入力自動絵文字ｴﾝｺｰﾄﾞ機能追加
# 2008.04.15 v.7.05.01 emoji_send_mail3関数ﾌｧｲﾙ添付送信不具合見直し
# 2008.04.17 v.7.05.02 emoji_send_mail3関数本文生成不具合修正
# 2008.04.18 v.7.05.03 emoji_send_mail3関数絵文字ﾒｰﾙ送信不具合修正
# 2008.06.11 v.7.06.00 ﾃﾞﾊﾞｯｸﾓｰﾄﾞ追加,新ｴﾝｺｰﾄﾞﾊﾟﾀｰﾝ追加,初期化ﾒｿｯﾄﾞ追加
# 2008.07.01 v.7.06.01 初期化時端末情報取得不具合,ﾃﾞﾘﾐﾀ不具合修正
# 2008.07.07 v.7.06.02 初期化時機種情報取得不足(kisyu_type)追加
# 2008.07.17 v.7.06.03 SoftBank携帯絵文字ﾒｰﾙ送信対応
# 2008.07.18 v.7.06.04 SoftBank携帯絵文字ﾒｰﾙ送信対応不具合,ﾃﾞﾘﾐﾀ設定不具合修正
# 2008.07.19 v.7.06.05 絵文字ﾃﾞｺｰﾄﾞ正規表現不具合修正
# 2008.07.25 v.7.06.06 ﾃﾞｺﾒ送信機能 件名･本文半角ｶﾀｶﾅ全角変換ｷｬﾝｾﾙ機能追加
# 2008.07.25 v.7.06.07 SoftBankｼｭﾐﾚｰﾀ対応
# 2008.08.05 v.7.06.08 iﾓｰﾄﾞID取得機能追加
# 2008.08.29 v.7.06.09 iﾓｰﾄﾞID取得機能不具合修正
###############################################################################

###############################################################################
# 値設定 ######################################################################
###############################################################################
# 絵文字設定ﾌｧｲﾙ位置設定 //////////////////////////////////////////////////////
if (file_exists(dirname(__FILE__).'/emj_setfile.php')) {
  include_once(dirname(__FILE__).'/emj_setfile.php');
} else {
  # 設定ﾌｧｲﾙが無い場合のﾗｲﾌﾞﾗﾘ設定ﾌｧｲﾙの場所を指定してください。
  $emj_setting_file = _INC_DIR . 'emoji/data/setting.cgi';
}

###############################################################################
# 値設定ｺｺまで ################################################################
###############################################################################

# ﾗｲﾄ版設定 ///////////////////////////////////////////////////////////////////
if (!isset($emj_lite_flag)) { $emj_lite_flag = False; }

# ｵﾌﾞｼﾞｪｸﾄ生成 ////////////////////////////////////////////////////////////////

# 絵文字変換ﾗｲﾌﾞﾗﾘｵﾌﾞｼﾞｪｸﾄ作成 ////////////////////////////////////////////////
#if (!isset($set_db_obj)) {
  $emoji_obj = new emoji($emj_setting_file);
#}

# 絵文字DB処理用ｸﾗｽﾗｲﾌﾞﾗﾘ読込み ///////////////////////////////////////////////
$emj_db_obj = '';
if (file_exists(dirname(__FILE__).'/mobile_class_8_db.php')) {
  include_once(dirname(__FILE__).'/mobile_class_8_db.php');
  $emj_db_obj = new emj_db();
  $emj_obj_flag_db = True;
}

if (!isset($set_db_obj)) {
  if (is_object($emoji_obj)) {
    if (isset($emoji_obj->db_flag)) {
      if ($emoji_obj->db_flag == '1') {
        # DB処理ﾗｲﾌﾞﾗﾘ初期化
        if (is_object($emj_db_obj)) {
          # ﾃﾞｰﾀﾍﾞｰｽ値設定
          $emj_db_obj->db_set_connection_data(array('dbd'            => $emoji_obj->dbd,
                                                    'db_hostname'    => $emoji_obj->db_hostname,
                                                    'db_hostport'    => $emoji_obj->db_hostport,
                                                    'db_name'        => $emoji_obj->db_name,
                                                    'db_username'    => $emoji_obj->db_username,
                                                    'db_usrpassword' => $emoji_obj->db_usrpassword
                                                    ));
        }
      }
    }
  }
}

# DB指定時初期化自動実行 //////////////////////////////////////////////////////
if (!isset($set_db_obj)) {
  if ($emoji_obj->db_flag == '1') {
    # ﾌｧｲﾙ仕様
    $emoji_obj->_auto_init();
  }
}

# 入力ﾃﾞｰﾀ前処理(ｴｽｹｰﾌﾟ処理,絵文字ｴﾝｺｰﾄﾞ,Shift-JISｺｰﾄﾞ変換指定 v.7.05) ////////
# $emj_auto_in_flag：1 以上に設定することで入力用の全てのｽｰﾊﾟｰｸﾞﾛｰﾊﾞﾙ変数
# ($_REQUEST,$_GET,$_POST)の内容を絵文字ｴﾝｺｰﾄﾞします。
#
# 入力文字文字列は自動認識しますが、文字列の内容によっては自動認識できない場合も
# あります。その場合には、設定で設定されているﾃﾞﾌｫﾙﾄｺｰﾄﾞが適用されます。
#
# $emj_auto_in_flag：2 を指定することで、SoftBank携帯からの入力は常に UTF-8 と認
# 識されます。SoftBank携帯からのﾘｸｴｽﾄが、常にUTF-8ｺｰﾄﾞであると言う場合には 2 を指
# 定することをお勧め致します。
#
# 変換された値(文字ｺｰﾄﾞ)は、設定されているﾃﾞﾌｫﾙﾄ文字ｺｰﾄﾞに従います。
if (isset($emoji_obj->emj_auto_in_flag)) {
  if ($emoji_obj->emj_auto_in_flag >= 1) {
    $base_code = '';
    if ($emoji_obj->emj_auto_in_flag == 2) {
      if ($emoji_obj->HARD_DATA['hard'] == $emoji_obj->softbank_name) { $base_code = 'UTF-8'; }
    }
    $cc = $emoji_obj->chr_code;
    if ($emoji_obj->chr_code == 'Shift_JIS') { $cc = $emoji_obj->chg_code_sjis; }
    if ($emoji_obj->chr_code == 'EUC-JP')    { $cc = $emoji_obj->chg_code_euc; }
    $conv = '';
    if (isset($emoji_obj->emj_auto_in_hensu_r)) { $conv .= $emoji_obj->emj_auto_in_hensu_r; }
    if (isset($emoji_obj->emj_auto_in_hensu_g)) { $conv .= $emoji_obj->emj_auto_in_hensu_g; }
    if (isset($emoji_obj->emj_auto_in_hensu_p)) { $conv .= $emoji_obj->emj_auto_in_hensu_p; }
    if ($conv != '') {
      if (isset($emoji_obj->emj_auto_in_kana)) {
        $emoji_obj->reqest_data_conv($conv,$emoji_obj->emj_auto_in_kana,$cc,$base_code);
      } else {
        $emoji_obj->reqest_data_conv($conv,'',$cc,$base_code);
      }
    }
  }
}

###############################################################################
# 絵文字処理基本ｸﾗｽ ###########################################################
###############################################################################
class emoji {
  # ﾊﾞｰｼﾞｮﾝ設定
  var $ver = 'v.8.02.00';

  #############################################################################
  # ﾒｲﾝｽｸﾘﾌﾟﾄからﾗｲﾌﾞﾗﾘ設定ﾌｧｲﾙへの位置を指定します
  var $settingc_file = './data/setting.cgi';
  #############################################################################

  # ﾃﾞｰﾀﾍﾞｰｽﾌｧｲﾙ設定
  var $emj_path_b;       # 絵文字対応ﾃﾞｰﾀﾍﾞｰｽ
  var $emj_path_d;       # DoCoMo絵文字ﾃﾞｰﾀﾍﾞｰｽ
  var $emj_path_v;       # SoftBank絵文字ﾃﾞｰﾀﾍﾞｰｽ
  var $emj_path_a;       # au絵文字ﾃﾞｰﾀﾍﾞｰｽ
  var $emj_path_am;      # auﾒｰﾙ用絵文字ﾃﾞｰﾀﾍﾞｰｽ
  var $mob_path;         # 携帯情報ﾃﾞｰﾀﾍﾞｰｽ

  var $emj_path;         # 絵文字ﾃﾞｰﾀﾍﾞｰｽ位置設定
  var $emjimg_path;      # 絵文字画像位置設定
  var $emoji_non;        # 未対応絵文字対応
  var $emoji_chr;        # 未対応絵文字潰し文字
  var $fitimg_path;      # 画像変換ｽｸﾘﾌﾟﾄ指定
  var $chr_code;         # ｽｸﾘﾌﾟﾄ扱い文字ｺｰﾄﾞ指定(Shift_JIS,EUC-JP)
  var $emojiset;         # 固定絵文字ﾊﾟﾀｰﾝ指定
  var $init_flag = '';   # ﾗｲﾌﾞﾗﾘ初期化設定
  var $color_flag;       # DoCoMo絵文字ｶﾗｰ化設定
  var $enc_type;         # ｴﾝｺｰﾄﾞﾀｲﾌﾟ設定
  var $old_enc_flag;     # 旧ｴﾝｺｰﾄﾞﾀｲﾌﾟ処理設定
  var $geta_str;         # 下駄文字設定
#  var $htmlarea_flag;    # HTMLArea使用設定

  var $img_onry_flag;    # 画像表示のみﾌﾗｸﾞ
  var $dec_to_code_flag; # DoCoMo,auﾃﾞｺｰﾄﾞ後復元ｺｰﾄﾞ処理ﾌﾗｸﾞ

  var $hard;             # ｷｬﾘｱ判別
  var $hard_k;           # 区分
  var $ez_flag;          # au機種ﾌﾗｸﾞ
  var $cac;              # ｷｬｯｼｭ容量
  var $mheight;          # ﾃﾞｨｽﾌﾟﾚｲ高さ
  var $mwidth;           # ﾃﾞｨｽﾌﾟﾚｲ幅
  var $mcolor;           # 解像度
  var $will_flag;        # Willcomﾌﾗｸﾞ
  var $chg_code_sjis;    # Shift-JISｺｰﾄﾞの扱いｺｰﾄﾞﾀｲﾌﾟ
  var $chg_code_euc;     # EUC-JPｺｰﾄﾞの扱いｺｰﾄﾞﾀｲﾌﾟ
  var $content_type;     # Contrnt-Type指定

  var $debug_flag = '';  # ﾃﾞﾊﾞｯｸﾓｰﾄﾞﾌﾗｸﾞ

  # 携帯機種情報保存配列初期化
  var $HARD_DATA  = array();  # 端末情報ﾃﾞｰﾀ
  var $PHONE_DATA = array();  # 携帯情報ﾃﾞｰﾀ

  # DoCoMo用配列初期化
  var $DOCOMO_NO_TO_NAME       = array();
  var $DOCOMO_NO_TO_FILE       = array();
  var $DOCOMO_NO_TO_IMG        = array();
  var $DOCOMO_NO_TO_IMG_MAIL   = array();
  var $DOCOMO_SJIS10_TO_NO     = array();
  var $DOCOMO_UTF8_TO_NO       = array();
  var $DOCOMO_UNI_TO_SIS10     = array();
  var $DOCOMO_NO_TO_BIN        = array();
  var $DOCOMO_NO_TO_BIN_UTF8   = array();
  var $DOCOMO_NO_TO_TXT        = array();
  var $DOCOMO_NO_TO_UTXT       = array();
  var $DOCOMO_NO_TO_BIN_COLOR  = array();
  var $DOCOMO_NO_TO_TXT_COLOR  = array();
  var $DOCOMO_NO_TO_UTXT_COLOR = array();

  # SoftBank用配列初期化
  var $SOFT_NO_TO_NAME       = array();
  var $SOFT_NO_TO_FILE       = array();
  var $SOFT_NO_TO_IMG        = array();
  var $SOFT_NO_TO_IMG_MAIL   = array();
  var $SOFT_NO_TO_WEBCODE    = array();
  var $SOFT_WEBCODE_TO_NO    = array();
  var $SOFT3G_DEC_TO_WEBCODE = array();
  var $SOFT3G_DEC_TO_NO      = array();
  var $SOFT3G_NO_TO_UTF8     = array();

  # au用配列初期化
  var $AU_NO_TO_NAME     = array();
  var $AU_NO_TO_FILE     = array();
  var $AU_NO_TO_IMG      = array();
  var $AU_NO_TO_IMG_MAIL = array();
  var $AU_NO_TO_SJIS10   = array();
  var $AU_SJIS10_TO_NO   = array();
  var $AU_UTF8_TO_NO     = array();
  var $AU_NO_TO_MAILCODE = array();
  var $AU_NO_TO_BIN      = array();
  var $AU_NO_TO_BIN_UTF8 = array();
  var $AU_NO_TO_BIN_MAIL = array();
  var $AU_NO_TO_TXT      = array();
  var $AU_NO_TO_TXT_WIN  = array();

  # 変換対応配列初期化
  var $DOCOMO_TO_SOFT = array();
  var $DOCOMO_TO_AU   = array();
  var $SOFT_TO_DOCOMO = array();
  var $SOFT_TO_AU     = array();
  var $AU_TO_DOCOMO   = array();
  var $AU_TO_SOFT     = array();

  # ｴﾝｺｰﾄﾞ/ﾃﾞｺｰﾄﾞ用配列初期化
  var $ENC_TYPE1 = array();   # ｴﾝｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ1
  var $ENC_TYPE2 = array();   # ｴﾝｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ2
  var $ENC_TYPE3 = array();   # ｴﾝｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ3
  var $ENC_TYPE4 = array();   # ｴﾝｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ4
  var $ENC_TYPE5 = array();   # ｴﾝｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ5
  var $ENC_TYPE6 = array();   # ｴﾝｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ6
  var $ENC_TYPE7 = array();   # ｴﾝｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ7
  var $ENC_TYPE8 = array();   # ｴﾝｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ8

  var $DEC_TYPE1 = array();   # ﾃﾞｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ1
  var $DEC_TYPE2 = array();   # ﾃﾞｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ2
  var $DEC_TYPE3 = array();   # ﾃﾞｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ3
  var $DEC_TYPE4 = array();   # ﾃﾞｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ4
  var $DEC_TYPE5 = array();   # ﾃﾞｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ5
  var $DEC_TYPE6 = array();   # ﾃﾞｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ6
  var $DEC_TYPE7 = array();   # ﾃﾞｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ7
  var $DEC_TYPE8 = array();   # ﾃﾞｺｰﾄﾞﾊﾟﾀｰﾝﾃﾞｰﾀ - ﾀｲﾌﾟ8

  # 固定絵文字用配列初期化
  var $FIX_EMJ = array();

  # DB文字変換ﾊﾟﾀｰﾝ設定
  var $save_ptn = '';
  var $read_ptn = '';

  # ﾃﾞｰﾀﾍﾞｰｽｵﾌﾞｼﾞｪｸﾄ
  var $emj_db_obj;

  # 文字ｺｰﾄﾞｴﾝｺｰﾄﾞﾘｽﾄ初期化
  var $ENCODINGLIST = array();

  # ﾃﾞﾘﾐﾀ設定
  var $DELIMITER = array(
    1 => array('left'=>'{'    ,'a'=>'emj_' ,'b'=>'_' ,'right'=>'}'),
    2 => array('left'=>'{'    ,'a'=>''     ,'b'=>''  ,'right'=>'}'),
    3 => array('left'=>'{#'   ,'a'=>'emj_' ,'b'=>'_' ,'right'=>'#}'),
    4 => array('left'=>'{#'   ,'a'=>''     ,'b'=>''  ,'right'=>'#}'),
    5 => array('left'=>'###'  ,'a'=>'emj_' ,'b'=>'_' ,'right'=>'###'),
    6 => array('left'=>'###'  ,'a'=>''     ,'b'=>''  ,'right'=>'###'),
    7 => array('left'=>'<!--' ,'a'=>'emj_' ,'b'=>'_' ,'right'=>'-->'),
    8 => array('left'=>'<!--' ,'a'=>''     ,'b'=>''  ,'right'=>'-->'),
  );

  # 入力ｺｰﾄﾞ一時保管用
  var $input_code_tmpl = '';

  # ｺﾝｽﾄﾗｸﾀ ///////////////////////////////////////////////////////////////////
  # [引渡し値]
  # 　$setting_file : 設定ﾌｧｲﾙ指定
  # 　$auto_flag    : 自動実行指定(1:ｷｬﾝｾﾙ)
  # [返り値]
  # 　なし
  #////////////////////////////////////////////////////////////////////////////
  function emoji ($setting_file='',$auto_flag='') {
    global $emj_lite_flag,$emoji_sub_obj,$emoji_mail_obj;

    # 設定ﾌｧｲﾙ設定
    if ($setting_file != '') {
      $this->setting_file = $setting_file;
    } else {
      if ($this->setting_file == '') { $this->setting_file = $settingc_file; }
    }
    # 設定ﾌｧｲﾙ読込み
    if (file_exists($this->setting_file)) {
      $SETTING_DATA = array();
      $SETTING_DATA = file($this->setting_file);
      foreach ($SETTING_DATA as $sdt) {
        if ($sdt == '') { break; }
        list($namedt,$setdt) = explode("\t",$sdt);
        $this->$namedt = $setdt;
        define('EMOJI_'.$namedt,$setdt);
      }
      if ($this->geta_str == '') { $this->geta_str = '〓'; }
    } else {
      # 設定ﾌｧｲﾙが見つからない場合
      print 'Emoji Change Library Setting Data File Error.';
      exit();
    }

    $this->emj_path = _INC_DIR . 'emoji/data/';

    if (!defined('EMOJI_delimiter_flag')) { define('EMOJI_delimiter_flag','1'); }
    if (!defined('EMOJI_emj_lite_flag'))  { define('EMOJI_emj_lite_flag',True); }

    # ﾗｲﾄ版認識
    if (file_exists(dirname(__FILE__).'/mobile_class_8_sub.php') and 
      file_exists(dirname(__FILE__).'/decome_class.php') and 
      file_exists(dirname(__FILE__).'/mobile_class_8_mail.php')) {
      if (EMOJI_emj_lite_flag == True) {
        if ($emj_lite_flag == True) {
          define('EMJ_LITE_FLAG',True);
        } else {
          define('EMJ_LITE_FLAG',False);
        }
      } else {
        if ($emj_lite_flag == True) {
          define('EMJ_LITE_FLAG',True);
        } else {
          define('EMJ_LITE_FLAG',False);
        }
      }
    } else {
      define('EMJ_LITE_FLAG',True);
    }

    # ｴﾝｺｰﾄﾞﾀｲﾌﾟ再設定
    if (EMJ_LITE_FLAG == True) { $this->enc_type = 1; }

    # 絵文字拡張処理用ｸﾗｽﾗｲﾌﾞﾗﾘ読込み
    $emoji_sub_obj = '';
    if (EMJ_LITE_FLAG == False) {
      include_once(dirname(__FILE__).'/mobile_class_8_sub.php');
      $emoji_sub_obj = new emoji_sub();
    }

    # 絵文字ﾒｰﾙ処理用ｸﾗｽﾗｲﾌﾞﾗﾘ読込み
    # 絵文字拡張処理用ｸﾗｽﾗｲﾌﾞﾗﾘが読込まれている必要があります。
    $emoji_mail_obj = '';
    if (is_object($emoji_sub_obj)) {
      if (EMJ_LITE_FLAG == False) {
        include_once(dirname(__FILE__).'/mobile_class_8_mail.php');
        $emoji_mail_obj = new emoji_mail();
      }
    }

    # 文字ｺｰﾄﾞ変換設定
    if ($this->db_flag == '1') {
      # ﾃﾞｰﾀﾍﾞｰｽ仕様
      if ($this->db_code == 'SJIS') {
      } elseif ($this->db_code == 'EUC-JP') {
        $this->save_ptn = 'StoE';
        $this->read_ptn = 'EtoS';
      } elseif ($this->db_code == 'UTF-8') {
        $this->save_ptn = 'StoU';
        $this->read_ptn = 'UtoS';
      }
      define('EMOJI_save_ptn',$this->save_ptn);
      define('EMOJI_read_ptn',$this->read_ptn);
    }

    # ﾃﾞｰﾀﾍﾞｰｽﾌｧｲﾙ設定
    $this->emj_path_b  = $this->emj_path.'/emoji.cgi';         # 絵文字対応ﾃﾞｰﾀﾍﾞｰｽ
    $this->emj_path_d  = $this->emj_path.'/docomo.cgi';        # DoCoMo絵文字ﾃﾞｰﾀﾍﾞｰｽ
    $this->emj_path_v  = $this->emj_path.'/vodafone.cgi';      # SoftBank絵文字ﾃﾞｰﾀﾍﾞｰｽ
    $this->emj_path_a  = $this->emj_path.'/au.cgi';            # au絵文字ﾃﾞｰﾀﾍﾞｰｽ
    $this->emj_path_am = $this->emj_path.'/au_mail.cgi';       # auﾒｰﾙ用絵文字ﾃﾞｰﾀﾍﾞｰｽ
    $this->mob_path    = $this->emj_path.'/mobile.cgi';        # 携帯情報ﾃﾞｰﾀﾍﾞｰｽ
    define('EMOJI_mob_path',$this->mob_path);

    # 初期化自動実行
    if ($this->db_flag != '1') {
      # ﾌｧｲﾙ仕様の場合のみ
      if (($this->init_flag == '') or ($this->init_flag == '0')) {
        # ﾗｲﾌﾞﾗﾘ自動初期化
        $this->_auto_init();
      }
    }

    # 文字ｺｰﾄﾞｴﾝｺｰﾄﾞﾘｽﾄ設定
    if (!isset($this->encode_list_sjis)) { $this->encode_list_sjis = 'SJIS-win,SJIS,JIS,EUC-JP,UTF-8'; }
    if (!isset($this->encode_list_euc))  { $this->encode_list_euc  = 'EUC-JP,SJIS-win,SJIS,JIS,UTF-8'; }
    if (!isset($this->encode_list_utf8)) { $this->encode_list_utf8 = 'UTF-8,SJIS-win,SJIS,JIS,EUC-JP'; }
    if (!isset($this->encode_list_jis))  { $this->encode_list_jis  = 'SJIS-win,SJIS,JIS,EUC-JP,UTF-8'; }
    $this->ENCODINGLIST = array(
      'Shift_JIS'   => $this->encode_list_sjis,
      'SJIS'        => $this->encode_list_sjis,
      'SJIS-win'    => $this->encode_list_sjis,
      'EUC-JP'      => $this->encode_list_euc,
      'EUC'         => $this->encode_list_euc,
      'eucJP-win'   => $this->encode_list_euc,
      'UTF-8'       => $this->encode_list_utf8,
      'JIS'         => $this->encode_list_jis,
      'ISO-2022-JP' => $this->encode_list_jis,
    );

  }

  # 絵文字変換ﾗｲﾌﾞﾗﾘ初期化自動実行 ////////////////////////////////////////////
  # ﾗｲﾌﾞﾗﾘ初期化時に自動実行する関数を指定します。
  # [引渡し値]
  # 　なし
  # [返り値]
  # 　なし
  #////////////////////////////////////////////////////////////////////////////
  function _auto_init() {
    # 機種判別、情報取得
    $HARDDATA = $this->Get_Hardware();   # 機種判別,auﾌﾗｸﾞ,ｷｬｯｼｭ,高さ,幅,色数
    # ﾗｲﾌﾞﾗﾘ初期化
    $this->read_emojidata();             # 絵文字ﾃﾞｰﾀﾍﾞｰｽ読込み
  }

  # 絵文字変換ﾗｲﾌﾞﾗﾘ初期化(手動実行用)-ver. ////////////////////////////////////////
  # ﾗｲﾌﾞﾗﾘ初期化を任意に指定します。
  # [引渡し値]
  # 　なし
  # [返り値]
  # 　なし
  #////////////////////////////////////////////////////////////////////////////
  function Emoji_init() {
    # 機種判別、情報取得
    $HARDDATA = $this->Get_Hardware();   # 機種判別,auﾌﾗｸﾞ,ｷｬｯｼｭ,高さ,幅,色数
    # ﾗｲﾌﾞﾗﾘ初期化
    $this->read_emojidata();             # 絵文字ﾃﾞｰﾀﾍﾞｰｽ読込み
  }

  # 絵文字変換ﾗｲﾌﾞﾗﾘﾊﾞｰｼﾞｮﾝ取得 ///////////////////////////////////////////////
  # ｷｬﾘｱ判別と機種情報を取得します。(新処理->推奨)
  # [引渡し値]
  # 　なし
  # [返り値]
  # 　$ver : ﾗｲﾌﾞﾗﾘﾊﾞｰｼﾞｮﾝ
  #////////////////////////////////////////////////////////////////////////////
  function Get_Emj_Version() {
    $ver = $this->ver;
    if (EMJ_LITE_FLAG == True) { $ver .= 'L'; }
    return $ver;
  }

  # 絵文字変換ﾗｲﾌﾞﾗﾘﾓｰﾄﾞ取得 ///////////////////////////////////////////////
  # 絵文字変換ﾗｲﾌﾞﾗﾘの動作ﾓｰﾄﾞを取得します。
  # [引渡し値]
  # 　なし
  # [返り値]
  # 　Lite:ﾗｲﾄ版,Normal:通常版
  #////////////////////////////////////////////////////////////////////////////
  function Get_Emj_Mode() {
    if (EMJ_LITE_FLAG == True) { return 'Lite'; }
    return 'Normal';
  }

  # 機種判別・携帯情報取得 ////////////////////////////////////////////////////
  # ｷｬﾘｱ判別と機種情報を取得します。(新処理->推奨)
  # [引渡し値]
  # 　$huag            : ﾕｰｻﾞｰｴｰｼﾞｪﾝﾄ指定(指定無しの場合ｱｸｾｽ端末のﾕｰｻﾞｰｴｰｼﾞｪﾝﾄ)
  # 　$career_get_flag : ｷｬﾘｱ識別方法指定(標準3ｷｬﾘｱ識別の場合"3"(ﾃﾞﾌｫﾙﾄ),Willcomも識別の場合"4")
  # [返り値]
  # 　$RETURNDATA['hard']           : ｷｬﾘｱ判別結果(PC,DoCoMo,au,SoftBank or Vodafone,Willcom)
  # 　$RETURNDATA['will_flag']      : Willcom携帯の場合"1"
  # 　$RETURNDATA['tg_flag']        : DoCoMo 3G -> "FOMA",au 3G -> "WIN",SoctBank 3G -> "3G"
  # 　$RETURNDATA['cache_size']     : 携帯ｷｬｯｼｭｻｲｽﾞ(KB)(PCの場合無し)
  # 　$RETURNDATA['display_height'] : 携帯ﾃﾞｨｽﾌﾟﾚｲ高さ(pt)
  # 　$RETURNDATA['display_width']  : 携帯ﾃﾞｨｽﾌﾟﾚｲ幅(pt)
  # 　$RETURNDATA['display_color']  : 携帯ﾃﾞｨｽﾌﾟﾚｲ表示色数
  #////////////////////////////////////////////////////////////////////////////
  function Get_Hardware($huag='',$career_get_flag='3') {
    global $emoji_sub_obj;
    if ($huag == '') { $huag = $_SERVER['HTTP_USER_AGENT']; }
    $hard       = 'PC';
    $tg_flag    = '';
    $will_flag  = 0;
    $user_agent = explode('/', $huag);
    if (preg_match('/KDDI/',$user_agent[0])) {
      # au
      $hard    = 'au';
      $tg_flag = 'WIN';
    } elseif ($user_agent[0] == 'DoCoMo') {
      # DoCoMo
      $hard    = 'DoCoMo';
      if ($user_agent[1] == '2.0') { $tg_flag = 'FOMA'; }
    } elseif ($user_agent[0] == 'L-mode') {
      # Lﾓｰﾄﾞ
      $hard    = 'DoCoMo';
    } elseif ($user_agent[0] == 'ASTEL') {
      # ASTEL
      $hard    = 'DoCoMo';
    } elseif ($user_agent[0] == 'UP.Browser') {
      # au(旧機種)
      $hard    = 'au';
    } elseif (($user_agent[0] == 'DDIPOCKET') or ($user_agent[0] == 'PDXGW')) {
      # PDXGW(Willcom)
      if ($career_get_flag == '4') {
        $hard  = 'DoCoMo';
      } else {
        $hard  = 'Willcom';
      }
      $will_flag = 1;
    } elseif (preg_match("/(J-PHONE)|(Vodafone)|(MOT)|(Vemulator)/",$user_agent[0]) or ($user_agent[0] == 'SoftBank')) {
      # Vodafone,SoftBank
      $hard    = $this->softbank_name;
      if (preg_match('/(Vodafone)|(MOT)|(Vemulator)/',$user_agent[0]) or ($user_agent[0] == 'SoftBank')) { $tg_flag = '3G'; }
    } else {
      if ($this->debug_flag == '1') {
        if (preg_match("/Vemulator/",$user_agent[0])) {
          # SoftBankｴﾐｭﾚｰﾀー
          $hard    = $this->softbank_name;
          $tg_flag = '3G';
        }
      } else {
        $hard    = 'PC';
      }
    }

    # 機種情報取得
    $cache_size_s     = '';
    $display_height_s = '';
    $display_width_s  = '';
    $display_color_s  = '';
    $PHONEDATA = array();
    if (EMJ_LITE_FLAG == False) {
      $PHONEDATA = $emoji_sub_obj->Get_PhoneData();
    }

    # 携帯個体識別番号取得
    $career  = '';
    $model   = '';
    $devid   = '';
    $ser     = '';
    $icc     = '';
    $imodeid = '';
    $SER_RETDATA = array();
    if (EMJ_LITE_FLAG == False) {
      $SER_RETDATA = $emoji_sub_obj->get_ser_no($huag);
    }

    # 返り値設定
    $RETURNDATA = array();
    $RETURNDATA['hard']           = '';
    $RETURNDATA['will_flag']      = '';
    $RETURNDATA['tg_flag']        = '';
    $RETURNDATA['cache_size']     = '';
    $RETURNDATA['display_height'] = '';
    $RETURNDATA['display_width']  = '';
    $RETURNDATA['display_color']  = '';
    $RETURNDATA['kisyu_type']     = '';
    $RETURNDATA['model']          = '';
    $RETURNDATA['devid']          = '';
    $RETURNDATA['ser']            = '';
    $RETURNDATA['icc']            = '';
    $RETURNDATA['imodeid']        = '';
    if (isset($hard))                        { $RETURNDATA['hard']           = $hard; }
    if (isset($will_flag))                   { $RETURNDATA['will_flag']      = $will_flag; }
    if (isset($tg_flag))                     { $RETURNDATA['tg_flag']        = $tg_flag; }
    if (isset($PHONEDATA['cache_size']))     { $RETURNDATA['cache_size']     = $PHONEDATA['cache_size']; }
    if (isset($PHONEDATA['display_height'])) { $RETURNDATA['display_height'] = $PHONEDATA['display_height']; }
    if (isset($PHONEDATA['display_width']))  { $RETURNDATA['display_width']  = $PHONEDATA['display_width']; }
    if (isset($PHONEDATA['display_color']))  { $RETURNDATA['display_color']  = $PHONEDATA['display_color']; }
    if (isset($PHONEDATA['kisyu_type']))     { $RETURNDATA['kisyu_type']     = $PHONEDATA['kisyu_type']; }
    if (isset($SER_RETDATA['model']))        { $RETURNDATA['model']          = $SER_RETDATA['model']; }
    if (isset($SER_RETDATA['devid']))        { $RETURNDATA['devid']          = $SER_RETDATA['devid']; }
    if (isset($SER_RETDATA['ser']))          { $RETURNDATA['ser']            = $SER_RETDATA['ser']; }
    if (isset($SER_RETDATA['icc']))          { $RETURNDATA['icc']            = $SER_RETDATA['icc']; }
    if (isset($SER_RETDATA['imodeid']))      { $RETURNDATA['imodeid']        = $SER_RETDATA['imodeid']; }

    # ﾗｲﾌﾞﾗﾘ値設定
    $this->HARD_DATA  = array();
    $this->PHONE_DATA = array();
    if (is_array($RETURNDATA))        { $this->HARD_DATA  = $RETURNDATA; }
    if (is_array($PHONEDATA))         { $this->PHONE_DATA = $PHONEDATA; }

    $this->PHONE_DATA['model']   = '';
    $this->PHONE_DATA['devid']   = '';
    $this->PHONE_DATA['ser']     = '';
    $this->PHONE_DATA['icc']     = '';
    $this->PHONE_DATA['imodeid'] = '';
    if (isset($SER_RETDATA['model']))   { $this->PHONE_DATA['model']   = $SER_RETDATA['model']; }
    if (isset($SER_RETDATA['devid']))   { $this->PHONE_DATA['devid']   = $SER_RETDATA['devid']; }
    if (isset($SER_RETDATA['ser']))     { $this->PHONE_DATA['ser']     = $SER_RETDATA['ser']; }
    if (isset($SER_RETDATA['icc']))     { $this->PHONE_DATA['icc']     = $SER_RETDATA['icc']; }
    if (isset($SER_RETDATA['imodeid'])) { $this->PHONE_DATA['imodeid'] = $SER_RETDATA['imodeid']; }

    return $RETURNDATA;
  }

  # ﾒｰﾙｱﾄﾞﾚｽｷｬﾘｱ解析 //////////////////////////////////////////////////////////
  # ﾒｰﾙｱﾄﾞﾚｽよりｷｬﾘｱ情報を取得します
  # [引渡し値]
  # 　$mail_address : ﾒｰﾙｱﾄﾞﾚｽ
  # [返り値]
  # 　$career : ｷｬﾘｱ判別結果(DoCoMo,au,SoftBank or Vodafone)
  #////////////////////////////////////////////////////////////////////////////
  function get_mail_career($mail_address) {
    global $emoji_mail_obj;
    $career = '';
    if (is_object($emoji_mail_obj)) {
      $career = $emoji_mail_obj->get_mail_career($mail_address);
    }
    return $career;
  }

  # 機種名・固体識別番号取得 //////////////////////////////////////////////////
  # 携帯の機種名と個体識別番号を取得します。
  # [引渡し値]
  # 　$user_agent : ﾕｰｻﾞｰｴｰｼﾞｪﾝﾄ指定(指定無しの場合ｱｸｾｽ端末のﾕｰｻﾞｰｴｰｼﾞｪﾝﾄ)
  # [返り値]
  # 　$RETURNDATA['career']  : ｷｬﾘｱ(DoCoMo,au,SoftBank or Vodafone)
  # 　$RETURNDATA['model']   : 機種名
  # 　$RETURNDATA['devid']   : ﾃﾞﾊﾞｲｽID
  # 　$RETURNDATA['ser']     : 個体識別番号(ｻﾌﾞｽｸﾗｲﾊﾞID)
  # 　$RETURNDATA['icc']     : FOMAｶｰﾄﾞ個体識別子
  # 　$RETURNDATA['imodeid'] : iﾓｰﾄﾞID
  #////////////////////////////////////////////////////////////////////////////
  function get_ser_no($user_agent='') {
    global $emoji_sub_obj;
    $RETURNDATA = False;
    if (is_object($emoji_sub_obj)) {
      $COUNTDATA = $emoji_sub_obj->get_ser_no($user_agent);
    }
    return $RETURNDATA;
  }

  # ﾗｲﾌﾞﾗﾘ初期化 //////////////////////////////////////////////////////////////
  # ﾗｲﾌﾞﾗﾘを初期化します。
  # [引渡し値]
  # 　なし
  # [返り値]
  # 　なし
  #////////////////////////////////////////////////////////////////////////////
  function read_emojidata() {
    global $emj_db_obj;
    # 基本ﾃﾞｰﾀﾍﾞｰｽ読込み
    $EMJDATA_BASE   = array();
    $EMJDATA_DOCOMO = array();
    $EMJDATA_SOFT   = array();
    $EMJDATA_AU     = array();

    if ($this->db_flag == '1') {
      # ﾃﾞｰﾀﾍﾞｰｽ使用
      # DB接続
      $emj_db_obj->db_connect();
      # 絵文字変換対応ﾃﾞｰﾀﾍﾞｰｽ読込み
      $sql = "SELECT * FROM emj_emoji ORDER BY Base_emj_id";
      $sth = $emj_db_obj->sql_set_data(0,$sql,'','',$this->save_ptn);
      while ($GETDATA = $emj_db_obj->sql_get_data(0,$sth,'','','loop','ass','1',$this->read_ptn)) {
        $EMJDATA_BASE[] = $GETDATA['Base_emj_id']."\t".$GETDATA['script_code']."\t".$GETDATA['DoCoMo_no']."\t".$GETDATA['SoftBank_no']."\t".$GETDATA['au_no']."\t".$GETDATA['yusen_no']."\t";
      }
      # DoCoMo絵文字ﾃﾞｰﾀﾍﾞｰｽ読込み
      $sql = "SELECT * FROM emj_DoCoMo ORDER BY DoCoMo_emj_id";
      $sth = $emj_db_obj->sql_set_data(0,$sql,'','',$this->save_ptn);
      while ($GETDATA = $emj_db_obj->sql_get_data(0,$sth,'','','loop','ass','1',$this->read_ptn)) {
        $EMJDATA_DOCOMO[] = $GETDATA['DoCoMo_emj_id']."\t".$GETDATA['emj_name']."\t".$GETDATA['emj_file']."\t".$GETDATA['sjis16']."\t".$GETDATA['sjis10']."\t".$GETDATA['web_code']."\t".$GETDATA['unicode']."\t".$GETDATA['color']."\t\n";
      }

      # au絵文字ﾃﾞｰﾀﾍﾞｰｽ読込み
      $sql = "SELECT * FROM emj_au ORDER BY au_emj_id";
      $sth = $emj_db_obj->sql_set_data(0,$sql,'','',$this->save_ptn);
      while ($GETDATA = $emj_db_obj->sql_get_data(0,$sth,'','','loop','ass','1',$this->read_ptn)) {
        $EMJDATA_AU[] = $GETDATA['au_emj_id']."\t".$GETDATA['emj_name']."\t".$GETDATA['emj_file']."\t".$GETDATA['sjis16']."\t".$GETDATA['sjis10']."\t".$GETDATA['web_code']."\t".$GETDATA['unicode']."\t".$GETDATA['mail_code']."\t".$GETDATA['mail_code']."\t\n";
      }

      # SoftBank絵文字ﾃﾞｰﾀﾍﾞｰｽ読込み
      $sql = "SELECT * FROM emj_SoftBank ORDER BY SoftBank_emj_id";
      $sth = $emj_db_obj->sql_set_data(0,$sql,'','',$this->save_ptn);
      while ($GETDATA = $emj_db_obj->sql_get_data(0,$sth,'','','loop','ass','1',$this->read_ptn)) {
        $EMJDATA_SOFT[] = $GETDATA['SoftBank_emj_id']."\t".$GETDATA['emj_name']."\t".$GETDATA['emj_file']."\t".$GETDATA['sjis16']."\t".$GETDATA['mail_code']."\t".$GETDATA['web_code']."\t".$GETDATA['unicode']."\t".$GETDATA['utf_8']."\t\n";
      }
    } else {
      # ﾌｧｲﾙﾃﾞｰﾀﾍﾞｰｽ使用
      # 絵文字変換対応ﾃﾞｰﾀﾍﾞｰｽ読込み
      if (file_exists($this->emj_path_b)) {
        if (!$EMJDATA_BASE = @file($this->emj_path_b)) {
          print 'Emoji DataBase File Read Error.';
          exit();
        }
      } else {
        print 'Emoji DataBase File Read Error.';
        exit();
      }
      # DoCoMo絵文字ﾃﾞｰﾀﾍﾞｰｽ読込み
      if (file_exists($this->emj_path_d)) {
        if (!$EMJDATA_DOCOMO = @file($this->emj_path_d)) {
          print 'DoCoMo Emoji DataBase File Read Error.';
          exit();
        }
        # 絵文字ﾃﾞｰﾀﾍﾞｰｽﾊﾞｰｼﾞｮﾝﾁｪｯｸ
        $FDT = explode("\t",$EMJDATA_DOCOMO[0]);
        if (count($FDT) < 10) {
          print 'DoCoMo Emoji DataBase File is Old Format Error.';
          exit();
        }
      } else {
        print 'DoCoMo Emoji DataBase File Read Error.';
        exit();
      }
      # SoftBank絵文字ﾃﾞｰﾀﾍﾞｰｽ読込み
      if (file_exists($this->emj_path_v)) {
        if (!$EMJDATA_SOFT = file($this->emj_path_v)) {
          print 'SoftBank Emoji DataBase File Read Error.';
          exit();
        }
      } else {
        print 'SoftBank Emoji DataBase File Read Error.';
        exit();
      }
      # au絵文字ﾃﾞｰﾀﾍﾞｰｽ読込み
      if (file_exists($this->emj_path_a)) {
        if (!$EMJDATA_AU = file($this->emj_path_a)) {
          print 'au Emoji DataBase File Read Error.';
          exit();
        }
        # 絵文字ﾃﾞｰﾀﾍﾞｰｽﾊﾞｰｼﾞｮﾝﾁｪｯｸ
        $FDT = explode("\t",$EMJDATA_AU[0]);
        if (count($FDT) < 10) {
          print 'au Emoji DataBase File is Old Format Error.';
          exit();
        }
      } else {
        print 'au Emoji DataBase File Read Error.';
        exit();
      }
      # ﾗﾍﾞﾙ削除
      array_shift($EMJDATA_DOCOMO);
      array_shift($EMJDATA_SOFT);
      array_shift($EMJDATA_AU);
      # 絵文字変換対応ﾃﾞｰﾀﾍﾞｰｽﾊﾞｰｼﾞｮﾝ取得
      $e_ver = $EMJDATA_BASE[0];
      if ($e_ver != '') { array_splice($EMJDATA_BASE,0,2); }
    }

    # ﾃﾞﾘﾐﾀ設定取得設定(Line版はﾃﾞﾌｫﾙﾄの {emj_*_####} ﾊﾟﾀｰﾝのみ)
    if (EMJ_LITE_FLAG == True) {
      $set_deli = 1;
      $loop_num = 1;
    } else {
      if (EMOJI_delimiter_flag == '1') {
        $set_deli = EMOJI_enc_type;
        $loop_num = 1;
      } else {
        $set_deli = 1;
        $loop_num = 8;
      }
    }

    # DoCoMo用絵文字ﾃﾞｰﾀ配列展開
    foreach ($EMJDATA_DOCOMO as $edt) {
      if ($edt != '') {
        list($eno,$ename,$efile,$esjis16,$esjis10,$eweb,$euni,$color,$eutf8) = explode("\t",$edt);
        if (isset($eutf8) and preg_match('/^[0-9a-fA-F]{6}$/',$eutf8)) { $utf8c = substr($eutf8,2); }
        # 絵文字名設定
        $this->DOCOMO_NO_TO_NAME[$eno] = $ename;
        # 絵文字画像ﾌｧｲﾙ設定
        $this->DOCOMO_NO_TO_FILE[$eno] = $efile;
        # 絵文字画像表示設定
        $img_opt = '';
        if ($this->img_title_flag == '1') { $img_opt .= ' title="'.$ename.'"'; }
        if ($this->img_alt_flag   == '1') { $img_opt .= ' alt="'.$ename.'"'; }
        if ($this->fitimg_path) {
          # Fitimg使用する場合
#          $this->DOCOMO_NO_TO_IMG[$eno] = '<img src="'.$this->fitimg_path.'/fitimg.php?file='.$this->emjimg_path.'/'.$efile.'&w='.$this->fitimg_size.'" border="0" align="center"'.$img_opt.' />';
          $this->DOCOMO_NO_TO_IMG[$eno] = '<img src="'.$this->fitimg_path.'/fitimg.php?file='.$this->emjimg_path.'/'.$efile.'&w='.$this->fitimg_size.'" border="0"'.$img_opt.' />';
          if (EMJ_LITE_FLAG == False) {
#            $this->DOCOMO_NO_TO_IMG_MAIL[$eno] = '<img src="'.$this->fitimg_path.'/fitimg.php?file='.$this->emjimg_path.'/'.$efile.'&w='.$this->fitimg_size.'" border="0" align="center" />';
            $this->DOCOMO_NO_TO_IMG_MAIL[$eno] = '<img src="'.$this->fitimg_path.'/fitimg.php?file='.$this->emjimg_path.'/'.$efile.'&w='.$this->fitimg_size.'" border="0" />';
          }
        } else {
          # Fitimg使用しない場合
#          $this->DOCOMO_NO_TO_IMG[$eno] = '<img src="'.$this->emjimg_path.'/'.$efile.'" border="0" align="center"'.$img_opt.' />';
          $this->DOCOMO_NO_TO_IMG[$eno] = '<img src="'.$this->emjimg_path.'/'.$efile.'" border="0"'.$img_opt.' />';
          if (EMJ_LITE_FLAG == False) {
#            $this->DOCOMO_NO_TO_IMG_MAIL[$eno] = '<img src="'.$this->emjimg_path.'/'.$efile.'" border="0" align="center" />';
            $this->DOCOMO_NO_TO_IMG_MAIL[$eno] = '<img src="'.$this->emjimg_path.'/'.$efile.'" border="0" />';
          }
        }
        $this->DOCOMO_SJIS10_TO_NO[$esjis10] = $eno;
        $this->DOCOMO_UTF8_TO_NO[0] = '';
        if (isset($utf8c)) {
          $this->DOCOMO_UTF8_TO_NO[hexdec($utf8c)] = $eno;
        }
        $this->DOCOMO_UNI_TO_SIS10[$euni] = $esjis10;
        # ﾊﾞｲﾅﾘｺｰﾄﾞ設定
        $this->DOCOMO_NO_TO_BIN[$eno] = pack("H4",$esjis16);
        if (isset($eutf8) and preg_match('/^[0-9a-fA-F]{6}$/',$eutf8)) {
          $this->DOCOMO_NO_TO_BIN_UTF8[$eno] = pack("H6",$eutf8);
        }
        # ﾃｷｽﾄｺｰﾄﾞ設定
        if ($eno < 1000) {
          # SJIS(基本絵文字)
          $this->DOCOMO_NO_TO_TXT[$eno] = "&#{$esjis10};";
        } else {
          # Unicode(拡張絵文字)
          $this->DOCOMO_NO_TO_TXT[$eno] = "&#x{$euni};";
        }
        $this->DOCOMO_NO_TO_UTXT[$eno] = "&#x{$euni};";
        # ｶﾗｰ設定
        if (($this->color_flag == 1) and preg_match('/#[0-9a-fA-F]{6}/',$color)) {
          # ｶﾗｰ指定あり
          $this->DOCOMO_NO_TO_BIN_COLOR[$eno]  = '<font color="'.$color.'">'.$this->DOCOMO_NO_TO_BIN[$eno].'</font>';
          $this->DOCOMO_NO_TO_TXT_COLOR[$eno]  = '<font color="'.$color.'">'.$this->DOCOMO_NO_TO_TXT[$eno].'</font>';
          $this->DOCOMO_NO_TO_UTXT_COLOR[$eno] = '<font color="'.$color.'">'.$this->DOCOMO_NO_TO_UTXT[$eno].'</font>';
        } else {
          # ｶﾗｰ指定なし
          $this->DOCOMO_NO_TO_BIN_COLOR[$eno]  = $this->DOCOMO_NO_TO_BIN[$eno];
          $this->DOCOMO_NO_TO_TXT_COLOR[$eno]  = $this->DOCOMO_NO_TO_TXT[$eno];
          $this->DOCOMO_NO_TO_UTXT_COLOR[$eno] = $this->DOCOMO_NO_TO_UTXT[$eno];
        }
        # ｴﾝｺｰﾄﾞ展開
        for ($i = $set_deli; $i <= $loop_num; $i++) {
          # ﾃｷｽﾄｺｰﾄﾞ展開(SJISｷｰ…10進)
          $this->{'ENC_TYPE'.$i}[$esjis10] = $this->DELIMITER[$i]['left'].$this->DELIMITER[$i]['a'].'d'.$this->DELIMITER[$i]['b'].$eno.$this->DELIMITER[$i]['right'];
          # ﾃｷｽﾄｺｰﾄﾞ展開(Unicodeｷｰ…16進)
          $this->{'ENC_TYPE'.$i}[$euni] = $this->DELIMITER[$i]['left'].$this->DELIMITER[$i]['a'].'d'.$this->DELIMITER[$i]['b'].$eno.$this->DELIMITER[$i]['right'];
          # ﾃｷｽﾄｺｰﾄﾞ展開(Unicodeｷｰ…10進)
          $this->{'ENC_TYPE'.$i}[hexdec('0x'.$euni)] = $this->DELIMITER[$i]['left'].$this->DELIMITER[$i]['a'].'d'.$this->DELIMITER[$i]['b'].$eno.$this->DELIMITER[$i]['right'];
          # ﾊﾞｲﾅﾘ展開
          $this->{'ENC_TYPE'.$i}[$this->DOCOMO_NO_TO_BIN[$eno]] = $this->DELIMITER[$i]['left'].$this->DELIMITER[$i]['a'].'d'.$this->DELIMITER[$i]['b'].$eno.$this->DELIMITER[$i]['right'];
        }
        if (isset($this->DOCOMO_NO_TO_BIN_UTF8[$eno]) and ($this->DOCOMO_NO_TO_BIN_UTF8[$eno] != '')) {
          for ($i = $set_deli; $i <= $loop_num; $i++) {
            $this->{'ENC_TYPE'.$i}[$this->DOCOMO_NO_TO_BIN_UTF8[$eno]] = $this->DELIMITER[$i]['left'].$this->DELIMITER[$i]['a'].'d'.$this->DELIMITER[$i]['b'].$eno.$this->DELIMITER[$i]['right'];
          }
        }
      }
    }

    # SoftBank用絵文字ﾃﾞｰﾀ配列展開
    foreach ($EMJDATA_SOFT as $edt) {
      if ($edt != '') {
        list($eno,$ename,$efile,$esjis16,$emailcd,$eweb,$euni,$eutf8) = explode("\t",$edt);
        # 絵文字名設定
        $this->SOFT_NO_TO_NAME[$eno] = $ename;
        # 絵文字画像ﾌｧｲﾙ設定
        $this->SOFT_NO_TO_FILE[$eno] = $efile;
        # 絵文字画像表示設定
        $img_opt = '';
        if ($this->img_title_flag == '1') { $img_opt .= ' title="'.$ename.'"'; }
        if ($this->img_alt_flag   == '1') { $img_opt .= ' alt="'.$ename.'"'; }
        if ($this->fitimg_path) {
          # Fitimg使用する場合
#          $this->SOFT_NO_TO_IMG[$eno] = '<img src="'.$this->fitimg_path.'/fitimg.php?file='.$this->emjimg_path.'/'.$efile.'&w='.$this->fitimg_size.'" border="0" align="center"'.$img_opt.' />';
          $this->SOFT_NO_TO_IMG[$eno] = '<img src="'.$this->fitimg_path.'/fitimg.php?file='.$this->emjimg_path.'/'.$efile.'&w='.$this->fitimg_size.'" border="0"'.$img_opt.' />';
          if (EMJ_LITE_FLAG == False) {
#            $this->SOFT_NO_TO_IMG_MAIL[$eno] = '<img src="'.$this->fitimg_path.'/fitimg.php?file='.$this->emjimg_path.'/'.$efile.'&w='.$this->fitimg_size.'" border="0" align="center" />';
            $this->SOFT_NO_TO_IMG_MAIL[$eno] = '<img src="'.$this->fitimg_path.'/fitimg.php?file='.$this->emjimg_path.'/'.$efile.'&w='.$this->fitimg_size.'" border="0" />';
          }
        } else {
          # Fitimg使用しない場合
#          $this->SOFT_NO_TO_IMG[$eno] = '<img src="'.$this->emjimg_path.'/'.$efile.'" border="0" align="center"'.$img_opt.' />';
          $this->SOFT_NO_TO_IMG[$eno] = '<img src="'.$this->emjimg_path.'/'.$efile.'" border="0"'.$img_opt.' />';
          if (EMJ_LITE_FLAG == False) {
#            $this->SOFT_NO_TO_IMG_MAIL[$eno] = '<img src="'.$this->emjimg_path.'/'.$efile.'" border="0" align="center" />';
            $this->SOFT_NO_TO_IMG_MAIL[$eno] = '<img src="'.$this->emjimg_path.'/'.$efile.'" border="0" />';
          }
        }
        $this->SOFT_NO_TO_WEBCODE[$eno]  = $eweb;
        $this->SOFT_WEBCODE_TO_NO[$eweb] = $eno;
        $decdt = hexdec(substr($eutf8,2));
        $this->SOFT3G_DEC_TO_WEBCODE[$decdt] = $eweb;
        $this->SOFT3G_DEC_TO_NO[$decdt] = $eno;
        if (isset($eutf8) and preg_match('/^[0-9a-fA-F]{6}$/',$eutf8)) {
          $this->SOFT3G_NO_TO_UTF8[$eno] = pack("H6",$eutf8);
        }
        # ｴﾝｺｰﾄﾞ用展開
        for ($i = $set_deli; $i <= $loop_num; $i++) {
          $this->{'ENC_TYPE'.$i}[$eweb] = $this->DELIMITER[$i]['left'].$this->DELIMITER[$i]['a'].'v'.$this->DELIMITER[$i]['b'].$eno.$this->DELIMITER[$i]['right'];
        }
      }
    }

    # au用絵文字ﾃﾞｰﾀ配列展開
    foreach ($EMJDATA_AU as $edt) {
      if ($edt != '') {
        list($eno,$ename,$efile,$esjis16,$esjis10,$eweb,$euni,$esjis16m,$eutf8) = explode("\t",$edt);
        if (isset($eutf8) and preg_match('/^[0-9a-fA-F]{6}$/',$eutf8)) { $utf8c = substr($eutf8,2); }
        # 絵文字名設定
        $this->AU_NO_TO_NAME[$eno] = $ename;
        # 絵文字画像ﾌｧｲﾙ設定
        $this->AU_NO_TO_FILE[$eno] = $efile;
        # 絵文字画像表示設定
        $img_opt = '';
        if ($this->img_title_flag == '1') { $img_opt .= ' title="'.$ename.'"'; }
        if ($this->img_alt_flag   == '1') { $img_opt .= ' alt="'.$ename.'"'; }
        if ($this->fitimg_path) {
          # Fitimg使用する場合
#          $this->AU_NO_TO_IMG[$eno] = '<img src="'.$this->fitimg_path.'/fitimg.php?file='.$this->emjimg_path.'/'.$efile.'&w='.$this->fitimg_size.'" border="0" align="center"'.$img_opt.' />';
          $this->AU_NO_TO_IMG[$eno] = '<img src="'.$this->fitimg_path.'/fitimg.php?file='.$this->emjimg_path.'/'.$efile.'&w='.$this->fitimg_size.'" border="0"'.$img_opt.' />';
          if (EMJ_LITE_FLAG == False) {
#            $this->AU_NO_TO_IMG_MAIL[$eno] = '<img src="'.$this->fitimg_path.'/fitimg.php?file='.$this->emjimg_path.'/'.$efile.'&w='.$this->fitimg_size.'" border="0" align="center" />';
            $this->AU_NO_TO_IMG_MAIL[$eno] = '<img src="'.$this->fitimg_path.'/fitimg.php?file='.$this->emjimg_path.'/'.$efile.'&w='.$this->fitimg_size.'" border="0" />';
          }
        } else {
          # Fitimg使用しない場合
#          $this->AU_NO_TO_IMG[$eno] = '<img src="'.$this->emjimg_path.'/'.$efile.'" border="0" align="center"'.$img_opt.' />';
          $this->AU_NO_TO_IMG[$eno] = '<img src="'.$this->emjimg_path.'/'.$efile.'" border="0"'.$img_opt.' />';
          if (EMJ_LITE_FLAG == False) {
#            $this->AU_NO_TO_IMG_MAIL[$eno] = '<img src="'.$this->emjimg_path.'/'.$efile.'" border="0" align="center" />';
            $this->AU_NO_TO_IMG_MAIL[$eno] = '<img src="'.$this->emjimg_path.'/'.$efile.'" border="0" />';
          }
        }
        $this->AU_NO_TO_SJIS10[$eno]     = $esjis10;
        $this->AU_SJIS10_TO_NO[$esjis10] = $eno;
        if (EMJ_LITE_FLAG == False) {
          $this->AU_NO_TO_MAILCODE[$eno] = hexdec($esjis16m);
          $this->AU_SJIS10_TO_NO[$this->AU_NO_TO_MAILCODE[$eno]] = $eno;
        }
        if (isset($utf8c) and ($utf8c != '')) { $this->AU_UTF8_TO_NO[hexdec($utf8c)] = $eno; }
        # ﾊﾞｲﾅﾘｺｰﾄﾞ設定
        $this->AU_NO_TO_BIN[$eno] = pack("H4",$esjis16);
        if (isset($eutf8) and preg_match('/^[0-9a-fA-F]{6}$/',$eutf8)) {
          $this->AU_NO_TO_BIN_UTF8[$eno] = pack("H6",$eutf8);
        }
        if (EMJ_LITE_FLAG == False) {
          $this->AU_NO_TO_BIN_MAIL[$eno] = pack("H4",$esjis16m);
        }
        # ﾃｷｽﾄｺｰﾄﾞ設定
        $enos = preg_replace('|^0*|','',$eno);
        $this->AU_NO_TO_TXT_WIN[$eno] = '<img localsrc="'.$enos.'" />';
        $this->AU_NO_TO_TXT[$eno]     = '<IMG ICON="'.$enos.'" />';
        # ｴﾝｺｰﾄﾞ展開
        for ($i = $set_deli; $i <= $loop_num; $i++) {
          # ｴﾝｺｰﾄﾞ展開
          $this->{'ENC_TYPE'.$i}[$esjis10] = $this->DELIMITER[$i]['left'].$this->DELIMITER[$i]['a'].'a'.$this->DELIMITER[$i]['b'].$eno.$this->DELIMITER[$i]['right'];
          $this->{'ENC_TYPE'.$i}[$this->AU_NO_TO_BIN[$eno]] = $this->DELIMITER[$i]['left'].$this->DELIMITER[$i]['a'].'a'.$this->DELIMITER[$i]['b'].$eno.$this->DELIMITER[$i]['right'];
          # ﾒｰﾙｺｰﾄﾞｴﾝｺｰﾄﾞ用展開
          if (EMJ_LITE_FLAG == False) { 
            $this->{'DEC_TYPE'.$i}[$this->AU_NO_TO_MAILCODE[$eno]] = $this->DELIMITER[$i]['left'].$this->DELIMITER[$i]['a'].'am'.$this->DELIMITER[$i]['b'].$eno.$this->DELIMITER[$i]['right'];
            $this->{'DEC_TYPE'.$i}[$this->AU_NO_TO_BIN_MAIL[$eno]] = $this->DELIMITER[$i]['left'].$this->DELIMITER[$i]['a'].'am'.$this->DELIMITER[$i]['b'].$eno.$this->DELIMITER[$i]['right'];
          }
        }

      }
    }

    # 変換対応ﾃﾞｰﾀ準備
    foreach ($EMJDATA_BASE as $edt) {
      if ($edt != '') {
        list($enob,$enameb,$d_nob,$v_nob,$a_nob,$junib) = explode("\t",$edt);
        $this->DOCOMO_TO_SOFT[$d_nob] = $v_nob;
        $this->DOCOMO_TO_AU[$d_nob]   = $a_nob;
        $this->SOFT_TO_DOCOMO[$v_nob] = $d_nob;
        $this->SOFT_TO_AU[$v_nob]     = $a_nob;
        $this->AU_TO_DOCOMO[$a_nob]   = $d_nob;
        $this->AU_TO_SOFT[$a_nob]     = $v_nob;
      }
    }

    # 固定絵文字設定(ｱｸｾｽｷｬﾘｱに応じて設定)
    foreach ($EMJDATA_BASE as $edt) {
      if ($edt != '') {
        list($enob,$enameb,$d_nob,$v_nob,$a_nob,$junib) = explode("\t",$edt);
        if (preg_match('/^pc$/i',$this->HARD_DATA['hard'])) {
          # PC表示時
          $check_flag = False;
          if (($this->emojiset == "DoCoMo") and ($d_nob != '')) {
            # DoCoMo絵文字画像に変換(対応絵文字設定がある場合)
            $this->FIX_EMJ[$enob] = $this->DOCOMO_NO_TO_IMG[$d_nob];
            $check_flag = True;
          } elseif (($this->emojiset == "au") and ($a_nob != '')) {
            # au絵文字画像に変換(対応絵文字設定がある場合)
            $this->FIX_EMJ[$enob] = $this->AU_NO_TO_IMG[$a_nob];
            $check_flag = True;
          } elseif (($this->emojiset == "SoftBank") and ($v_nob != '')) {
            # SoftBank絵文字画像に変換(対応絵文字設定がある場合)
            $this->FIX_EMJ[$enob] = $this->SOFT_NO_TO_IMG[$v_nob];
            $check_flag = True;
          }
          # 対応絵文字設定が無い場合
          if ($check_flag == False) { $this->FIX_EMJ[$enob] = $this->emoji_chr; }
        } elseif (preg_match('/^docomo$/i',$this->HARD_DATA['hard'])) {
          # DoCoMo携帯表示時
          if ($d_nob != '') {
            # 対応絵文字設定がある場合
            if ($this->color_flag == 1) {
              # ｶﾗｰ指定有りの場合
              if ($this->docomo_fix_code == 'Unicode') {
                $this->FIX_EMJ[$enob] = $this->DOCOMO_NO_TO_UTXT_COLOR[$d_nob];
              } else {
                $this->FIX_EMJ[$enob] = $this->DOCOMO_NO_TO_TXT_COLOR[$d_nob];
              }
            } else {
              # ｶﾗｰ指定無しの場合
              if ($this->docomo_fix_code == 'Unicode') {
                $this->FIX_EMJ[$enob] = $this->DOCOMO_NO_TO_UTXT[$d_nob];
              } else {
                $this->FIX_EMJ[$enob] = $this->DOCOMO_NO_TO_TXT[$d_nob];
              }
            }
          } else {
            # 対応絵文字設定が無い場合
            $this->FIX_EMJ[$enob] = $this->emoji_chr;
          }
        } elseif (preg_match('/^'.$this->softbank_name.'$/i',$this->HARD_DATA['hard'])) {
          # Vodafone,Softbank携帯表示時
          if ($v_nob != '') {
            # 対応絵文字設定がある場合
            $this->FIX_EMJ[$enob] = $this->SOFT_NO_TO_WEBCODE[$v_nob];
          } else {
            # 対応絵文字設定が無い場合
            $this->FIX_EMJ[$enob] = $this->emoji_chr;
          }
        } elseif (preg_match('/^au$/i',$this->HARD_DATA['hard'])) {
          # au携帯表示時
          if ($a_nob != '') {
            # 対応絵文字設定がある場合
            if ($this->HARD_DATA['tg_flag'] == 'WIN') {
              $this->FIX_EMJ[$enob] = $this->AU_NO_TO_TXT_WIN[$a_nob];
            } else {
              $this->FIX_EMJ[$enob] = $this->AU_NO_TO_TXT[$a_nob];
            }
          } else {
            # 対応絵文字設定が無い場合
            $this->FIX_EMJ[$enob] = $this->emoji_chr;
          }
        }
      }
    }

  }

  # ﾘｸｴｽﾄﾃﾞｰﾀ前処理(ｴｽｹｰﾌﾟｺｰﾄﾞ削除,文字変換,絵文字ｴﾝｺｰﾄﾞ) /////////////////
  # ﾘｸｴｽﾄﾃﾞｰﾀの前処理をします。
  # [引渡し値]
  # 　$mode     : ﾘｸｴｽﾄ処理区分を指定
  # 　　　　　　　'P'or'p':$_POSTのみ
  # 　　　　　　　'G'or'g':$_GETのみ
  # 　　　　　　　'R'or'r':$_REQUESTのみ
  # 　　　　　　　'p','g','r'の組合せにより複数指定可能
  # 　$kana     : 文字列変換指定
  # 　　　　　　　指定なし→変換なし
  # 　　　　　　　全角数字→半角数字 'n'
  # 　　　　　　　全角英字→半角英字 'r'
  # 　　　　　　　全角英数字→半角英数字 'a'
  # 　　　　　　　全角ｶﾀｶﾅ→半角ｶﾀｶﾅ 'kv'
  # 　　　　　　　全角英数字ｶﾀｶﾅ→半角英数字ｶﾀｶﾅ 'kva'
  # 　　　　　　　半角数字→全角数字 'N' 
  # 　　　　　　　半角英字→全角英字 'R'
  # 　　　　　　　半角英数字→全角英数字 'A'
  # 　　　　　　　半角ｶﾀｶﾅ→全角ｶﾀｶﾅ 'KV'
  # 　　　　　　　半角英数字ｶﾀｶﾅ→全角英数字ｶﾀｶﾅ 'KVA'
  # 　$out_code : 出力文字ｺｰﾄﾞ指定(ﾃﾞﾌｫﾙﾄ 'Shift_JIS')
  # 　$input_code : 入力文字ｺｰﾄﾞ指定(指定なし:設定による、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # [返り値]
  # 　なし
  #////////////////////////////////////////////////////////////////////////////
  function reqest_data_conv($mode='r',$kana='',$out_code='SJIS',$input_code='') {
    if ((mb_preferred_mime_name($out_code) == mb_preferred_mime_name('SJIS'))  and ($out_code != $this->chg_code_sjis)) { $out_code = $this->chg_code_sjis; }
    if ((mb_preferred_mime_name($out_code) == mb_preferred_mime_name('EUC'))   and ($out_code != $this->chg_code_euc))  { $out_code = $this->chg_code_euc; }
    if (preg_match('/r/i',$mode)) {
      $_REQUEST = $this->_reqest_data_conv($_REQUEST,$kana,$out_code,$input_code);
    }
    if (preg_match('/g/i',$mode)) {
      $_GET = $this->_reqest_data_conv($_GET,$kana,$out_code,$input_code);
    }
    if (preg_match('/p/i',$mode)) {
      $_POST = $this->_reqest_data_conv($_POST,$kana,$out_code,$input_code);
    }
  }

  # 配列内ﾃﾞｰﾀ一括処理(ｴｽｹｰﾌﾟｺｰﾄﾞ削除,文字変換,絵文字ｴﾝｺｰﾄﾞ) //////////////////
  # 配列(ﾊｯｼｭ)内ﾃﾞｰﾀの一括処理をします。
  # [引渡し値]
  # 　$ARRAY_DATA  : 処理対象の配列(ﾊｯｼｭ)指定
  # 　$kana        : 文字列変換指定
  # 　　　　　　　　指定なし→変換なし
  # 　　　　　　　　全角数字→半角数字 'n'
  # 　　　　　　　　全角英字→半角英字 'r'
  # 　　　　　　　　全角英数字→半角英数字 'a'
  # 　　　　　　　　全角ｶﾀｶﾅ→半角ｶﾀｶﾅ 'kv'
  # 　　　　　　　　全角英数字ｶﾀｶﾅ→半角英数字ｶﾀｶﾅ 'kva'
  # 　　　　　　　　半角数字→全角数字 'N' 
  # 　　　　　　　　半角英字→全角英字 'R'
  # 　　　　　　　　半角英数字→全角英数字 'A'
  # 　　　　　　　　半角ｶﾀｶﾅ→全角ｶﾀｶﾅ 'KV'
  # 　　　　　　　　半角英数字ｶﾀｶﾅ→全角英数字ｶﾀｶﾅ 'KVA'
  # 　$escape_flag : ｴｽｹｰﾌﾟｺｰﾄﾞ削除指定(指定なしor 0:php.ini設定に従う、1:削除する、2:処理しない)
  # 　$out_code    : 出力文字ｺｰﾄﾞ指定(ﾃﾞﾌｫﾙﾄ 'Shift_JIS')
  # 　$input_code  : 入力文字ｺｰﾄﾞ指定(指定なし:設定による、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # [返り値]
  # 　なし
  #////////////////////////////////////////////////////////////////////////////
  function array_data_conv($ARRAY_DATA,$kana='',$escape_flag=0,$out_code='SJIS',$input_code='') {
    if ((mb_preferred_mime_name($out_code) == mb_preferred_mime_name('SJIS')) and ($out_code != $this->chg_code_sjis)) { $out_code = $this->chg_code_sjis; }
    if ((mb_preferred_mime_name($out_code) == mb_preferred_mime_name('EUC'))  and ($out_code != $this->chg_code_euc))  { $out_code = $this->chg_code_euc; }
    $ARRAY_DATA = $this->_reqest_data_conv($ARRAY_DATA,$kana,$out_code,$input_code,$escape_flag);
    return $ARRAY_DATA;
  }

  # 配列内ﾃﾞｰﾀ前処理(内部処理) ////////////////////////////////////////////////
  function _reqest_data_conv($ARRAYDATA,$kana,$out_code,$input_code,$escape_flag=0) {
    $ARRAYOUT = array();
    if ($escape_flag == 0) {
      $quote_flag = ini_get('magic_quotes_gpc');
    } elseif ($escape_flag == 1) {
      $quote_flag = '1';
    } elseif ($escape_flag == 2) {
      $quote_flag = '0';
    }
    $RQT = array();
    $RQT = array_keys($ARRAYDATA);
    foreach ($RQT as $rdt) {
      if (is_array($ARRAYDATA[$rdt])) {
        # 値が配列の場合
        $ARRAYOUT[$rdt] = $this->_reqest_data_conv($ARRAYDATA[$rdt],$kana,$out_code,$input_code);
      } else {
        if ($ARRAYDATA[$rdt] == '') {
          # 値無しの場合
          $ARRAYOUT[$rdt] = $ARRAYDATA[$rdt];
        } else {
          # ｴｽｹｰﾌﾟ処理
          if ($quote_flag == '1') { $ARRAYOUT[$rdt] = stripslashes($ARRAYDATA[$rdt]); }
          # ﾃﾞｰﾀｴﾝｺｰﾃﾞｨﾝｸﾞ取得
          if ($input_code == '') {
            if (($this->HARD_DATA['hard'] == $this->softbank_name) and ($this->HARD_DATA['tg_flag'] == '3G')) {
              $this_code = mb_detect_encoding($ARRAYDATA[$rdt],$this->ENCODINGLIST['UTF-8']);
            } else {
              $this_code = mb_detect_encoding($ARRAYDATA[$rdt],$this->ENCODINGLIST[$this->chr_code]);
            }
          }
          # 絵文字ｴﾝｺｰﾄﾞ
          if ($input_code == '') {
            $ARRAYOUT[$rdt] = $this->emj_encode($ARRAYDATA[$rdt],$out_code,'',$this_code);
          } else {
            $ARRAYOUT[$rdt] = $this->emj_encode($ARRAYDATA[$rdt],$out_code,'',$input_code);
          }
          # 文字変換
          if ($kana != '') { $ARRAYOUT[$rdt] = mb_convert_kana($ARRAYDATA[$rdt],$kana,$out_code); }
        }
      }
    }
    return $ARRAYOUT;
  }

  # 絵文字変換(Web用) /////////////////////////////////////////////////////////
  # 絵文字ｺｰﾄﾞをｱｸｾｽｷｬﾘｱに応じてWeb表示用に絵文字変換して出力します。
  # [引渡し値]
  # 　$textstr  : 変換対象文字列
  # 　$out_code : 変換後出力ｺｰﾄﾞ指定
  # 　$input_code : 入力文字ｺｰﾄﾞ指定(指定なし:設定による、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # [返り値]
  # 　$textstr  : 変換後文字列
  #////////////////////////////////////////////////////////////////////////////
  function replace_emoji($textstr,$out_code='',$input_code='') {
    if (isset($textstr)) {
      # 絵文字ｴﾝｺｰﾄﾞ
      $textstr = $this->emj_encode($textstr,'',1,$input_code);
      # ﾃｷｽﾄShift_JIS変換
      if ($input_code == '') {
        $de = mb_detect_encoding($textstr,$this->ENCODINGLIST[$this->chr_code]);
      } else {
        $de = mb_detect_encoding($textstr,$this->ENCODINGLIST[$input_code]);
      }
      if ($de) {
        $text_code = mb_preferred_mime_name($de);
        if ($text_code != mb_preferred_mime_name($this->chg_code_sjis)) { $textstr = @mb_convert_encoding($textstr,$this->chg_code_sjis,$text_code); }
      }
      $this->input_code_tmpl = 'SJIS';
      # 絵文字ﾃﾞｺｰﾄﾞ
      $TEXTSTR = $this->emj_decode($textstr,'',$out_code,'');
      $textstr = $TEXTSTR['web'];
    }
    return $textstr;
  }

  # 絵文字変換(Web用ｷｬﾘｱ指定) /////////////////////////////////////////////////
  # 絵文字ｺｰﾄﾞを指定ｷｬﾘｱに応じてWeb表示用に絵文字変換して出力します。
  # [引渡し値]
  # 　$textstr  : 変換対象文字列
  # 　$career   : 変換対象ｷｬﾘｱ指定(指定無い場合ｱｸｾｽｷｬﾘｱ,'DoCoMo','au','SoftBank'or'Vodafone')
  # 　$out_code : 変換後出力ｺｰﾄﾞ指定
  # 　$input_code : 入力文字ｺｰﾄﾞ指定(指定なし:設定による、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # [返り値]
  # 　$textstr  : 変換後文字列
  #////////////////////////////////////////////////////////////////////////////
  function replace_emoji_career($textstr,$career='DoCoMo',$out_code='',$input_code='') {
    if (isset($textstr)) {
      # 絵文字ｴﾝｺｰﾄﾞ
      $textstr = $this->emj_encode($textstr,'',1,$input_code);
      # ﾃｷｽﾄShift_JIS変換
      if ($input_code == '') {
        $de = mb_detect_encoding($textstr,$this->ENCODINGLIST[$this->chr_code]);
      } else {
        $de = mb_detect_encoding($textstr,$this->ENCODINGLIST[$input_code]);
      }
      if ($de) {
        $text_code = mb_preferred_mime_name($de);
        if ($text_code != mb_preferred_mime_name($this->chg_code_sjis)) { $textstr = @mb_convert_encoding($textstr,$this->chg_code_sjis,$text_code); }
      }
      $this->input_code_tmpl = 'SJIS';
      # 絵文字ﾃﾞｺｰﾄﾞ
      $TEXTSTR = $this->emj_decode($textstr,$career,$out_code,'');
      $textstr = $TEXTSTR['web'];
    }
    return $textstr;
  }

  # 絵文字変換(ﾒｰﾙ送信用) /////////////////////////////////////////////////////
  # 絵文字ｺｰﾄﾞを指定ｷｬﾘｱに応じてﾒｰﾙ送信用に絵文字変換して出力します。
  # [引渡し値]
  # 　$textstr     : 変換対象文字列
  # 　$career      : 変換対象ｷｬﾘｱ指定(指定無い場合ｱｸｾｽｷｬﾘｱ,'DoCoMo','au','SoftBank'or'Vodafone')
  # 　$out_code    : 変換後出力ｺｰﾄﾞ指定
  # 　$input_code  : 入力文字ｺｰﾄﾞ指定(指定なし:設定による、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # 　$career_gene : 携帯世代('3G':第3世代(ﾃﾞﾌｫﾙﾄ)、'2G':第2世代-SoftBank携帯のみ)
  # [返り値]
  # 　$textstr  : 変換後文字列
  #////////////////////////////////////////////////////////////////////////////
  function replace_emoji_mail($textstr,$career='PC',$input_code='',$career_gene='3G') {
    if (isset($textstr)) {
      # 絵文字ｴﾝｺｰﾄﾞ
      $textstr = $this->emj_encode($textstr,'',1,$input_code);
      # ﾃｷｽﾄShift_JIS変換
      if ($input_code == '') {
        $de = mb_detect_encoding($textstr,$this->ENCODINGLIST[$this->chr_code]);
      } else {
        $de = mb_detect_encoding($textstr,$this->ENCODINGLIST[$input_code]);
      }
      if ($de) {
        $text_code = mb_preferred_mime_name($de);
        if ($text_code != mb_preferred_mime_name($this->chg_code_sjis)) { $textstr = @mb_convert_encoding($textstr,$this->chg_code_sjis,$text_code); }
      }
      $this->input_code_tmpl = 'SJIS';
      # 絵文字ﾃﾞｺｰﾄﾞ
      $TEXTSTR = $this->emj_decode($textstr,$career,'JIS','');
      if ((($career == 'Vodafone') or ($career == 'SoftBank')) and ($career_gene == '2G')) {
        $textstr = $TEXTSTR['mail_plain'];
      } else {
        $textstr = $TEXTSTR['mail'];
      }
    }
    return $textstr;
  }

  # 絵文字変換(ﾌｫｰﾑ表示用) ////////////////////////////////////////////////////
  # 絵文字ｺｰﾄﾞをｱｸｾｽｷｬﾘｱに応じてﾌｫｰﾑ表示用に絵文字変換して出力します。
  # [引渡し値]
  # 　$textstr  : 変換対象文字列
  # 　$career   : 変換対象ｷｬﾘｱ指定(指定無い場合ｱｸｾｽｷｬﾘｱ,'DoCoMo','au','SoftBank'or'Vodafone')
  # 　$out_code : 変換後出力ｺｰﾄﾞ指定
  # 　$input_code : 入力文字ｺｰﾄﾞ指定(指定なし:設定による、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # [返り値]
  # 　$textstr  : 変換後文字列
  #////////////////////////////////////////////////////////////////////////////
  function replace_emoji_form($textstr,$career='',$out_code='',$input_code='') {
    if (isset($textstr)) {
      # 絵文字ｴﾝｺｰﾄﾞ
      $textstr = $this->emj_encode($textstr,'',1,$input_code);
      # ﾃｷｽﾄShift_JIS変換
      if ($input_code == '') {
        $de = mb_detect_encoding($textstr,$this->ENCODINGLIST[$this->chr_code]);
      } else {
        $de = mb_detect_encoding($textstr,$this->ENCODINGLIST[$input_code]);
      }
      if ($de) {
        $text_code = mb_preferred_mime_name($de);
        if ($text_code != mb_preferred_mime_name($this->chg_code_sjis)) { $textstr = @mb_convert_encoding($textstr,$this->chg_code_sjis,$text_code); }
      }
      $this->input_code_tmpl = 'SJIS';
      # 絵文字ﾃﾞｺｰﾄﾞ
      $TEXTSTR = $this->emj_decode($textstr,$career,$out_code,'');
      $textstr = $TEXTSTR['form'];
    }
    return $textstr;
  }

  # 絵文字ｺｰﾄﾞ削除 ////////////////////////////////////////////////////////////
  # 文字列から絵文字を削除します。
  # [引渡し値]
  # 　$textstr     : 変換対象文字列
  # 　$docomo_flag : DoCoMo絵文字削除(0:削除する,1:削除しない)
  # 　$voda_flag   : SoftBank絵文字削除(0:削除する,1:削除しない)
  # 　$au_flag     : au絵文字削除(0:削除する,1:削除しない)
  # 　$out_code    : 変換後出力ｺｰﾄﾞ指定
  # 　$enc_cancel  : 内部ｴﾝｺｰﾄﾞ処理ｷｬﾝｾﾙ指定(1:ｷｬﾝｾﾙ)
  # 　$input_code : 入力文字ｺｰﾄﾞ指定(指定なし:設定による、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # [返り値]
  # 　$textstr     : 変換後文字列
  #////////////////////////////////////////////////////////////////////////////
  function delete_emoji_code($textstr,$docomo_flag='0',$voda_flag='0',$au_flag='0',$out_code='',$enc_cancel='',$input_code='') {
    global $emoji_sub_obj;
    if (is_object($emoji_sub_obj)) {
      $textstr = $emoji_sub_obj->delete_emoji_code($textstr,$docomo_flag,$voda_flag,$au_flag,$out_code,$enc_cancel,$input_code);
    }
    return $textstr;
  }

  # 絵文字ｺｰﾄﾞ下駄変換 ////////////////////////////////////////////////////////
  # 文字列中の絵文字を下駄変換します。
  # [引渡し値]
  # 　$textstr     : 変換対象文字列
  # 　$docomo_flag : DoCoMo絵文字下駄変換(0:変換する,1:変換しない)
  # 　$voda_flag   : SoftBank絵文字下駄変換(0:変換する,1:変換しない)
  # 　$au_flag     : au絵文字下駄変換(0:変換する,1:変換しない)
  # 　$out_code    : 変換後出力ｺｰﾄﾞ指定
  # 　$enc_cancel  : 内部ｴﾝｺｰﾄﾞ処理ｷｬﾝｾﾙ指定(1:ｷｬﾝｾﾙ)
  # 　$input_code : 入力文字ｺｰﾄﾞ指定(指定なし:設定による、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # [返り値]
  # 　$textstr     : 変換後文字列
  #////////////////////////////////////////////////////////////////////////////
  function emoji2geta($textstr,$docomo_flag='0',$voda_flag='0',$au_flag='0',$out_code='',$enc_cancel='',$input_code='') {
    global $emoji_sub_obj;
    if (is_object($emoji_sub_obj)) {
      $textstr = $emoji_sub_obj->emoji2geta($textstr,$docomo_flag,$voda_flag,$au_flag,$out_code,$enc_cancel,$input_code);
    }
    return $textstr;
  }

  # 絵文字ｺｰﾄﾞ指定ﾃｷｽﾄ変換 ////////////////////////////////////////////////////
  # 文字列中の絵文字を指定の文字列に変換します。
  # [引渡し値]
  # 　$textstr     : 変換対象文字列
  # 　$replace_str : 変換対象文字列
  # 　$docomo_flag : DoCoMo絵文字下駄変換(0:変換する,1:変換しない)
  # 　$voda_flag   : SoftBank絵文字下駄変換(0:変換する,1:変換しない)
  # 　$au_flag     : au絵文字下駄変換(0:変換する,1:変換しない)
  # 　$out_code    : 変換後出力ｺｰﾄﾞ指定
  # 　$enc_cancel  : 内部ｴﾝｺｰﾄﾞ処理ｷｬﾝｾﾙ指定(1:ｷｬﾝｾﾙ)
  # 　$input_code  : 入力文字ｺｰﾄﾞ指定(指定なし:SJIS、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # [返り値]
  # 　$textstr     : 変換後文字列
  #////////////////////////////////////////////////////////////////////////////
  function emoji_str_replace($textstr,$replace_str,$docomo_flag='0',$voda_flag='0',$au_flag='0',$out_code='',$enc_cancel='',$input_code='') {
    global $emoji_sub_obj;
    if (is_object($emoji_sub_obj)) {
      $textstr = $emoji_sub_obj->emoji_str_replace($textstr,$replace_str,$docomo_flag,$voda_flag,$au_flag,$out_code,$enc_cancel,$input_code);
    }
    return $textstr;
  }

  # 文字数ｶｳﾝﾄ ////////////////////////////////////////////////////////////////
  # 文字列の絵文字を加味した文字数をｶｳﾝﾄします。
  # ﾊﾞｲﾅﾘｶｳﾝﾄは絵文字を2ﾊﾞｲﾄとしてｶｳﾝﾄします。
  # [引渡し値]
  # 　$textstr    : ﾁｪｯｸ対象文字列
  # 　$enc_cancel : 内部ｴﾝｺｰﾄﾞ処理ｷｬﾝｾﾙ指定(1:ｷｬﾝｾﾙ)
  # 　$input_code : 入力文字ｺｰﾄﾞ指定(指定なし:設定による、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # [返り値]
  # 　$COUNTDATA['mb_strlen']   : 全文字数(ﾏﾙﾁﾊﾞｲﾄも1文字としてｶｳﾝﾄ)
  # 　$COUNTDATA['mb_strwidth'] : 全ﾊﾞｲﾄ数(半角:1,全角:2,絵文字:2)
  # 　$COUNTDATA['total']       : 全絵文字数
  # 　$COUNTDATA['DoCoMo']      : DoCoMo絵文字数
  # 　$COUNTDATA['au']          : au絵文字数
  # 　$COUNTDATA['SoftBank']    : SoftBank絵文字数
  #////////////////////////////////////////////////////////////////////////////
  function emj_check($textstr,$enc_cancel='',$input_code='') {
    global $emoji_sub_obj;
    $COUNTDATA = False;
    if (is_object($emoji_sub_obj)) {
      $COUNTDATA = $emoji_sub_obj->emj_check($textstr,$enc_cancel,$input_code);
    }
    return $COUNTDATA;
  }

  # 文字切り詰め //////////////////////////////////////////////////////////////
  # 絵文字を含む文字列を指定した幅に切り詰めます。
  # ※絵文字は2ﾊﾞｲﾄとして処理されます。
  # [引渡し値]
  # 　$textstr    : 処理対象文字列
  # 　$offset     : 開始位置
  # 　$width      : 文字列の幅
  # 　$end_str    : 切り詰めた場合の目印の文字列
  # 　$out_code   : 出力文字ｺｰﾄﾞ
  # 　$enc_cancel : 内部ｴﾝｺｰﾄﾞ処理ｷｬﾝｾﾙ指定(1:ｷｬﾝｾﾙ)
  # 　$input_code : 入力文字ｺｰﾄﾞ指定(指定なし:設定による、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # [返り値]
  # 　$textstr    : 指定された幅の文字列
  #////////////////////////////////////////////////////////////////////////////
  function emj_strimwidth($textstr,$offset,$width,$end_str,$out_code='',$enc_cancel='',$input_code='') {
    global $emoji_sub_obj;
    if (is_object($emoji_sub_obj)) {
      $textstr = $emoji_sub_obj->emj_strimwidth($textstr,$offset,$width,$end_str,$out_code,$enc_cancel,$input_code);
    }
    return $textstr;
  }

  # 絵文字変換 ////////////////////////////////////////////////////////////////
  # 指定の絵文字を別の指定した絵文字に置き換えます。
  # [引渡し値]
  # 　$textstr      : 処理対象文字列
  # 　$original_emj : 元絵文字
  # 　$change_emj   : 変換絵文字
  # 　$out_code     : 出力文字ｺｰﾄﾞ
  # 　$enc_cancel   : 内部ｴﾝｺｰﾄﾞ処理ｷｬﾝｾﾙ指定(1:ｷｬﾝｾﾙ)
  # 　$input_code : 入力文字ｺｰﾄﾞ指定(指定なし:設定による、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # [返り値]
  # 　$textstr             : 指定された幅の文字列
  #////////////////////////////////////////////////////////////////////////////
  function emj_change($textstr,$original_emj,$change_emj,$out_code='',$enc_cancel='',$input_code='') {
    global $emoji_sub_obj;
    if (is_object($emoji_sub_obj)) {
      $textstr = $emoji_sub_obj->emj_change($textstr,$original_emj,$change_emj,$out_code,$enc_cancel,$input_code);
    }
    return $textstr;
  }

  # 絵文字ｺｰﾄﾞｴﾝｺｰﾄﾞ //////////////////////////////////////////////////////////
  # 文字列中の絵文字をｴﾝｺｰﾄﾞします。
  # [引渡し値]
  # 　$textstr     : 変換対象文字列
  # 　$out_code    : 変換後出力ｺｰﾄﾞ指定
  # 　$encode_pass : 文字ｺｰﾄﾞ変換無効化('1')
  # 　$input_code  : 入力文字ｺｰﾄﾞ指定(指定なし:全ｺｰﾄﾞﾁｪｯｸ、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # [返り値]
  # 　$textstr     : 変換後文字列
  #////////////////////////////////////////////////////////////////////////////
  function emj_encode($textstr,$out_code='',$encode_pass='',$input_code='') {
    if (isset($textstr)) {
      # 入力文字ｺｰﾄﾞ設定
      if ($input_code == '') {
#        if ($this->chr_code == 'UTF-8') { $input_code = 'UTF-8'; }
        $input_code = $this->chr_code;
      }
#      if ($input_code != 'UTF-8') { $input_code = 'SJIS'; }

      # SoftBank絵文字UTF-8ｴﾝｺｰﾄﾞ
      if (($input_code == '') or (mb_preferred_mime_name($input_code) == mb_preferred_mime_name('UTF-8'))) {
        $textstr = $this->_replace_v_emoji_utf8($textstr);
      }
      # SoftBank絵文字ｴﾝｺｰﾄﾞ
      if (($input_code == '') or (mb_preferred_mime_name($input_code) == mb_preferred_mime_name('Shift_JIS'))) {
        $textstr = $this->_replace_v_emoji($textstr);
      }

      # DoCoMo絵文字UTF-8ｴﾝｺｰﾄﾞ
      if (($input_code == '') or (mb_preferred_mime_name($input_code) == mb_preferred_mime_name('UTF-8'))) {
        $textstr = $this->_replace_d_emoji_utf8($textstr);
      }
      # DoCoMo絵文字ｴﾝｺｰﾄﾞ
      if (($input_code == '') or (mb_preferred_mime_name($input_code) == mb_preferred_mime_name('Shift_JIS'))) {
        $textstr = $this->_replace_d_emoji($textstr);
      }
      # DoCoMo絵文字ﾃｷｽﾄｴﾝｺｰﾄﾞ
      $textstr = $this->_replace_d_emoji_text($textstr);

      # au絵文字UTF-8ｴﾝｺｰﾄﾞ
      if (($input_code == '') or (mb_preferred_mime_name($input_code) == mb_preferred_mime_name('UTF-8'))) {
        $textstr = $this->_replace_a_emoji_utf8($textstr);
      }
      # au絵文字ｴﾝｺｰﾄﾞ
      if (($input_code == '') or (mb_preferred_mime_name($input_code) == mb_preferred_mime_name('Shift_JIS'))) {
        $textstr = $this->_replace_a_emoji($textstr);
      }

      # ﾃｷｽﾄｺｰﾄﾞ変換
      if ($encode_pass == '') {
        $de = mb_detect_encoding($textstr,$this->ENCODINGLIST[$input_code]);
        if ($de) {
          $text_code = mb_preferred_mime_name($de);
          # 出力ｺｰﾄﾞ設定
          if ($out_code == '') { $oc = $this->chr_code; } else { $oc = $out_code; }
          if ($text_code != mb_preferred_mime_name($oc)) {
            # 文字列ｺｰﾄﾞが指定出力ｺｰﾄﾞと異なる場合
            if (mb_preferred_mime_name($oc) != mb_preferred_mime_name($this->chg_code_sjis)) {
              # SJIS指定の場合
              $textstr = @mb_convert_encoding($textstr,$oc,$this->chg_code_sjis);
            } else {
              # SJIS以外の場合
              $textstr = @mb_convert_encoding($textstr,$oc,$text_code);
            }
          }
        }
      }
    } else {
      $textstr = '';
    }
    return $textstr;
  }

  # 絵文字ｺｰﾄﾞﾃﾞｺｰﾄﾞ //////////////////////////////////////////////////////////
  # 文字列中の絵文字をﾃﾞｺｰﾄﾞします。
  # [引渡し値]
  # 　$textstr  : 変換対象文字列
  # 　$career   : 変換対象ｷｬﾘｱ指定(指定無い場合ｱｸｾｽｷｬﾘｱ,'DoCoMo','au','SoftBank'or'Vodafone')
  # 　$out_code : 変換後出力ｺｰﾄﾞ指定
  # 　$img_mode : 画像変換強制指定(1:強制画像変換)
  # [返り値]
  # 　$DECODE_DATA['web']        : 変換後文字列(Web用)
  # 　$DECODE_DATA['form']       : 変換後文字列(Form用)
  # 　$DECODE_DATA['mail']       : 変換後文字列(Mail用)
  # 　$DECODE_DATA['mail_plain'] : 変換後文字列(Mail用-Softbankﾃｷｽﾄ用)
  # 　$DECODE_DATA['text']       : 変換後文字列(ﾃｷｽﾄｺｰﾄﾞ)
  # 　$DECODE_DATA['bin']        : 変換後文字列(ﾊﾞｲﾅﾘｺｰﾄﾞ)
  #////////////////////////////////////////////////////////////////////////////
  function emj_decode($textstr,$career='',$out_code='',$img_mode='') {
    if ($out_code == '') { $oc = $this->chr_code; } else { $oc = $out_code; }

    if ($this->input_code_tmpl != '') { $oc = $this->input_code_tmpl; $this->input_code_tmpl = ''; }
    $DECODE_DATA = array();
    $DECODE_DATA['web']        = $textstr;
    $DECODE_DATA['form']       = $textstr;
    $DECODE_DATA['mail']       = $textstr;
    $DECODE_DATA['mail_plain'] = $textstr;
    $DECODE_DATA['text']       = $textstr;
    $DECODE_DATA['bin']        = $textstr;
    if (isset($textstr)) {
      # 変換先ｷｬﾘｱ設定
      if ($career == '') {
        # 変換先ｷｬﾘｱ指定無し(ｱｸｾｽｷｬﾘｱ変換)
        $set_career = $this->HARD_DATA['hard'];
      } else {
        # 変換先ｷｬﾘｱ指定有り(指定ｷｬﾘｱ変換)
        $set_career = $career;
      }

      # 絵文字ｴﾝｺｰﾄﾞ変換
      $textstr_img = $textstr;
      if (($this->img_onry_flag != '1') and ($img_mode != '1')) {
        # PC又は強制画像変換指定以外について絵文字対応変換
        $textstr = $this->_emj_enc_change($textstr,$set_career);
      }

      # ﾃｷｽﾄｺｰﾄﾞ変換
      $change_code = '';
      $text_code = mb_detect_encoding($textstr,$this->ENCODINGLIST[$oc]);
      if ($text_code != '') {
        if ($out_code == '') {
          # 出力ｺｰﾄﾞ指定なしの場合(ﾃﾞﾌｫﾙﾄ設定ｺｰﾄﾞ出力)
          if (mb_preferred_mime_name($this->chr_code) != mb_preferred_mime_name($text_code)) {
            $textstr = @mb_convert_encoding($textstr,$this->chr_code,$text_code);
            $change_code = $this->chr_code;
          }
        } else {
          # 出力ｺｰﾄﾞ指定有りの場合
          if (mb_preferred_mime_name($out_code) != mb_preferred_mime_name($text_code)) {
            $textstr = @mb_convert_encoding($textstr,$out_code,$text_code);
            $change_code = $out_code;
          }
        }
      }

      # ﾃｷｽﾄ準備
      $DECODE_DATA['web']        = $textstr;
      $DECODE_DATA['form']       = $textstr;
      $DECODE_DATA['mail']       = $textstr;
      $DECODE_DATA['mail_plain'] = $textstr;
      $DECODE_DATA['text']       = $textstr;
      $DECODE_DATA['bin']        = $textstr;
      # ﾌｫｰﾑ表示時HTMLｴﾝﾃｨﾃｨ実行
      $DECODE_DATA['form'] = $this->form_htmlentities($DECODE_DATA['form']);
      # ﾙｰﾌﾟ用ﾃｷｽﾄ設定
      $loop_string = $textstr;
      if (preg_match('/^pc$/i',$set_career)) {
        # PC変換時
        while (preg_match('/(\{|\{#|###|<!\-\-)(emj_d_|d|emj_a_|a|emj_am_|am|emj_v_|v)(\d{4})(\}|#\}|###|\-\->)/',$loop_string,$PM)) {
          # Web表示用ﾃﾞｺｰﾄﾞ(画像変換)
          # ﾒｰﾙ用ﾃﾞｺｰﾄﾞ(本関数での処理なし)
          if (($PM[2] == 'emj_d_') or ($PM[2] == 'd')) {
            # DoCoMoｴﾝｺｰﾄﾞ
            if ($change_code == '') {
              $set_data = $this->DOCOMO_NO_TO_IMG[$PM[3]];
            } else {
              $set_data = @mb_convert_encoding($this->DOCOMO_NO_TO_IMG[$PM[3]],$out_code,'SJIS');
            }
          } elseif (($PM[2] == 'emj_a_') or ($PM[2] == 'a') or ($PM[2] == 'emj_am_') or ($PM[2] == 'am')) {
            # auｴﾝｺｰﾄﾞ
            if ($change_code == '') {
              $set_data = $this->AU_NO_TO_IMG[$PM[3]];
            } else {
              $set_data = @mb_convert_encoding($this->AU_NO_TO_IMG[$PM[3]],$out_code,'SJIS');
            }
          } elseif (($PM[2] == 'emj_v_') or ($PM[2] == 'v')) {
            # SoftBankｴﾝｺｰﾄﾞ
            if ($change_code == '') {
              $set_data = $this->SOFT_NO_TO_IMG[$PM[3]];
            } else {
              $set_data = @mb_convert_encoding($this->SOFT_NO_TO_IMG[$PM[3]],$out_code,'SJIS');
            }
          }
          $DECODE_DATA['web']  = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_data,$DECODE_DATA['web']);
          $DECODE_DATA['mail'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_data,$DECODE_DATA['web']);
          # ﾌｫｰﾑ表示用ﾃﾞｺｰﾄﾞ(絵文字ｴﾝｺｰﾄﾞのまま出力)
          $DECODE_DATA['form'] = $this->form_htmlentities($textstr);
          # ﾃｷｽﾄﾃﾞｺｰﾄﾞ
          if (($PM[2] == 'emj_d_') or ($PM[2] == 'd')) {
            # DoCoMoｴﾝｺｰﾄﾞ
            $set_data = $this->DOCOMO_NO_TO_UTXT[$PM[3]];
          } elseif (($PM[2] == 'emj_a_') or ($PM[2] == 'a') or ($PM[2] == 'emj_am_') or ($PM[2] == 'am')) {
            # auｴﾝｺｰﾄﾞ
            $set_data = $this->AU_NO_TO_TXT_WIN[$PM[3]];
          } elseif (($PM[2] == 'emj_v_') or ($PM[2] == 'v')) {
            # SoftBankｴﾝｺｰﾄﾞ
            $set_data = $this->SOFT_NO_TO_WEBCODE[$PM[3]];
          }
          $DECODE_DATA['text'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_data,$DECODE_DATA['text']);
          # ﾊﾞｲﾅﾘﾃﾞｺｰﾄﾞ
          if (($PM[2] == 'emj_d_') or ($PM[2] == 'd')) {
            # DoCoMoｴﾝｺｰﾄﾞ
            $set_data = $this->DOCOMO_NO_TO_BIN[$PM[3]];
          } elseif (($PM[2] == 'emj_a_') or ($PM[2] == 'a') or ($PM[2] == 'emj_am_') or ($PM[2] == 'am')) {
            # auｴﾝｺｰﾄﾞ
            $set_data = $this->AU_NO_TO_BIN[$PM[3]];
          } elseif (($PM[2] == 'emj_v_') or ($PM[2] == 'v')) {
            # SoftBankｴﾝｺｰﾄﾞ
            $set_data = $this->SOFT_NO_TO_WEBCODE[$PM[3]];
          }
          $DECODE_DATA['bin'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_data,$DECODE_DATA['bin']);
          # ﾙｰﾌﾟ用ﾃｷｽﾄ処理
          $loop_string = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|','',$loop_string);
        }
      } elseif (preg_match('/^docomo$/i',$set_career)) {
        # DoCoMoｷｬﾘｱに対しての絵文字ﾃﾞｺｰﾄﾞ
        while (preg_match('/(\{|\{#|###|<!\-\-)(emj_d_|d)(\d{4})(\}|#\}|###|\-\->)/',$loop_string,$PM)) {
          # Web表示用
          if (($this->img_onry_flag == '1') or ($img_mode == '1')) {
            # 強制画像変換指定
            if ($change_code == '') {
              $set_data = $this->DOCOMO_NO_TO_IMG[$PM[3]];
            } else {
              $set_data = @mb_convert_encoding($this->DOCOMO_NO_TO_IMG[$PM[3]],$out_code,'SJIS');
            }
            $DECODE_DATA['web'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_data,$DECODE_DATA['web']);
          } else {
            # 絵文字ｺｰﾄﾞ変換
            if ($this->content_type == 'xhtml') {
              $rep_code = preg_replace('/<font\scolor=\"([^\"]+)\">([^<]+)<\/font>/','<span style="color:\\1">\\2</span>',$this->DOCOMO_NO_TO_UTXT_COLOR[$PM[3]]);
              $DECODE_DATA['web'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$rep_code,$DECODE_DATA['web']);
            } else {
              $DECODE_DATA['web'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->DOCOMO_NO_TO_UTXT_COLOR[$PM[3]],$DECODE_DATA['web']);
            }

          }
          # ﾌｫｰﾑ表示用ﾃﾞｺｰﾄﾞ
          $DECODE_DATA['form'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->DOCOMO_NO_TO_UTXT[$PM[3]],$DECODE_DATA['form']);
          # ﾒｰﾙ用ﾃﾞｺｰﾄﾞ
          if (($this->img_onry_flag == '1') or ($img_mode == '1')) {
            if ($change_code == '') {
              $set_data = $this->DOCOMO_NO_TO_IMG_MAIL[$PM[3]];
            } else {
              $set_data = @mb_convert_encoding($this->DOCOMO_NO_TO_IMG_MAIL[$PM[3]],$out_code,'SJIS');
            }
            $DECODE_DATA['mail'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_data,$DECODE_DATA['mail']);
          } else {
            # 絵文字ｺｰﾄﾞ変換
            $DECODE_DATA['mail'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->DOCOMO_NO_TO_BIN[$PM[3]],$DECODE_DATA['mail']);
          }
          $DECODE_DATA['mail'] = preg_replace('|\stitle=\".+?\"|i','',$DECODE_DATA['mail']);
          $DECODE_DATA['mail'] = preg_replace('|\salt=\".+?\"|i'  ,'',$DECODE_DATA['mail']);
          # ﾃｷｽﾄﾃﾞｺｰﾄﾞ
          $DECODE_DATA['text'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->DOCOMO_NO_TO_UTXT[$PM[3]],$DECODE_DATA['text']);
          # ﾊﾞｲﾅﾘﾃﾞｺｰﾄﾞ
          if ($oc == 'UTF-8') {
            $DECODE_DATA['bin']  = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->DOCOMO_NO_TO_BIN_UTF8[$PM[3]],$DECODE_DATA['bin']);
          } else {
            $DECODE_DATA['bin']  = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->DOCOMO_NO_TO_BIN[$PM[3]],$DECODE_DATA['bin']);
          }
          # ﾙｰﾌﾟ用ﾃｷｽﾄ処理
          $loop_string = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|','',$loop_string);
        }

        if (($this->img_onry_flag == '1') or ($img_mode == '1')) {
          # 強制画像変換指定
          while (preg_match('/(\{|\{#|###|<!\-\-)(emj_a_|a|emj_am_|am|emj_v_|v)(\d{4})(\}|#\}|###|\-\->)/',$loop_string,$PM)) {
            if (($PM[2] == 'emj_a_') or ($PM[2] == 'a') or ($PM[2] == 'emj_am_') or ($PM[2] == 'am')) {
              if ($change_code == '') {
                $set_text      = $this->AU_NO_TO_IMG[$PM[3]];
                $set_text_mail = $this->AU_NO_TO_IMG_MAIL[$PM[3]];
              } else {
                $set_data      = @mb_convert_encoding($this->AU_NO_TO_IMG[$PM[3]],$out_code,'SJIS');
                $set_text_mail = @mb_convert_encoding($this->AU_NO_TO_IMG_MAIL[$PM[3]],$out_code,'SJIS');
              }
            } elseif (($PM[2] == 'emj_v_') or ($PM[2] == 'v')) {
              if ($change_code == '') {
                $set_text      = $this->SOFT_NO_TO_IMG[$PM[3]];
                $set_text_mail = $this->SOFT_NO_TO_IMG_MAIL[$PM[3]];
              } else {
                $set_text      = @mb_convert_encoding($this->SOFT_NO_TO_IMG[$PM[3]],$out_code,'SJIS');
                $set_text_mail = @mb_convert_encoding($this->SOFT_NO_TO_IMG_MAIL[$PM[3]],$out_code,'SJIS');
              }
            }
            $DECODE_DATA['web']  = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_text     ,$DECODE_DATA['web']);
            $DECODE_DATA['mail'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_text_mail,$DECODE_DATA['mail']);
            # ﾙｰﾌﾟ用ﾃｷｽﾄ処理
            $loop_string = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|','',$loop_string);
          }

        } else {
          # 未対応文字が存在する場合
          while (preg_match('/\?(emj_a_|a|emj_am_|am|emj_v_|v)(\d{4})\?/',$loop_string,$PM)) {
            if ($this->emoji_non == 0) {
              # 文字列で潰して表示
              $set_text = $this->emoji_chr;
            } elseif ($this->emoji_non == 1) {
              # 説明文で表示
              if (($PM[1] == 'emj_a_') or ($PM[1] == 'a') or ($PM[1] == 'emj_am_') or ($PM[1] == 'am')) {
                $set_text = $this->AU_NO_TO_NAME[$PM[2]];
              } elseif (($PM[1] == 'emj_v_') or ($PM[1] == 'v')) {
                $set_text = $this->SOFT_NO_TO_NAME[$PM[2]];
              }
            } elseif ($this->emoji_non == 2) {
              # 画像で表示
              if (($PM[1] == 'emj_a_') or ($PM[1] == 'a') or ($PM[1] == 'emj_am_') or ($PM[1] == 'am')) {
                if ($change_code == '') {
                  $set_text = $this->AU_NO_TO_IMG[$PM[2]];
                } else {
                  $set_text = @mb_convert_encoding($this->AU_NO_TO_IMG[$PM[2]],$out_code,'SJIS');
                }
              } elseif (($PM[1] == 'emj_v_') or ($PM[1] == 'v')) {
                if ($change_code == '') {
                  $set_text = $this->SOFT_NO_TO_IMG[$PM[2]];
                } else {
                  $set_text = @mb_convert_encoding($this->SOFT_NO_TO_IMG[$PM[2]],$out_code,'SJIS');
                }
              }
            }
            # Web表示用
            $DECODE_DATA['web']  = preg_replace('|\?'.$PM[1].$PM[2].'\?|',$set_text,$DECODE_DATA['web']);
            # ﾌｫｰﾑ表示用ﾃﾞｺｰﾄﾞ
            $DECODE_DATA['form'] = preg_replace('|\?'.$PM[1].$PM[2].'\?|','{'.$PM[1].$PM[2].'}',$DECODE_DATA['form']);
            # ﾒｰﾙ用ﾃﾞｺｰﾄﾞ
            $DECODE_DATA['mail'] = preg_replace('|\?'.$PM[1].$PM[2].'\?|',$this->emoji_chr,$DECODE_DATA['mail']);
            # ﾃｷｽﾄﾃﾞｺｰﾄﾞ
            $DECODE_DATA['text'] = preg_replace('|\?'.$PM[1].$PM[2].'\?|','{'.$PM[1].$PM[2].'}',$DECODE_DATA['text']);
            # ﾊﾞｲﾅﾘﾃﾞｺｰﾄﾞ
            $DECODE_DATA['bin']  = preg_replace('|\?'.$PM[1].$PM[2].'\?|','{'.$PM[1].$PM[2].'}',$DECODE_DATA['bin']);
            # ﾙｰﾌﾟ用ﾃｷｽﾄ処理
            $loop_string = preg_replace('|\?'.$PM[1].$PM[2].'\?|','',$loop_string);
          }
        }
      } elseif (preg_match('/^au$/i',$set_career)) {
        # auｷｬﾘｱに対しての絵文字ﾃﾞｺｰﾄﾞ
        while (preg_match('/(\{|\{#|###|<!\-\-)(emj_a_|a|emj_am_|am)(\d{4})(\}|#\}|###|\-\->)/',$loop_string,$PM)) {
          # Web表示用
          if (($this->img_onry_flag == '1') or ($img_mode == '1')) {
            # 強制画像変換指定
            if ($change_code == '') {
              $set_data = $this->AU_NO_TO_IMG[$PM[3]];
            } else {
              $set_data = @mb_convert_encoding($this->AU_NO_TO_IMG[$PM[3]],$out_code,'SJIS');
            }
            $DECODE_DATA['web']  = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_data,$DECODE_DATA['web']);
          } else {
            # 絵文字ｺｰﾄﾞ変換
            if ($career == '') {
              # ｱｸｾｽｷｬﾘｱ変換の場合
              if ($this->HARD_DATA['tg_flag'] == 'WIN') {
                $set_data = $this->AU_NO_TO_TXT_WIN[$PM[3]];
              } else {
                $set_data = $this->AU_NO_TO_TXT[$PM[3]];
              }
            } else {
              # 変換ｷｬﾘｱ指定の場合(WIN用に変換)
              $set_data = $this->AU_NO_TO_TXT_WIN[$PM[3]];
            }
            $DECODE_DATA['web']  = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_data,$DECODE_DATA['web']);
          }
          # ﾌｫｰﾑ表示用ﾃﾞｺｰﾄﾞ
          if ($oc == 'UTF-8') {
            $DECODE_DATA['form'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->AU_NO_TO_BIN_UTF8[$PM[3]],$DECODE_DATA['form']);
          } else {
            $DECODE_DATA['form'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->AU_NO_TO_BIN[$PM[3]],$DECODE_DATA['form']);
          }
          $DECODE_DATA['form'] = $this->form_htmlentities($DECODE_DATA['form']);
          # ﾒｰﾙ用ﾃﾞｺｰﾄﾞ
          if (($this->img_onry_flag == '1') or ($img_mode == '1')) {
            # 強制画像変換指定
            if ($change_code == '') {
              $set_data = $this->AU_NO_TO_IMG_MAIL[$PM[3]];
            } else {
              $set_data = @mb_convert_encoding($this->AU_NO_TO_IMG_MAIL[$PM[3]],$out_code,'SJIS');
            }
            $DECODE_DATA['mail'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_data,$DECODE_DATA['mail']);
          } else {
            # 絵文字ｺｰﾄﾞ変換
            $DECODE_DATA['mail'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->AU_NO_TO_BIN_MAIL[$PM[3]],$DECODE_DATA['mail']);
          }
          $DECODE_DATA['mail'] = preg_replace('|\stitle=\".+?\"|i','',$DECODE_DATA['mail']);
          $DECODE_DATA['mail'] = preg_replace('|\salt=\".+?\"|i'  ,'',$DECODE_DATA['mail']);
          # ﾃｷｽﾄﾃﾞｺｰﾄﾞ
          if ($this->HARD_DATA['tg_flag'] == 'WIN') {
            $DECODE_DATA['text'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->AU_NO_TO_TXT_WIN[$PM[3]],$DECODE_DATA['text']);
          } else {
            $DECODE_DATA['text'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->AU_NO_TO_TXT[$PM[3]],$DECODE_DATA['text']);
          }
          # ﾊﾞｲﾅﾘﾃﾞｺｰﾄﾞ
          if ($oc == 'UTF-8') {
            $DECODE_DATA['bin']  = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->AU_NO_TO_BIN_UTF8[$PM[3]],$DECODE_DATA['bin']);
          } else {
            $DECODE_DATA['bin']  = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->AU_NO_TO_BIN[$PM[3]],$DECODE_DATA['bin']);
          }
          # ﾙｰﾌﾟ用ﾃｷｽﾄ処理
          $loop_string = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|','',$loop_string);
        }

        if (($this->img_onry_flag == '1') or ($img_mode == '1')) {
          # 強制画像変換指定
          while (preg_match('/(\{|\{#|###|<!\-\-)(emj_d_|d|emj_v_|v)(\d{4})(\}|#\}|###|\-\->)/',$loop_string,$PM)) {
            if (($PM[2] == 'emj_d_') or ($PM[2] == 'd')) {
              if ($change_code == '') {
                $set_text      = $this->DOCOMO_NO_TO_IMG[$PM[3]];
                $set_text_mail = $this->DOCOMO_NO_TO_IMG_MAIL[$PM[3]];
              } else {
                $set_text      = @mb_convert_encoding($this->DOCOMO_NO_TO_IMG[$PM[3]],$out_code,'SJIS');
                $set_text_mail = @mb_convert_encoding($this->DOCOMO_NO_TO_IMG_MAIL[$PM[3]],$out_code,'SJIS');
              }
            } elseif (($PM[2] == 'emj_v_') or ($PM[2] == 'v')) {
              if ($change_code == '') {
                $set_text      = $this->SOFT_NO_TO_IMG[$PM[3]];
                $set_text_mail = $this->SOFT_NO_TO_IMG_MAIL[$PM[3]];
              } else {
                $set_text      = @mb_convert_encoding($this->SOFT_NO_TO_IMG[$PM[3]],$out_code,'SJIS');
                $set_text_mail = @mb_convert_encoding($this->SOFT_NO_TO_IMG_MAIL[$PM[3]],$out_code,'SJIS');
              }
            }
            $DECODE_DATA['web']  = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_text     ,$DECODE_DATA['web']);
            $DECODE_DATA['mail'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_text_mail,$DECODE_DATA['mail']);
            # ﾙｰﾌﾟ用ﾃｷｽﾄ処理
            $loop_string = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|','',$loop_string);
          }

        } else {
          # 未対応文字が存在する場合
          while (preg_match('/\?(emj_d_|d|emj_v_|v)(\d{4})\?/',$loop_string,$PM)) {
            if ($this->emoji_non == 0) {
              # 文字列で潰して表示
              $set_text = $this->emoji_chr;
            } elseif ($this->emoji_non == 1) {
              # 説明文で表示
              if (($PM[1] == 'emj_d_') or ($PM[1] == 'd')) {
                $set_text = $this->DOCOMO_NO_TO_NAME[$PM[2]];
              } elseif (($PM[1] == 'emj_v_') or ($PM[1] == 'v')) {
                $set_text = $this->SOFT_NO_TO_NAME[$PM[2]];
              }
            } elseif ($this->emoji_non == 2) {
              # 画像で表示
              if (($PM[1] == 'emj_d_') or ($PM[1] == 'd')) {
                if ($change_code == '') {
                  $set_text = $this->DOCOMO_NO_TO_IMG[$PM[2]];
                } else {
                  $set_text = @mb_convert_encoding($this->DOCOMO_NO_TO_IMG[$PM[2]],$out_code,'SJIS');
                }
              } elseif (($PM[1] == 'emj_v_') or ($PM[1] == 'v')) {
                if ($change_code == '') {
                  $set_text = $this->SOFT_NO_TO_IMG[$PM[2]];
                } else {
                  $set_text = @mb_convert_encoding($this->SOFT_NO_TO_IMG[$PM[2]],$out_code,'SJIS');
                }
              }
            }
            # Web表示用
            $DECODE_DATA['web']  = preg_replace('|\?'.$PM[1].$PM[2].'\?|',$set_text,$DECODE_DATA['web']);
            # ﾌｫｰﾑ表示用ﾃﾞｺｰﾄﾞ
            $DECODE_DATA['form'] = preg_replace('|\?'.$PM[1].$PM[2].'\?|','{'.$PM[1].$PM[2].'}',$DECODE_DATA['form']);
            # ﾒｰﾙ用ﾃﾞｺｰﾄﾞ
            $DECODE_DATA['mail'] = preg_replace('|\?'.$PM[1].$PM[2].'\?|',$this->emoji_chr,$DECODE_DATA['mail']);
            # ﾃｷｽﾄﾃﾞｺｰﾄﾞ
            $DECODE_DATA['text'] = preg_replace('|\?'.$PM[1].$PM[2].'\?|','{'.$PM[1].$PM[2].'}',$DECODE_DATA['text']);
            # ﾊﾞｲﾅﾘﾃﾞｺｰﾄﾞ
            $DECODE_DATA['bin']  = preg_replace('|\?'.$PM[1].$PM[2].'\?|','{'.$PM[1].$PM[2].'}',$DECODE_DATA['bin']);
            # ﾙｰﾌﾟ用ﾃｷｽﾄ処理
            $loop_string = preg_replace('|\?'.$PM[1].$PM[2].'\?|','',$loop_string);
          }
        }
      } elseif (preg_match('/^'.$this->softbank_name.'$/i',$set_career)) {
        # SoftBankｷｬﾘｱに対しての絵文字ﾃﾞｺｰﾄﾞ
        while (preg_match('/(\{|\{#|###|<!\-\-)(emj_v_|v)(\d{4})(\}|#\}|###|\-\->)/',$loop_string,$PM)) {
          # Web表示用
          if (($this->img_onry_flag == '1') or ($img_mode == '1')) {
            # 強制画像変換指定
            if ($change_code == '') {
              $set_text = $this->SOFT_NO_TO_IMG[$PM[3]];
            } else {
              $set_text = @mb_convert_encoding($this->SOFT_NO_TO_IMG[$PM[3]],$out_code,'SJIS');
            }
            $DECODE_DATA['web'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_text,$DECODE_DATA['web']);
          } else {
            # 絵文字ｺｰﾄﾞ変換
            $DECODE_DATA['web'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->SOFT_NO_TO_WEBCODE[$PM[3]],$DECODE_DATA['web']);
          }
          # ﾌｫｰﾑ表示用ﾃﾞｺｰﾄﾞ
          $DECODE_DATA['form'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->SOFT_NO_TO_WEBCODE[$PM[3]],$DECODE_DATA['form']);
          # ﾒｰﾙ用ﾃﾞｺｰﾄﾞ
          if (($this->img_onry_flag == '1') or ($img_mode == '1')) {
            # 強制画像変換指定
            if ($change_code == '') {
              $set_text = $this->SOFT_NO_TO_IMG_MAIL[$PM[3]];
            } else {
              $set_text = @mb_convert_encoding($this->SOFT_NO_TO_IMG_MAIL[$PM[3]],$out_code,'SJIS');
            }
            $DECODE_DATA['mail'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_text,$DECODE_DATA['mail']);
          } else {
            # 絵文字ｺｰﾄﾞ変換
            $DECODE_DATA['mail']       = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->SOFT3G_NO_TO_UTF8[$PM[3]],$DECODE_DATA['mail']);
            $DECODE_DATA['mail_plain'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->SOFT_NO_TO_WEBCODE[$PM[3]],$DECODE_DATA['mail']);
          }
          $DECODE_DATA['mail'] = preg_replace('|\stitle=\".+?\"|i','',$DECODE_DATA['mail']);
          $DECODE_DATA['mail'] = preg_replace('|\salt=\".+?\"|i'  ,'',$DECODE_DATA['mail']);
          # ﾃｷｽﾄﾃﾞｺｰﾄﾞ
          $DECODE_DATA['text'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->SOFT_NO_TO_WEBCODE[$PM[3]],$DECODE_DATA['text']);
          # ﾊﾞｲﾅﾘﾃﾞｺｰﾄﾞ
          $DECODE_DATA['bin']  = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$this->SOFT_NO_TO_WEBCODE[$PM[3]],$DECODE_DATA['bin']);
          # ﾙｰﾌﾟ用ﾃｷｽﾄ処理
          $loop_string = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|','',$loop_string);
        }

        if (($this->img_onry_flag == '1') or ($img_mode == '1')) {
          # 強制画像変換指定
          while (preg_match('/(\{|\{#|###|<!\-\-)(emj_d_|d|emj_a_|a|emj_am_|am)(\d{4})(\}|#\}|###|\-\->)/',$loop_string,$PM)) {
            if (($PM[2] == 'emj_d_') or ($PM[2] == 'd')) {
              if ($change_code == '') {
                $set_text      = $this->DOCOMO_NO_TO_IMG[$PM[3]];
                $set_text_mail = $this->DOCOMO_NO_TO_IMG_MAIL[$PM[3]];
              } else {
                $set_text      = @mb_convert_encoding($this->DOCOMO_NO_TO_IMG[$PM[3]],$out_code,'SJIS');
                $set_text_mail = @mb_convert_encoding($this->DOCOMO_NO_TO_IMG_MAIL[$PM[3]],$out_code,'SJIS');
              }
            } elseif (($PM[2] == 'emj_a_') or ($PM[2] == 'a') or ($PM[2] == 'emj_am_') or ($PM[2] == 'am')) {
              if ($change_code == '') {
                $set_text      = $this->AU_NO_TO_IMG[$PM[3]];
                $set_text_mail = $this->AU_NO_TO_IMG_MAIL[$PM[3]];
              } else {
                $set_text      = @mb_convert_encoding($this->AU_NO_TO_IMG[$PM[3]],$out_code,'SJIS');
                $set_text_mail = @mb_convert_encoding($this->AU_NO_TO_IMG_MAIL[$PM[3]],$out_code,'SJIS');
              }
            }
            $DECODE_DATA['web']        = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_text     ,$DECODE_DATA['web']);
            $DECODE_DATA['mail']       = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_text_mail,$DECODE_DATA['mail']);
            $DECODE_DATA['mail_plain'] = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$set_text_mail,$DECODE_DATA['mail_plain']);
            # ﾙｰﾌﾟ用ﾃｷｽﾄ処理
            $loop_string = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|','',$loop_string);
          }

        } else {
          # 未対応文字が存在する場合
          while (preg_match('/\?(emj_d_|d|emj_a_|a|emj_am_|am)(\d{4})\?/',$loop_string,$PM)) {
            if ($this->emoji_non == 0) {
              # 文字列で潰して表示
              $set_text = $this->emoji_chr;
            } elseif ($this->emoji_non == 1) {
              # 説明文で表示
              if (($PM[1] == 'emj_d_') or ($PM[1] == 'd')) {
                $set_text = $this->DOCOMO_NO_TO_NAME[$PM[2]];
              } elseif (($PM[1] == 'emj_a_') or ($PM[1] == 'a') or ($PM[1] == 'emj_am_') or ($PM[1] == 'am')) {
                $set_text = $this->AU_NO_TO_NAME[$PM[2]];
              }
            } elseif ($this->emoji_non == 2) {
              # 画像で表示
              if (($PM[1] == 'emj_d_') or ($PM[1] == 'd')) {
                if ($change_code == '') {
                  $set_text = $this->DOCOMO_NO_TO_IMG[$PM[2]];
                } else {
                  $set_text = @mb_convert_encoding($this->DOCOMO_NO_TO_IMG[$PM[2]],$out_code,'SJIS');
                }
              } elseif (($PM[1] == 'emj_a_') or ($PM[1] == 'a') or ($PM[1] == 'emj_am_') or ($PM[1] == 'am')) {
                if ($change_code == '') {
                  $set_text = $this->AU_NO_TO_IMG[$PM[2]];
                } else {
                  $set_text = @mb_convert_encoding($this->AU_NO_TO_IMG[$PM[2]],$out_code,'SJIS');
                }
              }
            }
            # Web表示用
            $DECODE_DATA['web']  = preg_replace('|\?'.$PM[1].$PM[2].'\?|',$set_text,$DECODE_DATA['web']);
            # ﾌｫｰﾑ表示用ﾃﾞｺｰﾄﾞ
            $DECODE_DATA['form'] = preg_replace('|\?'.$PM[1].$PM[2].'\?|','{'.$PM[1].$PM[2].'}',$DECODE_DATA['form']);
            # ﾒｰﾙ用ﾃﾞｺｰﾄﾞ
            $DECODE_DATA['mail']       = preg_replace('|\?'.$PM[1].$PM[2].'\?|',$this->emoji_chr,$DECODE_DATA['mail']);
            $DECODE_DATA['mail_plain'] = preg_replace('|\?'.$PM[1].$PM[2].'\?|',$this->emoji_chr,$DECODE_DATA['mail_plain']);
            # ﾃｷｽﾄﾃﾞｺｰﾄﾞ
            $DECODE_DATA['text'] = preg_replace('|\?'.$PM[1].$PM[2].'\?|','{'.$PM[1].$PM[2].'}',$DECODE_DATA['text']);
            # ﾊﾞｲﾅﾘﾃﾞｺｰﾄﾞ
            $DECODE_DATA['bin']  = preg_replace('|\?'.$PM[1].$PM[2].'\?|','{'.$PM[1].$PM[2].'}',$DECODE_DATA['bin']);
            # ﾙｰﾌﾟ用ﾃｷｽﾄ処理
            $loop_string = preg_replace('|\?'.$PM[1].$PM[2].'\?|','',$loop_string);
          }
        }
      }
    } else {
      $DECODE_DATA['web']        = '';
      $DECODE_DATA['form']       = '';
      $DECODE_DATA['mail']       = '';
      $DECODE_DATA['mail_plain'] = '';
      $DECODE_DATA['text']       = '';
      $DECODE_DATA['bin']        = '';
    }
    return $DECODE_DATA;
  }

  # 絵文字ｴﾝｺｰﾄﾞ絵文字変換 ////////////////////////////////////////////////////
  # 絵文字ｴﾝｺｰﾄﾞされた文字列をｱｸｾｽｷｬﾘｱ、或いは指定のｷｬﾘｱの絵文字に変換します。
  # [引渡し値]
  # 　$textstr  : 変換対象文字列
  # 　$career   : ｷｬﾘｱ指定(指定無い場合ｱｸｾｽｷｬﾘｱ,'DoCoMo','au','SoftBank'or'Vodafone')
  # [返り値]
  # 　$textstr  : 変換後文字列
  #////////////////////////////////////////////////////////////////////////////
  function _emj_enc_change($textstr,$career='') {
    if (isset($textstr)) {
      # 変換先ｷｬﾘｱ設定
      if ($career == '') {
        # 変換先ｷｬﾘｱ指定無し(ｱｸｾｽｷｬﾘｱ変換)
        $career = $this->HARD_DATA['hard'];
      } else {
        # 変換先ｷｬﾘｱ指定有り(指定ｷｬﾘｱ変換)
      }
      if (!preg_match('/^pc$/i',$career)) {
        # PC以外変換
        $loop_text = $textstr;
        # ｴﾝｺｰﾄﾞﾀｲﾌﾟ(ﾃﾞﾘﾐﾀ)指定
        $left_delimiter  = $this->DELIMITER[$this->enc_type]['left'];
        $right_delimiter = $this->DELIMITER[$this->enc_type]['right'];
        $etype_top       = $this->DELIMITER[$this->enc_type]['a'];
        $etype_sec       = $this->DELIMITER[$this->enc_type]['b'];

        while (preg_match('/(\{|\{#|###|<!\-\-)(emj_d_|d|emj_a_|a|emj_am_|am|emj_v_|v)(\d{4})(\}|#\}|###|\-\->)/',$loop_text,$PM)) {
          $check_flag = False;
          if (($PM[2] == 'emj_d_') or ($PM[2] == 'd')) {
            # DoCoMo絵文字変換
            if (preg_match('/^docomo$/i',$career)) {
              # DoCoMo変換
              $check_flag = True;
            } elseif (preg_match('/^au$/i',$career)) {
              # au変換
              if (isset($this->DOCOMO_TO_AU[$PM[3]])) {
                if (preg_match('/^[0-9]{4}$/',$this->DOCOMO_TO_AU[$PM[3]])) {
                  $textstr = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$PM[1].$etype_top.'a'.$etype_sec.$this->DOCOMO_TO_AU[$PM[3]].$PM[4],$textstr);
                  $check_flag = True;
                }
              }
            } elseif (preg_match('/'.$this->softbank_name.'/i',$career)) {
              # SoftBank変換
              if (isset($this->DOCOMO_TO_SOFT[$PM[3]])) {
                if (preg_match('/^[0-9]{4}$/',$this->DOCOMO_TO_SOFT[$PM[3]])) {
                  $textstr = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$PM[1].$etype_top.'v'.$etype_sec.$this->DOCOMO_TO_SOFT[$PM[3]].$PM[4],$textstr);
                  $check_flag = True;
                }
              }
            }
            if ($check_flag == False) {
              # 対応絵文字が無い場合
              $textstr = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|','?'.$PM[2].$PM[3].'?',$textstr);
            }
          } elseif (($PM[2] == 'emj_a_') or ($PM[2] == 'a') or ($PM[2] == 'emj_am_') or ($PM[2] == 'am')) {
            # au絵文字変換
            if (preg_match('/^docomo$/i',$career)) {
              # DoCoMo変換
              if (isset($this->AU_TO_DOCOMO[$PM[3]])) {
                if (preg_match('/^[0-9]{4}$/',$this->AU_TO_DOCOMO[$PM[3]])) {
                  $textstr = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$PM[1].$etype_top.'d'.$etype_sec.$this->AU_TO_DOCOMO[$PM[3]].$PM[4],$textstr);
                  $check_flag = True;
                }
              }
            } elseif (preg_match('/^au$/i',$career)) {
              # au変換
              $check_flag = True;
            } elseif (preg_match('/'.$this->softbank_name.'/i',$career)) {
              # SoftBank変換
              if (isset($this->AU_TO_SOFT[$PM[3]])) {
                if (preg_match('/^[0-9]{4}$/',$this->AU_TO_SOFT[$PM[3]])) {
                  $textstr = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$PM[1].$etype_top.'v'.$etype_sec.$this->AU_TO_SOFT[$PM[3]].$PM[4],$textstr);
                  $check_flag = True;
                }
              }
            }
            if ($check_flag == False) {
              # 対応絵文字が無い場合
              $textstr = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|','?'.$PM[2].$PM[3].'?',$textstr);
            }
          } elseif (($PM[2] == 'emj_v_') or ($PM[2] == 'v')) {
            # SoftBank絵文字変換
            if (preg_match('/^docomo$/i',$career)) {
              # DoCoMoｴﾝｺｰﾄﾞ変換
              if (isset($this->SOFT_TO_DOCOMO[$PM[3]])) {
                if (preg_match('/^[0-9]{4}$/',$this->SOFT_TO_DOCOMO[$PM[3]])) {
                  $textstr = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$PM[1].$etype_top.'d'.$etype_sec.$this->SOFT_TO_DOCOMO[$PM[3]].$PM[4],$textstr);
                  $check_flag = True;
                }
              }
            } elseif (preg_match('/^au$/i',$career)) {
              # au変換
              if (isset($this->SOFT_TO_AU[$PM[3]])) {
                if (preg_match('/^[0-9]{4}$/',$this->SOFT_TO_AU[$PM[3]])) {
                  $textstr = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|',$PM[1].$etype_top.'a'.$etype_sec.$this->SOFT_TO_AU[$PM[3]].$PM[4],$textstr);
                  $check_flag = True;
                }
              }
            } elseif (preg_match('/'.$this->softbank_name.'/i',$career)) {
              # SoftBank変換
              $check_flag = True;
            }
            if ($check_flag == False) {
              # 対応絵文字が無い場合
              $textstr = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|','?'.$PM[2].$PM[3].'?',$textstr);
            }
          }
          $loop_text = preg_replace('|'.$PM[1].$PM[2].$PM[3].$PM[4].'|','',$loop_text);
        }

      }

    } else {
      $textstr = '';
    }
    return $textstr;
  }

  # SoftBank 3G UTF-8ｺｰﾄﾞ対応 /////////////////////////////////////////////////
  # 絵文字ｴﾝｺｰﾄﾞされた文字列をｱｸｾｽｷｬﾘｱ、或いは指定のｷｬﾘｱの絵文字に変換します。
  # [引渡し値]
  # 　$textstr     : 変換対象文字列
  # 　$change_mode : 強制処理指定(1:強制変換処理)
  # [返り値]
  # 　$textstr     : 変換後文字列
  #////////////////////////////////////////////////////////////////////////////
  function v3_utf8_sjis($textstr,$change_mode='') {
    if (($this->HARD_DATA['hard'] == $this->softbank_name) and ($this->HARD_DATA['tg_flag'] == '3G') and (($change_mode == '1') or (mb_detect_encoding($textstr,$this->ENCODINGLIST[$this->chr_code]) == 'UTF-8'))) {
      # SoftBank絵文字ｴﾝｺｰﾄﾞ
      $textstr = $this->_replace_v_emoji_utf8($textstr);
      # 文字ｺｰﾄﾞ変換
      $textstr = @mb_convert_encoding($textstr,$this->chg_code_sjis,'UTF-8');
      # Vofadone絵文字ﾃﾞｺｰﾄﾞ
      $TEXTSTR = $this->emj_decode($textstr);
      $textstr = $TEXTSTR['web'];
    }
    return $textstr;
  }

  # SoftBank 3G UTF-8ｺｰﾄﾞ変換(内部処理用) /////////////////////////////////////
  # SoftBank絵文字(UTF-8ｺｰﾄﾞ)を絵文字ｴﾝｺｰﾄﾞします。
  # [引渡し値]
  # 　$textstr : 変換対象文字列
  # [返り値]
  # 　$textstr : 変換後文字列
  #////////////////////////////////////////////////////////////////////////////
  function _replace_v_emoji_utf8($textstr) {
    # SoftBank絵文字ｴﾝｺｰﾄﾞ
    if ($this->enc_type == '') { $this->enc_type = '1'; }
    $ptn   = '';
    $NEWDT = array();
    $OLDDT = array();
    $OLDDT = explode("\n", $textstr);
    foreach ($OLDDT as $str) {
      if (preg_match('/\xEE[\x80\x81\x84\x85\x88\x89\x8C\x8D\x90\x91\x94][\x80-\xBF]/',$str)) {
        while (preg_match('/\xEE([\x80\x81\x84\x85\x88\x89\x8C\x8D\x90\x91\x94][\x80-\xBF])/',$str,$PM)) {
          $DEC = unpack('n1int', $PM[1]);
          if (isset($this->SOFT3G_DEC_TO_NO[$DEC['int']])) {
            $str = preg_replace('|\xEE'.$PM[1].'|',$this->DELIMITER[$this->enc_type]['left'].$this->DELIMITER[$this->enc_type]['a'].'v'.$this->DELIMITER[$this->enc_type]['b'].$this->SOFT3G_DEC_TO_NO[$DEC['int']].$this->DELIMITER[$this->enc_type]['right'], $str);
          } else {
            $str = preg_replace('|\xEE'.$PM[1].'|',$this->emoji_chr, $str);
          }
        }
      }
      $NEWDT[] = $str;
    }
    $news = join("\n", $NEWDT);
    return $news;
  }

  # DoCoMo絵文字ﾊﾞｲﾅﾘｺｰﾄﾞ変換(内部処理用) /////////////////////////////////////
  # DoCoMo絵文字(SJISﾊﾞｲﾅﾘｺｰﾄﾞ)を絵文字ｴﾝｺｰﾄﾞ又はSJISﾃｷｽﾄ変換します。
  # [引渡し値]
  # 　$str  : 変換対象文字列
  # 　$mode : 処理指定(指定なし:ｺｰﾄﾞ変換,1:削除,2:ｶｳﾝﾄ,3:下駄変換)
  # [返り値]
  # 　$news : 処理後文字列(ｶｳﾝﾄﾓｰﾄﾞの場合はｶｳﾝﾄ数)
  #////////////////////////////////////////////////////////////////////////////
  function _replace_d_emoji($str, $mode = '') {
    if ($this->enc_type == '') { $this->enc_type = '1'; }
    $no    = 0;
    $news  = '';
    $OLDDT = array();
    $NEWDT = array();
    $OLDDT = explode("\n", $str);
    foreach ($OLDDT as $str) {
      $old = $str;
      $new = '';
      if (preg_match('/[\xF8\xF9]/', $old)) {
        while (1) {
          $RES = array();
          if (preg_match('/^[\xF8\xF9][\x40-\xFC]/', $old , $RES)) {
            $old = preg_replace('/^[\xF8\xF9][\x40-\xFC]/', '', $old);
            if ($mode == '') {
              # 絵文字置換え
              $bin = unpack('n1int', $RES[0]);
              if (($this->enc_type >= 1) and ($this->enc_type <= '8')) {
                if (isset($this->DOCOMO_SJIS10_TO_NO[$bin["int"]])) {
                  $new .= $this->DELIMITER[$this->enc_type]['left'].$this->DELIMITER[$this->enc_type]['a'].'d'.$this->DELIMITER[$this->enc_type]['b'].$this->DOCOMO_SJIS10_TO_NO[$bin["int"]].$this->DELIMITER[$this->enc_type]['right'];
                } else {
                  $new .= $this->emoji_chr;
                }
              } else {
                $new .= '&#'.$bin["int"].';';
              }
            } elseif ($mode == 1) {
              # 絵文字削除
            } elseif ($mode == 2) {
              # 絵文字ｶｳﾝﾄ
              $no++;
            } elseif ($mode == 3) {
              # 絵文字下駄変換
              $new .= $this->geta_str;
            }
          } elseif (preg_match('/^[\x81-\x9F\xE0-\xF7\xFA-\xFC][\x40-\x7E\x80-\xFC]/', $old, $RES)) {
            $old = preg_replace('/^[\x81-\x9F\xE0-\xF7\xFA-\xFC][\x40-\x7E\x80-\xFC]/', '', $old);
            $new .= $RES[0];
          } elseif (preg_match('/^./', $old, $RES)) {
            $old = preg_replace('/^./', '', $old);
            $new .= $RES[0];
          } else {
            break;
          }
        }
      } else {
        $new = $old;
      }
      $NEWDT[] = $new;
    }
    if ($mode == 2) {
      $news = $no;
    } else {
      $news = join("\n", $NEWDT);
    }
    return $news;
  }

  # DoCoMo絵文字UTF-8ﾊﾞｲﾅﾘｺｰﾄﾞ変換(内部処理用) ///////////////////////////////
  # DoCoMo絵文字(UTF-8ﾊﾞｲﾅﾘｺｰﾄﾞ)を絵文字ｴﾝｺｰﾄﾞ又はSJISﾃｷｽﾄ変換します。
  # [引渡し値]
  # 　$str  : 変換対象文字列
  # 　$mode : 処理指定(指定なし:ｺｰﾄﾞ変換,1:削除,2:ｶｳﾝﾄ,3:下駄変換)
  # [返り値]
  # 　$news : 処理後文字列(ｶｳﾝﾄﾓｰﾄﾞの場合はｶｳﾝﾄ数)
  #////////////////////////////////////////////////////////////////////////////
  function _replace_d_emoji_utf8($str, $mode = '') {
    if ($this->enc_type == '') { $this->enc_type = '1'; }
    $no    = 0;
    $news  = '';
    $OLDDT = array();
    $NEWDT = array();
    $OLDDT = explode("\n", $str);
    foreach ($OLDDT as $str) {
      $old = $str;
      $new = '';
      if (preg_match('/\xEE([\x98-\x9D][\x80-\xBF])/', $old)) {
        while (1) {
          $RES = array();
          if (preg_match('/^\xEE([\x98-\x9D][\x80-\xBF])/', $old , $RES)) {
            $old = preg_replace('/^\xEE[\x98-\x9D][\x80-\xBF]/', '', $old);
            if ($mode == '') {
              # 絵文字置換え
              $bin = unpack('n1int', $RES[1]);
              if (($this->enc_type >= 1) and ($this->enc_type <= '8')) {
                if (isset($this->DOCOMO_UTF8_TO_NO[$bin["int"]])) {
                  $new .= $this->DELIMITER[$this->enc_type]['left'].$this->DELIMITER[$this->enc_type]['a'].'d'.$this->DELIMITER[$this->enc_type]['b'].$this->DOCOMO_UTF8_TO_NO[$bin["int"]].$this->DELIMITER[$this->enc_type]['right'];
                } else {
                  $new .= $this->emoji_chr;
                }
              } else {
                $new .= '&#'.$bin["int"].';';
              }
            } elseif ($mode == 1) {
              # 絵文字削除
            } elseif ($mode == 2) {
              # 絵文字ｶｳﾝﾄ
              $no++;
            } elseif ($mode == 3) {
              # 絵文字下駄変換
              $new .= $this->geta_str;
            }
          } elseif (preg_match('/^./', $old, $RES)) {
            $old = preg_replace('/^./', '', $old);
            $new .= $RES[0];
          } else {
            break;
          }
        }
      } else {
        $new = $old;
      }
      $NEWDT[] = $new;
    }
    if ($mode == 2) {
      $news = $no;
    } else {
      $news = join("\n", $NEWDT);
    }
    return $news;
  }

  # DoCoMo絵文字ﾃｷｽﾄｺｰﾄﾞ変換(内部処理用) ///////////////////////////////
  # DoCoMo絵文字(ﾃｷｽﾄｺｰﾄﾞ)を絵文字ｴﾝｺｰﾄﾞします。
  # [引渡し値]
  # 　$str  : 変換対象文字列
  # 　$mode : 処理指定(指定なし:ｺｰﾄﾞ変換,1:削除,2:ｶｳﾝﾄ,3:下駄変換)
  # [返り値]
  # 　$news : 処理後文字列(ｶｳﾝﾄﾓｰﾄﾞの場合はｶｳﾝﾄ数)
  #////////////////////////////////////////////////////////////////////////////
  function _replace_d_emoji_text($str, $mode = '') {

    if ($this->enc_type == '') { $this->enc_type = '1'; }

    $no    = 0;
    $news  = '';
    $OLDDT = array();
    $NEWDT = array();
    $OLDDT = explode("\n", $str);
    foreach ($OLDDT as $str) {
      $old = $str;
      $new = '';
      if (preg_match('/&#(x*)([0-9a-fA-F]+?);/', $old)) {
        while (1) {
          $RES = array();
          if (preg_match('/^&#(x*)([0-9a-fA-F]+?);/',$old ,$RES)) {
            $eflag = False;
            if ($mode == '') {
              # 絵文字置換え
              if (($this->enc_type >= 1) and ($this->enc_type <= '8')) {
                if (isset($this->{'ENC_TYPE'.$this->enc_type}[$RES[2]])) {
                  $new  .= $this->{'ENC_TYPE'.$this->enc_type}[$RES[2]];
                  $eflag = True;
                } else {
                  $new .= $this->emoji_chr;
                }
              } else {
                $new .= '&#'.$RES[1].$RES[2].';';
              }
            } elseif ($mode == 1) {
              # 絵文字削除
            } elseif ($mode == 2) {
              # 絵文字ｶｳﾝﾄ
              $no++;
            } elseif ($mode == 3) {
              # 絵文字下駄変換
              $new .= $this->geta_str;
            }
            $old = preg_replace('/^&#'.$RES[1].$RES[2].';/','',$old);
          } elseif (preg_match('/^./',$old,$RES)) {
            $old = preg_replace('/^./','',$old);
            $new .= $RES[0];
          } else {
            break;
          }
        }
      } else {
        $new = $old;
      }
      $NEWDT[] = $new;
    }
    if ($mode == 2) {
      $news = $no;
    } else {
      $news = join("\n", $NEWDT);
    }
    return $news;
  }

  # au絵文字ﾊﾞｲﾅﾘｺｰﾄﾞ変換(内部処理用) /////////////////////////////////////////
  # au絵文字(SJISﾊﾞｲﾅﾘｺｰﾄﾞ)を絵文字ｴﾝｺｰﾄﾞ又はSJISﾃｷｽﾄ変換します。
  # [引渡し値]
  # 　$str  : 変換対象文字列
  # 　$mode : 処理指定(指定なし:ｺｰﾄﾞ変換,1:削除,2:ｶｳﾝﾄ,3:下駄変換)
  # [返り値]
  # 　$news : 処理後文字列(ｶｳﾝﾄﾓｰﾄﾞの場合はｶｳﾝﾄ数)
  #////////////////////////////////////////////////////////////////////////////
  function _replace_a_emoji($str, $mode = '') {

    if ($this->enc_type == '') { $this->enc_type = '1'; }

    $no    = 0;
    $news  = '';
    $OLDDT = array();
    $NEWDT = array();
    $OLDDT = explode("\n", $str);
    foreach ($OLDDT as $str) {
      $old = $str;
      $new = '';
      if (preg_match('/[\xEB\xEC\xED\xEE\xF3\xF4\xF6\xF7]/', $old)) {
        while (1) {
          $RES = array();
          if (preg_match('/^[\xEB\xEC\xED\xEE\xF3\xF4\xF6\xF7][\x40-\xFC]/', $old , $RES)) {
            $old = preg_replace('/^[\xEB\xEC\xED\xEE\xF3\xF4\xF6\xF7][\x40-\xFC]/', '', $old);
            if ($mode == '') {
              # 絵文字置換え
              $bin = unpack('n1int', $RES[0]);
              if (($this->enc_type >= 1) and ($this->enc_type <= '8')) {
                if (isset($this->AU_SJIS10_TO_NO[$bin["int"]])) {
                  $new .= $this->DELIMITER[$this->enc_type]['left'].$this->DELIMITER[$this->enc_type]['a'].'a'.$this->DELIMITER[$this->enc_type]['b'].$this->AU_SJIS10_TO_NO[$bin["int"]].$this->DELIMITER[$this->enc_type]['right'];
                } else {
                  $new .= $this->emoji_chr;
                }
              } else {
                $new .= '&#'.$bin["int"].';';
              }
            } elseif ($mode == 1) {
              # 絵文字削除
            } elseif ($mode == 2) {
              # 絵文字ｶｳﾝﾄ
              $no++;
            } elseif ($mode == 3) {
              # 絵文字下駄変換
              $new .= $this->geta_str;
            }

          } elseif (preg_match('/^[\x81-\x9F\xE0-\xF7\xFA-\xFC][\x40-\x7E\x80-\xFC]/', $old, $RES)) {
            $old = preg_replace('/^[\x81-\x9F\xE0-\xF7\xFA-\xFC][\x40-\x7E\x80-\xFC]/', '', $old);
            $new .= $RES[0];
          } elseif (preg_match('/^./', $old, $RES)) {
            $old = preg_replace('/^./', '', $old);
            $new .= $RES[0];
          } else {
            break;
          }
        }
      } else {
        $new = $old;
      }
      $NEWDT[] = $new;
    }
    if ($mode == 2) {
      $news = $no;
    } else {
      $news = join("\n", $NEWDT);
    }
    return $news;
  }

  # au絵文字UTF-8ﾊﾞｲﾅﾘｺｰﾄﾞ変換(内部処理用) ////////////////////////////////////
  # au絵文字(UTF-8ﾊﾞｲﾅﾘｺｰﾄﾞ)を絵文字ｴﾝｺｰﾄﾞ又はSJISﾃｷｽﾄ変換します。
  # [引渡し値]
  # 　$str  : 変換対象文字列
  # 　$mode : 処理指定(指定なし:ｺｰﾄﾞ変換,1:削除,2:ｶｳﾝﾄ,3:下駄変換)
  # [返り値]
  # 　$news : 処理後文字列(ｶｳﾝﾄﾓｰﾄﾞの場合はｶｳﾝﾄ数)
  #////////////////////////////////////////////////////////////////////////////
  function _replace_a_emoji_utf8($str, $mode = '') {

    if ($this->enc_type == '') { $this->enc_type = '1'; }

    $no    = 0;
    $news  = '';
    $OLDDT = array();
    $NEWDT = array();
    $OLDDT = explode("\n", $str);
    foreach ($OLDDT as $str) {
      $old = $str;
      $new = '';
      if (preg_match('/\xEE[\xB1-\xB3\xB5\xB6\xBD-\xBF][\x80-\xBF]/',$old) or preg_match('/\xEF[\x81\x82\x83][\x80-\xBF]/',$old)) {
        while (1) {
          $RES = array();
          if (preg_match('/^\xEE([\xB1-\xB3\xB5\xB6\xBD-\xBF][\x80-\xBF])/',$old,$RES) or preg_match('/^\xEF([\x81\x82\x83][\x80-\xBF])/',$old,$RES)) {
            $old = preg_replace('/^'.$RES[0].'/','',$old);
            if ($mode == '') {
              # 絵文字置換え
              $bin = unpack('n1int', $RES[1]);
              if (($this->enc_type >= 1) and ($this->enc_type <= '8')) {
                if (isset($this->AU_UTF8_TO_NO[$bin["int"]])) {
                  $new .= $this->DELIMITER[$this->enc_type]['left'].$this->DELIMITER[$this->enc_type]['a'].'a'.$this->DELIMITER[$this->enc_type]['b'].$this->AU_UTF8_TO_NO[$bin["int"]].$this->DELIMITER[$this->enc_type]['right'];
                } else {
                  $new .= $this->emoji_chr;
                }
              } else {
                $new .= '&#'.$bin["int"].';';
              }
            } elseif ($mode == 1) {
              # 絵文字削除
            } elseif ($mode == 2) {
              # 絵文字ｶｳﾝﾄ
              $no++;
            } elseif ($mode == 3) {
              # 絵文字下駄変換
              $new .= $this->geta_str;
            }
          } elseif (preg_match('/^./', $old, $RES)) {
            $old = preg_replace('/^./', '', $old);
            $new .= $RES[0];
          } else {
            break;
          }
        }
      } else {
        $new = $old;
      }
      $NEWDT[] = $new;
    }
    if ($mode == 2) {
      $news = $no;
    } else {
      $news = join("\n", $NEWDT);
    }
    return $news;
  }

  # au絵文字ﾊﾞｲﾅﾘｺｰﾄﾞ変換(内部処理用ｻﾌﾞ処理) //////////////////////////////////
  # au絵文字(SJISﾊﾞｲﾅﾘｺｰﾄﾞ)を絵文字ｴﾝｺｰﾄﾞ又はSJISﾃｷｽﾄ変換します。
  # [引渡し値]
  # 　$str  : 変換対象文字列
  # 　$mode : 処理指定(指定なし:ｺｰﾄﾞ変換,1:削除,2:ｶｳﾝﾄ,3:下駄変換)
  # [返り値]
  # 　$news : 処理後文字列(ｶｳﾝﾄﾓｰﾄﾞの場合はｶｳﾝﾄ数)
  #////////////////////////////////////////////////////////////////////////////
  function _replace_a_emoji_sub($str, $mode = '') {
    $no = 0;
    $news = '';
    $OLDDT = array();
    $NEWDT = array();
    $OLDDT = explode("\n", $str);
    foreach ($OLDDT as $str) {
      $old = $str;
      $new = '';
      if (preg_match('/[\xEB\xEC\xED\xEE\xF3\xF4\xF6\xF7]/', $old)) {
        while (1) {
          $RES = array();
          if (preg_match('/^[\xEB\xEC\xED\xEE\xF3\xF4\xF6\xF7][\x40-\xFC]/', $old , $RES)) {
            $old = preg_replace('/^[\xEB\xEC\xED\xEE\xF3\xF4\xF6\xF7][\x40-\xFC]/', '', $old);

            if ($mode == '') {
              # 絵文字置換え
              $bin = unpack('n1int', $RES[0]);
              $new .= '&#'.$bin["int"].'_sub;';
            } elseif ($mode == 1) {
              # 絵文字削除
            } elseif ($mode == 2) {
              # 絵文字ｶｳﾝﾄ
              $no++;
            } elseif ($mode == 3) {
              # 絵文字下駄変換
              $new .= $this->geta_str;
            }

          } elseif (preg_match('/^[\x81-\x9F\xE0-\xF7\xFA-\xFC][\x40-\x7E\x80-\xFC]/', $old, $RES)) {
            $old = preg_replace('/^[\x81-\x9F\xE0-\xF7\xFA-\xFC][\x40-\x7E\x80-\xFC]/', '', $old);
            $new .= $RES[0];
          } elseif (preg_match('/^./', $old, $RES)) {
            $old = preg_replace('/^./', '', $old);
            $new .= $RES[0];
          } else {
            break;
          }
        }
      } else {
        $new = $old;
      }
      $NEWDT[] = $new;
    }
    if ($mode == 2) {
      $news = $no;
    } else {
      $news = join("\n", $NEWDT);
    }
    return $news;
  }

  # SoftBank絵文字ﾊﾞｲﾅﾘｺｰﾄﾞ変換(内部処理用) ///////////////////////////////////
  # SoftBank絵文字(SJISWebｺｰﾄﾞ)を絵文字ｴﾝｺｰﾄﾞ変換します。
  # [引渡し値]
  # 　$str  : 変換対象文字列
  # 　$mode : 処理指定(指定なし:ｺｰﾄﾞ変換,1:削除,2:ｶｳﾝﾄ,3:下駄変換)
  # [返り値]
  # 　$news : 処理後文字列(ｶｳﾝﾄﾓｰﾄﾞの場合はｶｳﾝﾄ数)
  #////////////////////////////////////////////////////////////////////////////
  function _replace_v_emoji($str, $mode = '') {
    if ($this->enc_type == '') { $this->enc_type = '1'; }
    $str .= chr(0x0F);
    # 絵文字第一ﾊﾞｲﾄ展開
    while (preg_match('/(\x1B\$[GEFOPQ])([\x21-\x7A])([\x21-\x7A]+)(\x0F)/', $str)) {
      $str = preg_replace('/(\x1B\$[GEFOPQ])([\x21-\x7A])([\x21-\x7A]+)(\x0F)/', '\\1\\2\\4\\1\\3\\4', $str);
    }
    # 絵文字置換え
    while (preg_match('/(\x1B\$[GEFOPQ][\x21-\x7A]\x0F)/', $str, $PM)) {
      $pms = quotemeta($PM[1]);
      if ($mode == '') {
        # 絵文字置換え
        $str = preg_replace('|'.$pms.'|', $this->DELIMITER[$this->enc_type]['left'].$this->DELIMITER[$this->enc_type]['a'].'v'.$this->DELIMITER[$this->enc_type]['b'].$this->SOFT_WEBCODE_TO_NO[$PM[1]].$this->DELIMITER[$this->enc_type]['right'], $str);
      } elseif ($mode == 1) {
        # 絵文字削除
        $str = preg_replace('|'.$pms.'|', '', $str);
      } elseif ($mode == 2) {
        # 絵文字ｶｳﾝﾄ
        $no++;
      } elseif ($mode == 3) {
        # 絵文字下駄変換
        $str = preg_replace('|'.$pms.'|', $this->geta_str, $str);
      }
    }
    # SI消去
    $str = preg_replace('/\x0F$/', '', $str);
    if ($mode == 2) { $str = $no; }
    return $str;
  }

  # ﾌｫｰﾑ処理用 ////////////////////////////////////////////////////////////////
  # ﾌｫｰﾑで表示する際のｴﾝﾃｨﾃｨ処理を行います。
  # [引渡し値]
  # 　$html : ｴﾝﾃｨﾃｨ対象文字列
  # [返り値]
  # 　$html : ｴﾝﾃｨﾃｨ処理後文字列
  #////////////////////////////////////////////////////////////////////////////
  function form_htmlentities($html) {
    $html = preg_replace('/</','&lt;',$html);
    $html = preg_replace('/>/','&gt;',$html);
    $html = preg_replace('/"/','&#34;',$html);
    $html = preg_replace("/'/",'&#39;',$html);
    return $html;
  }

  # ﾒｰﾙ送信(mail関数送信) /////////////////////////////////////////////////////
  # ﾒｰﾙ送信関数 emoji_send_mail のｴｲﾘｱｽです。(旧ﾊﾞｰｼﾞｮﾝとの互換性保持のため)
  # [引渡し値]
  # 　$to_name                   : 送信先名
  # 　$to_add                    : 送信先ﾒｰﾙｱﾄﾞﾚｽ
  # 　$from_name                 : 送信元名
  # 　$from_add                  : 送信元ﾒｰﾙｱﾄﾞﾚｽ
  # 　$repry_name                : 返信先名
  # 　$repry_to                  : 返信先ﾒｰﾙｱﾄﾞﾚｽ
  # 　$return_path               : 不達ﾒｰﾙ送信先ｱﾄﾞﾚｽ
  # 　$subject                   : 件名
  # 　$body                      : 本文
  # 　$to_career                 : 送信先ｷｬﾘｱ
  # 　$html_flag                 : HTMLﾒｰﾙﾌﾗｸﾞ
  # 　$content_transfer_encoding : ﾒｰﾙｴﾝｺｰﾃﾞｨﾝｸﾞ指定
  # 　$upfile                    : 添付ﾌｧｲﾙ保存ﾊﾟｽ
  # 　$file_name                 : 添付ﾌｧｲﾙ名
  # [返り値]
  # 　True : 送信成功、False : 送信失敗
  #////////////////////////////////////////////////////////////////////////////
  function emoji_send_mail2($to_name,$to_add,$from_name,$from_add,$repry_name,$repry_to,$return_path,$subject,$body,$to_career='DoCoMo',$html_flag='0',$content_transfer_encoding='',$upfile='',$file_name='') {
    global $emoji_mail_obj;
    $flag = False;
    if (is_object($emoji_mail_obj)) {
      $flag = $emoji_mail_obj->emoji_send_mail2($to_name,$to_add,$from_name,$from_add,$repry_name,$repry_to,$return_path,$subject,$body,$to_career,$html_flag,$content_transfer_encoding,$upfile,$file_name);
    }
    return $flag;
  }

  # 絵文字ﾒｰﾙ送信(mail関数送信) ///////////////////////////////////////////////
  # 絵文字ﾒｰﾙを送信します。
  # [引渡し値]
  # 　$to_name                   : 送信先名
  # 　$to_add                    : 送信先ﾒｰﾙｱﾄﾞﾚｽ
  # 　$from_name                 : 送信元名
  # 　$from_add                  : 送信元ﾒｰﾙｱﾄﾞﾚｽ
  # 　$subject                   : 件名
  # 　$body                      : 本文
  # 　$repry_name                : 返信先名(指定無い場合は送信元名)
  # 　$repry_to                  : 返信先ﾒｰﾙｱﾄﾞﾚｽ(指定無い場合は送信元ﾒｰﾙｱﾄﾞﾚｽ)
  # 　$return_path               : 不達ﾒｰﾙ送信先ｱﾄﾞﾚｽ(指定無い場合は送信元ﾒｰﾙｱﾄﾞﾚｽ)
  # 　$html_flag                 : HTMLﾒｰﾙﾌﾗｸﾞ(指定なし又は'0':ﾃｷｽﾄﾒｰﾙ、'1':HTMLﾒｰﾙ、'2':HTMLﾒｰﾙ(ｲﾝﾅｰ画像-ﾃﾞｺﾒﾀｲﾌﾟ))
  # 　$content_transfer_encoding : ﾒｰﾙｴﾝｺｰﾃﾞｨﾝｸﾞ指定(指定なし又は'7bit':ﾃﾞﾌｫﾙﾄ又は7bit、'base64':base64)
  # 　$mail_code                 : ﾒｰﾙ本文文字ｺｰﾄﾞ指定(指定なし又は'JIS':JIS)
  # 　$upfile                    : 添付ﾌｧｲﾙ保存ﾊﾟｽ
  # 　$file_name                 : 添付ﾌｧｲﾙ名
  # 　$encode_pass               : ｴﾝｺｰﾄﾞ処理無効化('1')
  # 　$input_code                : 入力文字ｺｰﾄﾞ指定(指定なし:設定による、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # [返り値]
  # 　True : 送信成功、False : 送信失敗
  #////////////////////////////////////////////////////////////////////////////
  function emoji_send_mail($to_name,$to_add,$from_name,$from_add,$subject,$body,$repry_name='',$repry_to='',$return_path='',$html_flag='0',$content_transfer_encoding='',$mail_code='JIS',$upfile='',$file_name='',$encode_pass='',$input_code='') {
    global $emoji_mail_obj;
    $flag = False;
    if (is_object($emoji_mail_obj)) {
      $flag = $emoji_mail_obj->emoji_send_mail($to_name,$to_add,$from_name,$from_add,$subject,$body,$repry_name,$repry_to,$return_path,$html_flag,$content_transfer_encoding,$mail_code,$upfile,$file_name,$encode_pass,$input_code);
    }
    return $flag;
  }

  # 絵文字ﾒｰﾙ送信3(mail関数送信) //////////////////////////////////////////////
  # 絵文字ﾒｰﾙを送信します。
  # [引渡し値]
  # 　$TODATA[*****]             : ｷｰ名:送信先ﾒｰﾙｱﾄﾞﾚｽ、要素(値):送信先名
  # 　$CCDATA[*****]             : ｷｰ名:送信先(ｶｰﾎﾞﾝｺﾋﾟｰ)ﾒｰﾙｱﾄﾞﾚｽﾘｽﾄ、要素(値):送信先(ｶｰﾎﾞﾝｺﾋﾟｰ)名
  # 　$BCCDATA[*****]            : ｷｰ名:同報先ﾒｰﾙｱﾄﾞﾚｽﾘｽﾄ、要素(値):同報先名
  # 　$from_name                 : 送信元名
  # 　$from_add                  : 送信元ﾒｰﾙｱﾄﾞﾚｽ
  # 　$subject                   : 件名
  # 　$body_plain                : ﾃｷｽﾄ本文
  # 　$body_html                 : HTML本文
  # 　$repry_name                : 返信先名(指定無い場合は送信元名)
  # 　$repry_to                  : 返信先ﾒｰﾙｱﾄﾞﾚｽ(指定無い場合は送信元ﾒｰﾙｱﾄﾞﾚｽ)
  # 　$return_path               : 不達ﾒｰﾙ送信先ｱﾄﾞﾚｽ(指定無い場合は送信元ﾒｰﾙｱﾄﾞﾚｽ)
  # 　$to_career                 : 送信先ｷｬﾘｱ(指定なし:PC及び全ｷｬﾘｱ、'DoCoMo':DoCOMo、'au':au、'SoftBank':SoftBank)
  # 　$content_transfer_encoding : ﾒｰﾙｴﾝｺｰﾃﾞｨﾝｸﾞ指定(指定なし又は'7bit':ﾃﾞﾌｫﾙﾄ又は7bit、'base64':base64)
  # 　$mail_code                 : ﾒｰﾙ本文文字ｺｰﾄﾞ指定(指定なし又は'JIS':JIS)
  # 　$UPFILE[*****]             : ｷｰ名:添付ﾌｧｲﾙﾊﾟｽ、要素(値):添付ﾌｧｲﾙ名
  # 　$encode_pass               : ｴﾝｺｰﾄﾞ処理無効化('1')
  # 　$input_code                : 入力文字ｺｰﾄﾞ指定(指定なし:設定による、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # [返り値]
  # 　True : 送信成功、False : 送信失敗
  #////////////////////////////////////////////////////////////////////////////
  function emoji_send_mail3($TODATA,$CCDATA,$BCCDATA,$from_name,$from_add,$subject,$body_plain,$body_html,$repry_name='',$repry_to='',$return_path='',$to_career='',$content_transfer_encoding='',$mail_code='JIS',$UPFILE='',$encode_pass='',$input_code='') {
    global $emoji_mail_obj;
    $flag = False;
    if (is_object($emoji_mail_obj)) {
      $flag = $emoji_mail_obj->emoji_send_mail3($TODATA,$CCDATA,$BCCDATA,$from_name,$from_add,$subject,$body_plain,$body_html,$repry_name,$repry_to,$return_path,$to_career,$content_transfer_encoding,$mail_code,$UPFILE,$encode_pass,$input_code);
    }
    return $flag;
  }

  # 絵文字ﾃﾞｺﾚｰｼｮﾝﾒｰﾙ送信 /////////////////////////////////////////////////////
  # 絵文字ﾃﾞｺﾚｰｼｮﾝﾒｰﾙを送信します。
  # [引渡し値]
  # 　$MAIL_DATA['TODATA']                       : 送信先ﾒｰﾙｱﾄﾞﾚｽﾘｽﾄ
  # 　　$MAIL_DATA['TODATA'][*****]              : ｷｰ名:送信先ﾒｰﾙｱﾄﾞﾚｽ、要素(値):送信先名
  # 　$MAIL_DATA['CCDATA']                       : 送信先ﾒｰﾙｱﾄﾞﾚｽﾘｽﾄ(ｶｰﾎﾞﾝｺﾋﾟｰ)
  # 　　$MAIL_DATA['CCDATA'][*****]              : ｷｰ名:送信先(ｶｰﾎﾞﾝｺﾋﾟｰ)ﾒｰﾙｱﾄﾞﾚｽﾘｽﾄ、要素(値):送信先(ｶｰﾎﾞﾝｺﾋﾟｰ)名
  # 　$MAIL_DATA['BCCDATA']                      : 同報先ﾒｰﾙｱﾄﾞﾚｽ
  # 　　$MAIL_DATA['BCCDATA'][*****]             : ｷｰ名:同報先ﾒｰﾙｱﾄﾞﾚｽﾘｽﾄ、要素(値):同報先名
  # 　$MAIL_DATA['from_name']                    : 送信元名
  # 　$MAIL_DATA['from_add']                     : 送信元ﾒｰﾙｱﾄﾞﾚｽ
  # 　$MAIL_DATA['repry_name']                   : 返信先名(指定無い場合は送信元名)
  # 　$MAIL_DATA['repry_to']                     : 返信先ﾒｰﾙｱﾄﾞﾚｽ(指定無い場合は送信元ﾒｰﾙｱﾄﾞﾚｽ)
  # 　$MAIL_DATA['return_path']                  : 不達ﾒｰﾙ送信先ｱﾄﾞﾚｽ(指定無い場合は送信元ﾒｰﾙｱﾄﾞﾚｽ)
  # 　$MAIL_DATA['subject']                      : 件名
  # 　$MAIL_DATA['body_plain']                   : ﾃｷｽﾄ本文
  # 　$MAIL_DATA['body_html']                    : HTML本文
  # 　$SETTING_DATA['decome_mode']               : ﾃﾞｺﾒ指定(指定なし:一般送信、'1':ﾃﾞｺﾒ送信)
  # 　$SETTING_DATA['to_career']                 : 送信先ｷｬﾘｱ(指定なし:PC及び全ｷｬﾘｱ、'DoCoMo':DoCoMo、'au':au、'SoftBank':SoftBank(絵文字変換ﾗｲﾌﾞﾗﾘで設定した名前))
  # 　$SETTING_DATA['content_transfer_encoding'] : ﾒｰﾙｴﾝｺｰﾃﾞｨﾝｸﾞ指定(指定なし又は'7bit':ﾃﾞﾌｫﾙﾄ又は7bit、'base64':base64)
  # 　$SETTING_DATA['mail_code']                 : ﾒｰﾙ本文文字ｺｰﾄﾞ指定(指定なし又は'JIS':JIS)
  # 　$SETTING_DATA['encode_pass']               : ｴﾝｺｰﾄﾞ処理無効化('1')
  # 　$SETTING_DATA['input_code']                : 入力文字ｺｰﾄﾞ指定(指定なし:設定による、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # 　$UPFILE[*****]                             : ｷｰ名:添付ﾌｧｲﾙﾊﾟｽ、要素(値):添付ﾌｧｲﾙ名
  # 　$katakana_chg_cancel       : 件名･本文半角ｶﾀｶﾅ全角変換ｷｬﾝｾﾙ(指定なし:強制変換,1:変換ｷｬﾝｾﾙ)
  # [返り値]
  # 　True : 送信成功、False : 送信失敗
  #////////////////////////////////////////////////////////////////////////////
  function emoji_decome($MAIL_DATA,$SETTING_DATA,$UPFILE,$katakana_chg_cancel='') {
    global $emoji_mail_obj;
    $flag = False;
    if (is_object($emoji_mail_obj)) {
      $flag = $emoji_mail_obj->emoji_decome($MAIL_DATA,$SETTING_DATA,$UPFILE,$katakana_chg_cancel);
    }
    return $flag;
  }

  # 絵文字ﾃﾞｺﾚｰｼｮﾝﾒｰﾙ送信2(emoji_send_mail3関数と互換性) //////////////////////
  # 絵文字ﾃﾞｺﾚｰｼｮﾝﾒｰﾙを送信します。
  # [引渡し値]
  # 　$TODATA[*****]             : ｷｰ名:送信先ﾒｰﾙｱﾄﾞﾚｽ、要素(値):送信先名
  # 　$CCDATA[*****]             : ｷｰ名:送信先(ｶｰﾎﾞﾝｺﾋﾟｰ)ﾒｰﾙｱﾄﾞﾚｽﾘｽﾄ、要素(値):送信先(ｶｰﾎﾞﾝｺﾋﾟｰ)名
  # 　$BCCDATA[*****]            : ｷｰ名:同報先ﾒｰﾙｱﾄﾞﾚｽﾘｽﾄ、要素(値):同報先名
  # 　$from_name                 : 送信元名
  # 　$from_add                  : 送信元ﾒｰﾙｱﾄﾞﾚｽ
  # 　$subject                   : 件名
  # 　$body_plain                : ﾃｷｽﾄ本文
  # 　$body_html                 : HTML本文
  # 　$repry_name                : 返信先名(指定無い場合は送信元名)
  # 　$repry_to                  : 返信先ﾒｰﾙｱﾄﾞﾚｽ(指定無い場合は送信元ﾒｰﾙｱﾄﾞﾚｽ)
  # 　$return_path               : 不達ﾒｰﾙ送信先ｱﾄﾞﾚｽ(指定無い場合は送信元ﾒｰﾙｱﾄﾞﾚｽ)
  # 　$to_career                 : 送信先ｷｬﾘｱ(指定なし:PC及び全ｷｬﾘｱ、'DoCoMo':DoCOMo、'au':au、'SoftBank':SoftBank)
  # 　$content_transfer_encoding : ﾒｰﾙｴﾝｺｰﾃﾞｨﾝｸﾞ指定(指定なし又は'7bit':ﾃﾞﾌｫﾙﾄ又は7bit、'base64':base64)
  # 　$mail_code                 : ﾒｰﾙ本文文字ｺｰﾄﾞ指定(指定なし又は'JIS':JIS)
  # 　$UPFILE[*****]             : ｷｰ名:添付ﾌｧｲﾙﾊﾟｽ、要素(値):添付ﾌｧｲﾙ名
  # 　$encode_pass               : ｴﾝｺｰﾄﾞ処理無効化('1')
  # 　$input_code                : 入力文字ｺｰﾄﾞ指定(指定なし:設定による、UTF-8ｺｰﾄﾞ:UTF-8、その他ｺｰﾄﾞ:SJIS)
  # 　$decome_mode               : ﾃﾞｺﾒ指定(指定なし:一般送信(emoji_send_mail3関数と同等の処理となります)、'1':ﾃﾞｺﾒ送信)
  # 　$katakana_chg_cancel       : 件名･本文半角ｶﾀｶﾅ全角変換ｷｬﾝｾﾙ(指定なし:強制変換,1:変換ｷｬﾝｾﾙ)
  # [返り値]
  # 　True : 送信成功、False : 送信失敗
  #////////////////////////////////////////////////////////////////////////////
  function emoji_decome2($TODATA,$CCDATA,$BCCDATA,$from_name,$from_add,$subject,$body_plain,$body_html,$repry_name='',$repry_to='',$return_path='',$to_career='',$content_transfer_encoding='',$mail_code='JIS',$UPFILE='',$encode_pass='',$input_code='',$decome_mode='1',$katakana_chg_cancel='') {
    global $emoji_mail_obj;
    $flag = False;
    if (is_object($emoji_mail_obj)) {
      $flag = $emoji_mail_obj->emoji_decome2($TODATA,$CCDATA,$BCCDATA,$from_name,$from_add,$subject,$body_plain,$body_html,$repry_name,$repry_to,$return_path,$to_career,$content_transfer_encoding,$mail_code,$UPFILE,$encode_pass,$input_code,$decome_mode,$katakana_chg_cancel);
    }
    return $flag;
  }

  # 携帯情報取得 //////////////////////////////////////////////////////////////
  # 本関数は携帯の詳細情報を取得するための関数です。
  # [引渡し値]
  # 　$user_agent : ﾕｰｻﾞｰｴｰｼﾞｪﾝﾄ指定(指定無しの場合ｱｸｾｽ端末のﾕｰｻﾞｰｴｰｼﾞｪﾝﾄ)
  # [返り値]
  # 　$RETURNDATA['hard']           : ｷｬﾘｱ(PC,DoCoMo,au,Vodafone)
  # 　$RETURNDATA['career']         : ｷｬﾘｱ(PC,PSP,DoCoMo,au,Vodafone)
  # 　$RETURNDATA['kubun']          : 区分(DoCoMo:FOMA/mova,au:win,SoftBank:3G)
  # 　$RETURNDATA['meka_name']      : ﾒｰｶｰ名
  # 　$RETURNDATA['kisyu_type']     : 機種名
  # 　$RETURNDATA['image_mime']     : 画像MIME
  # 　$RETURNDATA['image_kaku']     : ﾃﾞﾌｫﾙﾄ画像拡張子
  # 　$RETURNDATA['movie_mime']     : 動画MIME
  # 　$RETURNDATA['movie_kaku']     : ﾃﾞﾌｫﾙﾄ動画拡張子
  # 　$RETURNDATA['movie_size']     : ﾃﾞﾌｫﾙﾄ動画ｻｲｽﾞ
  # 　$RETURNDATA['down_size']      : ﾀﾞｳﾝﾛｰﾄﾞ動画最大ｻｲｽﾞ(KB)
  # 　$RETURNDATA['str_size']       : ｽﾄﾘｰﾐﾝｸﾞ動画最大ｻｲｽﾞ(KB)
  # 　$RETURNDATA['display_width']  : ﾃﾞｨｽﾌﾟﾚｲ幅(pt)
  # 　$RETURNDATA['display_height'] : ﾃﾞｨｽﾌﾟﾚｲ高さ(pt)
  # 　$RETURNDATA['display_color']  : ﾃﾞｨｽﾌﾟﾚｲ表示色数
  # 　$RETURNDATA['cache_size']     : ｷｬｯｼｭｻｲｽﾞ
  # 　$RETURNDATA['export_type']    : 動画処理用ﾀｲﾌﾟ指定1
  # 　$RETURNDATA['export_type2']   : 動画処理用ﾀｲﾌﾟ指定2
  #////////////////////////////////////////////////////////////////////////////
  function Get_PhoneData($user_agent='') {
    global $emoji_sub_obj;
    $RETURNDATA = array();
    if (is_object($emoji_sub_obj)) {
      $RETURNDATA = $emoji_sub_obj->Get_PhoneData($user_agent);
    }
    return $RETURNDATA;
  }

  # 数字→絵文字数字変換(ｴﾝｺｰﾄﾞ文字) //////////////////////////////////////////
  # 数字から絵文字数字へ変換します。
  # [引渡し値]
  # 　$num_text    : 数値
  # 　$change_mode : 10以上の数値の場合の変換ﾊﾟﾀｰﾝ指定
  # [返り値]
  # 　$emoji_num : 変換結果
  #////////////////////////////////////////////////////////////////////////////
  function num2emojinum($num_text,$change_mode='') {
    global $emoji_sub_obj;
    $emoji_num = '';
    if (is_object($emoji_sub_obj)) {
      $emoji_num = $emoji_sub_obj->num2emojinum($num_text,$change_mode);
    }
    return $emoji_num;
  }

}

?>