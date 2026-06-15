@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full glass border-white/[0.08] text-[#EDE0CC] placeholder-[#3A5E2D]/60 text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-[#C8A96E]/50 transition-all duration-200']) }}>
