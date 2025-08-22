<button 
    {{ $attributes->merge([
        'type' => 'submit', 
        'class' => 'inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all ease-in-out duration-300 hover:scale-105 hover:shadow-lg active:scale-95 transform',
        'style' => 'background-color: #13395d; border: 2px solid #fbbf0f; color: white;'
    ]) }}
    onmouseover="this.style.backgroundColor='#fbbf0f'; this.style.borderColor='#13395d'; this.style.color='black';"
    onmouseout="this.style.backgroundColor='#13395d'; this.style.borderColor='#fbbf0f'; this.style.color='white';"
    onfocus="this.style.backgroundColor='#fbbf0f'; this.style.borderColor='#13395d'; this.style.color='black';"
    onblur="this.style.backgroundColor='#13395d'; this.style.borderColor='#fbbf0f'; this.style.color='white';"
>
    {{ $slot }}
</button>