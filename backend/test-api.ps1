# Test API endpoint
$body = @{
    email = "test@example.com"
    password = "password123"
} | ConvertTo-Json

try {
    $response = Invoke-RestMethod -Uri "http://localhost:8000/api/login" -Method POST -ContentType "application/json" -Body $body
    Write-Host "SUCCESS!" -ForegroundColor Green
    Write-Host "Message: $($response.message)"
    Write-Host "User: $($response.user.name) ($($response.user.email))"
    Write-Host "Token: $($response.token.Substring(0, 30))..."
} catch {
    Write-Host "ERROR: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.Exception.Response) {
        $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
        $responseBody = $reader.ReadToEnd()
        Write-Host "Response: $responseBody"
    }
}

