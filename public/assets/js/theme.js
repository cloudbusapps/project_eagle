if ("undefined" == typeof jQuery)
    throw new Error("jQuery plugins need to be before this file");
function toggleFullScreen(e) {
    (void 0 !== document.fullScreenElement &&
        null === document.fullScreenElement) ||
    (void 0 !== document.msFullscreenElement &&
        null === document.msFullscreenElement) ||
    (void 0 !== document.mozFullScreen && !document.mozFullScreen) ||
    (void 0 !== document.webkitIsFullScreen && !document.webkitIsFullScreen)
        ? e.requestFullScreen
            ? e.requestFullScreen()
            : e.mozRequestFullScreen
            ? e.mozRequestFullScreen()
            : e.webkitRequestFullScreen
            ? e.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT)
            : e.msRequestFullscreen && e.msRequestFullscreen()
        : document.cancelFullScreen
        ? document.cancelFullScreen()
        : document.mozCancelFullScreen
        ? document.mozCancelFullScreen()
        : document.webkitCancelFullScreen
        ? document.webkitCancelFullScreen()
        : document.msExitFullscreen && document.msExitFullscreen();
}

    $(function () {
        "use strict";
        document.documentElement;
        $(".menu-toggle").on("click", function () {
            $(".sidebar").toggleClass("open");
        }),
            $(".btn-right a").on("click", function () {
                $(".rightbar").toggleClass("open");
            }),
            $(".sidebar-mini-btn").on("click", function () {
                $(".sidebar").toggleClass("sidebar-mini");
            }),
            $(".hamburger-icon").on("click", function () {
                // $(this).toggleClass("active");
            }),
            $(".inbox .fa-star").on("click", function () {
                $(this).toggleClass("active");
            }),
            $(".main-search input").on("focus", function () {
                $(".search-result").addClass("show");
            }),
            $(".main-search input").on("blur", function () {
                setTimeout(function () {
                    $(".search-result").removeClass("show");
                }, 200);
            }),
            $(".font_setting input:radio").on("click", function () {
                var e = $("[name='" + this.name + "']")
                    .map(function () {
                        return this.value;
                    })
                    .get()
                    .join(" ");
                console.log(e), $("body").removeClass(e).addClass(this.value);
            }),
            $("#font_apply").on("click", function () {
                var e = $("#font_url").val(),
                    t = $("#font_family").val(),
                    c = $("head");
                $("body").css("font-family", t),
                    c.append(
                        '<link href="' +
                            e +
                            '" rel="stylesheet" data-type="font-url">'
                    ),
                    e &&
                        t &&
                        $(".font_setting input[name=font]").attr(
                            "disabled",
                            !0
                        );
            }),
            $("#font_cancel").on("click", function () {
                var e = $("link").filter(function () {
                    if ("font-url" == $(this)[0].getAttribute("data-type"))
                        return $(this)[0];
                });
                $("body").css("font-family", ""),
                    e[0].remove(),
                    $("#font_url").val(""),
                    $("#font_family").val("");
                $(".font_setting input[name=font]").attr("disabled", !1);
            }),
            $(".select-all.form-check-input").on("change", function () {
                var t = $(this).is(":checked"),
                    e = $(this)
                        .parent()
                        .parent()
                        .parent()
                        .parent()
                        .parent()
                        .find(".row-selectable");
                0 < e.length &&
                    e.each(function (e) {
                        $(this).find(".form-check-input")[0].checked = t;
                    });
            }),
            document.getElementById("NotificationsDiv") &&
                document
                    .getElementById("NotificationsDiv")
                    .addEventListener("click", function (e) {
                        e.stopPropagation();
                    });
        [].slice
            .call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            .map(function (e) {
                return new bootstrap.Tooltip(e);
            });
    }),
    $(function () {
        "use strict";
        let t = document.documentElement;
        $(".choose-skin li").on("click", function () {
            var e = $("body"),
                t = $(this);
            $(".choose-skin li.active").data("theme");
            $(".choose-skin li").removeClass("active"),
                t.addClass("active"),
                e.attr("data-luno", "theme-" + t.data("theme"));
        }),
            $("#primaryColorPicker")
                .colorpicker()
                .on("changeColor", function () {
                    t.style.setProperty(
                        "--primary-color",
                        $(this).colorpicker("getValue", "#ffffff")
                    );
                }),
            $("#secondaryColorPicker")
                .colorpicker()
                .on("changeColor", function () {
                    t.style.setProperty(
                        "--secondary-color",
                        $(this).colorpicker("getValue", "#ffffff")
                    );
                }),
            $("#BodyColorPicker")
                .colorpicker()
                .on("changeColor", function () {
                    t.style.setProperty(
                        "--body-color",
                        $(this).colorpicker("getValue", "#ffffff")
                    );
                }),
            $("#CardColorPicker")
                .colorpicker()
                .on("changeColor", function () {
                    t.style.setProperty(
                        "--card-color",
                        $(this).colorpicker("getValue", "#ffffff")
                    );
                }),
            $("#BorderColorPicker")
                .colorpicker()
                .on("changeColor", function () {
                    t.style.setProperty(
                        "--border-color",
                        $(this).colorpicker("getValue", "#ffffff")
                    );
                }),
            $("#chartColorPicker1")
                .colorpicker()
                .on("changeColor", function () {
                    t.style.setProperty(
                        "--chart-color1",
                        $(this).colorpicker("getValue", "#ffffff")
                    );
                }),
            $("#chartColorPicker2")
                .colorpicker()
                .on("changeColor", function () {
                    t.style.setProperty(
                        "--chart-color2",
                        $(this).colorpicker("getValue", "#ffffff")
                    );
                }),
            $("#chartColorPicker3")
                .colorpicker()
                .on("changeColor", function () {
                    t.style.setProperty(
                        "--chart-color3",
                        $(this).colorpicker("getValue", "#ffffff")
                    );
                }),
            $("#chartColorPicker4")
                .colorpicker()
                .on("changeColor", function () {
                    t.style.setProperty(
                        "--chart-color4",
                        $(this).colorpicker("getValue", "#ffffff")
                    );
                }),
            $("#chartColorPicker5")
                .colorpicker()
                .on("changeColor", function () {
                    t.style.setProperty(
                        "--chart-color5",
                        $(this).colorpicker("getValue", "#ffffff")
                    );
                }),
            $(".theme-rtl input:checkbox").on("click", function () {
                $(this).is(":checked")
                    ? $("body").addClass("rtl_mode")
                    : $("body").removeClass("rtl_mode");
                var e = $(".scale-left"),
                    t = $(".scale-right");
                e.addClass("scale-right"),
                    e.removeClass("scale-left"),
                    t.addClass("scale-left"),
                    t.removeClass("scale-right");
            }),
            $(".h-menu-switch input:checkbox").on("click", function () {
                $(this).is(":checked")
                    ? $("body").addClass("h-menu")
                    : $("body").removeClass("h-menu");
            }),
            $(".pageheader-switch input:checkbox").on("click", function () {
                $(this).is(":checked")
                    ? $(".page-header").addClass("sticky-top")
                    : $(".page-header").removeClass("sticky-top");
            }),
            $(".pageheader-dark-switch input:checkbox").on(
                "click",
                function () {
                    $(this).is(":checked")
                        ? $(".page-header").addClass("dark")
                        : $(".page-header").removeClass("dark");
                }
            ),
            $(".radius-switch input:checkbox").on("click", function () {
                $(this).is(":checked")
                    ? $("body").addClass("radius-0")
                    : $("body").removeClass("radius-0");
            }),
            $(".sidebardark-switch input:checkbox").on("click", function () {
                $(this).is(":checked")
                    ? $(".sidebar").addClass("dark")
                    : $(".sidebar").removeClass("dark");
            }),
            $(".imagebg-switch input:checkbox").on("click", function () {
                $(this).is(":checked")
                    ? ($(".bg-images").addClass("show"),
                      $(".sidebar").addClass("sidebar-img-bg"))
                    : ($(".bg-images").removeClass("show"),
                      $(".sidebar").removeClass("sidebar-img-bg"));
            }),
            $(".sidebar-img").on("click", function () {
                var e = (e = $(this).attr("id")).split("-");
                t.style.setProperty(
                    "--sidebar-img",
                    "url(https://www.wrraptheme.com/templates/luno/assets/img/sidebar-bg/sidebar-" +
                        e[1] +
                        ".jpg)"
                ),
                    $(".sidebar-img-active").removeClass("sidebar-img-active"),
                    $(".sidebar-img-" + e[1]).addClass("sidebar-img-active");
            }),
            $(".fluid-switch input:checkbox").on("click", function () {
                $(this).is(":checked")
                    ? ($(".container").addClass("container-fluid"),
                      $(".container").removeClass("container"))
                    : ($(".container-fluid").addClass("container"),
                      $(".container-fluid").removeClass("container-fluid"));
            }),
            $(".shadow-switch input:checkbox").on("click", function () {
                $(this).is(":checked")
                    ? $(".card").addClass("shadow-active")
                    : $(".card").removeClass("shadow-active");
            }),
            $(".sidebar-v2 input:checkbox").on("click", function () {
                $(this).is(":checked")
                    ? $(".sidebar").addClass("version-2")
                    : $(".sidebar").removeClass("version-2");
            });
    }),
    
    $(function () {
        $(".next").on("click", function () {
            var e = $(this).parents(".tab-pane").next().attr("id");
            return $("a[href=\\#" + e + "]").tab("show"), !1;
        }),
            $('a[data-bs-toggle="tab"]').on("shown.bs.tab", function (e) {
                (e = $(e.target).data("bs-step")),
                    (e = (parseInt(e) / 5) * 100);
                $("#CreateNew .progress-bar").css({ width: e + "%" });
            }),
            $(".first").on("click", function () {
                $("#myWizard a:first").tab("show");
            });
    }),
    $(function () {
        $(".password-meter .form-control").on("input", function () {
            var e = 0,
                t = $(this).val(),
                c = new RegExp("[A-Z]"),
                o = new RegExp("[a-z]"),
                n = new RegExp("[0-9]"),
                r = new RegExp("^(?=.*?[#?!@$%^&*-]).{1,}$");
            7 < t.length && e++,
                0 < t.length && t.match(c) && e++,
                0 < t.length && t.match(o) && e++,
                0 < t.length && t.match(n) && e++,
                0 < t.length && t.match(r) && e++,
                ($(".password-meter .progress-bar")[0].style.width =
                    20 * e + "%");
        });
    }),
    $(function () {
        $(".image-input .form-control").on("change", function () {
            var e = URL.createObjectURL(this.files[0]);
            $(this)
                .parent()
                .parent()
                .children(".avatar-wrapper")[0].style.background =
                "url(" + e + ") no-repeat";
        });
    }),
    $(function () {
        $(".card-fullscreen").on("click", function (e) {
            return (
                $(this).closest("div.card").toggleClass("fullscreen"),
                e.preventDefault(),
                !1
            );
        });
    }),
    (document.onkeydown = function (e) {
        return (
            123 != event.keyCode &&
            (!e.ctrlKey || !e.shiftKey || e.keyCode != "I".charCodeAt(0)) &&
            (!e.ctrlKey || !e.shiftKey || e.keyCode != "C".charCodeAt(0)) &&
            (!e.ctrlKey || !e.shiftKey || e.keyCode != "J".charCodeAt(0)) &&
            (!e.ctrlKey || e.keyCode != "U".charCodeAt(0)) &&
            void 0
        );
    }),
    $(document).on("contextmenu", function (e) {
        // return !1;
    });
