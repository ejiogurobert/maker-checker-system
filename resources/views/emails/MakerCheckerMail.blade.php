<x-mail::message>
    {{$data['title']}}

    {{$data['body']}}

    <x-mail::button :url="{{'127.0.0.1:8000/api/v1/approve/'.$data['request_id']}}">
        Approve this request
    </x-mail::button>

    <x-mail::button :url="{{'127.0.0.1:8000/api/v1/decline/'.$data['request_id']}}">
        Decline this request
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
