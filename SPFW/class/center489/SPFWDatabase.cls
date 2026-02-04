<?php
/**
 * SPFWDatabaseクラス
 *
 * データベースへの接続とSQL実行を担うクラスです。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/01/31
 * @version 0.1
 **/

#if (!$MYSQL)
#	include_once _CLS_DIR . 'SPFWDatabase_PGSQL.cls';
#else
	include_once _CLS_DIR . 'center489/SPFWDatabase_MYSQL.cls';
?>
