<script src="/messages.js"></script>
<div class="container">
    <div id="rootwizard" class="tabbable tabs-left">
        <ul class="nav nav-tabs">
            <li class=""><a href="#tab1" data-toggle="tab">My Info</a></li>
            <li class=""><a href="#tab2" data-toggle="tab">Post A Job</a></li>
            <li class=""><a href="#tab3" data-toggle="tab">Jobs</a></li>
            <li class="active"><a href="#tab4" data-toggle="tab">Messages</a></li>
        </ul>
        <div class="tab-content pull-right">
            <div class="tab-pane" id="tab1">
                <table class="table table-striped table-condensed">
                    <tbody>
                    @if($profile->password == "" || empty($profile->password))
                        <p class="alert-info">Password not set for profile so it mean you only able to log using social
                            set the password
                            then you can able to use regular user name &amp; password</p>
                    @endif
                    @if($profile->userinfo->zip_code == "" || $profile->userinfo->zip_code == 0)
                        <p class="alert-danger">Profile Incomplete, You can't submit job</p>
                    @endif
                    <p><img src="{{ $profile->avatar }}" alt=""></p>

                    <p>

                    <form action="/profile/edituser" method="get">
                        <input type="submit" class="btn btn-success" value="Edit User Profile">
                    </form>
                    </p>
                    <tr>
                        <td>Name</td>
                        <td>{{ $profile->name }}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>{{ $profile->userinfo->address }}</td>
                    </tr>
                    <tr>
                        <td>Address 2</td>
                        <td>{{ $profile->userinfo->address2 }}</td>
                    </tr>
                    <tr>
                        <td>City</td>
                        <td>{{ $profile->userinfo->city }}</td>
                    </tr>
                    <tr>
                        <td>State</td>
                        <td>{{ $profile->userinfo->state }}</td>
                    </tr>
                    <tr>
                        <td>Country</td>
                        <td>{{ $profile->userinfo->country }}</td>
                    </tr>
                    <tr>
                        <td>Zip Code</td>
                        <td>{{ $profile->userinfo->zip_code }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $profile->email }}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <div class="tab-pane" id="tab2">
                <button class="btn btn-red" onclick="window.location.href = '/jobs'">Create Job
                </button>
            </div>
            <div class="tab-pane" id="tab3">
                @if(Auth::user()->hasRole('User'))
                    @if(!$user_projects->isEmpty())
                        @foreach($user_projects as $project)
                            <div class="job-list">
                                <div class="job">
                                    <h3>{{ $project->title }}</h3>

                                    <p>{{ $project->body }}</p>

                                    <p>You hired :</p> <b>{{ $project->provider['name'] }}</b>
                                    <a href="/jobs/show/?id={{ $project->id }}">Job Detail</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h3>Currenly No Project <a href="#tab2" data-toggle="tab">#Add New One</a></h3>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>