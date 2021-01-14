<?php
    include_once("../common.php");
    include_once(G5_LIB_PATH.'/apms.thema.lib.php');


    $g5['title'] = $fm['fm_subject'];


    include_once('../bbs/_head.php');


    $result_ary = GetListItemView();
?>
<style>
    #bo_v_sns {
        margin: 4px 0 15px;
        padding: 0;
        list-style: none;
        zoom: 1;
        list-style: none;
    }

    #bo_v_sns > li {
        display: inline-block;
        list-style: none;
        padding: 0;
    }

	.nx_pen_style{background-color: #FF5E00; color:#fff; height:25px; padding:5px; text-align:center;}
</style>
<script src="<?php echo G5_URL.'/gseek/gseek.js'; ?>"></script>
<script src="<?php echo G5_URL.'/gseek/jquery.cookie.old.js';?>"></script>

<p class="nx_page_tit">배움터</p>
<div class="nx_path_wrap">
    <a href="nx_learn_list.php" role="button" class="btn btn-black btn-sm fR"><i class="fa fa-bars"></i><span class="hidden-xs"> 목록</span></a>
</div>
<div class="data_ct">
    <h3 class="learn_tit"><?php echo $result_ary[title]; ?></h3>
    <div class="learn_read_info">
        <div class="nx_title_img">
            <div class="img_wrap1">
                <div class="img_wrap2">
                    <?php
                    if($result_ary[main_video]) {
                    if($result_ary[main_type]) {
                    ?>

                    <video id="player" src="<?php echo($result_ary[main_video])?>"<?php if($result_ary[main_video_poster] != '') { ?> poster="<?php echo($result_ary[main_video_poster])?>"<?php } ?> controls width="100%" height="100%" style="background:#000000;">
							<source src="<?php echo($result_ary[main_video])?>" type="video/mp4" />
						</video>
                    <?php
                    } else {
                    ?>
                    <iframe width="100%" height="100%" src="<?php echo($result_ary[main_video])?>" frameborder="0" allowfullscreen></iframe>
                    <?php
                    }
                    } else if($result_ary[main_img]) {
                    ?>
                    <img src="<?php echo($result_ary[main_img])?>" style="width:100%;" onerror="this.onerror=null;this.src='<?php echo($result_ary[main_eimg])?>';" alt="" id="imgMain"/>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="info_wrap">
            <div class="star_rate_wrap mb25">
                <div class="star_rate">
                    <?php
                    echo(showStarIco($result_ary[study_star]));
                    /*
                    $s = ceil($result_ary[study_star]);

                    //echo $s;
                    for($f=5; $f >= 1; $f--){
                    if($f == $s)
                    echo '<span class="star_ico on half"><span class="ico_star_full"></span><span class="ico_star_half"></span></span>';
                    else
                    echo '<span class="star_ico"><span class="ico_star_full"></span><span class="ico_star_half"></span></span>';
                    }
                    */
                    ?>
                </div>
                <span class="cnt"><?php  echo (number_format($result_ary[study_star], 2)." (". $result_ary[study_star_count] ."명 참여)"); ?></span>
                <!-- <span class="cnt"><?php  echo (round($result_ary[study_star], 2)." (". $result_ary[study_star_count] ."명 참여)"); ?></span> -->
            </div>
            <div class="learn_info_dl">
                <dl>
                    <dt>강의 수</dt>
                    <dd><?php echo $result_ary[study_count]; ?></dd>
                </dl>
                <dl class="mb15">
                    <dt>수료기준</dt>
                    <dd>
                        <?php
                        if($result_ary[study_sg_comp_rate]) echo '진도율 '.$result_ary[study_sg_comp_rate].'% 이상<br>';
                        if($result_ary[study_sg_comp_point]) echo '점수 '.$result_ary[study_sg_comp_point].'점 이상<br>';
                        if($result_ary[study_sg_comp_tcount]) echo '필수 차시 '.$result_ary[study_sg_comp_tcount].'개<br>';
                        ?>
                    </dd>
                </dl>
                <dl>
                    <dt>모집인원</dt>
                    <dd><?php echo $result_ary[study_sg_people_cnt];?>명</dd>
                </dl>
                <dl>
                    <dt>신청기간</dt>
                    <dd><?php echo $result_ary[study_regi_type_print];?></dd>
                </dl>
                <dl>
                    <dt>학습기간</dt>
                    <dd><?php echo $result_ary[study_study_type_print];?></dd>
                </dl>
                <dl>
                    <dt>학습시간</dt>
                    <dd><?php echo $result_ary[study_sg_time];?></dd>
                </dl>
                <dl>
                    <dt>강사</dt>
                    <dd><?php echo $result_ary[study_lecturer];?></dd>
                </dl>
                <?php
                // 수강 신청을 한 경우
                if($result_ary[study_is_apply]=='Y') {
                ?>
                <dl>
                    <dt>진도율</dt>
                    <dd><?php echo $result_ary[study_progress]; ?> %</dd>
                </dl>
                <?php
                }
                ?>
            </div>

<?php if($member['mb_id']){ ?>
            <div class="nx_btn_area">
                <div class="ofH">
                    <?php
					$http_host = $_SERVER['HTTP_HOST'];
					$request_uri = $_SERVER['REQUEST_URI'];
					$url = 'http://' . $http_host . $request_uri;
		
					$text_msg = "";

                    // 수강 신청을 한 경우
                    if($result_ary[study_is_apply]=='Y') {
                    // 학습이 가능한 경우
                    if($result_ary[study_can_study]=='Y' && $result_ary[buttion_study_name] != "") { ?>
					<?php /*
					<a href="<?php echo $result_ary[button_study_link];?>" class="nx_btn type1" target="_blank"><?php echo $result_ary[buttion_study_name];?><!-- 01 --></a>
					*/ ?>
					<a onclick="winLearn('<?php echo $result_ary[button_study_link];?>', '<?php echo $url; ?>', '<?php echo str_replace("'", "\\'", $result_ary[buttion_study_name]);?>');" class="nx_btn type1"><?php echo $result_ary[buttion_study_name];?></a>
                    <?php }
                    // 학습기간 이전일 경우
                    if($result_ary[can_study_after]=='Y')
						$text_msg .= $result_ary[buttion_study_after_day_msg];
                    //echo "<br/><h5 class='nx_pen_style'>".$result_ary[buttion_study_after_day_msg]."</h5><!--t01-->";

                    if($result_ary[can_study_max1day] == 'Y')
						$text_msg .= $result_ary[buttion_study_max1day_msg];
                    //echo "<br/><h5 class='nx_pen_style'>".$result_ary[buttion_study_max1day_msg]."</h5><!--t02-->";

                    // 수강취소가 가능한 경우
                    if($result_ary[study_can_cencal]=='Y') { ?>
                    <a onclick="winPopup('<?php echo UrlParamPlus($result_ary[buttion_cencal_link], G5_URL."/gseek/nx_learn_state.php"); ?>');" class="nx_btn type1" >수강취소</a>
                    <?php	}

                    // 수료증 출력이 가능한 경우
                    if($result_ary[study_can_certificate]=='Y' && $result_ary[buttion_certificate_name] != "") {	?>
                    <?php /* <a onclick="winPopup('<?php echo UrlParamPlus($result_ary[buttion_certificate_link], G5_URL."/gseek/nx_learn_state.php"); ?>');" class="nx_btn type1"><?php echo $result_ary[buttion_certificate_name];?><!-- 02 --></a> */ ?>
                    <a href="<?php echo $result_ary[buttion_certificate_link]; ?>" target="_blank" class="nx_btn type1"><?php echo $result_ary[buttion_certificate_name];?><!-- 02 --></a>
                    <?php	}

                    // 학습기간 이전일 경우
                    if($result_ary[can_study_before]=='Y')
						$text_msg .= $result_ary[buttion_study_before_msg];
                    //echo "<br/><h5 class='nx_pen_style'>".$result_ary[buttion_study_before_msg]."</h5><!--t04-->";

                    // 학습기간 이후일 경우
                    if($result_ary[can_study_end]=='Y')
						$text_msg .= $result_ary[buttion_study_end_msg];
                    //echo "<br/><h5 class='nx_pen_style'>".$result_ary[buttion_study_end_msg]."</h5><!--t05-->";

                    // 재수강이 가능한 경우
                    if($result_ary[study_can_reapply]=='Y' && $result_ary[buttion_apply_name] != "") {		?>
                    <a onclick="winPopup('<?php echo UrlParamPlus($result_ary[buttion_apply_link], G5_URL."/gseek/nx_learn_state.php"); ?>');" class="nx_btn type1"><?php echo $result_ary[buttion_apply_name]?><!-- 03 --></a>
                    <?php	}

                    // 수강 신청 전
                    } else {

                    // 수강 신청이 가능하면.
                    if($result_ary[study_can_apply] && $result_ary[buttion_apply_name] != "") {	?>
                    <a onclick="winPopup('<?php echo UrlParamPlus($result_ary[buttion_apply_link], G5_URL."/gseek/nx_learn_state.php"); ?>');" class="nx_btn type1"><?php echo $result_ary[buttion_apply_name]?><!-- 04 --></a>
                    <?php	}

                    // 수강 신청 가능일 이전이면
                    if($result_ary[can_apply_before])
						$text_msg .= $result_ary[buttion_apply_before_msg];
                    //echo "<br/><h5 class='nx_pen_style'>".$result_ary[buttion_apply_before_msg]."</h5><!--t06-->";

                    // 수강 신청일 종료 이면
                    if($result_ary[can_apply_end])
						$text_msg .= $result_ary[buttion_apply_end_msg];
                    //echo "<br/><h5 class='nx_pen_style'>".$result_ary[buttion_apply_end_msg]."</h5><!--t07-->";

                    // 학습이 가능한 경우
                    if($result_ary[study_can_preview]=='Y' && $result_ary[buttion_preview_name] != "") {		?>
                    <a href="<?php echo $result_ary[button_preview_link]; ?>" target="_blank" class="nx_btn type2"><?php echo $result_ary[buttion_preview_name];?><!-- 05 --></a> 
                    <?php	}

                    // 학습이 가능한 경우
                    if($result_ary[study_can_basket]=='Y') {
                    if($result_ary[study_is_basket]=='Y' && $result_ary[buttion_basket_name] != "") {					?>
                    <a onclick="winPopup('<?php echo UrlParamPlus($result_ary[buttion_basket_del_link], G5_URL."/gseek/nx_learn_state.php"); ?>');" class="nx_btn type3"><?php echo $result_ary[buttion_basket_name];?><!-- 06 --></a>
                    <?php
                    } else {
                    ?>
                    <a onclick="winPopup('<?php echo UrlParamPlus($result_ary[buttion_basket_add_link], G5_URL."/gseek/nx_learn_state.php"); ?>');" class="nx_btn type3"><?php echo $result_ary[buttion_basket_name];?> <span class="ico_heart"></span><!-- 07 --></a>
                    <?php
                    }
                    }

                    // 학습이 가능한 경우
                    if($result_ary[can_apply_people]=='Y')
						$text_msg .= $result_ary[buttion_apply_people_msg];
                    //echo "<br/><h5 class='nx_pen_style'>".$result_ary[buttion_apply_people_msg]."</h5><!--t08-->";

                    } ?>
                </div>

				<?php 
				if(strlen($text_msg) > 2)
					echo "<div class='ofH'><br/><h5 class='nx_pen_style'>". $text_msg ."</h5></div>";
				?>


                <?php
                /* # 숨김 요청
                <div class="mt20">

                    <?php
                    $board['bo_use_sns'] = 1;
                    include_once(G5_SNS_PATH."/view.sns.skin.php"); // SNS
                    ?>


                </div>
                */
                ?>
            </div>
<?php }else{ ?>
		<div class="nx_btn_area">
           <div class="ofH">
				<a href="javascript:fn_member(1);" class="nx_btn2 fR">로그인 후 이용 가능합니다.</a>
			</div>
		</div>

<?php } ?>
        </div>
    </div>





    <div id="tab" class="learn_tab">
        <div id="tbaM1" class="on">
            <a onclick="fnBoxDisplay('_01', 'learn_read_ct'); SetTabMenu('1');">강의내용</a>
        </div>
        <div id="tbaM2">
            <a onclick="fnBoxDisplay('_02', 'learn_read_ct'); SetTabMenu('2');">학습톡톡</a>
        </div>
        <div id="tbaM3">
            <a onclick="fnBoxDisplay('_03', 'learn_read_ct'); SetTabMenu('3');">수강후기</a>
        </div>
        <span class="border"></span>
    </div>

    <div class="learn_read_ct" id="learn_read_ct_01" style="display:block">
        <h4 class="nx_tit2 mb25">강의내용</h4>

        <p class="nx_para1">
            <?php echo $result_ary[study_introduction]; ?>
        </p>
        <?php /*
        <p class="nx_para1 mt30">
            <a href="" target="_blank">www.abc.co.kr</a>
        </p>
        */ ?>
        <?php /*
        <h6 class="nx_tit3 mt30">학습자료</h6>
        <ul class="atnx_taChment_lst">
            <li><a href="">[HD]한글 프로그램을 활용한 보고서 작성 실무1.zip</a></li>
            <li><a href="">[HD]한글 프로그램을 활용한 보고서 작성 실무2.zip</a></li>
        </ul>
        */ ?>
        <h5 class="nx_tit2 mt50">학습목록</h5>

        <?php
        $response_json_obj = GetListItemView_Bottom();

        $no = 1;
        for($i = 0; $i < count($response_json_obj['result_list']); $i++) {
        $conn = $response_json_obj['result_list'][$i];

        $cha = 1;
        ?>
		<?php
			$response_json_sub_obj = GetLearnButton($conn['CM_CODE']);
			for($z=0; $z < count($response_json_sub_obj['result']); $z++)
			{
				echo '<span id="divlearnbtn_title'. $no .'" style="display:none;">'. $response_json_sub_obj['result'][$z]['title']. "</span>";
				echo '<span id="divlearnbtn_count'. $no .'" style="display:none;">'. $response_json_sub_obj['result'][$z]['study_count']. "</span>";
				echo '<span id="divlearnbtn_main_img'. $no .'" style="display:none;">'. $response_json_sub_obj['result'][$z]['main_img']. "</span>";
				echo '<span id="divlearnbtn_main_eimg'. $no .'" style="display:none;">'. $response_json_sub_obj['result'][$z]['main_eimg']. "</span>";
				echo '<span id="divlearnbtn_link'. $no .'" style="display:none;">'. $response_json_sub_obj['result'][$z]['pds_is_link']. "</span>";
				echo '<span id="divlearnbtn_msg'. $no .'" style="display:none;">'. $response_json_sub_obj['result'][$z]['pds_message']. "</span>";
			}
			?>		

        <div class="learn_session_wrap">
            <div class="top">
                <span class="num">학습 <?php echo $no; ?>.</span>
                <div class="name">
                    <p>
                        <?php echo $conn[conn_title]; ?>
                    </p>
                </div>
                <a class="nx_btn type3" onclick="fnLearnButton('<?php echo $no; ?>');">학습 소개</a>
                <a class="fold" onclick="fnBoxDisplay('<?php echo $no; ?>', 'nx_ct');"><span class="ico_arr1_d"></span><span class="ico_arr1_u"></span></a>
				
						
            </div>
            <div class="nx_ct" id="nx_ct<?php echo $no; ?>"<?php if($no != 1) {echo(' style="display:none;"');}?>>
                <ul class="learn_session_lst">
                    <?php
                    for($j = 0; $j < count($conn['conn_sub_list']); $j++) {
                        $conn_sub = $conn['conn_sub_list'][$j];
                        ?>
                    <li>
                        <p class="num">
                            <?php echo $cha; ?>차시
                        </p>
                        <p class="nx_tit">
                            <?php echo('<script>console.log("'.$conn_sub['conn_study_time_second_print'].'")</script>')?>
                            <?php echo $conn_sub[conn_sub_title] ;?>
                            <?php
                            if ($conn_sub['conn_study_time_print'] != '' && $conn_sub['conn_study_time_print'] > 0) {
                                $learn_time = $conn_sub['conn_study_time_second_print'];
                                $learn_min = ($learn_time > 0) ? floor($learn_time/60) : 0;
                                $learn_sec = ($learn_time > 0) ? $learn_time - ($learn_min*60) : 0;
                                echo('<script>console.log("'.$conn_sub['conn_study_time_second_print'].'")</script>');
                                ?>
                            <br>
                            <span class="nx_time mr20">최소 학습시간: <?php echo($conn_sub['conn_study_time_print'])?>분</span>
                            <span class="nx_time">
                                내가 학습한 시간: 
                                <?php if($learn_min) echo(round($learn_min)).'분'?>
                                <?php if($learn_sec) echo(round($learn_sec)).'초'?>
                                <?php if(!$learn_sec && !$learn_min) echo('0분')?>
                                </span>
                                <?php
                            }
                            ?>
                        </p>
                        <?php if($conn_sub[conn_study_bool] == 'Y') echo learn_Conn_btnName($conn_sub[conn_button_name], $conn_sub[conn_button_cls], $conn_sub[buttion_study_link], $url, $conn_sub[conn_sub_title], $conn_sub[main_img] ); ?>
                    </li>
                    <?php	$cha++; } ?>
            </div>
        </div>
        <?php 	$no++; } ?>
    </div>


    <?php

    $response_json_obj = GetListItemView_Re_List();

    ?>
    <div class="learn_read_ct" id="learn_read_ct_02" style="display:none">
        <h4 class="nx_tit2">학습톡톡</h4>
        <textarea id="txttalk" name="txttalk" class="nx_ips2" style="min-height:100px;"></textarea>
		<div class="taR mt10">
		<?php if(GSK_I_CODE != "GSK_I_CODE" && GSK_I_CODE != ""){ ?>
        <a onclick="SetIFrame('nx_talk_reply.php', '#txttalk', 'talk');" class="nx_btn_cmt_add">등록</a>
		<?php }else{ ?>
		<a onclick="win_open();"><h5><b>로그인 후 이용 가능합니다.</b></h5></a>
		<?php } ?>
		</div>


        <div class="cmt_outer mt30">
            <hr style="border:0; border-bottom:solid 1px #ccc;">
            <?php
            for($i = 0; $i < count($response_json_obj['result']); $i++) {
            $conn = $response_json_obj['result'][$i]['itm'];

            for($j = 0; $j < count($conn); $j++) {
            $conn_sub = $conn[$j];

            ?>

            <div class="cmt_wrap">
                <div class="cmt">
                    <?php
                    $response_pic_json_obj = GetMyProfile_Photo2($conn_sub["I_CODE"]);
                    $MY_PICS = $response_pic_json_obj[result][0];
                    $my_pic = $MY_PICS['my_img'];
                    $my_epic = $MY_PICS['my_eimg'];
                    ?>
                    <span class="profile_img">
                        <?php if($my_pic){ ?>
                        <img src="<?php echo $my_pic; ?>" onerror="this.onerror=null;this.src='<?php echo $my_epic; ?>';">
                        <?php }else{ ?>
                        <img src="<?php echo G5_URL.'/img/photo.png';?>" >
                        <?php } ?>
                    </span>
                    <p class="name ofH">
                        <strong><?php echo $conn_sub['NAME']?></strong>
                        <span class="date"><?php echo $conn_sub['SC_WDATE']?></span>
                    </p>
                    <div class="nx_ct"><p><?php echo $conn_sub['SC_CONT']?></p></div>
                </div>
                <div class="reply_area">

                    <div class="reply_top">
                        <?php //if($conn_sub['REPLY_CNT'] > 0){  ?>
                        <a onclick="fnBoxDisplay('<?php echo $j; ?>', 'reply_outer');" class="nx_btn_toggle">
                            <span class="cnt">답글 <span><?php echo $conn_sub['REPLY_CNT'] ;?></span></span>&nbsp;
                            <span class="ico_arr1_u"></span><span class="ico_arr1_d"></span>
                        </a>
                        <?php //} ?>
                        <?php if($conn_sub['DEL_BO']){  ?>
						<?php if(GSK_I_CODE != "GSK_I_CODE" && GSK_I_CODE != ""){ ?>
                        <a onclick="SetTalkDel('nx_talk_delete.php', '<?php echo $conn_sub['SC_IDX']; ?>', 'talk');" class="fR lh30" style="font-size:12px;color:red">삭제</a>
                        <?php } ?>
						<?php } ?>
                    </div>

                    <!--리플//-->
                    <div class="reply_outer" id="reply_outer<?php echo $j; ?>" style="display:none">
                        <?php if($conn_sub['REPLY_CNT'] > 0){  ?>
                        <!--리플시작//-->
                        <div class="reply_lst">
                            <?php
                            $response_json_obj_Re = GetListItemView_RRe_List($conn_sub['SC_IDX']);


                            for($g = 0; $g < count($response_json_obj_Re['result']); $g++) {
                            $conn_Re = $response_json_obj_Re['result'][$g]['itm'];

                            for($h = 0; $h < count($conn_Re); $h++) {
                            $conn_sub_Re = $conn_Re[$h];
                            $response_pic_json_obj_Re = GetMyProfile_Photo2($conn_sub_Re["I_CODE"]);
                            $MY_PICS = $response_pic_json_obj_Re[result][0];
                            $my_pic = $MY_PICS['my_img'];
                            $my_epic = $MY_PICS['my_eimg'];
                            ?>
                            <div class="reply">
                                <span class="profile_img">
                                    <?php if($my_pic){ ?>
                                    <img src="<?php echo $my_pic; ?>" onerror="this.onerror=null;this.src='<?php echo $my_epic; ?>';">
                                    <?php }else{ ?>
                                    <img src="<?php echo G5_URL.'/img/photo.png';?>" >
                                    <?php } ?>
                                </span>
                                <p class="name ofH">
                                    <strong><?php echo $conn_sub_Re['NAME'] ?></strong>
                                    <span class="date"><?php echo $conn_sub_Re['SC_WDATE']?></span>
                                </p>
                                <p class="nx_ct"><?php echo $conn_sub_Re['SC_CONT']?></p>
                                <div class="info">
                                    <?php if($conn_sub_Re['DEL_BO']){  ?>
									<?php if(GSK_I_CODE != "GSK_I_CODE" && GSK_I_CODE != ""){ ?>
                                    <a onclick="SetTalkDel('nx_talk_delete.php', '<?php echo $conn_sub_Re['SC_IDX']; ?>', 'talk');" class="del fR">삭제</a>
                                    <?php } ?>
									<?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                            <?php }   ?>
                        </div>
                        <!--//리플끝-->
                        <?php } ?>
                        <!--리플작성//-->
                        <div class="regist_reply">
                            <textarea id="txtReRe<?php echo $conn_sub['SC_IDX']; ?>" name="txtReRe<?php echo $conn_sub['SC_IDX']; ?>" class="nx_ips1" style="min-height:80px;"></textarea>
                            <div class="taR mt10">
                                <?php if(GSK_I_CODE != "GSK_I_CODE" && GSK_I_CODE != ""){ ?>
								<a onclick="SetIFrame('nx_talk_reply.php', '#txtReRe<?php echo $conn_sub['SC_IDX']; ?>', 'talkReRe', '<?php echo $conn_sub['SC_IDX']; ?>');" class="nx_btn_cmt_add">등록</a>
								<?php }else{ ?>
								<a onclick="win_open();"><h5><b>로그인 후 이용 가능합니다.</b></h5></a>
								<?php } ?>
                            </div>
                        </div>
                        <!--//리플작성끝-->
                    </div>
                    <!--//르플끝-->
                </div>
            </div>
            <?php
            }
            }
            ?>
        </div>
    </div>






    <div class="learn_read_ct" id="learn_read_ct_03" style="display:none">

        <h4 class="nx_tit2">수강후기</h4>

        <div class="star_average">
            <div class="star_rate">
                <?php
                echo(showStarIco($result_ary[study_star]));
                ?>
            </div>
            <span class="average">
                <span class="rate"><?php  echo number_format($result_ary[study_star], 2) ?></span>
                <span class="txt">평점 평균</span>
            </span>
        </div>

        <?php 	if(!GetStudy_memo_is_my()){ ?>
        <div class="star_set">
            <div class="star_rate">
                <input type="radio" id="s5" name="rdo_star" value="5"><label for="s5"><span class="ico_star_full"></span></label>
                <input type="radio" id="s4" name="rdo_star" value="4"><label for="s4"><span class="ico_star_full"></span></label>
                <input type="radio" id="s3" name="rdo_star" value="3"><label for="s3"><span class="ico_star_full"></span></label>
                <input type="radio" id="s2" name="rdo_star" value="2"><label for="s2"><span class="ico_star_full"></span></label>
                <input type="radio" id="s1" name="rdo_star" value="1"><label for="s1"><span class="ico_star_full"></span></label>
            </div>
        </div>
        <textarea id="txtsugang" name="txtsugang" class="nx_ips2" style="min-height:100px;" placeholder="수강 후기를 남겨주세요."></textarea>
		<div class="taR mt10">
		<?php if(GSK_I_CODE != "GSK_I_CODE" && GSK_I_CODE != ""){ ?>
        <a onclick="SetIFrame('nx_talk_reply.php', '#txtsugang', 'sugang');" class="nx_btn_cmt_add">등록</a>
		<?php }else{ ?>
		<a onclick="win_open();"><h5><b>로그인 후 이용 가능합니다.</b></h5></a>
		<?php } ?>
		</div>

        <?php } ?>


        <div class="cmt_outer mt30">
            <?php
            $response_json_obj = GetStudy_memo_List();

            for($i = 0; $i < count($response_json_obj['result']); $i++) {
            $conn = $response_json_obj['result'][$i]['itm'];
            for($j = 0; $j < count($conn); $j++) {
            $conn_sub = $conn[$j];
            ?>
            <div class="cmt_wrap">
                <div class="cmt">
		<?php
		$response_pic_json_obj = GetMyProfile_Photo2($conn_sub["I_CODE"]);
		$MY_PICS = $response_pic_json_obj[result][0];
		$my_pic = $MY_PICS['my_img'];
		$my_epic = $MY_PICS['my_eimg'];
		?>
                    <span class="profile_img">
                        <?php if($my_pic){ ?>
                        <img src="<?php echo $my_pic; ?>" onerror="this.onerror=null;this.src='<?php echo $my_epic; ?>';">
                        <?php }else{ ?>
                        <img src="<?php echo G5_URL.'/img/photo.png';?>" >
                        <?php } ?>
    		</span>
                    <p class="name ofH">
                        <strong><?php echo $conn_sub['NAME']?></strong>
                        <span class="date"><?php echo $conn_sub['SM_WDATE']?></span>
                    </p>
                    <div class="star_cmt">
                        <div class="star_rate">
                            <?php
                            $s = ceil($conn_sub[SM_STAR]);
        
							//echo $s;
                            for($f=5; $f >= 1; $f--){
                            if($f == $s)
								echo '<span class="star_ico on"><span class="ico_star_full"></span><span class="ico_star_half"></span></span>';
								//echo '<span class="star_ico on half"><span class="ico_star_full"></span><span class="ico_star_half"></span></span>';
                            else
								echo '<span class="star_ico"><span class="ico_star_full"></span><span class="ico_star_half"></span></span>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="nx_ct"><p><?php echo $conn_sub[SM_CONT]?></p></div>
                    <?php if( $conn_sub['DEL_BO']){ ?>
					<?php if(GSK_I_CODE != "GSK_I_CODE" && GSK_I_CODE != ""){ ?>
                    <div class="info">
                        <a onclick="SetTalkDel('nx_talk_delete.php', '<?php echo $conn_sub['SM_IDX']; ?>', 'sugang');" class="del fR">삭제</a>
                    </div>
                    <?php } ?>
					<?php } ?>
                </div>
            </div>
            <?php }}    ?>
        </div>


       
    </div>
	 <div style="height:30px"></div>
</div>

    <script>
	$(document).ready(function(){
		if($.urlParam('exefn') == "fnTabshow")fnTabshow();
	});


function fnBoxDisplay(no, cssnm)
{
	var objid = "#"+cssnm+no;
	var css_nm = "."+cssnm;

	$(css_nm).each(function(){
		$(css_nm).css("display", "none");
	});

	$(objid).css("display", "block");
}

function SetTabMenu(no)
{
	var objnm = "#tbaM";
	for(var i=1; i<=3; i++)
	{
		$(objnm+i).removeClass("on");
	}

		$(objnm+no).addClass("on");
}

function SetIFrame(url, obj, t, pkey)
{
	url = url +"?SG_CODE=<?php echo $_REQUEST[SG_CODE]; ?>";
	
	switch(t)
	{
		case "talk":
			url = url +"&SC_CONT="+ $(obj).val()+"&t="+t;
		break;

		case "talkReRe":
			url = url +"&SC_CONT="+ $(obj).val()+"&t="+t+"&SC_PARENT_IDX="+pkey;
		break;

		case "sugang":
			var st = $("input:radio[name=rdo_star]:checked").val();
			if(!st)st = "0";
			url = url +"&SM_CONT="+ $(obj).val()+"&SM_STAR="+st+"&t="+t;
		break;
	}

	$('#iframe1').attr('src', url);
}

function SetTalkDel(url, vkey, t)
{
	url = url +"?SG_CODE=<?php echo $_REQUEST[SG_CODE]; ?>";

	switch(t)
	{
		case "talk":
			url = url +"&SC_IDX="+vkey+"&t="+t;
		break;

		case "sugang":
			url = url +"&SM_IDX="+vkey+"&t="+t;
		break;
	}

	$('#iframe1').attr('src', url);
}

function fnTabshow()
{
	$('#tab  a[href="#tbaM2"]').tab('show');
}

function winPopup(url)
{
	window.open(url, "_blank", 'width=500,height=600');
}

function winLearn(url, viewurl,  txt, imgsrc)
{
	//수정
	txt = $(".learn_tit").text();
	
	if(!imgsrc)
	{
		if($('#imgMain'))
			imgsrc = $('#imgMain').attr('src');
	}

	var dataArray = new Array();
	var learnobj = new Object();

	learnobj.title = txt;
	learnobj.img = imgsrc;
	learnobj.link = viewurl;

	dataArray.push(learnobj);

	if($.cookie("learn"))
	{
		var obj = $.cookie('learn');
		var json_obj = $.parseJSON(obj);

		for (var i in json_obj) 
		{
			if(json_obj[i].title != txt && json_obj[i].title != "" && json_obj[i].img != "" && json_obj[i].link != "")
			{
				var json_obj_item = new Object();
				json_obj_item.title = json_obj[i].title;
				json_obj_item.img = json_obj[i].img;
				json_obj_item.link = json_obj[i].link;

				dataArray.push(json_obj_item);
			}
		}
	}


	$.cookie('learn', JSON.stringify(dataArray), { expires : 1, path : '/' });

	window.location.href = url + "&URL=<?php echo(urlencode($url))?>";
}



function fnLearnButton(no)
{
	$('#divModalTitle').html($('#divlearnbtn_title'+no).text());
	$('#divContentModal').html($('#divlearnbtn_count'+no).text());
	$('#imgModal').html($('#divlearnbtn_main_img'+no).text());
	$('#imgModal').attr('onerror', "this.onerror=null;this.src='"+ $('#divlearnbtn_main_eimg'+no).text() +"';");
	//$('#divModalTitle').html($('#divlearnbtn_link'+no).text());
	$('#divMsgModal').html($('#divlearnbtn_msg'+no).text());

	$('#viewModal').modal('show');
}


    </script>
<iframe id="iframe1" frameborder="0" style="width:100%; height:0px;"></iframe>


<div id="viewModal" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="divModalTitle"></h4>
        </div>
        <div class="modal-body">
          <div class="container-fluid">

            <div class="row">
				<div class="col-md-12">
					<img id="imgModal" style="width:100%;">
				</div>
            </div>
            <div class="row">
              <div class="col-md-12" id="divContentModal"></div>
            </div>
            <div class="row">
              <div class="col-md-12" id="divMsgModal"></div>
            </div>

            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


<?php
    # 메뉴 표시에 사용할 변수 
    $_gr_id = 'learning';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
    $pid = ($pid) ? $pid : '';   // Page ID

    include "../page/inc.page.menu.php";

    include_once('../bbs/_tail.php');
?>
