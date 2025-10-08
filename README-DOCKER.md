# Docker Setup for Genealogy Application

This guide explains how to run the Genealogy application in Docker containers.

## Prerequisites

-   Docker Desktop (Windows/Mac) or Docker Engine (Linux)
-   Docker Compose
-   Git

## Quick Start

1. **Clone the repository**

    ```bash
    git clone https://github.com/MGeurts/genealogy.git
    cd genealogy
    ```

2. **Copy environment file**

    ```bash
    cp .env.docker .env
    ```

3. **Edit the .env file**

    - Set `APP_KEY` (will be generated in step 5)
    - Configure your database credentials
    - Set your mail server settings
    - Update `APP_URL` to match your domain

4. **Build and start containers**

    ```bash
    docker-compose up -d --build
    ```

5. **Generate application key**

    ```bash
    docker-compose exec app php artisan key:generate
    ```

6. **Run migrations and seed database**

    ```bash
    docker-compose exec app php artisan migrate:fresh --seed
    ```

7. **Create storage link**

    ```bash
    docker-compose exec app php artisan storage:link
    ```

8. **Access the application**

    Open your browser and navigate to: `http://localhost:8000`

## Container Structure

The Docker setup includes the following containers:

-   **app**: PHP 8.3-FPM with Nginx, running the Laravel application
-   **db**: MySQL 8.0 database server
-   **redis**: Redis server for caching and queues

## Services

The application container runs multiple services via Supervisor:

-   **Nginx**: Web server
-   **PHP-FPM**: PHP FastCGI Process Manager
-   **Laravel Scheduler**: Runs scheduled tasks
-   **Laravel Queue Workers**: Processes queued jobs (2 workers)

## Configuration Files

### Required Docker Files

Create these files in your project:

```
genealogy/
├── Dockerfile
├── docker-compose.yml
├── .dockerignore
├── .env.docker
└── docker/
    ├── nginx/
    │   └── default.conf
    ├── supervisor/
    │   └── supervisord.conf
    └── mysql/
        └── my.cnf
```

## Common Commands

### Start containers

```bash
docker-compose up -d
```

### Stop containers

```bash
docker-compose down
```

### View logs

```bash
docker-compose logs -f app
```

### Access application container

```bash
docker-compose exec app bash
```

### Run artisan commands

```bash
docker-compose exec app php artisan [command]
```

### Run composer commands

```bash
docker-compose exec app composer [command]
```

### Rebuild containers

```bash
docker-compose down
docker-compose up -d --build
```

## Database Management

### Access MySQL

```bash
docker-compose exec db mysql -u genealogy -p
```

### Backup database

```bash
docker-compose exec db mysqldump -u genealogy -p genealogy > backup.sql
```

### Restore database

```bash
docker-compose exec -T db mysql -u genealogy -p genealogy < backup.sql
```

## Troubleshooting

### Permission Issues

If you encounter permission errors:

```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache
docker-compose exec app chmod -R 755 /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/bootstrap/cache
```

### Clear caches

```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan route:clear
```

### Rebuild assets

```bash
docker-compose exec app npm run build
```

## HTTPS Configuration

For production use with HTTPS:

1. Use a reverse proxy like Nginx or Traefik in front of the containers
2. Configure SSL certificates (Let's Encrypt recommended)
3. Update `APP_URL` in `.env` to use `https://`
4. Set `APP_FORCE_HTTPS=true` in `.env`

Example using Nginx reverse proxy:

```nginx
server {
    listen 443 ssl http2;
    server_name genealogy.yourdomain.com;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    location / {
        proxy_pass http://localhost:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

## Performance Optimization

### For production environments:

1. **Optimize Composer autoloader**

    ```bash
    docker-compose exec app composer install --optimize-autoloader --no-dev
    ```

2. **Cache configuration**

    ```bash
    docker-compose exec app php artisan config:cache
    docker-compose exec app php artisan route:cache
    docker-compose exec app php artisan view:cache
    ```

3. **Optimize images** - Ensure uploaded images are optimized

4. **Increase worker processes** - Edit `docker/supervisor/supervisord.conf` and increase `numprocs` for queue workers

## Production Deployment

For production deployment:

1. Update `.env` with production values
2. Set `APP_ENV=production`
3. Set `APP_DEBUG=false`
4. Use strong database passwords
5. Configure proper mail server
6. Set up automated backups
7. Configure log rotation
8. Set up monitoring
9. Use HTTPS with valid SSL certificates
10. Configure firewall rules

## Maintenance

### Update application

```bash
git pull origin main
docker-compose down
docker-compose up -d --build
docker-compose exec app php artisan migrate
docker-compose exec app php artisan cache:clear
```

## Support

For issues and questions:

-   GitHub Issues: https://github.com/MGeurts/genealogy/issues
-   Documentation: Check the application's built-in help system
