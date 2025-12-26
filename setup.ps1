# PowerShell setup script for Blog Application

Write-Host "🚀 Setting up Blog Application..." -ForegroundColor Green

# Start Docker containers
Write-Host "📦 Starting Docker containers..." -ForegroundColor Yellow
docker-compose up -d

# Wait for services to be ready
Write-Host "⏳ Waiting for services to be ready..." -ForegroundColor Yellow
Start-Sleep -Seconds 10

# Install backend dependencies
Write-Host "📥 Installing backend dependencies..." -ForegroundColor Yellow
docker-compose exec -T backend composer install

# Generate application key
Write-Host "🔑 Generating application key..." -ForegroundColor Yellow
docker-compose exec -T backend php artisan key:generate

# Generate JWT secret
Write-Host "🔐 Generating JWT secret..." -ForegroundColor Yellow
docker-compose exec -T backend php artisan jwt:secret

# Run migrations
Write-Host "🗄️  Running database migrations..." -ForegroundColor Yellow
docker-compose exec -T backend php artisan migrate

# Create storage link
Write-Host "🔗 Creating storage link..." -ForegroundColor Yellow
docker-compose exec -T backend php artisan storage:link

# Install frontend dependencies
Write-Host "📥 Installing frontend dependencies..." -ForegroundColor Yellow
docker-compose exec -T frontend npm install

Write-Host "✅ Setup complete!" -ForegroundColor Green
Write-Host ""
Write-Host "🌐 Access the application at:" -ForegroundColor Cyan
Write-Host "   Frontend: http://localhost:3000" -ForegroundColor White
Write-Host "   Backend API: http://localhost:8000/api" -ForegroundColor White
Write-Host ""
Write-Host "📝 Run tests with: docker-compose exec backend php artisan test" -ForegroundColor Cyan

