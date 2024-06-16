<x-mail::message>
# Vote Registered

Hello {{ $user->name }}.

This email is to inform you we received your vote:  
<strong>Candidate/Political Party:</strong> {{$vote->candidate->name}} / {{$vote->candidate->political_party}}  
<strong>Confirmation Code:</strong> {{$vote->id}}

Please keep this email for your records.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
