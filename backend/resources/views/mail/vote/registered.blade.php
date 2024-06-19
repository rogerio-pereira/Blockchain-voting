<x-mail::message>
# Vote Registered

Hello {{ $user->name }}.

This email is to inform you we received your vote:  
<strong>Candidate/Political Party:</strong> {{$vote->candidate->name}} / {{$vote->candidate->political_party}}  
<strong>Confirmation Code:</strong> {{$vote->id}}

Your vote is registered in our Blockchain:  
<strong>Blockchain Id:</strong> {{$vote->blockchain_id}}

# Blockchain data
<strong>Id:</strong> {{$vote->id}}  
<strong>Candidate Id:</strong> {{$vote->candidate_id}}  
<strong>Election Id:</strong> {{$vote->election_id}}  
<strong>Candidate Id:</strong> {{$vote->candidate_id}}  
<strong>Voter Hash:</strong> {{$vote->voter_hash}}  
<strong>Created at:</strong> {{$vote->created_at}}  
<strong>Updated at:</strong> {{$vote->updated_at}}

<x-mail::button :url="config('app.frontend_url').'/vote/verify/'.$vote->blockchain_id">
Verify Vote
</x-mail::button>

Please keep this email for your records.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
