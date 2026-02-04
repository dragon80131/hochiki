<?php

###############################################################################
# �G�����޺ڰ���Ұُ����׽
# Potora/inaken(C) 2003-2007.
# MAIL: support@potora.dip.jp
# URL : http://potora.dip.jp/
###############################################################################
# ���{ײ���؂́y�G�����ϊ�ײ����2007/2008�z�̻��ײ����(�q�׽�ł͂Ȃ�)�ɂȂ�܂��B
# �@�P�Ƃł̎g�p�͂ł��܂���B
###############################################################################
$decome_ver = 'deco_v.1.00.00'; # 2007.12.14 �V�K�쐬
$decome_ver = 'deco_v.1.00.01'; # 2007.12.16 ��ײ݉摜�擾�s���C��
$decome_ver = 'deco_v.1.00.02'; # 2007.12.19 �{�����������s��C��
$decome_ver = 'deco_v.1.00.03'; # 2007.12.23 �Ȉ������@�\�ǉ��A�װ�����C��
$decome_ver = 'deco_v.1.00.04'; # 2008.07.17 SoftBank���ĊG����ҰّΉ�
$decome_ver = 'deco_v.1.00.05'; # 2008.07.28 ������{�����p���őS�p�ϊ���ݾً@�\�ǉ�
$decome_ver = 'deco_v.2.00.00'; # 2008.10.20 SMTP�ڑ�Ұّ��M�@�\�ǉ�
$decome_ver = 'deco_v.2.00.01'; # 2008.10.20 SMTP���M��CC,BCC�w��s��C��
$decome_ver = 'deco_v.2.00.02'; # 2008.11.02 �G����Ұّ��M�s��C��
$decome_ver = 'deco_v.2.00.03'; # 2008.11.28 ��۰��ٕϐ������ύX
$decome_ver = 'deco_v.2.00.04'; # 2009.02.24 ��޼ު�Ďw��s��C��
$decome_ver = 'deco_v.2.01.00'; # 2009.03.09 SoftBank���Ė{�������s��C��
$decome_ver = 'deco_v.2.01.01'; # 2009.03.10 SoftBank����HTML�{�������s��C��
$decome_ver = 'deco_v.2.01.02'; # 2009.03.11 au,SoftBank�޺ҏ����s��C��
$decome_ver = 'deco_v.2.01.03'; # 2009.03.12 �ް�ޮݑ���ɂ���޼ު�ČĂяo���s��C��
$decome_ver = 'deco_v.2.01.04'; # 2009.03.23 �G�����摜��Ď����č폜�ǉ�
$decome_ver = 'deco_v.2.01.05'; # 2009.03.25 SB�g�ш��ĊG����Ұِ�����HTML�����s��C��
$decome_ver = 'deco_v.2.01.06'; # 2009.04.13 ÷��Ұ�HTML����URL�Ұٱ��ڽ�ݸ��
$decome_ver = 'deco_v.2.01.07'; # 2009.04.15 SoftBankHTMLҰ�ͯ�ް�C��
$decome_ver = 'deco_v.2.01.08'; # 2009.05.13 PC�pͯ�ް�C��
$decome_ver = 'deco_v.2.01.09'; # 2009.06.07 PC�p�޺ҏ����C��
$decome_ver = 'deco_v.2.01.10'; # 2009.07.07 �ԐM��w��C��,�������ޏ����s��C��
$decome_ver = 'deco_v.2.01.11'; # 2009.07.08 PC����Ұ������(No.9)�s����C��
$decome_ver = 'deco_v.2.01.12'; # 2009.07.16 PHP5 quoted_printable_encode�֐��d���Ή�
$decome_ver = 'deco_v.2.01.13'; # 2009.07.23 �������������s��C��
$decome_ver = 'deco_v.2.01.14'; # 2009.07.29 Form���w�莞�s��C��
$decome_ver = 'deco_v.2.01.15'; # 2010.01.29 �޺�Ӱ�ވȊOҰٻ�������������
###############################################################################

define('DECOME_DEBUG_MODE',False);
#define('DECOME_DEBUG_MODE',True);

class decome {

  # �޺�Ӱ�ޗL������
  # �@True :�L��-�����w��ɏ]���܂�
  # �@False:����-�����w��𖳎������������܂�
  var $decome_flag = True;
  # SoftBank���đ��M�������w��
  # �@0:��ײ݉摜���M���Ȃ�(HTMLӰ��)
  # �@1:��ײ݉摜�ő��M(�޺�Ӱ��)
  var $softbank_inline = 0;

  # PC�p�����ݒ�
  var $inline_max_num_pc       = 0;    # ��ײ݉摜������
  var $inline_max_size_pc      = 0;    # ��ײ݉摜̧�ٻ��ސ���(1̧�ٍő廲��)(Byte)
  var $inline_all_max_size_pc  = 0;    # ��ײ݉摜İ��̧�ٻ��ސ���(Byte)
  var $upfile_max_num_pc       = 0;    # �Y�ţ�ِ�����
  var $upfile_max_size_pc      = 0;    # �Y�ţ�ٻ��ސ���(1̧�ٍő廲��)(Byte)
  var $upfile_all_max_size_pc  = 0;    # �Y�ţ��İ�ٻ��ސ���(Byte)
  var $allfile_max_num_pc      = 0;    # ��ײ݉摜�A�Y�ţ��İ�ِ�����
  var $allfile_max_size_pc     = 0;    # ��ײ݉摜�A�Y�ţ��İ�ٻ��ސ���(Byte)
  var $body_max_size_pc        = 0;    # �{��(÷��+HTML)İ�ٻ��ސ���(Byte)
  var $body_all_max_size_pc    = 0;    # İ�ٻ��ސ���(Byte)

  # DoCoMo�p�����ݒ�
  var $inline_max_num_docomo       = 10;         # ��ײ݉摜������
  var $inline_max_size_docomo      = 10000;      # ��ײ݉摜̧�ٻ��ސ���(1̧�ٍő廲��)(Byte)
  var $inline_all_max_size_docomo  = 0;          # ��ײ݉摜İ��̧�ٻ��ސ���(Byte)
  var $upfile_max_num_docomo       = 0;          # �Y�ţ�ِ�����
  var $upfile_max_size_docomo      = 0;          # �Y�ţ�ٻ��ސ���(1̧�ٍő廲��)(Byte)
  var $upfile_all_max_size_docomo  = 10240;      # �Y�ţ��İ�ٻ��ސ���(Byte)
  var $allfile_max_num_docomo      = 0;          # ��ײ݉摜�A�Y�ţ��İ�ِ�����
  var $allfile_max_size_docomo     = 0;          # ��ײ݉摜�A�Y�ţ��İ�ٻ��ސ���(Byte)
  var $body_max_size_docomo        = 10240;      # �{��(÷��+HTML)İ�ٻ��ސ���(Byte)
  var $body_all_max_size_docomo    = 1002400;    # İ�ٻ��ސ���(Byte)

  # au�p�����ݒ�
  var $inline_max_num_au       = 10;        # ��ײ݉摜������
  var $inline_max_size_au      = 0;         # ��ײ݉摜̧�ٻ��ސ���(1̧�ٍő廲��)(Byte)
  var $inline_all_max_size_au  = 0;         # ��ײ݉摜İ��̧�ٻ��ސ���(Byte)
  var $upfile_max_num_au       = 0;         # �Y�ţ�ِ�����
  var $upfile_max_size_au      = 0;         # �Y�ţ�ٻ��ސ���(1̧�ٍő廲��)(Byte)
  var $upfile_all_max_size_au  = 102400;    # �Y�ţ��İ�ٻ��ސ���(Byte)
  var $allfile_max_num_au      = 0;         # ��ײ݉摜�A�Y�ţ��İ�ِ�����
  var $allfile_max_size_au     = 0;         # ��ײ݉摜�A�Y�ţ��İ�ٻ��ސ���(Byte)
  var $body_max_size_au        = 10000;     # �{��(÷��+HTML)İ�ٻ��ސ���(Byte)
  var $body_all_max_size_au    = 150000;    # İ�ٻ��ސ���(Byte)

  # SoftBank�p�����ݒ�
  var $inline_max_num_softbank       = 0;         # ��ײ݉摜������
  var $inline_max_size_softbank      = 0;         # ��ײ݉摜̧�ٻ��ސ���(1̧�ٍő廲��)(Byte)
  var $inline_all_max_size_softbank  = 0;         # ��ײ݉摜İ��̧�ٻ��ސ���(Byte)
  var $upfile_max_num_softbank       = 0;         # �Y�ţ�ِ�����
  var $upfile_max_size_softbank      = 0;         # �Y�ţ�ٻ��ސ���(1̧�ٍő廲��)(Byte)
  var $upfile_all_max_size_softbank  = 0;         # �Y�ţ��İ�ٻ��ސ���(Byte)
  var $allfile_max_num_softbank      = 0;         # ��ײ݉摜�A�Y�ţ��İ�ِ�����
  var $allfile_max_size_softbank     = 307200;    # ��ײ݉摜�A�Y�ţ��İ�ٻ��ސ���(Byte)
  var $body_max_size_softbank        = 0;         # �{��(÷��+HTML)İ�ٻ��ސ���(Byte)
  var $body_all_max_size_softbank    = 0;         # İ�ٻ��ސ���(Byte)

  # �װ�ݒ�
  var $error_flag        = False;
  var $error_code        = 0;
  var $error_coment      = '';
  var $file_error_flag   = False;
  var $file_error_code   = 0;
  var $file_error_coment = '';

  var $set_decome = '';

  # �ݽ�׸� ///////////////////////////////////////////////////////////////////
  # [���n���l]
  # �@�Ȃ�
  # [�Ԃ�l]
  # �@�Ȃ�
  #////////////////////////////////////////////////////////////////////////////
  function decome() {

  }

  # �G�����ϊ�ײ�����ް�ޮݎ擾 ///////////////////////////////////////////////
  # ��ر���ʂƋ@������擾���܂��B(�V����->����)
  # [���n���l]
  # �@�Ȃ�
  # [�Ԃ�l]
  # �@$this->ver : ײ�����ް�ޮ�
  #////////////////////////////////////////////////////////////////////////////
  function Get_Emj_Version() {
    global $decome_ver;
    return $decome_ver;
  }

  # �G�����޺ڰ���Ұّ��M(mail�֐����M) ///////////////////////////////////////
  # �޺ڰ���Ұ�(�G����)�𑗐M���܂��B
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
  # �@$katakana_chg_cancel                       : ������{�����p���őS�p�ϊ���ݾ�(�w��Ȃ�:�����ϊ�,1:�ϊ���ݾ�)
  # [�Ԃ�l]
  # �@True : ���M�����AFalse : ���M���s
  #////////////////////////////////////////////////////////////////////////////
  function emoji_decome($MAIL_DATA,$SETTING_DATA,$UPFILE,$katakana_chg_cancel='') {
    global $smtp_obj;

    # �װ������
    $this->error_flag   = False;
    $this->error_code   = 0;
    $this->error_coment = '';

    # �����ݒ�
    if (!isset($MAIL_DATA['from_add'])) {
      $this->error_flag   = True;
      $this->error_code   = 100;
      $this->error_coment = 'No Form Address.';
      return False;
    }
    if (!isset($MAIL_DATA['from_name']))                    { $MAIL_DATA['from_name']                    = ''; }
    if (!isset($MAIL_DATA['from_add']))                     { $MAIL_DATA['from_add']                     = ''; }
    if (!isset($MAIL_DATA['repry_name']))                   { $MAIL_DATA['repry_name']                   = ''; }
    if (!isset($MAIL_DATA['repry_to']))                     { $MAIL_DATA['repry_to']                     = ''; }
    if (!isset($MAIL_DATA['return_path']))                  { $MAIL_DATA['return_path']                  = ''; }
    if (!isset($MAIL_DATA['subject']))                      { $MAIL_DATA['subject']                      = ''; }
    if (!isset($MAIL_DATA['body_plain']))                   { $MAIL_DATA['body_plain']                   = ''; }
    if (!isset($MAIL_DATA['body_html']))                    { $MAIL_DATA['body_html']                    = ''; }
    if (!isset($SETTING_DATA['decome_mode']))               { $SETTING_DATA['decome_mode']               = ''; }
    if (!isset($SETTING_DATA['to_career']))                 { $SETTING_DATA['to_career']                 = 'PC'; }
    if (!isset($SETTING_DATA['content_transfer_encoding'])) { $SETTING_DATA['content_transfer_encoding'] = ''; }
    if (!isset($SETTING_DATA['mail_code']))                 { $SETTING_DATA['mail_code']                 = 'JIS'; }
    if (!isset($SETTING_DATA['encode_pass']))               { $SETTING_DATA['encode_pass']               = ''; }
    if (!isset($SETTING_DATA['input_code']))                { $SETTING_DATA['input_code']                = ''; }

    $this->set_decome = $SETTING_DATA['decome_mode'];

    # Ұ��ް�����
    $MAIL = $this->make_mail_data($MAIL_DATA,$SETTING_DATA,$UPFILE,$katakana_chg_cancel);

    # Debug Mode
    if (DECOME_DEBUG_MODE == True) {
      header('Content-Type: text/plain; charset=ISO-2022-JP');
      print "Error  =>".$MAIL['error']."\n";
      print "To     =>".$MAIL['set_to']."\n";
      print "Return =>".$MAIL['return_path']."\n";
      print "Subject=>".$MAIL['subject']."\n";
      print "header =>".$MAIL['add_mail_header']."\n";
      print "Body=>\n";
      print $MAIL['mail_body'];
      exit();
    }

    # Ұّ��M
    if ($MAIL['error'] == False) {
      if ((EMOJI_smtp_flag == 1) and is_object($smtp_obj)) {
        # SMTP���M
        # ���M���e�ݒ�
        $smtp_obj->TOLIST           = $MAIL_DATA['TODATA'];
        $smtp_obj->CCLILST          = $MAIL_DATA['CCDATA'];
        $smtp_obj->BCCLIST          = $MAIL_DATA['BCCDATA'];
        $smtp_obj->from_name        = $MAIL_DATA['from_name'];
        $smtp_obj->from_address     = $MAIL_DATA['from_add'];
        $smtp_obj->reply_to_name    = $MAIL_DATA['repry_name'];
        $smtp_obj->reply_to_address = $MAIL_DATA['repry_to'];
        $smtp_obj->return_path      = $MAIL['return_path'];
        $smtp_obj->add_header       = $MAIL['add_mail_header'];
        $smtp_obj->subject          = $MAIL['subject'];
        $smtp_obj->body             = $MAIL['mail_body'];
        # Ұّ��M
        $success = $smtp_obj->smtp_mail();
      } else {
        # PHP mail�֐����M
        if ($MAIL['return_path'] != '') {
          // $success = @mail($MAIL['set_to'],$MAIL['subject'],$MAIL['mail_body'],$MAIL['add_mail_header'],'-f'.$MAIL['return_path']);
        } else {
          // $success = @mail($MAIL['set_to'],$MAIL['subject'],$MAIL['mail_body'],$MAIL['add_mail_header']);
        }
      }
#      if (@mail($MAIL['set_to'],$MAIL['subject'],$MAIL['mail_body'],$MAIL['add_mail_header'],'-f'.$MAIL['return_path'])) {
      if ($success == True) {
        return True;
      } else {
        $this->error_flag   = True;
        $this->error_code   = 101;
        $this->error_coment = 'Mail Send Error.';
        return False;
      }
    } else {
      $this->error_flag   = True;
#      $this->error_coment = 'Mail Data Make Error.';
      return False;
    }

  }

  # �G�����޺ڰ���Ұ��ް����� /////////////////////////////////////////////////
  # �޺ڰ���Ұ�(�G����)�̑��M�ް��𐶐����܂��B
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
  # �@$katakana_chg_cancel                       : ������{�����p���őS�p�ϊ���ݾ�(�w��Ȃ�:�����ϊ�,1:�ϊ���ݾ�)
  # [�Ԃ�l]
  # �@$MAIL                      : Ұِ����ް�
  # �@�@$MAIL['error']           : �װ�׸�(True:�װ�L��AFalse:�װ����)
  # �@�@$MAIL['set_to']          : ���M���ް�(To)
  # �@�@$MAIL['return_path']     : �s�BҰٱ��ڽ
  # �@�@$MAIL['subject']         : ����
  # �@�@$MAIL['add_mail_header'] : Ұْǉ�ͯ�ް
  # �@�@$MAIL['mail_body']       : Ұٖ{��
  #////////////////////////////////////////////////////////////////////////////
  function make_mail_data($MAIL_DATA,$SETTING_DATA,$UPFILE,$katakana_chg_cancel='') {
    global $emoji_obj,$emoji_mail_obj;

    # �װ������
    $this->error_flag   = False;
    $this->error_code   = 0;
    $this->error_coment = '';

    # �����ݒ�
    $MAIL = array();
    if (!isset($MAIL_DATA['from_add'])) {
      $this->error_flag   = True;
      $this->error_code   = 200;
      $this->error_coment = 'No From Address.';
      $MAIL['error']      = True;
      return $MAIL;
    }
    if (!isset($MAIL_DATA['from_name']))                    { $MAIL_DATA['from_name']                    = ''; }
    if (!isset($MAIL_DATA['from_add']))                     { $MAIL_DATA['from_add']                     = ''; }
    if (!isset($MAIL_DATA['repry_name']))                   { $MAIL_DATA['repry_name']                   = ''; }
    if (!isset($MAIL_DATA['repry_to']))                     { $MAIL_DATA['repry_to']                     = ''; }
    if (!isset($MAIL_DATA['return_path']))                  { $MAIL_DATA['return_path']                  = ''; }
    if (!isset($MAIL_DATA['subject']))                      { $MAIL_DATA['subject']                      = ''; }
    if (!isset($MAIL_DATA['body_plain']))                   { $MAIL_DATA['body_plain']                   = ''; }
    if (!isset($MAIL_DATA['body_html']))                    { $MAIL_DATA['body_html']                    = ''; }
    if (!isset($SETTING_DATA['decome_mode']))               { $SETTING_DATA['decome_mode']               = ''; }
    if (!isset($SETTING_DATA['to_career']))                 { $SETTING_DATA['to_career']                 = 'PC'; }
    if (!isset($SETTING_DATA['content_transfer_encoding'])) { $SETTING_DATA['content_transfer_encoding'] = ''; }
    if (!isset($SETTING_DATA['mail_code']))                 { $SETTING_DATA['mail_code']                 = 'JIS'; }
    if (!isset($SETTING_DATA['encode_pass']))               { $SETTING_DATA['encode_pass']               = ''; }
    if (!isset($SETTING_DATA['input_code']))                { $SETTING_DATA['input_code']                = ''; }

    # Ӱ�ސݒ�
    if ($this->decome_flag         == False) { $SETTING_DATA['decome_mode'] = ''; }
#    if ($SETTING_DATA['to_career'] == 'PC')  { $SETTING_DATA['decome_mode'] = ''; }
#    # SoftBank�pӰ�ސݒ�
#    if (($SETTING_DATA['to_career'] == 'SoftBank') or ($SETTING_DATA['to_career'] == $emoji_obj->softbank_name)) { $SETTING_DATA['decome_mode'] = ''; }

    # ���M������
    $to_flag  = False;
    $cc_flag  = False;
    $bcc_flag = False;
    $flag     = False;
    if (isset($MAIL_DATA['TODATA'])) {
      if (is_array($MAIL_DATA['TODATA'])) {
        if (isset($MAIL_DATA['TODATA'])) { $flag = True; $to_flag = True; }
      }
    }
    # ���M������
    if (isset($MAIL_DATA['CCDATA'])) {
      if (is_array($MAIL_DATA['CCDATA'])) {
        if (isset($MAIL_DATA['CCDATA'])) { $flag = True; $cc_flag = True; }
      }
    }
    # ���񑗐M����
    if (isset($MAIL_DATA['BCCDATA'])) {
      if (is_array($MAIL_DATA['BCCDATA'])) {
        if (isset($MAIL_DATA['BCCDATA'])) { $flag = True; $bcc_flag = True; }
      }
    }
    if ($flag == False) {
      $this->error_flag   = True;
      $this->error_flag   = 201;
      $this->error_coment = 'To or CC or BCC Address Set Error.';
      return False;
    }

#    # �ԐM�於����
#    if ($MAIL_DATA['repry_name']  == '') { $MAIL_DATA['repry_name']  = $MAIL_DATA['from_name']; }
#    # �ԐM�於����
#    if ($MAIL_DATA['repry_to']    == '') { $MAIL_DATA['repry_to']    = $MAIL_DATA['from_add']; }
#    # �s�BҰٖ߂������
#    if ($MAIL_DATA['return_path'] == '') { $MAIL_DATA['return_path'] = $MAIL_DATA['from_add']; }

    # �{������
    if (($MAIL_DATA['body_plain'] == '') and ($MAIL_DATA['body_html'] == '')) {
      $this->error_flag   = True;
      $this->error_flag   = 202;
      $this->error_coment = 'No Body Data.';
      return False;
    }

    # �Y�ţ������
    $upfile_flag = False;
    if (isset($UPFILE)) {
      if (is_array($UPFILE)) {
        foreach ($UPFILE as $pathdt => $namedt) {
          if (isset($pathdt)) {
            if (file_exists($pathdt)) { $upfile_flag = True; break; }
          }
        }
      }
    }

    # ���M�ݺ��ސݒ�
    if ($SETTING_DATA['content_transfer_encoding'] == '') {
      if (isset($emoji_obj->cont_trs_enc)) {
        if ($emoji_obj->cont_trs_enc == '') {
          $SETTING_DATA['content_transfer_encoding'] = $emoji_obj->cont_trs_enc;
        } else {
          $SETTING_DATA['content_transfer_encoding'] = '7bit';
        }
      } else {
        $SETTING_DATA['content_transfer_encoding'] = '7bit';
      }
    }

    # ���M��(To��)����
    $sp     = '';
    $set_to = '';
    $no     = 0;
    $set_career  = '';
    $career_flag = True;
    if ($to_flag == True) {
      foreach ($MAIL_DATA['TODATA'] as $adddt => $namedt) {
        if ($namedt != '') {
          # ���M�於�̎w�肪����ꍇ
          $set_to_sub = '';
          if ($SETTING_DATA['input_code'] != '') {
            $str_code = mb_detect_encoding($namedt,$emoji_obj->ENCODINGLIST[$SETTING_DATA['input_code']]);
          } else {
            $str_code = mb_detect_encoding($namedt,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
          }
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
        # ��ر����
        $check_career = $emoji_mail_obj->get_mail_career($adddt);
        if ($no == 0) {
          $set_career = $check_career;
        } else {
          if ($set_career != $check_career) { $career_flag = False; }
        }
        $sp = ',';
        $no++;
      }
    }
    if (($SETTING_DATA['to_career'] == '') and ($career_flag == True)) {
      $SETTING_DATA['to_career'] = $set_career;
    }

    # ���M��(CC��)����
    $sp     = '';
    $set_cc = '';
    if ($cc_flag == True) {
      foreach ($MAIL_DATA['CCDATA'] as $adddt => $namedt) {
        if ($namedt != '') {
          # ���M�於�̎w�肪����ꍇ
          $set_cc_sub = '';
          if ($SETTING_DATA['input_code'] != '') {
            $str_code = mb_detect_encoding($namedt,$emoji_obj->ENCODINGLIST[$SETTING_DATA['input_code']]);
          } else {
            $str_code = mb_detect_encoding($namedt,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
          }
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
      foreach ($MAIL_DATA['BCCDATA'] as $adddt => $namedt) {
        if ($namedt != '') {
          # ����於�̎w�肪����ꍇ
          $set_bcc_sub = '';
          if ($SETTING_DATA['input_code'] != '') {
            $str_code = mb_detect_encoding($namedt,$emoji_obj->ENCODINGLIST[$SETTING_DATA['input_code']]);
          } else {
            $str_code = mb_detect_encoding($namedt,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
          }
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
    if ($MAIL_DATA['from_name'] != '') {
      if ($SETTING_DATA['input_code'] != '') {
        $str_code = mb_detect_encoding($MAIL_DATA['from_name'],$emoji_obj->ENCODINGLIST[$SETTING_DATA['input_code']]);
      } else {
        $str_code = mb_detect_encoding($MAIL_DATA['from_name'],$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
      }
      if ($str_code == 'JIS') {
        $set_form = $MAIL_DATA['from_name'];
      } else {
        $set_form = @mb_convert_encoding($MAIL_DATA['from_name'],'JIS',$str_code);
      }
      $set_form  = mb_convert_kana($set_form,'KV','JIS');
      $set_form  = mb_encode_mimeheader($set_form,'JIS');
      $set_form .= ' <'.$MAIL_DATA['from_add'].'>';
    } else {
      $set_form = $MAIL_DATA['from_add'];
    }

    # �ԐM��(Repry_to��)����
    $set_repry_to = '';
    if ($MAIL_DATA['repry_to'] != '') {
      if ($MAIL_DATA['repry_name'] != '') {
        if ($SETTING_DATA['input_code'] != '') {
          $str_code = mb_detect_encoding($MAIL_DATA['repry_name'],$emoji_obj->ENCODINGLIST[$SETTING_DATA['input_code']]);
        } else {
          $str_code = mb_detect_encoding($MAIL_DATA['repry_name'],$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
        }
        if ($str_code == 'JIS') {
          $set_repry_to  = $MAIL_DATA['repry_name'];
        } else {
          $set_repry_to  = @mb_convert_encoding($MAIL_DATA['repry_name'],'JIS',$str_code);
        }
        $set_repry_to  = mb_convert_kana($set_repry_to,'KV','JIS');
        $set_repry_to  = mb_encode_mimeheader($set_repry_to,'JIS');
        $set_repry_to .= " <".$MAIL_DATA['repry_to'].">";
      } else {
        $set_repry_to = $MAIL_DATA['repry_to'];
      }
    }

    # Ұّ��M�p�G�����ϊ�(�ݺ��ށ�SJIS�ϊ�)
    if ($SETTING_DATA['encode_pass'] != '1') {
      $MAIL_DATA['subject']    = $emoji_obj->emj_encode($MAIL_DATA['subject']   ,'SJIS','',$SETTING_DATA['input_code']);
      $MAIL_DATA['body_plain'] = $emoji_obj->emj_encode($MAIL_DATA['body_plain'],'SJIS','',$SETTING_DATA['input_code']);
      $MAIL_DATA['body_html']  = $emoji_obj->emj_encode($MAIL_DATA['body_html'] ,'SJIS','',$SETTING_DATA['input_code']);
      $SETTING_DATA['input_code'] = 'SJIS';
    }

    # �������ގ擾
    if ($SETTING_DATA['input_code'] != '') {
#      $subject_code    = mb_detect_encoding($MAIL_DATA['subject']   ,$emoji_obj->ENCODINGLIST[$SETTING_DATA['input_code']]);
#      $body_plain_code = mb_detect_encoding($MAIL_DATA['body_plain'],$emoji_obj->ENCODINGLIST[$SETTING_DATA['input_code']]);
#      $body_html_code  = mb_detect_encoding($MAIL_DATA['body_html'] ,$emoji_obj->ENCODINGLIST[$SETTING_DATA['input_code']]);
      $subject_code    = $SETTING_DATA['input_code'];
      $body_plain_code = $SETTING_DATA['input_code'];
      $body_html_code  = $SETTING_DATA['input_code'];
    } else {
      $subject_code    = mb_detect_encoding($MAIL_DATA['subject']   ,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
      $body_plain_code = mb_detect_encoding($MAIL_DATA['body_plain'],$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
      $body_html_code  = mb_detect_encoding($MAIL_DATA['body_html'] ,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
    }
    if ($subject_code    != '') { $subject_code    = mb_preferred_mime_name($subject_code); }
    if ($body_plain_code != '') { $body_plain_code = mb_preferred_mime_name($body_plain_code); }
    if ($body_html_code  != '') { $body_html_code  = mb_preferred_mime_name($body_html_code); }

    # ��������
    if ($MAIL_DATA['subject'] == '') { $MAIL_DATA['subject'] = @mb_convert_encoding('����',$subject_code,'SJIS'); }
    # �G�����ϊ�(�޺���)
    if (($SETTING_DATA['to_career'] == '') or ($SETTING_DATA['to_career'] == 'PC')) {
      # PC�y�ёS��ر�����̏ꍇ(�G�����폜)
      $MAIL_DATA['subject'] = $emoji_obj->delete_emoji_code($MAIL_DATA['subject'],'0','0','0',$subject_code,'',$subject_code);
    } elseif (($SETTING_DATA['to_career'] == 'Vodafone') or ($SETTING_DATA['to_career'] == 'SoftBank')) {
      # SoftBank3G�����̏ꍇ(�G�����폜)
      $MAIL_DATA['subject'] = $emoji_obj->delete_emoji_code($MAIL_DATA['subject'],'0','0','0',$subject_code,'',$subject_code);
    } else {
      # �e��ر����(�G�����޺���)
      $SUBJECT = $emoji_obj->emj_decode($MAIL_DATA['subject'],$SETTING_DATA['to_career'],$subject_code);
      $MAIL_DATA['subject'] = $SUBJECT['mail'];
    }

    # �������ޕϊ�
    if ($subject_code    != mb_preferred_mime_name($SETTING_DATA['mail_code'])) { $MAIL_DATA['subject']    = @mb_convert_encoding($MAIL_DATA['subject']   ,$SETTING_DATA['mail_code'],$subject_code); }
    if ($body_plain_code != mb_preferred_mime_name($SETTING_DATA['mail_code'])) { $MAIL_DATA['body_plain'] = @mb_convert_encoding($MAIL_DATA['body_plain'],$SETTING_DATA['mail_code'],$body_plain_code); }
    if ($body_html_code  != mb_preferred_mime_name($SETTING_DATA['mail_code'])) { $MAIL_DATA['body_html']  = @mb_convert_encoding($MAIL_DATA['body_html'] ,$SETTING_DATA['mail_code'],$body_html_code); }

    # ���ŕϊ�
    if ($katakana_chg_cancel == '') {
      $MAIL_DATA['subject']    = mb_convert_kana($MAIL_DATA['subject']   ,'KV',$SETTING_DATA['mail_code']);
      $MAIL_DATA['body_plain'] = mb_convert_kana($MAIL_DATA['body_plain'],'KV',$SETTING_DATA['mail_code']);
      $MAIL_DATA['body_html']  = mb_convert_kana($MAIL_DATA['body_html'] ,'KV',$SETTING_DATA['mail_code']);
    }

    # �������މ�
    $MAIL_DATA['subject'] = base64_encode($MAIL_DATA['subject']);
    $MAIL_DATA['subject'] = '=?ISO-2022-JP?B?'.$MAIL_DATA['subject'].'?=';

    # Ұ�Ӱ�ގ擾
    $MAILMODE = $this->_get_mail_mode($MAIL_DATA['body_plain'],$MAIL_DATA['body_html'],$SETTING_DATA['to_career'],$SETTING_DATA['decome_mode'],$upfile_flag,$SETTING_DATA['content_transfer_encoding'],$SETTING_DATA['mail_code'],$SETTING_DATA['input_code']);
    $MAIL_DATA['body_plain'] = $MAILMODE['body_plain'];
    $MAIL_DATA['body_html']  = $MAILMODE['body_html'];

    # �{���e������(÷�Ė{��+HTML)
    $mail_body_size = strlen($MAIL_DATA['body_plain']) + strlen($MAIL_DATA['body_html']);
    if ($SETTING_DATA['to_career'] == 'PC') {
      # PC�p�{���e������
      if (($this->body_all_max_size_pc > 0) and ($this->body_all_max_size_pc < $mail_body_size)) {
        $this->error_flag   = True;
        $this->error_code   = 210;
        $this->error_coment = 'PC Body(Text and HTML) Size Orver.';
        $MAIL['error']      = True;
        return $MAIL;
      }
    } elseif ($SETTING_DATA['to_career'] == 'DoCoMo') {
      # DoCoMo�p�{���e������
      if (($this->body_all_max_size_docomo > 0) and ($this->body_all_max_size_docomo < $mail_body_size)) {
        $this->error_flag   = True;
        $this->error_code   = 211;
        $this->error_coment = 'DoCoMo Body(Text and HTML) Size Orver.';
        $MAIL['error']      = True;
        return $MAIL;
      }
    } elseif ($SETTING_DATA['to_career'] == 'au') {
      # au�p�{���e������
      if (($this->body_all_max_size_au > 0) and ($this->body_all_max_size_au < $mail_body_size)) {
        $this->error_flag   = True;
        $this->error_code   = 212;
        $this->error_coment = 'au Body(Text and HTML) Size Orver.';
        $MAIL['error']      = True;
        return $MAIL;
      }
    } elseif (($SETTING_DATA['to_career'] == 'SoftBank') or ($SETTING_DATA['to_career'] == $emoji_obj->softbank_name)) {
      # SoftBank�p�{���e������
      if (($this->body_all_max_size_softbank > 0) and ($this->body_all_max_size_softbank < $mail_body_size)) {
        $this->error_flag   = True;
        $this->error_code   = 213;
        $this->error_coment = 'SoftBank Body(Text and HTML) Size Orver.';
        $MAIL['error']      = True;
        return $MAIL;
      }
    }

    # ��ײ݉摜�擾
    $INLINEFILE = array();
    if ($SETTING_DATA['decome_mode'] == '1') {
      list($MAIL_DATA['body_html'],$INLINEFILE) = $this->_get_inline_img($MAIL_DATA['body_html'],$SETTING_DATA['to_career']);
      # ��ײ݉摜����
      if (!$this->_inline_check($INLINEFILE,$SETTING_DATA['to_career'])) {
        $this->error_flag   = True;
        $this->error_code   = 220;
        $this->error_coment = 'Inline Image Check Error.';
        $MAIL['error']      = True;
        return $MAIL;
      }
    }

    # �Y�ţ�َ擾
    list($upfile_flag,$UPFILELIST) = $this->_get_upfile($UPFILE,$SETTING_DATA['input_code']);
    # �Y�ţ������
    if ($SETTING_DATA['decome_mode'] == '1') {
      if (!$this->_upfile_check($UPFILELIST,$SETTING_DATA['to_career'])) {
        $this->error_flag   = True;
        $this->error_code   = 221;
        $this->error_coment = 'Add File Check Error.';
        $MAIL['error']      = True;
        return $MAIL;
      }
    }

    # ��ײ݁A�Y�ţ��İ������
    if ($SETTING_DATA['decome_mode'] == '1') {
      if (!$this->_all_file_check($INLINEFILE,$UPFILELIST,$SETTING_DATA['to_career'])) {
        $this->error_flag   = True;
        $this->error_code   = 222;
        $this->error_coment = 'Inline Image And Add File Check Error.';
        $MAIL['error']      = True;
        return $MAIL;
      }
    }

    # ����ͯ�ް����
    $add_mail_header  = '';
    if ($set_form     != '') { $add_mail_header .= "From: ".$set_form."\n"; }
    if ($set_repry_to != '') { $add_mail_header .= "Reply-To: ".$set_repry_to."\n"; }
    if ($set_cc       != '') { $add_mail_header .= "Cc: ".$set_cc."\n"; }
    if ($set_bcc      != '') { $add_mail_header .= "Bcc: ".$set_bcc."\n"; }
    $add_mail_header .= "MIME-Version: 1.0\n";

    # �{������
    list($mail_header_ptn,$mail_body) = $this->_make_mail_body($MAILMODE['ptn_no'],$MAIL_DATA['body_plain'],$MAIL_DATA['body_html'],$SETTING_DATA['to_career'],$INLINEFILE,$UPFILELIST,$SETTING_DATA['decome_mode'],$upfile_flag,$SETTING_DATA['content_transfer_encoding'],$SETTING_DATA['mail_code'],$SETTING_DATA['input_code']);
    $add_mail_header .= $mail_header_ptn;

    # �{���e������
    if ($SETTING_DATA['to_career'] == 'PC') {
      # PC�p�{���e������
      if (($this->body_max_size_pc > 0) and ($this->body_max_size_pc < strlen($mail_body))) {
        $this->error_flag   = True;
        $this->error_code   = 230;
        $this->error_coment = 'PC All Body Size Order.';
        $MAIL['error']      = True;
        return $MAIL;
      }
    } elseif ($SETTING_DATA['to_career'] == 'DoCoMo') {
      # DoCoMo�p�{���e������
      if ($SETTING_DATA['decome_mode'] == '1') {
        if (($this->body_max_size_docomo > 0) and ($this->body_max_size_docomo < strlen($mail_body))) {
          $this->error_flag   = True;
          $this->error_code   = 231;
          $this->error_coment = 'DoCoMo All Body Size Order.';
          $MAIL['error']      = True;
          return $MAIL;
        }
      }
    } elseif ($SETTING_DATA['to_career'] == 'au') {
      # au�p�{���e������
      if ($SETTING_DATA['decome_mode'] == '1') {
        if (($this->body_max_size_au > 0) and ($this->body_max_size_au < strlen($mail_body))) {
          $this->error_flag   = True;
          $this->error_code   = 232;
          $this->error_coment = 'au All Body Size Order.';
          $MAIL['error']      = True;
          return $MAIL;
        }
      }
    } elseif (($SETTING_DATA['to_career'] == 'SoftBank') or ($SETTING_DATA['to_career'] == $emoji_obj->softbank_name)) {
      # SoftBank�p�{���e������
      if ($SETTING_DATA['decome_mode'] == '1') {
        if (($this->body_max_size_softbank > 0) and ($this->body_max_size_softbank < strlen($mail_body))) {
          $this->error_flag   = True;
          $this->error_code   = 233;
          $this->error_coment = 'SoftBank All Body Size Order.';
          $MAIL['error']      = True;
          return $MAIL;
        }
      }
    }

    # �Ԃ�l�ݒ�
    $MAIL['error']           = False;
    $MAIL['set_to']          = $set_to;
    $MAIL['return_path']     = $MAIL_DATA['return_path'];
    $MAIL['subject']         = $MAIL_DATA['subject'];
    $MAIL['mail_body']       = $mail_body;
    $MAIL['add_mail_header'] = $add_mail_header;

    return $MAIL;

  }

  # �G�����޺ڰ���Ұ��ް��Ȉ����� /////////////////////////////////////////////
  # �޺ڰ���Ұ�(�G����)�̑��M�ް����Ȉ��������܂��B
  # [���n���l]
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
  # �@$katakana_chg_cancel                       : ������{�����p���őS�p�ϊ���ݾ�(�w��Ȃ�:�����ϊ�,1:�ϊ���ݾ�)
  # [�Ԃ�l]
  # �@$MAIL                      : Ұِ����ް�
  # �@�@$MAIL['error']           : �װ�׸�(True:�װ�L��AFalse:�װ����)
  # �@�@$MAIL['subject']         : ����
  # �@�@$MAIL['add_mail_header'] : Ұْǉ�ͯ�ް
  # �@�@$MAIL['mail_body']       : Ұٖ{��
  #////////////////////////////////////////////////////////////////////////////
  function check_mail_data($MAIL_DATA,$SETTING_DATA,$UPFILE,$katakana_chg_cancel='') {
    global $emoji_obj;

    # �װ������
    $this->error_flag   = False;
    $this->error_code   = 0;
    $this->error_coment = '';

    # �����ݒ�
    if (!isset($MAIL_DATA['subject']))                      { $MAIL_DATA['subject']                      = ''; }
    if (!isset($MAIL_DATA['body_plain']))                   { $MAIL_DATA['body_plain']                   = ''; }
    if (!isset($MAIL_DATA['body_html']))                    { $MAIL_DATA['body_html']                    = ''; }
    if (!isset($SETTING_DATA['decome_mode']))               { $SETTING_DATA['decome_mode']               = ''; }
    if (!isset($SETTING_DATA['to_career']))                 { $SETTING_DATA['to_career']                 = 'PC'; }
    if (!isset($SETTING_DATA['content_transfer_encoding'])) { $SETTING_DATA['content_transfer_encoding'] = ''; }
    if (!isset($SETTING_DATA['mail_code']))                 { $SETTING_DATA['mail_code']                 = 'JIS'; }
    if (!isset($SETTING_DATA['encode_pass']))               { $SETTING_DATA['encode_pass']               = ''; }
    if (!isset($SETTING_DATA['input_code']))                { $SETTING_DATA['input_code']                = ''; }

    # Ӱ�ސݒ�
    if ($this->decome_flag         == False) { $SETTING_DATA['decome_mode'] = ''; }
#    if ($SETTING_DATA['to_career'] == 'PC')  { $SETTING_DATA['decome_mode'] = ''; }
#    # SoftBank�pӰ�ސݒ�
#    if (($SETTING_DATA['to_career'] == 'SoftBank') or ($SETTING_DATA['to_career'] == $emoji_obj->softbank_name)) { $SETTING_DATA['decome_mode'] = ''; }

    # �{������
    if (($MAIL_DATA['body_plain'] == '') and ($MAIL_DATA['body_html'] == '')) {
      $this->error_flag   = True;
      $this->error_code   = 202;
      $this->error_coment = 'No Body Data.';
      return False;
    }

    # �Y�ţ������
    $upfile_flag = False;
    if (isset($UPFILE)) {
      if (is_array($UPFILE)) {
        foreach ($UPFILE as $pathdt => $namedt) {
          if (isset($pathdt)) {
            if (file_exists($pathdt)) { $upfile_flag = True; break; }
          }
        }
      }
    }

    # ���M�ݺ��ސݒ�
    if ($SETTING_DATA['content_transfer_encoding'] == '') {
      if (isset($emoji_obj->cont_trs_enc)) {
        if ($emoji_obj->cont_trs_enc == '') {
          $SETTING_DATA['content_transfer_encoding'] = $emoji_obj->cont_trs_enc;
        } else {
          $SETTING_DATA['content_transfer_encoding'] = '7bit';
        }
      } else {
        $SETTING_DATA['content_transfer_encoding'] = '7bit';
      }
    }

    # Ұّ��M�p�G�����ϊ�(�ݺ��ށ�SJIS�ϊ�)
    if ($SETTING_DATA['encode_pass'] != '1') {
      $MAIL_DATA['subject']    = $emoji_obj->emj_encode($MAIL_DATA['subject']   ,'SJIS','',$SETTING_DATA['input_code']);
      $MAIL_DATA['body_plain'] = $emoji_obj->emj_encode($MAIL_DATA['body_plain'],'SJIS','',$SETTING_DATA['input_code']);
      $MAIL_DATA['body_html']  = $emoji_obj->emj_encode($MAIL_DATA['body_html'] ,'SJIS','',$SETTING_DATA['input_code']);
      $SETTING_DATA['input_code'] = 'SJIS';
    }

    # �������ގ擾
    if ($SETTING_DATA['input_code'] != '') {
#      $subject_code    = mb_detect_encoding($MAIL_DATA['subject']   ,$emoji_obj->ENCODINGLIST[$SETTING_DATA['input_code']]);
#      $body_plain_code = mb_detect_encoding($MAIL_DATA['body_plain'],$emoji_obj->ENCODINGLIST[$SETTING_DATA['input_code']]);
#      $body_html_code  = mb_detect_encoding($MAIL_DATA['body_html'] ,$emoji_obj->ENCODINGLIST[$SETTING_DATA['input_code']]);
      $subject_code    = $SETTING_DATA['input_code'];
      $body_plain_code = $SETTING_DATA['input_code'];
      $body_html_code  = $SETTING_DATA['input_code'];
    } else {
      $subject_code    = mb_detect_encoding($MAIL_DATA['subject']   ,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
      $body_plain_code = mb_detect_encoding($MAIL_DATA['body_plain'],$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
      $body_html_code  = mb_detect_encoding($MAIL_DATA['body_html'] ,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
    }
    if ($subject_code    != '') { $subject_code    = mb_preferred_mime_name($subject_code); }
    if ($body_plain_code != '') { $body_plain_code = mb_preferred_mime_name($body_plain_code); }
    if ($body_html_code  != '') { $body_html_code  = mb_preferred_mime_name($body_html_code); }

    # ��������
    if ($MAIL_DATA['subject'] == '') { $MAIL_DATA['subject'] = @mb_convert_encoding('����',$subject_code,'SJIS'); }
    # �G�����ϊ�(�޺���)
    if (($SETTING_DATA['to_career'] == '') or ($SETTING_DATA['to_career'] == 'PC')) {
      # PC�y�ёS��ر�����̏ꍇ(�G�����폜)
      $MAIL_DATA['subject'] = $emoji_obj->delete_emoji_code($MAIL_DATA['subject'],'0','0','0',$subject_code,'',$subject_code);
    } elseif (($SETTING_DATA['to_career'] == 'Vodafone') or ($SETTING_DATA['to_career'] == 'SoftBank')) {
      # SoftBank3G�����̏ꍇ(�G�����폜)
      $MAIL_DATA['subject'] = $emoji_obj->delete_emoji_code($MAIL_DATA['subject'],'0','0','0',$subject_code,'',$subject_code);
    } else {
      # �e��ر����(�G�����޺���)
      $SUBJECT = $emoji_obj->emj_decode($MAIL_DATA['subject'],$SETTING_DATA['to_career'],$subject_code);
      $MAIL_DATA['subject'] = $SUBJECT['mail'];
    }

    # �������ޕϊ�
    if ($subject_code    != mb_preferred_mime_name($SETTING_DATA['mail_code'])) { $MAIL_DATA['subject']    = @mb_convert_encoding($MAIL_DATA['subject']   ,$SETTING_DATA['mail_code'],$subject_code); }
    if ($body_plain_code != mb_preferred_mime_name($SETTING_DATA['mail_code'])) { $MAIL_DATA['body_plain'] = @mb_convert_encoding($MAIL_DATA['body_plain'],$SETTING_DATA['mail_code'],$body_plain_code); }
    if ($body_html_code  != mb_preferred_mime_name($SETTING_DATA['mail_code'])) { $MAIL_DATA['body_html']  = @mb_convert_encoding($MAIL_DATA['body_html'] ,$SETTING_DATA['mail_code'],$body_html_code); }

    # ���ŕϊ�
    if ($katakana_chg_cancel == '') {
      $MAIL_DATA['subject']    = mb_convert_kana($MAIL_DATA['subject']   ,'KV',$SETTING_DATA['mail_code']);
      $MAIL_DATA['body_plain'] = mb_convert_kana($MAIL_DATA['body_plain'],'KV',$SETTING_DATA['mail_code']);
      $MAIL_DATA['body_html']  = mb_convert_kana($MAIL_DATA['body_html'] ,'KV',$SETTING_DATA['mail_code']);
    }

    # �������މ�
    $MAIL_DATA['subject'] = base64_encode($MAIL_DATA['subject']);
    $MAIL_DATA['subject'] = '=?ISO-2022-JP?B?'.$MAIL_DATA['subject'].'?=';

    # Ұ�Ӱ�ގ擾
    $MAILMODE = $this->_get_mail_mode($MAIL_DATA['body_plain'],$MAIL_DATA['body_html'],$SETTING_DATA['to_career'],$SETTING_DATA['decome_mode'],$upfile_flag,$SETTING_DATA['content_transfer_encoding'],$SETTING_DATA['mail_code'],$SETTING_DATA['input_code']);
    $MAIL_DATA['body_plain'] = $MAILMODE['body_plain'];
    $MAIL_DATA['body_html']  = $MAILMODE['body_html'];

    # �{���e������(÷�Ė{��+HTML)
    $mail_body_size = strlen($MAIL_DATA['body_plain']) + strlen($MAIL_DATA['body_html']);
    if ($SETTING_DATA['to_career'] == 'PC') {
      # PC�p�{���e������
      if (($this->body_all_max_size_pc > 0) and ($this->body_all_max_size_pc < $mail_body_size)) {
        $this->error_flag   = True;
        $this->error_code   = 210;
        $this->error_coment = 'PC Body(Text and HTML) Size Orver.';
        $MAIL['error']      = True;
        return $MAIL;
      }
    } elseif ($SETTING_DATA['to_career'] == 'DoCoMo') {
      # DoCoMo�p�{���e������
      if (($this->body_all_max_size_docomo > 0) and ($this->body_all_max_size_docomo < $mail_body_size)) {
        $this->error_flag   = True;
        $this->error_code   = 211;
        $this->error_coment = 'DoCoMo Body(Text and HTML) Size Orver.';
        $MAIL['error']      = True;
        return $MAIL;
      }
    } elseif ($SETTING_DATA['to_career'] == 'au') {
      # au�p�{���e������
      if (($this->body_all_max_size_au > 0) and ($this->body_all_max_size_au < $mail_body_size)) {
        $this->error_flag   = True;
        $this->error_code   = 212;
        $this->error_coment = 'au Body(Text and HTML) Size Orver.';
        $MAIL['error']      = True;
        return $MAIL;
      }
    } elseif (($SETTING_DATA['to_career'] == 'SoftBank') or ($SETTING_DATA['to_career'] == $emoji_obj->softbank_name)) {
      # SoftBank�p�{���e������
      if (($this->body_all_max_size_softbank > 0) and ($this->body_all_max_size_softbank < $mail_body_size)) {
        $this->error_flag   = True;
        $this->error_code   = 213;
        $this->error_coment = 'SoftBank Body(Text and HTML) Size Orver.';
        $MAIL['error']      = True;
        return $MAIL;
      }
    }

    # ��ײ݉摜�擾
    $INLINEFILE = array();
    if ($SETTING_DATA['decome_mode'] == '1') {
      list($MAIL_DATA['body_html'],$INLINEFILE) = $this->_get_inline_img($MAIL_DATA['body_html'],$SETTING_DATA['to_career']);
      if ($this->file_error_flag == True) {
        $this->error_flag   = True;
        $this->error_code   = $this->file_error_code;
        $this->error_coment = $this->file_error_coment;
        $MAIL['error']      = True;
        return $MAIL;
      } else {
        # ��ײ݉摜����
        if (!$this->_inline_check($INLINEFILE,$SETTING_DATA['to_career'])) {
          $this->error_flag   = True;
          $this->error_code   = 220;
          $this->error_coment = 'Inline Image Check Error.';
          $MAIL['error']      = True;
          return $MAIL;
        }
      }
    }

    # �Y�ţ�َ擾
    list($upfile_flag,$UPFILELIST) = $this->_get_upfile($UPFILE,$SETTING_DATA['input_code']);
    if ($this->file_error_flag == True) {
      $this->error_flag   = True;
      $this->error_code   = $this->file_error_code;
      $this->error_coment = $this->file_error_coment;
      $MAIL['error']      = True;
      return $MAIL;
    } else {
      # �Y�ţ������
      if ($SETTING_DATA['decome_mode'] == '1') {
        if (!$this->_upfile_check($UPFILELIST,$SETTING_DATA['to_career'])) {
          $this->error_flag   = True;
          $this->error_code   = 221;
          $this->error_coment = 'Add File Check Error.';
          $MAIL['error']      = True;
          return $MAIL;
        }
      }
    }

    # ��ײ݁A�Y�ţ��İ������
    if ($SETTING_DATA['decome_mode'] == '1') {
      if (!$this->_all_file_check($INLINEFILE,$UPFILELIST,$SETTING_DATA['to_career'])) {
        $this->error_flag   = True;
        $this->error_code   = 222;
        $this->error_coment = 'Inline Image And Add File Check Error.';
        $MAIL['error']      = True;
        return $MAIL;
      }
    }

    # ����ͯ�ް����
    $add_mail_header  = '';

    # �{������
    list($mail_header_ptn,$mail_body) = $this->_make_mail_body($MAILMODE['ptn_no'],$MAIL_DATA['body_plain'],$MAIL_DATA['body_html'],$SETTING_DATA['to_career'],$INLINEFILE,$UPFILELIST,$SETTING_DATA['decome_mode'],$upfile_flag,$SETTING_DATA['content_transfer_encoding'],$SETTING_DATA['mail_code'],$SETTING_DATA['input_code']);
    $add_mail_header .= $mail_header_ptn;

    # �{���e������
    if ($SETTING_DATA['to_career'] == 'PC') {
      # PC�p�{���e������
      if (($this->body_max_size_pc > 0) and ($this->body_max_size_pc < strlen($mail_body))) {
        $this->error_flag   = True;
        $this->error_code   = 230;
        $this->error_coment = 'PC All Body Size Order.';
        $MAIL['error']      = True;
        return $MAIL;
      }
    } elseif ($SETTING_DATA['to_career'] == 'DoCoMo') {
      # DoCoMo�p�{���e������
      if ($SETTING_DATA['decome_mode'] == '1') {
        if (($this->body_max_size_docomo > 0) and ($this->body_max_size_docomo < strlen($mail_body))) {
          $this->error_flag   = True;
          $this->error_code   = 231;
          $this->error_coment = 'DoCoMo All Body Size Order.';
          $MAIL['error']      = True;
          return $MAIL;
        }
      }
    } elseif ($SETTING_DATA['to_career'] == 'au') {
      # au�p�{���e������
      if ($SETTING_DATA['decome_mode'] == '1') {
        if (($this->body_max_size_au > 0) and ($this->body_max_size_au < strlen($mail_body))) {
          $this->error_flag   = True;
          $this->error_code   = 232;
          $this->error_coment = 'au All Body Size Order.';
          $MAIL['error']      = True;
          return $MAIL;
        }
      }
    } elseif (($SETTING_DATA['to_career'] == 'SoftBank') or ($SETTING_DATA['to_career'] == $emoji_obj->softbank_name)) {
      # SoftBank�p�{���e������
      if ($SETTING_DATA['decome_mode'] == '1') {
        if (($this->body_max_size_softbank > 0) and ($this->body_max_size_softbank < strlen($mail_body))) {
          $this->error_flag   = True;
          $this->error_code   = 233;
          $this->error_coment = 'SoftBank All Body Size Order.';
          $MAIL['error']      = True;
          return $MAIL;
        }
      }
    }

    # �Ԃ�l�ݒ�
    $MAIL['error']           = False;
    $MAIL['subject']         = $MAIL_DATA['subject'];
    $MAIL['mail_body']       = $mail_body;
    $MAIL['add_mail_header'] = $add_mail_header;

    return $MAIL;

  }

  # Ұ�Ӱ�ސݒ� //////////////////////////////////////////////////////////////
  # ���e�ɂ��Ұ�Ӱ�ނ��擾���܂��B
  # [���n���l]
  # �@$body_plain  : ÷�Ė{��
  # �@$body_html   : HTML�{��
  # �@$to_career   : ���M�淬ر
  # �@$decome_mode : �޺�Ӱ�ގw��
  # �@$upfile_flag : �Y�ţ���׸�
  # �@$content_transfer_encoding : �ݺ��޺���
  # �@$mail_code   : Ұٖ{���������ގw��(�w��Ȃ�����'JIS':JIS)
  # �@$input_code  : ���ͺ���
  # [�Ԃ�l]
  # �@$ptn_no : ���`��l
  #////////////////////////////////////////////////////////////////////////////
  function _get_mail_mode($body_plain,$body_html,$to_career,$decome_mode,$upfile_flag,$content_transfer_encoding,$mail_code,$input_code) {
    global $emoji_obj;

    $RETURN           = array();
    $RETURN['ptn_no'] = '';
    $plain_flag       = '';

    # Ұ����ߐݒ�
    if (($to_career == '') or ($to_career == 'PC')) {
      # PC����
      if (($body_plain == '') and ($body_html != '')) {
        # HTML�̂�
        if ($decome_mode == '1') { $img_num = preg_match('/<img\s/i',$body_html); } else { $img_num = 0; }
        if ($img_num == 0) {
          # ��ײ݉摜����
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 11; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 10; }
        } elseif ($img_num > 0) {
          # ��ײ݉摜�L��
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 13; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 12; }
        }
      } elseif (($body_plain != '') and ($body_html == '')) {
        # ÷�Ă̂�
        # �G�����L������
        $PLCOUNT = $emoji_obj->emj_check($body_plain,'',$input_code);
        if ($PLCOUNT['total'] > 0) {
          # �G�������܂܂�Ă���ꍇ(÷�ā�HTML�{��)
          $body_html  = $body_plain;
          # URL�Ұٱ��ڽ�ݸ��
          $body_html = link_make($body_html);
          # �G�����폜
          $body_plain = $emoji_obj->delete_emoji_code($body_plain);
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 9; } else { $RETURN['ptn_no'] = 8; }
#          if ($upfile_flag == True) { $RETURN['ptn_no'] = 3; } else { $RETURN['ptn_no'] = 7; }
          $plain_flag = '1';
        } else {
          # �G�������܂܂�Ă��Ȃ��ꍇ
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 2; } else { $RETURN['ptn_no'] = 1; }
        }
      } elseif (($body_plain != '') and ($body_html != '')) {
        # ÷�� + HTML
        $PLCOUNT = $emoji_obj->emj_check($body_plain,'',$input_code);
        if ($PLCOUNT['total'] > 0) {
          # ÷�Ė{���ɊG�������܂܂�Ă���ꍇ(�G�������)
          $body_plain = $emoji_obj->delete_emoji_code($body_plain);
        }
        if ($decome_mode == '1') {
          # �޺�Ӱ��
          $HTCOUNT = $emoji_obj->emj_check($body_html,'',$input_code);
          $img_num = preg_match('/<img\s/i',$body_html);
        } else {
          # �ʏ�Ӱ��
          $HTCOUNT['total'] = 0;
          $img_num          = 0;
        }
        if (($HTCOUNT['total'] == 0) and ($img_num == 0)) {
          # �摜���܂܂�Ȃ��ꍇ
#          if ($upfile_flag == True) { $RETURN['ptn_no'] = 3; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 7; }
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 14; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 7; }
        } elseif (($HTCOUNT['total'] > 0) or ($img_num > 0)) {
          # �摜���܂܂��ꍇ
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 9; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 8; }
        }
      }

    } elseif ($to_career == 'DoCoMo') {
      # DoCoMo����
      if (($body_plain == '') and ($body_html != '')) {
        # HTML�̂�
        # ÷�Ė{���ݒ�
        $body_plain = strip_tags($body_html,'<br>');
        $body_plain = preg_replace('|<br\s*/*>|i',"\n",$body_plain);
        if ($decome_mode == '1') { $img_num = preg_match('/<img\s/i',$body_html); } else { $img_num = 0; }
        if ($img_num == 0) {
          # ��ײ݉摜����
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 3; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 4; }
        } elseif ($img_num > 0) {
          # ��ײ݉摜�L��
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 6; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 5; }
        }
      } elseif (($body_plain != '') and ($body_html == '')) {
        # ÷�Ă̂�
        if ($upfile_flag == True) { $RETURN['ptn_no'] = 2; } else { $RETURN['ptn_no'] = 1; }
      } elseif (($body_plain != '') and ($body_html != '')) {
        # ÷�� + HTML
        if ($decome_mode == '1') { $img_num = preg_match('/<img\s/i',$body_html); } else { $img_num = 0; }
        if ($img_num == 0) {
          # ��ײ݉摜����
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 3; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 4; }
        } elseif ($img_num > 0) {
          # ��ײ݉摜�L��
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 6; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 5; }
        }
      }

    } elseif ($to_career == 'au') {
      # au����
      if (($body_plain == '') and ($body_html != '')) {
        # HTML�̂�
        # ÷�Ė{���ݒ�
        $body_plain = strip_tags($body_html,'<br>');
        $body_plain = preg_replace('|<br\s*/*>|i',"\n",$body_plain);
        if ($decome_mode == '1') { $img_num = preg_match('/<img\s/i',$body_html); } else { $img_num = 0; }
        if ($img_num == 0) {
          # ��ײ݉摜����
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 3; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 4; }
        } elseif ($img_num > 0) {
          # ��ײ݉摜�L��
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 6; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 5; }
        }
      } elseif (($body_plain != '') and ($body_html == '')) {
        # ÷�Ă̂�
        if ($upfile_flag == True) { $RETURN['ptn_no'] = 2; } else { $RETURN['ptn_no'] = 1; }
      } elseif (($body_plain != '') and ($body_html != '')) {
        # ÷�� + HTML
        if ($decome_mode == '1') { $img_num = preg_match('/<img\s/i',$body_html); } else { $img_num = 0; }
        if ($img_num == 0) {
          # ��ײ݉摜����
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 3; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 4; }
        } elseif ($img_num > 0) {
          # ��ײ݉摜�L��
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 6; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 5; }
        }
      }

    } elseif (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
      # SoftBank����
      if (($body_plain == '') and ($body_html != '')) {
        # HTML�̂�
        # ÷�Ė{���ݒ�
        $body_plain = strip_tags($body_html,'<br>');
        $body_plain = preg_replace('|<br\s*/*>|i',"\n",$body_plain);
        # HTML���摜������
        if ($decome_mode == '1') { $img_num = preg_match('/<img\s/i',$body_html); } else { $img_num = 0; }
#        $PLCOUNT = $emoji_obj->emj_check($body_plain,'',$input_code);
#        if (($PLCOUNT['total'] == 0) and ($img_num == 0)) {
        if ($img_num == 0) {
          # ��ײ݉摜����
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 3; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 4; }
        } else {
          # ��ײ݉摜�L��
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 6; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 5; }
        }
      } elseif (($body_plain != '') and ($body_html == '')) {
        # ÷�Ă̂�
        # �G�����L������
        $PLCOUNT = $emoji_obj->emj_check($body_plain,'',$input_code);
        if ($PLCOUNT['total'] > 0) {
          # �G�������܂܂�Ă���ꍇ(÷�ā�HTML�{��)
          $body_html = $body_plain;
          # URL�Ұٱ��ڽ�ݸ��
          $body_html = link_make($body_html);
          # ÷���߰ĊG�����폜
          $body_plain = $emoji_obj->delete_emoji_code($body_plain);
#          if ($upfile_flag == True) { $RETURN['ptn_no'] = 9; } else { $RETURN['ptn_no'] = 8; }
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 3; } else { $RETURN['ptn_no'] = 4; }
          $plain_flag = '1';
        } else {
          # �G�������܂܂�Ă��Ȃ��ꍇ
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 2; } else { $RETURN['ptn_no'] = 1; }
        }
      } elseif (($body_plain != '') and ($body_html != '')) {
        # ÷�� + HTML
        $PLCOUNT = $emoji_obj->emj_check($body_plain,'',$input_code);
        if ($PLCOUNT['total'] > 0) {
          # ÷�Ė{���ɊG�������܂܂�Ă���ꍇ(�G�������)
          $body_plain = $emoji_obj->delete_emoji_code($body_plain);
        }
        if ($decome_mode == '1') {
          # �޺�Ӱ��
          $HTCOUNT = $emoji_obj->emj_check($body_html,'',$input_code);
          $img_num = preg_match('/<img\s/i',$body_html);
        } else {
          # �ʏ�Ӱ��
          $HTCOUNT['total'] = 0;
          $img_num          = 0;
        }
#        if (($HTCOUNT['total'] == 0) and ($img_num == 0)) {
        if ($img_num == 0) {
          # �摜���܂܂�Ȃ��ꍇ
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 3; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 4; }
#        } elseif (($HTCOUNT['total'] > 0) or ($img_num > 0)) {
        } elseif ($img_num > 0) {
          # �摜���܂܂��ꍇ
          if ($upfile_flag == True) { $RETURN['ptn_no'] = 6; } elseif ($upfile_flag == False) { $RETURN['ptn_no'] = 5; }
        }
      }

    }

    # �{������
    $body_plain = $this->_body_plain_make($body_plain,$to_career);
    $body_html  = $this->_body_html_make($body_html,$to_career,$mail_code,$plain_flag);

    # �G�����޺���
    if (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
      $BODYPLAIN  = $emoji_obj->emj_decode($body_plain,$to_career,$mail_code);
      $body_plain = $BODYPLAIN['mail_plain'];
      $body_html  = mb_convert_encoding($body_html,'SJIS','JIS');
      $BODYHTML   = $emoji_obj->emj_decode($body_html,$to_career,'UTF-8');
    } else {
      $BODYPLAIN  = $emoji_obj->emj_decode($body_plain,$to_career,$mail_code);
      $body_plain = $BODYPLAIN['mail'];
      $BODYHTML   = $emoji_obj->emj_decode($body_html,$to_career,$mail_code);
    }
    $body_html = $BODYHTML['mail'];

    # �G�����摜���č폜
    $body_html = preg_replace('/(<img\ssrc=\"[^>]+\"[^>]*)\stitle=\"[^>]+\"\salt=\"[^>]+\">/i','\\1>',$body_html);

    $RETURN['body_plain'] = $body_plain;
    $RETURN['body_html']  = $body_html;

    return $RETURN;
  }

  # ��ײ݉摜�擾 //////////////////////////////////////////////////////////////
  # HTML�{�����̉摜���擾���܂��B
  # [���n���l]
  # �@$body_html : HTML�{��
  # �@$to_career : ���M�淬ر
  # [�Ԃ�l]
  # �@$INLINE_IMGLIST : �擾��ײ݉摜ؽ�
  #////////////////////////////////////////////////////////////////////////////
  function _get_inline_img($body_html,$to_career) {
    global $emoji_obj,$emoji_mail_obj;

    $this->file_error_flag   = False;
    $this->file_error_code   = 0;
    $this->file_error_coment = '';

    $INLINE_IMGLIST = array();
    $body_html      = preg_replace('/\r/','',$body_html);
    $body_html_sub  = $body_html;
    $no             = 0;

    # <IMG>��ޓ��摜�擾
    while (preg_match('|(<img\s+src\s*=[\s\"\']*)(.+?)([\"\'\s>])|i',$body_html_sub,$MATCH)) {
      # ̧�ٓǍ���
      if ($filedata = @file($MATCH[2])) {
        $fdata = join('',$filedata);
        # CID�ݒ�
        $cid = 'img_cid_'.str_pad($no,3,'0',STR_PAD_LEFT).'@'.date('ymd.His',time());
        # �ް��擾
        $PATHDATA = pathinfo($MATCH[2]);
        $INLINE_IMGLIST[$cid]['name'] = $PATHDATA['basename'];
        if ((isset($emoji_mail_obj) and is_object($emoji_mail_obj)) and method_exists($emoji_mail_obj,'get_mime_type')) {
          # ver.8�p
          $INLINE_IMGLIST[$cid]['mime'] = $emoji_mail_obj->get_mime_type($MATCH[2]);
        } else {
          # ver.7�p
          $INLINE_IMGLIST[$cid]['mime'] = $emoji_obj->get_mime_type($MATCH[2]);
        }
        $INLINE_IMGLIST[$cid]['size'] = strlen(base64_encode($fdata));
        $INLINE_IMGLIST[$cid]['data'] = chunk_split(base64_encode($fdata));
        # �{������
        $body_html_sub = preg_replace('|'.$MATCH[1].$MATCH[2].$MATCH[3].'|i','',$body_html_sub);
        $body_html     = preg_replace('|'.$MATCH[2].'|i','cid:'.$cid,$body_html);
      } else {
        $body_html_sub = preg_replace('|'.$MATCH[1].$MATCH[2].$MATCH[3].'|i','',$body_html_sub);
        $body_html     = preg_replace('|'.$MATCH[2].'|i','',$body_html);
        $this->file_error_flag   = True;
        $this->file_error_code   = 300;
        $this->file_error_coment = 'Inline Image No Link Error.';
      }
      $no++;
    }

    # <BODY>��ޓ��摜�擾
    if (preg_match('|(<body\s+background\s*=[\s\"\']*)(.+?)([\"\'\s>])|i',$body_html_sub,$MATCH)) {
      # ̧�ٓǍ���
      if ($filedata = @file($MATCH[2])) {
        $fdata = join('',$filedata);
        # CID�ݒ�
        $cid = 'img_cid_'.str_pad($no,3,'0',STR_PAD_LEFT).'@'.date('ymd.His',time());
        # �ް��擾
        $PATHDATA = pathinfo($MATCH[2]);
        $INLINE_IMGLIST[$cid]['name'] = $PATHDATA['basename'];
        if ((isset($emoji_mail_obj) and is_object($emoji_mail_obj)) and method_exists($emoji_mail_obj,'get_mime_type')) {
          # ver.8�p
          $INLINE_IMGLIST[$cid]['mime'] = $emoji_mail_obj->get_mime_type($MATCH[2]);
        } else {
          # ver.7�p
          $INLINE_IMGLIST[$cid]['mime'] = $emoji_obj->get_mime_type($MATCH[2]);
        }
        $INLINE_IMGLIST[$cid]['size'] = strlen(base64_encode($fdata));
        $INLINE_IMGLIST[$cid]['data'] = chunk_split(base64_encode($fdata));
        # �{������
        $body_html = preg_replace('|'.$MATCH[2].'|i','cid:'.$cid,$body_html);
      } else {
        $body_html = preg_replace('|'.$MATCH[2].'|i','',$body_html);
        $this->file_error_flag   = True;
        $this->file_error_code   = 300;
        $this->file_error_coment = 'Inline Image No Link Error.';
      }
    }

    return array($body_html,$INLINE_IMGLIST);
  }

  # �Y�ţ�َ擾 //////////////////////////////////////////////////////////////
  # �Y�ţ�ق��擾���܂��B
  # [���n���l]
  # �@$UPFILE     : ����۰��̧��ؽ�
  # �@$input_code : ���͕�������
  # [�Ԃ�l]
  # �@$UPFILELIST : �擾̧��ؽ�
  #////////////////////////////////////////////////////////////////////////////
  function _get_upfile($UPFILE,$input_code='SJIS') {
    global $emoji_obj,$emoji_mail_obj;

    $this->file_error_flag   = False;
    $this->file_error_code   = 0;
    $this->file_error_coment = '';

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
              if ((isset($emoji_mail_obj) and is_object($emoji_mail_obj)) and method_exists($emoji_mail_obj,'get_mime_type')) {
                # ver.8�p
                $UPFILELIST[$no]['mime'] = $emoji_mail_obj->get_mime_type($pathdt);
              } else {
                # ver.7�p
                $UPFILELIST[$no]['mime'] = $emoji_obj->get_mime_type($pathdt);
              }
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
              # ̧�ٖ��ݺ���
              $UPFILELIST[$no]['basename_flag']  = False;
              $UPFILELIST[$no]['basename_fname'] = $UPFILELIST[$no]['basename'];
              $fcd = mb_detect_encoding($UPFILELIST[$no]['basename'],$emoji_obj->ENCODINGLIST[$input_code]);
              if ($fcd != 'ASCII') {
                # ���{��̏ꍇ
                $UPFILELIST[$no]['basename'] = mb_convert_encoding($UPFILELIST[$no]['basename'],'JIS',$fcd);
                $UPFILELIST[$no]['basename'] = base64_encode($UPFILELIST[$no]['basename']);
                $UPFILELIST[$no]['basename'] = '=?ISO-2022-JP?B?'.$UPFILELIST[$no]['basename'].'?=';
                $UPFILELIST[$no]['basename_fname'] = mb_convert_encoding($UPFILELIST[$no]['basename_fname'],'JIS',$fcd);
                $UPFILELIST[$no]['basename_fname'] = rawurlencode($UPFILELIST[$no]['basename_fname']);
                $UPFILELIST[$no]['basename_flag']  = True;
              }
              # ̧�ٓǍ���
              if ($fp = @fopen($pathdt,"r")) {
                $fdata = fread($fp,filesize($pathdt));
                fclose($fp);
              } else {
                $this->file_error_flag   = True;
                $this->file_error_code   = 301;
                $this->file_error_coment = 'Add File No Link Error.';
              }
              # �ݺ��ނ��ĕ���
              $UPFILELIST[$no]['size']     = strlen(base64_encode($fdata));
              $UPFILELIST[$no]['filedata'] = chunk_split(base64_encode($fdata));
              $upfile_flag = True;
              $no++;
            }
          }
        }
      }
    }

    return array($upfile_flag,$UPFILELIST);
  }

  # ÷���ް����` //////////////////////////////////////////////////////////////
  # ÷�Ė{���𐮌`���܂��B
  # [���n���l]
  # �@$body_plain : ���`�O�l
  # �@$to_career  : ���M�淬ر
  # [�Ԃ�l]
  # �@$body_plain : ���`��l
  #////////////////////////////////////////////////////////////////////////////
  function _body_plain_make($body_plain,$to_career) {

    # �ް������s����
    if (($body_plain != '') and !preg_match('/\n$/',$body_plain)) { $body_plain .= "\n"; }

    return $body_plain;
  }

  # HTML�ް����` //////////////////////////////////////////////////////////////
  # HTML�{���𐮌`���܂��B
  # [���n���l]
  # �@$body_html       : ���`�O�l
  # �@$to_career       : ���M�淬ر
  # �@$mail_code       : Ұٕ�������
  # �@$body_plain_flag : �����ް���÷�Ė{���̏ꍇ'1'
  # [�Ԃ�l]
  # �@$body_html : ���`��l
  #////////////////////////////////////////////////////////////////////////////
  function _body_html_make($body_html,$to_career,$mail_code,$body_plain_flag='') {
    global $emoji_obj;

    # ���ް�����
    $body_html = preg_replace('/\r/','',$body_html);

    # ���ް���÷�Ă̏ꍇ
    if ($body_plain_flag == '1') {
      $body_html = preg_replace('/\n/','<br />',$body_html);
      $body_html = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-2022-jp\"></head><body>".$body_html."</body></html>";
    }

    # HTMLͯ�ް����
    if (!eregi('<html>.+</html>',$body_html)) {
      if (eregi('<body.+</body>',$body_html)) {
        if (eregi('<head>.+</head>',$body_html)) {
          $body_html = "<html>".$body_html."</html>";
        } else {
          $body_html = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-2022-jp\"></head>".$body_html."</html>";
        }
      } else {
        $body_html = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-2022-jp\"></head><body>".$body_html."</body></html>";
      }
    }

    # html���s��؂�
    $body_html = preg_replace('/<br\s*\/*>/i',"<br />\n",$body_html);

    # HTML�������ސݒ�
    $mcode = '';
    if (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
      # SoftBank�̏ꍇ����UTF-8�ϊ�
      if (preg_match('/<meta\s[^>]+content\s*=\s*\"[^>]+\scharset=([^>]+)\"[^>]*>/i',$body_html,$MATCH)) {
        # �������ގw��<META>��ނ��܂܂�Ă���ꍇ
        $body_html = preg_replace('/(<meta\s[^>]+content\s*=\s*\"[^>]+\scharset=)([^>]+)(\"[^>]*>)/i','\\1UTF-8\\3',$body_html);
      } else {
        # �������ގw��<META>��ނ��܂܂�Ă��Ȃ��ꍇ
        $body_html = str_replace('<head>','<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">',$body_html);
      }
      $mcode = 'UTF-8';
      $body_html_code = mb_detect_encoding($body_html,$emoji_obj->ENCODINGLIST[$emoji_obj->chr_code]);
      if (mb_preferred_mime_name($body_html_code) != mb_preferred_mime_name('UTF-8')) {
        $body_html = mb_convert_encoding($body_html,'UTF-8');
      }
    } else {
      # SoftBank�ȊO
      if (preg_match('/^jis$/i',$mail_code)) {
        $mcode = 'ISO-2022-JP';
      } elseif (preg_match('/sjis/i',$mail_code) or preg_match('/shift_jis/i',$mail_code)) {
        $mcode = 'Shift_JIS';
      } elseif (preg_match('/euc/i',$mail_code)) {
        $mcode = 'EUC-JP';
      } elseif (preg_match('/utf/i',$mail_code)) {
        $mcode = 'UTF-8';
      }
    }
    $body_html = preg_replace('|(<meta\s.*\scharset=)(.+?)([\'\"].*?>)|i','\\1'.$mcode.'\\3',$body_html);

    if ($to_career != 'PC') {
      # PC�ȊO
      # �s���󔒍폜
      $TDATA  = explode("\n",$body_html);
      $TDATAS = array();
      foreach ($TDATA as $tdt) {
        $TDATAS[] = preg_replace('/^\s*/','',$tdt);
      }
      $body_html = join("\n",$TDATAS);
      # ���s�폜
      $body_html = preg_replace('/[\r\n]/','',$body_html);
    }

    # �ް������s����
    if (($body_html != '') and preg_match('/\n$/',$body_html)) { $body_html = preg_replace('/\n$/','',$body_html); }

    return $body_html;
  }

  # Ұٖ{������ //////////////////////////////////////////////////////////////
  # Ұق̖{���𐶐����܂��B
  # [���n���l]
  # �@$mail_ptn    : Ұٌ`�������
  # �@$body_plain  : ÷�Ė{��
  # �@$body_html   : HTML�{��
  # �@$INLINEFILE  : ��ײ݉摜̧��ؽ�
  # �@$UPFILE      : �Y�ţ��ؽ�
  # �@$to_career   : ���M�淬ر
  # �@$decome_mode : �޺�Ӱ�ގw��
  # �@$upfile_flag : �Y�ţ���׸�
  # �@$content_transfer_encoding : �ݺ��޺���
  # �@$mail_code   : Ұٖ{���������ގw��(�w��Ȃ�����'JIS':JIS)
  # �@$input_code  : ���ͺ���
  # [�Ԃ�l]
  # �@$mail_body   : Ұٖ{��
  #////////////////////////////////////////////////////////////////////////////
  function _make_mail_body($mail_ptn,$body_plain,$body_html,$to_career,$INLINEFILE,$UPFILE,$decome_mode,$upfile_flag,$content_transfer_encoding,$mail_code,$input_code) {
    global $emoji_obj;

    $mail_header_ptn = '';
    $mail_body       = '';
    # �{���ݺ���
    if ($content_transfer_encoding == 'base64') {
      # Base64�ݺ���
      $body_plain = chunk_split(base64_encode($body_plain));
      $body_html  = chunk_split(base64_encode($body_html));
    } elseif ($content_transfer_encoding == 'quoted_printable') {
      # Quoted_Printable�ݺ���
      $body_plain = _quoted_printable_encode($body_plain);
      $body_html  = _quoted_printable_encode($body_html);
    } else {
      # �w�薳��(7bit)
      if ($to_career == 'PC') {
        # PC���Ă̏ꍇ
      } elseif (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
        # SoftBank�g�ш��Ă̏ꍇ
#        $content_transfer_encoding = 'quoted_printable';
#        $body_plain = _quoted_printable_encode($body_plain);
        $content_transfer_encoding = '7bit';
        $body_html = _quoted_printable_encode($body_html);
      } else {
        # SoftBank�g�ш��ĈȊO�̏ꍇ
      }
    }

    if ($mail_ptn == '1') {
      # ÷��(����)
      list($mail_header_ptn,$mail_body) = $this->_mail_ptn_1($body_plain,$content_transfer_encoding);
    } elseif ($mail_ptn == '2') {
      # ÷�� + ̧�ٓY�t(����)
      list($mail_header_ptn,$mail_body) = $this->_mail_ptn_2($body_plain,$UPFILE,$content_transfer_encoding);
    } elseif ($mail_ptn == '3') {
      # ÷�� + HTML + ̧�ٓY�t(����)
      list($mail_header_ptn,$mail_body) = $this->_mail_ptn_3($body_plain,$body_html,$UPFILE,$content_transfer_encoding,$to_career);
    } elseif ($mail_ptn == '4') {
      # ÷�� + HTML(�g�їp)
      list($mail_header_ptn,$mail_body) = $this->_mail_ptn_4($body_plain,$body_html,$content_transfer_encoding,$to_career);
    } elseif ($mail_ptn == '5') {
      # ÷�� + HTML + ��ײ݉摜(�g�їp)
      if ($to_career == 'au') {
        list($mail_header_ptn,$mail_body) = $this->_mail_ptn_5_au($body_plain,$body_html,$INLINEFILE,$content_transfer_encoding,$to_career);
      } elseif (($to_career == 'SoftBank') or  ($to_career == $emoji_obj->softbank_name)) {
        list($mail_header_ptn,$mail_body) = $this->_mail_ptn_5_sb($body_plain,$body_html,$INLINEFILE,$content_transfer_encoding,$to_career);
      } else {
        list($mail_header_ptn,$mail_body) = $this->_mail_ptn_5($body_plain,$body_html,$INLINEFILE,$content_transfer_encoding,$to_career);
      }
    } elseif ($mail_ptn == '6') {
      # ÷�� + HTML + ��ײ݉摜 + ̧�ٓY�t(�g�їp)
      if ($to_career == 'au') {
        list($mail_header_ptn,$mail_body) = $this->_mail_ptn_6_au($body_plain,$body_html,$INLINEFILE,$UPFILE,$content_transfer_encoding,$to_career);
      } elseif (($to_career == 'SoftBank') or  ($to_career == $emoji_obj->softbank_name)) {
        list($mail_header_ptn,$mail_body) = $this->_mail_ptn_6_sb($body_plain,$body_html,$INLINEFILE,$UPFILE,$content_transfer_encoding,$to_career);
      } else {
        list($mail_header_ptn,$mail_body) = $this->_mail_ptn_6($body_plain,$body_html,$INLINEFILE,$UPFILE,$content_transfer_encoding,$to_career);
      }
    } elseif ($mail_ptn == '7') {
      # ÷�� + HTML(PC�p)
      list($mail_header_ptn,$mail_body) = $this->_mail_ptn_7($body_plain,$body_html,$content_transfer_encoding);
    } elseif ($mail_ptn == '8') {
      # ÷�� + HTML + ��ײ݉摜(PC�p)
      list($mail_header_ptn,$mail_body) = $this->_mail_ptn_8($body_plain,$body_html,$INLINEFILE,$content_transfer_encoding);
    } elseif ($mail_ptn == '9') {
      # ÷�� + HTML + ��ײ݉摜 + ̧�ٓY�t(PC�p)
      list($mail_header_ptn,$mail_body) = $this->_mail_ptn_9($body_plain,$body_html,$INLINEFILE,$UPFILE,$content_transfer_encoding);
    } elseif ($mail_ptn == '10') {
      # HTML(PC�p)
      list($mail_header_ptn,$mail_body) = $this->_mail_ptn_10($body_html,$content_transfer_encoding);
    } elseif ($mail_ptn == '11') {
      # HTML + ̧�ٓY�t(PC�p)
      list($mail_header_ptn,$mail_body) = $this->_mail_ptn_11($body_html,$UPFILE,$content_transfer_encoding);
    } elseif ($mail_ptn == '12') {
      # HTML + ��ײ݉摜(PC�p)
      list($mail_header_ptn,$mail_body) = $this->_mail_ptn_12($body_html,$INLINEFILE,$content_transfer_encoding);
    } elseif ($mail_ptn == '13') {
      # HTML + ��ײ݉摜 + ̧�ٓY�t(PC�p)
      list($mail_header_ptn,$mail_body) = $this->_mail_ptn_13($body_html,$INLINEFILE,$UPFILE,$content_transfer_encoding);
    } elseif ($mail_ptn == '14') {
      # ÷�� + HTML + ̧�ٓY�t(PC�p)
      list($mail_header_ptn,$mail_body) = $this->_mail_ptn_14($body_plain,$body_html,$UPFILE,$content_transfer_encoding);
    }
    return array($mail_header_ptn,$mail_body);
  }

  # Ұ������1 /////////////////////////////////////////////////////////////////
  # Ұٌ`�������1(PC,�g�ы���) - ÷�Ė{���̂�
  # [���n���l]
  # �@$body_plain                : ÷�Ė{��
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_1($body_plain,$content_transfer_encoding) {

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
    $mail_header_ptn .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
    $mail_header_ptn .= "Content-Transfer-Encoding: ".$content_transfer_encoding;

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    $mail_ptn .= $body_plain;

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������2 /////////////////////////////////////////////////////////////////
  # Ұٌ`�������2(PC,�g�ы���) - ÷�Ė{�� + �Y�ţ��
  # [���n���l]
  # �@$body_plain                : ÷�Ė{��
  # �@$UPFILELIST                : ����۰��̧��ؽ�
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_2($body_plain,$UPFILELIST,$content_transfer_encoding) {

    # �޳���ذ�ݒ�
    $boundary = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
    $mail_header_ptn .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # ÷�Ė{���߰Đݒ�
    $mail_ptn .= "--{$boundary}\n";
    $mail_ptn .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_plain;
    $mail_ptn .= "\n";
    # �Y�ţ���߰Đݒ�
    $mail_ptn .= $this->_addfile($UPFILELIST,$boundary);
    # �߰ďI���޳���ذ
    $mail_ptn .= "--{$boundary}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������3 /////////////////////////////////////////////////////////////////
  # Ұٌ`�������3(PC,�g�ы���) - ÷�Ė{�� + HTML�{�� + �Y�ţ��
  # [���n���l]
  # �@$body_plain                : ÷�Ė{��
  # �@$body_html                 : HTML�{��
  # �@$UPFILELIST                : ����۰��̧��ؽ�
  # �@$content_transfer_encoding : �ݺ��޺���
  # �@$to_career                 : ���M�淬ر
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_3($body_plain,$body_html,$UPFILELIST,$content_transfer_encoding,$to_career) {
    global $emoji_obj;

    # �޳���ذ�ݒ�
    $boundary = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
    $mail_header_ptn .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # ÷�Ė{���߰Đݒ�
    $mail_ptn .= "--{$boundary}\n";
    $mail_ptn .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_plain;
    $mail_ptn .= "\n";
    # HTML�{���߰Đݒ�
    $mail_ptn .= "--{$boundary}\n";
    if ($to_career == 'PC') {
      # PC����
      $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
      $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    } else {
      # �g�ш���
      if ($content_transfer_encoding == '7bit') {
        if (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
          $mail_ptn .= "Content-Type: text/html; charset=\"UTF-8\"\n";
          $mail_ptn .= "Content-Transfer-Encoding: quoted-printable\n";
        } else {
          $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
          $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
        }
      } else {
        $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
        $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
      }
    }
    $mail_ptn .= "\n";
    $mail_ptn .= $body_html."\n";
    $mail_ptn .= "\n";
    # �Y�ţ�ْǉ�
    $mail_ptn .= $this->_addfile($UPFILELIST,$boundary);
    # �߰ďI���޳���ذ
    $mail_ptn .= "--{$boundary}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������4 /////////////////////////////////////////////////////////////////
  # Ұٌ`�������4(�g�їp) - ÷�Ė{�� + HTML�{��
  # [���n���l]
  # �@$body_plain                : ÷�Ė{��
  # �@$body_html                 : HTML�{��
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_4($body_plain,$body_html,$content_transfer_encoding,$to_career) {
    global $emoji_obj;

    # �޳���ذ�ݒ�
    $boundary_1 = md5(uniqid(rand()));
    $boundary_2 = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
    $mail_header_ptn .= "Content-Type: multipart/mixed; boundary=\"{$boundary_1}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # ����߰�ͯ�ް�ݒ�
    $mail_ptn .= "--{$boundary_1}\n";
    $mail_ptn .= "Content-Type: multipart/alternative; boundary=\"{$boundary_2}\"\n";
    $mail_ptn .= "\n";
    # ÷�Ė{���߰Đݒ�
    $mail_ptn .= "--{$boundary_2}\n";
    $mail_ptn .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_plain;
    $mail_ptn .= "\n";
    # HTML�{���߰Đݒ�
    $mail_ptn .= "--{$boundary_2}\n";
    if ($content_transfer_encoding == '7bit') {
      if (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
        $mail_ptn .= "Content-Type: text/html; charset=\"UTF-8\"\n";
        $mail_ptn .= "Content-Transfer-Encoding: quoted-printable\n";
      } else {
        $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
        $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
      }
    } else {
      $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
      $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    }
    $mail_ptn .= "\n";
    $mail_ptn .= $body_html."\n";
    $mail_ptn .= "\n";
    # �߰�2�I���޳���ذ
    $mail_ptn .= "--{$boundary_2}--\n";
    # �߰�1�I���޳���ذ
    $mail_ptn .= "--{$boundary_1}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������5 /////////////////////////////////////////////////////////////////
  # Ұٌ`�������5(�g�їp) - ÷�Ė{�� + HTML�{�� + ��ײ݉摜
  # [���n���l]
  # �@$body_plain                : ÷�Ė{��
  # �@$body_html                 : HTML�{��
  # �@$INLINEFILE                : ��ײ݉摜̧��ؽ�
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_5($body_plain,$body_html,$INLINEFILE,$content_transfer_encoding,$to_career) {
    global $emoji_obj;

    # �޳���ذ�ݒ�
    $boundary_1 = md5(uniqid(rand()));
    $boundary_2 = md5(uniqid(rand()));
    $boundary_3 = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
    $mail_header_ptn .= "Content-Type: multipart/mixed; boundary=\"{$boundary_1}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # ����߰�ͯ�ް1�ݒ�
    $mail_ptn .= "--{$boundary_1}\n";
    $mail_ptn .= "Content-Type: multipart/related; boundary=\"{$boundary_2}\"\n";
    $mail_ptn .= "\n";
    # ����߰�ͯ�ް2�ݒ�
    $mail_ptn .= "--{$boundary_2}\n";
    $mail_ptn .= "Content-Type: multipart/alternative; boundary=\"{$boundary_3}\"\n";
    $mail_ptn .= "\n";
    # ÷�Ė{���߰Đݒ�
    $mail_ptn .= "--{$boundary_3}\n";
    $mail_ptn .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_plain;
    $mail_ptn .= "\n";
    # HTML�{���߰Đݒ�
    $mail_ptn .= "--{$boundary_3}\n";
    if ($content_transfer_encoding == '7bit') {
      if (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
        $mail_ptn .= "Content-Type: text/html; charset=\"UTF-8\"\n";
        $mail_ptn .= "Content-Transfer-Encoding: quoted-printable\n";
      } else {
        $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
        $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
      }
    } else {
      $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
      $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    }
    $mail_ptn .= "\n";
    $mail_ptn .= $body_html."\n";
    $mail_ptn .= "\n";
    # �߰�3�I���޳���ذ
    $mail_ptn .= "--{$boundary_3}--\n";
    # ��ײ݉摜�߰Đݒ�
    $mail_ptn .= $this->_inlinefile($INLINEFILE,$boundary_2);
    # �߰�2�I���޳���ذ
    $mail_ptn .= "--{$boundary_2}--\n";
    # �߰�1�I���޳���ذ
    $mail_ptn .= "--{$boundary_1}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������5(au�g�ѐ�p) /////////////////////////////////////////////////////
  # Ұٌ`�������5(au�g�ѐ�p) - ÷�Ė{�� + HTML�{�� + ��ײ݉摜
  # [���n���l]
  # �@$body_plain                : ÷�Ė{��
  # �@$body_html                 : HTML�{��
  # �@$INLINEFILE                : ��ײ݉摜̧��ؽ�
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_5_au($body_plain,$body_html,$INLINEFILE,$content_transfer_encoding,$to_career) {
    global $emoji_obj;

    # �޳���ذ�ݒ�
    $boundary_1 = md5(uniqid(rand()));
    $boundary_2 = md5(uniqid(rand()));
    $boundary_3 = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
#    $mail_header_ptn .= "Content-Type: multipart/mixed; boundary=\"{$boundary_1}\"\n";
    $mail_header_ptn .= "Content-Type: multipart/mixed; boundary=\"{$boundary_2}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # ����߰�ͯ�ް1�ݒ�
#    $mail_ptn .= "--{$boundary_1}\n";
#    $mail_ptn .= "Content-Type: multipart/related; boundary=\"{$boundary_2}\"\n";
#    $mail_ptn .= "\n";
    # ����߰�ͯ�ް2�ݒ�
    $mail_ptn .= "--{$boundary_2}\n";
    $mail_ptn .= "Content-Type: multipart/alternative; boundary=\"{$boundary_3}\"\n";
    $mail_ptn .= "\n";
    # ÷�Ė{���߰Đݒ�
    $mail_ptn .= "--{$boundary_3}\n";
    $mail_ptn .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_plain;
    $mail_ptn .= "\n";
    # HTML�{���߰Đݒ�
    $mail_ptn .= "--{$boundary_3}\n";
    if ($content_transfer_encoding == '7bit') {
      if (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
        $mail_ptn .= "Content-Type: text/html; charset=\"UTF-8\"\n";
        $mail_ptn .= "Content-Transfer-Encoding: quoted-printable\n";
      } else {
        $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
        $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
      }
    } else {
      $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
      $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    }
    $mail_ptn .= "\n";
    $mail_ptn .= $body_html."\n";
    $mail_ptn .= "\n";
    # �߰�3�I���޳���ذ
    $mail_ptn .= "--{$boundary_3}--\n";
    # ��ײ݉摜�߰Đݒ�
    $mail_ptn .= $this->_inlinefile($INLINEFILE,$boundary_2);
    # �߰�2�I���޳���ذ
    $mail_ptn .= "--{$boundary_2}--\n";
    # �߰�1�I���޳���ذ
#    $mail_ptn .= "--{$boundary_1}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������5(SoftBank�g�ѐ�p) ///////////////////////////////////////////////
  # Ұٌ`�������5(Softbank�g�ѐ�p) - ÷�Ė{�� + HTML�{�� + ��ײ݉摜
  # [���n���l]
  # �@$body_plain                : ÷�Ė{��
  # �@$body_html                 : HTML�{��
  # �@$INLINEFILE                : ��ײ݉摜̧��ؽ�
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_5_sb($body_plain,$body_html,$INLINEFILE,$content_transfer_encoding,$to_career) {
    global $emoji_obj;

    # �޳���ذ�ݒ�
    $boundary_1 = md5(uniqid(rand()));
    $boundary_2 = md5(uniqid(rand()));
    $boundary_3 = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
#    $mail_header_ptn .= "Content-Type: multipart/mixed; boundary=\"{$boundary_1}\"\n";
    $mail_header_ptn .= "Content-Type: multipart/related; boundary=\"{$boundary_2}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # ����߰�ͯ�ް1�ݒ�
#    $mail_ptn .= "--{$boundary_1}\n";
#    $mail_ptn .= "Content-Type: multipart/related; boundary=\"{$boundary_2}\"\n";
#    $mail_ptn .= "\n";
    # ����߰�ͯ�ް2�ݒ�
    $mail_ptn .= "--{$boundary_2}\n";
    $mail_ptn .= "Content-Type: multipart/alternative; boundary=\"{$boundary_3}\"\n";
    $mail_ptn .= "\n";
    # ÷�Ė{���߰Đݒ�
    $mail_ptn .= "--{$boundary_3}\n";
    $mail_ptn .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_plain;
    $mail_ptn .= "\n";
    # HTML�{���߰Đݒ�
    $mail_ptn .= "--{$boundary_3}\n";
    if ($content_transfer_encoding == '7bit') {
      if (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
        $mail_ptn .= "Content-Type: text/html; charset=\"UTF-8\"\n";
        $mail_ptn .= "Content-Transfer-Encoding: quoted-printable\n";
      } else {
        $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
        $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
      }
    } else {
      $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
      $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    }
    $mail_ptn .= "\n";
    $mail_ptn .= $body_html."\n";
    $mail_ptn .= "\n";
    # �߰�3�I���޳���ذ
    $mail_ptn .= "--{$boundary_3}--\n";
    # ��ײ݉摜�߰Đݒ�
    $mail_ptn .= $this->_inlinefile($INLINEFILE,$boundary_2);
    # �߰�2�I���޳���ذ
    $mail_ptn .= "--{$boundary_2}--\n";
    # �߰�1�I���޳���ذ
#    $mail_ptn .= "--{$boundary_1}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������6 /////////////////////////////////////////////////////////////////
  # Ұٌ`�������6(�g�їp) - ÷�Ė{�� + HTML�{�� + ��ײ݉摜 + �Y�t
  # [���n���l]
  # �@$body_plain                : ÷�Ė{��
  # �@$body_html                 : HTML�{��
  # �@$INLINEFILE                : ��ײ݉摜̧��ؽ�
  # �@$UPFILELIST                : �Y�ţ��ؽ�
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_6($body_plain,$body_html,$INLINEFILE,$UPFILELIST,$content_transfer_encoding,$to_career) {
    global $emoji_obj;

    # �޳���ذ�ݒ�
    $boundary_1 = md5(uniqid(rand()));
    $boundary_2 = md5(uniqid(rand()));
    $boundary_3 = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
    $mail_header_ptn .= "Content-Type: multipart/mixed; boundary=\"{$boundary_1}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # ����߰�ͯ�ް1�ݒ�
    $mail_ptn .= "--{$boundary_1}\n";
    $mail_ptn .= "Content-Type: multipart/related; boundary=\"{$boundary_2}\"\n";
    $mail_ptn .= "\n";
    # ����߰�ͯ�ް2�ݒ�
    $mail_ptn .= "--{$boundary_2}\n";
    $mail_ptn .= "Content-Type: multipart/alternative; boundary=\"{$boundary_3}\"\n";
    $mail_ptn .= "\n";
    # ÷�Ė{���߰Đݒ�
    $mail_ptn .= "--{$boundary_3}\n";
    $mail_ptn .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_plain;
    $mail_ptn .= "\n";
    # HTML�{���߰Đݒ�
    $mail_ptn .= "--{$boundary_3}\n";
    if ($content_transfer_encoding == '7bit') {
      if (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
        $mail_ptn .= "Content-Type: text/html; charset=\"UTF-8\"\n";
        $mail_ptn .= "Content-Transfer-Encoding: quoted-printable\n";
      } else {
        $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
        $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
      }
    } else {
      $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
      $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    }
    $mail_ptn .= "\n";
    $mail_ptn .= $body_html."\n";
    $mail_ptn .= "\n";
    # �߰�3�I���޳���ذ
    $mail_ptn .= "--{$boundary_3}--\n";
    # ��ײ݉摜�߰Đݒ�
    $mail_ptn .= $this->_inlinefile($INLINEFILE,$boundary_2);
    # �߰�2�I���޳���ذ
    $mail_ptn .= "--{$boundary_2}--\n";
    # �Y�ţ�ْǉ�
    $mail_ptn .= $this->_addfile($UPFILELIST,$boundary_1);
    # �߰�1�I���޳���ذ
    $mail_ptn .= "--{$boundary_1}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������6(au�g�ѐ�p) /////////////////////////////////////////////////////
  # Ұٌ`�������6(au�g�ѐ�p) - ÷�Ė{�� + HTML�{�� + ��ײ݉摜 + �Y�t
  # [���n���l]
  # �@$body_plain                : ÷�Ė{��
  # �@$body_html                 : HTML�{��
  # �@$INLINEFILE                : ��ײ݉摜̧��ؽ�
  # �@$UPFILELIST                : �Y�ţ��ؽ�
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_6_au($body_plain,$body_html,$INLINEFILE,$UPFILELIST,$content_transfer_encoding,$to_career) {
    global $emoji_obj;

    # �޳���ذ�ݒ�
    $boundary_1 = md5(uniqid(rand()));
    $boundary_2 = md5(uniqid(rand()));
    $boundary_3 = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
#    $mail_header_ptn .= "Content-Type: multipart/mixed; boundary=\"{$boundary_1}\"\n";
    $mail_header_ptn .= "Content-Type: multipart/mixed; boundary=\"{$boundary_2}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # ����߰�ͯ�ް1�ݒ�
#    $mail_ptn .= "--{$boundary_1}\n";
#    $mail_ptn .= "Content-Type: multipart/related; boundary=\"{$boundary_2}\"\n";
#    $mail_ptn .= "\n";
    # ����߰�ͯ�ް2�ݒ�
    $mail_ptn .= "--{$boundary_2}\n";
    $mail_ptn .= "Content-Type: multipart/alternative; boundary=\"{$boundary_3}\"\n";
    $mail_ptn .= "\n";
    # ÷�Ė{���߰Đݒ�
    $mail_ptn .= "--{$boundary_3}\n";
    $mail_ptn .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_plain;
    $mail_ptn .= "\n";
    # HTML�{���߰Đݒ�
    $mail_ptn .= "--{$boundary_3}\n";
    if ($content_transfer_encoding == '7bit') {
      if (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
        $mail_ptn .= "Content-Type: text/html; charset=\"UTF-8\"\n";
        $mail_ptn .= "Content-Transfer-Encoding: quoted-printable\n";
      } else {
        $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
        $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
      }
    } else {
      $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
      $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    }
    $mail_ptn .= "\n";
    $mail_ptn .= $body_html."\n";
    $mail_ptn .= "\n";
    # �߰�3�I���޳���ذ
    $mail_ptn .= "--{$boundary_3}--\n";
    # ��ײ݉摜�߰Đݒ�
    $mail_ptn .= $this->_inlinefile($INLINEFILE,$boundary_2);
    # �߰�2�I���޳���ذ
#    $mail_ptn .= "--{$boundary_2}--\n";
    # �Y�ţ�ْǉ�
    $mail_ptn .= $this->_addfile($UPFILELIST,$boundary_1);
    # �߰�1�I���޳���ذ
#    $mail_ptn .= "--{$boundary_1}--\n";
    $mail_ptn .= "--{$boundary_2}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������6(Softbank�g�ѐ�p) ///////////////////////////////////////////////
  # Ұٌ`�������6(Softbank�g�ѐ�p) - ÷�Ė{�� + HTML�{�� + ��ײ݉摜 + �Y�t
  # [���n���l]
  # �@$body_plain                : ÷�Ė{��
  # �@$body_html                 : HTML�{��
  # �@$INLINEFILE                : ��ײ݉摜̧��ؽ�
  # �@$UPFILELIST                : �Y�ţ��ؽ�
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_6_sb($body_plain,$body_html,$INLINEFILE,$UPFILELIST,$content_transfer_encoding,$to_career) {
    global $emoji_obj;

    # �޳���ذ�ݒ�
    $boundary_1 = md5(uniqid(rand()));
    $boundary_2 = md5(uniqid(rand()));
    $boundary_3 = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
#    $mail_header_ptn .= "Content-Type: multipart/mixed; boundary=\"{$boundary_1}\"\n";
    $mail_header_ptn .= "Content-Type: multipart/related; boundary=\"{$boundary_2}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # ����߰�ͯ�ް1�ݒ�
#    $mail_ptn .= "--{$boundary_1}\n";
#    $mail_ptn .= "Content-Type: multipart/related; boundary=\"{$boundary_2}\"\n";
#    $mail_ptn .= "\n";
    # ����߰�ͯ�ް2�ݒ�
    $mail_ptn .= "--{$boundary_2}\n";
    $mail_ptn .= "Content-Type: multipart/alternative; boundary=\"{$boundary_3}\"\n";
    $mail_ptn .= "\n";
    # ÷�Ė{���߰Đݒ�
    $mail_ptn .= "--{$boundary_3}\n";
    $mail_ptn .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_plain;
    $mail_ptn .= "\n";
    # HTML�{���߰Đݒ�
    $mail_ptn .= "--{$boundary_3}\n";
    if ($content_transfer_encoding == '7bit') {
      if (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
        $mail_ptn .= "Content-Type: text/html; charset=\"UTF-8\"\n";
        $mail_ptn .= "Content-Transfer-Encoding: quoted-printable\n";
      } else {
        $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
        $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
      }
    } else {
      $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
      $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    }
    $mail_ptn .= "\n";
    $mail_ptn .= $body_html."\n";
    $mail_ptn .= "\n";
    # �߰�3�I���޳���ذ
    $mail_ptn .= "--{$boundary_3}--\n";
    # ��ײ݉摜�߰Đݒ�
    $mail_ptn .= $this->_inlinefile($INLINEFILE,$boundary_2);
    # �߰�2�I���޳���ذ
#    $mail_ptn .= "--{$boundary_2}--\n";
    # �Y�ţ�ْǉ�
    $mail_ptn .= $this->_addfile($UPFILELIST,$boundary_1);
    # �߰�1�I���޳���ذ
#    $mail_ptn .= "--{$boundary_1}--\n";
    $mail_ptn .= "--{$boundary_2}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������7 /////////////////////////////////////////////////////////////////
  # Ұٌ`�������7(PC�p) - ÷�Ė{�� + HTML�{��
  # [���n���l]
  # �@$body_plain                : ÷�Ė{��
  # �@$body_html                 : HTML�{��
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_7($body_plain,$body_html,$content_transfer_encoding) {

    # �޳���ذ�ݒ�
    $boundary = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
    $mail_header_ptn .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # ÷�Ė{���߰Đݒ�
    $mail_ptn .= "--{$boundary}\n";
    $mail_ptn .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_plain;
    $mail_ptn .= "\n";
    # HTML�{���߰Đݒ�
    $mail_ptn .= "--".$boundary."\n";
    $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_html."\n";
    $mail_ptn .= "\n";
    # �߰ďI���޳���ذ
    $mail_ptn .= "--{$boundary}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������8 /////////////////////////////////////////////////////////////////
  # Ұٌ`�������8(PC�p) - ÷�Ė{�� + HTML�{�� + ��ײ݉摜
  # [���n���l]
  # �@$body_plain                : ÷�Ė{��
  # �@$body_html                 : HTML�{��
  # �@$INLINEFILE                : ��ײ݉摜̧��ؽ�
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_8($body_plain,$body_html,$INLINEFILE,$content_transfer_encoding) {

    # �޳���ذ�ݒ�
    $boundary_1 = md5(uniqid(rand()));
    $boundary_2 = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
    $mail_header_ptn .= "Content-Type: multipart/alternative; boundary=\"{$boundary_1}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # ÷�Ė{���߰Đݒ�
    $mail_ptn .= "--{$boundary_1}\n";
    $mail_ptn .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_plain;
    $mail_ptn .= "\n";
    # ����߰�ͯ�ް1�ݒ�
    $mail_ptn .= "--{$boundary_1}\n";
    $mail_ptn .= "Content-Type: multipart/related; boundary=\"{$boundary_2}\"\n";
    $mail_ptn .= "\n";
    # HTML�{���߰Đݒ�
    $mail_ptn .= "--{$boundary_2}\n";
    $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_html."\n";
    $mail_ptn .= "\n";
    # ��ײ݉摜�߰Đݒ�
    $mail_ptn .= $this->_inlinefile($INLINEFILE,$boundary_2);
    # �߰�2�I���޳���ذ
    $mail_ptn .= "--{$boundary_2}--\n";
    # �߰�1�I���޳���ذ
    $mail_ptn .= "--{$boundary_1}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������9 /////////////////////////////////////////////////////////////////
  # Ұٌ`�������9(PC�p) - ÷�Ė{�� + HTML�{�� + ��ײ݉摜 + �Y�t
  # [���n���l]
  # �@$body_plain                : ÷�Ė{��
  # �@$body_html                 : HTML�{��
  # �@$INLINEFILE                : ��ײ݉摜̧��ؽ�
  # �@$UPFILELIST                : �Y�ţ��ؽ�
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_9($body_plain,$body_html,$INLINEFILE,$UPFILELIST,$content_transfer_encoding) {

    # �޳���ذ�ݒ�
    $boundary_1 = md5(uniqid(rand()));
    $boundary_2 = md5(uniqid(rand()));
    $boundary_3 = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
    $mail_header_ptn .= "Content-Type: multipart/mixed; boundary=\"{$boundary_1}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # ����߰�ͯ�ް1�ݒ�
    $mail_ptn .= "--{$boundary_1}\n";
    $mail_ptn .= "Content-Type: multipart/alternative; boundary=\"{$boundary_2}\"\n";
    $mail_ptn .= "\n";
    # ÷�Ė{���߰Đݒ�
    $mail_ptn .= "--{$boundary_2}\n";
    $mail_ptn .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_plain;
    $mail_ptn .= "\n";
    # ����߰�ͯ�ް2�ݒ�
    $mail_ptn .= "--{$boundary_2}\n";
    $mail_ptn .= "Content-Type: multipart/related; boundary=\"{$boundary_3}\"\n";
    $mail_ptn .= "\n";
    # HTML�{���߰Đݒ�
    $mail_ptn .= "--{$boundary_3}\n";
    $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_html."\n";
    $mail_ptn .= "\n";
    # ��ײ݉摜�߰Đݒ�
    $mail_ptn .= $this->_inlinefile($INLINEFILE,$boundary_3);
    # �߰�3�I���޳���ذ
    $mail_ptn .= "--{$boundary_3}--\n";

#    # �Y�ţ�ْǉ�
#    $mail_ptn .= $this->_addfile($UPFILELIST,$boundary_2);
#    # �߰�2�I���޳���ذ
#    $mail_ptn .= "--{$boundary_2}--\n";
#    # �߰�1�I���޳���ذ
#    $mail_ptn .= "--{$boundary_1}--\n";

    # �߰�2�I���޳���ذ
    $mail_ptn .= "--{$boundary_2}--\n";
    # �Y�ţ�ْǉ�
    $mail_ptn .= $this->_addfile($UPFILELIST,$boundary_1);
    # �߰�1�I���޳���ذ
    $mail_ptn .= "--{$boundary_1}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������10 ////////////////////////////////////////////////////////////////
  # Ұٌ`�������10(PC) - HTML�{���̂�
  # [���n���l]
  # �@$body_html                 : HTML�{��
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_10($body_html,$content_transfer_encoding) {

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
    $mail_header_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
    $mail_header_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    $mail_ptn .= $body_html."\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������11 ////////////////////////////////////////////////////////////////
  # Ұٌ`�������11(PC�p) - HTML�{�� + �Y�t
  # [���n���l]
  # �@$body_html                 : HTML�{��
  # �@$UPFILELIST                : �Y�ţ��ؽ�
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_11($body_html,$UPFILELIST,$content_transfer_encoding) {

    # �޳���ذ�ݒ�
    $boundary = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
    $mail_header_ptn .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # HTML�{���߰Đݒ�
    $mail_ptn .= "--{$boundary}\n";
    $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_html."\n";
    $mail_ptn .= "\n";
    # �Y�ţ�ْǉ�
    $mail_ptn .= $this->_addfile($UPFILELIST,$boundary);
    # �߰ďI���޳���ذ
    $mail_ptn .= "--{$boundary}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������12 ////////////////////////////////////////////////////////////////
  # Ұٌ`�������12(PC�p) - HTML�{�� + ��ײ݉摜
  # [���n���l]
  # �@$body_html                 : HTML�{��
  # �@$INLINEFILE                : ��ײ݉摜̧��ؽ�
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_12($body_html,$INLINEFILE,$content_transfer_encoding) {

    # �޳���ذ�ݒ�
    $boundary = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
    $mail_header_ptn .= "Content-Type: multipart/related; boundary=\"{$boundary}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # HTML�{���߰Đݒ�
    $mail_ptn .= "--{$boundary}\n";
    $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_html."\n";
    $mail_ptn .= "\n";
    # ��ײ݉摜�߰Đݒ�
    $mail_ptn .= $this->_inlinefile($INLINEFILE,$boundary);
    # �߰ďI���޳���ذ
    $mail_ptn .= "--{$boundary}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������13 ////////////////////////////////////////////////////////////////
  # Ұٌ`�������13(PC�p) - HTML�{�� + ��ײ݉摜 + �Y�t
  # [���n���l]
  # �@$body_html                 : HTML�{��
  # �@$INLINEFILE                : ��ײ݉摜̧��ؽ�
  # �@$UPFILELIST                : �Y�ţ��ؽ�
  # �@$content_transfer_encoding : �ݺ��޺���
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_13($body_html,$INLINEFILE,$UPFILELIST,$content_transfer_encoding) {

    # �޳���ذ�ݒ�
    $boundary_1 = md5(uniqid(rand()));
    $boundary_2 = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
    $mail_header_ptn .= "Content-Type: multipart/mixed; boundary=\"{$boundary_1}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # ����߰�ͯ�ް1�ݒ�
    $mail_ptn .= "--{$boundary_1}\n";
    $mail_ptn .= "Content-Type: multipart/related; boundary=\"{$boundary_2}\"\n";
    $mail_ptn .= "\n";
    # HTML�{���߰Đݒ�
    $mail_ptn .= "--{$boundary_2}\n";
    $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_html."\n";
    $mail_ptn .= "\n";
    # ��ײ݉摜�߰Đݒ�
    $mail_ptn .= $this->_inlinefile($INLINEFILE,$boundary_2);
    # �߰�2�I���޳���ذ
    $mail_ptn .= "--{$boundary_3}--\n";
    # �Y�ţ�ْǉ�
    $mail_ptn .= $this->_addfile($UPFILELIST,$boundary_1);
    # �߰�1�I���޳���ذ
    $mail_ptn .= "--{$boundary_1}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # Ұ������14 ////////////////////////////////////////////////////////////////
  # Ұٌ`�������14(PC�p) - ÷�Ė{�� + HTML�{�� + �Y�ţ��
  # [���n���l]
  # �@$body_plain                : ÷�Ė{��
  # �@$body_html                 : HTML�{��
  # �@$UPFILELIST                : ����۰��̧��ؽ�
  # �@$content_transfer_encoding : �ݺ��޺���
  # �@$to_career                 : ���M�淬ر
  # [�Ԃ�l]
  # �@$mail_header_ptn : �ǉ�ͯ�ް
  # �@$mail_ptn        : �{��
  #////////////////////////////////////////////////////////////////////////////
  function _mail_ptn_14($body_plain,$body_html,$UPFILELIST,$content_transfer_encoding) {

    # �޳���ذ�ݒ�
    $boundary_1 = md5(uniqid(rand()));
    $boundary_2 = md5(uniqid(rand()));

    # Ұْǉ�ͯ�ް�ݒ�
    $mail_header_ptn  = '';
    $mail_header_ptn .= "Content-Type: multipart/alternative; boundary=\"{$boundary_1}\"\n";
#    $mail_header_ptn .= "This is a multi-part message in MIME format.";

    # Ұٖ{���ݒ�
    $mail_ptn  = '';
    # ÷�Ė{���߰Đݒ�
    $mail_ptn .= "--{$boundary_1}\n";
    $mail_ptn .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_plain;
    $mail_ptn .= "\n";
    # ����߰�ͯ�ް1�ݒ�
    $mail_ptn .= "--{$boundary_1}\n";
    $mail_ptn .= "Content-Type: multipart/related; boundary=\"{$boundary_2}\"\n";
    $mail_ptn .= "\n";
    # HTML�{���߰Đݒ�
    $mail_ptn .= "--{$boundary_2}\n";
    $mail_ptn .= "Content-Type: text/html; charset=\"ISO-2022-JP\"\n";
    $mail_ptn .= "Content-Transfer-Encoding: {$content_transfer_encoding}\n";
    $mail_ptn .= "\n";
    $mail_ptn .= $body_html."\n";
    $mail_ptn .= "\n";
    # �Y�ţ���߰Đݒ�
    $mail_ptn .= $this->_addfile($UPFILELIST,$boundary_2);
    # �߰�2�I���޳���ذ
    $mail_ptn .= "--{$boundary_2}--\n";
    # �߰�1�I���޳���ذ
    $mail_ptn .= "--{$boundary_1}--\n";

    return array($mail_header_ptn,$mail_ptn);
  }

  # ��ײ݉摜�߰ď��� /////////////////////////////////////////////////////////
  # ��ײ݉摜�߰ď���
  # [���n���l]
  # �@$INLINEFILE : ��ײ݉摜̧��ؽ�
  # �@$boundary   : �޳���ذNo
  # [�Ԃ�l]
  # �@$inlinefile_part : ��ײ݉摜�߰�
  #////////////////////////////////////////////////////////////////////////////
  function _inlinefile($INLINEFILE,$boundary) {
    $inlinefile_part = '';
    foreach ($INLINEFILE as $kdt => $IDT) {
      $inlinefile_part .= "--{$boundary}\n";
      $inlinefile_part .= "Content-Type: {$IDT['mime']};\n";
      $inlinefile_part .= "\tname=\"{$IDT['name']}\"\n";
      $inlinefile_part .= "Content-Transfer-Encoding: base64\n";
      $inlinefile_part .= "Content-ID: <{$kdt}>\n";
      $inlinefile_part .= "\n";
#      $inlinefile_part .= $IDT['data']."\n";
      $inlinefile_part .= $IDT['data'];
      $inlinefile_part .= "\n";
    }
    return $inlinefile_part;
  }

  # ̧�ٓY�t�߰ď��� //////////////////////////////////////////////////////////////
  # ̧�ٓY�t�߰ď���
  # [���n���l]
  # �@$UPFILELIST : �Y�ţ��ؽ�
  # �@$boundary   : �޳���ذNo
  # [�Ԃ�l]
  # �@$addfile_part : ̧�ٓY�t�߰�
  #////////////////////////////////////////////////////////////////////////////
  function _addfile($UPFILELIST,$boundary) {
    $addfile_part = '';
    foreach ($UPFILELIST as $kdt => $UDT) {
      $addfile_part .= "--{$boundary}\n";
      $addfile_part .= "Content-Type: {$UDT['mime']};\n";
      $addfile_part .= "\tname=\"{$UDT['basename']}\"\n";
      $addfile_part .= "Content-Transfer-Encoding: base64\n";
      $addfile_part .= "Content-Disposition: attachment;\n";
      if ($UDT['basename_flag'] == True) {
        $addfile_part .= "\tfilename*=ISO-2022-JP''{$UDT['basename_fname']}\n\n";
      } else {
        $addfile_part .= "\tfilename=\"{$UDT['basename_fname']}\"\n\n";
      }
#      $addfile_part .= $UDT['filedata']."\n";
      $addfile_part .= $UDT['filedata'];
      $addfile_part .= "\n";
    }
    return $addfile_part;
  }

  # ��ײ݉摜�������� /////////////////////////////////////////////////////////
  # ��ײ݉摜���������s���܂��B
  # [���n���l]
  # �@$INLINEFILE : ��ײ݉摜ؽ�
  # �@$to_career  : ���M�淬ر
  # [�Ԃ�l]
  # �@$check_flag : ̧�ٓY�t�߰�
  #////////////////////////////////////////////////////////////////////////////
  function _inline_check($INLINEFILE,$to_career) {
    global $emoji_obj;

    $check_flag = True;

    # ��ײ݉摜��������
    $total_size = 0;
    foreach ($INLINEFILE as $kdt => $IDT) {
      $total_size += $IDT['size'];
      if ($to_career == 'PC') {
        # PC�p��ײ݉摜����
        if (($this->inline_max_size_pc > 0) and ($this->inline_max_size_pc < $IDT['size'])) { $check_flag = False; }
      } elseif ($to_career == 'DoCoMo') {
        # DoCoMo�p��ײ݉摜����
        if (($this->inline_max_size_docomo > 0) and ($this->inline_max_size_docomo < $IDT['size'])) { $check_flag = False; }
      } elseif ($to_career == 'au') {
        # au�p��ײ݉摜����
        if (($this->inline_max_size_au > 0) and ($this->inline_max_size_au < $IDT['size'])) { $check_flag = False; }
      } elseif (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
        # SoftBank�p��ײ݉摜����
        if (($this->inline_max_size_softbank > 0) and ($this->inline_max_size_softbank < $IDT['size'])) { $check_flag = False; }
      }
    }
    if ($to_career == 'PC') {
      # PC�p��ײ݉摜����
      # ��ײ݉摜������
      if (($this->inline_max_num_pc > 0) and ($this->inline_max_num_pc < count($INLINEFILE))) { $check_flag = False; }
      # ��ײ݉摜İ�ٻ�������
      if (($this->inline_all_max_size_pc > 0) and ($this->inline_all_max_size_pc < $total_size)) { $check_flag = False; }
    } elseif ($to_career == 'DoCoMo') {
      # DoCoMo�p��ײ݉摜����
      # ��ײ݉摜������
      if (($this->inline_max_num_docomo > 0) and ($this->inline_max_num_docomo < count($INLINEFILE))) { $check_flag = False; }
      # ��ײ݉摜İ�ٻ�������
      if (($this->inline_all_max_size_docomo > 0) and ($this->inline_all_max_size_docomo < $total_size)) { $check_flag = False; }
    } elseif ($to_career == 'au') {
      # au�p��ײ݉摜����
      # ��ײ݉摜������
      if (($this->inline_max_num_au > 0) and ($this->inline_max_num_au < count($INLINEFILE))) { $check_flag = False; }
      # ��ײ݉摜İ�ٻ�������
      if (($this->inline_all_max_size_au > 0) and ($this->inline_all_max_size_au < $total_size)) { $check_flag = False; }
    } elseif (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
      # SoftBank�p��ײ݉摜����
      # ��ײ݉摜������
      if (($this->inline_max_num_softbank > 0) and ($this->inline_max_num_softbank < count($INLINEFILE))) { $check_flag = False; }
      # ��ײ݉摜İ�ٻ�������
      if (($this->inline_all_max_size_softbank > 0) and ($this->inline_all_max_size_softbank < $total_size)) { $check_flag = False; }
    }
    return $check_flag;
  }

  # �Y�ţ���������� /////////////////////////////////////////////////////////
  # �Y�ţ�ق��������s���܂��B
  # [���n���l]
  # �@$UPFILELIST : �Y�ţ��ؽ�
  # �@$to_career  : ���M�淬ر
  # [�Ԃ�l]
  # �@$check_flag : ̧�ٓY�t�߰�
  #////////////////////////////////////////////////////////////////////////////
  function _upfile_check($UPFILELIST,$to_career) {
    global $emoji_obj;

    $check_flag = True;

    # �Y�ţ�ٻ�������
    $total_size = 0;
    foreach ($UPFILELIST as $kdt => $UDT) {
      $total_size += $UDT['size'];
      if ($to_career == 'PC') {
        # PC�p�Y�ţ������
        if (($this->upfile_max_size_pc > 0) and ($this->upfile_max_size_pc < $UDT['size'])) { $check_flag = False; }
      } elseif ($to_career == 'DoCoMo') {
        # DoCoMo�p�Y�ţ������
        if (($this->upfile_max_size_docomo > 0) and ($this->upfile_max_size_docomo < $UDT['size'])) { $check_flag = False; }
      } elseif ($to_career == 'au') {
        # au�p�Y�ţ������
        if (($this->upfile_max_size_au > 0) and ($this->upfile_max_size_au < $UDT['size'])) { $check_flag = False; }
      } elseif (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
        # SoftBank�p�Y�ţ������
        if (($this->upfile_max_size_softbank > 0) and ($this->upfile_max_size_softbank < $UDT['size'])) { $check_flag = False; }
      }
    }
    if ($to_career == 'PC') {
      # PC�p�Y�ţ������
      # �Y�ţ�ِ�����
      if (($this->upfile_max_num_pc > 0) and ($this->upfile_max_num_pc < count($INLINEFILE))) { $check_flag = False; }
      # �Y�ţ��İ�ٻ�������
      if (($this->upfile_all_max_size_pc > 0) and ($this->upfile_all_max_size_pc < $total_size)) { $check_flag = False; }
    } elseif ($to_career == 'DoCoMo') {
      # DoCoMo�p�Y�ţ������
      # �Y�ţ�ِ�����
      if (($this->upfile_max_num_docomo > 0) and ($this->upfile_max_num_docomo < count($INLINEFILE))) { $check_flag = False; }
      # �Y�ţ��İ�ٻ�������
      if (($this->upfile_all_max_size_docomo > 0) and ($this->upfile_all_max_size_docomo < $total_size)) { $check_flag = False; }
    } elseif ($to_career == 'au') {
      # au�p�Y�ţ������
      # �Y�ţ�ِ�����
      if (($this->upfile_max_num_au > 0) and ($this->upfile_max_num_au < count($INLINEFILE))) { $check_flag = False; }
      # �Y�ţ��İ�ٻ�������
      if (($this->upfile_all_max_size_au > 0) and ($this->upfile_all_max_size_au < $total_size)) { $check_flag = False; }
    } elseif (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
      # SoftBank�p�Y�ţ������
      # �Y�ţ�ِ�����
      if (($this->upfile_max_num_softbank > 0) and ($this->upfile_max_num_softbank < count($INLINEFILE))) { $check_flag = False; }
      # �Y�ţ��İ�ٻ�������
      if (($this->upfile_all_max_size_softbank > 0) and ($this->upfile_all_max_size_softbank < $total_size)) { $check_flag = False; }
    }
    return $check_flag;
  }

  # �Y�ţ���������� /////////////////////////////////////////////////////////
  # �Y�ţ�ق��������s���܂��B
  # [���n���l]
  # �@$INLINEFILE : ��ײ݉摜ؽ�
  # �@$UPFILELIST : �Y�ţ��ؽ�
  # �@$to_career  : ���M�淬ر
  # [�Ԃ�l]
  # �@$check_flag : ̧�ٓY�t�߰�
  #////////////////////////////////////////////////////////////////////////////
  function _all_file_check($INLINEFILE,$UPFILELIST,$to_career) {
    global $emoji_obj;

    $check_flag = True;

    $total_file_num = count($INLINEFILE) + count($UPFILELIST);
    $total_size = 0;
    foreach ($INLINEFILE as $kdt => $IDT) { $total_size += $IDT['size']; }
    foreach ($UPFILELIST as $kdt => $UDT) { $total_size += $UDT['size']; }
    if ($to_career == 'PC') {
      # PC�p�Y�ţ������
      # İ��̧�ِ�����
      if (($this->allfile_max_num_pc > 0) and ($this->allfile_max_num_pc < $total_file_num)) { $check_flag = False; }
      # İ��̧�ٻ�������
      if (($this->allfile_max_size_pc > 0) and ($this->allfile_max_size_pc < $total_size)) { $check_flag = False; }
    } elseif ($to_career == 'DoCoMo') {
      # DoCoMo�p�Y�ţ������
      # �Y�ţ�ِ�����
      if (($this->allfile_max_num_docomo > 0) and ($this->allfile_max_num_docomo < $total_file_num)) { $check_flag = False; }
      # �Y�ţ�ٻ�������
      if (($this->allfile_max_size_docomo > 0) and ($this->allfile_max_size_docomo < $total_size)) { $check_flag = False; }
    } elseif ($to_career == 'au') {
      # au�p�Y�ţ������
      # �Y�ţ�ِ�����
      if (($this->allfile_max_num_au > 0) and ($this->allfile_max_num_au < $total_file_num)) { $check_flag = False; }
      # �Y�ţ�ٻ�������
      if (($this->allfile_max_size_au > 0) and ($this->allfile_max_size_au < $total_size)) { $check_flag = False; }
    } elseif (($to_career == 'SoftBank') or ($to_career == $emoji_obj->softbank_name)) {
      # SoftBank�p�Y�ţ������
      # �Y�ţ�ِ�����
      if (($this->allfile_max_num_softbank > 0) and ($this->allfile_max_num_softbank < $total_file_num)) { $check_flag = False; }
      # �Y�ţ�ٻ�������
      if (($this->allfile_max_size_softbank > 0) and ($this->allfile_max_size_softbank < $total_size)) { $check_flag = False; }
    }
    return $check_flag;
  }

}

# Quoted_Printable �ݺ��� /////////////////////////////////////////////////////
function _quoted_printable_encode($sText,$bEmulate_imap_8bit=true) {
  // split text into lines
  $aLines=explode(chr(13).chr(10),$sText);

  for ($i=0;$i<count($aLines);$i++) {
    $sLine =& $aLines[$i];
    if (strlen($sLine)===0) continue; // do nothing, if empty

    $sRegExp = '/[^\x09\x20\x21-\x3C\x3E-\x7E]/e';

    // imap_8bit encodes x09 everywhere, not only at lineends,
    // for EBCDIC safeness encode !"#$@[\]^`{|}~,
    // for complete safeness encode every character :)
    if ($bEmulate_imap_8bit)
      $sRegExp = '/[^\x20\x21-\x3C\x3E-\x7E]/e';

    $sReplmt = 'sprintf( "=%02X", ord ( "$0" ) ) ;';
    $sLine = preg_replace( $sRegExp, $sReplmt, $sLine ); 

    // encode x09,x20 at lineends
    {
      $iLength = strlen($sLine);
      $iLastChar = ord($sLine{$iLength-1});

      //              !!!!!!!!   
      // imap_8_bit does not encode x20 at the very end of a text,
      // here is, where I don't agree with imap_8_bit,
      // please correct me, if I'm wrong,
      // or comment next line for RFC2045 conformance, if you like
      if (!($bEmulate_imap_8bit && ($i==count($aLines)-1)))
         
      if (($iLastChar==0x09)||($iLastChar==0x20)) {
        $sLine{$iLength-1}='=';
        $sLine .= ($iLastChar==0x09)?'09':'20';
      }
    }    // imap_8bit encodes x20 before chr(13), too
    // although IMHO not requested by RFC2045, why not do it safer :)
    // and why not encode any x20 around chr(10) or chr(13)
    if ($bEmulate_imap_8bit) {
      $sLine=str_replace(' =0D','=20=0D',$sLine);
      //$sLine=str_replace(' =0A','=20=0A',$sLine);
      //$sLine=str_replace('=0D ','=0D=20',$sLine);
      //$sLine=str_replace('=0A ','=0A=20',$sLine);
    }

    // finally split into softlines no longer than 76 chars,
    // for even more safeness one could encode x09,x20
    // at the very first character of the line
    // and after soft linebreaks, as well,
    // but this wouldn't be caught by such an easy RegExp                  
    preg_match_all( '/.{1,73}([^=]{0,2})?/', $sLine, $aMatch );
    $sLine = implode( '=' . chr(13).chr(10), $aMatch[0] ); // add soft crlf's
  }

  // join lines into text
  return implode(chr(13).chr(10),$aLines);
}

# Quoted_Printable �ݺ���2 ////////////////////////////////////////////////////
function _quoted_printable($string) {
  $crlf   = "\n" ;
  $string = preg_replace('!(\r\n|\r|\n)!', $crlf, $string) . $crlf ;
  $f[]    = '/([\000-\010\013\014\016-\037\075\177-\377])/e' ;
  $r[]    = "'=' . sprintf('%02X', ord('\\1'))" ;
  $f[]    = '/([\011\040])' . $crlf . '/e' ;
  $r[]    = "'=' . sprintf('%02X', ord('\\1')) . '" . $crlf . "'" ;
  $string = preg_replace($f, $r, $string) ;
  return trim(wordwrap($string, 70, ' =' . $crlf)) ;
}

# URL�Ұْu�����ݸ�u���� ///////////////////////////////////////////////////////////////////
function link_make($string) {
  $string_sub = $string;
  # URL�ݸ�u����
  $pattern     = '/(https?(:\/\/[-_.!~*\'()a-z0-9;\/?:\@&=+\$,%#]+))/i';
  $replacement = '<a href="\1">\1</a>';
  $string      = preg_replace($pattern,$replacement,$string);
  # Ұٱ��ڽ�u����
  $pattern     = '/([a-z0-9_\-.]+@([a-z0-9_\-]+\.)+[a-z]+)/i';
  $replacement = '<a href="mailto:\1">\1</a>';
  $string      = preg_replace($pattern,$replacement,$string);
  return $string;
}

?>
