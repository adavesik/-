/** ******************************
 * Generate a Random Password
 ****************************** **/
function generatePassword(limit) {
	limit = limit || 6;
	var password = '';
	// You can add or remove any characters you wish between the two single quote marks (')
	// Do NOT use singe quote marks in your characters list (')
	var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!"$&=^*#_-@+,.';
	var list = chars.split('');
	var len = list.length,
	i = 0;
	do {
		i++;
		var index = Math.floor(Math.random() * len);
		password += list[index];
	}
	while (i < limit);
	// Return the newly generated password
	return password;
}

/** ******************************
 * Touch Support
 ****************************** **/
var supports = (function () {
    var d = document.documentElement,
        c = "ontouchstart" in window || navigator.msMaxTouchPoints;
    if (c) {
        d.className += " touch";
        return {
            touch: true
        }
    } else {
        d.className += " no-touch";
        return {
            touch: false
        }
    }
})();

/** ******************************
 * Cookies
 ****************************** **/
(function (a) {
    if (typeof define === "function" && define.amd) {
        define(["jquery"], a)
    } else {
        a(jQuery)
    }
}(function (c) {
    var a = /\+/g;

    function d(f) {
        if (b.raw) {
            return f
        }
        try {
            return decodeURIComponent(f.replace(a, " "))
        } catch (g) {}
    }
    function e(f) {
        if (f.indexOf('"') === 0) {
            f = f.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, "\\")
        }
        f = d(f);
        try {
            return b.json ? JSON.parse(f) : f
        } catch (g) {}
    }
    var b = c.cookie = function (n, m, r) {
        if (m !== undefined) {
            r = c.extend({}, b.defaults, r);
            if (typeof r.expires === "number") {
                var o = r.expires,
                    q = r.expires = new Date();
                q.setDate(q.getDate() + o)
            }
            m = b.json ? JSON.stringify(m) : String(m);
            return (document.cookie = [b.raw ? n : encodeURIComponent(n), "=", b.raw ? m : encodeURIComponent(m), r.expires ? "; expires=" + r.expires.toUTCString() : "", r.path ? "; path=" + r.path : "", r.domain ? "; domain=" + r.domain : "", r.secure ? "; secure" : ""].join(""))
        }
        var s = n ? undefined : {};
        var p = document.cookie ? document.cookie.split("; ") : [];
        for (var k = 0, h = p.length; k < h; k++) {
            var j = p[k].split("=");
            var f = d(j.shift());
            var g = j.join("=");
            if (n && n === f) {
                s = e(g);
                break
            }
            if (!n && (g = e(g)) !== undefined) {
                s[f] = g
            }
        }
        return s
    };
    b.defaults = {};
    c.removeCookie = function (g, f) {
        if (c.cookie(g) !== undefined) {
            c.cookie(g, "", c.extend({}, f, {
                expires: -1
            }));
            return true
        }
        return false
    }
}));

/** ******************************
 * Main Menu
 ****************************** **/
(function (a) {
    a.fn.dcAccordion = function (c) {
        var e = {
            classParent: "task-parent",
            classActive: "active",
            classArrow: "task-icon",
            classCount: "task-count",
            classExpand: "task-current-parent",
            eventType: "click",
            hoverDelay: 300,
            menuClose: true,
            autoClose: true,
            autoExpand: false,
            speed: "slow",
            saveState: true,
            disableLink: true,
            showCount: false,
            cookie: "task-accordion"
        };
        var c = a.extend(e, c);
        this.each(function (p) {
            var h = this;
            l();
            if (e.saveState == true) {
                d(e.cookie, h)
            }
            if (e.autoExpand == true) {
                a("li." + e.classExpand + " > a").addClass(e.classActive)
            }
            j();
            if (e.eventType == "hover") {
                var g = {
                    sensitivity: 2,
                    interval: e.hoverDelay,
                    over: o,
                    timeout: e.hoverDelay,
                    out: n
                };
                a("li a", h).hoverIntent(g);
                var f = {
                    sensitivity: 2,
                    interval: 1000,
                    over: m,
                    timeout: 1000,
                    out: i
                };
                a(h).hoverIntent(f);
                if (e.disableLink == true) {
                    a("li a", h).click(function (q) {
                        if (a(this).siblings("ul").length > 0) {
                            q.preventDefault()
                        }
                    })
                }
            } else {
                a("li a", h).click(function (q) {
                    $activeLi = a(this).parent("li");
                    $parentsLi = $activeLi.parents("li");
                    $parentsUl = $activeLi.parents("ul");
                    if (e.disableLink == true) {
                        if (a(this).siblings("ul").length > 0) {
                            q.preventDefault()
                        }
                    }
                    if (e.autoClose == true) {
                        k($parentsLi, $parentsUl)
                    }
                    if (a("> ul", $activeLi).is(":visible")) {
                        a("ul", $activeLi).slideUp(e.speed);
                        a("a", $activeLi).removeClass(e.classActive)
                    } else {
                        a(this).siblings("ul").slideToggle(e.speed);
                        a("> a", $activeLi).addClass(e.classActive)
                    }
                    if (e.saveState == true) {
                        b(e.cookie, h)
                    }
                })
            }
            function l() {
                $arrow = '<span class="' + e.classArrow + '"></span>';
                var q = e.classParent + "-li";
                a("> ul", h).show();
                a("li", h).each(function () {
                    if (a("> ul", this).length > 0) {
                        a(this).addClass(q);
                        a("> a", this).addClass(e.classParent).append($arrow)
                    }
                });
                a("> ul", h).hide();
                if (e.showCount == true) {
                    a("li." + q, h).each(function () {
                        if (e.disableLink == true) {
                            var r = parseInt(a("ul a:not(." + e.classParent + ")", this).length)
                        } else {
                            var r = parseInt(a("ul a", this).length)
                        }
                        a("> a", this).append(' <span class="' + e.classCount + '">(' + r + ")</span>")
                    })
                }
            }
            function o() {
                $activeLi = a(this).parent("li");
                $parentsLi = $activeLi.parents("li");
                $parentsUl = $activeLi.parents("ul");
                if (e.autoClose == true) {
                    k($parentsLi, $parentsUl)
                }
                if (a("> ul", $activeLi).is(":visible")) {
                    a("ul", $activeLi).slideUp(e.speed);
                    a("a", $activeLi).removeClass(e.classActive)
                } else {
                    a(this).siblings("ul").slideToggle(e.speed);
                    a("> a", $activeLi).addClass(e.classActive)
                }
                if (e.saveState == true) {
                    b(e.cookie, h)
                }
            }
            function n() {}
            function m() {}
            function i() {
                if (e.menuClose == true) {
                    a("ul", h).slideUp(e.speed);
                    a("a", h).removeClass(e.classActive);
                    b(e.cookie, h)
                }
            }
            function k(q, r) {
                a("ul", h).not(r).slideUp(e.speed);
                a("a", h).removeClass(e.classActive);
                a("> a", q).addClass(e.classActive)
            }
            function j() {
                a("ul", h).hide();
                $allActiveLi = a("a." + e.classActive, h);
                $allActiveLi.siblings("ul").show()
            }
        });

        function d(g, i) {
            var f = a.cookie(g);
            if (f != null) {
                var h = f.split(",");
                a.each(h, function (k, m) {
                    var j = a("li:eq(" + m + ")", i);
                    a("> a", j).addClass(e.classActive);
                    var l = j.parents("li");
                    a("> a", l).addClass(e.classActive)
                })
            }
        }
        function b(g, h) {
            var f = [];
            a("li a." + e.classActive, h).each(function (j) {
                var l = a(this).parent("li");
                var k = a("li", h).index(l);
                f.push(k)
            });
            a.cookie(g, f, {
                path: "/"
            })
        }
    }
})(jQuery);

$(document).ready(function () {

	/** ******************************
	 * Current Time
	 ****************************** **/
	setInterval(function() {
		var date = new Date(),
		time = date.toLocaleTimeString();
		$(".clock").html(time);
	}, 1000);
	 
	/** ******************************
	 * Alert Message Boxes
	 ****************************** **/
    $('.alertMsg .alert-close').each(function() {
        $(this).click(function(e) {
            e.preventDefault();
            $(this).parent().fadeOut("slow", function() {
                $(this).addClass('hidden');
            });
        });
    });

	/** ******************************
	* Activate Tool-tips
	****************************** **/
    $("[data-toggle='tooltip']").tooltip();

	/** ******************************
	* Activate Popovers
	****************************** **/
	$("[data-toggle='popover']").popover();

    $('.title').wrapInner("<span></span>");


    /** ******************************
	 * Primary Nav
	 ****************************** **/
    $('.primary-nav').dcAccordion({
        saveState: true,
        autoClose: true,
        disableLink: true,
        speed: "fast",
        showCount: false,
        autoExpand: false
    });


    /** ******************************
	 * Menu Toggle
	 ****************************** **/
    $('.toggle-menu').click(function () {
        $('html').toggleClass('menu-open');
        $('html').removeClass('search-open');
    });

    /** ******************************
	 * Search
	 ****************************** **/
    $('.toggle-search').click(function(e) {
		e.preventDefault();
        $('html').toggleClass('search-open');
        $('html').removeClass('menu-open');
    });
	
	/** ******************************
	 * Placeholders
	 ****************************** **/
    if (!Modernizr.input.placeholder) {
        $("input").each(function () {
            var a = $(this);
            if (a.val() == "" && a.attr("placeholder") != "") {
                a.val(a.attr("placeholder"));
                a.focus(function () {
                    if (a.val() == a.attr("placeholder")) {
                        a.val("")
                    }
                });
                a.blur(function () {
                    if (a.val() == "") {
                        a.val(a.attr("placeholder"))
                    }
                });
                $(a).closest("form").submit(function () {
                    var b = $(this);
                    if (!b.hasClass("placeholderPending")) {
                        $("input", this).each(function () {
                            var c = $(this);
                            if (c.val() == c.attr("placeholder")) {
                                c.val("")
                            }
                        });
                        b.addClass("placeholderPending")
                    }
                })
            }
        })
    }

});

/** ******************************
 * Search
 ****************************** **/
$(document).click(function (a) {
	/** ******************************
	 * Close Search
	 ****************************** **/
    if ($(a.target).parents().index($(".search-wrapper")) == -1) {
        $('html').removeClass('search-open');
    }
});
$(document).on("touchstart", function (a) {
    if ($(a.target).parents().index($(".search-wrapper")) == -1) {
        $('html').removeClass('search-open');
    }
});