@component('mail::message')
<h1>Hello ,the manager just  added you to the system</h1>
<p>You can use the following code to use your account:</p>

@component('mail::panel')
{{ $code }}
@endcomponent

<p>The allowed duration of the code is one hour from the time the message was sent</p>
@endcomponent
