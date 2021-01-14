<?php
$sub_menu = "990500";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$html_title = 'SMS';

if ($w == 'u') {
    $html_title .= '수정';
    $readonly = ' readonly';

    $sql = " select * from nx_sms where ma_id = '{$ma_id}' And ma_target = 'B' ";
    $ma = sql_fetch($sql);
    if (!$ma['ma_id'])
        alert('등록된 자료가 없습니다.');
} else {
    $html_title .= '입력';
}

$g5['title'] = $html_title;
include_once('./admin.head.php');
?>

<p>SMS 내용에 {이름} , {휴대폰번호} 처럼 내용에 삽입하면 해당 내용에 맞게 변환하여 SMS를 발송합니다.</p>

<form name="fmailform" id="fmailform" action="./sms_update.php" onsubmit="return fmailform_check(this);" method="post">
<input type="hidden" name="w" value="<?php echo $w ?>" id="w">
<input type="hidden" name="ma_id" value="<?php echo $ma['ma_id'] ?>" id="ma_id">
<input type="hidden" name="ma_target" value="B" id="ma_target">
<input type="hidden" name="token" value="" id="token">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="ma_type">종류<strong class="sound_only">필수</strong></label></th>
        <td>
            <div id="js_MMS" class="mb10" style="display: none;">
                <div class="nx-tip-box">내용이 길어 MMS로 발송됩니다.</div>
            </div>
            <div class="radio1_wrap">
                <input type="radio" id="ma_type1" name="ma_type" class="radio1" value="SMS" checked disabled /><label for="ma_type1"><span class="radbox"><span></span></span><span class="txt">SMS</span></label>
                <input type="radio" id="ma_type2" name="ma_type" class="radio1" value="MMS" disabled /><label for="ma_type2"><span class="radbox"><span></span></span><span class="txt">MMS</span></label>
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="ma_subject">제목<strong class="sound_only">필수</strong></label></th>
        <td><input type="text" name="ma_subject" value="<?php echo $ma['ma_subject'] ?>" id="ma_subject" required class="required frm_input" size="100"></td>
    </tr>
    <tr>
        <th scope="row"><label for="ma_content">내용<strong class="sound_only">필수</strong></label></th>
        <td>
			<textarea id="ma_content" name="ma_content" onkeydown="onch_Byte();" onkeyup="onch_Byte();" style="width:50%; height:120px;"><?php echo $ma['ma_content'] ?></textarea>
			<span id="span_byte">0</span> / <span id="js_max_byte">90</span>
		</td>
    </tr>
    </tbody>
    </table>
</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" class="btn_submit" accesskey="s" value="확인">
</div>
</form>

<script>
var byte = 0;
var is_mms = false;

$(document).ready(function() {
    onch_Byte();
});

function fmailform_check(f)
{
    errmsg = "";
    errfld = "";

    check_field(f.ma_subject, "제목을 입력하세요.");
    //check_field(f.ma_content, "내용을 입력하세요.");

	if (getByteLen($("#ma_content").val()) > 2000) {
		alert("내용은 2000Byte까지 입력해 주세요.");
		$("#ma_content").focus();
		return false;
	}

    if (errmsg != "") {
        alert(errmsg);
        errfld.focus();
        return false;
    }

    return true;
}

function onch_Byte() {
    byte = getByteLen($("#ma_content").val());
	$("#span_byte").text(byte);

    if (byte > 90 && !is_mms) {
        $('#ma_type2').prop("checked", true);
        $('#js_MMS').fadeIn('fast');
        $('#js_max_byte').text("2000");
    }
    else {
        $('#ma_type1').prop("checked", true);
        $('#js_MMS').fadeOut('fast');   
        $('#js_max_byte').text("90");
    }
}

function getByteLen(sMsg, sMsgLng) {
	var TOG_WORD = "%0D";
	var sTmpMsg = '';						// 메시지를 임시로 저장하는 변수
	var sTmpMsgLen = 0;				// 임시로 저장된 메시지의 길이를 저장하는 변수
	var sOneChar = '';						// 한문자를 저장하는 변수
	var iCounts = new Array();		// 총 바이트와 페이지당 바이트 수를 저장하는 배열
	
	iCounts[0] = 0;							// 총 바이트를 저장 하는 변수
	
	(sMsgLng != null) ? sTmpMsg	= new String(sMsgLng) : sTmpMsg	= new String(sMsg);
	sTmpMsgLen = sTmpMsg.length;
	
	for (k = 0; k < sTmpMsgLen; k++) {
		sOneChar = sTmpMsg.charAt(k);
		
		if (escape(sOneChar) == TOG_WORD)
			iCounts[0]++;
		else if (escape(sOneChar).length > 4)
			iCounts[0] += 2;
		else
			iCounts[0]++;
	}
	return iCounts;
}

document.fmailform.ma_subject.focus();
</script>

<?php
include_once('./admin.tail.php');
?>
