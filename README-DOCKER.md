# Docker Setup Guide

This guide provides instructions for running the Genealogy application using Docker and Docker Compose.

## Overview

The Docker setup consists of:

-   **Application Container**: PHP 8.4 with FPM and Nginx (based on [Server Side Up Docker images](https://serversideup.net/open-source/docker-php/))
-   **Database Container**: MySQL 8.4
-   **Vite Container**: Node.js 23 Alpine for frontend development server

## Prerequisites

-   [Docker](https://docs.docker.com/get-docker/) (20.10 or higher)
-   [Docker Compose](https://docs.docker.com/compose/install/) (2.0 or higher)

## Quick Start

### 1. Clone the Repository

```bash
git clone https://github.com/MGeurts/genealogy.git
cd genealogy
```

### 2. Configure Environment

Copy the docker environment file and configure it:

```bash
cp .env.docker .env
```

Edit `.env` and update the database settings:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=genealogy
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Build and Start Containers

```bash
docker compose up -d
```

This will:

-   Build the application container
-   Pull the MySQL 8.4 image
-   Start both containers in the background

### 4. Install Dependencies and Initialize

Run the following commands to set up the application:

```bash
# Install Composer dependencies
# docker compose exec app composer install
docker compose exec app composer install --no-scripts

# Generate application key
docker compose exec app php artisan key:generate

# Create storage link
docker compose exec app php artisan storage:link

# Run database migrations and seeders
docker compose exec app php artisan migrate:fresh --seed
```

The Vite service will automatically install npm packages and start the development server when you run `docker compose up -d`.

### 5. Access the Application

Open your browser and navigate to:

-   Application: `http://localhost:8080`
-   Vite Dev Server: `http://localhost:5173` (for frontend development with hot module replacement)

## Configuration Options

### Ports

You can customize the ports by setting environment variables in your `.env` file:

```env
# Application port (default: 8080)
APP_PORT=8080

# Database external port (default: 3306)
DB_EXTERNAL_PORT=3306

# Vite dev server port (default: 5173)
VITE_PORT=5173
```

Then restart the containers:

```bash
docker compose down
docker compose up -d
```

### Database

The database data is persisted in `./.docker/data` directory. This ensures your data is preserved between container restarts.

## Docker Images

### Development Image

The development image (`target: development` in docker compose.yml) includes:

-   PHP 8.4 with FPM and Nginx
-   PHP extensions: gd, xsl, exif, intl, pcov
-   User ID mapping for file permissions

### Production Image

For production deployments, use the `deploy` target which includes:

-   Optimized PHP configuration (OPcache enabled)
-   Laravel autorun automations:
    -   Database migrations
    -   Storage link creation
    -   Event caching
    -   Route caching
    -   View caching
    -   Config caching
-   Production-ready Composer dependencies

### CI Image

A separate CLI-based image is available for continuous integration with:

-   PHP 8.4 CLI
-   All necessary extensions for testing
-   Higher memory limit (2GB)

## Common Commands

### Container Management

```bash
# Start containers
docker compose up -d

# Stop containers
docker compose down

# Restart containers
docker compose restart

# View logs
docker compose logs -f app
docker compose logs -f db

# Access application shell
docker compose exec app bash

# Access MySQL shell
docker compose exec db mysql -u root genealogy
```

### Laravel Commands

```bash
# Run Artisan commands
docker compose exec app php artisan [command]

# Examples:
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan queue:work
```

### Composer Commands

```bash
# Install dependencies
docker compose exec app composer install

# Update dependencies
docker compose exec app composer update

# Dump autoload
docker compose exec app composer dump-autoload
```

### NPM Commands

The Vite container automatically runs `npm install` and `npm run dev` on startup. You can also run npm commands manually:

```bash
# Restart Vite dev server
docker compose restart vite

# View Vite logs
docker compose logs -f vite

# Run npm commands in the Vite container
docker compose exec vite npm install

# Build for production (run in Vite container)
docker compose exec vite npm run build

# Alternatively, you can still run npm commands in the app container if needed
docker compose exec app npm install
docker compose exec app npm run build
```

### Frontend Development

The Vite container provides hot module replacement (HMR) for frontend development:

```bash
# The Vite dev server starts automatically with docker compose up
# Access it at http://localhost:5173

# Watch Vite server logs
docker compose logs -f vite

# Restart the Vite server if needed
docker compose restart vite

# For production builds, stop the Vite container and build assets
docker compose stop vite
docker compose exec vite npm run build
```

**Note:** When developing, your Laravel application at `http://localhost:8080` will automatically use the Vite dev server for hot module replacement. Changes to CSS and JavaScript files will be reflected immediately in your browser.

### Testing

```bash
# Run tests
docker compose exec app php artisan test

# Run Pest tests
docker compose exec app ./vendor/bin/pest

# Run tests with coverage
docker compose exec app php artisan test --coverage
```

## Troubleshooting

### Permission Issues

If you encounter permission issues with files, you may need to rebuild the development image with your user ID:

```bash
docker compose build --build-arg USER_ID=$(id -u) --build-arg GROUP_ID=$(id -g)
docker compose up -d
```

### Database Connection Issues

1. Ensure the database container is running:

    ```bash
    docker compose ps
    ```

2. Check database logs:

    ```bash
    docker compose logs db
    ```

3. Verify database credentials in `.env` match the docker compose.yml configuration

### Storage Permissions

If you have issues with file uploads or storage:

```bash
docker compose exec app php artisan storage:link
docker compose exec app chmod -R 775 storage bootstrap/cache
```

### Container Not Starting

1. Check for port conflicts:

    ```bash
    # Check if ports 8080 or 3306 are already in use
    lsof -i :8080
    lsof -i :3306
    ```

2. View container logs:

    ```bash
    docker compose logs
    ```

3. Rebuild containers:
    ```bash
    docker compose down -v
    docker compose build --no-cache
    docker compose up -d
    ```

## Production Deployment

For production deployment:

1. Build the production image:

    ```bash
    docker build --target deploy -t genealogy:latest .
    ```

2. Use environment-specific configuration:

    ```bash
    cp .env.example .env.production
    # Edit .env.production with production settings
    ```

3. Run with production environment:
    ```bash
    docker run -d \
      --name genealogy-app \
      --env-file .env.production \
      -p 443:8443 \
      -v ./storage:/var/www/html/storage \
      genealogy:latest
    ```

The production image automatically runs:

-   Database migrations
-   Storage link creation
-   Laravel optimizations (route, view, config caching)

## Data Backup

### Database Backup

```bash
# Create a backup
docker compose exec db mysqldump -u root genealogy > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore from backup
docker compose exec -T db mysql -u root genealogy < backup_20240101_120000.sql
```

### Application Backup

The application includes a built-in Backup Manager accessible through the interface.

## Cleaning Up

### Remove Containers and Networks

```bash
docker compose down
```

### Remove Containers, Networks, and Volumes

```bash
docker compose down -v
```

### Remove Images

```bash
docker compose down --rmi all
```

## Additional Resources

-   [Server Side Up PHP Docker Images](https://serversideup.net/open-source/docker-php/)
-   [Laravel Docker Documentation](https://laravel.com/docs/deployment#docker)
-   [Docker Compose Documentation](https://docs.docker.com/compose/)

## Support

For issues related to:

-   Application functionality: See main [README.md](README.md)
-   Docker setup: Submit an issue on [GitHub](https://github.com/MGeurts/genealogy/issues)
-   Server Side Up images: Visit [Server Side Up Documentation](https://serversideup.net/open-source/docker-php/docs)
