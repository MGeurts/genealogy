# README-LANGUAGES.md

## How to Add a Language (`xx`) to the Project

Assume the new language has the language code `xx`.

---

### 1. Add Translation Files

#### a. Automated

-   To create new language, use the command : `php artisan translate:lang {from} {to}`<br/>
    Example for Persian (fa) : `php artisan translate:lang en fa
-   Save the file `/lang/locales/xx/json.json` from the package [Laravel-Lang/lang](https://github.com/Laravel-Lang/lang/tree/main/locales) as `/lang/xx.json`.
-   Run the following command to check for missing translations:

    ```bash
    php artisan translations:check --excludedDirectories=vendor
    ```

#### b. Manualy

-   Copy all files from `/lang/en/` to `/lang/xx/` and translate them.
-   Save the file `/lang/locales/xx/json.json` from the package [Laravel-Lang/lang](https://github.com/Laravel-Lang/lang/tree/main/locales) as `/lang/xx.json`.
-   Run the following command to check for missing translations:

    ```bash
    php artisan translations:check --excludedDirectories=vendor
    ```

---

### 2. Update `config/app.php`

Add the new language to the `available_locales` array:

```php
'available_locales' => [
    'Deutsch'           => 'de',       // German
    'English'           => 'en',       // English
    'Español'           => 'es',       // Spanish
    'Français'          => 'fr',       // French
    'हिन्दी'              => 'hi',       // Hindi
    'Bahasa Indonesian' => 'id',       // Indonesian
    'Nederlands'        => 'nl',       // Dutch
    'Português'         => 'pt',       // Portuguese
    'Türkçe'            => 'tr',       // Turkish
    'Việt Nam'          => 'vi',       // Vietnamese
    '中文简体'           => 'zh_cn',    // Chinese (Simplified)
    'NewLanguage'       => 'xx',       // Replace with actual name
],
```

---

### 3. Update `config/laravellocalization.php`

Uncomment or add the new language in the `supportedLocales` array.

---

### 4. Update `app/Countries.php`

Add the language code to the `LOCALE_TO_COUNTRY` constant:

```php
private const array LOCALE_TO_COUNTRY = [
    'de'    => 'de',
    'en'    => 'en',
    'es'    => 'es',
    'fr'    => 'fr',
    'hi'    => 'hi',
    'id'    => 'id',
    'nl'    => 'nl',
    'pt'    => 'pt',
    'tr'    => 'tr',
    'zh_cn' => 'zh',
    'xx'    => 'xx', // Replace with appropriate country code
];
```

---

### 5. Translate Markdown files

-   Copy all files from `/resources/markdown/en/` to `/resources/markdown/xx/` and translate them.
-   Copy the file `/resources/markdown/policy.en.md` to `/resources/markdown/policy.xx.md` and translate it.
-   Copy the file `/resources/markdown/terms.en.md` to `/resources/markdown/terms.xx.md` and translate it.
