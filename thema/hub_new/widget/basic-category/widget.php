<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

global $menu;

include(G5_PATH. "/gseek/nx_gseek_menu.php");

//add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$widget_url.'/widget.css">', 0);

for ($i=0; $i < count($menu); $i++) {

	if($menu[$i]['on'] == "on" && $menu[$i]['is_sub']) {
	    #j+변수의 outboundIndex Exception  방지를 위해 처리
	    $nextDepth2Title = 'N';
	    if ($j + 1 < count($menu[$i]['sub']) && $nextDepth2Title != 'Y') {
	        $nextDepth2Title = $menu[$i]['sub'][$j+1]['depth2'];
	    }
	    
		# 타이틀 메뉴 관련 변수 저장
		$tit_exist = false; // 타이틀메뉴 존재 여부
		$tit_num = 0;
		for($j=0; $j < count($menu[$i]['sub']); $j++) {
		    if ($menu[$i]['sub'][$j]['depth2'] == 'Y') {
				$tit_num++;
				$tit_exist = true;
		    }
			else if ($menu[$i]['sub'][$j]['on'] == 'on'){ 
			     $tit_on = $tit_num; // on 되어있는 메뉴의 타이틀메뉴 저장
			}
		}
?>
	<div id="lnb" class="basic-category nx_basic_category">
		<div class="ca-head">
			<?php echo $menu[$i]['name'];?>
		</div>
		<?php
		if(count($menu[$i]['sub']) == 0 && $menu[$i]['gr_id'] == "learning")
		{
			echo $Menu_Left_Tag;
		}
		?>
		<?php 
			# 타이틀 메뉴 설정
			$last_tit_menu = 0; // 마지막 타이틀메뉴가 몇번째 타이틀 메뉴인지
			for($j=0; $j < count($menu[$i]['sub']); $j++) { 
			    #j+변수의 outboundIndex Exception  방지를 위해 처리
			    $nextDepth2Title = 'N';
			    if ($j + 1 < count($menu[$i]['sub'])) {
			        $nextDepth2Title = $menu[$i]['sub'][$j+1]['depth2'];
			    }
			    
			    $class_text = "";
			    #2depth title이 아닐때
			    if ($menu[$i]['sub'][$j]['depth2'] != 'Y') {
			    }
			    #타이틀 체크를 했지만, 2depth 메뉴처럼 쓰고 싶은경우
			    else if($menu[$i]['sub'][$j]['depth2'] == 'Y' && $nextDepth2Title == 'Y' ){
			        $last_tit_menu++;
			    }
			    #2depth title 일때
			    else {
			        # 타이틀 메뉴가 가장 마지막 메뉴메뉴가 아니면
			        if ($j + 1 < count($menu[$i]['sub'])) {
			            $class_text .= ' tit-menu';
			            $last_tit_menu++; // 타이틀메뉴이면 +1 
			        }
			    }
			    
			    
				
				
				if($last_tit_menu == $tit_on) $class_text .= ' tit-on'; // 현재 메뉴가 타이틀에 속해있다면 타이틀 on 표시
				if($last_tit_menu == $tit_num) $class_text .= ' tit-last'; // 마지막 타이틀메뉴

				$style_text = "";
				$depth2_text ="";

				# 타이틀메뉴가 있고 && 첫번째 타이틀 메뉴가 이미 지나갔거나 지금이 첫번째 일때
				if ($tit_exist &&  $last_tit_menu > 0) {
					#2depth title이 아닐때
					if ($menu[$i]['sub'][$j]['depth2'] != 'Y') {
					    $depth2_text .= ' data-parent="' . $last_tit_menu . '"';
					    #마지막 타이틀 메뉴에 현재 열린 메뉴가 속해있지 않으면
					    if ($menu[$i]['sub'][$j]['depth2'] != 'Y' && $last_tit_menu != $tit_on) {
					        $style_text .= ' style="display:none;"'; // 숨김
					    }
					}
					#타이틀 체크를 했지만, 2depth 메뉴처럼 쓰고 싶은경우
					else if($menu[$i]['sub'][$j]['depth2'] == 'Y' && $nextDepth2Title == 'Y' ){
					}
					#2depth title 일때
					else {
					    if ($j + 1 < count($menu[$i]['sub'])) {
					        $depth2_text .= ' data-depth2="' . $last_tit_menu . '"';
					    }else{
					        $style_text .= ' style="border-top: 1px solid #F1F1F1;"';
					    }
					}
				}
				

				if($menu[$i]['sub'][$j]['line']) { //구분라인이 있을 때 ?>
				<div class="ca-line">
					<b><?php echo $menu[$i]['sub'][$j]['line'];?></b>
				</div>
			<?php } ?>
			<div class="ca-sub1 <?php echo $menu[$i]['sub'][$j]['on'];?><?php echo($class_text)?>"<?php echo($depth2_text)?><?php echo($style_text)?>>
				<a href="
				    <?php 
				        #2depth title이 아닐때
				        if ($menu[$i]['sub'][$j]['depth2'] != 'Y') {
				            echo($menu[$i]['sub'][$j]['href']);
				        }
				        #타이틀 체크를 했지만, 2depth 메뉴처럼 쓰고 싶은경우
				        else if($menu[$i]['sub'][$j]['depth2'] == 'Y' && $nextDepth2Title == 'Y' ){
				            echo($menu[$i]['sub'][$j]['href']);
				        }
				        #2depth title 일때
				        else {
				            # 타이틀 메뉴가 가장 마지막 메뉴메뉴가 아니면
				            if ($j + 1 < count($menu[$i]['sub'])) {
				                echo('javascript:void(0)');
				            }else{
				                echo($menu[$i]['sub'][$j]['href']);
				            }
				        }
				    ?>
				    "
				    <?php 
				        echo $menu[$i]['sub'][$j]['target'];
			        ?> 
			        class="
			        <?php 
			             echo ($menu[$i]['sub'][$j]['is_sub']) ? 'is' : 'no';
		            ?>-sub"
		            style="
		            <?php 
    		            #2depth title이 아닐때
    		            if ($menu[$i]['sub'][$j]['depth2'] != 'Y') {
    		            }
    		            #타이틀 체크를 했지만, 2depth 메뉴처럼 쓰고 싶은경우
    		            else if($menu[$i]['sub'][$j]['depth2'] == 'Y' && $nextDepth2Title == 'Y' ){
    		            }
    		            #2depth title 일때
    		            else {
    		                # 타이틀 메뉴가 가장 마지막 메뉴메뉴가 아니면
    		                if ($j + 1 < count($menu[$i]['sub'])) {
    		                }else{
    		                    echo('padding: 10px 12px;');
    		                }
    		            }
		            ?> 
		            "
		            >
					<?php
					# 타이틀 메뉴이면 꺾쇠 아이콘 표시 
					#2depth title이 아닐때
					if ($menu[$i]['sub'][$j]['depth2'] != 'Y') {
					}
					#타이틀 체크를 했지만, 2depth 메뉴처럼 쓰고 싶은경우
					else if($menu[$i]['sub'][$j]['depth2'] == 'Y' && $nextDepth2Title == 'Y' ){
					}
					#2depth title 일때
					else {
					    # 타이틀 메뉴가 가장 마지막 메뉴가 아니면
					    if ($j + 1 < count($menu[$i]['sub'])) {
					        ?>
    						<div class="fR">
    							<i class="fa fa-angle-down off"></i>
    							<i class="fa fa-angle-up on"></i>
    						</div>
    						<?php
					    }
					}
					?>
					<?php echo $menu[$i]['sub'][$j]['name'];?>
					<?php 
					/*
					if($menu[$i]['sub'][$j]['new'] == 'new') { 
					?>
						<i class="fa fa-bolt new"></i>
						<?php 
					} 
					*/
					?>
				</a>
			</div>
			<?php 
			/*
			if($menu[$i]['sub'][$j]['is_sub'] && $menu[$i]['sub'][$j]['on'] == 'on') { // 선택메뉴이면 서브 출력 ?>
				<ul class="ca-sub2">
				<?php for($k=0; $k < count($menu[$i]['sub'][$j]['sub']); $k++) { ?>
					<li class="<?php echo $menu[$i]['sub'][$j]['sub'][$k]['on']; ?>">
						<a href="<?php echo $menu[$i]['sub'][$j]['sub'][$k]['href'];?>"<?php echo $menu[$i]['sub'][$j]['sub'][$k]['target'];?>>
							<?php echo $menu[$i]['sub'][$j]['sub'][$k]['name'];?>
						</a>
					</li>
				<?php } ?>
				</ul>
			<?php }
			*/
			?>
		<?php } ?>
	</div>
<?php 
		break;
	} 
} 

# 마이페이지 메뉴일 경우 고정 메뉴 노출
global $_gr_id, $pid;
if ($_gr_id == 'nx05') {
	?>
	<div class="basic-category nx_basic_category">
		<div class="ca-head">
			마이페이지
		</div>
		<div class="ca-sub1<?php if($pid == 'myedu') {echo(' on');}?>">
			<a href="/gseek/nx_my_list.php" class="no-sub">나의 학습</a>
		</div>
		<div class="ca-sub1<?php if($pid == 'myevt') {echo(' on');}?>">
			<a href="/my/evt.list.php" class="no-sub">모집</a>
		</div>
		<div class="ca-sub1<?php if($pid == 'myrent') {echo(' on');}?>">
			<a href="/my/rent.list.php" class="no-sub">대관</a>
		</div>
		<div class="ca-sub1<?php if($pid == 'editprofile') {echo(' on');}?>">
			<a href="javascript:win_open2('<?php echo(GSK_www_URL)?>/memb/myss.edit.info.pwcheck', '600');" class="no-sub">개인정보수정</a>
		</div>
		<div class="ca-sub1<?php if($pid == 'editpw') {echo(' on');}?>">
			<a href="javascript:win_open2('<?php echo(GSK_www_URL)?>/memb/myss.edit.pw.pwcheck', '600');" class="no-sub">비밀번호변경</a>
		</div>
		<div class="ca-sub1<?php if($pid == 'myout') {echo(' on');}?>">
			<a href="/my/outintro.php" class="no-sub">탈퇴하기</a>
		</div>
	</div>
	<?php
}
?>

<?php # 타이틀메뉴 여닫기 스크립트  ?>
<script>
$('#lnb .ca-sub1.tit-menu a').on('click', function() {
	var depth2 = $(this).parent().attr('data-depth2');

	// 열려있으면
	if ($(this).parent().hasClass('tit-on')) {
		$('#lnb .ca-sub1[data-parent='+depth2+']').slideUp('fast');
		$(this).parent().removeClass('tit-on');
	}
	// 닫혀있으면
	else {
		$('#lnb .ca-sub1[data-parent='+depth2+']').slideDown('fast');
		$(this).parent().addClass('tit-on');
	}
});
</script>