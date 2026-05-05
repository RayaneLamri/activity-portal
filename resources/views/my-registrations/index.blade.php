<x-app-layout>
@foreach ($registrations as $registration)
    <p>
        {{ $registration->activity->title }}
        -
        {{ $registration->status }}
    </p>
@endforeach

{{ $registrations->links() }}
</x-app-layout>