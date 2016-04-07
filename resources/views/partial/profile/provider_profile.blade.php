<div class="container">
    <div id="rootwizard" class="tabbable tabs-left">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab">My Info</a></li>
            <li class=""><a href="#tab2" data-toggle="tab">Post A Job</a></li>
            <li class=""><a href="#tab3" data-toggle="tab">Jobs</a></li>
            <li class=""><a href="#tab4" data-toggle="tab">Messages</a></li>
        </ul>
        <div class="tab-content pull-right">
            <div class="tab-pane active" id="tab1">
                <table class="table table-striped table-condensed">
                    <tbody>
                    <tr>
                        <td>Name</td>
                        <td>{{ $profile->provider->name }}

                        </td>
                    </tr>
                    <tr>
                        <td>Service</td>
                        <td>{{ $profile->provider->range }}</td>
                    </tr>
                    @if($profile->provider->logo != "" || $profile->provider->logo != 'null')
                        <tr>
                            <td>
                                <img src="/uploads/users/logos/{{ $profile->provider->logo }}" alt="">
                            </td>
                            @if($profile->provider->youtube != "" || $profile->provider->youtube != 'null')
                                <td>
                                    <iframe width="100%" height="200"
                                            src="https://www.youtube.com/embed/{{ $profile->provider->youtube }}"
                                            frameborder="0" allowfullscreen></iframe>
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td>
                                <h3>Categories</h3>
                                @foreach($categories as $category)
                                    {{ $category->name }} <br/>
                                @endforeach
                            </td>
                            <td>
                                <h3>Subcategories</h3>
                                @foreach($subcategories as $subcategory)
                                    {{ $subcategory->name }} <br/>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td><b>License / Insurance / Handyman</b></td>
                            <td>{{ $profile->provider->license }} / {{ $profile->provider->insurance }}
                                / {{ $profile->provider->handyman }}</td>
                        </tr>
                        <tr>
                            <td>Testimonial</td>
                            <td>{{ $profile->provider->testimonial }}</td>
                        </tr>
                        <tr>
                            <td>Note</td>
                            <td>{{ $profile->provider->note }}</td>
                        </tr>
                        <tr>
                            <td>
                                @if($hired == null)
                                @else
                                    <h3>You hired for following projects</h3>
                                    @foreach($hired as $hire)
                                        @foreach($hire->projects as $project)
                                            <b><p>{{ $project->title }}</p></b>
                                            <p>{{ $project->body }}</p>
                                            @if($project->ended == 1)
                                                <b>Project Ended - {{ $project->user->name }}</b> and Gave
                                                you {{ $project->feedback }} star rating.
                                            @else
                                                <b>Project Running - Hired By {{ $project->user->name }}</b>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                @if($projects == null)
                                    <h1>Currently No Project</h1>
                                @else
                                    <h3>You Recieved Projects :</h3>
                                    @foreach($projects as $project)
                                        <b><p> {{ $project->title }}</p></b>
                                        <p> {{ $project->body }} </p>
                                        <a href="#" data-project="{{ $project->project_id }}"
                                           data-text="{{ $project->user_id }}" class="sendMessage">Send
                                            Message</a>
                                        <hr/>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="tab2">
                <button class="btn btn-red" onclick="window.location.href = '/jobs'">Create Job
                </button>
            </div>
            <div class="tab-pane" id="tab3">
                @if(Auth::user()->hasRole('Provider'))
                    @if($projects == null)
                        <h1>Currently No Project</h1>
                    @else
                        <h3>You Recieved Projects :</h3>
                        @foreach($projects as $project)
                            <b><p> {{ $project->title }}</p></b>
                            <p> {{ $project->body }} </p>
                            <a href="#" data-project="{{ $project->project_id }}" data-text="{{ $project->user_id }}"
                               class="sendMessage">Send Message</a>
                            <hr/>
                        @endforeach
                    @endif
                @endif
            </div>

        </div>
    </div>
</div>


<div class="modal fade message" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Recipient:</label>
                        <input type="text" name="name" value="" class="form-control" id="recipient">
                    </div>
                    <div class="form-group">
                        <label for="recipient-title" class="control-label">Title:</label>
                        <input type="text" value="" name="title" class="form-control" id="recipient-title">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Message:</label>
                        <textarea name="message" class="form-control" id="message" rows="5"
                                  cols="40"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="message-send">Send message</button>
            </div>
        </div>
    </div>
</div>

@include('partial.js.profile.profilejs')