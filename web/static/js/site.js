/*jQuery lazyload plugin*/
(function(t) { t.fn.lazyload = function(e) { var n = { attr: "data-original", container: t(window), callback: t.noop } , i = t.extend({}, n, e || {}); i.cache = [], t(this).each(function() { var e = this.nodeName.toLowerCase() , n = t(this).attr(i.attr) , o = { obj: t(this), tag: e, url: n }; i.cache.push(o) }); var o = function(e) { t.isFunction(i.callback) && i.callback.call(e.get(0)) } , s = function() { var e = i.container.height(); contop = t(window).get(0) === window ? t(window).scrollTop() : i.container.offset().top, t.each(i.cache, function(t, n) { var i, s, r = n.obj, a = n.tag, l = n.url; r && (i = r.offset().top - contop, i + r.height(), (i >= 0 && e > i || s > 0 && e >= s) && (l ? "img" === a ? o(r.attr("src", l).css("opacity", 0).animate({ opacity: 1 }, 600)) : r.load(l, {}, function() { o(r) }) : o(r), n.obj = null )) }) } ; s(), i.container.bind("scroll", s) } }) (jQuery);
$(function(){
    var baseUrl = $(document.body).attr("data-baseurl"),$body=  $(document.body);
    $("img.lazy").lazyload({ threshold: 500, effect: "" });
    $.fn.tooltip && ($('[data-toggle="tooltip"]').tooltip());
    //$('#siteheader [data-toggle="popover"]').popover();
    $body.on("click","[data-type=number] .input-group-addon",function(){
        var $btn=$(this),$num = $btn.parent().find(".number"),max = $btn.parent().attr("data-max")||-1,
            newValue = (parseInt($num.val(),10)||0)+($btn.is(".plus")?1:-1);
        max!=-1 && (newValue= Math.min(max,newValue));
        (newValue<1) && (newValue=1);
        $num.val()!=newValue && ($num.val(newValue).trigger("change"));
    }).on("keydown","[data-type=number]",function(e){
        ((e.keyCode>=48 && e.keyCode<=57 && (!e.shiftKey) ) || ($.inArray(e.keyCode,[8,9,13,17,18,20,27,35,36,37,38,39,40,46])!=-1  ) || e.ctrlKey ) || (e.preventDefault());
    }).on("paste","[data-type=number]",function(e){
        var pastedText = undefined;
        if (window.clipboardData && window.clipboardData.getData) { // IE
           pastedText = window.clipboardData.getData('Text');
        } else if (e.clipboardData && e.clipboardData.getData) {
           pastedText = e.clipboardData.getData('text/plain');
        }
        var $num = $(this).parent().find(".number");
        e.preventDefault();
        $num.val(pastedText.replace(/[^0-9]/g,""));
        /*setTimeout(function(){
            $num.val($num.val().replace(/[^0-9]/g,""));
        },50);*/
    });
    //cart
    $body.on("updatecart",function(){
        $("#cartAmount").length && ($.getJSON(baseUrl+"web/cart/items-count").then(function(res){
            $("#cartAmount").html(res.data==0?"无":res.data);
        }));
    }).trigger("updatecart");
});
