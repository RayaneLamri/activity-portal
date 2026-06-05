<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn app-btn-danger']) }}>
    {{ $slot }}
</button>
