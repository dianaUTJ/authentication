<x-mail::message>
{{-- Greeting --}}
@lang('Hello!'),
{{ __('Tu usuario a sido eliminado.') }}
@lang('Regards'),
{{ config('app.name') }}
<x-slot:subcopy>
@lang('Si necesitas mas información, contáctanos.')
 </x-slot:subcopy>
</x-mail::message>
