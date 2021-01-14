function mid_slider($target, $speed){

  this.speed = $speed == 'undefined' ? 4000 : parseInt($speed);

  this.box = $($target);
  this.ul = this.box.find('ul');

  this.clone = this.ul.find('li').clone(true).addClass('clone');
  this.clone2 = this.ul.find('li').clone(true).addClass('clone');
  this.ul.append(this.clone);
  this.ul.append(this.clone2);

  var _this = this;

  // 이미지 비율 설정
  this.img_perW = 1200;
  this.img_perH = 675;

  _this.play();

  $(window).resize(function(){
    _this.winWidth = parseInt($(window).outerWidth());
    if(_this.winWidth < 1200){
      _this.setSize();
    }else{
      _this.play();
    }
  })

  _this.winWidth = parseInt($(window).outerWidth());
  if(_this.winWidth < 1200){
    _this.setSize();
  }else{
    _this.play();
  }



  //_this.setInt = setInterval(function(){_this.left()},_this.speed);

  $(document).on('click', $target + ' .btn-left', function(e){
      //clearInterval(_this.setInt);
      //_this.setInt = setInterval(function(){_this.left()},_this.speed);
      _this.left();
  })

  $(document).on('click', $target + ' .btn-right', function(e){
      _this.right();
      //clearInterval(_this.setInt);
      //_this.setInt = setInterval(function(){_this.left()},_this.speed);
  })


}



mid_slider.prototype.play = function(){
  var _this = this;


  _this.img = _this.ul.find('img');
  _this.imgLeng = _this.img.length;
  //_this.ul_width = _this.getWidth();
  _this.ul_width = (100 * _this.imgLeng) + '%';
  _this.li_width = (100 / _this.imgLeng) + '%';

  _this.ul.find('li').css('width', _this.li_width);

  var img0 = parseInt(_this.img.eq(0).width());
  //_this.ul.width(img0).height('auto');

  _this.ul.width(_this.ul_width).css('margin-left', '-200%');

  _this.imgSize();

}

mid_slider.prototype.getWidth = function(){
  var _this = this;

  var totalWidth = 0;

  _this.img.each(function(){
    totalWidth += parseInt($(this).outerWidth());
  })

  return totalWidth;

}

mid_slider.prototype.left = function(){
  var _this = this;

  var imgLeft = parseInt(_this.ul.find('img').eq(0).width());

  _this.ul.stop(false,true).animate({
    'margin-left' : '-=' + imgLeft
  },1000, function(){
    _this.ul.find('li').eq(0).appendTo(_this.ul);
    _this.ul.css('margin-left','-200%');
  })
}

mid_slider.prototype.right = function(){
  var _this = this;

  var imgLeft = parseInt(_this.ul.find('img').eq(0).width());

  _this.ul.find('li').last(0).prependTo(_this.ul);
  _this.ul.css('margin-left','-300%');

  _this.ul.stop().animate({
    'margin-left' : '+=' + imgLeft
  },1000, function(){
    _this.ul.css('margin-left','-200%');
  })
}

mid_slider.prototype.setSize = function(){
  var _this = this;
  //_this.img.outerWidth(_this.winWidth);

  //_this.ul.outerWidth(_this.winWidth).outerHeight(parseInt(_this.img.eq(0).height()));

  _this.imgSize();
  
}

mid_slider.prototype.imgSize = function(){
  var _this = this;


  //이미지 각각에 높이 지정해준다.
  _this.ul.find('li').each(function(){
    var fix_width = parseInt($(this).width());
    var fix_height = (fix_width * _this.img_perH) / _this.img_perW;
    fix_height = Math.round(fix_height);
    //console.log(fix_height);
    $(this).height(fix_height);
  })


}
