<?php
## 우리동네학습공간 시설관리자 엑셀 업로드
$mode = 'udong_adm';
$sub_menu = "970200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w, d', true);


$g5['title'] = '시설관리자 엑셀일괄등록';
include_once('../admin.head.php');
?>

<form name="frmUpload" id="frmUpload" enctype="multipart/form-data" action="./member_udongmng_upload.php" method="post" autocomplete="off">

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
                <div class="ofH mb10">
                    <div class="fL">
                        <span class="nx-tip">엑셀파일 작성 예시</span>
                    </div>
                    <div class="fR">
                        <a href="<?php echo(G5_ADMIN_URL)?>/nx/sample_udongmng_upload.xlsx" target="_blank" class="nx-btn-b2">샘플파일 다운로드</a>
                    </div>
                </div>
                <p class="taL">
                    샘플파일을 다운로드하여 2행부터 각 열에 맞게 데이터를 입력해주세요.<br>
                    A열의 번호는 업로드 작업에 영향을 미치지 않습니다.<br>
                    <br>
                    동일한 아이디, 닉네임이 <!--이메일주소, 휴대폰번호가--> 있는 데이터는 등록되지 않습니다.
                </p>

                <table style="width:700px; background: #fff">
                    <colgroup>
                        <col width="60"><col width="80"><col width="80"><col width="100"><col width="80"><col width="100"><col width="100"><col width="100">
                    </colgroup>
                    <thead>
                        <tr>
                            <th style="padding: 8px 0;">&nbsp;</th>
                            <th style="padding: 8px 0;">A</th>
                            <th style="padding: 8px 0;">B</th>
                            <th style="padding: 8px 0;">C</th>
                            <th style="padding: 8px 0;">D</th>
                            <th style="padding: 8px 0;">E</th>
                            <th style="padding: 8px 0;">F</th>
                            <th style="padding: 8px 0;">G</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background:#e8eef7">
                            <th>1</th>
                            <td style="text-align: center;">번호</td>
                            <td style="text-align: center;">ID</td>
                            <td style="text-align: center;">초기비밀번호</td>
                            <td style="text-align: center;">이름(실명)</td>
                            <td style="text-align: center;">닉네임</td>
                            <td style="text-align: center;">E-mail</td>
                            <td style="text-align: center;">휴대폰번호</td>
                        </tr>
                        <tr>
                            <th style="background: #e8eef7">2</th>
                            <td style="text-align: center;">1</td>
                            <td style="text-align: center;">ud0001</td>
                            <td style="text-align: center;">1234</td>
                            <td style="text-align: center;">홍길동</td>
                            <td style="text-align: center;">광명학습공간</td>
                            <td style="text-align: center;">ud0001@email.com</td>
                            <td style="text-align: center;">010-0000-0000</td>
                        </tr>
                        <tr>
                            <th style="background: #e8eef7">3</th>
                            <td style="text-align: center;">2</td>
                            <td style="text-align: center;">ud0002</td>
                            <td style="text-align: center;">1234</td>
                            <td style="text-align: center;">아무개</td>
                            <td style="text-align: center;">안산학습공간</td>
                            <td style="text-align: center;">ud0002@email.com</td>
                            <td style="text-align: center;">010-1234-5678</td>
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
    <a href="./member_list.php?mode=<?php echo($mode)?>" class="nx-btn-b3">목록</a>
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
include_once('../admin.tail.php');
?>
