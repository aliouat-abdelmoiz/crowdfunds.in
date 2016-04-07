<h1>Thanks For Registration - Your Service Connection</h1>
<p>Please Click Below Link to Activate Your Account as User</p>
<p><a href="http://yourserviceconnection.com//auth/confirm?code={{$confirm}}">Confirm Account</a></p>
<p>For more information please use our website support or contact us</p>
@if(isset($password))
    <h3>Your Account Password : {{ $password }}</h3>
@endif