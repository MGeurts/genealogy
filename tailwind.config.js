export default {
    presets: [
        // TallStackUI
        require('./vendor/tallstackui/tallstackui/tailwind.config.js')
    ],
    content: [
        // TallStackUI
        './vendor/tallstackui/tallstackui/src/**/*.php',
        './app/View/Components/**/*.php',
        './app/Providers/AppServiceProvider.php',
    ],
}
