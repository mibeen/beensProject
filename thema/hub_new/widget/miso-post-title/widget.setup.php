<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(THEMA_PATH.'/assets/func.php');

// input의 name을 wset[배열키] 형태로 등록
// 모바일 설정값은 동일 배열키에 배열변수만 wmset으로 지정 → wmset[배열키]

// 노출수
if($wset['heights']) {
	list($wset['height'], $wset['hl'], $wset['hm'],$wset['hs'],$wset['hx']) = explode(",", $wset['heights']);
}
if(!$wset['height']) $wset['height'] = 640;
if($wset['opa'] == "") $wset['opa'] = 40;
if($wset['raster'] == "") $wset['raster'] = 1;
if($wset['vol'] == "") $wset['vol'] = 30;
if(!$wset['skin']) $wset['skin'] = 'basic';

$wlist = array();
$wdata = array();
$wlist = thema_switcher('file', $widget_path.'/skin', $wset['skin'], "php");
$wdata = thema_switcher('file', $widget_path.'/data', $wset['data'], "php");

?>

<style>
	.slt1 { margin-bottom: 5px; display: none; }

	.frm_input,
	.frm_input2,
	.frm_input3 { padding: 0 5px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }

	.frm_input2,
	.frm_input3 { height: 22px; border: 1px solid #e4eaec; background: #f7f7f7; color: #000; vertical-align: middle; line-height: 2em; }

	.frm_input2 { width: calc(100% - 300px); }
	.frm_input3 { width: 65px; }

	.sl-image-wrap { padding: 5px; padding-bottom: 5px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
	.sl-image-wrap .item { position: relative; padding: 10px; padding-top: 20px; margin-bottom: 5px; border: 1px solid #DDD; border-radius: 5px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
	
	.sl-image-wrap .item .input-wrap { padding-bottom: 5px; }
	.sl-image-wrap .item .frm_input { width: calc(100% - 120px); }
	.sl-image-wrap .item .btn-close { padding: 3px 5px; position: absolute; top: 0; right: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; background: #F00; color: #FFF; font-size: 14px; overflow: hidden; border-radius: 0 0 0 5px; }
	.sl-image-wrap .item .txt { display: inline-block; vertical-align: middle; width: 45px; text-align: center; }
	
	.btn-wrap { padding: 0; text-align: center; font-size: 20px; }
	
	.btn_ { background: transparent; border: 0; outline: 0; line-height: 1.2; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
</style>

<div class="tbl_head01 tbl_wrap">
	<table>
	<caption>위젯설정</caption>
	<colgroup>
		<col class="grid_2" style="width: 60px;">
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
		<td align="center">스킨설정</td>
		<td>
			<?php echo help('위젯 내 /skin 폴더 안의 개별 php 파일');?>
			<select name="wset[skin]">
			<?php for($i=0; $i < count($wlist); $i++) { ?>
				<option value="<?php echo $wlist[$i]['value'];?>"<?php echo ($wlist[$i]['selected']) ? ' selected' : '';?>>
					<?php echo $wlist[$i]['value'];?>
				</option>
			<?php } ?>
			</select>
			&nbsp;
			<label><input type="checkbox" name="wset[text]" value="1"<?php echo get_checked('1', $wset['text']); ?>> 내용 출력안함</label>
			&nbsp;
			<label><input type="checkbox" name="wset[noslide]" value="1"<?php echo get_checked('1', $wset['noslide']); ?>> 슬라이드(추출) 없음</label>
		</td>
	</tr>
	<tr>
		<td align="center">스타일</td>
		<td>
			<?php echo help('모바일에서 동영상 출력은 지원하지 않습니다.');?>
			<select name="wset[type]">
				<option value=""<?php echo get_selected('', $wset['type']);?>>일반배경</option>
				<?php /* <option value="parallax"<?php echo get_selected('parallax', $wset['type']);?>>패럴렉스</option> */ ?>
				<option value="slider"<?php echo get_selected('slider', $wset['type']);?>>슬라이더</option>
				<option value="video"<?php echo get_selected('video', $wset['type']);?>>동영상</option>
			</select>
			- PC
			&nbsp;
			<select name="wmset[type]">
				<option value=""<?php echo get_selected('', $wmset['type']);?>>PC설정 유지</option>
				<?php /* <option value="parallax"<?php echo get_selected('parallax', $wmset['type']);?>>패럴렉스</option> */ ?>
				<option value="slider"<?php echo get_selected('slider', $wmset['type']);?>>슬라이더</option>
			</select>
			- 모바일
			&nbsp;
			<select name="wset[modal]">
				<option value=""<?php echo get_selected('', $wset['modal']);?>>글내용 - 현재창</option>
				<option value="1"<?php echo get_selected('1', $wset['modal']);?>>글내용 - 모달창</option>
				<option value="2"<?php echo get_selected('2', $wset['modal']);?>>링크#1 - 현재창</option>
				<option value="3"<?php echo get_selected('3', $wset['modal']);?>>링크#1 - 새창</option>
			</select>
			링크
		</td>
	</tr>
	<!-- <tr>
		<td align="center">이미지</td>
		<td>
			<input type="text" name="wset[img]" value="<?php echo $wset['img'];?>" id="wimg" size="42" class="frm_input">
			<a href="<?php echo G5_BBS_URL;?>/widget.image.php?fid=wimg&amp;type=title" class="btn_frmline win_scrap">대표 이미지 선택</a>
		</td>
	</tr> -->
	<!-- slider pc -->
	<tr>
		<td align="center">슬라이드 <br> 이미지 <br> (pc)</td>
		<td class="item-wrap">

			<!-- 각각의 아이템이 반복된다 Start -->
			<div class="sl-image-wrap" id="sl-image-wrap1">
				<?php 
				$str_uniq = $wset['uniq'];
				$arr_uniq = explode(',', $str_uniq);

				for ($i=0; $i<count($arr_uniq); $i++) {
					$uniq = trim($arr_uniq[$i]);
					if ($uniq == '') continue;
					?>
					<div class="item" id="<?php echo $uniq; ?>">
						<div class="slt1">
							<select name="wset[<?php echo $uniq; ?>_seq]" id="wset[<?php echo $uniq; ?>_seq]">
								<option value="1">1</option>
							</select>
						</div>
						<div class="input-wrap">
							<label for="<?php echo $uniq; ?>_img" class="txt">이미지</label> <input type="text" name="wset[<?php echo $uniq; ?>_img]" value="<?php echo $wset[$uniq . '_img'];?>" id="<?php echo $uniq ?>_wimg" size="42" class="frm_input">
							<a href="<?php echo G5_BBS_URL;?>/widget.image.php?fid=<?php echo $uniq ?>_wimg&amp;type=title" class="btn_frmline win_scrap">이미지 선택</a>
						</div>
						<div class="input-wrap">
							<label for="<?php echo $uniq; ?>_url" class="txt">URL</label> <input type="text" name="wset[<?php echo $uniq; ?>_url]" value="<?php echo $wset[$uniq . '_url']; ?>" id="" size="42" class="frm_input" placeholder="LINK URL">
							<input type="checkbox" name="wset[<?php echo $uniq; ?>_target]" id="wset[target]" value="1"> <label for="wset[target]">새창</label>
						</div>
						<div class="input-wrap">
							<label for="<?php echo $uniq; ?>_txt1" class="txt">텍스트 1</label> <input type="text" name="wset[<?php echo $uniq; ?>_txt1]" value="<?php echo $wset[$uniq . '_txt1']; ?>" id="" size="42" class="frm_input2" >
							<label for="<?php echo $uniq; ?>_fsize1" class="txt">사이즈</label> <input type="text" name="wset[<?php echo $uniq; ?>_fsize1]" value="<?php echo $wset[$uniq . '_fsize1']; ?>" id="" size="42" class="frm_input3" >
							<label for="<?php echo $uniq; ?>_fcolor1" class="txt">컬러</label> <input type="text" name="wset[<?php echo $uniq; ?>_fcolor1]" value="<?php echo $wset[$uniq . '_fcolor1']; ?>" id="" size="42" class="frm_input3" >
						</div>
						<div class="input-wrap">
							<label for="<?php echo $uniq; ?>_txt2" class="txt">텍스트 2</label> <input type="text" name="wset[<?php echo $uniq; ?>_txt2]" value="<?php echo $wset[$uniq . '_txt2']; ?>" id="" size="42" class="frm_input2" >
							<label for="<?php echo $uniq; ?>_fsize2" class="txt">사이즈</label> <input type="text" name="wset[<?php echo $uniq; ?>_fsize2]" value="<?php echo $wset[$uniq . '_fsize2']; ?>" id="" size="42" class="frm_input3" >
							<label for="<?php echo $uniq; ?>_fcolor2" class="txt">컬러</label> <input type="text" name="wset[<?php echo $uniq; ?>_fcolor2]" value="<?php echo $wset[$uniq . '_fcolor2']; ?>" id="" size="42" class="frm_input3" >
						</div>
						<div class="input-wrap">
							<label for="<?php echo $uniq; ?>_barcolor" class="txt">바 컬러</label> <input type="text" name="wset[<?php echo $uniq; ?>_barcolor]" value="<?php echo $wset[$uniq . '_barcolor']; ?>" id="" size="42" class="frm_input3" >
						</div>
						<button type="button" class="btn_ btn-close" onclick="visualSet.remove('<?php echo $uniq; ?>');"><i class="fa fa-times" aria-hidden="true"></i></button>
					</div>
					<?php
				}
				?>
			</div>

			<input type="hidden" name="wset[uniq]" id="wset[uniq]" value="<?php echo $wset['uniq']; ?>">
			<div class="btn-wrap">
				<button type="button" class="btn_ btn-plus" onclick="visualSet.add();"><i class="fa fa-plus-circle"></i></button>
			</div>
			<!-- 각각의 아이템이 반복된다 End -->

		</td>
	</tr>
	<!-- slider pc end -->
	<!-- slider mobile -->
	<tr>
		<td align="center">슬라이드 <br> 이미지 <br> (mobile)</td>
		<td class="item-wrap">

			<!-- 각각의 아이템이 반복된다 Start -->
			<div class="sl-image-wrap" id="sl-image-wrap2">
				<?php 
				$str_uniq_m = $wmset['uniq'];
				$arr_uniq_m = explode(',', $str_uniq_m);

				for ($i=0; $i<count($arr_uniq_m); $i++) {
					$uniq_m = trim($arr_uniq_m[$i]);
					if ($uniq_m == '') continue;
					?>
					<div class="item" id="<?php echo $uniq_m; ?>">
						<div class="slt1">
							<select name="wmset[<?php echo $uniq_m; ?>_seq]" id="wmset[<?php echo $uniq_m; ?>_seq]">
								<option value="1">1</option>
							</select>
						</div>
						<div class="input-wrap">
							<label for="<?php echo $uniq_m; ?>_img" class="txt">이미지</label> <input type="text" name="wmset[<?php echo $uniq_m; ?>_img]" value="<?php echo $wmset[$uniq_m . '_img'];?>" id="<?php echo $uniq_m; ?>_wimg" size="42" class="frm_input">
							<a href="<?php echo G5_BBS_URL;?>/widget.image.php?fid=<?php echo $uniq_m; ?>_wimg&amp;type=title" class="btn_frmline win_scrap">이미지 선택</a>
						</div>
						<div class="input-wrap">
							<label for="<?php echo $uniq_m; ?>_url" class="txt">URL</label> <input type="text" name="wmset[<?php echo $uniq_m; ?>_url]" value="<?php echo $wmset[$uniq_m . '_url']; ?>" id="" size="42" class="frm_input" placeholder="LINK URL">
							<input type="checkbox" name="wmset[<?php echo $uniq_m; ?>_target]" id="wmset[target]" value="1"> <label for="wmset[target]">새창</label>
						</div>
						<div class="input-wrap">
							<label for="<?php echo $uniq_m; ?>_txt1" class="txt">텍스트 1</label> <input type="text" name="wmset[<?php echo $uniq_m; ?>_txt1]" value="<?php echo $wmset[$uniq_m . '_txt1']; ?>" id="" size="42" class="frm_input2" >
							<label for="<?php echo $uniq_m; ?>_fsize1" class="txt">사이즈</label> <input type="text" name="wmset[<?php echo $uniq_m; ?>_fsize1]" value="<?php echo $wmset[$uniq_m . '_fsize1']; ?>" id="" size="42" class="frm_input3" >
							<label for="<?php echo $uniq_m; ?>_fcolor1" class="txt">컬러</label> <input type="text" name="wmset[<?php echo $uniq_m; ?>_fcolor1]" value="<?php echo $wmset[$uniq_m . '_fcolor1']; ?>" id="" size="42" class="frm_input3" >
						</div>
						<div class="input-wrap">
							<label for="<?php echo $uniq_m; ?>_txt2" class="txt">텍스트 2</label> <input type="text" name="wmset[<?php echo $uniq_m; ?>_txt2]" value="<?php echo $wmset[$uniq_m . '_txt2']; ?>" id="" size="42" class="frm_input2" >
							<label for="<?php echo $uniq_m; ?>_fsize2" class="txt">사이즈</label> <input type="text" name="wmset[<?php echo $uniq_m; ?>_fsize2]" value="<?php echo $wmset[$uniq_m . '_fsize2']; ?>" id="" size="42" class="frm_input3" >
							<label for="<?php echo $uniq_m; ?>_fcolor2" class="txt">컬러</label> <input type="text" name="wmset[<?php echo $uniq_m; ?>_fcolor2]" value="<?php echo $wmset[$uniq_m . '_fcolor2']; ?>" id="" size="42" class="frm_input3" >
						</div>
						<div class="input-wrap">
							<label for="<?php echo $uniq_m; ?>_barcolor" class="txt">바 컬러</label> <input type="text" name="wmset[<?php echo $uniq_m; ?>_barcolor]" value="<?php echo $wmset[$uniq_m . '_barcolor']; ?>" id="" size="42" class="frm_input3" >
						</div>
						<button type="button" class="btn_ btn-close" onclick="visualSet_m.remove('<?php echo $uniq_m; ?>');"><i class="fa fa-times" aria-hidden="true"></i></button>
					</div>
					<?php
				}
				?>
			</div>

			<input type="hidden" name="wmset[uniq]" id="wmset[uniq]" value="<?php echo $wmset['uniq']; ?>">
			<div class="btn-wrap">
				<button type="button" class="btn_ btn-plus" onclick="visualSet_m.add();"><i class="fa fa-plus-circle"></i></button>
			</div>
			<!-- 각각의 아이템이 반복된다 End -->

		</td>
	</tr>
	<!-- slider mobile end -->
	<!-- Youtube pc -->
	<tr>
		<td align="center" rowspan="2">유튜브 <br> (pc)</td>
		<td>
			<?php echo help('적용체크시 출력되며, 볼륨은 0부터 100까지 설정가능');?>
			<label><input type="checkbox" name="wset[vauto]" value="1"<?php echo get_checked('1', $wset['vauto']);?>> 자동플레이</label>
			&nbsp;
			<label><input type="checkbox" name="wset[vloop]" value="1"<?php echo get_checked('1', $wset['vloop']);?>> 첫영상 반복</label>
			&nbsp;
			<label><input type="checkbox" name="wset[vrdm]" value="1"<?php echo get_checked('1', $wset['vrdm']);?>> 랜덤순서</label>
			&nbsp;
			<input type="text" name="wset[vol]" value="<?php echo $wset['vol'];?>" size="3" class="frm_input"> % 볼륨
		</td>
	</tr>
	<tr>
		<td>
			<?php echo help('유튜브 동영상 아이디를 줄바꿈(엔터)으로 구분해서 등록');?>
			<?php echo help('아이디,시작시간,종료시간 형태 등록가능. ex) kcihcYEOeic,18,362');?>
			<textarea name="wset[vlist]" style="width:98%; height:60px; margin-bottom:8px;"><?php echo $wset['vlist'];?></textarea>
			<br>
		</td>
	</tr>
	<!-- Youtube pc end -->
	<tr>
		<td align="center" rowspan="3">커버출력</td>
		<td>
			<label><input type="checkbox" name="wset[cover]" value="1"<?php echo get_checked('1', $wset['cover']); ?>> 로고/검색창 슬라이더 출력</label>
		</td>
	</tr>
	<tr>
		<td>
			<input type="text" name="wset[logo]" value="<?php echo $wset['logo'];?>" id="logo_img" size="42" class="frm_input" placeholder="로고 이미지"> 
			<a href="<?php echo G5_BBS_URL;?>/widget.image.php?fid=logo_img&amp;type=image" class="btn_frmline win_scrap">사이트 로고 선택</a>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo help('추천검색어는 단어를 기준으로 콤마(,)로 구분하여 등록');?>
			<input type="text" name="wset[stx]" value="<?php echo $wset['stx'];?>" size="54" class="frm_input" style="width:98%;" placeholder="추천검색어 입력"> 
		</td>
	</tr>
	<tr>
		<td align="center" rowspan="2">슬라이더</td>
		<td>
			<select name="wset[mode]">
				<option value=""<?php echo get_selected('', $wset['mode']);?>>슬라이드</option>
				<option value="fade"<?php echo get_selected('fade', $wset['mode']);?>>페이드</option>
				<option value="vertical"<?php echo get_selected('vertical', $wset['mode']);?>>버티컬</option>
			</select>
			효과
			&nbsp;
			<label><input type="checkbox" name="wset[auto]" value="1"<?php echo get_checked('1', $wset['auto']); ?>> 자동실행안함</label>
			&nbsp;
			<label><input type="checkbox" name="wset[rdm]" value="1"<?php echo get_checked('1', $wset['rdm']); ?>> 랜덤섞기</label>
			&nbsp;
			<label><input type="checkbox" name="wset[hover]" value="1"<?php echo get_checked('1', $wset['hover']); ?>> 호버작동</label>
			&nbsp;
			<label><input type="checkbox" name="wset[nav]" value="1"<?php echo get_checked('1', $wset['nav']); ?>> 네비 숨김</label>
		</td>
	</tr>
	<tr>
		<td>
			불투명
			<select name="wset[opa]">
				<option value="0"<?php echo get_selected('0', $wset['opa']); ?>>없음</option>
				<option value="10"<?php echo get_selected('10', $wset['opa']); ?>>10%</option>
				<option value="20"<?php echo get_selected('20', $wset['opa']); ?>>20%</option>
				<option value="30"<?php echo get_selected('30', $wset['opa']); ?>>30%</option>
				<option value="40"<?php echo get_selected('40', $wset['opa']); ?>>40%</option>
				<option value="50"<?php echo get_selected('50', $wset['opa']); ?>>50%</option>
				<option value="60"<?php echo get_selected('60', $wset['opa']); ?>>60%</option>
				<option value="70"<?php echo get_selected('70', $wset['opa']); ?>>70%</option>
				<option value="80"<?php echo get_selected('80', $wset['opa']); ?>>80%</option>
			</select>
			&nbsp;
			래스터(주사선)
			<select name="wset[raster]">
				<option value="0"<?php echo get_selected('0', $wset['raster']); ?>>없음</option>
				<option value="1"<?php echo get_selected('1', $wset['raster']); ?>>1번</option>
				<option value="2"<?php echo get_selected('2', $wset['raster']); ?>>2번</option>
				<option value="3"<?php echo get_selected('3', $wset['raster']); ?>>3번</option>
				<option value="4"<?php echo get_selected('4', $wset['raster']); ?>>4번</option>
				<option value="5"<?php echo get_selected('5', $wset['raster']); ?>>5번</option>
			</select>
			&nbsp;
			페이저
			<select name="wset[dot]">
				<option value=""<?php echo get_selected('', $wset['dot']); ?>>출력안함</option>
				<?php echo apms_color_options($wset['dot']);?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="center">높이설정</td>
		<td>
			<table>
			<thead>
			<tr>
				<th scope="col">구분</th>
				<th scope="col">기본</th>
				<th scope="col">lg(1199px~)</th>
				<th scope="col">md(991px~)</th>
				<th scope="col">sm(767px~)</th>
				<th scope="col">xs(480px~)</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td align="center">높이(px)</td>
				<td align="center">
					<input type="text" name="wset[height]" value="<?php echo $wset['height']; ?>" class="frm_input" size="6">
				</td>
				<td align="center">
					<input type="text" name="wset[hl]" value="<?php echo $wset['hl']; ?>" class="frm_input" size="6">
				</td>
				<td align="center">
					<input type="text" name="wset[hm]" value="<?php echo $wset['hm']; ?>" class="frm_input" size="6">
				</td>
				<td align="center">
					<input type="text" name="wset[hs]" value="<?php echo $wset['hs']; ?>" class="frm_input" size="6">
				</td>
				<td align="center">
					<input type="text" name="wset[hx]" value="<?php echo $wset['hx']; ?>" class="frm_input" size="6">
				</td>
			</tr>
			</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center">추출자료</td>
		<td>
			<?php echo help('데이타 자료는 위젯 내 /data 폴더의 각 배열자료');?>
			<select name="wset[data]">
				<option value=""<?php echo get_selected('', $wset['data']); ?>>게시물 추출</option>
				<?php for($i=0; $i < count($wdata); $i++) { ?>
					<option value="<?php echo $wdata[$i]['value'];?>"<?php echo ($wdata[$i]['selected']) ? ' selected' : '';?>>
						데이타 : <?php echo $wdata[$i]['value'];?>
					</option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="center">추출글수</td>
		<td>
			<?php echo help('데이타 자료는 생성한 배열에 따라 자동적용됨');?>
			<input type="text" name="wset[rows]" value="<?php echo $wset['rows']; ?>" class="frm_input" size="3"> 개 - PC
			&nbsp;
			<input type="text" name="wmset[rows]" value="<?php echo $wmset['rows']; ?>" class="frm_input" size="3"> 개 - 모바일
			&nbsp;
			<input type="text" name="wset[page]" value="<?php echo $wset['page'];?>" size="3" class="frm_input"> 페이지
			&nbsp;
			<label><input type="checkbox" name="wset[main]" value="1"<?php echo get_checked('1', $wset['main']); ?>> 메인글</label>
		</td>
	</tr>
	<tr>
		<td align="center">추출옵션</td>
		<td>
			<label><input type="checkbox" name="wset[main]" value="1"<?php echo get_checked('1', $wset['main']); ?>> 메인글</label>
			&nbsp;
			<label><input type="checkbox" name="wset[image]" value="1"<?php echo get_checked('1', $wset['image']); ?>> 이미지글</label>
			&nbsp;
			<select name="wset[vid]">
				<option value=""<?php echo get_selected('', $wset['vid']);?>>동영상글</option>
				<option value="1"<?php echo get_selected('1', $wset['vid']);?>>전체동영상</option>
				<option value="youtube"<?php echo get_selected('youtube', $wset['vid']);?>>유튜브</option>
				<option value="vimeo"<?php echo get_selected('vimeo', $wset['vid']);?>>비메오</option>
				<option value="tvcast"<?php echo get_selected('tvcast', $wset['vid']);?>>네이버TV</option>
				<option value="kakao"<?php echo get_selected('kakao', $wset['vid']);?>>카카오TV</option>
			</select>
		</td>
	</tr>
	<tr>
		<td align="center">추출보드</td>
		<td>
			<?php echo help('보드아이디를 콤마(,)로 구분해서 복수 등록 가능, 미입력시 전체 게시판 적용');?>
			<input type="text" name="wset[bo_list]" value="<?php echo $wset['bo_list']; ?>" size="60" class="frm_input">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td align="center">추출그룹</td>
		<td>
			<?php echo help('그룹아이디를 콤마(,)로 구분해서 복수 등록 가능, 설정시 추출보드는 적용안됨');?>
			<input type="text" name="wset[gr_list]" value="<?php echo $wset['gr_list']; ?>" size="60" class="frm_input">
		</td>
	</tr>
	<tr>
		<td align="center">추출분류</td>
		<td>
			<?php echo help('분류를 콤마(,)로 구분해서 복수 등록 가능, 단일보드 추출시에만 적용됨');?>
			<input type="text" name="wset[ca_list]" value="<?php echo $wset['ca_list']; ?>" size="60" class="frm_input">
		</td>
	</tr>
	<tr>
		<td align="center">제외설정</td>
		<td>
			<label><input type="checkbox" name="wset[except]" value="1"<?php echo get_checked('1', $wset['except']);?>> 지정한 보드/그룹 제외</label>
			&nbsp;
			<label><input type="checkbox" name="wset[ex_ca]" value="1"<?php echo get_checked('1', $wset['ex_ca']);?>> 지정한 분류제외</label>
		</td>
	</tr>
	<tr>
		<td align="center">정렬설정</td>
		<td>
			<select name="wset[sort]">
				<?php echo apms_rank_options($wset['sort']);?>
			</select>
			&nbsp;
			랭크표시
			<select name="wset[rank]">
				<option value=""<?php echo get_selected('', $wset['rank']); ?>>표시안함</option>
				<?php echo apms_color_options($wset['rank']);?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="center">기간설정</td>
		<td>
			<select name="wset[term]">
				<?php echo apms_term_options($wset['term']);?>
			</select>
			&nbsp;
			<input type="text" name="wset[dayterm]" value="<?php echo $wset['dayterm'];?>" size="3" class="frm_input"> 일전까지 자료(일자지정 설정시 적용)
		</td>
	</tr>
	<tr>
		<td align="center">회원지정</td>
		<td>
			<?php echo help('회원아이디를 콤마(,)로 구분해서 복수 등록 가능');?>
			<input type="text" name="wset[mb_list]" value="<?php echo $wset['mb_list']; ?>" size="46" class="frm_input">
			&nbsp;
			<label><input type="checkbox" name="wset[ex_mb]" value="1"<?php echo get_checked('1', $wset['ex_mb']);?>> 제외하기</label>
		</td>
	</tr>
	<tr>
		<td align="center">캐시사용</td>
		<td>
			<input type="text" name="wset[cache]" value="<?php echo $wset['cache']; ?>" class="frm_input" size="4"> 초 간격으로 캐싱
		</td>
	</tr>
	</tbody>
	</table>
</div>

<!-- slider add pc -->
<script type="text/template" id="item_template_01">
	<div class="item" id="[#uniq#]">
		<div class="slt1">
			<select name="wset[[#uniq#]_seq]" id="wset[[#uniq#]_seq]">
				<option value="1">1</option>
			</select>
		</div>
		<div class="input-wrap">
			<label for="[#uniq#]_img" class="txt">이미지</label> <input type="text" name="wset[[#uniq#]_img]" value="" id="<?php echo $uniq ?>_wimg" size="42" class="frm_input">
			<a href="<?php echo G5_BBS_URL;?>/widget.image.php?fid=<?php echo $uniq ?>_wimg&amp;type=title" class="btn_frmline win_scrap">이미지 선택</a>
		</div>
		<div class="input-wrap">
			<label for="[#uniq#]_url" class="txt">URL</label> <input type="text" name="wset[[#uniq#]_url]" value="" id="" size="42" class="frm_input" placeholder="LINK URL">
			<input type="checkbox" name="wset[[#uniq#]_target]" id="wset[target]" value="1"> <label for="wset[target]">새창</label>
		</div>
		<div class="input-wrap">
			<label for="[#uniq#]_txt1" class="txt">텍스트 1</label> <input type="text" name="wset[[#uniq#]_txt1]" value="" id="" size="42" class="frm_input2" >
			<label for="[#uniq#]_fsize1" class="txt">사이즈</label> <input type="text" name="wset[[#uniq#]_fsize1]" value="" id="" size="42" class="frm_input3" >
			<label for="[#uniq#]_fcolor1" class="txt">컬러</label> <input type="text" name="wset[[#uniq#]_fcolor1]" value="" id="" size="42" class="frm_input3" >
		</div>
		<div class="input-wrap">
			<label for="[#uniq#]_txt2" class="txt">텍스트 2</label> <input type="text" name="wset[[#uniq#]_txt2]" value="" id="" size="42" class="frm_input2" >
			<label for="[#uniq#]_fsize2" class="txt">사이즈</label> <input type="text" name="wset[[#uniq#]_fsize2]" value="" id="" size="42" class="frm_input3" >
			<label for="[#uniq#]_fcolor2" class="txt">컬러</label> <input type="text" name="wset[[#uniq#]_fcolor2]" value="" id="" size="42" class="frm_input3" >
		</div>
		<div class="input-wrap">
			<label for="[#uniq#]_barcolor" class="txt">바 컬러</label> <input type="text" name="wset[[#uniq#]_barcolor]" value="" id="" size="42" class="frm_input3" >
		</div>
		<button type="button" class="btn_ btn-close" onclick="visualSet.remove('[#uniq#]');"><i class="fa fa-times" aria-hidden="true"></i></button>
	</div>
</script>
<!-- slider add pc end -->
<!-- slider add mobile -->
<script type="text/template" id="item_template_02">
	<div class="item" id="[#uniq#]">
		<div class="slt1">
			<select name="wmset[[#uniq#]_seq]" id="wmset[[#uniq#]_seq]">
				<option value="1">1</option>
			</select>
		</div>
		<div class="input-wrap">
			<label for="[#uniq#]_img" class="txt">이미지</label> <input type="text" name="wmset[[#uniq#]_img]" value="" id="[#uniq#]_wimg" size="42" class="frm_input">
			<a href="<?php echo G5_BBS_URL;?>/widget.image.php?fid=[#uniq#]_wimg&amp;type=title" class="btn_frmline win_scrap">이미지 선택</a>
		</div>
		<div class="input-wrap">
			<label for="[#uniq#]_url" class="txt">URL</label> <input type="text" name="wmset[[#uniq#]_url]" value="" id="" size="42" class="frm_input" placeholder="LINK URL">
			<input type="checkbox" name="wmset[[#uniq#]_target]" id="wmset[target]" value="1"> <label for="wmset[target]">새창</label>
		</div>
		<div class="input-wrap">
			<label for="[#uniq#]_txt1" class="txt">텍스트 1</label> <input type="text" name="wmset[[#uniq#]_txt1]" value="" id="" size="42" class="frm_input2" >
			<label for="[#uniq#]_fsize1" class="txt">사이즈</label> <input type="text" name="wmset[[#uniq#]_fsize1]" value="" id="" size="42" class="frm_input3" >
			<label for="[#uniq#]_fcolor1" class="txt">컬러</label> <input type="text" name="wmset[[#uniq#]_fcolor1]" value="" id="" size="42" class="frm_input3" >
		</div>
		<div class="input-wrap">
			<label for="[#uniq#]_txt2" class="txt">텍스트 2</label> <input type="text" name="wmset[[#uniq#]_txt2]" value="" id="" size="42" class="frm_input2" >
			<label for="[#uniq#]_fsize2" class="txt">사이즈</label> <input type="text" name="wmset[[#uniq#]_fsize2]" value="" id="" size="42" class="frm_input3" >
			<label for="[#uniq#]_fcolor2" class="txt">컬러</label> <input type="text" name="wmset[[#uniq#]_fcolor2]" value="" id="" size="42" class="frm_input3" >
		</div>
		<div class="input-wrap">
			<label for="[#uniq#]_barcolor" class="txt">바 컬러</label> <input type="text" name="wmset[[#uniq#]_barcolor]" value="" id="" size="42" class="frm_input3" >
		</div>
		<button type="button" class="btn_ btn-close" onclick="visualSet_m.remove('[#uniq#]');"><i class="fa fa-times" aria-hidden="true"></i></button>
	</div>
</script>
<!-- slider add mobile end -->
<script>
	var visualSet = {
		init : function () {

			var length = $('#sl-image-wrap1 .item').length;
			if (length < 1) window.visualSet.add();

		},

		add : function () {
			var uniqid = window.uniqid();
			var template = document.getElementById('item_template_01').innerHTML;
			template = template.replaceAll("[#uniq#]", uniqid);
			
			document.getElementById('wset[uniq]').value = document.getElementById('wset[uniq]').value.replaceAll(',,', ',') + ',' + uniqid;
			$('#sl-image-wrap1').append(template);

			$(".win_scrap").click(function() {
		        win_scrap(this.href);
		        return false;
		    });
		},

		remove : function (str) {
			if (typeof str === 'undefined') return false;

			$('#' + str).detach();

			var str_uniq = document.getElementById('wset[uniq]').value;
			var arr_uniq = str_uniq.replace(str, '');
				arr_uniq = arr_uniq.replaceAll(',,', ',');

			document.getElementById('wset[uniq]').value = arr_uniq;
		}
	}
	var visualSet_m = {
		init : function () {

			var length = $('#sl-image-wrap2 .item').length;
			if (length < 1) window.visualSet_m.add();

		},

		add : function () {
			var uniqid = window.uniqid();
			var template = document.getElementById('item_template_02').innerHTML;
			template = template.replaceAll("[#uniq#]", uniqid);
			
			document.getElementById('wmset[uniq]').value = document.getElementById('wmset[uniq]').value.replaceAll(',,', ',') + ',' + uniqid;
			$('#sl-image-wrap2').append(template);

			$(".win_scrap").click(function() {
		        win_scrap(this.href);
		        return false;
		    });
		},

		remove : function (str) {
			if (typeof str === 'undefined') return false;

			$('#' + str).detach();

			var str_uniq_m = document.getElementById('wmset[uniq]').value;
			var arr_uniq_m = str_uniq_m.replace(str, '');
				arr_uniq_m = arr_uniq_m.replaceAll(',,', ',');

			document.getElementById('wmset[uniq]').value = arr_uniq_m;
		}
	}

	String.prototype.replaceAll = function(org, dest) {
	    return this.split(org).join(dest);
	}

	function uniqid (prefix, more_entropy) {
		// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +    revised by: Kankrelune (http://www.webfaktory.info/)
		// %        note 1: Uses an internal counter (in php_js global) to avoid collision
		// *     example 1: uniqid();
		// *     returns 1: 'a30285b160c14'
		// *     example 2: uniqid('foo');
		// *     returns 2: 'fooa30285b1cd361'
		// *     example 3: uniqid('bar', true);
		// *     returns 3: 'bara20285b23dfd1.31879087'
		if (typeof prefix === 'undefined') {
			prefix = "";
		}

		var retId;
		var formatSeed = function (seed, reqWidth) {
			seed = parseInt(seed, 10).toString(16); // to hex str
			if (reqWidth < seed.length) { // so long we split
				  return seed.slice(seed.length - reqWidth);
			}
			if (reqWidth > seed.length) { // so short we pad
				  return Array(1 + (reqWidth - seed.length)).join('0') + seed;
			}
			return seed;
		};

		// BEGIN REDUNDANT
		if (!this.php_js) {
			this.php_js = {};
		}
		// END REDUNDANT
		if (!this.php_js.uniqidSeed) { // init seed with big random int
			this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
		}
		this.php_js.uniqidSeed++;

		retId = prefix; // start with prefix, add current milliseconds hex string
		retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
		retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
		if (more_entropy) {
			// for more entropy we add a float lower to 10
			retId += (Math.random() * 10).toFixed(8).toString();
		}

		return retId;
	}


	visualSet.init();
	visualSet_m.init();
</script>