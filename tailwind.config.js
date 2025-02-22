export default {
    presets: [
        // Filament Table Builder
        require('./vendor/filament/support/tailwind.config.preset'),
        // TallStackUI
        require('./vendor/tallstackui/tallstackui/tailwind.config.js')
    ],
    content: [
        // Filament Table Builder
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        // TallStackUI
        './vendor/tallstackui/tallstackui/src/**/*.php',
        './app/View/Components/**/*.php',
        './app/Providers/AppServiceProvider.php',
    ],
}
