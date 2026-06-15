<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-6 py-2.5 glass border-[rgba(255,255,255,0.08)] text-[#7A8E72] hover:border-[#C8A96E]/40 hover:text-[#C8A96E] font-medium text-xs uppercase tracking-widest rounded-full transition-all duration-200 focus:outline-none disabled:opacity-30']) }}>
    {{ $slot }}
</button>
