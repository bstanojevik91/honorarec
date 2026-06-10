<x-mail::message>
Здраво {{ $company->name }},

Имате нова апликација за вашиот оглас "{{ $jobListing->title }}" на Honorarec.mk.

Податоци за кандидатот:

Име и презиме: {{ $application->full_name }}
Телефон: {{ $application->phone }}
Град: {{ $application->city }}

Порака од кандидатот:
{{ filled($application->message) ? $application->message : 'Кандидатот не оставил дополнителна порака.' }}

CV:
@if ($cvUrl)
<x-mail::button :url="$cvUrl">
Отвори CV
</x-mail::button>

{{ $cvUrl }}
@else
Кандидатот нема прикачено CV.
@endif

Апликацијата е испратена на: {{ $application->created_at?->format('d.m.Y H:i') }}

Можете да се најавите во вашиот employer dashboard за да ги прегледате сите апликации.

Поздрав,<br>
Honorarec.mk
</x-mail::message>
