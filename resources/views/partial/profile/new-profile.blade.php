<figure class="form-group">
    {!! Form::label('nameLabel', 'Name: ') !!}
    {!! Form::input('text', 'name', null , ['class' => 'form-control']) !!}
</figure>
<figure class="form-group">
    {!! Form::select('range', ['state' => 'State Wide', 'city' => 'City Wide', 'Miles'], 2, ['class' =>
    'form-control range']) !!}
    <label class="add-margin" for="rangeValue" id="customRangeLabel">Miles :</label>
    {!! Form::input('number','rangeValue', null, ['class' => 'form-control rangeValue']) !!}
</figure>
<figure class="form-group">
    {!! Form::label('websiteLabel', 'Website: ') !!}
    {!! Form::input('text', 'website', null, ['class' => 'form-control']) !!}
</figure>
<figure class="form-group">
    {!! Form::label('testimonialLabel', 'Testimonial: ') !!}
    {!! Form::input('text', 'testimonial', null, ['class' => 'form-control']) !!}
</figure>
<figure class="form-group">
    {!! Form::label('logoLabel', 'Logo: ') !!}
    {!! Form::input('file', 'logo', null, ['class' => 'form-control']) !!}
</figure>
<figure class="form-group">
    {!! Form::label('youtubeLabel', 'Youtube: ') !!}
    {!! Form::input('text', 'youtube', null, ['class' => 'form-control']) !!}
</figure>
<figure class="form-group">
    {!! Form::label('noteLabel', 'Note: ') !!}
    {!! Form::input('text', 'note', null, ['class' => 'form-control']) !!}
</figure>
<figure class="form-group add-margin">
    {!! Form::label('license', 'License: ') !!}
    {!! Form::checkbox('license', 'license') !!}
    {!! Form::label('insurance', 'Insurance: ') !!}
    {!! Form::checkbox('insurance', 'insurance') !!}
    {!! Form::label('handyman', 'Handyman: ') !!}
    {!! Form::checkbox('handyman', 'handyman') !!}
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