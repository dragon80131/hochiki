<?php

###############################################################################
# �g�ъG�����ϊ�ײ���� 2008(Ұُ����׽ײ����)
# Potora/inaken(C) 2008.
# MAIL: support@potora.dip.jp
#       inaken@jomon.ne.jp
# URL : http://potora.dip.jp/
#       http://www.jomon.ne.jp/~inaken/
###############################################################################
# 2008.10.07 v.1.00.00 �V�K
# 2008.10.20 v.2.00.00 SMTP�ڑ�Ұّ��M�@�\�ǉ�
# 2008.11.28 v.2.00.01 ��۰��ٕϐ������ύX
###############################################################################

###############################################################################
# �G����Ұُ����׽ ############################################################
###############################################################################
class emoji_mail {
  # �ް�ޮݐݒ�
  var $ver = 'mail_v.1.00.00';

  var $html_mail_flag;   # PC��HTMLҰّ��M�ݒ�
  var $cont_trs_enc;     # Ұّ��M�ݺ��ސݒ�

  # ̧��MIME�w��
  var $FILETYPE = array(
    'txt'  => 'text/plain',
    'htm'  => 'text/html',
    'html' => 'text/html',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'gif'  => 'image/gif',
    'png'  => 'image/png',
    'bmp'  => 'image/x-bmp',
    'ai'   => 'application/postscript',
    'psd'  => 'image/x-photoshop',
    'eps'  => 'application/postscript',
    'pdf'  => 'application/pdf',
    'swf'  => 'application/x-shockwave-flash',
    'lzh'  => 'application/x-lha-compressed',
    'zip'  => 'application/x-zip-compressed',
    'sit'  => 'application/x-stuffit',
  );

  # �ݽ�׸� ///////////////////////////////////////////////////////////////////
  function emoji_mail() {
    global $decome_obj,$smtp_obj;

    # �l�ݒ�
    if (!defined('EMOJI_smtp_flag')) { define('EMOJI_smtp_flag','0'); }
    # �޺�ײ���ؑΉ�
    $decome_obj = '';
    if (file_exists(dirname(__FILE__).'/decome_class.php')) {
      include_once(dirname(__FILE__).'/decome_class.php');
      $decome_obj = new decome();
    }
    # SMTPײ���ؓǍ���
    $smtp_obj = '';
    if (EMOJI_smtp_flag == 1) {
      if (file_exists(dirname(__FILE__).'/smtp_class.php')) {
        include_once(dirname(__FILE__).'/smtp_class.php');
        $smtp_obj = new smtp();
        # SMTP�ڑ���ݒ�
        $smtp_obj->this_server = '';
        $smtp_obj->smtp_server = '';
        $smtp_obj->smtp_port   = 25;
        $smtp_obj->pop3_server = '';
        $smtp_obj->pop3_port   = 110;
        $smtp_obj->mail_user   = '';
        $smtp_obj->mail_pass   = '';
        $smtp_obj->auth        = True;
        $smtp_obj->auth_type   = 'POP';
        if (defined('EMOJI_this_server')) { $smtp_obj->this_server = EMOJI_this_server; }
        if (defined('EMOJI_smtp_server')) { $smtp_obj->smtp_server = EMOJI_smtp_server; }
        if (defined('EMOJI_smtp_port'))   { $smtp_obj->smtp_port   = EMOJI_smtp_port; }
        if (defined('EMOJI_pop3_server')) { $smtp_obj->pop3_server = EMOJI_pop3_server; }
        if (defined('EMOJI_pop3_port'))   { $smtp_obj->pop3_port   = EMOJI_pop3_port; }
        if (defined('EMOJI_mail_user'))   { $smtp_obj->mail_user   = EMOJI_mail_user; }
        if (defined('EMOJI_mail_pass'))   { $smtp_obj->mail_pass   = EMOJI_mail_pass; }
        if (defined('EMOJI_auth'))        { $smtp_obj->auth        = EMOJI_auth; }
        if (defined('EMOJI_auth_tyle'))   { $smtp_obj->auth_tyle   = EMOJI_auth_tyle; }
      }
    }
  }

  # �G�����ϊ�ײ�����ް�ޮݎ擾 ///////////////////////////////////////////////
  # ��ر���ʂƋ@������擾���܂��B(�V����->����)
  # [���n���l]
  # �@�Ȃ�
  # [�Ԃ�l]
  # �@$this->ver : ײ�����ް�ޮ�
  #////////////////////////////////////////////////////////////////////////////
  function Get_Emj_Version() {
    return $this->ver;
  }

  # Ұٱ��ڽ��ر��� //////////////////////////////////////////////////////////
  # Ұٱ��ڽ��跬ر�����擾���܂�
  # [���n���l]
  # �@$mail_address : Ұٱ��ڽ
  # [�Ԃ�l]
  # �@$career : ��ر���ʌ���(DoCoMo,au,SoftBank or Vodafone)
  #////////////////////////////////////////////////////////////////////////////
  function get_mail_career($mail_address) {
    global $emoji_obj;

    $career = '';
    if (preg_match('/^(.+?)\@(.*)docomo(.+)$/',$mail_address)) {
      # DoCoMo�g��
      $career = 'DoCoMo';
    } elseif (preg_match('/^(.+?)\@(.*)vodafone(.+)$/',$mail_address) or preg_match('/^(.+?)\@softbank(.+)$/',$mail_address)) {
      # SoftBank(Vodafone)�g��
      $career = $emoji_obj->softbank_name;
    } elseif (preg_match('/^(.+?)\@(.*)ezweb(.+)$/',$mail_address)) {
      # au�g��
      $career = 'au';
    } else {
      # ���̑�
      $career = 'PC';
    }
    return $career;
  }

  # Ұّ��M(mail�֐����M) /////////////////////////////////////////////////////
  # Ұّ��M�֐� emoji_send_mail �̴�ر��ł��B(���ް�ޮ݂Ƃ̌݊����ێ��̂���)
  # [���n���l]
  # �@$to_name                   : ���M�於
  # �@$to_add                    : ���M��Ұٱ��ڽ
  # �@$from_name                 : ���M����
  # �@$from_add                  : ���M��Ұٱ��ڽ
  # �@$repry_name                : �ԐM�於
  # �@$repry_to                  : �ԐM��Ұٱ��ڽ
  # �@$return_path               : �s�BҰّ��M����ڽ
  # �@$subject                   : ����
  # �@$body                      : �{��
  # �@$to_career                 : ���M�淬ر
  # �@$html_flag                 : HTMLҰ��׸�
  # �@$content_transfer_encoding : Ұٴݺ��ިݸގw��
  # �@$upfile                    : �Y�ţ�ٕۑ��߽
  # �@$file_name                 : �Y�ţ�ٖ�
  # [�Ԃ�l]
  # �@True : ���M�����AFalse : ���M���s
  #////////////////////////////////////////////////////////////////////////////
  function emoji_send_mail2($to_name,$to_add,$from_name,$from_add,$repry_name,$repry_to,$return_path,$subject,$body,$to_career='DoCoMo',$html_flag='0',$content_transfer_encoding='',$upfile='',$file_name='') {
    # Ұّ��M�֐��ďo
    $flag = $this->emoji_send_mail($to_name,$to_add,$from_name,$from_add,$subject,$body,$repry_name,$repry_to,$return_path,$html_flag,$content_transfer_encoding,'JIS',$upfile,$file_name);
    return $flag;
  }

  # �G����Ұّ��M(mail�֐����M) ///////////////////////////////////////////////
  # �G����Ұق𑗐M���܂��B
  # [���n���l]
  # �@$to_name                   : ���M�於
  # �@$to_add                    : ���M��Ұٱ��ڽ
  # �@$from_name                 : ���M����
  # �@$from_add                  : ���M��Ұٱ��ڽ
  # �@$subject                   : ����
  # �@$body                      : �{��
  # �@$repry_name                : �ԐM�於(�w�薳���ꍇ�͑��M����)
  # �@$repry_to                  : �ԐM��Ұٱ��ڽ(�w�薳���ꍇ�͑��M��Ұٱ��ڽ)
  # �@$return_path               : �s�BҰّ��M����ڽ(�w�薳���ꍇ�͑��M��Ұٱ��ڽ)
  # �@$html_flag                 : HTMLҰ��׸�(�w��Ȃ�����'0':÷��ҰفA'1':HTMLҰفA'2':HTMLҰ�(��Ű�摜-�޺�����))
  # �@$content_transfer_encoding : Ұٴݺ��ިݸގw��(�w��Ȃ�����'7bit':��̫�Ė���7bit�A'base64':base64)
  # �@$mail_code                 : Ұٖ{���������ގw��(�w��Ȃ�����'JIS':JIS)
  # �@$upfile                    : �Y�ţ�ٕۑ��߽
  # �@$file_name                 : �Y�ţ�ٖ�
  # �@$encode_pass               : �ݺ��ޏ���������('1')
  # �@$input_code                : ���͕������ގw��(�w��Ȃ�:�ݒ�ɂ��AUTF-8����:UTF-8�A���̑�����:SJIS)
  # [�Ԃ�l]
  # �@True : ���M�����AFalse : ���M���s
  #////////////////////////////////////////////////////////////////////////////
  function emoji_send_mail($to_name,$to_add,$from_name,$from_add,$subject,$body,$repry_name='',$repry_to='',$return_path='',$html_flag='0',$content_transfer_encoding='',$mail_code='JIS',$upfile='',$file_name='',$encode_pass='',$input_code='') {
    global $emoji_obj,$emoji_sub_obj,$smtp_obj;

    # ���M��A���M������
    if (($to_add == '') or ($from_add == '')) { return False; }
    # �ԐM�於����
    if ($repry_name == '')  { $repry_name  = $from_name; }
    # �ԐM�於����
    if ($repry_to == '')    { $repry_to    = $from_add; }
    # �s�BҰٖ߂������
    if ($return_path == '') { $return_path = $from_add; }
    # ���M�ݺ��ސݒ�
    if ($content_transfer_encoding == '') {
      if ($emoji_obj->cont_trs_enc) {
        $content_transfer_encoding = $emoji_obj->cont_trs_enc;
      } else {
        $content_transfer_encoding = '7bit';
      }
    }
    # ���M�淬ر�擾
    $to_career = $this->get_mail_career($to_add);

    # ���M��(To��)����
    $set_to = '';
    if ($to_name != '') {
      # ���M�Җ��̎w�肪����ꍇ
      $str_code = mb_detect_encoding($to_name,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
      if ($str_code == 'JIS') {
        $set_to  = $to_name;
      } else {
        $set_to  = @mb_convert_encoding($to_name,'JIS',$str_code);
      }
      $set_to  = mb_convert_kana($set_to,'KV','JIS');
      $set_to  = mb_encode_mimeheader($set_to,'JIS');
      $set_to .= ' <'.$to_add.'>';
    } else {
      # ���M�Җ��̎w�肪�����ꍇ
      $set_to = $to_add;
    }
    # ���M��(From��)����
    $set_form = '';
    if ($from_name != '') {
      $str_code = mb_detect_encoding($from_name,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
      if ($str_code == 'JIS') {
        $set_form = $from_name;
      } else {
        $set_form = @mb_convert_encoding($from_name,'JIS',$str_code);
      }
      $set_form  = mb_convert_kana($set_form,'KV','JIS');
      $set_form  = mb_encode_mimeheader($set_form,'JIS');
      $set_form .= ' <'.$from_add.'>';
    } else {
      $set_form = $from_add;
    }
    # �ԐM��(Repry_to��)����
    $set_repry_to = '';
    if ($repry_name != '') {
      $str_code = mb_detect_encoding($repry_name,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
      if ($str_code == 'JIS') {
        $set_repry_to  = $repry_name;
      } else {
        $set_repry_to  = @mb_convert_encoding($repry_name,'JIS',$str_code);
      }
      $set_repry_to  = mb_convert_kana($set_repry_to,'KV','JIS');
      $set_repry_to  = mb_encode_mimeheader($set_repry_to,'JIS');
      $set_repry_to .= " <".$repry_to.">";
    } else {
      $set_repry_to = $repry_to;
    }
    # Ұّ��M�p�G�����ϊ�(�ݺ���)
    if ($encode_pass != '1') {
      $subject = $emoji_obj->emj_encode($subject,'','',$input_code);
      $body    = $emoji_obj->emj_encode($body,'','',$input_code);
    }
    # �������ގ擾
    $subject_code = mb_detect_encoding($subject,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
    $body_code    = mb_detect_encoding($body,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
    # �������ޕϊ�
    if ($subject_code != $mail_code) { $subject = @mb_convert_encoding($subject,$mail_code,$subject_code); }
    if ($body_code    != $mail_code) { $body    = @mb_convert_encoding($body,$mail_code,$subject_code); }
    # ���ŕϊ�
    $subject = mb_convert_kana($subject,'KV',$mail_code);
    $body    = mb_convert_kana($body,'KV',$mail_code);

    # ��������
    # �G�����ϊ�(�޺���)
    $SUBJECT = $emoji_obj->emj_decode($subject,$to_career,$mail_code);
    $subject = $SUBJECT['mail'];
    # ��������
    if ($subject == '') { $subject = @mb_convert_encoding('����','JIS','SJIS'); }
    $subject = base64_encode($subject);
    $subject = '=?ISO-2022-JP?B?'.$subject.'?=';

    # �{������
    # �{���G�����F��
    $EMJ_COUNT = $emoji_sub_obj->emj_check($body,'1');
    if ($EMJ_COUNT['total'] > 0) { $body_emj_flag = True; } else { $body_emj_flag = False; }
    # Ұّ��M�p�G�����ϊ�(�޺���)
    $BODY = $emoji_obj->emj_decode($body,$to_career,$mail_code,$html_flag);
    $body = $BODY['mail'];
    if ((preg_match('/^pc$/i',$to_career) or preg_match('/^'.$emoji_obj->softbank_name.'$/i',$to_career)) and (($body_emj_flag == True) or ($html_flag == '1'))) {
      # HTML��ޗL������
      $tag_flag = False;
      if ($body != strip_tags($body)) { $tag_flag = True; }
      # �{��HTML������
      $body = preg_replace('/\r/','',$body);
      if ($tag_flag == False) { $body = preg_replace('/\n/',"<br>\n",$body); }
    }
    # Base64�޺���
    if ($content_transfer_encoding == 'base64') { $body = base64_encode($body); }

    # ͯ�ް�A�{������
    $msg  = '';
    $add_mail_header  = '';
    $add_mail_header .= "From: ".$set_form."\r\n";
    $add_mail_header .= "Reply-To: ".$set_repry_to."\r\n";
    $add_mail_header .= "MIME-Version: 1.0\r\n";

    # �Y�ţ������
    $upfile_type = '';
    $tail        = '';
    $upfile_flag = 0;
    if (file_exists($upfile)) {
      if ($fp = @fopen($upfile,"r")) {
        @fclose($fp);
        if (preg_match('/.gif$/i',$upfile)) {
          $upfile_type = 'image/gif';
          $tail        = '.gif';
        } elseif (preg_match('/.jpe*g$/i',$upfile)) {
          $upfile_type = 'image/jpeg';
          $tail        = '.jpg';
        } elseif (preg_match('/.png$/i',$upfile)) {
          $upfile_type = 'image/png';
          $tail        = '.png';
        }
        $FDT = split('/',$upfile);
        $upfile_name = $FDT[count($FDT) - 1];
        $upfile_flag = 1;
      }
    }

    if ($upfile_flag == 1) {
      # �Y�ţ�ٗL��ꍇ
      # �޳���ذ����(�߰Ă̋��E)
      $boundary = md5(uniqid(rand()));
      # ͯ�ް�ݒ�
      $header .= "Content-Type: multipart/mixed;\n";
      $header .= "\tboundary=\"".$boundary."\"\n";
      # �{������
      $msg .= "This is a multi-part message in MIME format.\n\n";
      $msg .= "--".$boundary."\n";
    }
    $ht = '';
    if ((preg_match('/^pc$/i',$to_career) or preg_match('/^'.$emoji_obj->softbank_name.'$/i',$to_career)) and (($body_emj_flag == True) or ($html_flag == '1'))) {
      # HTMLҰق̏ꍇ
      $ht .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\r\n";
    } else {
      # ÷��Ұق̏ꍇ
      $ht .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\r\n";
    }
    $ht .= "Content-Transfer-Encoding: ".$content_transfer_encoding;
    if ($upfile_flag == 1) {
      # �Y�ţ�ٗL��ꍇ
      $msg .= $ht;
      $msg .= $body;
      # ̧�ٓǍ���
      $fp = fopen($upfile,"r");
      $fdata = fread($fp, filesize($upfile));
      fclose($fp);
      # ̧�ٖ��ݒ�
      if ($file_name) { $upfile_name = $file_name.$tail; }
      # �ݺ��ނ��ĕ���
      $f_encoded = chunk_split(base64_encode($fdata));
      $msg .= "\n\n--".$boundary."\n";
      $msg .= "Content-Type: ".$upfile_type.";\n";
      $msg .= "\tname=\"".$upfile_name."\"\n";
      $msg .= "Content-Transfer-Encoding: base64\n";
      $msg .= "Content-Disposition: attachment;\n";
      $msg .= "\tfilename=\"".$upfile_name."\"\n\n";
      $msg .= $f_encoded."\n";
      $msg .= "--".$boundary."--";
      $body = $msg;
    } else {
      # �Y�ţ�ٖ����ꍇ
      $add_mail_header .= $ht;
    }

    if ((EMOJI_smtp_flag == 1) and is_object($smtp_obj)) {
      # SMTP���M
      # ���M���e�ݒ�
      $smtp_obj->TOLIST[$to_name] = $to_add;
      $smtp_obj->from_name        = $from_name;
      $smtp_obj->from_address     = $from_add;
      $smtp_obj->reply_to_name    = $repry_name;
      $smtp_obj->reply_to_address = $repry_to;
      $smtp_obj->return_path      = $return_path;
      $smtp_obj->add_header       = '';
      $smtp_obj->subject          = $subject;
      $smtp_obj->body             = $body;
      # Ұّ��M
      $success = $smtp_obj->smtp_mail();
    } else {
      # PHP mail�֐����M
      // $success = @mail($set_to,$subject,$body,$add_mail_header,'-f'.$return_path);
    }
    if ($success) { return True; } else { return False; }

  }

  # �G����Ұّ��M3(mail�֐����M) //////////////////////////////////////////////
  # �G����Ұق𑗐M���܂��B
  # [���n���l]
  # �@$TODATA[*****]             : ����:���M��Ұٱ��ڽ�A�v�f(�l):���M�於
  # �@$CCDATA[*****]             : ����:���M��(����ݺ�߰)Ұٱ��ڽؽāA�v�f(�l):���M��(����ݺ�߰)��
  # �@$BCCDATA[*****]            : ����:�����Ұٱ��ڽؽāA�v�f(�l):����於
  # �@$from_name                 : ���M����
  # �@$from_add                  : ���M��Ұٱ��ڽ
  # �@$subject                   : ����
  # �@$body_plain                : ÷�Ė{��
  # �@$body_html                 : HTML�{��
  # �@$repry_name                : �ԐM�於(�w�薳���ꍇ�͑��M����)
  # �@$repry_to                  : �ԐM��Ұٱ��ڽ(�w�薳���ꍇ�͑��M��Ұٱ��ڽ)
  # �@$return_path               : �s�BҰّ��M����ڽ(�w�薳���ꍇ�͑��M��Ұٱ��ڽ)
  # �@$to_career                 : ���M�淬ر(�w��Ȃ�:PC�y�ёS��ر�A'DoCoMo':DoCOMo�A'au':au�A'SoftBank':SoftBank)
  # �@$content_transfer_encoding : Ұٴݺ��ިݸގw��(�w��Ȃ�����'7bit':��̫�Ė���7bit�A'base64':base64)
  # �@$mail_code                 : Ұٖ{���������ގw��(�w��Ȃ�����'JIS':JIS)
  # �@$UPFILE[*****]             : ����:�Y�ţ���߽�A�v�f(�l):�Y�ţ�ٖ�
  # �@$encode_pass               : �ݺ��ޏ���������('1')
  # �@$input_code                : ���͕������ގw��(�w��Ȃ�:�ݒ�ɂ��AUTF-8����:UTF-8�A���̑�����:SJIS)
  # [�Ԃ�l]
  # �@True : ���M�����AFalse : ���M���s
  #////////////////////////////////////////////////////////////////////////////
  function emoji_send_mail3($TODATA,$CCDATA,$BCCDATA,$from_name,$from_add,$subject,$body_plain,$body_html,$repry_name='',$repry_to='',$return_path='',$to_career='',$content_transfer_encoding='',$mail_code='JIS',$UPFILE='',$encode_pass='',$input_code='') {
    global $emoji_obj,$emoji_sub_obj,$smtp_obj;

    # ���M������
    $to_flag  = False;
    $cc_flag  = False;
    $bcc_flag = False;
    $flag     = False;
    if (isset($TODATA)) {
      if (is_array($TODATA)) {
        if (isset($TODATA)) { $flag = True; $to_flag = True; }
      }
    }
    # ���M������
    if (isset($CCDATA)) {
      if (is_array($CCDATA)) {
        if (isset($CCDATA)) { $flag = True; $cc_flag = True; }
      }
    }
    # ���񑗐M����
    if (isset($BCCDATA)) {
      if (is_array($BCCDATA)) {
        if (isset($BCCDATA)) { $flag = True; $bcc_flag = True; }
      }
    }
    if ($flag == False) { return False; }
    # ���M������
    if (!isset($from_add))  { return False; }
    if (!isset($from_name)) { $from_name = ''; }
    # �ԐM�於����
    if ($repry_name == '')  { $repry_name  = $from_name; }
    # �ԐM�於����
    if ($repry_to == '')    { $repry_to    = $from_add; }
    # �s�BҰٖ߂������
    if ($return_path == '') { $return_path = $from_add; }

    # ���M�ݺ��ސݒ�
    if ($content_transfer_encoding == '') {
      if ($emoji_obj->cont_trs_enc) {
        $content_transfer_encoding = $emoji_obj->cont_trs_enc;
      } else {
        $content_transfer_encoding = '7bit';
      }
    }

    # Ұ����ߐݒ�
    $mail_type = '';
    if (isset($body_plain)) {
      if ($body_plain != '') {
        if (isset($body_html)) {
          if ($body_html != '') { $mail_type = 'multipart'; } else { $mail_type = 'plain'; }
        } else {
          $mail_type = 'plain';
        }
      } else {
        if (isset($body_html)) {
          if ($body_html != '') { $mail_type = 'html'; } else { return False; }
        } else {
          return False;
        }
      }
    } else {
      if (isset($body_html)) {
        if ($body_html != '') { $mail_type = 'html'; } else { return False; }
      } else {
        return False;
      }
    }

    # ���M��(To��)����
    $sp     = '';
    $set_to = '';
    if ($to_flag == True) {
      foreach ($TODATA as $adddt => $namedt) {
        if ($namedt != '') {
          # ���M�於�̎w�肪����ꍇ
          $set_to_sub = '';
          $str_code   = mb_detect_encoding($namedt,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
          if ($str_code == 'JIS') {
            $set_to_sub = $namedt;
          } else {
            $set_to_sub = @mb_convert_encoding($namedt,'JIS',$str_code);
          }
          $set_to_sub  = mb_convert_kana($set_to_sub,'KV','JIS');
          $set_to_sub  = mb_encode_mimeheader($set_to_sub,'JIS');
          $set_to     .= $sp.$set_to_sub.' <'.$adddt.'>';
        } else {
          # ���M�於�̎w�肪�����ꍇ
          $set_to .= $sp.$adddt;
        }
        $sp = ',';
      }
    }

    # ���M��(CC��)����
    $sp     = '';
    $set_cc = '';
    if ($cc_flag == True) {
      foreach ($CCDATA as $adddt => $namedt) {
        if ($namedt != '') {
          # ���M�於�̎w�肪����ꍇ
          $set_cc_sub = '';
          $str_code   = mb_detect_encoding($namedt,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
          if ($str_code == 'JIS') {
            $set_cc_sub = $namedt;
          } else {
            $set_cc_sub = @mb_convert_encoding($namedt,'JIS',$str_code);
          }
          $set_cc_sub  = mb_convert_kana($set_cc_sub,'KV','JIS');
          $set_cc_sub  = mb_encode_mimeheader($set_cc_sub,'JIS');
          $set_cc     .= $sp.$set_cc_sub.' <'.$adddt.'>';
        } else {
          # ���M���̎w�肪�����ꍇ
          $set_cc .= $sp.$adddt;
        }
        $sp = ',';
      }
    }

    # ����(Bcc��)����
    $sp      = '';
    $set_bcc = '';
    if ($bcc_flag == True) {
      foreach ($BCCDATA as $adddt => $namedt) {
        if ($namedt != '') {
          # ����於�̎w�肪����ꍇ
          $set_bcc_sub = '';
          $str_code    = mb_detect_encoding($namedt,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
          if ($str_code == 'JIS') {
            $set_bcc_sub = $namedt;
          } else {
            $set_bcc_sub = @mb_convert_encoding($namedt,'JIS',$str_code);
          }
          $set_bcc_sub  = mb_convert_kana($set_bcc_sub,'KV','JIS');
          $set_bcc_sub  = mb_encode_mimeheader($set_bcc_sub,'JIS');
          $set_bcc     .= $sp.$set_bcc_sub.' <'.$adddt.'>';
        } else {
          # ���񖼂̎w�肪�����ꍇ
          $set_bcc .= $sp.$adddt;
        }
        $sp = ',';
      }
    }

    # ���M��(From��)����
    $set_form = '';
    if ($from_name != '') {
      $str_code = mb_detect_encoding($from_name,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
      if ($str_code == 'JIS') {
        $set_form = $from_name;
      } else {
        $set_form = @mb_convert_encoding($from_name,'JIS',$str_code);
      }
      $set_form  = mb_convert_kana($set_form,'KV','JIS');
      $set_form  = mb_encode_mimeheader($set_form,'JIS');
      $set_form .= ' <'.$from_add.'>';
    } else {
      $set_form = $from_add;
    }

    # �ԐM��(Repry_to��)����
    $set_repry_to = '';
    if ($repry_name != '') {
      $str_code = mb_detect_encoding($repry_name,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
      if ($str_code == 'JIS') {
        $set_repry_to  = $repry_name;
      } else {
        $set_repry_to  = @mb_convert_encoding($repry_name,'JIS',$str_code);
      }
      $set_repry_to  = mb_convert_kana($set_repry_to,'KV','JIS');
      $set_repry_to  = mb_encode_mimeheader($set_repry_to,'JIS');
      $set_repry_to .= " <".$repry_to.">";
    } else {
      $set_repry_to = $repry_to;
    }

    # �{���ݒ�
    if (!isset($body_plain)) { $body_plain = ''; }
    if (!isset($body_html))  { $body_html  = ''; }

    # Ұّ��M�p�G�����ϊ�(�ݺ���)
    if ($encode_pass != '1') {
      $subject    = $emoji_obj->emj_encode($subject   ,'','',$input_code);
      $body_plain = $emoji_obj->emj_encode($body_plain,'','',$input_code);
      $body_html  = $emoji_obj->emj_encode($body_html ,'','',$input_code);
    }

    # �������ގ擾
    $subject_code    = mb_detect_encoding($subject   ,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
    $body_plain_code = mb_detect_encoding($body_plain,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
    $body_html_code  = mb_detect_encoding($body_html ,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);

    # �������ޕϊ�
    if ($subject_code    != $mail_code) { $subject    = @mb_convert_encoding($subject   ,$mail_code,$subject_code); }
    if ($body_plain_code != $mail_code) { $body_plain = @mb_convert_encoding($body_plain,$mail_code,$subject_code); }
    if ($body_html_code  != $mail_code) { $body_html  = @mb_convert_encoding($body_html ,$mail_code,$subject_code); }

    # ���ŕϊ�
    $subject    = mb_convert_kana($subject   ,'KV',$mail_code);
    $body_plain = mb_convert_kana($body_plain,'KV',$mail_code);
    $body_html  = mb_convert_kana($body_html ,'KV',$mail_code);

    # ��������
    # �G�����ϊ�(�޺���)
    if (($to_career == '') or ($to_career == 'PC')) {
      # PC�y�ёS��ر�����̏ꍇ(�G�����폜)
      $subject = $emoji_sub_obj->delete_emoji_code($subject);
    } elseif (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
      # SoftBank���Ă̏ꍇ(�G�����폜)
      $subject = $emoji_sub_obj->delete_emoji_code($subject);
    } else {
      # �e��ر����(�G�����޺���)
      $SUBJECT = $emoji_sub_obj->emj_decode($subject,$to_career,$mail_code);
      $subject = $SUBJECT['mail'];
    }
    # ��������
    if ($subject == '') { $subject = @mb_convert_encoding('����','JIS','SJIS'); }
    $subject = base64_encode($subject);
    $subject = '=?ISO-2022-JP?B?'.$subject.'?=';

    # SoftBank��ر�ݒ�

    # �{������(÷��)
    $to_html_flag = False;
    $enc_code = 'ISO-2022-JP';
    # Ұّ��M�p�G�����ϊ�(�޺���)
    if (($to_career == '') or ($to_career == 'PC')) {
      # PC�y�ёS��ر�����̏ꍇ(�G�����폜��HTML��)
      # �G�����L������
      $ECOUNT = $emoji_sub_obj->emj_check($body_plain,'',$input_code);
      if ($ECOUNT['total'] > 0) {
        # HTML��
        if ($body_html == '') {
          $body_html = $body_plain;
          $mail_type = 'multipart';
        }
        # �G�����L��G�����폜
        $body_plain = $emoji_sub_obj->delete_emoji_code($body_plain);
      }
    } elseif (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
      # SoftBank����
      # �G�����L������
      $ECOUNT = $emoji_sub_obj->emj_check($body_plain,'',$input_code);
      if ($ECOUNT['total'] > 0) {
        # HTML��
        if ($body_html == '') {
          $body_html = $body_plain;
          # �������ޕϊ�
          $dt = mb_detect_encoding($body_html,'auto');
          if ($dt != '') {
            if (mb_preferred_mime_name($dt) != mb_preferred_mime_name('UTF-8')) {
              $body_html = mb_convert_encoding($body_html,'UTF-8',$dt);
            }
            $body_html = preg_replace('/\r/','',$body_html);
            $body_html = preg_replace('/\n/',"<br>\n",$body_html);
            $body_html = "<html><head><meta http-equiv=\"content-type\" content=\"text/html;charset=UTF-8\"></head><body>\n{$body_html}\n</body></html>";
          }
          $BODYPLAIN    = $emoji_obj->emj_decode($body_plain,$to_career,$mail_code);
          $body_plain   = $BODYPLAIN['mail_plain'];
          $to_html_flag = True;
          $enc_code     = 'UTF-8';
          $mail_type    = 'multipart';
        }
      }
    } else {
      # �e��ر����(�G�����޺���)
      $BODYPLAIN  = $emoji_obj->emj_decode($body_plain,$to_career,$mail_code);
      $body_plain = $BODYPLAIN['mail'];
    }
    $body_plain = preg_replace('/\r/','',$body_plain);
    if (($body_plain != '') and !preg_match('/\n$/',$body_plain)) { $body_plain .= "\n"; }

    # �{������(HTML)
    # Ұّ��M�p�G�����ϊ�(�޺���)
    if (($to_career == '') or ($to_career == 'PC')) {
      # PC�y�ёS��ر�����̏ꍇ(�摜�ϊ�)
      $BODYHTML  = $emoji_obj->emj_decode($body_html,$to_career,$mail_code,1);
      $body_html = $BODYHTML['mail'];
    } else {
      # �e��ر����(�G�����޺���)
      if ($to_html_flag == True) {
        # SoftBank����HTMLҰٗp�G�����޺���
        $BODYPLAIN  = $emoji_obj->emj_decode($body_html,$to_career,'UTF-8');
        $body_html  = $BODYPLAIN['mail'];
      } else {
        # SoftBank����HTMLҰوȊO�p�G�����޺���
        $BODYHTML  = $emoji_obj->emj_decode($body_html,$to_career,$mail_code);
        $body_html = $BODYHTML['mail'];
      }
    }

    # �{��HTML������
    $body_html = preg_replace('/\r/','',$body_html);
    if (($body_html != '') and !preg_match('/\n$/',$body_html)) { $body_html .= "\n"; }

    # Base64�޺���
    if ($content_transfer_encoding == 'base64') {
      $body_plain = base64_encode($body_plain);
      $body_html  = base64_encode($body_html);
    }

    # �Y�ţ������
    $upfile_flag = False;
    $UPFILELIST  = array();
    if (isset($UPFILE)) {
      if (is_array($UPFILE)) {
        $no = 0;
        foreach ($UPFILE as $pathdt => $namedt) {
          if (isset($pathdt)) {
            if (file_exists($pathdt)) {
              # �Y�ţ�ُ��ݒ�
              $PATHDATA = pathinfo($pathdt);
              $UPFILELIST[$no]['path']      = $PATHDATA['dirname'];
              $UPFILELIST[$no]['extension'] = $PATHDATA['extension'];
              $UPFILELIST[$no]['mime']      = $this->get_mime_type($pathdt);
              # ̧�ٖ��ݒ�
              $UPFILELIST[$no]['basename']  = $PATHDATA['basename'];
              if (isset($namedt)) {
                if ($namedt == '') {
                  $UPFILELIST[$no]['basename'] = $PATHDATA['basename'];
                } else {
                  $UPFILELIST[$no]['basename'] = $namedt;
                }
              } else {
                $UPFILELIST[$no]['basename'] = $PATHDATA['basename'];
              }
              # ̧�ٓǍ���
              $fp    = fopen($pathdt,"r");
              $fdata = fread($fp,filesize($pathdt));
              fclose($fp);
              # �ݺ��ނ��ĕ���
              $UPFILELIST[$no]['filedata'] = chunk_split(base64_encode($fdata));
              $upfile_flag = True;
              $mail_type   = 'multipart/file';
              $no++;
            }
          }
        }
      }
    }

    # ����ͯ�ް����
    $add_mail_header       = '';
    $add_mail_header_smtp  = '';
    $add_mail_header      .= "From: ".$set_form."\n";
    $add_mail_header      .= "Reply-To: ".$set_repry_to."\n";
    if ($set_cc  != '') { $add_mail_header .= "Cc: ".$set_cc."\n"; }
    if ($set_bcc != '') { $add_mail_header .= "Bcc: ".$set_bcc."\n"; }
    $add_mail_header .= "MIME-Version: 1.0\n";
    $add_mail_header_smtp .= "MIME-Version: 1.0\n";

    # ͯ�ް����
    if (preg_match('/^multipart/',$mail_type)) {
      # ����߰�Ұ�(÷��+HTML,÷��+HTML+�Y�ţ��,÷��orHTML+�Y�ţ��)
      # �޳���ذ����(�߰Ă̋��E)
      $boundary = md5(uniqid(rand()));
      # ͯ�ް�ݒ�
      if ($mail_type == 'multipart') {
        # HTMLҰ�
        $add_mail_header      .= "Content-Type: multipart/alternative; boundary=\"".$boundary."\"\n";
        $add_mail_header_smtp .= "Content-Type: multipart/alternative; boundary=\"".$boundary."\"\n";
      } else {
        # �Y�ţ��
        $add_mail_header      .= "Content-Type: multipart/mixed; boundary=\"".$boundary."\"\n";
        $add_mail_header_smtp .= "Content-Type: multipart/mixed; boundary=\"".$boundary."\"\n";
      }
      $add_mail_header      .= "This is a multi-part message in MIME format.";
      $add_mail_header_smtp .= "This is a multi-part message in MIME format.";
    } elseif ($mail_type == 'plain') {
      $add_mail_header      .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
      $add_mail_header      .= "Content-Transfer-Encoding: ".$content_transfer_encoding;
      $add_mail_header_smtp .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
      $add_mail_header_smtp .= "Content-Transfer-Encoding: ".$content_transfer_encoding;
    } elseif ($mail_type == 'html') {
      $add_mail_header .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
      $add_mail_header .= "Content-Transfer-Encoding: ".$content_transfer_encoding;
      $add_mail_header_smtp .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
      $add_mail_header_smtp .= "Content-Transfer-Encoding: ".$content_transfer_encoding;
    }

    # �{������
    $msg = '';
    if (preg_match('/^multipart/',$mail_type)) {
      if (($body_plain != '') and ($body_html != '') and preg_match('/file/',$mail_type)) {
        # ����߰�Ұ�(÷��+HTML+�Y�ţ��)
        $boundary_2 = md5(uniqid(rand()));
        # �߰ċ�؂����
        $msg .= "--".$boundary."\n";
        $msg .= "Content-Type: multipart/alternative; boundary=\"".$boundary_2."\"\n";
        $msg .= "\n";
        # ÷�Ė{��
        $msg .= "--".$boundary_2."\n";
        $msg .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
        $msg .= "Content-Transfer-Encoding: ".$content_transfer_encoding."\n";
        $msg .= "\n";
        $msg .= $body_plain;
        $msg .= "\n";
        # HTML�{��
        $msg .= "--".$boundary_2."\n";
        $msg .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
        $msg .= "Content-Transfer-Encoding: ".$content_transfer_encoding."\n";
        $msg .= "\n";
        $msg .= $body_html;
        $msg .= "\n";
        # �߰ċ�؂�I��
        $msg .= "--".$boundary_2."--\n";
        # �Y�ţ��
        foreach ($UPFILELIST as $UDT) {
          $msg .= "--".$boundary."\n";
          $msg .= "Content-Type: ".$UDT['mime'].";\n";
          $msg .= "\tname=\"".$UDT['basename']."\"\n";
          $msg .= "Content-Transfer-Encoding: base64\n";
          $msg .= "Content-Disposition: attachment;\n";
          $msg .= "\tfilename=\"".$UDT['basename']."\"\n\n";
          $msg .= $UDT['filedata']."\n";
          $msg .= "\n";
        }

      } else {
        # ����߰�Ұ�(÷��+HTML,÷��orHTML+�Y�ţ��)
        if ($body_plain != '') {
          # ÷�Đݒ�
          $msg .= "--".$boundary."\n";
          $msg .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
          $msg .= "Content-Transfer-Encoding: ".$content_transfer_encoding."\n";
          $msg .= "\n";
          $msg .= $body_plain;
          $msg .= "\n";
        }
        if ($body_html != '') {
          # HTML�ݒ�
          $msg .= "--".$boundary."\n";
          $msg .= "Content-Type: text/html; charset=\"{$enc_code}\"\n";
          $msg .= "Content-Transfer-Encoding: ".$content_transfer_encoding."\n";
          $msg .= "\n";
          $msg .= $body_html;
          $msg .= "\n";
        }
        if ($upfile_flag == 1) {
          # �Y�ţ�ٗL��ꍇ
          foreach ($UPFILELIST as $UDT) {
            $msg .= "--".$boundary."\n";
            $msg .= "Content-Type: ".$UDT['mime'].";\n";
            $msg .= "\tname=\"".$UDT['basename']."\"\n";
            $msg .= "Content-Transfer-Encoding: base64\n";
            $msg .= "Content-Disposition: attachment;\n";
            $msg .= "\tfilename=\"".$UDT['basename']."\"\n\n";
            $msg .= $UDT['filedata']."\n";
            $msg .= "\n";
          }
        }
      }
      $msg .= "--".$boundary."--\n";
    } elseif ($mail_type == 'plain') {
      # ÷��Ұ�
      $msg .= $body_plain;
    } elseif ($mail_type == 'html') {
      # HTMLҰ�
      $msg .= $body_html;
    }
    # Ұّ��M
    if ((EMOJI_smtp_flag == 1) and is_object($smtp_obj)) {
      # SMTP���M
      # ���M���e�ݒ�
      $smtp_obj->TOLIST           = $TODATA;
      $smtp_obj->CCDATA           = $CCDATA;
      $smtp_obj->BCCDATA          = $BCCDATA;
      $smtp_obj->from_name        = $from_name;
      $smtp_obj->from_address     = $from_add;
      $smtp_obj->reply_to_name    = $repry_name;
      $smtp_obj->reply_to_address = $repry_to;
      $smtp_obj->return_path      = $return_path;
      $smtp_obj->add_header       = $add_mail_header_smtp;
      $smtp_obj->subject          = $subject;
      $smtp_obj->body             = $msg;
      # Ұّ��M
      $success = $smtp_obj->smtp_mail();
    } else {
      # PHP mail�֐����M
      // $success = @mail($set_to,$subject,$msg,$add_mail_header,'-f'.$return_path);
    }
    if ($success) { return True; } else { return False; }
  }

  # �G�����޺ڰ���Ұّ��M /////////////////////////////////////////////////////
  # �G�����޺ڰ���Ұق𑗐M���܂��B
  # [���n���l]
  # �@$MAIL_DATA['TODATA']                       : ���M��Ұٱ��ڽؽ�
  # �@�@$MAIL_DATA['TODATA'][*****]              : ����:���M��Ұٱ��ڽ�A�v�f(�l):���M�於
  # �@$MAIL_DATA['CCDATA']                       : ���M��Ұٱ��ڽؽ�(����ݺ�߰)
  # �@�@$MAIL_DATA['CCDATA'][*****]              : ����:���M��(����ݺ�߰)Ұٱ��ڽؽāA�v�f(�l):���M��(����ݺ�߰)��
  # �@$MAIL_DATA['BCCDATA']                      : �����Ұٱ��ڽ
  # �@�@$MAIL_DATA['BCCDATA'][*****]             : ����:�����Ұٱ��ڽؽāA�v�f(�l):����於
  # �@$MAIL_DATA['from_name']                    : ���M����
  # �@$MAIL_DATA['from_add']                     : ���M��Ұٱ��ڽ
  # �@$MAIL_DATA['repry_name']                   : �ԐM�於(�w�薳���ꍇ�͑��M����)
  # �@$MAIL_DATA['repry_to']                     : �ԐM��Ұٱ��ڽ(�w�薳���ꍇ�͑��M��Ұٱ��ڽ)
  # �@$MAIL_DATA['return_path']                  : �s�BҰّ��M����ڽ(�w�薳���ꍇ�͑��M��Ұٱ��ڽ)
  # �@$MAIL_DATA['subject']                      : ����
  # �@$MAIL_DATA['body_plain']                   : ÷�Ė{��
  # �@$MAIL_DATA['body_html']                    : HTML�{��
  # �@$SETTING_DATA['decome_mode']               : �޺Ҏw��(�w��Ȃ�:��ʑ��M�A'1':�޺ґ��M)
  # �@$SETTING_DATA['to_career']                 : ���M�淬ر(�w��Ȃ�:PC�y�ёS��ر�A'DoCoMo':DoCoMo�A'au':au�A'SoftBank':SoftBank(�G�����ϊ�ײ���؂Őݒ肵�����O))
  # �@$SETTING_DATA['content_transfer_encoding'] : Ұٴݺ��ިݸގw��(�w��Ȃ�����'7bit':��̫�Ė���7bit�A'base64':base64)
  # �@$SETTING_DATA['mail_code']                 : Ұٖ{���������ގw��(�w��Ȃ�����'JIS':JIS)
  # �@$SETTING_DATA['encode_pass']               : �ݺ��ޏ���������('1')
  # �@$SETTING_DATA['input_code']                : ���͕������ގw��(�w��Ȃ�:�ݒ�ɂ��AUTF-8����:UTF-8�A���̑�����:SJIS)
  # �@$UPFILE[*****]                             : ����:�Y�ţ���߽�A�v�f(�l):�Y�ţ�ٖ�
  # �@$katakana_chg_cancel       : ������{�����p���őS�p�ϊ���ݾ�(�w��Ȃ�:�����ϊ�,1:�ϊ���ݾ�)
  # [�Ԃ�l]
  # �@True : ���M�����AFalse : ���M���s
  #////////////////////////////////////////////////////////////////////////////
  function emoji_decome($MAIL_DATA,$SETTING_DATA,$UPFILE,$katakana_chg_cancel='') {
    global $decome_obj;

    if (is_object($decome_obj)) {
      # ��޼ު�Ă��쐬����Ă���ꍇ
      return $decome_obj->emoji_decome($MAIL_DATA,$SETTING_DATA,$UPFILE,$katakana_chg_cancel);
    } else {
      # ��޼ު�Ă��쐬����Ă��Ȃ��ꍇ
      return False;
    }
  }

  # �G�����޺ڰ���Ұّ��M2(emoji_send_mail3�֐��ƌ݊���) //////////////////////
  # �G�����޺ڰ���Ұق𑗐M���܂��B
  # [���n���l]
  # �@$TODATA[*****]             : ����:���M��Ұٱ��ڽ�A�v�f(�l):���M�於
  # �@$CCDATA[*****]             : ����:���M��(����ݺ�߰)Ұٱ��ڽؽāA�v�f(�l):���M��(����ݺ�߰)��
  # �@$BCCDATA[*****]            : ����:�����Ұٱ��ڽؽāA�v�f(�l):����於
  # �@$from_name                 : ���M����
  # �@$from_add                  : ���M��Ұٱ��ڽ
  # �@$subject                   : ����
  # �@$body_plain                : ÷�Ė{��
  # �@$body_html                 : HTML�{��
  # �@$repry_name                : �ԐM�於(�w�薳���ꍇ�͑��M����)
  # �@$repry_to                  : �ԐM��Ұٱ��ڽ(�w�薳���ꍇ�͑��M��Ұٱ��ڽ)
  # �@$return_path               : �s�BҰّ��M����ڽ(�w�薳���ꍇ�͑��M��Ұٱ��ڽ)
  # �@$to_career                 : ���M�淬ر(�w��Ȃ�:PC�y�ёS��ر�A'DoCoMo':DoCOMo�A'au':au�A'SoftBank':SoftBank)
  # �@$content_transfer_encoding : Ұٴݺ��ިݸގw��(�w��Ȃ�����'7bit':��̫�Ė���7bit�A'base64':base64)
  # �@$mail_code                 : Ұٖ{���������ގw��(�w��Ȃ�����'JIS':JIS)
  # �@$UPFILE[*****]             : ����:�Y�ţ���߽�A�v�f(�l):�Y�ţ�ٖ�
  # �@$encode_pass               : �ݺ��ޏ���������('1')
  # �@$input_code                : ���͕������ގw��(�w��Ȃ�:�ݒ�ɂ��AUTF-8����:UTF-8�A���̑�����:SJIS)
  # �@$decome_mode               : �޺Ҏw��(�w��Ȃ�:��ʑ��M(emoji_send_mail3�֐��Ɠ����̏����ƂȂ�܂�)�A'1':�޺ґ��M)
  # �@$katakana_chg_cancel       : ������{�����p���őS�p�ϊ���ݾ�(�w��Ȃ�:�����ϊ�,1:�ϊ���ݾ�)
  # [�Ԃ�l]
  # �@True : ���M�����AFalse : ���M���s
  #////////////////////////////////////////////////////////////////////////////
  function emoji_decome2($TODATA,$CCDATA,$BCCDATA,$from_name,$from_add,$subject,$body_plain,$body_html,$repry_name='',$repry_to='',$return_path='',$to_career='',$content_transfer_encoding='',$mail_code='JIS',$UPFILE='',$encode_pass='',$input_code='',$decome_mode='1',$katakana_chg_cancel='') {
    global $decome_obj;

    if (is_object($decome_obj)) {
      # ��޼ު�Ă��쐬����Ă���ꍇ
      # �l���
      $MAIL_DATA    = array();
      $SETTING_DATA = array();
      $MAIL_DATA['TODATA']                       = $TODATA;
      $MAIL_DATA['CCDATA']                       = $CCDATA;
      $MAIL_DATA['BCCDATA']                      = $BCCDATA;
      $MAIL_DATA['from_name']                    = $from_name;
      $MAIL_DATA['from_add']                     = $from_add;
      $MAIL_DATA['repry_name']                   = $repry_name;
      $MAIL_DATA['repry_to']                     = $repry_to;
      $MAIL_DATA['return_path']                  = $return_path;
      $MAIL_DATA['subject']                      = $subject;
      $MAIL_DATA['body_plain']                   = $body_plain;
      $MAIL_DATA['body_html']                    = $body_html;
      $SETTING_DATA['to_career']                 = $to_career;
      $SETTING_DATA['content_transfer_encoding'] = $content_transfer_encoding;
      $SETTING_DATA['mail_code']                 = $mail_code;
      $SETTING_DATA['encode_pass']               = $encode_pass;
      $SETTING_DATA['input_code']                = $input_code;
      $SETTING_DATA['decome_mode']               = $decome_mode;
      return $decome_obj->emoji_decome($MAIL_DATA,$SETTING_DATA,$UPFILE,$katakana_chg_cancel);
    } else {
      # ��޼ު�Ă��쐬����Ă��Ȃ��ꍇ
      return False;
    }
  }

  # ̧��MIME�擾���� ////////////////////////////////////////////////
  # ̧�ق̊g���q����̧��MIME���擾���܂��B
  # [���n���l]
  # �@$filename : ̧�ٖ�
  # [�Ԃ�l]
  # �@$mime : ̧��MIME
  #////////////////////////////////////////////////////////////////////////////
  function get_mime_type($filename) {
    global $emoji_obj;

    $mime = 'application/octet-stream';
    $PATHDATA  = pathinfo($filename);
    if (isset($PATHDATA['extension'])) {
      if ($PATHDATA['extension'] != '') {
        $extension = $PATHDATA['extension'];
        if (isset($emoji_obj->FILETYPE[$PATHDATA['extension']])) { $mime = $emoji_obj->FILETYPE[$PATHDATA['extension']]; }
      }
    }
    return $mime;
  }

}

?>