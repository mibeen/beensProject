<?php
$sub_menu = "200800";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '페이지별 접속자집계 (상위 50개 까지 추출)';
include_once('./visit.sub.php');

$colspan = 5;


$totalsum = 0;
$sql  = " select sum(p.cnt) as total,p.page";
$sql .= " ,(select me_name from g5_menu as m where substring(p.page,1,LENGTH(m.me_link)) like m.me_link and LENGTH(p.page)>2 order by m.me_code desc limit 0,1 ) as name ";
$sql .= " from pagecnt p where p.date between '{$fr_date}' and '{$to_date}' ";
$sql .= " group by name ";
$sql .= " order by total desc ";
$sql .= " limit 0,50 ";
$result = sql_query($sql);

$RCD = array();
while ($row=sql_fetch_array($result)) {

    $totalsum += $row['total'];
	$RCD[] = $row;
}

?>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">순위</th>
        <th scope="col">메뉴</th>
		<th scope="col">주소</th>
        <th scope="col">그래프</th>
        <th scope="col">접속자수</th>
        <th scope="col">비율(%)</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <td colspan="4">합계</td>
        <td><strong><?php echo $sum_count ?></strong></td>
        <td>100%</td>
    </tr>
    </tfoot>
    <tbody>
	<?php
		$i = 0;
		$k = 0;
		$save_count = -1;
		$tot_count = 0;
	?>
	<?php foreach($RCD as $R) { 
			$bg = 'bg'.($i%2); 
			$rate = ($R['total'] / $totalsum * 100);
            $s_rate = number_format($rate, 1);
	?>
		<tr class="<?php echo $bg; ?>">
			<td class="td_num"><?php echo ++$i ?></td>
			<td class="">
				<?php 
					if($R['name']) {
						echo $R['name'];
					}else{
						echo "기타";
					}
				?>
			</td>
			<td class=""><?php echo $R['page'] ?></td>
			<td>
				<div class="visit_bar">
					<span style="width:<?php echo $s_rate ?>%"></span>
				</div>
			</td>
			<td class="td_numbig"><?php echo $R['total'] ?></td>
			<td class="td_num"><?php echo $s_rate ?></td>
		</tr>

	<?php } ?>
	<?php if(sql_num_rows($result)<1) {?>
        <tr><td colspan="<?=$colspan?>" class="empty_table">자료가 없습니다.</td></tr>
    <?php } ?>
    </tbody>
    </table>
</div>

<?php
include_once('./admin.tail.php');
?>
