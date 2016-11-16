$(document).ready(function(){
	$('.post-list .animated').hover(function(){
		$(this).addClass('pulse');
	},function(){
		$(this).removeClass('pulse');
	});
});

//列表图片剧中
function imgresize() {
	// IMAGE RESIZE
    $('.img-div').each(function() {
        var width = $(this).width();
        var height = $(this).height();
        var img_width = $('img',this).width();
        var img_height = $('img',this).height();

        if(height > img_height){
            ratio = height / img_height;
            ratio1 = width * ratio;
            $('img',this).css("height", height);
            $('img',this).css("width", ratio1);
            ratio2 = width - ratio1;
            ratio3 = ratio2 / 2;
            $('img',this).css("margin-left", ratio3);
        } else {
	        martop = (img_height - height)/2;
	        $('img',this).css("margin-top", -martop);
        }
    });
};
$(window).bind("load", function() {
    imgresize();
});

//内容页导航
$(function(){
	$.fn.scrollToTop2=function(){
		var scrollDiv2=$(this);
		//var height = $(window).height()-90;

		$(window).scroll(function(){
			if($(window).scrollTop()<90){
				$(scrollDiv2).removeClass("fixed-top")
			}else{
				$(scrollDiv2).addClass("fixed-top");
			}
		});
	}
});
$(function() {
	$("#header").scrollToTop2();
});

//平滑滚动到锚点
$("a.goto").click(function() {
        var gotop = $($(this).attr("href")).offset().top-76;
        $("html, body").animate({
            scrollTop: gotop + "px"
        }, {
            duration: 500,
            easing: "swing"
        });
        return false;
    });

		$(function(){

		  //判断浏览器是否支持placeholder属性
		  supportPlaceholder='placeholder'in document.createElement('input'),

		  placeholder=function(input){

		    var text = input.attr('placeholder'),
		    defaultValue = input.defaultValue,
				defaultType = input.attr('type');

		    if(!defaultValue && defaultType!='hidden'){
		      input.val(text).addClass("phcolor");
					input.attr('type','text');
		    }

		    input.focus(function(){
		      if(input.val() == text){
		        $(this).val("");
						input.attr('type',defaultType);
		      }
		    });


		    input.blur(function(){
		      if(input.val() == ""){
		        $(this).val(text).addClass("phcolor");
						input.attr('type','text');
		      }
		    });

		    //输入的字符不为灰色
		    input.keydown(function(){
		      $(this).removeClass("phcolor");
		    });
		  };

		  //当浏览器不支持placeholder属性时，调用placeholder函数
		  if(!supportPlaceholder){
		    $('input').each(function(){
		      text = $(this).attr("placeholder");
					placeholder($(this));
		    });
		  }

		});
