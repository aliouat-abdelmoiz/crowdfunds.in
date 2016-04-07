PUT yourservice
{
"settings": {
"number_of_shards": 1,
"analysis": {
"filter": {
"autocomplete_filter": {
"type": "edge_ngram",
"min_gram": 3,
"max_gram": 20
}
},
"analyzer": {
"autocomplete": {
"type": "custom",
"tokenizer": "standard",
"filter": [
"lowercase",
"autocomplete_filter"
]
}
}
}
},
"mappings": {
"categories": {
"properties": {
"name": {
"type": "string",
"index": "not_analyzed"
},
"title": {
"type": "string",
"index": "not_analyzed"
},
"description": {
"type": "string",
"index": "not_analyzed"
},
"subcategories": {
"type": "string",
"index": "not_analyzed"
},
"suggested_tags": {
"type": "string",
"index": "analyzed",
"analyzer": "autocomplete"
}
}
}
}
}



$(function () {
$.ajaxSetup({cache: true});
var selected_item = "";
var options = {
url: function (phrase) {
return "/api/search?query=" + $("#search").val();
},

listLocation: function (data) {
return data["hits"]["hits"];
},

getValue: function (element) {
var items = [];
var result = element["highlight"]["suggested_tags"];
for (var i = 0; i < result.length; i++) {
return result[i];
}
},

list: {
maxNumberOfElements: 10,
onChooseEvent: function () {
window.location.href = "/search?query=" + ($("#search").getSelectedItemData()._source.name);
},
onSelectItemEvent: function () {
var index = $("#search").getSelectedItemData()._source.name;
$("#search").val(index).trigger("change");
selected_item = index;
},
onKeyEnterEvent: function () {
if(selected_item == "" || selected_item == null) {
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
beforeSend: function() {
$(".load").show();
selected_item = "";
},
complete: function () {
$(".load").hide();
selected_item = "";
}
},
requestDelay: 300
};

$("#search").easyAutocomplete(options);

$("#search").keydown(function (e) {
if (e.keyCode == 13) {
if(selected_item == "" || selected_item == null || selected_item == "undefined") {
return true;
} else {
window.location.href = "/search?query=" + encodeURIComponent($(this).val());
}
}
})
});




"yourservice": {
"aliases": {},
"mappings": {
"my_type": {
"properties": {
"name": {
"type": "string",
"analyzer": "autocomplete"
},
"suggested_tags": {
"type": "string",
"analyzer": "autocomplete"
},
"title": {
"type": "string"
}
}
},
"categories": {
"properties": {
"name": {
"type": "string",
"analyzer": "autocomplete"
},
"suggested_tags": {
"type": "string",
"analyzer": "autocomplete"
},
"title": {
"type": "string"
}
}
}
},
"settings": {
"index": {
"creation_date": "1450011636232",
"analysis": {
"filter": {
"autocomplete_filter": {
"type": "edge_ngram",
"min_gram": "1",
"max_gram": "20"
}
},
"analyzer": {
"autocomplete": {
"filter": [
"lowercase",
"autocomplete_filter"
],
"type": "custom",
"tokenizer": "standard"
}
}
},
"number_of_shards": "5",
"number_of_replicas": "0",
"uuid": "JiJat6IMRHqYXz_XyylI9Q",
"version": {
"created": "2000002"
}
}
},