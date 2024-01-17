<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' => 'inline-flex rounded 
                        
        bg-warning 
    
        px-3 pb-2 pt-2.5 
    
        text-sm font-medium leading-normal text-white 
    
        shadow-[0_4px_9px_-4px_#e4a11b] transition duration-150 ease-in-out 
    
        hover:bg-warning-600 
        hover:shadow-[0_8px_9px_-4px_rgba(228,161,27,0.3),0_4px_18px_0_rgba(228,161,27,0.2)] 
    
        focus:bg-warning-600 
        focus:shadow-[0_8px_9px_-4px_rgba(228,161,27,0.3),0_4px_18px_0_rgba(228,161,27,0.2)] 
        focus:outline-none focus:ring-2 
        focus:ring-offset-2 
        focus:ring-warning-700 
    
        active:bg-warning-700 
        active:shadow-[0_8px_9px_-4px_rgba(228,161,27,0.3),0_4px_18px_0_rgba(228,161,27,0.2)] 
        
        dark:shadow-[0_4px_9px_-4px_rgba(228,161,27,0.5)] 
        dark:hover:shadow-[0_8px_9px_-4px_rgba(228,161,27,0.2),0_4px_18px_0_rgba(228,161,27,0.1)] 
        dark:focus:shadow-[0_8px_9px_-4px_rgba(228,161,27,0.2),0_4px_18px_0_rgba(228,161,27,0.1)] 
        dark:active:shadow-[0_8px_9px_-4px_rgba(228,161,27,0.2),0_4px_18px_0_rgba(228,161,27,0.1)]',
    ]) }}
    data-te-ripple-init>
    {{ $slot }}
</button>
