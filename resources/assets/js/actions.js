/**
 * Created by mandeepgill on 18/04/15.
 */

var rate = "";
var advertise = [];

Array.prototype.inArray = function (comparer) {
    for (var i = 0; i < this.length; i++) {
        if (comparer(this[i])) return true;
    }
    return false;
};

// adds an element to the array if it does not already exist using a comparer
// function
Array.prototype.pushIfNotExist = function (element, comparer) {
    if (!this.inArray(comparer)) {
        this.push(element);
    }
};

(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });
    $(".notification").click(function () {
        window.location.href = "/notifications";
    });
})();

function imgError(image) {
    image.onerror = "";
    image.src = "../images/logo-stamp.png";
    return true;
}

var source = new EventSource("/messages");
source.onmessage = function (event) {
    if (event.data != 0) {
        $("#newmessages").html(" " + event.data + " New Message's").parent().removeClass('low-opacity').addClass('red');
    }
};
var notification = new EventSource("/notification");
notification.onmessage = function (event) {
    if (event.data != 0) {
        $(".notification").html(" " + event.data + " New Notification's").parent().removeClass('low-opacity').addClass('red');
    }
};

function loadMessages() {
    $.ajax({
        url: '/messages/count',
        success: function (data) {
            if (data.messages != 0) {
                $("#newmessages").html(" " + data.messages + " New Message's").parent().removeClass('low-opacity').addClass('red');
            }
            if (data.notification != 0) {
                $(".notification").html(" " + data.notification + " New Notification's").parent().removeClass('low-opacity').addClass('red');
            }
        }
    });
}

function hire(project_id, provider_id) {
    $.ajax({
        url: '/api/hireUserForProject',
        method: 'POST',
        data: {
            hire_project_id: project_id,
            hire_provider_id: provider_id
        },
        complete: function (data) {
            swal({
                title: "Contractor Hired",
                text: data.responseText,
                type: "success",
                showCancelButton: false,
                closeOnConfirm: false
            }, function (data) {
                window.location.reload();
            });
        }
    })
}

function endContract(e) {
    e.preventDefault();
    inst.open();
    $.ajax({
        url: '/jobs/end',
        method: 'POST',
        data: {
            rating: rate,
            project_id: $('.endContract').data("project"),
            provider_id: $('.endContract').data("provider")
        },
        success: function () {
            swal({
                title: "Contractor Ended",
                text: "Contact us if any problem",
                type: "success",
                showCancelButton: false,
                closeOnConfirm: false
            }, function () {
                window.location.reload();
            });
        }
    });
}

var arr;

function setArray(image) {
    $.ajax('/image', function () {

    });
    if ($(image).data('subcategory') == 1) {
        $(image).attr("src", "null");
    }
}

setArray();

$("document").ready(function () {

    if ($("#phone").length > 0) {
        $("#phone").mask("9 (999) 999-999");
    }

    if ($(".website").length > 0) {
        var website = new RegExp('^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$');
        $(".website").keyup(function () {
            var VAL = $(this).val();
            if (website.test(VAL)) {
                $(".website").css("background", "rgb(192,234,252)");
            } else {
                $(".website").css("background", "rgb(255,255,255)");
            }
        })
    }

    $img = $(".myimage");

    $img.on("load", function () {
        setArray($(this));
    });

    $('.endContract').click(function (e) {
        e.preventDefault();
        $('div.rateme').raty({
            click: function (score, evt) {
                rate = score;
            }
        });
        inst.open();
    });

    $(".remodal-confirm").click(function (e) {
        e.preventDefault();
        if (rate == 0 || rate == "") {
            $(".rate_error").show();
        } else {
            inst.destroy();
            endContract();
        }
    });

    $(document).ajaxError(function (event, jqxhr, settings, exception) {
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
        path: function (index) {
            return "?page=" + index;
        }
    }, function (newElements) {
        
    });
    $(".pageitem").hover(function () {
        $(this).find('.overlay-link').show();
    }, function () {
        $(this).find('.overlay-link').hide();
    });

    new Vue({
        el: "#postal",
        data: {
            location: []
        },
        methods: {
            sendData: function () {
                if ($("#postal").val().length == 5) {
                    $.ajax({
                        url: '/zip?zipcode=' + $("#postal").val(),
                        success: function (data) {
                            $.each(data, function (key, val) {
                                if (val.city == null || val.city == "undefined") {
                                    $("#city").val(val.place);
                                } else {
                                    $("#city").val(val.city);
                                }
                                $("#state").val(val.state_name);
                                $("#zip").val($("#postal").val());
                                $("#latitude").val(val.latitude);
                                $("#longitude").val(val.longitude);
                                if (val.city == null || val.city == "undefined") {
                                    $(".iaddress").html(val.place + ", " + val.state_name + ", " + val.state_id);
                                } else {
                                    $(".iaddress").html(val.city + ", " + val.state_name + ", " + val.state_id);
                                }
                            });
                        }
                    });
                } else {
                    return false;
                }
            },
            sendDataManual: function () {
                if ($("#postal").val().length == 5) {
                    $.ajax({
                        url: '/zip?zipcode=' + $("#postal").val(),
                        success: function (data) {
                            $.each(data, function (key, val) {
                                if (val.city == null || val.city == "undefined") {
                                    $("#city").val(val.place);
                                } else {
                                    $("#city").val(val.city);
                                }
                                $("#state").val(val.state_name);
                                $("#zip").val($("#postal").val());
                                $("#latitude").val(val.latitude);
                                $("#longitude").val(val.longitude);
                                if (val.city == null || val.city == "undefined") {
                                    $(".iaddress").html(val.place + ", " + val.state_name + ", " + val.state_id);
                                } else {
                                    $(".iaddress").html(val.city + ", " + val.state_name + ", " + val.state_id);
                                }
                            });
                        }
                    });
                } else {
                    return false;
                }
            }
        }
    });

    $('[data-toggle="tooltip"]').tooltip();

});
