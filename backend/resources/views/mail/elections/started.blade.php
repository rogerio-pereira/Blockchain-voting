<x-mail::message>
# Elections Started. Vote Now!

Hello {{ $user->name }}.

The Elections just started, you can vote using your computer or phone.
This election will be running from {{ $election->start_date }} to {{ $election->end_date }}.

<x-mail::button :url="config('app.frontend_url')">
Vote Now!
</x-mail::button>

<table>
    <thead>
        <tr>
            <th width='200' style='text-align: left;'>Candidate</th>
            <th width='200' style='text-align: left;'>Political Party</th>
        </tr>
    </thead>
    <tbody>
        @foreach($election->candidates as $candidate)
            <tr>
                <td>{{ $candidate->name }}</td>
                <td>{{ $candidate->political_party }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<br><br>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
