<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$sql = " select sum(IF(mb_id<>'',1,0)) as mb_cnt, count(*) as total_cnt from ".$g5['login_table']." where mb_id <> '".$config[cf_admin]."' ";
$row = sql_fetch($sql);
$g_count = $row['total_cnt']-$row['mb_cnt'];
$m_count = $row['total_cnt']-$g_count;
if ($row['total_cnt']>$config['cf_8']) {
	$max=$row['total_cnt'];
	sql_query(" update ".$g5['config_table']." set cf_8='".$max."' ");
}
$temp = sql_fetch("select vs_count from ".$g5['visit_sum_table']." where vs_date = '".G5_TIME_YMD."'");
$today_visit = intval($temp['vs_count']);
$temp1 = sql_fetch("select vs_count from `".$g5['visit_sum_table']."` where vs_date = DATE_SUB('".G5_TIME_YMD."', INTERVAL 1 DAY)");
$yester_visit = intval($temp1['vs_count']);
$sql = " select max(vs_count) as cnt from ".$g5['visit_sum_table'];
$row = sql_fetch($sql);
$vi_max = $row['cnt'];
$sql = " select sum(vs_count) as cnt from ".$g5['visit_sum_table'];
$row = sql_fetch($sql);
$visit_total = $row['cnt'];
   
// 금월
$sql = " select sum(vs_count) as cnt from ".$g5['visit_sum_table']." where vs_date between '".date("Y-m-01",time())."' and '".date("Y-m-d",time())."' ";
$row = sql_fetch($sql);
$visit_cnt['month'] = $row['cnt'];
$visit_href_thismonth = G5_ADMIN_URL."/visit_date.php?fr_date=".date("Y-m-01",time())."&to_date=".date("Y-m-d",time());

// 전체 게시물수
$sql = " select sum(bo_count_write) as total from ".$g5['board_table'].""; 
$row = sql_fetch($sql);
$total_write  = $row['total'];

// 전체 코멘트수
$sql = " select sum(bo_count_comment) as total from ".$g5['board_table'].""; 
$row = sql_fetch($sql);
$total_comment  = $row['total'];

// 그누보드 전체 디비용량 구하기
$result = sql_query("show table status from ".G5_MYSQL_DB." like 'g5%'");
$db_size = 0;
while($dbData=sql_fetch_array($result)){
$db_size += $dbData['Data_length']+$dbData['Index_length'];
}

// 계정 용량 구하기
$du = `du -csk`;
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#e5e5e5">
    <tr>
        <td style="background:#f1f1f1; line-height:30px;"><a href='<?php echo G5_BBS_URL;?>/current_connect.php' onfocus='this.blur()'><b>- 접속</b> (<b>G</b>: <?php echo $g_count;?> 명 <b>M</b>: <?php echo $m_count;?> 명)</a></td>
    </tr>
    <tr>
    	<td style="border-top:1px solid #e5e5e5; background:#FFF; padding:5px 10px; line-height:20px;">
            총방문객 : <?php echo number_format($visit_total);?> 명<br>
            최대방문 : <?php echo number_format($vi_max);?> 명<br>
            오늘방문 : <?php echo number_format($today_visit);?> 명<br>
            어제방문 : <?php echo number_format($yester_visit);?> 명<br>
            이달방문 : <?php echo number_format($visit_cnt[month]);?> 명<br><br>
            
            총 게시물 : <?php echo number_format($total_write);?> 개<br>
            총 코멘트 : <?php echo number_format($total_comment);?> 개<br>
            DB 사용량 : <?php printf("%0.2f MB",$db_size / (1024*1024)); ?><br>
            계정 사용량 : <?php printf("%0.2f MB",$du / 1024); ?>
        </td>
    </tr>
</table>