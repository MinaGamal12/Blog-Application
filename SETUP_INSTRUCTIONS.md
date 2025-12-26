# Setup Instructions

## Prerequisites

- Docker
- Docker Compose
- Git

## Initial Setup

1. **Clone the repository** (if applicable) or navigate to the project directory

2. **Start Docker containers**:
   ```bash
   docker-compose up -d
   ```

3. **Install backend dependencies**:
   ```bash
   docker-compose exec backend composer install
   ```

4. **Generate application key**:
   ```bash
   docker-compose exec backend php artisan key:generate
   ```

5. **Generate JWT secret**:
   ```bash
   docker-compose exec backend php artisan jwt:secret
   ```

6. **Run migrations**:
   ```bash
   docker-compose exec backend php artisan migrate
   ```

7. **Create storage link** (for file uploads):
   ```bash
   docker-compose exec backend php artisan storage:link
   ```

8. **Install frontend dependencies**:
   ```bash
   docker-compose exec frontend npm install
   ```

## Access the Application

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000/api
- **MySQL**: localhost:3306
- **Redis**: localhost:6379

## Running Tests

```bash
docker-compose exec backend php artisan test
```

## Stopping the Application

```bash
docker-compose down
```

To also remove volumes:
```bash
docker-compose down -v
```

## Troubleshooting

### Backend not starting
- Check if ports 8000, 3306, 6379 are available
- Check Docker logs: `docker-compose logs backend`

### Database connection issues
- Ensure MySQL container is running: `docker-compose ps`
- Check database credentials in `docker-compose.yml`

### Frontend not connecting to backend
- Verify `REACT_APP_API_URL` in `docker-compose.yml`
- Check CORS settings in Laravel (if needed)

### Storage link issues
- Run: `docker-compose exec backend php artisan storage:link`
- Ensure `storage/app/public` directory exists

## Development

### Viewing logs
```bash
docker-compose logs -f backend
docker-compose logs -f frontend
docker-compose logs -f queue
docker-compose logs -f scheduler
```

### Running artisan commands
```bash
docker-compose exec backend php artisan [command]
```

### Running npm commands
```bash
docker-compose exec frontend npm [command]
```

### Database access
```bash
docker-compose exec mysql mysql -u blog_user -p blog_db
# Password: blog_password
```

