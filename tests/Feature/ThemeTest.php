<?php

declare(strict_types=1);

test('the application initializes the saved theme before the first paint', function (): void {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee("localStorage.getItem('dark-theme')", false)
        ->assertSee("classList.toggle('dark', isDark)", false)
        ->assertSee("tallstackui_darkTheme({ default: 'system' })", false)
        ->assertSee("storedTheme === null || storedTheme === 'system'", false);
});
