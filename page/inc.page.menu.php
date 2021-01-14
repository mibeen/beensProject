<?php
# gr_id에 해당하는 메뉴 on 설정
# $menu array의 첫번째 인덱스 (0)에는 메뉴정보가 아니라 전체 종합 정보가 들어있음.
# 그러므로 첫번째 메뉴는 $menu[1]
for ($i=0; $i < count($menu); $i++) {
    $menu[$i]['on'] = "";
    if ($menu[$i]['gr_id'] == $_gr_id) {
        $menu[$i]['on'] = "on";
        $_idx = $i;
        if ($_gr_id == 'learning') $menu[$i]['is_sub'] = true;        
    }
}

# 주메뉴(gr_id)에 서브메뉴 있으면(is_sub) pid 탐색하여 on 설정
if ($menu[$_idx]['is_sub']) {
    for ($j=0; $j < count($menu[$_idx]['sub']); $j++) {
        if ($menu[$_idx]['sub'][$j]['hid'] == $pid) {
            $_idx2 = $j;
            $menu[$_idx]['sub'][$j]['on'] = "on";
        }
    }
}
?>

<?php
# 상단메뉴에도 현재메뉴 on 표시
if (isset($_idx))  {
    ?>
<script>
$(document).ready(function() {
    var $mna = $('#mnb .menu-li').eq(<?php echo($_idx - 1)?>); <?php /* 메뉴 배열은 1부터 시작하므로 첫번째를 0으로 바꾸기 위해 -1 함 */ ?>
    $mna.removeClass('off').addClass('on');
    <?php
    # 상단메뉴의 서브메뉴 있으면 on 표시
    if (isset($_idx2))  {
        ?>
        $('.sub-1dli', $mna).eq(<?php echo($_idx2)?>).removeClass('off').addClass('on');
        <?php
    }
    ?>
});
</script>
    <?php
}
?>