@extends('user')
@section('title')
    Place Advertise on {{ date('F l Y') }}
@endsection
@section('main-content')
    <div class="col-md-10">
        @if($errors->all())
            <section class="alert-danger" style="border-radius: 5px; padding: 10px; margin-top: 10px">
                @foreach($errors->all() as $error)
                    <p style="font-size: 15px;">{{ $error }}</p>
                @endforeach
            </section>
        @endif
        {!! Form::model('AdvertiseController', ['route' => ['premium.update', $advertisement->id], 'method' => 'PUT', 'files' => true]) !!}
        <h1 class="add-margin">Advertise</h1>
        <h4>Auto Renew {!! Form::checkbox('renew', "1", $advertisement->auto_renew == 1 ? "checked" : "" ) !!} </h4>
        <hr>
        {{--{!! Form::open(['route' => 'premium.store', 'files' => true, 'method' => 'post']) !!}--}}
        {!! Form::hidden('plan', Input::get('plan')) !!}
        {!! Form::hidden('plan_name', Input::get('plan_name')) !!}
        {!! Form::hidden('clicks', Input::get('clicks')) !!}
        {!! Form::hidden('amount', Input::get('amount')) !!}
        {!! Form::hidden('id', Input::get('plan')) !!}

        {!! Form::text('title', $advertisement->advertise->title, ['placeholder' => 'Title', 'class' => 'form-control']) !!}
        <br>
        <b><p>Select Advertise Range - With selected range your advertise show users providing service in range.</p></b>
        {!! Form::select('range', [
        'city' => 'City Wide', 'state' => 'State Wide', 'Custom Range'
        ], 1, ['class' => 'form-control add-margin', 'id' => 'range']) !!}
        <input type="number" placeholder="Enter Own Range" name="custom_range"
               class="form-control add-margin custom_range">
        {!! Form::textarea('description', $advertisement->advertise->title, ['placeholder' => 'Description', 'class' => 'form-control']) !!}
        <br>
        {!! Form::file('images[]', ['multiple' => true, 'class' => 'form-control']) !!}
        <div class="images">
            @foreach($images as $image)
                <img src="/uploads/premium/{{ $image }}" alt="">
            @endforeach
        </div>
        <select name="categories" id="category" class="form-control add-margin">
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="subcategories[]" id="subcategory" class="form-control add-margin" multiple>

        </select>

        <p class="text-right">
            <input type="submit" value="Save Changes" class="btn btn-red">
        </p>

        {!! Form::close() !!}
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/dropzone.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multiple-select/1.2.0/multiple-select.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/dropzone.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multiple-select/1.2.0/multiple-select.css">
    <script>
        $("document").ready(function () {
            $(".custom_range").hide();
            $("#range").change(function () {
                switch ($(this).val()) {
                    case '0':
                        $('.custom_range').show();
                        break;
                    default :
                        $('.custom_range').hide();
                }
            });
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
                            $('#subcategory').multipleSelect();
                        });
                    }
                });
            });
        })
    </script>
@endsection