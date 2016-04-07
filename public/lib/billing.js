(function () {
    var StripeBilling = {
        init: function () {
            this.form = $("#billing-form");
            this.submitButton = this.form.find('input[type=submit]');
            this.form.find('.card-number').mask('0000-0000-0000-0000');
            this.form.validate({
                errorPlacement: function(error, element) {
                    return false;
                }
            });
            this.submitButtonVal = this.submitButton.val();
            var stripeKey = "pk_test_Rh54FuBpoz5Bvhu6yznIrhcm";
            Stripe.setPublishableKey(stripeKey);
            this.bindEvents();
        },

        bindEvents: function () {
            this.form.on('submit', $.proxy(this.sendToken, this));
        },
        
        sendToken: function (event) {
            this.submitButton.val("Please wait...").prop("disabled", true);
            Stripe.createToken(this.form, $.proxy(this.stripeResponseHandler, this));
            event.preventDefault();
        },
        
        stripeResponseHandler: function (status, response) {

            if(response.error) {
                this.form.find(".card-error").show().text(response.error.message);
                return this.submitButton.prop("disabled", false).val(this.submitButtonVal);
            }

            $('<input>', {
                type: 'hidden',
                name: 'stripeToken',
                value: response.id
            }).appendTo(this.form);

            this.form[0].submit();

        }
    }
    StripeBilling.init();
})();