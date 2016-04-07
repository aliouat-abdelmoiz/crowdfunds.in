<script>
    $("document").ready(function () {
        $("#auto_renew").click(function () {
            if ($(this).is(':checked')) {
                $(this).val(1);
            } else {
                $(this).val(0);
            }
        });
        $(".buy_plan").click(function () {
            $(this).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Don\'t Refresh Window');
            var plan = $(this).attr('plan');
            $.ajax({
                url: '/plan/create',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    plan_id: $(this).attr('data-plan'),
                    customer_id: "{{ $id }}",
                    plan_type: plan,
                    auto_renew: $("#auto_renew").val()
                },
                complete: function (data) {
                    $("#customButton").html('Payment Done').attr('disabled', 'disabled');
                    $("#auto_renew").attr('disabled', 'disabled');
                    if (data.responseText == "" || data.responseText == "undefined") {
                        alert("There is some error please contact website administrator");
                    } else {
                        return window.location.href = "/premium?plan_id=" + data.responseText;
                    }
                },
                error: function (errorData) {
                    alert(errorData.responseText);
                }
            });
        })
    });

    $(".verify_account").on("click", function () {
        var token = function (res) {
            var $id = $('<input type=hidden name=stripeToken />').val(res.id);
            var $email = $('<input type=hidden name=stripeEmail />').val(res.email);
            var $token = $('<input type=hidden name=_token />').val("{{ csrf_token() }}");
            var $plan_type = $('<input type=hidden name=plan_type />').val(plan);
            $('form').append($id).append($email).append($token).append($plan_type).submit();
        };

        var amount = $(this).data("amount");
        var plan = $(this).attr("plan");
        var clicks = $(this).attr("clicks");
        StripeCheckout.open({
            key: 'pk_test_Rh54FuBpoz5Bvhu6yznIrhcm',
            amount: amount,
            name: 'Buy ' + plan,
            image: '/images/128x128.png',
            description: 'You will get ' + clicks + " Clicks & Impression",
            panelLabel: 'Checkout',
            token: token
        });

        return false;
    });
</script>