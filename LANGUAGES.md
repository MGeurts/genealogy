# LANGUAGES.md

# How to add a language XX to the project

Let's asume the new language has the language code xx.

## 1. Provide and check the needed translation files in **/lang/xx/** by doing the following:

### a. Copy all files in **/lang/en/** to **/lang/xx/** and translate them

### b. Copy [/lang/locales/xx/json.json](https://github.com/Laravel-Lang/lang/tree/main/locales) to **/lang/xx.json**

### c. In a terminal window in your project root, run **php artisan translations:check --excludedDirectories=vendor** to check all translations

---

## 2. Edit **/config/app.php** and add the new language to the **available_locales** array

    ```
    'available_locales' => [
        'Deutsch'    => 'de',               // German
        'English'    => 'en',               // English
        'Español'    => 'es',               // Spanish
        'Français'   => 'fr',               // French
        'Nederlands' => 'nl',               // Dutch
        'Português'  => 'pt',               // Portuguese
        'Türkçe'     => 'tr',               // Turkish
        'Việt Nam'   => 'vi',               // Vietnamese
        '中文简体'   => 'zh_cn',             // Chinees
    ],
    ```

---

## 3. Edit **/config/laravellocalization.php** and uncomment the new language in the **supportedLocales** array

---

## 4. Edit **/app/Countries.php** and add the new language to the **LOCALE_TO_COUNTRY** constant

    ```
    private const array LOCALE_TO_COUNTRY = [
        'de'    => 'de',
        'en'    => 'en',
        'es'    => 'es',
        'fr'    => 'fr',
        'nl'    => 'nl',
        'pt'    => 'pt',
        'tr'    => 'tr',
        'zh_cn' => 'zh',
    ];
    ```

---
