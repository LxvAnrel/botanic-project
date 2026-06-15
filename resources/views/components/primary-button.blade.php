<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-7 py-2.5 bg-[#C8A96E] hover:bg-[#D4BA8A] text-[#0E1A0B] font-semibold text-xs uppercase tracking-widest rounded-full transition-all duration-200 shadow-[0_0_20px_rgba(200,169,110,0.25)] hover:shadow-[0_0_30px_rgba(200,169,110,0.4)] focus:outline-none disabled:opacity-40']) }}>
    {{ $slot }}
</button>
