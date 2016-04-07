<script>
    function isInArray(value, array) {
        return array.indexOf(value) > -1;
    }

    var data_category = [];
    var data_category_names = [];
    var data_subcategory = [];
    var data_subcategory_names = [];
    function selectCategory() {
        $("document").ready(function () {
            $(".catlist").parent().modal({
                keyboard: true
            });
            $("#saveData").click(function (e) {
                e.preventDefault();
                $("#category").val(data_category_names);
                $(".categories_values").val(data_category);
                $(".subcategories_values").val(data_subcategory);
                $("#subcategory").val(data_subcategory_names);
                $(".catlist").parent().modal('toggle');
            });
            $(".parentcheckbox").change(function() {
                // save state of parent
                c = $(this).is(':checked');
                $(this).parent().find("input[type=checkbox]").each(function() {
                    // set state of siblings
                    $(this).prop('checked', c);
                });
                var that = $(this);
                if(this.checked) {
                    if(!isInArray($(this).val(), data_category)) {
                        data_category.push($(this).val());
                        data_category_names.push($(this).data('text'));
                    }
                    $(that).parent().find(':checkbox:checked').not(that).map(function () {
                        if(!isInArray(this.value, data_subcategory)) {
                            data_subcategory.push(this.value); // or return this;
                        }
                        if(!isInArray($(this).data("subtext"), data_subcategory_names)) {
                            data_subcategory_names.push($(this).data("subtext"))
                        }
                    });
                } else {
                    $(that).parent().find(':checkbox').attr("checked", false).map(function () {

                        if(isInArray($(that).data("text"), data_category_names)) {
                            data_cat_names = data_category_names.indexOf($(that).data('text'));
                            if (~data_cat_names) data_category_names.splice(data_cat_names, 1);
                        }

                        if(isInArray($(that).val(), data_category)) {
                            data_cat = data_category.indexOf($(that).val());
                            if (~data_cat) data_category.splice(data_cat, 1);
                        }

                        if(isInArray(this.value, data_subcategory)) {
                            var data_subcat = data_subcategory.indexOf(this.value);
                            if (~data_subcat) data_subcategory.splice(data_subcat, 1);
                        }

                        if(isInArray($(this).data("subtext"), data_subcategory_names)) {
                            var data_subcat_names = data_subcategory_names.indexOf($(this).data("subtext"));
                            if (~data_subcat_names) data_subcategory_names.splice(data_subcat_names, 1);
                        }

                    });
                }

            });

            // Update parent checkbox based on children
            $("input[type=checkbox]:not('.parentcheckbox')").change(function() {
                var check = this.parentNode.parentNode.parentNode.children[0];
                var subcheck_name = this.parentNode.childNodes[1];

                if(this.checked) {
                    check.indeterminate = true;
                    if(!isInArray($(check).val(), data_category)) {
                        data_category.push(check.value);
                    }
                    if(!isInArray(check.dataset.text, data_category_names)) {
                        data_category_names.push(check.dataset.text);
                    }
                    if(!isInArray(this.value, data_subcategory)) {
                        data_subcategory.push(this.value);
                    }
                    if(!isInArray(subcheck_name.dataset.subtext, data_subcategory_names)) {
                        data_subcategory_names.push(subcheck_name.dataset.subtext);
                    }
                } else {
                    check.indeterminate = true;
                    if(isInArray(subcheck_name.value, data_subcategory)) {
                        var data_subcat = data_subcategory.indexOf(subcheck_name.value);
                        if (~data_subcat) data_subcategory.splice(data_subcat, 1);
                    }
                    if(isInArray(subcheck_name.dataset.subtext, data_subcategory_names)) {
                        var data_subcat_names = data_subcategory_names.indexOf(subcheck_name.dataset.subtext);
                        if (~data_subcat_names) data_subcategory_names.splice(data_subcat_names, 1);
                    }
                }
                if ($(this).closest("div").find("input[type=checkbox]:not('.parentcheckbox')").not(':checked').length < 1) {
                    $(this).closest("div").find(".parentcheckbox").indeterminate = true;
                } else {
                    $(this).closest("div").find(".parentcheckbox").attr('checked', false);
                }
            });
        });
    }
</script>