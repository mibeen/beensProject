<?php
$sub_menu = "990700";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');


# 날짜 기본
$FROM = clean_xss_tags($_GET['from']);
$TO   = clean_xss_tags($_GET['to']);
$dayArray = [];

if($FROM == '' && $TO == ''){
    # 파라메터가 없으면 이번 달로 지정
    $FROM = date('Y-m-') . '01';
    $TO   = date('Y-m-d');
}else{
    # 파라메터가 있으면 해당 파라메터대로.
    $FROM = date('Y-m-d', strtotime($FROM));
    $TO   = date('Y-m-d', strtotime($TO));
}

# 날짜 범위 지정. array로 반환
$dayArray = createDateRangeArray($FROM, $TO);
$day = [];


# 키와 배열을 바꾼다.
for($i=0; $i < count($dayArray); $i++){
    $day[$dayArray[$i]] = 0;
}


# 게시판 개수, 종류 반환
$sql = "SELECT *
        FROM g5_board
        WHERE bo_skin IN('Basic-Board')
            And bo_table NOT IN ('roller1', 'banner1', 'side_banner1', 'rolling_logo', 'main_banner', 'whatson', 'temp', 'ethical_edu') ";
$db1 = sql_query($sql) or die('sql error');
$cnt = sql_num_rows($db1);
$array = [];
while($row = sql_fetch_array($db1)){
    $array[] = [
          'name' => $row['bo_subject']
        , 'table' => 'g5_write_' . $row['bo_table']
        , 'day' => $day
    ];
}
unset($row);


# 개시판별 통계 삽입
for ($i=0; $i < count($array); $i++) {
    $sql = "SELECT date_format(wr_datetime, '%Y-%m-%d') as date, count(wr_id) as cnt FROM {$array[$i]['table']}
            WHERE date_format(wr_datetime, '%Y-%m-%d') >= '{$FROM}' And date_format(wr_datetime, '%Y-%m-%d') <= '{$TO}'
                AND wr_is_comment = 0
            Group by date_format(wr_datetime, '%Y-%m-%d')
            ";
    $db1 = sql_query($sql);
    while($row = sql_fetch_array($db1)){
        $array[$i]['day'][$row['date']] = $row['cnt'];
    }
    unset($row, $db1, $sql);
}


$g5['title'] = '게시판 통계';
include_once('./admin.head.php');

$colspan = 15;
?>
<link rel="stylesheet" href="/js/jquery-ui-1.12.1.custom/jquery-ui.css">
<script src="/js/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="/js/jquery-ui-1.12.1.custom/datepicker-ko.js"></script>
<style>
.custom-table{
    table-layout: fixed;
}
.custom-table-wrap{
    position: relative;
}
.custom-table th,
.custom-table td{
    padding: 5px 3px;
    word-break: keep-all;
    text-align: center;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
.custom-table tbody td{
    border-right: 1px solid #DDD;
    border-bottom: 1px solid #DDD;
}
#from, #to{
    border: 1px solid #DDD;
    height: 36px;
    padding: 0 10px;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
</style>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    게시판수 <?php echo number_format($cnt) ?>개
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">

    <label for="sfl" class="sound_only">검색대상</label>
    <label for="from">From</label>
    <input type="text" id="from" name="from" value="<?php echo $FROM; ?>">
    <label for="to">to</label>
    <input type="text" id="to" name="to" value="<?php echo $TO; ?>">
    <button type="button" class="nx-btn-s3" onclick="set_date('오늘')">오늘</button>
    <button type="button" class="nx-btn-s3" onclick="set_date('어제')">어제</button>
    <button type="button" class="nx-btn-s3" onclick="set_date('이번주')">이번주</button>
    <button type="button" class="nx-btn-s3" onclick="set_date('이번달')">이번달</button>
    <button type="button" class="nx-btn-s3" onclick="set_date('지난주')">지난주</button>
    <button type="button" class="nx-btn-s3" onclick="set_date('지난달')">지난달</button>
    <!-- <button type="button" class="nx-btn-s3" onclick="set_date('전체')">전체</button> -->

    <button type="submit" class="nx-btn-b2" style="float: right;">검색</button>
    <?php
    if($from != '' || $to != ''){
        echo '<button type="button" onclick="document.location.href = \'./static.php\';" class="nx-btn-b1" style="float: right; margin-right: 10px;">초기화</button>';
    }
    ?>
</form>

<div style="margin: 10px 20px;"><button type="button" onclick="excel();" class="nx-btn-s1">엑셀 다운로드</button></div>

<script>
var excel = function(){
    document.location.href = './static.excel.php?from=<?php echo $FROM; ?>&to=<?php echo $TO; ?>';
}
$( function() {
    $.datepicker.setDefaults( $.datepicker.regional[ "ko" ] );

    var dateFormat= "yy-mm-dd",
        from = $( "#from" )
                .datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 1,
                    dateFormat: "yy-mm-dd"

                })
                .on( "change", function() {
                    to.datepicker( "option", "minDate", getDate( this ) );
                }),
        to = $( "#to" ).datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 1,
                    dateFormat: "yy-mm-dd"

                })
                .on( "change", function() {
                    from.datepicker( "option", "maxDate", getDate( this ) );
                });

    function getDate( element ) {
        var date;
        try {
            date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
            date = null;
        }

        return date;
    }



});

function set_date(today)
{
    <?php
    $date_term = date('w', G5_SERVER_TIME);
    $week_term = $date_term + 7;
    $last_term = strtotime(date('Y-m-01', G5_SERVER_TIME));
    ?>
    if (today == "오늘") {
        document.getElementById("from").value = "<?php echo G5_TIME_YMD; ?>";
        document.getElementById("to").value = "<?php echo G5_TIME_YMD; ?>";
    } else if (today == "어제") {
        document.getElementById("from").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
        document.getElementById("to").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
    } else if (today == "이번주") {
        document.getElementById("from").value = "<?php echo date('Y-m-d', strtotime('-'.$date_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("to").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "이번달") {
        document.getElementById("from").value = "<?php echo date('Y-m-01', G5_SERVER_TIME); ?>";
        document.getElementById("to").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "지난주") {
        document.getElementById("from").value = "<?php echo date('Y-m-d', strtotime('-'.$week_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("to").value = "<?php echo date('Y-m-d', strtotime('-'.($week_term - 6).' days', G5_SERVER_TIME)); ?>";
    } else if (today == "지난달") {
        document.getElementById("from").value = "<?php echo date('Y-m-01', strtotime('-1 Month', $last_term)); ?>";
        document.getElementById("to").value = "<?php echo date('Y-m-t', strtotime('-1 Month', $last_term)); ?>";
    } else if (today == "전체") {
        document.getElementById("from").value = "";
        document.getElementById("to").value = "";
    }

    document.getElementById('fsearch').submit();
}
</script>

<form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">

    <div class="tbl_head01 tbl_wrap custom-table-wrap" style="width: 100%; max-width: 1120px; overflow-x: scroll;margin: 10px 20px 10px 20px; padding: 10px 0; -webkit-box-sizing: border-box;box-sizing: border-box;">
        <table class="tb1 custom-table" style="min-width: <?php echo count($array) * 100 ?>px;">
            <colgroup>
                <col width="100">
                <?php
                    for($i=0; $i < count($array); $i++){
                        echo '<col>';
                    }
                ?>
            </colgroup>
            <thead>
                <tr>
                    <th></th>
                    <?php
                        for($i=0; $i < count($array); $i++){
                            echo '<th data-name="'.$array[$i]['table'].'">'.$array[$i]['name'].'</th>';
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    for($i=0; $i < count($dayArray); $i++){
                        echo '<tr>';
                        echo '<td>'.$dayArray[$i].'</td>';
                        for($i2=0; $i2 < count($array); $i2++){
                            $bgColor = (int)$array[$i2]['day'][$dayArray[$i]] > 0 ? '#fbbc05' : '';
                            echo '<td style="background: '.$bgColor.'">'.$array[$i2]['day'][$dayArray[$i]].'</td>';
                        }
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

<script>
function fboardlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}

$(function(){
    $(".board_copy").click(function(){
        window.open(this.href, "win_board_copy", "left=100,top=100,width=550,height=450");
        return false;
    });
});
</script>

<?php
include_once('./admin.tail.php');

function createDateRangeArray($strDateFrom,$strDateTo) {

    $aryRange  = array();

    $iDateFrom = mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo   = mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry

        while ($iDateFrom < $iDateTo) {
            $iDateFrom += 86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
}


?>
