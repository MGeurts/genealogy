# UPDATE.md

# How to Update Your Local Laravel Project to the Latest Version

## ✅ If Your Local Copy Is a Git Clone of the Repo

1. **Open terminal** in your project directory:

    ```bash
    cd path/to/your/genealogy
    ```

2. **Check for uncommitted changes**:

    ```bash
    git status
    ```

    > If you have changes, commit them, stash them (`git stash`), or discard them.

3. **Pull the latest changes** from GitHub:

    ```bash
    git pull origin main
    ```

4. **Update dependencies**:

    ```bash
    composer install
    npm install && npm run build
    ```

5. **Run any new database migrations**:

    ```bash
    php artisan migrate
    ```

---

## ❗ If Your Local Copy Is NOT a Git Clone

If you just downloaded a ZIP or copied the folder, do the following:

1. **Backup your project and any custom data**.

2. **Backup the old folder as a ZIP file**.

3. **Backup the database**.

4. **Delete the old folder and clone the latest version**:

    ```bash
    git clone https://github.com/MGeurts/genealogy.git
    ```

5. Replace your old `.env`, restore any backed-up data.

---

## ✅ After Updating or Cloning

Run the following commands to set everything up:

```bash
composer install
php artisan migrate
php artisan config:cache
php artisan route:cache
```

> Ensure correct file permissions and `.env` settings.

---
