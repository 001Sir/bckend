<x-mail::message>
# Email Verification

Thank you for signing up. 
Your six-digit code is 

<a href="{{ env("STATIC_WEB_URL") }}/user/verify?email={{ $email }}&token={{ $pin }}">Click here to Verify</a>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
