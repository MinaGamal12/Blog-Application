#!/bin/bash

echo "🚀 Setting up Blog Application..."

# Start Docker containers
echo "📦 Starting Docker containers..."
docker-compose up -d

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 10

# Install backend dependencies
echo "📥 Installing backend dependencies..."
docker-compose exec -T backend composer install

# Generate application key
echo "🔑 Generating application key..."
docker-compose exec -T backend php artisan key:generate

# Generate JWT secret
echo "🔐 Generating JWT secret..."
docker-compose exec -T backend php artisan jwt:secret

# Run migrations
echo "🗄️  Running database migrations..."
docker-compose exec -T backend php artisan migrate

# Create storage link
echo "🔗 Creating storage link..."
docker-compose exec -T backend php artisan storage:link

# Install frontend dependencies
echo "📥 Installing frontend dependencies..."
docker-compose exec -T frontend npm install

echo "✅ Setup complete!"
echo ""
echo "🌐 Access the application at:"
echo "   Frontend: http://localhost:3000"
echo "   Backend API: http://localhost:8000/api"
echo ""
echo "📝 Run tests with: docker-compose exec backend php artisan test"

