<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// board.head.skin.php에서 세팅한 설정값 활용

// 댓글을 위해서 글배열 삭제
unset($list); 

// 목록이면
if($is_list_page) {
	if($wr_id) { //글번호가 있으면
		include_once(G5_BBS_PATH.'/view.php');
	} else {
		echo '<div class="well text-center">등록된 문서가 없습니다.</div>';
	}
}

// 글관리가 가능한 관리자일 때 위젯함수를 이용해서 전체목록 불러옴
if($is_admin) {
?>
	<div class="well text-center" style="margin-top:30px;">
		<a href="<?php echo $admin_href; ?>" class="btn btn-black btn-sm">
			<i class="fa fa-cog"> 보드설정</i>
		</a>
		<a href="<?php echo $setup_href; ?>" class="btn btn-black btn-sm win_memo">
			<i class="fa fa-tags"></i> 분류관리
		</a>

		<button type="button" class="btn btn-color btn-sm" data-toggle="modal" data-target="#pageModal">
			<i class="fa fa-bars"></i> 문서관리
		</button>

		<?php if($is_exist) { ?>
			<a href="<?php echo $delete_href ?>" class="btn btn-black btn-sm" onclick="del(this.href); return false;">
				<i class="fa fa-times"></i> 문서삭제
			</a>
			<a href="<?php echo $update_href ?>" class="btn btn-black btn-sm">
				<i class="fa fa-plus"></i> 문서수정
			</a>
		<?php } ?>
		<a href="<?php echo $write_href; ?>" class="btn btn-color btn-sm">
			<i class="fa fa-upload"></i> 문서등록
		</a>
	</div>

	<div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body">
					<form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post" role="form" class="form">
					<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
					<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
					<input type="hidden" name="stx" value="<?php echo $stx ?>">
					<input type="hidden" name="spt" value="<?php echo $spt ?>">
					<input type="hidden" name="sca" value="<?php echo $sca ?>">
					<input type="hidden" name="sst" value="<?php echo $sst ?>">
					<input type="hidden" name="sod" value="<?php echo $sod ?>">
					<input type="hidden" name="page" value="<?php echo $page ?>">
					<input type="hidden" name="sw" value="">
					<?php
						include_once(G5_LIB_PATH.'/apms.widget.lib.php');

						$pset = array();
						$pset['bo_list'] = $bo_table;
						$pset['rows'] = 100; // 20개 추출
						$pset['sort'] = 'asc'; // 등록순
						$list = apms_board_rows($pset);
						$list_cnt = count($list);

						$formlist = array();
						$skinlist = array();
						$headlist = array();

						$formlist = get_skin_dir('write', $board_skin_path);
						$skinlist = get_skin_dir('page', G5_SKIN_PATH);
						$headlist = get_skin_dir('header', G5_SKIN_PATH);

					?>
					<div class="table-responsive">
						<table class="table">
						<tbody>
						<tr class="bg-navy">
							<th class="text-center" scope="col">
								<label for="all_chk" class="sound_only">목록 전체</label>
								<input type="checkbox" id="all_chk">
							</th>
							<th class="pwl text-center" scope="col">문서파일</th>
							<th class="pw text-center" scope="col">문서스킨</th>
							<th class="pw text-center" scope="col">문서헤더</th>
							<th class="pw text-center" scope="col">헤더컬러</th>
							<th class="pw text-center" scope="col">등록폼</th>
						</tr>
						<?php 
							for ($i=0; $i < $list_cnt; $i++) {

								$n = $list[$i]['wr_id'];

								list($page_form, $page_file, $page_skin, $page_head, $page_color, $page_wide) = explode("|", get_text($list[$i]['wr_10']));

								$wr_color = '';
								$wr_text = '<span>';
								if($list[$i]['wr_id'] == $wr_id) {
									$wr_color = ' class="bg-light"';
									$wr_text = '<span class="red" style="font-weight:bold;">';
								}
						?>
							<tr<?php echo $wr_color;?>>
								<td rowspan="2" class="text-center">
									<input type="checkbox" name="chk_wr_id[]" value="<?php echo $n; ?>" id="chk_wr_id_<?php echo $i ?>">
								</td>
								<td colspan="4">
									<input type="hidden" name="chk_id[]" value="<?php echo $n; ?>" id="chk_id_<?php echo $i ?>">
									<a href="<?php echo $list[$i]['href'];?>">
										<?php echo $wr_text;?>
										<b>[<?php echo $list[$i]['ca_name'];?>]</b> <?php echo get_text($list[$i]['wr_subject']);?>
										</span>
									</a>
								</td>
								<td align="right">
									<label>
										<input type="checkbox" id="page_wide_<?php echo $i;?>" name="page_wide[<?php echo $n;?>]" value="1"<?php echo ($page_wide) ? ' checked' : '';?>> 와이드
									</label>
								</td>
							</tr>
							<tr<?php echo $wr_color;?>>
								<td>
									<div class="input-group input-group-sm">
										<span class="input-group-addon">/page/</span>
										<input type="text" name="page_file[<?php echo $n;?>]" value="<?php echo $page_file; ?>" id="page_file_<?php echo $i;?>" class="form-control input-sm" size="50" maxlength="255">
									</div>	
								</td>
								<td>
									<select name="page_skin[<?php echo $n;?>]" id="page_skin_<?php echo $i;?>" class="form-control input-sm">
										<option value="">스킨 미사용</option>
										<?php
											for ($k=0; $k<count($skinlist); $k++) {
												echo "<option value=\"".$skinlist[$k]."\"".get_selected($page_skin, $skinlist[$k]).">".$skinlist[$k]."</option>\n";
											} 
										?>
									</select>
								</td>
								<td>
									<select name="page_head[<?php echo $n;?>]" id="page_head_<?php echo $i;?>" class="form-control input-sm">
										<option value="">헤더 미사용</option>
										<?php
											for ($k=0; $k<count($headlist); $k++) {
												echo "<option value=\"".$headlist[$k]."\"".get_selected($page_head, $headlist[$k]).">".$headlist[$k]."</option>\n";
											} 
										?>
									</select>
								</td>
								<td>
									<select name="page_color[<?php echo $n;?>]" id="page_color_<?php echo $i;?>" class="form-control input-sm">
										<?php echo apms_color_options($page_color);?>
									</select>
								</td>
								<td>
									<select name="page_form[<?php echo $n;?>]" id="page_form_<?php echo $i;?>" class="form-control input-sm">
										<?php
											for ($k=0; $k<count($formlist); $k++) {
												echo "<option value=\"".$formlist[$k]."\"".get_selected($page_form, $formlist[$k]).">".$formlist[$k]."</option>\n";
											} 
										?>
									</select>
								</div>
							</tr>
						<?php } ?>
						</tbody>
						</table>
					</div>
					<?php if($i) { ?>
						<div class="text-center">
							<button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-black btn-sm">
								<i class="fa fa-times"></i> 선택삭제
							</button>
							<button type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value" class="btn btn-black btn-sm">
								<i class="fa fa-clipboard"></i> 선택복사
							</button>
							<button type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value" class="btn btn-black btn-sm">
								<i class="fa fa-arrows"></i> 선택이동
							</button>
							<button type="submit" name="btn_submit" value="일괄저장" onclick="document.pressed=this.value" class="btn btn-color btn-sm">
								<i class="fa fa-check"></i> 일괄저장
							</button>
							<button type="button" class="btn btn-black btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> 창닫기</button>
						</div>
					<?php } else { ?>
						<div class="text-center" style="padding:50px 10px;">
							등록된 문서가 없습니다.
						</div>
					<?php } ?>
					</form>
				</div><!-- /.modal-body -->
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<script>
	function all_checked(sw) {
		var f = document.fboardlist;

		for (var i=0; i<f.length; i++) {
			if (f.elements[i].name == "chk_wr_id[]")
				f.elements[i].checked = sw;
		}
	}

	$(function(){
		$("#all_chk").click(function(){
			var clicked_checked = $(this);

			if(clicked_checked.hasClass('active')) {
				all_checked(false);
				clicked_checked.removeClass('active');
			} else {
				all_checked(true);
				clicked_checked.addClass('active');
			}
		});
	});

	function fboardlist_submit(f) {

		if(document.pressed == "일괄저장") {
			f.removeAttribute("target");
			f.action = "<?php echo $board_skin_url;?>/list.update.php";
		} else {

			var chk_count = 0;

			for (var i=0; i<f.length; i++) {
				if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
					chk_count++;
			}

			if (!chk_count) {
				alert(document.pressed + "할 문서를 하나 이상 선택하세요.");
				return false;
			}

			if(document.pressed == "선택복사") {
				select_copy("copy");
				return;
			}

			if(document.pressed == "선택이동") {
				select_copy("move");
				return;
			}

			if(document.pressed == "선택삭제") {
				if (!confirm("선택한 문서를 정말 삭제하시겠습니까?\n\n한번 삭제한 문서는 복구할 수 없습니다."))
					return false;

				f.removeAttribute("target");
				f.action = "./board_list_update.php";
			}
		}

		return true;
	}

	// 선택한 게시물 복사 및 이동
	function select_copy(sw) {
		var f = document.fboardlist;

		if (sw == "copy")
			str = "복사";
		else
			str = "이동";

		var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

		f.sw.value = sw;
		f.target = "move";
		f.action = "./move.php";
		f.submit();
	}

	$(function(){
		$("#btn_chkall").click(function(){
			var clicked_checked = $(this);

			if(clicked_checked.hasClass('active')) {
				all_checked(false);
				clicked_checked.removeClass('active');
			} else {
				all_checked(true);
				clicked_checked.addClass('active');
			}
		});
	});
	</script>
<?php } ?>