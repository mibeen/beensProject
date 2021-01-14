<?php
if($member['mb_id'] == "" || GSK_I_CODE == "GSK_I_CODE" || GSK_I_CODE == "" || GSK_I_CODE == null)
{
	$msg = "로그인 해주세요.";

}else{


// 내가 쓴 톡톡 학습리스트
$response_json_obj = GetMyTalkTalk();
?>

<ul id="accordion" class="panel-group my_talk" role="tablist" aria-multiselectable="true">
	<?php 
		for($c = 0; $c < count($response_json_obj['result']); $c++) {
		$conn_grp = $response_json_obj['result'][$c];
	?>
	<li class="panel">
		<div id="heading<?php echo $c; ?>" role="tab" class="panel-heading top" >
			<p class="nx_tit" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $c; ?>" aria-expanded="false" aria-controls="collapse<?php echo $c; ?>"><?php echo $conn_grp['SG_NAME']?></p>
			<a class="toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $c; ?>" aria-expanded="false" aria-controls="collapse<?php echo $c; ?>">
				내용보기
				<span class="ico_arr1_u"></span><span class="ico_arr1_d"></span>
			</a>
		</div>

		<div id="collapse<?php echo $c; ?>" class="cmt_outer panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $c; ?>">

			<?php
			// 해당학습에서 내가 쓴 댓글 목록
			$response_coun_json_obj = GetListItemView_MyRe_List2($conn_grp['SG_CODE']);

			for($i = 0; $i < count($response_coun_json_obj['result']); $i++) {
            $conn = $response_coun_json_obj['result'][$i]['itm'];

            for($j = 0; $j < count($conn); $j++) {
            $conn_sub = $conn[$j];

            ?>

            <div class="cmt_wrap">
                <div class="cmt">
                    <span class="profile_img">
					<?php if($conn_sub['DEL_BO']){  ?>
					
						<?php
						$response_pic_json_obj = GetMyProfile_Photo2($conn_sub["I_CODE"]);
						$MY_PICS = $response_pic_json_obj[result][0];
						$my_pic = $MY_PICS['my_img'];
						$my_epic = $MY_PICS['my_eimg'];
						?>
						 <img src="<?php echo $my_pic; ?>" onerror="this.onerror=null;this.src='<?php echo $my_epic; ?>';">
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
                        <a onclick="SetTalkDel('nx_talk_delete.php', '<?php echo $conn_sub['SC_IDX']; ?>', 'talk', '<?php echo($conn_sub['SG_CODE'])?>');" class="fR lh30" style="font-size:12px;color:red">삭제</a>
                        <?php } ?>
						<?php } ?>
                    </div>

                    <!--리플//-->
                    <div class="reply_outer" id="reply_outer<?php echo $j; ?>" >
					
                        <?php if($conn_sub['REPLY_CNT'] > 0){  ?>
                        <!--리플시작//-->
                        <div class="reply_lst">
                            <?php
			    // 대댓글
                            $response_json_obj_Re = GetListItemView_RRe_List2($conn_sub['SC_IDX'], $conn_sub['SG_CODE']);


                            for($g = 0; $g < count($response_json_obj_Re['result']); $g++) {
                            $conn_Re = $response_json_obj_Re['result'][$g]['itm'];

                            for($h = 0; $h < count($conn_Re); $h++) {
                            $conn_sub_Re = $conn_Re[$h];
                            ?>
                            <div class="reply">
                                <span class="profile_img">
													<?php
													$response_pic_json_obj_Re = GetMyProfile_Photo2($conn_sub_Re["I_CODE"]);
													$MY_PICS = $response_pic_json_obj_Re['result'][0];
													$my_pic = $MY_PICS['my_img'];
													$my_epic = $MY_PICS['my_eimg'];
													?>
													 <img src="<?php echo $my_pic; ?>" onerror="this.onerror=null;this.src='<?php echo $my_epic; ?>';">
												</span>
                                <p class="name ofH">
                                    <strong><?php echo $conn_sub_Re['NAME'] ?></strong>
                                    <span class="date"><?php echo $conn_sub_Re['SC_WDATE']?></span>
                                </p>
                                <p class="nx_ct"><?php echo $conn_sub_Re['SC_CONT']?></p>
                                <div class="info">
                                    <?php if($conn_sub_Re['DEL_BO']){  ?>
									<?php if(GSK_I_CODE != "GSK_I_CODE" && GSK_I_CODE != ""){ ?>
                                    <a onclick="SetTalkDel('nx_talk_delete.php', '<?php echo $conn_sub_Re['SC_IDX']; ?>', 'talk', '<?php echo($conn_sub['SG_CODE'])?>');" class="del fR">삭제</a>
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
								<a onclick="SetIFrame('nx_talk_reply.php', '#txtReRe<?php echo $conn_sub['SC_IDX']; ?>', 'talkReRe', '<?php echo $conn_sub['SC_IDX']; ?>', '<?php echo($conn_sub['SG_CODE'])?>');" class="nx_btn5">등록</a>
								<?php }else{ ?>
								<a onclick="win_open();"><h5><b>로그인 후 이용하실 수 있습니다.</b></h5></a>
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
	</li>
	<?php } ?>
	<?php  /*
	<li class="panel">
		<div id="heading1" role="tab" class="panel-heading top" >
			<p class="nx_tit" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="false" aria-controls="collapse1">[백점집밥] 외식보다 맛있고 실속있는 류선생의 집밥</p>
			<a href="javascript:void(0);" class="toggle">
				내용보기
				<span class="ico_arr1_u"></span><span class="ico_arr1_d"></span>
			</a>
		</div>
		<div id="collapse1" class="cmt_outer panel-collapse collapse" role="tabpanel" aria-labelledby="heading1">
			<div class="cmt_wrap">
		222222222222222222222
			</div>
		</div>
	</li>
	*/ ?>
</ul>

<?php
/*
<ul id="accordion" class="panel-group my_talk" role="tablist" aria-multiselectable="true">
	<li class="panel">
		<div id="heading0" role="tab" class="panel-heading top" >
			<p class="nx_tit" data-toggle="collapse" data-parent="#accordion" href="#collapse0" aria-expanded="false" aria-controls="collapse0">[백점집밥] 외식보다 맛있고 실속있는 류선생의 집밥</p>
			<a href="javascript:void(0);" class="toggle on">
				내용보기
				<span class="ico_arr1_u"></span><span class="ico_arr1_d"></span>
			</a>
		</div>
		<div id="collapse0" class="cmt_outer panel-collapse collapse" role="tabpanel" aria-labelledby="heading0">
			<div class="cmt_wrap">
				<div class="cmt">
					<span class="profile_img"><img src="" alt=""></span>
					<p class="name ofH">
						<strong>홍길동</strong>
						<span class="date">2017-00-00</span>
					</p>
					<div class="nx_ct"><p>내용이 들어갑니다. 내용이 들어갑니다. 내용이 들어갑니다. 내용이 들어갑니다. 내용이 들어갑니다.</p></div>
				</div>
				<div class="reply_area">
					<div class="reply_top">
						<a href="javascript:void(0);" class="nx_btn_toggle on">
							<span class="cnt">답글 <span>0</span></span>&nbsp;
							<span class="ico_arr1_u"></span><span class="ico_arr1_d"></span>
						</a>
						<a href="javascript:void(0);" class="fR lh30" style="font-size:12px;color:red">삭제</a>
					</div>
					<div class="reply_outer">
						<div class="reply_lst">
							<div class="reply">
								<span class="profile_img"><img src="" alt=""></span>
								<p class="name ofH">
									<strong>홍길동</strong>
									<span class="date">2017-00-00</span>
								</p>
								<p class="nx_ct">답글 내용이 들어갑니다.</p>
								<div class="info">
									<a href="javascript:void(0);" class="del fR">삭제</a>
								</div>
							</div>
							<div class="reply">
								<span class="profile_img"><img src="" alt=""></span>
								<p class="name ofH">
									<strong>홍길동</strong>
									<span class="date">2017-00-00</span>
								</p>
								<p class="nx_ct">답글 내용이 들어갑니다.</p>
							</div>
						</div>
	
						<div class="regist_reply">
							<textarea id="" name="" class="nx_ips1" style="min-height:80px;"></textarea>
							<div class="taR mt10">
								<a href="javascript:void(0);" class="nx_btn_cmt_add">등록</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</li>
	<li class="panel">
		<div id="heading2" role="tab" class="panel-heading top" >
			<p class="nx_tit" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" aria-controls="collapse2">[백점집밥] 외식보다 맛있고 실속있는 류선생의 집밥</p>
			<a href="javascript:void(0);" class="toggle">
				내용보기
				<span class="ico_arr1_u"></span><span class="ico_arr1_d"></span>
			</a>
		</div>
		<div id="collapse2" class="cmt_outer panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
			<div class="cmt_wrap">
			
				<div class="cmt">
					<span class="profile_img"><img src="" alt=""></span>
					<p class="name ofH">
						<strong>홍길동</strong>
						<span class="date">2017-00-00</span>
					</p>
					<div class="nx_ct"><p>내용이 들어갑니다. 내용이 들어갑니다. 내용이 들어갑니다. 내용이 들어갑니다. 내용이 들어갑니다.</p></div>
				</div>
				<div class="reply_area">
					<div class="reply_top">
						<a href="javascript:void(0);" class="nx_btn_toggle on">
							<span class="cnt">답글 <span>0</span></span>&nbsp;
							<span class="ico_arr1_u"></span><span class="ico_arr1_d"></span>
						</a>
						<a href="javascript:void(0);" class="fR lh30" style="font-size:12px;color:red">삭제</a>
					</div>
					<div class="reply_outer">
						<div class="reply_lst">
							<div class="reply">
								<span class="profile_img"><img src="" alt=""></span>
								<p class="name ofH">
									<strong>홍길동</strong>
									<span class="date">2017-00-00</span>
								</p>
								<p class="nx_ct">답글 내용이 들어갑니다.</p>
								<div class="info">
									<a href="javascript:void(0);" class="del fR">삭제</a>
								</div>
							</div>
							<div class="reply">
								<span class="profile_img"><img src="" alt=""></span>
								<p class="name ofH">
									<strong>홍길동</strong>
									<span class="date">2017-00-00</span>
								</p>
								<p class="nx_ct">답글 내용이 들어갑니다.</p>
							</div>
						</div>
	
						<div class="regist_reply">
							<textarea id="" name="" class="nx_ips1" style="min-height:80px;"></textarea>
							<div class="taR mt10">
								<a href="javascript:void(0);" class="nx_btn_cmt_add">등록</a>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</li>
	<li class="panel">
		<div id="heading3" role="tab" class="panel-heading top" >
			<p class="nx_tit" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="false" aria-controls="collapse3">[백점집밥] 외식보다 맛있고 실속있는 류선생의 집밥</p>
			<a href="javascript:void(0);" class="toggle">
				내용보기
				<span class="ico_arr1_u"></span><span class="ico_arr1_d"></span>
			</a>
		</div>
		<div id="collapse3" class="cmt_outer panel-collapse collapse" role="tabpanel" aria-labelledby="heading3">
			<div class="cmt_wrap">
			
				<div class="cmt">
					<span class="profile_img"><img src="" alt=""></span>
					<p class="name ofH">
						<strong>홍길동</strong>
						<span class="date">2017-00-00</span>
					</p>
					<div class="nx_ct"><p>내용이 들어갑니다. 내용이 들어갑니다. 내용이 들어갑니다. 내용이 들어갑니다. 내용이 들어갑니다.</p></div>
				</div>
				<div class="reply_area">
					<div class="reply_top">
						<a href="javascript:void(0);" class="nx_btn_toggle on">
							<span class="cnt">답글 <span>0</span></span>&nbsp;
							<span class="ico_arr1_u"></span><span class="ico_arr1_d"></span>
						</a>
						<a href="javascript:void(0);" class="fR lh30" style="font-size:12px;color:red">삭제</a>
					</div>
					<div class="reply_outer">
						<div class="reply_lst">
							<div class="reply">
								<span class="profile_img"><img src="" alt=""></span>
								<p class="name ofH">
									<strong>홍길동</strong>
									<span class="date">2017-00-00</span>
								</p>
								<p class="nx_ct">답글 내용이 들어갑니다.</p>
								<div class="info">
									<a href="javascript:void(0);" class="del fR">삭제</a>
								</div>
							</div>
							<div class="reply">
								<span class="profile_img"><img src="" alt=""></span>
								<p class="name ofH">
									<strong>홍길동</strong>
									<span class="date">2017-00-00</span>
								</p>
								<p class="nx_ct">답글 내용이 들어갑니다.</p>
							</div>
						</div>
	
						<div class="regist_reply">
							<textarea id="" name="" class="nx_ips1" style="min-height:80px;"></textarea>
							<div class="taR mt10">
								<a href="javascript:void(0);" class="nx_btn_cmt_add">등록</a>
							</div>
						</div>
					</div>
				</div>


			</div>
		</div>
	</li>
	<li class="panel">
		<div id="heading4" role="tab" class="panel-heading top" >
			<p class="nx_tit" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="false" aria-controls="collapse4">[백점집밥] 외식보다 맛있고 실속있는 류선생의 집밥</p>
			<a href="javascript:void(0);" class="toggle">
				내용보기
				<span class="ico_arr1_u"></span><span class="ico_arr1_d"></span>
			</a>
		</div>
		<div id="collapse4" class="cmt_outer panel-collapse collapse" role="tabpanel" aria-labelledby="heading4">
			<div class="cmt_wrap">


				<div class="cmt">
					<span class="profile_img"><img src="" alt=""></span>
					<p class="name ofH">
						<strong>홍길동</strong>
						<span class="date">2017-00-00</span>
					</p>
					<div class="nx_ct"><p>내용이 들어갑니다. 내용이 들어갑니다. 내용이 들어갑니다. 내용이 들어갑니다. 내용이 들어갑니다.</p></div>
				</div>
				<div class="reply_area">
					<div class="reply_top">
						<a href="javascript:void(0);" class="nx_btn_toggle on">
							<span class="cnt">답글 <span>0</span></span>&nbsp;
							<span class="ico_arr1_u"></span><span class="ico_arr1_d"></span>
						</a>
						<a href="javascript:void(0);" class="fR lh30" style="font-size:12px;color:red">삭제</a>
					</div>
					<div class="reply_outer">
						<div class="reply_lst">
							<div class="reply">
								<span class="profile_img"><img src="" alt=""></span>
								<p class="name ofH">
									<strong>홍길동</strong>
									<span class="date">2017-00-00</span>
								</p>
								<p class="nx_ct">답글 내용이 들어갑니다.</p>
								<div class="info">
									<a href="javascript:void(0);" class="del fR">삭제</a>
								</div>
							</div>
							<div class="reply">
								<span class="profile_img"><img src="" alt=""></span>
								<p class="name ofH">
									<strong>홍길동</strong>
									<span class="date">2017-00-00</span>
								</p>
								<p class="nx_ct">답글 내용이 들어갑니다.</p>
							</div>
						</div>
	
						<div class="regist_reply">
							<textarea id="" name="" class="nx_ips1" style="min-height:80px;"></textarea>
							<div class="taR mt10">
								<a href="javascript:void(0);" class="nx_btn_cmt_add">등록</a>
							</div>
						</div>
					</div>
				</div>


		
			</div>
		</div>
	</li>
</ul>
*/ ?>
<?php } ?>