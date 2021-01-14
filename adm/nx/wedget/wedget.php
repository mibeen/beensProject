<?php

$sub_menu = '100700';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '메인 위젯 관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');


$sql    = "SELECT * FROM nx_widget WHERE deleted='N' ORDER BY row ASC, nx_order ASC";
$result = sql_query($sql, true);
$rows   = sql_num_rows($result);

?>
<link rel="stylesheet" href="<?php echo G5_URL ?>/js/jquery-ui.css">
<script src="<?php echo G5_URL ?>/adm/lib/nx.widget.js" type="text/javascript"></script>
<script src="<?php echo G5_URL ?>/js/jquery-ui.js"></script>
<script>
$(function(){
    var widget = new nx_widget();
    $('.nx-dragable-wrap').sortable({
      placeholder: "ui-state-highlight"
    });
    $('.nx-dragable-wrap').disableSelection();

    $('#btn_submit').on('click', function(e){

        var rows = $('#widget-form').find('.row');
            rows.each(function(){
                var idx = $('#widget-form .row').index($(this));
                $(this).find('input[name="row[]"]').val(idx+1);
            })

        validate();

        //return false;
        $('#widget-form').submit();
    })

    $(document).on('click', 'a.widget_cogs', function(e){
      var href = $(this).attr('href');
      if(href.length > 1){
        e.preventDefault();
        window.open(href, '', 'width=500, height=600, scrollbars=true, resize=true');
      }
    })
})

function validate(){
    return true
}
</script>
<div class="local_ov01 local_ov text-left">
  <p class="text-left">전체 <?php echo $rows; ?>건</p>
  <p class="text-left">새로 추가한 위젯들의 설정을 변경하려면 먼저 저장하십시오.</p>
</div>
<!--
<div class="btn_add01 btn_add">
    <a href="./newtemplist.php">템플릿 관리</a>
    <a href="./newwinform.php">새 레이어 팝업 추가</a>
</div>
-->

<div class=" tbl_wrap">
    <h3 class="title"><?php echo $g5['title']; ?> 목록</h3>
    <div class="widget-box">
        <input type="hidden" name="thema" value="<?php echo THEMA ?>">
        <form name="widget-form" id="widget-form" action="./process.php" method="POST">

        <?php
        # /extend/nx.extend.php
        echo nx_widget_list($result);
        ?>

        <p><br></p>

        <div class="btn-box"><button type="button" id="btn_submit" class="btn btn-warning">저장</button> <button type="button" class="btn btn-default">취소</button></div>

        </form>
    </div>

    <?php
        if ($rows == 0) {
            echo '<div>자료가 한건도 없습니다.</div>';
        }
    ?>
</div>



<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
