<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// input의 name을 boset[배열키] 형태로 등록

$skinlist = array();
$bo_category_list = str_replace("|", "\n", get_text($board['bo_category_list']));

?>
<div class="tbl_head01 tbl_wrap"><table>
	<caption>스킨설정</caption>
	<colgroup>
		<col class="grid_2">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th scope="col">구분</th>
		<th scope="col">설정</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td align="center">문서분류</td>
		<td>
			<?php echo help('줄바꿈(엔터)로 구분하고, # 또는 | 는 입력하지 마세요. (예: #질문 [X], |답변 [X])') ?>
			<textarea name="bo_category_list" style="width:98%; height:200px;" maxlength="255"><?php echo $bo_category_list;?></textarea>
		</td>
	</tr>
	<tr>
		<td align="center">댓글사용</td>
		<td>
			<?php echo help('댓글을 사용하고자 하는 분류를 띄어쓰기 없이 콤마(,)로 구분해서 등록해 주세요.') ?>
			<input type="text" name="boset[use_cmt]" size="55" value="<?php echo $boset['use_cmt'];?>" class="frm_input">
		</td>
	</tr>
	<tr>
		<td align="center">댓글설정</td>
		<td>
			회원사진
			<select name="boset[cmt_photo]">
				<option value=""<?php echo get_selected('', $boset['cmt_photo']); ?>>모두출력</option>
				<option value="1"<?php echo get_selected('1', $boset['cmt_photo']); ?>>PC만</option>
				<option value="2"<?php echo get_selected('2', $boset['cmt_photo']); ?>>모바일만</option>
				<option value="3"<?php echo get_selected('3', $boset['cmt_photo']); ?>>출력안함</option>
			</select>
			&nbsp;
			대댓글 이름
			<select name="boset[cmt_re]">
				<option value=""<?php echo get_selected('', $boset['cmt_re']); ?>>출력안함</option>
				<option value="1"<?php echo get_selected('1', $boset['cmt_re']); ?>>모두</option>
				<option value="2"<?php echo get_selected('2', $boset['cmt_re']); ?>>PC만</option>
				<option value="3"<?php echo get_selected('3', $boset['cmt_re']); ?>>모바일만</option>
			</select>
			&nbsp;
			<label><input type="checkbox" name="boset[cgood]" value="1"<?php echo get_checked('1', $boset['cgood']);?>> 댓글추천</label>
			&nbsp;
			<label><input type="checkbox" name="boset[cnogood]" value="1"<?php echo get_checked('1', $boset['cnogood']);?>> 비추천</label>
		</td>
	</tr>

	</tbody>
	</table>
</div>
