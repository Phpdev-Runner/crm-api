@component('mail::message')
# Invitation to {{$_ENV['APP_NAME']}} system.
Dear {{$user->name}},

{{$creator->name}} invited you to join our CRM System!


@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
