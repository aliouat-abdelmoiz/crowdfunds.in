@extends('user')
@section('title')
    My Projects - Your Service Connections
@endsection
@section('main-content')
    <div class='col-md-10 profile-page'>
        @if(count($projects) == 0)
            <h5>Project Recieved 0</h5>
        @else
            <div class="col-md-12 jobs">
                <h4>You recieved {{ count($projects) }} Projects</h4>
                <hr>
                @foreach($projects as $project)
                    <h2>{{ $project->title }} </h2>
                    <small class="posted">Posted By <a
                                href="">{{ \App\User::find($project->user_id)->name }}</a> {{ \Carbon\Carbon::parse($project->pcdate)->toFormattedDateString() }}
                    </small>
                    <p class="description">
                        {{ str_limit($project->body, 250) }}
                    </p>
                    <ul>
                        <li class="link">
                            @if($project->applied)
                                <a class="disabled" data-project="{{ $project->project_id }}" data-title="{{ $project->title }}"
                                   data-text="{{ $project->user_id }}">Already Applied</a>
                            @else
                                <a href="#" class="project-apply" data-project="{{ $project->project_id }}"
                                   data-text="{{ $project->user_id }}" data-title="{{ $project->title }}">Apply Job</a>
                            @endif
                        </li>
                        <li>{{ \App\Category::find($project->categories_id)->name }}</li>
                        <li>{{ \App\Subcategory::find($project->subcategories_id)->name }}</li>
                    </ul>
                    <hr class="clearfix">
                @endforeach
            </div>
        @endif
    </div>
    <div class="applyjob" data-remodal-id="jobmodal">
        <button data-remodal-action="close" class="remodal-close"></button>
        <h1 class="add-margin">Send Message</h1>

        <form>
            <div class="form-group">
                <input type="hidden" name="name" value="" class="form-control" id="recipient">
            </div>
            <div class="form-group">
                <label for="recipient-title" class="control-label">Project:</label>
                <input type="text" value="" disabled name="title" class="form-control" id="recipient-title">
            </div>
            <div class="form-group">
                <label for="message-text" class="control-label">Message:</label>
                        <textarea name="message" class="form-control" id="message" rows="5"
                                  cols="40"></textarea>
            </div>
        </form>
        <br>
        <button data-remodal-action="cancel" class="remodal-cancel">Cancel</button>
        <button data-remodal-action="confirm" class="remodal-confirm-job">OK</button>
    </div>
@endsection
@section('script')
    <script src="/lib/remodal/dist/remodal.min.js"></script>
    <link rel="stylesheet" href="/lib/remodal/dist/remodal.css">
    <link rel="stylesheet" href="/lib/remodal/dist/remodal-default-theme.css">
    <script>
        var options = {};

        var modal = $('[data-remodal-id=jobmodal]').remodal(options);

        $("document").ready(function () {
            var send_to = "";
            var selected_project_id = "";
            $(".project-apply").click(function () {
                send_to = $(this).data('text');
                selected_project_id = $(this).data('project');
                $("#recipient-title").val($(this).data('title'));
                $.ajax({
                    url: '/api/user/' + send_to,
                    method: 'GET',
                    complete: function (result) {
                        $("#recipient").val(result.responseText);
                    }
                });
                modal.open();
            });

            $(".remodal-confirm-job").click(function () {
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
                    complete: function () {
                        swal({
                            title: "Thank you for applying",
                            text: "Contact us if any problem",
                            type: "success",
                            showCancelButton: false,
                            closeOnConfirm: false
                        }, function () {
                            window.location.reload();
                        });
                    }
                });
                modal.close();
            });
        });

    </script>
@endsection