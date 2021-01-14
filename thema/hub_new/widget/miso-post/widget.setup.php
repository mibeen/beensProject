<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// input의 name을 wset[배열키] 형태로 등록
// 모바일 설정값은 동일 배열키에 배열변수만 wmset으로 지정 → wmset[배열키]

// 노출수
if($wset['items']) {
	list($wset['item'], $wset['lg'], $wset['md'],$wset['sm'],$wset['xs']) = explode(",", $wset['items']);
}
if(!$wset['item']) $wset['item'] = 1;

if($wset['heights']) {
	list($wset['height'], $wset['hl'], $wset['hm'],$wset['hs'],$wset['hx']) = explode(",", $wset['heights']);
}
if(!$wset['height']) $wset['height'] = '75%';
if($wset['margin'] == "") $wset['margin'] = 10;
if($wset['thumb_w'] == "") $wset['thumb_w'] = 400;
if($wset['thumb_h'] == "") $wset['thumb_h'] = 0;

if(!$wset['skin']) $wset['skin'] = 'basic';

$wlist = array();
$wdata = array();
$wlist = thema_switcher('file', $widget_path.'/skin', $wset['skin'], "php");
$wdata = thema_switcher('file', $widget_path.'/data', $wset['data'], "php");

?>

<div class="tbl_head01 tbl_wrap">
	<table>
	<caption>위젯설정</caption>
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
		</td>
	</tr>
	<tr>
		<td align="center" rowspan="3">스타일</td>
		<td>
			<?php echo help('스킨에 따라 적용안될 수 있슴');?>
			<select name="wset[mode]">
				<option value=""<?php echo get_selected('', $wset['mode']); ?>>일반</option>
				<option value="1"<?php echo get_selected('1', $wset['mode']); ?>>더보기</option>
				<option value="2"<?php echo get_selected('2', $wset['mode']); ?>>무한스크롤</option>
			</select>
			모드
			&nbsp;
			불투명
			<select name="wset[opa]">
				<option value=""<?php echo get_selected('', $wset['opa']); ?>>없음</option>
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
				<option value=""<?php echo get_selected('', $wset['raster']); ?>>없음</option>
				<option value="1"<?php echo get_selected('1', $wset['raster']); ?>>1번</option>
				<option value="2"<?php echo get_selected('2', $wset['raster']); ?>>2번</option>
				<option value="3"<?php echo get_selected('3', $wset['raster']); ?>>3번</option>
				<option value="4"<?php echo get_selected('4', $wset['raster']); ?>>4번</option>
				<option value="5"<?php echo get_selected('5', $wset['raster']); ?>>5번</option>
			</select>
			&nbsp;
			<label><input type="checkbox" name="wset[rdm]" value="1"<?php echo get_checked('1', $wset['rdm']); ?>> 랜덤목록</label>
		</td>
	</tr>
	<tr>
		<td>
			<input type="text" name="wset[round]" value="<?php echo $wset['round']; ?>" class="frm_input" size="4"> px 라운드 (1 입력시 원형)
			&nbsp;
			<select name="wset[masonry]">
				<option value=""<?php echo get_selected('', $wset['masonry']); ?>>사용안함</option>
				<option value="1"<?php echo get_selected('1', $wset['masonry']); ?>>고정비율</option>
				<option value="2"<?php echo get_selected('2', $wset['masonry']); ?>>원본비율</option>
			</select>
			메이슨리(Masory)
		</td>
	</tr>
	<tr>
		<td>
			<input type="text" name="wset[micon]" id="mcon" value="<?php echo ($wset['micon']);?>" size="44" class="frm_input"> 
			<a href="<?php echo G5_BBS_URL;?>/ficon.php?fid=mcon" class="btn_frmline win_scrap">더보기 아이콘 선택</a>
		</td>
	</tr>
	<tr>
		<td align="center">간격설정</td>
		<td>
			<input type="text" name="wset[margin]" value="<?php echo $wset['margin']; ?>" class="frm_input" size="4"> px 가로 간격(마진)
			&nbsp;
			<input type="text" name="wset[gap]" value="<?php echo $wset['gap']; ?>" class="frm_input" size="4"> px 세로 간격(미설정시 가로 간격 자동적용)
		</td>
	</tr>
	<tr>
		<td align="center">출력설정</td>
		<td>
			<?php echo help('이미지 높이는 px(고정) 또는 %(가로대비 세로비율)의 단위까지 반드시 입력');?>
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
				<td align="center">노출수(개)</td>
				<td align="center">
					<input type="text" name="wset[item]" value="<?php echo $wset['item']; ?>" class="frm_input" size="6">
				</td>
				<td align="center">
					<input type="text" name="wset[lg]" value="<?php echo $wset['lg']; ?>" class="frm_input" size="6">
				</td>
				<td align="center">
					<input type="text" name="wset[md]" value="<?php echo $wset['md']; ?>" class="frm_input" size="6">
				</td>
				<td align="center">
					<input type="text" name="wset[sm]" value="<?php echo $wset['sm']; ?>" class="frm_input" size="6">
				</td>
				<td align="center">
					<input type="text" name="wset[xs]" value="<?php echo $wset['xs']; ?>" class="frm_input" size="6">
				</td>
			</tr>
			<tr>
				<td align="center">높이(%,px)</td>
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
		<td align="center">세로수</td>
		<td>
			<input type="text" name="wset[sero]" value="<?php echo $wset['sero']; ?>" class="frm_input" size="4">
			개 - PC
			&nbsp;
			<input type="text" name="wmset[sero]" value="<?php echo $wmset['sero']; ?>" class="frm_input" size="4">
			개 - 모바일 (하나의 아이템 내에서 세로 배치 갯수)
		</td>
	</tr>
	<tr>
		<td align="center">썸네일</td>
		<td>
			<?php echo help('썸네일 가로너비에 0 입력시 원본 이미지 출력');?>
			<input type="text" name="wset[thumb_w]" value="<?php echo $wset['thumb_w']; ?>" class="frm_input" size="4">
			x
			<input type="text" name="wset[thumb_h]" value="<?php echo $wset['thumb_h']; ?>" class="frm_input" size="4">
			px
			&nbsp;
			<label><input type="checkbox" name="wset[cap]" value="1"<?php echo get_checked('1', $wset['cap']); ?>> 새글/랭킹 삼각아이콘 출력</label>
		</td>
	</tr>
	<tr>
		<td align="center">가로너비</td>
		<td>
			<?php echo help('웹진 등 가로 배치 스킨의 이미지 너비로 스킨에 따라 출력안될 수 있슴');?>
			<input type="text" name="wset[img_w]" value="<?php echo $wset['img_w']; ?>" class="frm_input" size="4">
			px 또는 % 단위까지 반드시 입력
		</td>
	</tr>
	<tr>
		<td align="center">노이미지</td>
		<td>
			<input type="text" name="wset[no_img]" value="<?php echo ($wset['no_img']);?>" id="no_img" size="44" class="frm_input"> 
			<a href="<?php echo G5_BBS_URL;?>/widget.image.php?type=image&amp;fid=no_img" class="btn_frmline win_scrap">등록/선택하기</a>
		</td>
	</tr>
	<tr>
		<td align="center">글아이콘</td>
		<td>
			<?php echo help('스킨에 따라 출력안될 수 있슴');?>
			<input type="text" name="wset[icon]" id="fcon" value="<?php echo ($wset['icon']);?>" size="44" class="frm_input"> 
			<a href="<?php echo G5_BBS_URL;?>/ficon.php?fid=fcon" class="btn_frmline win_scrap">아이콘 선택</a>
		</td>
	</tr>
	<tr>
		<td align="center">글머리</td>
		<td>
			<?php echo help('분류명이 없을 경우 게시판명이 자동 출력되며, 스킨에 따라 출력안될 수 있슴');?>
			<select name="wset[bullet]">
				<option value=""<?php echo get_selected('', $wset['bullet']);?>>사용안함</option>
				<option value="1"<?php echo get_selected('1', $wset['bullet']);?>>분류명</option>
				<option value="2"<?php echo get_selected('2', $wset['bullet']);?>>게시판명</option>
			</select>
			&nbsp;
			<input type="text" name="wset[bulcut]" value="<?php echo $wset['bulcut']; ?>" class="frm_input" size="4">
			자 출력 (0 입력시 전체출력)
		</td>
	</tr>
	<tr>
		<td align="center">글링크</td>
		<td>
			<?php echo help('이미지 라이트박스는 스킨에 따라 적용안될 수 있슴');?>
			<select name="wset[modal]">
				<option value=""<?php echo get_selected('', $wset['modal']);?>>글내용 - 현재창</option>
				<option value="1"<?php echo get_selected('1', $wset['modal']);?>>글내용 - 모달창</option>
				<option value="2"<?php echo get_selected('2', $wset['modal']);?>>링크#1 - 현재창</option>
				<option value="3"<?php echo get_selected('3', $wset['modal']);?>>링크#1 - 새창</option>
			</select>
			&nbsp;
			<label><input type="checkbox" name="wset[lightbox]" value="1"<?php echo get_checked('1', $wset['lightbox']); ?>> 이미지 라이트박스 적용</label>
		</td>
	</tr>
	<tr>
		<td align="center">글라인</td>
		<td>
			<?php echo help('제목이나 글내용 등 출력 줄수이며, 스킨에 따라 적용안될 수 있슴');?>
			<input type="text" name="wset[line]" value="<?php echo $wset['line']; ?>" class="frm_input" size="4">
			줄 출력 (기본 1)
			-
			한 줄 높이
			<input type="text" name="wset[lineh]" value="<?php echo $wset['lineh']; ?>" class="frm_input" size="4"> px(기본 20)
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
		</td>
	</tr>
	<tr>
		<td align="center">추출옵션</td>
		<td>
			<label><input type="checkbox" name="wset[comment]" value="1"<?php echo get_checked('1', $wset['comment']); ?>> 댓글</label>
			&nbsp;
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
		<td align="center">새글설정</td>
		<td>
			<input type="text" name="wset[newtime]" value="<?php echo ($wset['newtime']);?>" size="4" class="frm_input"> 시간 이내 등록 글
			&nbsp;
			색상
			<select name="wset[new]">
				<?php echo apms_color_options($wset['new']);?>
			</select>
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