/*
	공통으로 사용하는 script 를 저장하는 파일입니다.
	Auth : 엄익민 (NEXTBIZ)
*/
/* @ cc_on _d = document; eval(‘var document = _d’) @*/

// 글자수 제한.
function in_ti(word, length) {
	if(word.length > length) { word = word.substring(0, length) + ".."; }	
	document.write(word);
}

// launchCenter(url, name, width, height, att)
function launchCenter(url, name, width, height, att) 
{
  var str = "height=" + height + ",innerHeight=" + height;
  str += ",width=" + width + ",innerWidth=" + width;
  if (window.screen) {
	var ah = screen.availHeight - 30;
	var aw = screen.availWidth - 10;

	var xc = (aw - width) / 2;
	var yc = (ah - height) / 2;

	str += ",left=" + xc + ",screenX=" + xc;
	str += ",top=" + yc + ",screenY=" + yc;
	str += "," + att
  }
  return window.open(url, name, str);
}

// keyCode 가 13 일 경우 tab.
function enterTab(e, FocusName) {
  var focusname_obj = document.getElementsByName(FocusName);
  var ev = (window.event) ? window.event.keyCode : e.keyCode;

  if (ev == 13) { eval(focusname_obj[0].focus()); }
}

// keyCode 가 13 일 경우만 submit
function enterSubmit(e, SubmitName) {
	var ev = (window.event) ? window.event.keyCode : e.keyCode;

	if (ev == 13) {
		if(SubmitName != "") { eval(SubmitName + "()"); }
	}
}

// 준비중
function ready() {
	window.alert("해당메뉴는 서비스 준비중입니다.");
}

// add event listener (cf. onload)
function addLoadEvent(func)
{
	var oldonload = window.onload;
	window.onload = (typeof window.onload != 'function') ? func : function() {
		if (oldonload) { oldonload(); }
		func();
	}
}

// mailto : 단순메일링크 생성
function mailTo(mailAdd)
{
	if ( confirm(mailAdd+" 로 이메일을 발송하시겠습니까?") ) {
		window.location.href = "mailto:"+mailAdd;
	}
}

// 이미지 미리보기 (이미지만 보임)
function imgPreWin(picName, windowName, windowWidth, windowHeight)
{
	var winHandle = window.open("", "_blank","left=0, top=0,toolbar=no,scrollbars=no,resizable=no,width=" + windowWidth + ",height=" + windowHeight);
	if (winHandle != null)
	{
		var htmlString = "<html><head><title>"+windowName+"</title></head>"
			+ "<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>"
			+ "<a href=javascript:window.close()><img src='" + picName + "' border='0' alt='창 닫기'></a>"
			+ "</body></html>";
		winHandle.document.open();
		winHandle.document.write(htmlString);
		winHandle.document.close();
	}
	if(winHandle != null) winHandle.focus();
}

// Email 형식 chk.
function emailChk(elID)
{
	var validFlag = false;
	re=/^[0-9a-zA-Z-_.]*@[0-9a-zA-Z-_.]*[.][a-zA-Z]*$/i;
	validFlag = ( elID.length<6 || !re.test(elID) ) ? false : true;
	return validFlag;
}

// 파일이 존재하지 않을 경우 return msg.
function noFileMsg() {
	alert("파일이 실제 하지 않습니다.\n\n관리자에게 문의하여 주시기 바랍니다.");
}

// onkeydown event 발생시에 focus 이동
function moveFocus(elID1, len, elID2) {
	if (elID1 && elID2) {
		if ( elID1.length >= len) elID2.focus();
	}
}

// 날짜체크
function isJDate(ymd) {
	var validFlag = true;

	if (ymd.split("-").length != 3) validFlag = false;

	yy = ymd.split("-")[0];
	mm = ymd.split("-")[1];
	dd = ymd.split("-")[2];

	if (isNaN(yy)) validFlag = false;
	if ((yy < 1901) || (yy > 2078)) validFlag = false;
	if (isNaN(mm)) validFlag = false;
	if ((mm < 1) || (mm > 12)) validFlag = false;
	if (isNaN(dd)) validFlag = false;

	if (mm == 1 || mm == 3 || mm == 5 || mm == 7 || mm == 8 || mm == 10 || mm == 12) {
		kk = 31;
	} else if (mm == 4 || mm == 6 || mm == 9 || mm == 11) {
		kk = 30;
	} else if ((yy % 4 == 0 && yy % 100 != 0) || (yy % 400 == 0)) {
		kk = 29;
	} else {
		kk = 28;
	}
	
	if ((dd < 1) || (dd > kk)) validFlag = false;

	return validFlag;
}


// comma 삽입
function commaIn(number) {
	len = number.length;
	s = Math.floor(len / 3);
	t = len % 3;
 
	if(t == 0) {
		t = 3;
		s--;
	}
 
	r = number.substr(0, t);
 
	for(i = 0; i < s; i++) { r += "," + number.substr(3 * i + t, 3); }
	return r;
}

// chk : is numeric
function isNumber(s) {
	s += ''; // 문자열로 변환
	s = s.replace(/^\s*|\s*$/g, ''); // 좌우 공백 제거
	if (s == '' || isNaN(s)) return 0;
	return s;
}

// quick menu
function initMoving(target, position, topLimit, btmLimit) {
	if (!target)
		return false;
 
	var obj = target;
	obj.initTop = position;
	obj.topLimit = topLimit;
	obj.bottomLimit = document.documentElement.scrollHeight - btmLimit;
 
	obj.style.position = "absolute";
	obj.top = obj.initTop;
	obj.left = obj.initLeft;
 
	if (typeof(window.pageYOffset) == "number") {
		obj.getTop = function() {
			return window.pageYOffset;
		}
	} else if (typeof(document.documentElement.scrollTop) == "number") {
		obj.getTop = function() {
			return document.documentElement.scrollTop;
		}
	} else {
		obj.getTop = function() {
			return 0;
		}
	}
 
	if (self.innerHeight) {
		obj.getHeight = function() {
			return self.innerHeight;
		}
	} else if(document.documentElement.clientHeight) {
		obj.getHeight = function() {
			return document.documentElement.clientHeight;
		}
	} else {
		obj.getHeight = function() {
			return 100;
		}
	}
 
	obj.move = setInterval(function() {
		pos = (obj.initTop > 0) ? obj.getTop() + obj.initTop : obj.getTop() + obj.getHeight() + obj.initTop;
 
		if (pos > obj.bottomLimit) pos = obj.bottomLimit;
		if (pos < obj.topLimit) pos = obj.topLimit;
 
		interval = obj.top - pos;
		obj.top = obj.top - interval / 3;
		obj.style.top = obj.top + "px";
	}, 30)
}

// 간단한 img resize
function resizeImg(imgEl, imgX, imgY) {
	if ( imgEl && (!isNaN(imgX) && !isNaN(imgY)) ) {
		var imgXbo = false;

		if (imgEl.width > imgX) {
			imgEl.width = imgX;
			imgXbo = true;
		}
		if (imgEl.height > imgY) {
			if (imgXbo == true) imgEl.width = imgEl.width/(imgEl.height/imgY);
			imgEl.height = imgY;
		}
	}
}


// 아이디 형식 chk
function CheckChar(name) {
	strarr = new Array(name.value.length);

	if((name.value.charAt(0) < "a" || name.value.charAt(0) > "z") && (name.value.charAt(0) < "A" || name.value.charAt(0) > "Z")) {
		return true;
	}

	for(i = 0; i < name.value.length; i++) {
		strarr[i] = name.value.charAt(i)
		if(strarr[i] == " ")
			return true;
		else if((strarr[i] >= 0) && (strarr[i] <= 9))
			continue;
		else if((strarr[i] >= "a") && (strarr[i] <= "z"))
			continue;
		else if((strarr[i] >= "A") && (strarr[i] <= "Z"))
			continue;
		else {
			return true;
		}
	}		
	return false;
}


// URL 형식 chk
function CheckUrl(name) {
	strarr = new Array(name.value.length);

	for(i = 0; i < name.value.length; i++) {
		strarr[i] = name.value.charAt(i)
		if(strarr[i] == " ")
			return true;
		else if((strarr[i] >= 0) && (strarr[i] <= 9))
			continue;
		else if((strarr[i] >= "a") && (strarr[i] <= "z"))
			continue;
		else if((strarr[i] >= "A") && (strarr[i] <= "Z"))
			continue;
		else {
			return true;
		}
	}		
	return false;
}


//  resize window xbrowse
function resizeWin(x, y) {
	if (isNaN(x) || isNaN(y)) return;
	var len = (navigator.userAgent.toLowerCase().indexOf('chrome') > -1) ? 200 : 0;
	var t = setTimeout(function() {
			var innerWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
			var innerHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
			var targetWidth = x;
			var targetHeight = y;
			window.resizeBy(targetWidth-innerWidth, targetHeight-innerHeight);
		}, len);
}


// youtube
function flashYouTube(url,w,h){
	var flashStr=
	"<object width='"+w+"' height='"+h+"'>"+
	"<param name='movie' value='"+url+"'></param>"+
	"<param name='allowFullScreen' value='true'></param>"+
	"<param name='allowscriptaccess' value='always'></param>"+
	"<embed src='"+url+"' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='"+w+"' height='"+h+"'></embed>"+
	"</object>";
	document.write(flashStr);
}


/* 올리북 */
function ollybook(ID, addTitle) {
	window.open("http://zone.ollybook.com/" + ID + "/" + addTitle + "/", "OllyBook", "width=900,height=680,top=100,left=100,scrollbars=no");
}


// iframe auto resize
function iframe_autoresize(arg) {
	if (arg.src) {
		arg.height = eval(arg.name+".document.body.scrollHeight");
	}	
}


// hash 를 json 으로 반환 (jQuery 사용)
function hashToJSON()
{
	if (document.location.hash) {
		var tar = decodeURIComponent(document.location.hash);
		tar = tar.replace("#", "");
		var json = $.parseJSON(tar);
		return json;
	}
	else {
		return null;
	}
}

// json 을 string 으로 반환
function JSONtoString(object) {
	var results = [];
	for (var property in object) {
		var value = object[property];
		if (value)
			results.push('"'+property.toString()+'":"'+value+'"');
	}

	return '{' + results.join(',') + '}';
}


// set : file size unit & redefine value of the size
// set : size
function getSizeTxt(val)
{
	var ret = 0;
	if ( Math.floor(val/1048576)+1 >= 1000 ) {			// unit : GB
		ret = (Math.floor((val/1073741824 +0.5) *100) /100)+" GB";
	}
	else if ( Math.floor(val/1024)+1 >= 1000 ) {			// unit : MB
		ret = (Math.floor((val/1048576 +0.5) *100) /100)+" MB";
	}
	else if ( val >= 1000 ) {								// unit : KB
		ret = (Math.floor((val/1024 +0.5) *100) /100)+" KB";
	}
	else {													// unit : byte
		ret = (Math.floor((val +0.5) *100) /100)+" byte";
	}
	return ret;
}


var rfv = {
	chk:function(f) {
		if (!f instanceof Array || f.length != 2) return false;
		
		var $el = "";
		var f0 = f[0], f1 = f[1];
		for (var i in f0)
		{
			// case : class 로 전달된 경우 (checkbox 형식 확인)
			if (f0[i].indexOf('.cls_') > -1) {
				if ($(f0[i]+":checked").length <= 0) {
					alert(f1[i]+' 중 최소 하나이상 선택해 주세요.');
					$(f0[i]).get(0).focus();
					return false;
				}
			}
			// case : class 로 전달된 경우 (date 형식 확인)
			else if (f0[i].indexOf('.date_') > -1) {
				for (var j = 0; j < $(f0[i]).size(); j++) {
					if ($(f0[i] + ":eq(" + j + ")").val() == "") {
						alert(f1[i]+' 정보를 입력해 주세요.');
						$(f0[i] + ":eq(" + j + ")").focus();
						return false;
					}
				}
			}
			// case : 기본 ID 로 구분
			else {
				$el = $('#'+f0[i]);
				if ($el.val() == "") {
					alert(f1[i]+' 정보를 입력해 주세요.');
					$el.focus();
					return false;
				}
			}
		}
		return true;
	}
}

// create : form el.
function createForm(id, method, action, enctype, target, onsubmit, fs)
{
	if (id == '' || method == '' || action == '') return;

	// 객체가 이미 있을 경우 return
	if ($('#'+id).length > 0) return $('#'+id);

	var method = method.toLowerCase();
	if (method != 'get' && method != 'post') return;

	var f = document.createElement('form');
	f.id = id;
	f.name = id;
	f.method = method;
	f.action = action;
	
	if (enctype == 'Y') { f.enctype = "multipart/form-data"; }
	if (target != '') { f.target = target; }
	if (onsubmit != '') { f.setAttribute('onsubmit', onsubmit); }

	// fs 가 세쌍으로 묶여 있을 경우만(id+name+value)
	if (fs instanceof Array || fs.length == 3)
	{
		var fs0 = fs[0], fs1 = fs[1], fs2 = fs[2], i = '';
		for (var k in fs0)
		{
			i = document.createElement('input');
			i.type = "hidden";
			i.id = fs0[k];
			i.name = fs1[k];
			i.value = fs2[k];
			f.appendChild(i);
		}
	}

	return f;
}


// sns
function snsTo(sTarget, sUri, sDsc) {
	//sDsc = encodeURIComponent(sDsc);
	switch (sTarget) {
	case 'facebook':
		window.open('http://www.facebook.com/sharer.php?u=' + sUri + '&t=' + sDsc, 'snsTo','resizable=no,width=800,height=500');
		break;
	case 'twitter':
		window.open('http://twitter.com/share?url=' + sUri + '&text='
				+ sDsc, 'snsTo', 'width=600,height=300');
		break;
	}
}


function strip_tags(input, allowed) {
  //  discuss at: http://phpjs.org/functions/strip_tags/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Luke Godfrey
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //    input by: Pul
  //    input by: Alex
  //    input by: Marc Palau
  //    input by: Brett Zamir (http://brett-zamir.me)
  //    input by: Bobby Drake
  //    input by: Evertjan Garretsen
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Onno Marsman
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Eric Nagel
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Tomasz Wesolowski
  //  revised by: Rafał Kukawski (http://blog.kukawski.pl/)
  //   example 1: strip_tags('<p>Kevin</p> <br /><b>van</b> <i>Zonneveld</i>', '<i><b>');
  //   returns 1: 'Kevin <b>van</b> <i>Zonneveld</i>'
  //   example 2: strip_tags('<p>Kevin <img src="someimage.png" onmouseover="someFunction()">van <i>Zonneveld</i></p>', '<p>');
  //   returns 2: '<p>Kevin van Zonneveld</p>'
  //   example 3: strip_tags("<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>", "<a>");
  //   returns 3: "<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>"
  //   example 4: strip_tags('1 < 5 5 > 1');
  //   returns 4: '1 < 5 5 > 1'
  //   example 5: strip_tags('1 <br/> 1');
  //   returns 5: '1  1'
  //   example 6: strip_tags('1 <br/> 1', '<br>');
  //   returns 6: '1 <br/> 1'
  //   example 7: strip_tags('1 <br/> 1', '<br><br/>');
  //   returns 7: '1 <br/> 1'

  allowed = (((allowed || '') + '')
	.toLowerCase()
	.match(/<[a-z][a-z0-9]*>/g) || [])
	.join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
	commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
  return input.replace(commentsAndPhpTags, '')
	.replace(tags, function($0, $1) {
	  return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	});
}


function in_array(needle, haystack, argStrict) {
  //  discuss at: http://phpjs.org/functions/in_array/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: vlado houba
  // improved by: Jonas Sciangula Street (Joni2Back)
  //    input by: Billy
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //   example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
  //   returns 1: true
  //   example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
  //   returns 2: false
  //   example 3: in_array(1, ['1', '2', '3']);
  //   example 3: in_array(1, ['1', '2', '3'], false);
  //   returns 3: true
  //   returns 3: true
  //   example 4: in_array(1, ['1', '2', '3'], true);
  //   returns 4: false

  var key = '',
	strict = !! argStrict;

  //we prevent the double check (strict && arr[key] === ndl) || (!strict && arr[key] == ndl)
  //in just one for, in order to improve the performance 
  //deciding wich type of comparation will do before walk array
  if (strict) {
	for (key in haystack) {
	  if (haystack[key] === needle) {
		return true;
	  }
	}
  } else {
	for (key in haystack) {
	  if (haystack[key] == needle) {
		return true;
	  }
	}
  }

  return false;
}

function uniqid(prefix, more_entropy) {
  //  discuss at: http://phpjs.org/functions/uniqid/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //  revised by: Kankrelune (http://www.webfaktory.info/)
  //        note: Uses an internal counter (in php_js global) to avoid collision
  //        test: skip
  //   example 1: uniqid();
  //   returns 1: 'a30285b160c14'
  //   example 2: uniqid('foo');
  //   returns 2: 'fooa30285b1cd361'
  //   example 3: uniqid('bar', true);
  //   returns 3: 'bara20285b23dfd1.31879087'

  if (typeof prefix === 'undefined') {
    prefix = '';
  }

  var retId;
  var formatSeed = function(seed, reqWidth) {
    seed = parseInt(seed, 10)
      .toString(16); // to hex str
    if (reqWidth < seed.length) { // so long we split
      return seed.slice(seed.length - reqWidth);
    }
    if (reqWidth > seed.length) { // so short we pad
      return Array(1 + (reqWidth - seed.length))
        .join('0') + seed;
    }
    return seed;
  };

  // BEGIN REDUNDANT
  if (!this.php_js) {
    this.php_js = {};
  }
  // END REDUNDANT
  if (!this.php_js.uniqidSeed) { // init seed with big random int
    this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
  }
  this.php_js.uniqidSeed++;

  retId = prefix; // start with prefix, add current milliseconds hex string
  retId += formatSeed(parseInt(new Date()
    .getTime() / 1000, 10), 8);
  retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
  if (more_entropy) {
    // for more entropy we add a float lower to 10
    retId += (Math.random() * 10)
      .toFixed(8)
      .toString();
  }

  return retId;
}

// chk : is mobile (true: mobile, false: else)
var IS_MOBILE = function () {
	var check = false;
	(function(a,b){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
	return check;
}

/* 
 * 원본 : http://stackoverflow.com/questions/19999388/jquery-check-if-user-is-using-ie 
 * 수정본 : http://tonks.tistory.com/107 
 */ 
var IS_IE = function() {
	var word;
	var version = "N/A";

	var agent = navigator.userAgent.toLowerCase();
	var name = navigator.appName;

	// IE old version ( IE 10 or Lower )
	if ( name == "Microsoft Internet Explorer" ) word = "msie ";

	else {
		// IE 11
		if ( agent.search("trident") > -1 ) word = "trident/.*rv:";

		// IE 12  ( Microsoft Edge )
		else if ( agent.search("edge/") > -1 ) word = "edge/";
	}

	var reg = new RegExp( word + "([0-9]{1,})(\\.{0,}[0-9]{0,1})" );

	if (  reg.exec( agent ) != null  ) version = RegExp.$1 + RegExp.$2;

	return version;
}

// window width 를 기준으로 현재 디바이스를 지정
function getDevice() {
	var $html = $('html');
	var $w_w = $(this).width();

	if ($w_w <= 519) { $html.data('res', 'mobile_v'); }
	else if ($w_w <= 759) { $html.data('res', 'mobile_l'); }
	else if ($w_w <= 1023) { $html.data('res', 'tablet'); }
	else if ($w_w <= 1079) { $html.data('res', 'desktop'); }
	else { $html.data('res', 'desktop_w'); }
}

// extract url by given string
var extractDomain = function(url) {
	if (url == '') return;
	
	var patt = new RegExp(/^(file|gopher|news|nntp|telnet|https?|ftps?|sftp):\/\/([a-z0-9-]+\.)+[a-z0-9]{2,4}.*$/);
	var result = patt.test(url);

	// 외부 url
	if (result) {
		var domain;
		// find & remove protocol (http, ftp, etc.) and get domain
		if (url.indexOf("://") > -1) domain = url.split('/')[2];
		else domain = url.split('/')[0];

		// find & remove port number
		domain = domain.split(':')[0];
		return domain;
	}
	// 내부 url
	else {
		return document.domain;
	}
}
