@if(Session::has("errors"))
    <ul class="danger">
        @foreach($errors->all() as $error)
            <li class="alert-danger">{{ $error }}</li>
        @endforeach
    </ul>
@endif
<figure class="form-group">
    {!! Form::label('nameLabel', 'Name: ') !!}
    {!! Form::input('text', 'name', Auth::user()->provider->name , ['class' => 'form-control']) !!}
</figure>
<figure class="form-group">
    {!! Form::select('range', ['state' => 'State Wide', 'city' => 'City Wide', 'Miles'], !intval(\Auth::user()->provider->range) ? Auth::user()->provider->range : 0, ['class' =>
    'form-control range']) !!}
    <label class="add-margin" for="rangeValue" id="customRangeLabel">Miles :</label>
    {!! Form::input('number','rangeValue', Auth::user()->provider->range, ['class' => 'form-control rangeValue', 'placeholder' => 'Enter range in miles']) !!}
</figure>
<figure class="form-group">
    {!! Form::label('websiteLabel', 'Website: ') !!}
    {!! Form::input('url', 'website', Auth::user()->provider->website, ['class' => 'form-control website']) !!}
</figure>
<figure class="form-group">
    {!! Form::label('testimonialLabel', 'Testimonial: ') !!}
    {!! Form::input('text', 'testimonial', Auth::user()->provider->testimonial, ['class' => 'form-control']) !!}
</figure>
<figure class="form-group">
    {!! Form::label('logoLabel', 'Logo: ') !!}
    {!! Form::input('file', 'logo', Auth::user()->provider->logo, ['class' => 'form-control']) !!}
</figure>
<figure class="form-group">
    {!! Form::label('youtubeLabel', 'Youtube: ') !!}
    {!! Form::input('text', 'youtube', 'https://www.youtube.com/watch?v=' . Auth::user()->provider->youtube, ['class' =>
    'form-control']) !!}
</figure>
<figure class="form-group">
    {!! Form::label('noteLabel', 'Note: ') !!}
    {!! Form::input('text', 'note', Auth::user()->provider->note, ['class' => 'form-control']) !!}
</figure>
<figure class="form-group add-margin">
    {!! Form::label('license', 'License: ') !!}
    {!! Form::checkbox('license', 'license', Auth::user()->provider->license) !!}
    {!! Form::label('insurance', 'Insurance: ') !!}
    {!! Form::checkbox('insurance', 'insurance', Auth::user()->provider->insurance) !!}
    {!! Form::label('handyman', 'Handyman: ') !!}
    {!! Form::checkbox('handyman', 'handyman', Auth::user()->provider->handyman) !!}
</figure>
<figure class="form-group">
    {!! Form::label('catLabel', 'Categories: ') !!}
    <a href="javascript:selectCategory()" class="category">Select Categories / Subcategories</a>
    {!! Form::input('text', 'categories', \Auth::user()->hasRole('Provider') ? implode(",", $cat_names) : null ,
    ['class' => 'form-control add-margin', 'id' => 'category',
    'disabled']) !!}
    {!! Form::input('text', 'subcategories', \Auth::user()->hasRole('Provider') ? implode(",", $subcat_names) :
    null
    , ['class' => 'form-control add-margin', 'id' => 'subcategory',
    'disabled']) !!}
    {!! Form::hidden('categories', \Auth::user()->hasRole('Provider') ? implode(",", $cat_id) : null, ['class'
    =>
    'categories_values']) !!}
    {!! Form::hidden('subcategories', \Auth::user()->hasRole('Provider') ? implode(",", $subcat_id) : null,
    ['class'
    => 'subcategories_values']) !!}
</figure>
<figure class="form-group">
    {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}
</figure>
