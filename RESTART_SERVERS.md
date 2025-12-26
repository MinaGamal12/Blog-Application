# Restart Instructions

The backend server needs to be restarted for the route changes to take effect.

## To restart the servers:

### Backend (Terminal 1):
```powershell
cd "C:\Users\Mina Gamal\Desktop\Blog Application\backend"
php artisan serve
```

### Frontend (Terminal 2):
```powershell
cd "C:\Users\Mina Gamal\Desktop\Blog Application\frontend"
npm start
```

## After restarting:

1. The backend should be running on: http://localhost:8000
2. The frontend should be running on: http://localhost:3000
3. Try logging in again with:
   - Email: test@example.com
   - Password: password123

## If you still get 404 errors:

1. Check that the backend is running: http://localhost:8000
2. Check the browser console for CORS errors
3. Verify the API URL in frontend/src/services/api.js matches your backend URL

