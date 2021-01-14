<?php
$sub_menu = "960100";
include_once('./_common.php');

if ($member['mb_level'] == 3) {
    $auth['960300'] = 'w';
}
auth_check($auth[$sub_menu], 'w', true);


$g5['title'] = '학습공간 시설관리자연결 엑셀일괄등록';
include_once(G5_ADMIN_PATH.'/admin.head.php');
?>

<form name="frmUpload" id="frmUpload" enctype="multipart/form-data" action="./place.mng.excel.upload.php" method="post" autocomplete="off">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption>엑셀 파일업로드</caption>
    <colgroup>
        <col width="200"><col width="">
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">엑셀 업로드</th>
        <td>
            <div class="nx-box3 mb20">
                <p class="taL">
                    <span class="nx-tip">엑셀파일 작성 예시</span><br>
                    학습공간 내역 엑셀파일을 다운로드한 다음 .xlsx 확장자로 변환하여 사용해주세요.<br>
                    <br>
                    학습공간에 맞게 연결할 시설관리자 아이디를 E열에 입력해주세요.<br>
                    B열의 장소 고유번호에 해당하는 학습공간에 시설관리자 ID가 연결됩니다.<br>
                    E열 시설관리자 아이디 외의 정보는 등록되지 않습니다.<br>
                    둘째 줄 (2열) 부터 데이터를 입력해주세요.
                </p>

                <table style="width:600px; background: #fff">
                    <colgroup>
                        <col width="60"><col width="80"><col width="100"><col width="100"><col width="160"><col width="100">
                    </colgroup>
                    <thead>
                        <tr>
                            <th style="padding: 8px 0;">&nbsp;</th>
                            <th style="padding: 8px 0;">A</th>
                            <th style="padding: 8px 0;">B</th>
                            <th style="padding: 8px 0;">C</th>
                            <th style="padding: 8px 0;">D</th>
                            <th style="padding: 8px 0;">E</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background:#e8eef7">
                            <th>1</th>
                            <td style="text-align: center;">No.</td>
                            <td style="text-align: center;">장소고유번호</td>
                            <td style="text-align: center;">지역명</td>
                            <td style="text-align: center;">시설명</td>
                            <td style="text-align: center;">시설관리자 ID</td>
                        </tr>
                        <tr>
                            <th style="background: #e8eef7">2</th>
                            <td style="text-align: center;">1</td>
                            <td style="text-align: center;">31</td>
                            <td style="text-align: center;">가평군</td>
                            <td style="text-align: center;">OOO 작은 도서관</td>
                            <td style="text-align: center;" class="color1">ud0001</td>
                        </tr>
                        <tr>
                            <th style="background: #e8eef7">3</th>
                            <td style="text-align: center;">2</td>
                            <td style="text-align: center;">32</td>
                            <td style="text-align: center;">가평군</td>
                            <td style="text-align: center;">OO리 마을회관</td>
                            <td style="text-align: center;" class="color1">ud0002</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <input type="file" name="FILE" id="FILE">
        </td>
    </tr>
    </tbody>
    </table>
</div>
</form>

<div class="taR mt20">
    <a href="javascript:oncl_upload();" class="nx-btn-b2">등록</a>
    <a href="./place.list.php" class="nx-btn-b3">목록</a>
</div>

<script>
function oncl_upload() {
    if ($('#FILE').get(0).files.length === 0) {
        alert('엑셀 파일을 업로드해주세요.');
        return;
    }

    if (confirm('업로드한 데이터로 일괄등록 하시겠습니까?')) {
        $('#frmUpload').submit();
    }
}
</script>


<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>