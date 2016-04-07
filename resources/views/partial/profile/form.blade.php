@extends('user')
@section('main-content')
    @if(Auth::user()->hasRole('Provider'))
        {!! Form::model('Provider', ['route' => ['profile.update', Auth::user()->provider->id], 'method' => 'PUT', 'files' => true]) !!}
    @else
        {!! Form::open(['method' => 'post', 'route' => 'profile.store', 'files' => true]) !!}
    @endif
    <figure class="col-md-10">
        @if(Auth::user()->hasRole('Provider'))
            <h1 class="add-margin">Edit Account As Provider</h1>
            <figure class="col-md-10">
                @include('partial.profile.edit-profile')
            </figure>
        @else
            <h1 class="add-margin">Create New Provider Profile</h1>
            <figure class="col-md-10">
                @include('partial.profile.new-profile')
            </figure>
        @endif
    </figure>
    {!! Form::close() !!}
    <div class="modal fade" id="category-modal">
        <div style="position: fixed; top: 20%; left: 68%; background: #ffffff; padding: 20px 50px;">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="saveData">Save changes</button>
        </div>
        <div class="modal-dialog catlist">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Select Category</h4>
                </div>
                @if($select_category_name == "" || $select_subcategory_name == "")
                    <div class="modal-body">
                        <div class="row">
                            @foreach(array_chunk($select_category_name->all(), 52) as $mycategories)
                                <div class="col-p">
                                    @foreach($mycategories as $cat)
                                        <div class="clearfix">
                                            <input type="checkbox" class="parentcheckbox" data-text="{{ $cat->name }}"
                                                   name="category{{$cat->id}}"
                                                   value="{{ $cat->id }}"/>
                                            <b>{{ $cat->name }}</b>
                                            <ul>
                                                @foreach($cat->subcategories as $subcat)
                                                    <li>
                                                        <input type="checkbox" id="sub{{ $cat->id }}"
                                                               data-subtext="{{ $subcat->name }}"
                                                               name="category{{$subcat->id}}"
                                                               value="{{ $subcat->id }}"/>
                                                        {{ $subcat->name }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="modal-body">
                        <div class="row">
                            @foreach(array_chunk($categories->all(), 52) as $mycategories)
                                <div class="col-p">
                                    @foreach($mycategories as $cat)
                                        <div class="clearfix">
                                            <input type="checkbox" class="parentcheckbox" data-text="{{ $cat->name }}"
                                                   name="category{{$cat->id}}"
                                                   value="{{ $cat->id }}" {{ in_array($cat->name, $cat_names) ? "checked" : "" }}
                                            />
                                            <b> {{ $cat->name}}</b>
                                            <ul>
                                                @foreach($cat->subcategories as $subcat)
                                                    <li>
                                                        <input type="checkbox" id="sub{{ $cat->id }}"
                                                               data-subtext="{{ $subcat->name }}"
                                                               name="category{{$subcat->id}}"
                                                               value="{{ $subcat->id }}" {{ in_array($subcat->name, $subcat_names) ? "checked" : "" }}/>
                                                        {{ $subcat->name }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveData">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    @include('partial.js.profile.formjs')
@endsection
@section('script')

    <script>

        $("document").ready(function () {
            $("#customRangeLabel").show();
            $(".rangeValue").show();
            $(".range").change(function () {
                if ($(this).val() == 0) {
                    $(".rangeValue").show();
                    $("#customRangeLabel").show();
                } else {
                    $(".rangeValue").hide();
                    $(".rangeValue").attr("value", "");
                    $("#customRangeLabel").hide();
                }
            });
        });

    </script>

@endsection