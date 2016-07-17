/**
 * Created by mandeepgill on 18/04/15.
 */
var rate = "";
var advertise = [];
Array.prototype.inArray = function(comparer) {
    for (var i = 0; i < this.length; i++) {
        if (comparer(this[i])) return true;
    }
    return false;
};
// adds an element to the array if it does not already exist using a comparer
// function
Array.prototype.pushIfNotExist = function(element, comparer) {
    if (!this.inArray(comparer)) {
        this.push(element);
    }
};
(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });
    $(".notification").click(function() {
        window.location.href = "/notifications";
    });
})();

function imgError(image) {
    image.onerror = "";
    image.src = "/images/logo-stamp.png";
    return true;
}

function hire(project_id, provider_id) {
    $.ajax({
        url: '/api/hireUserForProject',
        method: 'POST',
        data: {
            hire_project_id: project_id,
            hire_provider_id: provider_id
        },
        complete: function(data) {
            swal({
                title: "Contractor Hired",
                text: data.responseText,
                type: "success",
                showCancelButton: false,
                closeOnConfirm: false
            }, function(data) {
                window.location.reload();
            });
        }
    })
}

function endContract() {
    inst.open();
    $.ajax({
        url: '/jobs/end',
        method: 'POST',
        data: {
            rating: rate,
            project_id: $('.endContract').data("project"),
            provider_id: $('.endContract').data("provider")
        },
        success: function() {
            swal({
                title: "Contractor Ended",
                text: "Contact us if any problem",
                type: "success",
                showCancelButton: false,
                closeOnConfirm: false
            });
            window.location.reload();
        }
    });
}
var arr;

function setArray(image) {
    $.ajax('/image', function() {});
    if ($(image).data('subcategory') == 1) {
        $(image).attr("src", "null");
    }
}
setArray();
$("document").ready(function() {
    if ($("#phone").length > 0) {
        $("#phone").mask("9 (999) 999-999");
    }
    if ($(".website").length > 0) {
        var website = new RegExp('^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$');
        $(".website").keyup(function() {
            var VAL = $(this).val();
            if (website.test(VAL)) {
                $(".website").css("background", "rgb(192,234,252)");
            } else {
                $(".website").css("background", "rgb(255,255,255)");
            }
        })
    }
    $img = $(".myimage");
    $img.on("load", function() {
        setArray($(this));
    });
    $('.endContract').click(function(e) {
        e.preventDefault();
        $('div.rateme').raty({
            click: function(score, evt) {
                rate = score;
            }
        });
        inst.open();
    });
    $(".remodal-confirm").click(function(e) {
        e.preventDefault();
        if (rate == 0 || rate == "") {
            $(".rate_error").show();
        } else {
            inst.destroy();
            endContract();
        }
    });
    $(document).ajaxError(function(event, jqxhr, settings, exception) {
        if (jqxhr.status == 401) {
            clearInterval(intervalID);
        }
    });
    var loading_options = {
        finishedMsg: "<div class='end-msg'>No More Pages</div>",
        msgText: "<div class='center'>Loading Please Wait...</div>",
        img: "/images/ajax-loader.gif"
    };
    $('.pages').infinitescroll({
        loading: loading_options,
        navSelector: ".pager",
        nextSelector: ".pager a:last",
        itemSelector: ".item-new",
        debug: false,
        dataType: 'html',
        path: function(index) {
            return "?page=" + index;
        }
    }, function(newElements) {});
    $(".pageitem").hover(function() {
        $(this).find('.overlay-link').show();
    }, function() {
        $(this).find('.overlay-link').hide();
    });    
    $('[data-toggle="tooltip"]').tooltip();
});
// Google

function imgError(image) {
    image.onerror = "";
    image.src = "../images/no.gif";
    return true;
}


var link = "";
var cat_name = " ";
var subcat_name = "";

function adRotate(links) {
    var id = Math.floor(Math.random() * links.length); // valid index in links
    var str = links[id]; // selects one of the links: str is a String
    var item = str.split(','); // splits the string to an Array
    return item;
}

function adv(idm, url) {
    $.ajax({
        url: '/api/chargeclick',
        method: 'POST',
        data: {
            id: idm,
        },
        complete: function () {
            window.location.href = url;
        }
    });
}

$("document").ready(function () {

    $.ajax({
        url: '/api/premium',
        success: function (data) {
            $.each(data.advertise, function (key, value) {
                $.ajax({
                    url: '/api/decode',
                    data: {cat_value: value.categories, sub_cats: value.subcategories},
                    success: function (data) {
                        var subcat_id = adRotate(data.subcats);
                        console.log(data.subcats);
                        $.ajax({
                            url: '/api/category_name/' + data.cat,
                            async: false,
                            success: function (result) {
                                cat_name = result;
                                $.ajax({
                                    async: false,
                                    url: '/api/subcategory_name/' + subcat_id,
                                    success: function (result_subcat) {
                                        subcat_name = result_subcat;
                                    }
                                });
                            }
                        });
                        var split_image = value.images.split(",");
                        link = '/jobs/' + cat_name + "/" + subcat_name + "/" + data.cat + "/" + subcat_id + "/?premium=true&plan_id=" + value.plan_id + "&uid=" + value.user_id;
                        $(".advertise").append('<p>' + value.title + '</p>' + "<img onclick='adv(this.dataset.id, this.dataset.url)' data-url='" + link + "' data-id='" + value.adv_id + "'" + "class='advshow' src='/uploads/premium/" + split_image[Math.floor(Math.random() * split_image.length)] + "'>" + "<p><br>" + cat_name + " / " + subcat_name + "</p>");
                    }
                })
            });
        }
    });
});

// Advertisement