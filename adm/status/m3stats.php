<?php
$sub_menu = "990100";
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

// 출력 시작

$DracoCounter_URL  = G5_ADMIN_URL  ."/status";  // 활성도통계 설치폴더
$DracoCounter_PATH = G5_ADMIN_PATH ."/status";  // 활성도통계 절대경로

$g5[title] = "사이트 활성도 통계";
include_once('../admin.head.php');

$day=30; //기간 - 기본검색설정된 기간입니다. 필요에따라 수정해서 사용가능합니다.

if (empty($fr_date)) $fr_date = date("Y-m-d", G5_SERVER_TIME-86400*$day);
if (empty($to_date)) $to_date = G5_TIME_YMD;

// m3stats 설정
$limit=(strtotime($to_date)- strtotime($fr_date)) / 86400;
$bar_width = 100; // 그래프 최대 너비 (기본 60)

$pluginDracoCounter = $DracoCounter_PATH.'/gDracoCounter.php';
include_once $pluginDracoCounter;

$pluginDracoData = ShowDracoCounter(30, 33); // 날짜, 날짜의 가로폭 : 총 가로폭은 날짜 * 날짜가로폭
?>

<style type="text/css">
#m3stats_tbl {border-collapse:collapse;}
#m3stats_tbl td {border:solid 1px #AAA;}
#m3stats_tbl_title {text-align:center;}
.m3stats_align_a {text-align:center; width:100px; height:25px;}
.m3stats_align_c {text-align:center; width:50px;}
</style>

<?php //echo subtitle($g5['title']); ?>

<script type="text/javascript">
function fcount_submit(ymd, gr_id, bo_table) 
{
    var f = document.fcount;
    f.ymd.value = ymd;
    f.gr_id.value = gr_id;
    f.bo_table.value = bo_table;
    f.action = "<?php echo $PHP_SELF; ?>";
    f.submit();
}
</script>


<?php

 // 전체 회원수
$sql = " select count(mb_id) as cnt from ".$g5['member_table'].""; 
$row = sql_fetch($sql);
$total_member  = $row[cnt];


// 남/여 성비

// 남자 성비
$sql = " select count(mb_sex) as sex from ".$g5['member_table']." where `mb_sex` = 'M'"; 
$row = sql_fetch($sql);
$man_num  = $row[sex];
// 여자 성비
$sql = " select count(mb_sex) as sex from ".$g5['member_table']." where `mb_sex` = 'F'"; 
$row = sql_fetch($sql);
$woman_num  = $row[sex];

// 남/여 성비 % 계산
$total_num = $man_num+$woman_num;
$man_per = @sprintf("%.2f",(($man_num / $total_num)*100));
$woman_per = @sprintf("%.2f",(($woman_num / $total_num)*100));

// 연령분포

$old1 = date("Ymd",strtotime("-9 year", time()));
$old2 = date("Ymd",strtotime("-19 year", time()));
$old3 = date("Ymd",strtotime("-29 year", time()));
$old4 = date("Ymd",strtotime("-39 year", time()));
$old5 = date("Ymd",strtotime("-49 year", time()));

// 0~9세
$sql = " select count(mb_birth) as old from ".$g5['member_table']." where `mb_birth` > '$old1'"; 
$row = sql_fetch($sql);
$year0  = $row[old];

// 10~19세
$sql = " select count(mb_birth) as old from ".$g5['member_table']." where `mb_birth`  > '$old2' and `mb_birth`  <= '$old1'"; 
$row = sql_fetch($sql);
$year1  = $row[old];

// 20~29세
$sql = " select count(mb_birth) as old from ".$g5['member_table']." where `mb_birth`  > '$old3' and `mb_birth`  <= '$old2'"; 
$row = sql_fetch($sql);
$year2  = $row[old];

// 30~39세
$sql = " select count(mb_birth) as old from ".$g5['member_table']." where `mb_birth`  > '$old4' and `mb_birth`  <= '$old3'"; 
$row = sql_fetch($sql);
$year3  = $row[old];

// 40~49세
$sql = " select count(mb_birth) as old from ".$g5['member_table']." where `mb_birth`  > '$old5' and `mb_birth`  <= '$old4'"; 
$row = sql_fetch($sql);
$year4  = $row[old];

// 50세 이상
$sql = " select count(mb_birth) as old from ".$g5['member_table']." where `mb_birth`  <= '$old5'"; 
$row = sql_fetch($sql);
$year5  = $row[old];

// 연령분포 % 계산
$year0_per = @sprintf("%.2f",(($year0 / $total_num)*100));
$year1_per = @sprintf("%.2f",(($year1 / $total_num)*100));
$year2_per = @sprintf("%.2f",(($year2 / $total_num)*100));
$year3_per = @sprintf("%.2f",(($year3 / $total_num)*100));
$year4_per = @sprintf("%.2f",(($year4 / $total_num)*100));
$year5_per = @sprintf("%.2f",(($year5 / $total_num)*100));


/*  거주지 분포  */

// 서울거주
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%서울%'"; 
$row = sql_fetch($sql);
$seoul  = $row[addr];

// 부산거주
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%부산%'"; 
$row = sql_fetch($sql);
$busan  = $row[addr];

// 대구거주
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%대구%'";
$row = sql_fetch($sql);
$daegu  = $row[addr];

// 인천거주
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%인천%'";
$row = sql_fetch($sql);
$incheon  = $row[addr];

// 광주
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%광주%'";
$row = sql_fetch($sql);
$gwangju  = $row[addr];

// 대전
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%대전%'";
$row = sql_fetch($sql);
$daejeon  = $row[addr];

// 울산
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%울산%'";
$row = sql_fetch($sql);
$ulsan  = $row[addr];

// 강원
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%강원%'";
$row = sql_fetch($sql);
$gangwon  = $row[addr];

// 경기
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%경기%'";
$row = sql_fetch($sql);
$gyeonggi  = $row[addr];

// 경남
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%경남%'";
$row = sql_fetch($sql);
$gyeongnam  = $row[addr];

// 경북
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%경북%'";
$row = sql_fetch($sql);
$gyeongbuk  = $row[addr];

// 전남
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%전남%'";
$row = sql_fetch($sql);
$jeonnam = $row[addr];

// 전북
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%전북%'";
$row = sql_fetch($sql);
$jeonbuk  = $row[addr];

// 제주
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%제주%'";
$row = sql_fetch($sql);
$jeju  = $row[addr];

// 충남
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%충남%'";
$row = sql_fetch($sql);
$chungnam  = $row[addr];

// 충북
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%충북%'";
$row = sql_fetch($sql);
$chungbuk  = $row[addr];

// 해외
$sql = " select count(mb_addr1) as addr from ".$g5['member_table']." where `mb_addr1` LIKE '%해외%'";
$row = sql_fetch($sql);
$oversea  = $row[addr];

// 지역분포 % 계산
$seoul_per = @sprintf("%.2f",(($seoul / $total_num)*100));
$busan_per = @sprintf("%.2f",(($busan / $total_num)*100));
$daegu_per = @sprintf("%.2f",(($daegu / $total_num)*100));
$incheon_per = @sprintf("%.2f",(($incheon / $total_num)*100));
$gwangju_per = @sprintf("%.2f",(($gwangju / $total_num)*100));
$daejeon_per = @sprintf("%.2f",(($daejeon / $total_num)*100));
$ulsan_per = @sprintf("%.2f",(($ulsan / $total_num)*100));
$gangwon_per = @sprintf("%.2f",(($gangwon / $total_num)*100));
$gyeonggi_per = @sprintf("%.2f",(($gyeonggi / $total_num)*100));
$gyeongnam_per = @sprintf("%.2f",(($gyeongnam / $total_num)*100));
$gyeongbuk_per = @sprintf("%.2f",(($gyeongbuk / $total_num)*100));
$jeonnam_per = @sprintf("%.2f",(($jeonnam / $total_num)*100));
$jeonbuk_per = @sprintf("%.2f",(($jeonbuk / $total_num)*100));
$jeju_per = @sprintf("%.2f",(($jeju / $total_num)*100));
$chungnam_per = @sprintf("%.2f",(($chungnam / $total_num)*100));
$chungbuk_per = @sprintf("%.2f",(($chungbuk / $total_num)*100));
$oversea_per = @sprintf("%.2f",(($oversea / $total_num)*100));
?>


<form name="fcount" method="get" style="padding:0;">
    <input type="hidden" name="ymd">
    <input type="hidden" name="gr_id" value="<?php echo $gr_id; ?>">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table; ?>">
    
    <div style="padding:10px 0 15px 30px;"><strong>&lt;최근 <?php echo $day; ?>일 기준 방문자 그래프&gt;</strong></div>
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <?php echo $fr_date; ?> ~ <?php echo $to_date; ?> &nbsp;<br>
    <div style="width:1000px; padding:0 0 20px 0;"><?php echo $pluginDracoData; ?></div>
</form>


<div style="float:left; width:200px; height:280px; padding-left:20px;""><?php include $DracoCounter_PATH.'/today_status.php'; ?></div>
<div style="float:left; width:375px; height:280px; padding:0 15px 0 15px;"><?php include $DracoCounter_PATH.'/visit_status.php'; ?></div>
<div style="float:left; width:375px; height:280px;"><?php include $DracoCounter_PATH.'/member_status.php'; ?></div>


<div style="float:both; padding:25px 0 0 20px;"></div>

<div style="width:980px; padding-left:20px;">
	<img src="./img/bul2.gif" align="absmiddle"> <b>총 회원 수 : <?php echo number_format($total_member); ?> 명</b> &nbsp; (오늘가입: <?php echo $member_cnt[0]; ?> 명 ,&nbsp; 이달가입 <?php echo number_format($member_cnt[month]); ?> 명)<br>

	<?php    
    $sql = " select count(*) as cnt from ".$g5['member_table']." where  mb_id <> '$config[cf_admin]' ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];
    
    $sql2 = "select count(*) as cnt from ".$g5['member_table']." where mb_sex = 'F' and mb_id <> '$config[cf_admin]'"; 
    $row2 = sql_fetch($sql2);
    $count_f = $row2['cnt'];
    
    $count_m=$total_count-$count_f;
    
    $sql3 = " select count(*) as cnt from ".$g5['member_table']." where mb_level between 3 and 11 "; 
    $row3 = sql_fetch($sql3); 
    $count_3 = $row3['cnt'];
    
    $sql4 = " select count(*) as cnt from ".$g5['member_table']." where mb_level = '2'"; 
    $row4 = sql_fetch($sql4); 
    $count_2 = $row4['cnt'];
    
    $sql5 = " select count(*) as cnt from ".$g5['member_table']." where mb_level between 13 and 14 "; 
    $row5 = sql_fetch($sql5); 
    $count_s = $row5['cnt'];
    $count_t = $count_3 + $count_s;
    
    //if ($member[mb_level]=="15") {
      echo " &nbsp;&nbsp; <font color='#666666'> 등급별 회원수 &nbsp;&nbsp;";
      for( $i = 2 ; $i <= 14 ; $i++){ 
        $sql = "select count(*) as mb_num from ".$g5['member_table']." where mb_level = '$i'"; 
        $result = sql_query($sql); 
        $row = sql_fetch_array($result); 
      if($row[mb_num]!=0) 
    
      echo " 레벨[$i] : $row[mb_num] 명 &nbsp;</font> "; }
    //}
    ?>
</div>

<div style="float:both; padding:25px 0 0 0;"></div>

<div style="float:left; width:230px; line-height:20px; padding-left:20px;">
		<img src="./img/bul2.gif" align="absmiddle"> <b>연령별 분포</b><br>
		&nbsp;&nbsp; - 만 0 ~ 9세 : <?php echo number_format($year0);?> 명 (<?php echo $year0_per; ?>%)<br>
		&nbsp;&nbsp; - 만 10 ~ 19세 : <?php echo number_format($year1);?> 명 (<?php echo $year1_per; ?>%)<br>
		&nbsp;&nbsp; - 만 20 ~ 29세 : <?php echo number_format($year2);?> 명 (<?php echo $year2_per; ?>%)<br>
		&nbsp;&nbsp; - 만 30 ~ 39세 : <?php echo number_format($year3);?> 명 (<?php echo $year3_per; ?>%)<br>
		&nbsp;&nbsp; - 만 40 ~ 49세 : <?php echo number_format($year4);?> 명 (<?php echo $year4_per; ?>%)<br>
		&nbsp;&nbsp; - 만 50세 이상 : <?php echo number_format($year5);?> 명 (<?php echo $year5_per; ?>%)<br><br>
</div>

<div style="width:200px; float:left; line-height:20px;">
      <img src="./img/bul2.gif" align="absmiddle"> <b>남/여성별 분포</b><br>
      &nbsp;&nbsp; - 남자 : <?php echo number_format($man_num);?> 명 (<?php echo $man_per; ?>%)<br>
      &nbsp;&nbsp; - 여자 : <?php echo number_format($woman_num);?> 명 (<?php echo $woman_per; ?>%)<br><br>
</div>

<div style="float:left; width:490px; line-height:20px;">
	<img src="./img/bul2.gif" align="absmiddle"> <b>지역별 분포</b><br>
    <div>
        <div style="float:left; width:163px; line-height:22px;">			
            - 서울 : <?php echo number_format($seoul);?> 명 (<?php echo $seoul_per; ?>%)<br>
            - 경기 : <?php echo number_format($gyeonggi);?> 명 (<?php echo $gyeonggi_per; ?>%)<br>
            - 인천 : <?php echo number_format($incheon);?> 명 (<?php echo $incheon_per; ?>%)<br>
            - 강원 : <?php echo number_format($gangwon);?> 명 (<?php echo $gangwon_per; ?>%)<br>
            - 충북 : <?php echo number_format($chungbuk);?> 명 (<?php echo $chungbuk_per; ?>%)<br>
            - 충남 : <?php echo number_format($chungnam);?> 명 (<?php echo $chungnam_per; ?>%)<br>
        </div>
        <div style="float:left; width:163px; line-height:22px;">			
            - 대전 : <?php echo number_format($daejeon);?> 명 (<?php echo $daejeon_per; ?>%)<br>
            - 경북 : <?php echo number_format($gyeongbuk);?> 명 (<?php echo $gyeongbuk_per; ?>%)<br>
            - 경남 : <?php echo number_format($gyeongnam);?> 명 (<?php echo $gyeongnam_per; ?>%)<br>
            - 대구 : <?php echo number_format($daegu);?> 명 (<?php echo $daegu_per; ?>%)<br>
            - 울산 : <?php echo number_format($ulsan);?> 명 (<?php echo $ulsan_per; ?>%)<br>
            - 부산 : <?php echo number_format($busan);?> 명 (<?php echo $busan_per; ?>%)<br>
        </div>
        <div style="float:left; width:163px; line-height:22px;">			
            - 전북 : <?php echo number_format($jeonbuk);?> 명 (<?php echo $jeonbuk_per; ?>%)<br>
            - 전남 : <?php echo number_format($jeonnam);?> 명 (<?php echo $jeonnam_per; ?>%)<br>
            - 광주 : <?php echo number_format($gwangju);?> 명 (<?php echo $gwangju_per; ?>%)<br>
            - 제주 : <?php echo number_format($jeju);?> 명 (<?php echo $jeju_per; ?>%)<br>
            - 해외 : <?php echo number_format($oversea);?> 명 (<?php echo $oversea_per; ?>%)<br>
        </div>
	</div>
</div>

<div style="float:both; padding:25px 0 0 0;"></div>

<div id="div_visit" style="width:980px; padding-left:20px;">
    <table width="890">
        <tr>
            <td style="text-align:center; height:25px;">날짜</td>
            <td colspan="2" style="text-align:center;">전체방문</td>
            <td colspan="2" style="text-align:center;">직접방문</td>
            <td colspan="2" style="text-align:center;">가입</td>
            <td colspan="2" style="text-align:center;">로그인</td>
            <td colspan="2" style="text-align:center;">원글/댓글</td>
        </tr>
        <?php
        $day .= " (".get_yoil($print_date[i]).")";
        
        for($i=0; $i<$limit; $i++) {
            $date = date("Y-m-d", time()-$i*24*60*60);
            $print_date[$i] = substr($date,2);
            $date_1 = date("Y-m-d", time()-($i-1)*24*60*60);
            // 방문자 수
            $temp = sql_fetch("select vs_count from ".$g5['visit_sum_table']." where vs_date='$date'");
            $count_visit[$i] = intval($temp[vs_count]);
            if($max_count_visit<$count_visit[$i]) $max_count_visit = $count_visit[$i];
            // 직접 방문자 수 (referer가 없는 경우)
            $temp = sql_fetch("select count(*) as total from ".$g5['visit_table']." where vi_date='$date' AND vi_referer=''");
            $count_direct[$i] = $temp[total];
            if($max_count_direct<$count_direct[$i]) { $max_count_direct = $count_direct[$i];
            if($max_count_direct>$config[cf_3]){
            sql_query(" update ".$g5['config_table']." set cf_3='$max_count_direct' ");
            }
            }
            // 가입자 수 (mb_datetime으로 확인)
            $temp = sql_fetch("select count(*) as total from `".$g5['member_table']."` where mb_datetime LIKE '$date%'");
            $count_join[$i] = $temp[total];
            if($max_count_join<$count_join[$i]) $max_count_join = $count_join[$i];
            // 로그인 수 (로그인 포인트가 없으면 계산 안되므로 안 띄운다)
            if($config[cf_login_point]) {
                $temp = sql_fetch("select count(*) as total from ".$g5['point_table']." where po_rel_table='@login' AND po_datetime LIKE '$date%'");
                $count_login[$i] = $temp[total];
                if($max_count_login<$count_login[$i]) $max_count_login = $count_login[$i];
                if($max_count_login>$config[cf_2]){
                sql_query(" update ".$g5['config_table']." set cf_2='$max_count_login' ");
            }
            }
            // 원글 수
            $temp = sql_fetch("select count(*) as total from ".$g5['board_new_table']." where wr_id=wr_parent AND bn_datetime LIKE '$date%'");
            $count_article[$i] = $temp[total];
            if($max_count_article<$count_article[$i]) $max_count_article = $count_article[$i];
            // 댓글 수
            $temp = sql_fetch("select count(*) as total from ".$g5['board_new_table']." where wr_id!=wr_parent AND bn_datetime LIKE '$date%'");
            $count_comment[$i] = $temp[total];
            if($max_count_comment<$count_comment[$i]) $max_count_comment = $count_comment[$i];
        }
        for($i=0; $i<$limit; $i++) {
        ?>
        <tr>
            <td class="m3stats_align_a"><?php echo $print_date[$i];?> (<?php echo get_yoil($print_date[$i]); ?>)</td>
            <td class="m3stats_align_c"><?php echo $count_visit[$i]; ?></td>
            <td><img src="<?php echo $DracoCounter_URL; ?>/img/graph.gif" height="9" width="<?php echo ceil($count_visit[$i]/$max_count_visit*$bar_width); ?>" /></td>
            <td class="m3stats_align_c"><?php echo $count_direct[$i]; ?></td>
            <td><img src="<?php echo $DracoCounter_URL; ?>/img/graph.gif" height="9" width="<?php echo ceil($count_direct[$i]/$max_count_direct*$bar_width); ?>" /></td>
            <td class="m3stats_align_c"><?php echo $count_join[$i]; ?></td>
            <td><img src="<?php echo $DracoCounter_URL; ?>/img/graph.gif" height="9" width="<?php echo ceil($count_join[$i]/$max_count_join*$bar_width); ?>" /></td>
            <td class="m3stats_align_c"><?php echo $count_login[$i]; ?></td>
            <td><img src="<?php echo $DracoCounter_URL; ?>/img/graph.gif" height="9" width="<?php echo ceil($count_login[$i]/$max_count_login*$bar_width); ?>" /></td>
            <td class="m3stats_align_c"><?php echo $count_article[$i]; ?>/<?php echo $count_comment[$i]; ?></td>
            <td><img src="<?php echo $DracoCounter_URL; ?>/img/graph.gif" height="9" width="<?php echo ceil($count_article[$i]/$max_count_article*$bar_width); ?>" /></td>
        </tr>
        <?php 
        } 
        ?>
    </table>

</div>


<?php
// 날짜 설정
if(!$fr_date) $fr_date = date("Y-m-d", strtotime("0 days ago"));
if(!$to_date) $to_date = G5_TIME_YMD;

// 주사 지랄 방지
$fr_date = substr($fr_date, 0, 10);
$to_date = substr($to_date, 0, 10);
$site = substr($site, 0, 10);
$site_ori = $site;

// 검색사이트들
$site_arr = array("Google", "Nate", "Yahoo", "Daum", "Naver", "Bing");
$surl_arr = array("Google" => "http://www.google.%", "Nate" => "%nate.com%", "Yahoo" => "%search.yahoo.com%", "Daum" => "%search.daum.net%", "Naver" => "%search.naver.com%", "Bing" => "http://www.bing.com%");
$svar_arr = array("Google" => "q", "Nate" => "q", "Yahoo" => "p", "Daum" => "q", "Naver" => "query", "Bing" => "q");
?>
<style>
#m3tbl { border:solid 1px #CCC; border-collapse:collapse;width:1000px;}
#m3tbl th { border:solid 1px #CCC; text-align:center;}
#m3tbl td { border:solid 1px #CCC; text-align:center; padding:2px 8px;}
#div_m3sq ul { padding:0; margin:0; }
#div_m3sq ul li { width:100px; line-height:25px; border:solid 1px #CCC; float:left; margin:0 5px; text-align:center; vertical-align:middle; list-style: none; }
#div_m3sq a { display:block; text-decoration:none; }
</style>

<div id="div_m3sq" style="width:980px; padding:30px 0 0 20px;">
    <div>
        <img src="./img/bul2.gif" border=0 align=absmiddle> <b>외부 유입 검색어(키워드) 분석기</b><br><br>
        <ul>
            <li><a href="<?php echo $PHP_SELF;?>?to_date=<?php echo $to_date;?>&fr_date=<?php echo $fr_date;?>">All</a></li>
            <?php foreach($site_arr as $site) { ?>
            <li><a href="<?php echo $PHP_SELF;?>?site=<?php echo $site;?>&to_date=<?php echo $to_date;?>&fr_date=<?php echo $fr_date;?>"><?php echo $site;?></a></li>
            <?php } ?>
        </ul>
    </div>
    
    <div style="clear:both;"></div>
    
    <div style="margin:12px 0;">
        <div style="float:left;">
            <form method="get" action="<?php echo $_SERVER[PHP_SELF];?>">
                <input type="hidden" name="site" value="<?php echo $site_ori;?>" />
                시작 : <input class="frm_input bo_subject full_input" style="width:90px" type="text" name="fr_date" value="<?php echo $fr_date;?>" />
                끝 : <input class="frm_input bo_subject full_input" style="width:90px" type="text" name="to_date" value="<?php echo $to_date;?>" />
                <input type="submit" value="go" class="btn_confirm btn_submit" style="width:50px; height:25px;" />
            </form>
        </div>
        <div style="float:right;">
            <form action="javascript:;" onsubmit="findsq(getElementById('sq').value)" />
                결과내 검색 : <input class="frm_input bo_subject full_input" style="width:100px;" type="text" id="sq" name="sq" value="<?php echo $sq;?>" />
                <input type="submit" value=" 검색 " />
                <input type="button" value=" 리셋 " onclick="resetsq()" />
                <span id="search_cnt"></span>
            </form>
        </div>
        <div style="clear:both;"></div>
    </div>
    
    <?php
    // vi_referer에서 사이트 찾고, vi_date로 범위 정하기, 정렬은 vi_id 역순 (속도 개선 필요)
    if(in_array($site_ori, $site_arr)) {
        $where1 = "vi_referer LIKE '{$surl_arr[$site_ori]}' ";
    }
    else { // 5개 사이트 모두 포함
        $where1 = " ( ";
        foreach($surl_arr as $site => $surl) {
            $where1 .= " vi_referer LIKE '$surl' OR ";
        }
        $where1 .= " 0 )";
    }
    
    $query = sql_query("select * from ".$g5['visit_table']." where $where1 AND vi_date>='$fr_date' AND vi_date<='$to_date' order by vi_id desc");
    ?>
    <table id="m3tbl">
        <tr>
            <th style="width:150px; height:25px;">날짜</th>
            <th style="width:150px;">시간</th>
            <th>사이트</th>
            <th>검색어</th>
        </tr>
        <?php
        $cnt = 0;
        $cnt2 = array();
        while($row = sql_fetch_array($query)) {
            // 어느 사이트인지 찾기
            foreach($surl_arr as $site => $surl) {
                if(strstr($row[vi_referer], str_replace("%", "", $surl))) {
                    $engine = $site;
                    break;
                }
            }
            // 검색문자열 찾기
            $regex = "/(\?|&){$svar_arr[$engine]}\=([^&]*)/i";
            preg_match($regex, $row[vi_referer], $matches);
            $querystr = $matches[2];
            // 보통 검색어 사이를 +로 넘긴다
            $querystr = str_replace("+", " ", $querystr);
            // %ab 이런 식으로 된 걸 바꿔주기
            $querystr = urldecode($querystr);
            // 네이버는 unicode로 된 경우도 있어서
            if($engine=="Naver") $querystr = utf8_urldecode($querystr);
            // 캐릭터셋이 utf-8인 경우는 euc-kr 고치기 (utf-8 유저는 euc-KR과 utf-8을 서로 바꿔주면 될 듯)
            $charset = mb_detect_encoding($querystr, "ASCII, euc-KR, utf-8"); 
            if($charset=="euc-kr") $querystr = iconv("euc-kr", "utf-8", $querystr);
            //$charset = mb_detect_encoding($querystr, "ASCII, utf-8, euc-kr");
            //if($charset=="utf-8") $querystr = iconv("utf-8", "euc-kr", $querystr);
            // 자잘한 처리들
            $querystr = trim($querystr);
            $querystr = htmlspecialchars($querystr);
            // 가끔 빈 것들도 있다 -_-
            if(!strlen($querystr)) continue;
            ?>
        <tr>
            <td style="width:100px; height:25px; line-height:20px;"><?php echo $row[vi_date]?></td>
            <td style="width:100px;"><?php echo $row[vi_time]?></td>
            <td><a href="<?php echo $PHP_SELF?>?site=<?php echo $engine?>"><img src="<?php echo $DracoCounter_URL?>/img/<?php echo strtolower($engine)?>.jpg" /></a></td>
			<td style="text-align:left" id="m3sqtd[<?php echo $cnt?>]"><a href="<?php echo $row[vi_referer]?>" target="_blank"><?php echo $querystr?></a></td>
        </tr>
            <?php
            // 카운트용 변수
            $cnt++;
            $cnt2[$engine]++;
        }
        ksort($cnt2);
        
        // 베짱이님 제공 함수
        function utf8_urldecode($str, $chr_set='CP949') {
            $callback_function = create_function('$matches, $chr_set="'.$chr_set.'"', 'return iconv("UTF-16BE", $chr_set, pack("n*", hexdec($matches[1])));');
            return rawurldecode(preg_replace_callback('/%u([[:alnum:]]{4})/', $callback_function, $str));
        } 
        
        ?>
    </table>
    <br>
    
    Total : <?php echo $days=(strtotime($to_date)-strtotime($fr_date))/(24*60*60)+1;?> days, <?php echo $cnt;?> results (<?php echo sprintf("%.1f",$cnt/$days);?>/day)<br>
    <?php 
    if(!$site_ori) { // 모든 사이트의 경우 비율 분석
        foreach($cnt2 as $engine => $count) {
            echo "$engine : $count (".sprintf("%.1f",$count/$cnt*100)."%)<br>";
        }
    }
    ?>

</div>

<script type="text/javascript">
function findsq(sq) {
	if(sq=="") return;
	var i = 0;
	var search_cnt = 0; // 결과내 검색 개수
	while(a = document.getElementById("m3sqtd["+i+"]")) {
		if(a.innerText.toLowerCase().match(sq.toLowerCase())) { // 찾는 값이 있으면 보이기
			a.parentNode.style.display="";
			search_cnt++;
		} else { // 찾는 값이 없으면 숨기기
			a.parentNode.style.display="none";
		}
		i++;
	}
	document.getElementById("search_cnt").innerText = "결과내 검색 : " + search_cnt + "건";
}
function resetsq() {
	var i = 0;
	while(a = document.getElementById("m3sqtd["+i+"]")) {
		a.parentNode.style.display=""; // 모든 행의 display 속성 reset
		i++;
	}
	document.getElementById("search_cnt").innerText = "";
	document.getElementById("sq").value = "";
}
</script>

<?php
//마지막 인클루드
include_once G5_ADMIN_PATH."/admin.tail.php";
?>