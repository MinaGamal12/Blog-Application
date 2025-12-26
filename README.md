# Blog Application

A full-stack blog application built with Laravel (PHP) backend and React frontend, running on Docker Compose.

## Features

- **User Authentication**: JWT-based authentication with user profile images
- **Post Management**: CRUD operations for blog posts with tags
- **Comments System**: Add, edit, and delete comments on posts
- **Auto-Expiry**: Posts automatically deleted after 24 hours
- **Tags Management**: Manage tags for each post
- **Responsive UI**: Modern React frontend with real-time updates

## Tech Stack

### Backend
- Laravel (PHP)
- MySQL Database
- Redis (Queue & Cache)
- JWT Authentication
- Laravel Queue Workers
- Laravel Scheduler

### Frontend
- React
- Axios for API calls
- React Router
- JWT stored in localStorage

### Infrastructure
- Docker Compose
- Nginx (if needed)

## Project Structure

```
Blog Application/
├── backend/          # Laravel API
├── frontend/         # React Application
└── docker-compose.yml
```

## Prerequisites

- Docker
- Docker Compose

## Installation & Setup

1. Clone the repository:
```bash
git clone <repository-url>
cd Blog-Application
```

2. Start all services:
```bash
docker-compose up -d
```

3. Install backend dependencies:
```bash
docker-compose exec backend composer install
```

4. Generate application key:
```bash
docker-compose exec backend php artisan key:generate
```

5. Generate JWT secret:
```bash
docker-compose exec backend php artisan jwt:secret
```

6. Run migrations:
```bash
docker-compose exec backend php artisan migrate
```

7. Create storage link (for file uploads):
```bash
docker-compose exec backend php artisan storage:link
```

8. Install frontend dependencies:
```bash
docker-compose exec frontend npm install
```

For detailed setup instructions, see [SETUP_INSTRUCTIONS.md](./SETUP_INSTRUCTIONS.md)

## Access Points

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000/api
- **MySQL**: localhost:3306
- **Redis**: localhost:6379

## API Documentation

See [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) for detailed API endpoints.

## Testing

Run backend tests:
```bash
docker-compose exec backend php artisan test
```

## Default Credentials

After seeding (if implemented):
- Email: admin@example.com
- Password: password

## Environment Variables

Backend environment variables are configured in `docker-compose.yml`. For production, use `.env` files.

## License

MIT

