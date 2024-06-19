<x-mail::message>
# Elections Started. Vote Now!

Hello {{ $user->name }}.

The Elections just ended.  
Here are the Results

<x-mail::button :url="config('app.frontend_url')">
Vote Now!
</x-mail::button>

<table>
    <thead>
        <tr>
            <th width='200' style='text-align: left;'>Candidate</th>
            <th width='200' style='text-align: left;'>Political Party</th>
            <th width='200' style='text-align: left;'>Votes Count</th>
        </tr>
    </thead>
    <tbody>
        @forelse($results as $result)
            <tr>
                <td>{{ $result['candidate']['name'] }}</td>
                <td>{{ $result['candidate']['political_party'] }}</td>
                <td>{{ $result['total_votes'] }}</td>
            </tr>
        @empty
        <tr>
            <td colspan='3' style='text-align: center;'>This election had no vote</td>
        </tr>
        @endforelse
    </tbody>
</table>
<br><br>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
