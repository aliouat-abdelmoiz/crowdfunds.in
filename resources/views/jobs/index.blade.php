@extends('app')
@if(isset($id))
@section('title')
    {{ \App\Category::find($id)->name }} - Your service connection
@endsection
@section('description')
    {{ \App\Category::find($id)->content }}
@endsection
@section('keywords')
    {{ \App\Category::find($id)->keywords }}
@endsection
@else
@section('title')
    Post New Job - Your service connection
@endsection
@section('description')
    You can post new jobs to yourserviceconnections on your choise categories and subcategories.
@endsection
@section('keywords')
    'Job','New Job', 'Post new job', Yourserviceconnections Job'
@endsection
@endif
@section('content')
    <article class="add-margin">
        {!! Form::open(['method' => 'POST', 'route' => 'jobs.post', 'class' => 'user_form']) !!}
        <p><b>Job Visiblity : </b>
        <p>
            {!! Form::checkbox('license', 'License') !!} License
            {!! Form::checkbox('handyman', 'handyman') !!} handyman
            {!! Form::checkbox('insurance', 'Insurance') !!} Insurance
        <hr>
        @if(Auth::guest())
            {!! Form::input('email', 'email', old('email'), ['class' => 'form-control flat-ui-text', 'placeholder' => 'Email Address *']) !!}
            <input type="text" name="postal" id="postal" v-on:keyup="sendData" placeholder="Zip Code"
                   class="form-control flat-ui-text"/>
            <input type="hidden" value="" id="current_place" name="current_place"/>
            <input type="hidden" value="" id="city" name="city"/>
            <input type="hidden" value="" id="state" name="state"/>
            <input type="hidden" value="" id="zip" name="zip"/>
            <input type="hidden" value="USA" id="country" name="country"/>
            <input type="hidden" value="" id="longitude" name="longitude"/>
            <input type="hidden" value="" id="latitude" name="latitude"/>
            <span class="iaddress"></span>
        @endif
        {!! Form::select('category', $categories, '', ['id' => 'category', 'class' => 'form-control']) !!}
        @if(isset($subcategories))
            {!! Form::select('subcategory', $subcategories, $id2, ['class' => 'form-control add-margin']) !!}
        @else
            <select name="subcategory" id="subcategory" class="form-control add-margin"></select>
        @endif
        {!! Form::hidden('premium', $premium) !!}
        {!! Form::input('text', 'project_title', old('project_title'), ['class' => 'form-control flat-ui-text',
        'placeholder' =>
        'Project Title *']) !!}
        <label for="description">Project Description</label>
        {!! Form::textarea('description', old('description'), ['class' => 'form-control flat-ui-text', 'rows' => 10]) !!}

        {!! Form::input('hidden', 'uid', Input::get('uid')) !!}
        {!! Form::input('hidden', 'plan_id', Input::get('plan_id')) !!}
        @if(Session::has('errors'))
            <p class="red"><b>All Fields are required mark with (*)</b></p>
            <section class="errors">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </section>
        @endif

        <div id="myDrop" class="dropzone add-margin"></div>
    </article>

    <section class="text-center">
        {!! Form::submit('Submit Job', ['class' => 'btn btn-success add-margin']) !!}
        {!! Form::reset('Clear Form', ['class' => 'btn btn-danger add-margin']) !!}
        {!! Form::close() !!}
    </section>

@section('script')

    <script>

        var options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        };

        function success(pos) {
            var crd = pos.coords;

            console.log('Your current position is:');
            console.log('Latitude : ' + crd.latitude);
            console.log('Longitude: ' + crd.longitude);
            console.log('More or less ' + crd.accuracy + ' meters.');
            console.log("City : " + pos.city);
        }
        ;

        function error(err) {
            console.warn('ERROR(' + err.code + '): ' + err.message);
        }
        ;

        navigator.geolocation.getCurrentPosition(function (position) {
            console.log(position.address.postalCode);
        });

        Dropzone.autoDiscover = false;

        var myDropzone = new Dropzone("#myDrop", {
            url: '/file/post',
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 2, // MB
            parallelUploads: 2, //limits number of files processed to reduce stress on server
            addRemoveLinks: true,
            maxFiles: 1,
            dictDefaultMessage: 'Upload File Max File Size One Only | Drag & Drop or Click Here',
            uploadMultiple: false,
            accept: function (file, done) {
                // TODO: Image upload validation
                done();
            },
            sending: function (file, xhr, formData) {
                // Pass token. You can use the same method to pass any other values as well such as a id to associate the image with for example.
                formData.append("_token", $('[name="_token"]').val()); // Laravel expect the token post value to be named _token by default
            },
            init: function () {
                this.on("success", function (file, response) {
                    // On successful upload do whatever :-)
                });
            }
        });


        $("document").ready(function () {

            $(document.body).on("change click", "#category", function () {
                $.ajax({
                    url: '/api/subcategories',
                    type: 'JSON',
                    method: 'GET',
                    data: {category_id: $(this).val()},
                    success: function (result) {
                        var x = JSON.parse(result);
                        $("#subcategory").html("");
                        $.each(x, function (key, value) {
                            $("#subcategory").append("<option value=" + value.id + ">" + value.name + "</option>");
                        });
                    }
                });
            });

            $.ajax({
                url: '/api/subcategories',
                type: 'JSON',
                method: 'GET',
                data: {category_id: $("#category :selected").val()},
                success: function (result) {
                    var x = JSON.parse(result);
                    $("#subcategory").html("");
                    $.each(x, function (key, value) {
                        $("#subcategory").append("<option value=" + value.id + ">" + value.name + "</option>");
                    });
                }
            });
        });
    </script>
@endsection
@endsection