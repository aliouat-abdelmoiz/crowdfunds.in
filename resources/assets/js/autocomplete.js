$(function () {
    $.ajaxSetup({cache: true});
    var selected_item = "";
    var entered_keyword = "";
    var options = {
        url: function (phrase) {
            return "/api/search?query=" + $("#search").val();
        },

        listLocation: function (data) {
            return data["hits"]["hits"];
        },

        getValue: function (element) {
            var items = [];
            var result = element["_source"]["name"] + "&nbsp;&nbsp;<small style='color: #00b0e8'>Category : " + element['_source']['category'] + "</small>";
            return result;
        },

        list: {
            maxNumberOfElements: 6,
            onChooseEvent: function () {
                window.location.href = "/search?query=" + ($("#search").getSelectedItemData()._source.category);
            },
            onSelectItemEvent: function () {
                var index = $("#search").getSelectedItemData()._source.category;
                $("#search").val(index).trigger("change");
                selected_item = index;
            },
            onKeyEnterEvent: function () {
                if (selected_item == "" || selected_item == null) {
                    return false;
                } else {
                    $("#search").val(selected_item);
                }
            }
        },

        placeholder: "Do you need any help ?",

        ajaxSettings: {
            dataType: "json",
            method: "GET",
            cache: true,
            beforeSend: function () {
                $(".load").show();
                selected_item = "";
            },
            complete: function () {
                $(".load").hide();
                selected_item = "";
            }
        },
        requestDelay: 0
    };

    $("#search").easyAutocomplete(options);

    $("#search").keydown(function (e) {
        entered_keyword = $(this).val();
        if (e.keyCode == 13) {
            if (selected_item == "" || selected_item == null || selected_item == "undefined") {
                return true;
            } else {
                window.location.href = "/search?query=" + encodeURIComponent($(this).val());
            }
        }
    })
});