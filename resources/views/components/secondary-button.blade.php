<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn app-btn-secondary']) }}>
    {{ $slot }}
</button>
