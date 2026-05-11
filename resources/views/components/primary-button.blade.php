<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn app-btn-primary']) }}>
    {{ $slot }}
</button>
