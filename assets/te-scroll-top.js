/**
 * Description: Front end scroll to top feature javascript function That is from "goup" js from javascript library ( https://www.jqueryscript.net/demo/Highly-Customizable-jQuery-Back-To-Top-Plugin-go2top/ ).
 * Author: TeamEngineer.
 * Author URL: https://www.fiverr.com/mahmud_remal/
 */



 ( function ( a ) {
    function b(a, b, c) {
        if ("show" == b) switch (c) {
            case "fade":
                a.fadeIn();
                break;
            case "slide":
                a.slideDown();
                break;
            default:
                a.fadeIn()
        } else switch (c) {
            case "fade":
                a.fadeOut();
                break;
            case "slide":
                a.slideUp();
                break;
            default:
                a.fadeOut()
        }
    }

    function c(a) {
        var b = document.createElement("style");
        document.head.appendChild(b);
        for (var c = b.sheet, d = 0, e = a.length; e > d; d++) {
            var f = 1,
                g = a[d],
                h = a[d][0],
                i = "";
            "[object Array]" === Object.prototype.toString.call(g[1][0]) && (g = g[1], f = 0);
            for (var j = g.length; j > f; f++) {
                var k = g[f];
                i += k[0] + ":" + k[1] + (k[2] ? " !important" : "") + ";\n"
            }
            c.insertRule(h + "{" + i + "}", c.cssRules.length)
        }
    }
    a.goup = function(d) {
        var e = a.extend({
            location: "right",
            locationOffset: 150,
            bottomOffset: 100,
            containerSize: 40,
            containerRadius: 10,
            containerClass: "goup-container",
            alwaysVisible: !1,
            trigger: 250,
            entryAnimation: "fade",
            goupSpeed: "slow",
            hideUnderWidth: 500,
            second: .3,
            bcolor: "#eee",
            acolor: "#bbb",
            img: '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="36.1 192 576 372" enable-background="new 36.1 192 576 372" xml:space="preserve"><g><g transform="translate(1.000000, 2.000000)"><path d="M563,406c-7.2,0-14.5-1.6-21.5-5.1L323.1,291.7L104.5,400.9c-23.8,11.9-52.5,2.2-64.4-21.5    c-11.9-23.7-2.2-52.5,21.5-64.4l240-120c13.5-6.8,29.4-6.8,42.9,0l240,120c23.7,11.8,33.3,40.7,21.5,64.4    C597.6,396.3,580.6,406,563,406L563,406z"></path><path d="M563,562c-3.6,0-7.2-0.8-10.7-2.5L323.1,444.8L93.8,559.5c-11.8,5.9-26.3,1.1-32.2-10.7    c-5.9-11.8-1.1-26.2,10.8-32.2l240-120c6.8-3.4,14.7-3.4,21.5,0l240,120c11.9,5.9,16.7,20.3,10.8,32.2    C580.3,557.1,571.8,562,563,562L563,562z"></path></g></g></svg>'
        }, d);
        a("body").append( '<div class="' + e.containerClass + '" >' + e.img + '</div>' );
        var f = a("." + e.containerClass);
        "right" != e.location && "left" != e.location && (e.location = "right"), e.locationOffset < 0 && (e.locationOffset = 0), e.bottomOffset < 0 && (e.bottomOffset = 0), e.containerSize < 20 && (e.containerSize = 20), e.containerRadius < 0 && (e.containerRadius = 0), e.trigger < 0 && (e.trigger = 0), e.hideUnderWidth < 0 && (e.hideUnderWidth = 0);
        var g = [];
        g[0] = [], g[0][0] = "." + e.containerClass, g[0][1] = [], g[0][1][0] = "position", g[0][1][1] = "fixed", g[0][2] = [], g[0][2][0] = "width", g[0][2][1] = e.containerSize + 18 + "px", g[0][3] = [], g[0][3][0] = "height", g[0][3][1] = e.containerSize + "px", g[0][4] = [], g[0][4][0] = "cursor", g[0][4][1] = "pointer", g[0][5] = [], g[0][5][0] = "bottom", g[0][5][1] = e.bottomOffset + "px", g[0][6] = [], g[0][6][0] = e.location, g[0][6][1] = e.locationOffset + "px", g[0][7] = [], g[0][7][0] = "border-radius", g[0][7][1] = e.containerRadius + "px", g[0][8] = [], g[0][8][0] = "fill", g[0][8][1] = e.bcolor, g[0][9] = [], g[0][9][0] = "transition", g[0][9][1] = "fill " + e.second + "s", g[0][10] = [], g[0][10][0] = "-moz-transition", g[0][10][1] = "fill " + e.second + "s", g[0][11] = [], g[0][11][0] = "-webkit-transition", g[0][11][1] = "fill " + e.second + "s", g[0][12] = [], g[0][12][0] = "-o-transition", g[0][12][1] = "fill " + e.second + "s", g[0][13] = [], g[0][13][0] = "display", g[0][13][1] = "none", g[1] = [], g[1][0] = "." + e.containerClass + ":hover", g[1][1] = [], g[1][1][0] = "fill", g[1][1][1] = e.acolor, c(g);
        var h = !1;
        a(window).resize(function() {
            a(window).outerWidth() <= e.hideUnderWidth ? (h = !0, b(a(f), "hide", e.entryAnimation)) : (h = !1, a(window).trigger("scroll"))
        }), a(window).outerWidth() <= e.hideUnderWidth && (h = !0, a(f).hide()), e.alwaysVisible ? b(a(f), "show", e.entryAnimation) : a(window).scroll(function() {
            a(window).scrollTop() >= e.trigger && !h && b(a(f), "show", e.entryAnimation), a(window).scrollTop() < e.trigger && !h && b(a(f), "hide", e.entryAnimation)
        }), a(window).scrollTop() >= e.trigger && !h && b(a(f), "show", e.entryAnimation);
        var i = !0;
        a(f).add().on("click", function() {
            return i && (i = !1, a("html,body").animate({
                scrollTop: 0
            }, e.goupSpeed, function() {
                i = !0
            })), !1
        })
    }
} )( jQuery );