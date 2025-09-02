<style>
.ripple-button {
    position: relative;
    overflow: hidden;
}

.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    pointer-events: none;
}

@keyframes ripple-animation {
    to {
        transform: scale(4);
        opacity: 0;
    }
}
</style>

<button
    {{ $attributes->merge([
        'type' => 'submit', 
        'class' => 'ripple-button inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all ease-in-out duration-300 hover:scale-105 hover:shadow-lg active:scale-95 transform',
        'style' => 'background-color: #13395d; border: 2px solid #fbbf0f; color: white;'
    ]) }}
    onmouseover="this.style.backgroundColor='#fbbf0f'; this.style.border='2px solid #13395d'; this.style.color='black'; createRipple(event, this);"
    onmouseout="this.style.backgroundColor='#13395d'; this.style.border='2px solid #fbbf0f'; this.style.color='white';"
    onfocus="this.style.backgroundColor='#fbbf0f'; this.style.border='2px solid #13395d'; this.style.color='black';"
    onblur="this.style.backgroundColor='#13395d'; this.style.border='2px solid #fbbf0f'; this.style.color='white';"
>
    {{ $slot }}
</button>

<script>
function createRipple(event, element) {
    const button = element;
    const circle = document.createElement('span');
    const diameter = Math.max(button.clientWidth, button.clientHeight);
    const radius = diameter / 2;
    
    const rect = button.getBoundingClientRect();
    const x = event.clientX - rect.left - radius;
    const y = event.clientY - rect.top - radius;
    
    circle.style.width = circle.style.height = `${diameter}px`;
    circle.style.left = `${x}px`;
    circle.style.top = `${y}px`;
    circle.classList.add('ripple');
    
    const ripple = button.getElementsByClassName('ripple')[0];
    if (ripple) {
        ripple.remove();
    }
    
    button.appendChild(circle);
    
    setTimeout(() => {
        circle.remove();
    }, 600);
}
</script>









<!-- <button
    {{ $attributes->merge([
        'type' => 'submit', 
        'class' => 'inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all ease-in-out duration-300 hover:scale-105 hover:shadow-lg active:scale-95 transform',
        'style' => 'background-color: #13395d; border: 2px solid #fbbf0f; color: white;'
    ]) }}
    onmouseover="this.style.backgroundColor='#fbbf0f'; this.style.border='2px solid #13395d'; this.style.color='black';"
    onmouseout="this.style.backgroundColor='#13395d'; this.style.border='2px solid #fbbf0f'; this.style.color='white';"
    onfocus="this.style.backgroundColor='#fbbf0f'; this.style.border='2px solid #13395d'; this.style.color='black';"
    onblur="this.style.backgroundColor='#13395d'; this.style.border='2px solid #fbbf0f'; this.style.color='white';"
>
    {{ $slot }}
</button> -->