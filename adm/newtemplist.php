<?php
$sub_menu = '100310';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

if( !isset($g5['new_temp_table']) ){
    die('<meta charset="utf-8">/data/dbconfig.php 파일에 <strong>$g5[\'new_temp_table\'] = G5_TABLE_PREFIX.\'new_win\';</strong> 를 추가해 주세요.');
}
//내용(컨텐츠)정보 테이블이 있는지 검사한다.
if(!sql_query(" DESCRIBE {$g5['new_temp_table']} ", false)) {
    if(sql_query(" DESCRIBE {$g5['g5_shop_new_temp_table']} ", false)) {
        sql_query(" ALTER TABLE {$g5['g5_shop_new_temp_table']} RENAME TO `{$g5['new_temp_table']}` ;", false);
    } else {
       $query_cp = sql_query(" CREATE TABLE `g5_new_temp` (
                      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `title` varchar(255) NOT NULL,
                      `url` varchar(255) NOT NULL,
                      `attachment` enum('A','B','C') NOT NULL DEFAULT 'A',
                      `template` text NOT NULL,
                      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                      `created_by` varchar(255) NOT NULL,
                      `deleted_at` timestamp NOT NULL DEFAULT current_timestamp(),
                      `deleted_by` varchar(255) NOT NULL,
                      `deleted` enum('Y','N') NOT NULL DEFAULT 'N',
                      `padding` varchar(10) NOT NULL DEFAULT '15',
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ", true);
    }
}

$g5['title'] = '팝업레이어 템플릿 관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');

$sql_common = " from {$g5['new_temp_table']} ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = "select * $sql_common WHERE deleted='N' order by id desc ";
$result = sql_query($sql);
?>

<style>
    .boarded{border: 1px solid #DDD; border-radius: 3px; padding: 10px; -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;}
</style>

<div class="local_ov01 local_ov">전체 <?php echo $total_count; ?>건</div>

<div class="btn_add01 btn_add">
    <a href="./newtempform.php">새 템플릿 추가</a>
</div>

<div class=" page_wrap">

    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);

        switch($row['attach']) {
            case 'A':
                $nw_device = '레이어 팝업';
                break;
            case 'B':
                $nw_device = '윈도우 팝업';
                break;
            case 'C':
                $nw_device = '상단바';
                break;
            default:
                $nw_device = '레이어 팝업';
                break;
        }

        echo '<div class="col-md-3 text-center boarded">
            <p><input type="checkbox" name="id[]" value="'.$row['id'].'" ></p>
            <div style="height: 200px; width: 100%; background-image: url('.$row['url'].')"><img src="'.$row['url'].'" style="opacity:0;"></div>
            <p>'.$row['title'].'</p>
            <a class="btn btn-primary" href="./newtempform.php?w=u&amp;id='.$row['id'].'">수정</a>
        </div>';
    ?>

    <?php
    }

    if ($i == 0) {
        echo '<div class="col-md-12 text-center"><div class="boarded">자료가 한건도 없습니다.</div></div>';
    }
    ?>
    <div class="clearfix"></div>
</div>

<style>
  div.col-md-3 div{background-size: cover; background-position: 50% 50%;}
  div.col-md-3 div img{max-width: 100%; max-height: 100%;}
</style>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
