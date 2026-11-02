<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn app-form-button']) }}>
    {{ $slot }}
</button>
