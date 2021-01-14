<?php

include_once './_common.php'; //공통 함수
include_once G5_EDITOR_PATH.'/'.$config['cf_editor'].'/editor.lib.php'; //에디터 라이브러리
$is_dhtml_editor = true; // Dhtil 에디터 사용 설정

$write['desc'] = '
  <img src="http://www.placehold.it/300x300">
  <h1>DB에서 불러온 값이 해당 <strong>배열</strong>에 들어갑니다. 해당 배열은 업데이트 및 view에도 동일하게 사용됩니다.</h1>';

?>

<script src="/js/jquery-1.11.3.min.js"></script>

<form name="" action="" action="" method="POST" onsubmit="submitContents(this)">
<table width="1000px">
<tr>
	<td>내용</td>
	<td colspan=3>
    <?php
      # function editor_html('Element ID', 'value', true);
      echo editor_html('desc', $write['desc'], $is_dhtml_editor);
    ?>

	<script>
		function submitContents(formElements) {
  		oEditors.getById["desc"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 업데이트합니다.
			formElements.form.submit();
		}
	</script>
</td>
</tr>
<!-- CKeditor와는 달리 다음과 같이 Submit 부분에 onclick 이벤트 추가 -->
<tr>
	<td colspan=4>
    <input type=submit value="저장하기">
  </td>
</tr>
</table>
</form>
