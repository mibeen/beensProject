/*
 *
 * Nextbiz Widget Javascript V0.2
 * Author : hoyoengkim | Nextbiz
 * Pre : min jQuery 1.11
 *
 */

function nx_widget(){

	var _this = this;
		this.widget_wrap = $('#widget-form');

//새 위젯 추가
	$(document).on('click', 'button[name=addWidget]', function(e){
		_this.addWidget($(this));
	})
	$(document).on('click', 'button[name=addrows]', function(e){
		_this.addRows($(this));
	})

	$(document).on('change', 'input[name="size[]"]', function(e){
		_this.col_resize($(this));
	})

	$(document).on('click', 'button.btn-close', function(e){
		console.log('close!');
		_this.widget_close($(this));
	})

	//위젯 추가시, 새로운 cogs 추가.
	_this.addCogs();

}

/*
 * 함수 정의 리스트
 */

// 위젯 추가 함수
nx_widget.prototype.addWidget = function($this){

	var _this    = this;
	var _btn     = $this;
	var nx_wrap  = _btn.closest('.nx-wrap');
	var row      = _btn.closest('.row');
	var template = this.template('wedget');
	var cur_num  = this.row_calc(row, 'addbtn');

	if(cur_num){
		nx_wrap.after(template);
	}

	_this.allTitleChange();
	_this.addCogs();

}

//열 추가 함수
nx_widget.prototype.addRows = function($this){

	var _this    = this;
	var _btn     = $this;
	var template = this.template('rows');

	_btn.after(template);

	_this.allTitleChange();
	_this.addCogs();

}

// 위젯 추가 시의 템플릿 모음
nx_widget.prototype.template = function($str){

	if($str == 'undefined'){
		$str = 'wedget';
	}

	var _this = this;

	var templateA = "<div class=\"col-md-4 nx-wrap ui-sortable-handle\"><input type=\"hidden\" name=\"id[]\" value=\"\"><input type=\"hidden\" name=\"row[]\" value=\"\"><div class=\"widget-box\"><div class=\"text-right\"><button type=\"button\" class=\"btn btn-default btn-close\"><i class=\"fa fa-times-circle\"></i></button></div><div><label for=\"\" class=\"col-md-3\">위젯 이름</label><input type=\"text\" name=\"title[]\" value=\"\" class=\"col-md-9\"><div class=\"clearfix\"></div></div><div class=\"hidden\"><label for=\"data\" class=\"col-md-3\">데이터</label><input type=\"text\" name=\"data[]\" value=\"\" class=\"col-md-9\" placeholder=\"DB관련입니다. 입력하지 않으면 자동으로 들어갑니다.\"><div class=\"clearfix\"></div></div><div><label for=\"name\" class=\"col-md-3\">위젯 종류</label><select name=\"name[]\" id=\"name\" class=\"col-md-9\"><option value=\"basic-category\">basic-category</option><option value=\"basic-keyword\">basic-keyword</option><option value=\"basic-member\">basic-member</option><option value=\"basic-outlogin\">basic-outlogin</option><option value=\"basic-poll\">basic-poll</option><option value=\"basic-post-gallery\">basic-post-gallery</option><option value=\"basic-post-garo\">basic-post-garo</option><option value=\"basic-post-list\">basic-post-list</option><option value=\"basic-post-mix\">basic-post-mix</option><option value=\"basic-post-sero\">basic-post-sero</option><option value=\"basic-post-slider\">basic-post-slider</option><option value=\"basic-post-ticker\">basic-post-ticker</option><option value=\"basic-post-webzine\">basic-post-webzine</option><option value=\"basic-sidebar\">basic-sidebar</option><option value=\"basic-title\">basic-title</option><option value=\"basic-outlogin-popup\">basic-outlogin-popup</option><option value=\"basic-title-admin\">basic-title-admin</option><option value=\"nx-post-gallery\">nx-post-gallery</option><option value=\"nx-post-gallery-chucheon\">nx-post-gallery-chucheon</option><option value=\"nx-post-image-banner\">nx-post-image-banner</option><option value=\"nx-post-notice\">nx-post-notice</option><option value=\"nx-rolling-banner\">nx-rolling-banner</option></select><div class=\"clearfix\"></div></div><div><label for=\"option\" class=\"col-md-3\">옵션</label><input type=\"text\" name=\"option[]\" value=\"\" class=\"col-md-9\"><div class=\"clearfix\"></div></div><div><label for=\"link\" class=\"col-md-3\">링크</label><input type=\"text\" name=\"link[]\" value=\"\" class=\"col-md-5\"><select name=\"target[]\" id=\"target\"><option value=\"_blank\">_blank</option><option value=\"_self\">_self</option><option value=\"_parent\">_parent</option><option value=\"_top\">_top</option></select><div class=\"clearfix\"></div></div><div><label for=\"size\" class=\"col-md-3\">사이즈 : (1199px~)</label><input type=\"number\" name=\"size[]\" value=\"4\" class=\"col-md-2\"><div class=\"col-md-2\">/12</div><div class=\"clearfix\"></div></div><div><label for=\"respon_md\" class=\"col-md-3\">사이즈 : (991px~)</label><input type=\"number\" name=\"respon_xs[]\" value=\"4\" class=\"col-md-2\"><div class=\"col-md-2\">/12</div><div class=\"clearfix\"></div></div><div><label for=\"respon_sm\" class=\"col-md-3\">사이즈 : (767px~)</label><input type=\"number\" name=\"respon_xs[]\" value=\"4\" class=\"col-md-2\"><div class=\"col-md-2\">/12</div><div class=\"clearfix\"></div></div><div><label for=\"respon_xs\" class=\"col-md-3\">사이즈 : (480px~)</label><input type=\"number\" name=\"respon_xs[]\" value=\"4\" class=\"col-md-2\"><div class=\"col-md-2\">/12</div><div class=\"clearfix\"></div></div><div class=\"btn-box\"><button type=\"button\" name=\"addWidget\" class=\"btn btn-primary col-md-12\">새 위젯 추가</button></div><div class=\"clearfix\"></div></div></div>";

	var templateB = "<div class=\"clearfix\"></div><div class=\"row\"><h3 class=\"title\">추가된 열</h3><div class=\"nx-dragable-wrap ui-sortable\">" + templateA + "</div></div><button type=\"button\" name=\"addrows\" class=\"col-md-12 btn btn-danger\">새 열 추가</button>";

	if($str == 'undefined'){
		alert('인수가 없습니다!');
		return false
	}

	switch($str){
		case 'wedget' :
			return templateA;
			break;

		case 'rows' :
			return templateB;
			break;

		default :
			return templateA;
			break;

	}
}

//한 열의 총 개수 확인
nx_widget.prototype.row_calc = function($row, $mode){

	var mode = typeof $mode == undefined ? 'default' : $mode;

	var _this = this;
	var rows  = $row.find('input[name="size[]"]');
	var sums  = 0;
	//console.log(rows);

	for(var i=0;i<rows.length;i++){
		//console.log(rows[i].value);
		sums += parseInt(rows[i].value);
	}

	switch(mode){
		case 'addbtn' :
		if(sums > 8){
			alert('최소 4칸을 남겨주세요. "사이즈" 항목을 조절하시면 됩니다. 한 열에는 총 12개의 공간만 활용할 수 있습니다.');
			return false;
		}
		break;

		default :
		if(sums > 12){
			alert('총 너비를 초과하였습니다.');
			return false;
		}

	}

	return true
}

nx_widget.prototype.col_resize = function(element){

	var _this = this;
	var rows  = element.closest('.nx-wrap');
	var cls   = rows.attr('class');//.split(' ');
	var size  = element[0].value;

	var row      = element.closest('.row');
	var cur_num  = this.row_calc(row);

	var inA   = cls.indexOf('col-md-');
	var inAs  = cls.substring(inA, inA+9);
		inAs  = inAs.trim();

		console.log(inAs);

	if(size > 12){
		alert('12보다 큰 값을 입력할 수 없습니다!');
		return false;
	}else if(size < 4){
		alert('4보다 작은 값을 입력할 수 없습니다.');
		return false;
	}

	rows.removeClass(inAs).addClass('col-md-' + size);

}

nx_widget.prototype.widget_close = function($element){

	var _this  = this;
	var btn    = $element;

	var widget = btn.closest('.nx-wrap');
	var rows   = btn.closest('.row');

	var w_num  = _this.calc_widget(widget);

	if(w_num <= 1){
		var msg1 = confirm('위젯이 한 개도 없으면 열이 삭제됩니다. 계속하시겠습니까?');
		if(msg1){
			rows.next('button').detach();
			rows.detach();
		}
	}else{
		var msg1 = confirm('위젯을 삭제하시겠습니까?');
		if(msg1){
			widget.detach();
		}
	}

	_this.allTitleChange();

}

nx_widget.prototype.calc_widget = function($widget){

	var _this  = this;
	var widget = $widget;

	var w_leng = widget.closest('.nx-dragable-wrap').find('.nx-wrap').length;
	return w_leng;

}

nx_widget.prototype.allTitleChange = function(){

	var _this = this;
	var rows = $('#widget-form .row');
	rows.each(function(){
		var idx = $('#widget-form .row').index($(this));
		$(this).find('.title').text( (idx + 1) + '번째 열');
	})
}

//cogs 추가,
//급하게 만들었기에 수정하는게 필요. 코드 리팩토링 합시다.

nx_widget.prototype.addCogs = function(){

	var _this = this;

	$(document).find('.widget_cogs').detach();

	$(document).find('.nx-wrap').each(function(){
		var nx_data = $(this).find('input[name=\'data[]\']').val();
		var wname   = $(this).find('select[name=\'name[]\']').val();
		var thema   = $('input[name=thema]').val();
		if( nx_data.length > 1 ){
			$(this).find('.btn-box').prepend('<a href=\'/bbs/widget.setup.php?wid=' + nx_data + '&wname=' + wname +'&thema=' + thema + '&opt=height%3D260px&mopt=auto%3D0\' class=\'widget_cogs btn btn-success col-md-12\' style=\'margin-bottom:5px;\' >설정</a>')
		}
	})
}
