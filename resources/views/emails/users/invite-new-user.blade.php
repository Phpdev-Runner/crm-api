@component('mail::message')
# Invitation to {{$_ENV['APP_NAME']}} system.
Dear {{$receptor->name}},

{{$creator->name}} invited you to join our CRM System!

To join our system you need to push button below and enter new password to login to our system.

@component('mail::button', ['url' => $_ENV['APP_URL']."/api/v1/set-new-password/$token"])
Settle password for newly created account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
