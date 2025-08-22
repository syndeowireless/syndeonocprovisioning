<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#13395d] border-2 border-[#fbbf0f] rounded-lg font-medium text-sm text-white transition-all duration-200 ease-in-out mt-4 mb-4 min-w-[120px] submit-button focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>