<script>
    $("document").ready(function () {
        var send_to = "";
        var selected_project_id = "";
        $(".sendMessage").click(function () {
            send_to = $(this).data('text');
            selected_project_id = $(this).data('project');
            $.ajax({
                url: '/api/user/' + send_to,
                method: 'GET',
                complete: function (result) {
                    $("#recipient").val(result.responseText);
                }
            });
            $('.message').modal('show');
        });
        $("#message-send").click(function () {
            $.ajax({
                url: '/api/user/sendmessage',
                method: 'POST',
                data: {
                    '_token': "{{ csrf_token() }}",
                    subject: $("#recipient-title").val(),
                    content: $("#message").val(),
                    conversation_id: send_to,
                    project_id: selected_project_id
                },
                complete: function (result) {
                    alert(result.responseText);
                }
            });
            $('.message').modal('hide');
        });
    });
</script>