@component('mail::message')

{{ $content }}

Thanks,<br>
{{ $userName }}
@endcomponent
