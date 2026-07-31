@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-[#40E0D0] focus:border-[#2bbdb3] focus:ring-[#40E0D0] rounded-md shadow-sm text-[#40E0D0]']) }}>
