<?php
// 최신댓글 추출 
function latestAnswer($skin_dir="", $bo_table, $rows=10, $subject_len=40, $options="") 
{ 
    global $g5; 

    if ($skin_dir) 
		$latest_skin_path = G5_SKIN_PATH.'/latest/'.$skin_dir;
       // $latest_skin_path = "$g4[path]/skin/latest/$skin_dir"; 
    else 
		$latest_skin_path = G5_SKIN_PATH.'/latest/'.$skin_dir;
        //$latest_skin_path = "$g4[path]/skin/latest/basic"; 

    $list = array(); 

$sql_common = " from $g5[board_new_table] a, $g5[board_table] b, $g5[group_table] c 
              where a.bo_table = b.bo_table and b.gr_id = c.gr_id and b.bo_use_search = '1' "; 
if ($gr_id) 
    $sql_common .= " and b.gr_id = '$gr_id' "; 
    
$sql_common .= " and a.wr_id <> a.wr_parent "; 

if ($mb_id) 
    $sql_common .= " and a.mb_id = '$mb_id' "; 
$sql_order = " order by a.bn_id desc "; 

if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지) 
$from_record = ($page - 1) * $rows; // 시작 열을 구함 

$sql = " select a.*, b.bo_subject, c.gr_subject, c.gr_id 
          $sql_common 
          $sql_order 
          limit $from_record, $rows "; 

    $result = sql_query($sql); 
for ($i=0; $row=sql_fetch_array($result); $i++) 
{ 
    $tmp_write_table = $g5[write_prefix] . $row[bo_table]; 

        $comment = ""; 
        $comment_link = ""; 

        $row2 = sql_fetch(" select * from $tmp_write_table where wr_id = '$row[wr_id]' "); 
        $list[$i] = $row2; 

        $name = get_sideview($row2[mb_id], cut_str($row2[wr_name], $config[cf_cut_name]), $row2[wr_email], $row2[wr_homepage]); 
        // 당일인 경우 시간으로 표시함 
        $datetime = substr($row2[wr_datetime],0,10); 
        $datetime2 = $row2[wr_datetime]; 
        if ($datetime == $g4[time_ymd]) 
            $datetime2 = substr($datetime2,11,5); 
        else 
            $datetime2 = substr($datetime2,5,5); 



    $list[$i][gr_id] = $row[gr_id]; 
    $list[$i][bo_table] = $row[bo_table]; 
    $list[$i][name] = $name; 
    $list[$i][comment] = $comment; 
    $list[$i][href] = "$G5_PATH/bbs/board.php?bo_table=$row[bo_table]&wr_id=$row2[wr_id]{$comment_link}";
    $list[$i][datetime] = $datetime; 
    $list[$i][datetime2] = $datetime2; 

    $list[$i][gr_subject] = $row[gr_subject]; 
    $list[$i][bo_subject] = $row[bo_subject]; 
    $list[$i][subject] = conv_subject($row2[wr_content], $subject_len, "…"); 
} 

    
    ob_start(); 
    include "$latest_skin_path/latest.skin.php"; 
    $content = ob_get_contents(); 
    ob_end_clean(); 

    return $content; 
} 
?>