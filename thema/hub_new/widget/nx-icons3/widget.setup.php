<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// input의 name을 wset[배열키] 형태로 등록
// 모바일 설정값은 동일 배열키에 배열변수만 wmset으로 지정 → wmset[배열키]

if(!$wset['new']) $wset['new'] = 'red';

// Ionicons : https://ionicons.com/v2/
add_stylesheet('<link rel="stylesheet" href="'.THEMA_URL.'/assets/css/css/ionicons.min.css" type="text/css">',-1);


$wset['ico_01'] = ($wset['ico_01'] != '') ? $wset['ico_01'] : "ion-android-add";
$wset['ico_02'] = ($wset['ico_02'] != '') ? $wset['ico_02'] : "ion-android-add";
$wset['ico_03'] = ($wset['ico_03'] != '') ? $wset['ico_03'] : "ion-android-add";
$wset['ico_04'] = ($wset['ico_04'] != '') ? $wset['ico_04'] : "ion-android-add";
$wset['ico_05'] = ($wset['ico_05'] != '') ? $wset['ico_05'] : "ion-android-add";
$wset['ico_06'] = ($wset['ico_06'] != '') ? $wset['ico_06'] : "ion-android-add";
$wset['ico_07'] = ($wset['ico_07'] != '') ? $wset['ico_07'] : "ion-android-add";
$wset['ico_08'] = ($wset['ico_08'] != '') ? $wset['ico_08'] : "ion-android-add";

?>

<div class="tbl_head01 tbl_wrap">
	<table>
	<caption>위젯설정</caption>
	<colgroup>
		<col class="grid_2" width="60">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th scope="col">구분</th>
		<th scope="col">설정</th>
	</tr>
	</thead>
	<tbody>
		<tr>
			<th>영역 1</th>
			<td>
				<div>
					<label for="wset[name_01]">명칭</label>
					<input type="text" name="wset[name_01]" value="<?php echo $wset['name_01'] ?>">
					<label for="wset[ico_01]">아이콘</label>
					<input type="hidden" name="wset[ico_01]" id="wset[ico_01]" value="<?php echo $wset['ico_01']; ?>" onfocus="ico_open(this.name);">
					<span data-name="wset[ico_01]" class="<?php echo $wset['ico_01']; ?>" onclick="ico_open(this.getAttribute('data-name'));"></span>
					(아이콘을 클릭하여 변경하세요)
				</div>
				<div style="padding-top: 15px;">
					<label for="">URL</label>
					<input type="text" name="wset[url_01]" value="<?php echo $wset['url_01'] ?>" style="width: calc(100% - 100px);">
				</div>
				<input type="checkbox" name="wset[target_01]" id="wset[target_01]" value="1" <?php echo get_checked('1', $wset['target_01']); ?>>
				<label for="wset[target_01]">새창여부</label>
			</td>
		</tr>
		<tr>
			<th>영역 2</th>
			<td>
				<div>
					<label for="wset[name_02]">명칭</label>
					<input type="text" name="wset[name_02]" value="<?php echo $wset['name_02'] ?>">
					<label for="wset[ico_02]">아이콘</label>
					<input type="hidden" name="wset[ico_02]" id="wset[ico_02]" value="<?php echo $wset['ico_02']; ?>" onfocus="ico_open(this.name);">
					<span data-name="wset[ico_02]" class="<?php echo $wset['ico_02']; ?>" onclick="ico_open(this.getAttribute('data-name'));"></span>
					(아이콘을 클릭하여 변경하세요)
				</div>
				<div style="padding-top: 15px;">
					<label for="">URL</label>
					<input type="text" name="wset[url_02]" value="<?php echo $wset['url_02'] ?>" style="width: calc(100% - 100px);">
				</div>
				<input type="checkbox" name="wset[target_02]" id="wset[target_02]" value="1" <?php echo get_checked('1', $wset['target_02']); ?>>
				<label for="wset[target_02]">새창여부</label>
			</td>
		</tr>
		<tr>
			<th>영역 3</th>
			<td>
				<div>
					<label for="wset[name_03]">명칭</label>
					<input type="text" name="wset[name_03]" value="<?php echo $wset['name_03'] ?>">
					<label for="wset[ico_03]">아이콘</label>
					<input type="hidden" name="wset[ico_03]" id="wset[ico_03]" value="<?php echo $wset['ico_03']; ?>" onfocus="ico_open(this.name);">
					<span data-name="wset[ico_03]" class="<?php echo $wset['ico_03']; ?>" onclick="ico_open(this.getAttribute('data-name'));"></span>
					(아이콘을 클릭하여 변경하세요)
				</div>
				<div style="padding-top: 15px;">
					<label for="">URL</label>
					<input type="text" name="wset[url_03]" value="<?php echo $wset['url_03'] ?>" style="width: calc(100% - 100px);">
				</div>
				<input type="checkbox" name="wset[target_03]" id="wset[target_03]" value="1" <?php echo get_checked('1', $wset['target_03']); ?>>
				<label for="wset[target_03]">새창여부</label>
			</td>
		</tr>
		<tr>
			<th>영역 4</th>
			<td>
				<div>
					<label for="wset[name_04]">명칭</label>
					<input type="text" name="wset[name_04]" value="<?php echo $wset['name_04'] ?>">
					<label for="wset[ico_04]">아이콘</label>
					<input type="hidden" name="wset[ico_04]" id="wset[ico_04]" value="<?php echo $wset['ico_04']; ?>" onfocus="ico_open(this.name);">
					<span data-name="wset[ico_04]" class="<?php echo $wset['ico_04']; ?>" onclick="ico_open(this.getAttribute('data-name'));"></span>
					(아이콘을 클릭하여 변경하세요)
				</div>
				<div style="padding-top: 15px;">
					<label for="">URL</label>
					<input type="text" name="wset[url_04]" value="<?php echo $wset['url_04'] ?>" style="width: calc(100% - 100px);">
				</div>
				<input type="checkbox" name="wset[target_04]" id="wset[target_04]" value="1" <?php echo get_checked('1', $wset['target_04']); ?>>
				<label for="wset[target_04]">새창여부</label>
			</td>
		</tr>
		<tr>
			<th>영역 5</th>
			<td>
				<div>
					<label for="wset[name_05]">명칭</label>
					<input type="text" name="wset[name_05]" value="<?php echo $wset['name_05'] ?>">
					<label for="wset[ico_05]">아이콘</label>
					<input type="hidden" name="wset[ico_05]" id="wset[ico_05]" value="<?php echo $wset['ico_05']; ?>" onfocus="ico_open(this.name);">
					<span data-name="wset[ico_05]" class="<?php echo $wset['ico_05']; ?>" onclick="ico_open(this.getAttribute('data-name'));"></span>
					(아이콘을 클릭하여 변경하세요)
				</div>
				<div style="padding-top: 15px;">
					<label for="">URL</label>
					<input type="text" name="wset[url_05]" value="<?php echo $wset['url_05'] ?>" style="width: calc(100% - 100px);">
				</div>
				<input type="checkbox" name="wset[target_05]" id="wset[target_05]" value="1" <?php echo get_checked('1', $wset['target_05']); ?>>
				<label for="wset[target_05]">새창여부</label>
			</td>
		</tr>
		<tr>
			<th>영역 6</th>
			<td>
				<div>
					<label for="wset[name_06]">명칭</label>
					<input type="text" name="wset[name_06]" value="<?php echo $wset['name_06'] ?>">
					<label for="wset[ico_06]">아이콘</label>
					<input type="hidden" name="wset[ico_06]" id="wset[ico_06]" value="<?php echo $wset['ico_06']; ?>" onfocus="ico_open(this.name);">
					<span data-name="wset[ico_06]" class="<?php echo $wset['ico_06']; ?>" onclick="ico_open(this.getAttribute('data-name'));"></span>
					(아이콘을 클릭하여 변경하세요)
				</div>
				<div style="padding-top: 15px;">
					<label for="">URL</label>
					<input type="text" name="wset[url_06]" value="<?php echo $wset['url_06'] ?>" style="width: calc(100% - 100px);">
				</div>
				<input type="checkbox" name="wset[target_06]" id="wset[target_06]" value="1" <?php echo get_checked('1', $wset['target_06']); ?>>
				<label for="wset[target_06]">새창여부</label>
			</td>
		</tr>
		<tr>
			<th>영역 7</th>
			<td>
				<div>
					<label for="wset[name_07]">명칭</label>
					<input type="text" name="wset[name_07]" value="<?php echo $wset['name_07'] ?>">
					<label for="wset[ico_07]">아이콘</label>
					<input type="hidden" name="wset[ico_07]" id="wset[ico_07]" value="<?php echo $wset['ico_07']; ?>" onfocus="ico_open(this.name);">
					<span data-name="wset[ico_07]" class="<?php echo $wset['ico_07']; ?>" onclick="ico_open(this.getAttribute('data-name'));"></span>
					(아이콘을 클릭하여 변경하세요)
				</div>
				<div style="padding-top: 15px;">
					<label for="">URL</label>
					<input type="text" name="wset[url_07]" value="<?php echo $wset['url_07'] ?>" style="width: calc(100% - 100px);">
				</div>
				<input type="checkbox" name="wset[target_07]" id="wset[target_07]" value="1" <?php echo get_checked('1', $wset['target_07']); ?>>
				<label for="wset[target_07]">새창여부</label>
			</td>
		</tr>
		<tr>
			<th>영역 8</th>
			<td>
				<div>
					<label for="wset[name_08]">명칭</label>
					<input type="text" name="wset[name_08]" value="<?php echo $wset['name_08'] ?>">
					<label for="wset[ico_08]">아이콘</label>
					<input type="hidden" name="wset[ico_08]" id="wset[ico_08]" value="<?php echo $wset['ico_08']; ?>" onfocus="ico_open(this.name);">
					<span data-name="wset[ico_08]" class="<?php echo $wset['ico_08']; ?>" onclick="ico_open(this.getAttribute('data-name'));"></span>
					(아이콘을 클릭하여 변경하세요)
				</div>
				<div style="padding-top: 15px;">
					<label for="">URL</label>
					<input type="text" name="wset[url_08]" value="<?php echo $wset['url_08'] ?>" style="width: calc(100% - 100px);">
				</div>
				<input type="checkbox" name="wset[target_08]" id="wset[target_08]" value="1" <?php echo get_checked('1', $wset['target_08']); ?>>
				<label for="wset[target_08]">새창여부</label>
			</td>
		</tr>
	</tbody>
	</table>
</div>

<style>
	.popup-bg {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
		background: #000;
		opacity: 0;
		visibility: hidden;
		z-index: -1;
	}
	.popup-bg.active {
		opacity: .8;
		visibility: visible;
		z-index: 1000;
	}
	.pop-ico { 
		opacity: 0;
		visibility: hidden;
		z-index: -1;
		padding: 15px;
		border: 1px solid #DDD;
		position: fixed; width: 100%; height: 100%; 
		max-width: 300px; max-height: 300px;
		top: 15px; right: 15px; bottom: 15px; left: 15px; 
		margin: auto; 
		transition: all .2s ease-in-out;
		overflow: scroll; 
		background: #FFF;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}
	.pop-ico.active {
		opacity: 1;
		visibility: visible;
		z-index: 1000;
	}
	.pop-ico:after {
		content: '';
		display: block;
		clear: both;
	}
	.pop-ico span {
		float: left;
		width: 12%;
		height: 12%;
		font-size: 20px;
		text-align: center;
		transition: all .2s ease-in-out;
		cursor: pointer;
	}
	.pop-ico span:hover {
		color: #F00;
	}

	.tbl_head01 [data-name] {
		font-size: 20px;
	    margin-left: 10px;
	    vertical-align: middle;
	}
</style>

<div class="popup-bg"></div>
<div class="pop-ico">
	<span class="ion-alert"></span>
	<span class="ion-alert-circled"></span>
	<span class="ion-android-add"></span>
	<span class="ion-android-add-circle"></span>
	<span class="ion-android-alarm-clock"></span>
	<span class="ion-android-alert"></span>
	<span class="ion-android-apps"></span>
	<span class="ion-android-archive"></span>
	<span class="ion-android-arrow-back"></span>
	<span class="ion-android-arrow-down"></span>
	<span class="ion-android-arrow-dropdown"></span>
	<span class="ion-android-arrow-dropdown-circle"></span>
	<span class="ion-android-arrow-dropleft"></span>
	<span class="ion-android-arrow-dropleft-circle"></span>
	<span class="ion-android-arrow-dropright"></span>
	<span class="ion-android-arrow-dropright-circle"></span>
	<span class="ion-android-arrow-dropup"></span>
	<span class="ion-android-arrow-dropup-circle"></span>
	<span class="ion-android-arrow-forward"></span>
	<span class="ion-android-arrow-up"></span>
	<span class="ion-android-attach"></span>
	<span class="ion-android-bar"></span>
	<span class="ion-android-bicycle"></span>
	<span class="ion-android-boat"></span>
	<span class="ion-android-bookmark"></span>
	<span class="ion-android-bulb"></span>
	<span class="ion-android-bus"></span>
	<span class="ion-android-calendar"></span>
	<span class="ion-android-call"></span>
	<span class="ion-android-camera"></span>
	<span class="ion-android-cancel"></span>
	<span class="ion-android-car"></span>
	<span class="ion-android-cart"></span>
	<span class="ion-android-chat"></span>
	<span class="ion-android-checkbox"></span>
	<span class="ion-android-checkbox-blank"></span>
	<span class="ion-android-checkbox-outline"></span>
	<span class="ion-android-checkbox-outline-blank"></span>
	<span class="ion-android-checkmark-circle"></span>
	<span class="ion-android-clipboard"></span>
	<span class="ion-android-close"></span>
	<span class="ion-android-cloud"></span>
	<span class="ion-android-cloud-circle"></span>
	<span class="ion-android-cloud-done"></span>
	<span class="ion-android-cloud-outline"></span>
	<span class="ion-android-color-palette"></span>
	<span class="ion-android-compass"></span>
	<span class="ion-android-contact"></span>
	<span class="ion-android-contacts"></span>
	<span class="ion-android-contract"></span>
	<span class="ion-android-create"></span>
	<span class="ion-android-delete"></span>
	<span class="ion-android-desktop"></span>
	<span class="ion-android-document"></span>
	<span class="ion-android-done"></span>
	<span class="ion-android-done-all"></span>
	<span class="ion-android-download"></span>
	<span class="ion-android-drafts"></span>
	<span class="ion-android-exit"></span>
	<span class="ion-android-expand"></span>
	<span class="ion-android-favorite"></span>
	<span class="ion-android-favorite-outline"></span>
	<span class="ion-android-film"></span>
	<span class="ion-android-folder"></span>
	<span class="ion-android-folder-open"></span>
	<span class="ion-android-funnel"></span>
	<span class="ion-android-globe"></span>
	<span class="ion-android-hand"></span>
	<span class="ion-android-hangout"></span>
	<span class="ion-android-happy"></span>
	<span class="ion-android-home"></span>
	<span class="ion-android-image"></span>
	<span class="ion-android-laptop"></span>
	<span class="ion-android-list"></span>
	<span class="ion-android-locate"></span>
	<span class="ion-android-lock"></span>
	<span class="ion-android-mail"></span>
	<span class="ion-android-map"></span>
	<span class="ion-android-menu"></span>
	<span class="ion-android-microphone"></span>
	<span class="ion-android-microphone-off"></span>
	<span class="ion-android-more-horizontal"></span>
	<span class="ion-android-more-vertical"></span>
	<span class="ion-android-navigate"></span>
	<span class="ion-android-notifications"></span>
	<span class="ion-android-notifications-none"></span>
	<span class="ion-android-notifications-off"></span>
	<span class="ion-android-open"></span>
	<span class="ion-android-options"></span>
	<span class="ion-android-people"></span>
	<span class="ion-android-person"></span>
	<span class="ion-android-person-add"></span>
	<span class="ion-android-phone-landscape"></span>
	<span class="ion-android-phone-portrait"></span>
	<span class="ion-android-pin"></span>
	<span class="ion-android-plane"></span>
	<span class="ion-android-playstore"></span>
	<span class="ion-android-print"></span>
	<span class="ion-android-radio-button-off"></span>
	<span class="ion-android-radio-button-on"></span>
	<span class="ion-android-refresh"></span>
	<span class="ion-android-remove"></span>
	<span class="ion-android-remove-circle"></span>
	<span class="ion-android-restaurant"></span>
	<span class="ion-android-sad"></span>
	<span class="ion-android-search"></span>
	<span class="ion-android-send"></span>
	<span class="ion-android-settings"></span>
	<span class="ion-android-share"></span>
	<span class="ion-android-share-alt"></span>
	<span class="ion-android-star"></span>
	<span class="ion-android-star-half"></span>
	<span class="ion-android-star-outline"></span>
	<span class="ion-android-stopwatch"></span>
	<span class="ion-android-subway"></span>
	<span class="ion-android-sunny"></span>
	<span class="ion-android-sync"></span>
	<span class="ion-android-textsms"></span>
	<span class="ion-android-time"></span>
	<span class="ion-android-train"></span>
	<span class="ion-android-unlock"></span>
	<span class="ion-android-upload"></span>
	<span class="ion-android-volume-down"></span>
	<span class="ion-android-volume-mute"></span>
	<span class="ion-android-volume-off"></span>
	<span class="ion-android-volume-up"></span>
	<span class="ion-android-walk"></span>
	<span class="ion-android-warning"></span>
	<span class="ion-android-watch"></span>
	<span class="ion-android-wifi"></span>
	<span class="ion-aperture"></span>
	<span class="ion-archive"></span>
	<span class="ion-arrow-down-a"></span>
	<span class="ion-arrow-down-b"></span>
	<span class="ion-arrow-down-c"></span>
	<span class="ion-arrow-expand"></span>
	<span class="ion-arrow-graph-down-left"></span>
	<span class="ion-arrow-graph-down-right"></span>
	<span class="ion-arrow-graph-up-left"></span>
	<span class="ion-arrow-graph-up-right"></span>
	<span class="ion-arrow-left-a"></span>
	<span class="ion-arrow-left-b"></span>
	<span class="ion-arrow-left-c"></span>
	<span class="ion-arrow-move"></span>
	<span class="ion-arrow-resize"></span>
	<span class="ion-arrow-return-left"></span>
	<span class="ion-arrow-return-right"></span>
	<span class="ion-arrow-right-a"></span>
	<span class="ion-arrow-right-b"></span>
	<span class="ion-arrow-right-c"></span>
	<span class="ion-arrow-shrink"></span>
	<span class="ion-arrow-swap"></span>
	<span class="ion-arrow-up-a"></span>
	<span class="ion-arrow-up-b"></span>
	<span class="ion-arrow-up-c"></span>
	<span class="ion-asterisk"></span>
	<span class="ion-at"></span>
	<span class="ion-backspace"></span>
	<span class="ion-backspace-outline"></span>
	<span class="ion-bag"></span>
	<span class="ion-battery-charging"></span>
	<span class="ion-battery-empty"></span>
	<span class="ion-battery-full"></span>
	<span class="ion-battery-half"></span>
	<span class="ion-battery-low"></span>
	<span class="ion-beaker"></span>
	<span class="ion-beer"></span>
	<span class="ion-bluetooth"></span>
	<span class="ion-bonfire"></span>
	<span class="ion-bookmark"></span>
	<span class="ion-bowtie"></span>
	<span class="ion-briefcase"></span>
	<span class="ion-bug"></span>
	<span class="ion-calculator"></span>
	<span class="ion-calendar"></span>
	<span class="ion-camera"></span>
	<span class="ion-card"></span>
	<span class="ion-cash"></span>
	<span class="ion-chatbox"></span>
	<span class="ion-chatbox-working"></span>
	<span class="ion-chatboxes"></span>
	<span class="ion-chatbubble"></span>
	<span class="ion-chatbubble-working"></span>
	<span class="ion-chatbubbles"></span>
	<span class="ion-checkmark"></span>
	<span class="ion-checkmark-circled"></span>
	<span class="ion-checkmark-round"></span>
	<span class="ion-chevron-down"></span>
	<span class="ion-chevron-left"></span>
	<span class="ion-chevron-right"></span>
	<span class="ion-chevron-up"></span>
	<span class="ion-clipboard"></span>
	<span class="ion-clock"></span>
	<span class="ion-close"></span>
	<span class="ion-close-circled"></span>
	<span class="ion-close-round"></span>
	<span class="ion-closed-captioning"></span>
	<span class="ion-cloud"></span>
	<span class="ion-code"></span>
	<span class="ion-code-download"></span>
	<span class="ion-code-working"></span>
	<span class="ion-coffee"></span>
	<span class="ion-compass"></span>
	<span class="ion-compose"></span>
	<span class="ion-connection-bars"></span>
	<span class="ion-contrast"></span>
	<span class="ion-crop"></span>
	<span class="ion-cube"></span>
	<span class="ion-disc"></span>
	<span class="ion-document"></span>
	<span class="ion-document-text"></span>
	<span class="ion-drag"></span>
	<span class="ion-earth"></span>
	<span class="ion-easel"></span>
	<span class="ion-edit"></span>
	<span class="ion-egg"></span>
	<span class="ion-eject"></span>
	<span class="ion-email"></span>
	<span class="ion-email-unread"></span>
	<span class="ion-erlenmeyer-flask"></span>
	<span class="ion-erlenmeyer-flask-bubbles"></span>
	<span class="ion-eye"></span>
	<span class="ion-eye-disabled"></span>
	<span class="ion-female"></span>
	<span class="ion-filing"></span>
	<span class="ion-film-marker"></span>
	<span class="ion-fireball"></span>
	<span class="ion-flag"></span>
	<span class="ion-flame"></span>
	<span class="ion-flash"></span>
	<span class="ion-flash-off"></span>
	<span class="ion-folder"></span>
	<span class="ion-fork"></span>
	<span class="ion-fork-repo"></span>
	<span class="ion-forward"></span>
	<span class="ion-funnel"></span>
	<span class="ion-gear-a"></span>
	<span class="ion-gear-b"></span>
	<span class="ion-grid"></span>
	<span class="ion-hammer"></span>
	<span class="ion-happy"></span>
	<span class="ion-happy-outline"></span>
	<span class="ion-headphone"></span>
	<span class="ion-heart"></span>
	<span class="ion-heart-broken"></span>
	<span class="ion-help"></span>
	<span class="ion-help-buoy"></span>
	<span class="ion-help-circled"></span>
	<span class="ion-home"></span>
	<span class="ion-icecream"></span>
	<span class="ion-image"></span>
	<span class="ion-images"></span>
	<span class="ion-information"></span>
	<span class="ion-information-circled"></span>
	<span class="ion-ionic"></span>
	<span class="ion-ios-alarm"></span>
	<span class="ion-ios-alarm-outline"></span>
	<span class="ion-ios-albums"></span>
	<span class="ion-ios-albums-outline"></span>
	<span class="ion-ios-americanfootball"></span>
	<span class="ion-ios-americanfootball-outline"></span>
	<span class="ion-ios-analytics"></span>
	<span class="ion-ios-analytics-outline"></span>
	<span class="ion-ios-arrow-back"></span>
	<span class="ion-ios-arrow-down"></span>
	<span class="ion-ios-arrow-forward"></span>
	<span class="ion-ios-arrow-left"></span>
	<span class="ion-ios-arrow-right"></span>
	<span class="ion-ios-arrow-thin-down"></span>
	<span class="ion-ios-arrow-thin-left"></span>
	<span class="ion-ios-arrow-thin-right"></span>
	<span class="ion-ios-arrow-thin-up"></span>
	<span class="ion-ios-arrow-up"></span>
	<span class="ion-ios-at"></span>
	<span class="ion-ios-at-outline"></span>
	<span class="ion-ios-barcode"></span>
	<span class="ion-ios-barcode-outline"></span>
	<span class="ion-ios-baseball"></span>
	<span class="ion-ios-baseball-outline"></span>
	<span class="ion-ios-basketball"></span>
	<span class="ion-ios-basketball-outline"></span>
	<span class="ion-ios-bell"></span>
	<span class="ion-ios-bell-outline"></span>
	<span class="ion-ios-body"></span>
	<span class="ion-ios-body-outline"></span>
	<span class="ion-ios-bolt"></span>
	<span class="ion-ios-bolt-outline"></span>
	<span class="ion-ios-book"></span>
	<span class="ion-ios-book-outline"></span>
	<span class="ion-ios-bookmarks"></span>
	<span class="ion-ios-bookmarks-outline"></span>
	<span class="ion-ios-box"></span>
	<span class="ion-ios-box-outline"></span>
	<span class="ion-ios-briefcase"></span>
	<span class="ion-ios-briefcase-outline"></span>
	<span class="ion-ios-browsers"></span>
	<span class="ion-ios-browsers-outline"></span>
	<span class="ion-ios-calculator"></span>
	<span class="ion-ios-calculator-outline"></span>
	<span class="ion-ios-calendar"></span>
	<span class="ion-ios-calendar-outline"></span>
	<span class="ion-ios-camera"></span>
	<span class="ion-ios-camera-outline"></span>
	<span class="ion-ios-cart"></span>
	<span class="ion-ios-cart-outline"></span>
	<span class="ion-ios-chatboxes"></span>
	<span class="ion-ios-chatboxes-outline"></span>
	<span class="ion-ios-chatbubble"></span>
	<span class="ion-ios-chatbubble-outline"></span>
	<span class="ion-ios-checkmark"></span>
	<span class="ion-ios-checkmark-empty"></span>
	<span class="ion-ios-checkmark-outline"></span>
	<span class="ion-ios-circle-filled"></span>
	<span class="ion-ios-circle-outline"></span>
	<span class="ion-ios-clock"></span>
	<span class="ion-ios-clock-outline"></span>
	<span class="ion-ios-close"></span>
	<span class="ion-ios-close-empty"></span>
	<span class="ion-ios-close-outline"></span>
	<span class="ion-ios-cloud"></span>
	<span class="ion-ios-cloud-download"></span>
	<span class="ion-ios-cloud-download-outline"></span>
	<span class="ion-ios-cloud-outline"></span>
	<span class="ion-ios-cloud-upload"></span>
	<span class="ion-ios-cloud-upload-outline"></span>
	<span class="ion-ios-cloudy"></span>
	<span class="ion-ios-cloudy-night"></span>
	<span class="ion-ios-cloudy-night-outline"></span>
	<span class="ion-ios-cloudy-outline"></span>
	<span class="ion-ios-cog"></span>
	<span class="ion-ios-cog-outline"></span>
	<span class="ion-ios-color-filter"></span>
	<span class="ion-ios-color-filter-outline"></span>
	<span class="ion-ios-color-wand"></span>
	<span class="ion-ios-color-wand-outline"></span>
	<span class="ion-ios-compose"></span>
	<span class="ion-ios-compose-outline"></span>
	<span class="ion-ios-contact"></span>
	<span class="ion-ios-contact-outline"></span>
	<span class="ion-ios-copy"></span>
	<span class="ion-ios-copy-outline"></span>
	<span class="ion-ios-crop"></span>
	<span class="ion-ios-crop-strong"></span>
	<span class="ion-ios-download"></span>
	<span class="ion-ios-download-outline"></span>
	<span class="ion-ios-drag"></span>
	<span class="ion-ios-email"></span>
	<span class="ion-ios-email-outline"></span>
	<span class="ion-ios-eye"></span>
	<span class="ion-ios-eye-outline"></span>
	<span class="ion-ios-fastforward"></span>
	<span class="ion-ios-fastforward-outline"></span>
	<span class="ion-ios-filing"></span>
	<span class="ion-ios-filing-outline"></span>
	<span class="ion-ios-film"></span>
	<span class="ion-ios-film-outline"></span>
	<span class="ion-ios-flag"></span>
	<span class="ion-ios-flag-outline"></span>
	<span class="ion-ios-flame"></span>
	<span class="ion-ios-flame-outline"></span>
	<span class="ion-ios-flask"></span>
	<span class="ion-ios-flask-outline"></span>
	<span class="ion-ios-flower"></span>
	<span class="ion-ios-flower-outline"></span>
	<span class="ion-ios-folder"></span>
	<span class="ion-ios-folder-outline"></span>
	<span class="ion-ios-football"></span>
	<span class="ion-ios-football-outline"></span>
	<span class="ion-ios-game-controller-a"></span>
	<span class="ion-ios-game-controller-a-outline"></span>
	<span class="ion-ios-game-controller-b"></span>
	<span class="ion-ios-game-controller-b-outline"></span>
	<span class="ion-ios-gear"></span>
	<span class="ion-ios-gear-outline"></span>
	<span class="ion-ios-glasses"></span>
	<span class="ion-ios-glasses-outline"></span>
	<span class="ion-ios-grid-view"></span>
	<span class="ion-ios-grid-view-outline"></span>
	<span class="ion-ios-heart"></span>
	<span class="ion-ios-heart-outline"></span>
	<span class="ion-ios-help"></span>
	<span class="ion-ios-help-empty"></span>
	<span class="ion-ios-help-outline"></span>
	<span class="ion-ios-home"></span>
	<span class="ion-ios-home-outline"></span>
	<span class="ion-ios-infinite"></span>
	<span class="ion-ios-infinite-outline"></span>
	<span class="ion-ios-information"></span>
	<span class="ion-ios-information-empty"></span>
	<span class="ion-ios-information-outline"></span>
	<span class="ion-ios-ionic-outline"></span>
	<span class="ion-ios-keypad"></span>
	<span class="ion-ios-keypad-outline"></span>
	<span class="ion-ios-lightbulb"></span>
	<span class="ion-ios-lightbulb-outline"></span>
	<span class="ion-ios-list"></span>
	<span class="ion-ios-list-outline"></span>
	<span class="ion-ios-location"></span>
	<span class="ion-ios-location-outline"></span>
	<span class="ion-ios-locked"></span>
	<span class="ion-ios-locked-outline"></span>
	<span class="ion-ios-loop"></span>
	<span class="ion-ios-loop-strong"></span>
	<span class="ion-ios-medical"></span>
	<span class="ion-ios-medical-outline"></span>
	<span class="ion-ios-medkit"></span>
	<span class="ion-ios-medkit-outline"></span>
	<span class="ion-ios-mic"></span>
	<span class="ion-ios-mic-off"></span>
	<span class="ion-ios-mic-outline"></span>
	<span class="ion-ios-minus"></span>
	<span class="ion-ios-minus-empty"></span>
	<span class="ion-ios-minus-outline"></span>
	<span class="ion-ios-monitor"></span>
	<span class="ion-ios-monitor-outline"></span>
	<span class="ion-ios-moon"></span>
	<span class="ion-ios-moon-outline"></span>
	<span class="ion-ios-more"></span>
	<span class="ion-ios-more-outline"></span>
	<span class="ion-ios-musical-note"></span>
	<span class="ion-ios-musical-notes"></span>
	<span class="ion-ios-navigate"></span>
	<span class="ion-ios-navigate-outline"></span>
	<span class="ion-ios-nutrition"></span>
	<span class="ion-ios-nutrition-outline"></span>
	<span class="ion-ios-paper"></span>
	<span class="ion-ios-paper-outline"></span>
	<span class="ion-ios-paperplane"></span>
	<span class="ion-ios-paperplane-outline"></span>
	<span class="ion-ios-partlysunny"></span>
	<span class="ion-ios-partlysunny-outline"></span>
	<span class="ion-ios-pause"></span>
	<span class="ion-ios-pause-outline"></span>
	<span class="ion-ios-paw"></span>
	<span class="ion-ios-paw-outline"></span>
	<span class="ion-ios-people"></span>
	<span class="ion-ios-people-outline"></span>
	<span class="ion-ios-person"></span>
	<span class="ion-ios-person-outline"></span>
	<span class="ion-ios-personadd"></span>
	<span class="ion-ios-personadd-outline"></span>
	<span class="ion-ios-photos"></span>
	<span class="ion-ios-photos-outline"></span>
	<span class="ion-ios-pie"></span>
	<span class="ion-ios-pie-outline"></span>
	<span class="ion-ios-pint"></span>
	<span class="ion-ios-pint-outline"></span>
	<span class="ion-ios-play"></span>
	<span class="ion-ios-play-outline"></span>
	<span class="ion-ios-plus"></span>
	<span class="ion-ios-plus-empty"></span>
	<span class="ion-ios-plus-outline"></span>
	<span class="ion-ios-pricetag"></span>
	<span class="ion-ios-pricetag-outline"></span>
	<span class="ion-ios-pricetags"></span>
	<span class="ion-ios-pricetags-outline"></span>
	<span class="ion-ios-printer"></span>
	<span class="ion-ios-printer-outline"></span>
	<span class="ion-ios-pulse"></span>
	<span class="ion-ios-pulse-strong"></span>
	<span class="ion-ios-rainy"></span>
	<span class="ion-ios-rainy-outline"></span>
	<span class="ion-ios-recording"></span>
	<span class="ion-ios-recording-outline"></span>
	<span class="ion-ios-redo"></span>
	<span class="ion-ios-redo-outline"></span>
	<span class="ion-ios-refresh"></span>
	<span class="ion-ios-refresh-empty"></span>
	<span class="ion-ios-refresh-outline"></span>
	<span class="ion-ios-reload"></span>
	<span class="ion-ios-reverse-camera"></span>
	<span class="ion-ios-reverse-camera-outline"></span>
	<span class="ion-ios-rewind"></span>
	<span class="ion-ios-rewind-outline"></span>
	<span class="ion-ios-rose"></span>
	<span class="ion-ios-rose-outline"></span>
	<span class="ion-ios-search"></span>
	<span class="ion-ios-search-strong"></span>
	<span class="ion-ios-settings"></span>
	<span class="ion-ios-settings-strong"></span>
	<span class="ion-ios-shuffle"></span>
	<span class="ion-ios-shuffle-strong"></span>
	<span class="ion-ios-skipbackward"></span>
	<span class="ion-ios-skipbackward-outline"></span>
	<span class="ion-ios-skipforward"></span>
	<span class="ion-ios-skipforward-outline"></span>
	<span class="ion-ios-snowy"></span>
	<span class="ion-ios-speedometer"></span>
	<span class="ion-ios-speedometer-outline"></span>
	<span class="ion-ios-star"></span>
	<span class="ion-ios-star-half"></span>
	<span class="ion-ios-star-outline"></span>
	<span class="ion-ios-stopwatch"></span>
	<span class="ion-ios-stopwatch-outline"></span>
	<span class="ion-ios-sunny"></span>
	<span class="ion-ios-sunny-outline"></span>
	<span class="ion-ios-telephone"></span>
	<span class="ion-ios-telephone-outline"></span>
	<span class="ion-ios-tennisball"></span>
	<span class="ion-ios-tennisball-outline"></span>
	<span class="ion-ios-thunderstorm"></span>
	<span class="ion-ios-thunderstorm-outline"></span>
	<span class="ion-ios-time"></span>
	<span class="ion-ios-time-outline"></span>
	<span class="ion-ios-timer"></span>
	<span class="ion-ios-timer-outline"></span>
	<span class="ion-ios-toggle"></span>
	<span class="ion-ios-toggle-outline"></span>
	<span class="ion-ios-trash"></span>
	<span class="ion-ios-trash-outline"></span>
	<span class="ion-ios-undo"></span>
	<span class="ion-ios-undo-outline"></span>
	<span class="ion-ios-unlocked"></span>
	<span class="ion-ios-unlocked-outline"></span>
	<span class="ion-ios-upload"></span>
	<span class="ion-ios-upload-outline"></span>
	<span class="ion-ios-videocam"></span>
	<span class="ion-ios-videocam-outline"></span>
	<span class="ion-ios-volume-high"></span>
	<span class="ion-ios-volume-low"></span>
	<span class="ion-ios-wineglass"></span>
	<span class="ion-ios-wineglass-outline"></span>
	<span class="ion-ios-world"></span>
	<span class="ion-ios-world-outline"></span>
	<span class="ion-ipad"></span>
	<span class="ion-iphone"></span>
	<span class="ion-ipod"></span>
	<span class="ion-jet"></span>
	<span class="ion-key"></span>
	<span class="ion-knife"></span>
	<span class="ion-laptop"></span>
	<span class="ion-leaf"></span>
	<span class="ion-levels"></span>
	<span class="ion-lightbulb"></span>
	<span class="ion-link"></span>
	<span class="ion-load-a"></span>
	<span class="ion-load-b"></span>
	<span class="ion-load-c"></span>
	<span class="ion-load-d"></span>
	<span class="ion-location"></span>
	<span class="ion-lock-combination"></span>
	<span class="ion-locked"></span>
	<span class="ion-log-in"></span>
	<span class="ion-log-out"></span>
	<span class="ion-loop"></span>
	<span class="ion-magnet"></span>
	<span class="ion-male"></span>
	<span class="ion-man"></span>
	<span class="ion-map"></span>
	<span class="ion-medkit"></span>
	<span class="ion-merge"></span>
	<span class="ion-mic-a"></span>
	<span class="ion-mic-b"></span>
	<span class="ion-mic-c"></span>
	<span class="ion-minus"></span>
	<span class="ion-minus-circled"></span>
	<span class="ion-minus-round"></span>
	<span class="ion-model-s"></span>
	<span class="ion-monitor"></span>
	<span class="ion-more"></span>
	<span class="ion-mouse"></span>
	<span class="ion-music-note"></span>
	<span class="ion-navicon"></span>
	<span class="ion-navicon-round"></span>
	<span class="ion-navigate"></span>
	<span class="ion-network"></span>
	<span class="ion-no-smoking"></span>
	<span class="ion-nuclear"></span>
	<span class="ion-outlet"></span>
	<span class="ion-paintbrush"></span>
	<span class="ion-paintbucket"></span>
	<span class="ion-paper-airplane"></span>
	<span class="ion-paperclip"></span>
	<span class="ion-pause"></span>
	<span class="ion-person"></span>
	<span class="ion-person-add"></span>
	<span class="ion-person-stalker"></span>
	<span class="ion-pie-graph"></span>
	<span class="ion-pin"></span>
	<span class="ion-pinpoint"></span>
	<span class="ion-pizza"></span>
	<span class="ion-plane"></span>
	<span class="ion-planet"></span>
	<span class="ion-play"></span>
	<span class="ion-playstation"></span>
	<span class="ion-plus"></span>
	<span class="ion-plus-circled"></span>
	<span class="ion-plus-round"></span>
	<span class="ion-podium"></span>
	<span class="ion-pound"></span>
	<span class="ion-power"></span>
	<span class="ion-pricetag"></span>
	<span class="ion-pricetags"></span>
	<span class="ion-printer"></span>
	<span class="ion-pull-request"></span>
	<span class="ion-qr-scanner"></span>
	<span class="ion-quote"></span>
	<span class="ion-radio-waves"></span>
	<span class="ion-record"></span>
	<span class="ion-refresh"></span>
	<span class="ion-reply"></span>
	<span class="ion-reply-all"></span>
	<span class="ion-ribbon-a"></span>
	<span class="ion-ribbon-b"></span>
	<span class="ion-sad"></span>
	<span class="ion-sad-outline"></span>
	<span class="ion-scissors"></span>
	<span class="ion-search"></span>
	<span class="ion-settings"></span>
	<span class="ion-share"></span>
	<span class="ion-shuffle"></span>
	<span class="ion-skip-backward"></span>
	<span class="ion-skip-forward"></span>
	<span class="ion-social-android"></span>
	<span class="ion-social-android-outline"></span>
	<span class="ion-social-angular"></span>
	<span class="ion-social-angular-outline"></span>
	<span class="ion-social-apple"></span>
	<span class="ion-social-apple-outline"></span>
	<span class="ion-social-bitcoin"></span>
	<span class="ion-social-bitcoin-outline"></span>
	<span class="ion-social-buffer"></span>
	<span class="ion-social-buffer-outline"></span>
	<span class="ion-social-chrome"></span>
	<span class="ion-social-chrome-outline"></span>
	<span class="ion-social-codepen"></span>
	<span class="ion-social-codepen-outline"></span>
	<span class="ion-social-css3"></span>
	<span class="ion-social-css3-outline"></span>
	<span class="ion-social-designernews"></span>
	<span class="ion-social-designernews-outline"></span>
	<span class="ion-social-dribbble"></span>
	<span class="ion-social-dribbble-outline"></span>
	<span class="ion-social-dropbox"></span>
	<span class="ion-social-dropbox-outline"></span>
	<span class="ion-social-euro"></span>
	<span class="ion-social-euro-outline"></span>
	<span class="ion-social-facebook"></span>
	<span class="ion-social-facebook-outline"></span>
	<span class="ion-social-foursquare"></span>
	<span class="ion-social-foursquare-outline"></span>
	<span class="ion-social-freebsd-devil"></span>
	<span class="ion-social-github"></span>
	<span class="ion-social-github-outline"></span>
	<span class="ion-social-google"></span>
	<span class="ion-social-google-outline"></span>
	<span class="ion-social-googleplus"></span>
	<span class="ion-social-googleplus-outline"></span>
	<span class="ion-social-hackernews"></span>
	<span class="ion-social-hackernews-outline"></span>
	<span class="ion-social-html5"></span>
	<span class="ion-social-html5-outline"></span>
	<span class="ion-social-instagram"></span>
	<span class="ion-social-instagram-outline"></span>
	<span class="ion-social-javascript"></span>
	<span class="ion-social-javascript-outline"></span>
	<span class="ion-social-linkedin"></span>
	<span class="ion-social-linkedin-outline"></span>
	<span class="ion-social-markdown"></span>
	<span class="ion-social-nodejs"></span>
	<span class="ion-social-octocat"></span>
	<span class="ion-social-pinterest"></span>
	<span class="ion-social-pinterest-outline"></span>
	<span class="ion-social-python"></span>
	<span class="ion-social-reddit"></span>
	<span class="ion-social-reddit-outline"></span>
	<span class="ion-social-rss"></span>
	<span class="ion-social-rss-outline"></span>
	<span class="ion-social-sass"></span>
	<span class="ion-social-skype"></span>
	<span class="ion-social-skype-outline"></span>
	<span class="ion-social-snapchat"></span>
	<span class="ion-social-snapchat-outline"></span>
	<span class="ion-social-tumblr"></span>
	<span class="ion-social-tumblr-outline"></span>
	<span class="ion-social-tux"></span>
	<span class="ion-social-twitch"></span>
	<span class="ion-social-twitch-outline"></span>
	<span class="ion-social-twitter"></span>
	<span class="ion-social-twitter-outline"></span>
	<span class="ion-social-usd"></span>
	<span class="ion-social-usd-outline"></span>
	<span class="ion-social-vimeo"></span>
	<span class="ion-social-vimeo-outline"></span>
	<span class="ion-social-whatsapp"></span>
	<span class="ion-social-whatsapp-outline"></span>
	<span class="ion-social-windows"></span>
	<span class="ion-social-windows-outline"></span>
	<span class="ion-social-wordpress"></span>
	<span class="ion-social-wordpress-outline"></span>
	<span class="ion-social-yahoo"></span>
	<span class="ion-social-yahoo-outline"></span>
	<span class="ion-social-yen"></span>
	<span class="ion-social-yen-outline"></span>
	<span class="ion-social-youtube"></span>
	<span class="ion-social-youtube-outline"></span>
	<span class="ion-soup-can"></span>
	<span class="ion-soup-can-outline"></span>
	<span class="ion-speakerphone"></span>
	<span class="ion-speedometer"></span>
	<span class="ion-spoon"></span>
	<span class="ion-star"></span>
	<span class="ion-stats-bars"></span>
	<span class="ion-steam"></span>
	<span class="ion-stop"></span>
	<span class="ion-thermometer"></span>
	<span class="ion-thumbsdown"></span>
	<span class="ion-thumbsup"></span>
	<span class="ion-toggle"></span>
	<span class="ion-toggle-filled"></span>
	<span class="ion-transgender"></span>
	<span class="ion-trash-a"></span>
	<span class="ion-trash-b"></span>
	<span class="ion-trophy"></span>
	<span class="ion-tshirt"></span>
	<span class="ion-tshirt-outline"></span>
	<span class="ion-umbrella"></span>
	<span class="ion-university"></span>
	<span class="ion-unlocked"></span>
	<span class="ion-upload"></span>
	<span class="ion-usb"></span>
	<span class="ion-videocamera"></span>
	<span class="ion-volume-high"></span>
	<span class="ion-volume-low"></span>
	<span class="ion-volume-medium"></span>
	<span class="ion-volume-mute"></span>
	<span class="ion-wand"></span>
	<span class="ion-waterdrop"></span>
	<span class="ion-wifi"></span>
	<span class="ion-wineglass"></span>
	<span class="ion-woman"></span>
	<span class="ion-wrench"></span>
	<span class="ion-xbox"></span>
	
	<!--  -->
	<span class="ion-amazon"></span>
	
	<span class="icon-results__cell mouseOff"><svg><use xlink:href="#logo-amazon"></use></svg></span>
	<span class="icon-results__cell mouseOff"><svg><use xlink:href="#logo-amplify"></use></svg></span><span class="icon-results__cell mouseOff"><svg><use xlink:href="#logo-android"></use></svg></span><span class="icon-results__cell mouseOff"><svg><use xlink:href="#logo-angular"></use></svg></span><span class="icon-results__cell mouseOff"><svg><use xlink:href="#logo-apple"></use></svg></span><span class="icon-results__cell mouseOff"><svg><use xlink:href="#logo-apple-appstore"></use></svg></span><span class="icon-results__cell mouseOff"><svg><use xlink:href="#logo-bitbucket"></use></svg></span><span class="icon-results__cell mouseOff"><svg><use xlink:href="#logo-bitcoin"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-buffer"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-capacitor"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-chrome"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-closed-captioning"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-codepen"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-css3"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-designernews"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-dribbble"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-dropbox"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-edge"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-electron"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-euro"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-facebook"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-firebase"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-firefox"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-flickr"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-foursquare"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-github"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-google"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-google-playstore"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-hackernews"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-html5"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-instagram"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-ionic"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-ionitron"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-javascript"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-laravel"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-linkedin"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-markdown"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-no-smoking"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-nodejs"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-npm"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-octocat"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-pinterest"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-playstation"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-pwa"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-python"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-react"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-reddit"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-rss"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-sass"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-skype"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-slack"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-snapchat"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-stackoverflow"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-steam"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-stencil"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-tumblr"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-tux"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-twitch"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-twitter"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-usd"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-vimeo"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-vk"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-vue"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-web-component"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-whatsapp"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-windows"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-wordpress"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-xbox"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-xing"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-yahoo"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-youtube"></use></svg></span><span class="icon-results__cell"><svg><use xlink:href="#logo-yen"></use></svg></span>
</div>

<script>
	function ico_open ( name ) {
		window.curIdx = name;
		$('.pop-ico').addClass('active');
		$('.popup-bg').addClass('active');
	}

	$(function() {
		$('.pop-ico span').on('click', function(){
			var className = $(this).attr('class');
			var curIdx = window.curIdx;

			if (className.trim() == '' || curIdx.trim() == '') {
				console.warn('필수 값이 없습니다.');
				return false;
			}

			$('[name="' + curIdx + '"]').val(className)
				.next('span').attr('class', className);
			window.curIdx = '';
			$('.pop-ico').removeClass('active');
			$('.popup-bg').removeClass('active');
		})
		$('.popup-bg').on('click', function(){
			window.curIdx = '';
			$('.pop-ico').removeClass('active');
			$('.popup-bg').removeClass('active');
		})
	})
</script>