<?php
## 비회원 SMS 발송
$sub_menu = "990500";
include_once('./_common.php');

if (!$config['cf_email_use'])
    alert('환경설정에서 \'메일발송 사용\'에 체크하셔야 SMS를 발송할 수 있습니다.');

auth_check($auth[$sub_menu], 'r');

$sql = " select * from nx_sms where ma_id = '$ma_id' ";
$ma = sql_fetch($sql);
if (!$ma['ma_id'])
    alert('보내실 내용을 선택하여 주십시오.');


$g5['title'] = 'SMS발송';
include_once('./admin.head.php');
?>

<form name="frmsendmailselectform" id="frmsendmailselectform" enctype="multipart/form-data" action="./sms_select_list.php" method="post" autocomplete="off">
<input type="hidden" name="ma_id" value="<?php echo $ma_id ?>">
<input type="hidden" name="ma_target" value="B">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 대상선택</caption>
    <colgroup>
        <col width="200"><col width="">
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">입력방식</th>
        <td>
            <input type="radio" id="INPUT_TYPE1" name="INPUT_TYPE" class="radio1" value="A" checked><label for="INPUT_TYPE1"><span class="radbox"><span></span></span><span class="txt">직접입력</span></label>
            <input type="radio" id="INPUT_TYPE2" name="INPUT_TYPE" class="radio1" value="B"><label for="INPUT_TYPE2"><span class="radbox"><span></span></span><span class="txt">엑셀 업로드</span></label>
        </td>
    </tr>
    <tr>
        <th scope="row">발신번호 선택</th>
        <td>
            <span class="nx-slt">
                <select name="from_tel" id="from_tel">
                   	<option value="0315476500" selected="selected">031-547-6500</option>
                    <option value="0315476501">031-547-6501</option>
                    <option value="0315472601">031-547-2601</option>
                    <option value="0315472621">031-547-2621</option>
                    <option value="0315472622">031-547-2622</option>
                    <option value="0315472623">031-547-2623</option>
                    <option value="0315472624">031-547-2624</option>
                    <option value="0315472625">031-547-2625</option>
                    <option value="0315472631">031-547-2631</option>
                    <option value="0315472632">031-547-2632</option>
                    <option value="0315472633">031-547-2633</option>
                    <option value="0315472634">031-547-2634</option>
                    <option value="0315472635">031-547-2635</option>
                    <option value="0315472636">031-547-2636</option>
                    <option value="0315472637">031-547-2637</option>
                    <option value="0315472651">031-547-2651</option>
                    <option value="0315472652">031-547-2652</option>
                    <option value="0315472653">031-547-2653</option>
                    <option value="0315472654">031-547-2654</option>
                    <option value="0315472655">031-547-2655</option>
                    <option value="0315476502">031-547-6502</option>
                    <option value="0315476503">031-547-6503</option>
                    <option value="0315476504">031-547-6504</option>
                    <option value="0315476506">031-547-6506</option>
                    <option value="0315476507">031-547-6507</option>
                    <option value="0315476510">031-547-6510</option>
                    <option value="0315476511">031-547-6511</option>
                    <option value="0315476512">031-547-6512</option>
                    <option value="0315476514">031-547-6514</option>
                    <option value="0315476515">031-547-6515</option>
                    <option value="0315476516">031-547-6516</option>
                    <option value="0315476518">031-547-6518</option>
                    <option value="0315476519">031-547-6519</option>
                    <option value="0315476521">031-547-6521</option>
                    <option value="0315476530">031-547-6530</option>
                    <option value="0315476531">031-547-6531</option>
                    <option value="0315476534">031-547-6534</option>
                    <option value="0315476535">031-547-6535</option>
                    <option value="0315476536">031-547-6536</option>
                    <option value="0315476537">031-547-6537</option>
                    <option value="0315476540">031-547-6540</option>
                    <option value="0315476541">031-547-6541</option>
                    <option value="0315476542">031-547-6542</option>
                    <option value="0315476543">031-547-6543</option>
                    <option value="0315476544">031-547-6544</option>
                    <option value="0315476545">031-547-6545</option>
                    <option value="0315476546">031-547-6546</option>
                    <option value="0315476547">031-547-6547</option>
                    <option value="0315476549">031-547-6549</option>
                    <option value="0315476554">031-547-6554</option>
                    <option value="0315476558">031-547-6558</option>
                    <option value="0315476559">031-547-6559</option>
                    <option value="0315476562">031-547-6562</option>
                    <option value="0315476570">031-547-6570</option>
                    <option value="0315476580">031-547-6580</option>
                    <option value="0315476591">031-547-6591</option>
                    <option value="0315476592">031-547-6592</option>
                    <option value="0315476593">031-547-6593</option>
                    <option value="0317701328">031-770-1328</option>
                    <option value="0317701510">031-770-1510</option>
                    <option value="0317701511">031-770-1511</option>
                    <option value="0317701512">031-770-1512</option>
                    <option value="0317701514">031-770-1514</option>
                    <option value="0317701515">031-770-1515</option>
                    <option value="0317701516">031-770-1516</option>
                    <option value="0317701519">031-770-1519</option>
                    <option value="0317701521">031-770-1521</option>
                    <option value="0317701522">031-770-1522</option>
                    <option value="0317701523">031-770-1523</option>
                    <option value="0317701524">031-770-1524</option>
                    <option value="0317701525">031-770-1525</option>
                    <option value="0317701526">031-770-1526</option>
                    <option value="0319562104">031-956-2104</option>
                    <option value="0319562106">031-956-2106</option>
                    <option value="0319562110">031-956-2110</option>
                    <option value="0319562115">031-956-2115</option>
                    <option value="0319562122">031-956-2122</option>
                    <option value="0319562124">031-956-2124</option>
                    <option value="0319562127">031-956-2127</option>
                    <option value="0319562129">031-956-2129</option>
                    <option value="0319562130">031-956-2130</option>
                    <option value="0319562131">031-956-2131</option>
                    <option value="0319562134">031-956-2134</option>
                    <option value="0319562136">031-956-2136</option>
                    <option value="0319562139">031-956-2139</option>
                    <option value="0319562154">031-956-2154</option>
                    <option value="0319562167">031-956-2167</option>
                    <option value="0319562169">031-956-2169</option>
                    <option value="0319562171">031-956-2171</option>
                    <option value="0319562177">031-956-2177</option>
                    <option value="0319562206">031-956-2206</option>
                    <option value="0319562207">031-956-2207</option>
                    <option value="0319562222">031-956-2222 스포츠센터</option>                    
                    <option value="0319562292">031-956-2292</option>
                    <option value="0319562298">031-956-2298</option>
                    <option value="0319562487">031-956-2487</option>
                    <option value="0319562492">031-956-2492</option>
                    <option value="0319562493">031-956-2493</option>
                    <option value="0319562496">031-956-2496</option>
                    <option value="0319562497">031-956-2497</option>
                    <option value="0319562610">031-956-2610</option>
                    <option value="0319562617">031-956-2617</option>
                    <option value="0319562625">031-956-2625</option>
                    <option value="0319562635">031-956-2635</option>
                    <option value="0319562652">031-956-2652</option>
                </select>
                <span class="ico_select"></span>
            </span>
        </td>
    </tr>
    <tr id="js_INPUT_TYPE_A">
        <th scope="row"><label for="ma_list">휴대폰번호 직접입력</label></th>
        <td>
            <?php echo help("휴대폰번호를 한 줄에 하나씩 입력해주세요. 010-0000-0000 형식.") ?>
            <textarea name="ma_list" id="ma_list" class="frm_input"></textarea>
        </td>
    </tr>
    <tr id="js_INPUT_TYPE_B" style="display: none;">
        <th scope="row">엑셀 업로드</th>
        <td>
            <div class="nx-box3 mb20">
                <p class="taL">
                    <span class="nx-tip">엑셀파일 작성 예시</span><br>
                    A열에 휴대폰번호를 입력하세요.<br>
                    B열에 이름을 입력하면 SMS 내용 중 {이름} 으로 입력한 부분에 치환되어 들어갑니다. (선택사항)
                </p>
                <table style="width:300px; background: #fff">
                    <colgroup>
                        <col width="60"><col width="120"><col width="120">
                    </colgroup>
                    <thead>
                        <tr>
                            <th style="padding: 5px 0;">&nbsp;</th>
                            <th style="padding: 5px 0;">A</th>
                            <th style="padding: 5px 0;">B</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>1</th>
                            <td style="text-align: center;">010-1234-5678</td>
                            <td style="text-align: center;">아무개</td>
                        </tr>
                        <tr>
                            <th>2</th>
                            <td style="text-align: center;">010-1111-2222</td>
                            <td style="text-align: center;">홍길동</td>
                        </tr>
                        <tr>
                            <th>3</th>
                            <td style="text-align: center;">010-3456-6789</td>
                            <td style="text-align: center;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <input type="file" name="FILE">
        </td>
    </tr>
    </tbody>
    </table>
</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit">
    <a href="./sms_list2.php">목록 </a>
</div>
</form>

<script>
$('input[name="INPUT_TYPE"]').on('change', function() {
    var val = $(this).val();
    $('#js_INPUT_TYPE_' + ((val == "A") ? 'B' : 'A')).hide();
    $('#js_INPUT_TYPE_' + val).show();
});
</script>

<?php
include_once('./admin.tail.php');
?>
