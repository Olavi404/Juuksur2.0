# Local Completion + Render Redeploy (Windows)

This guide does two things:
1. Gives a clean checklist to complete the missing Laravel skeleton.
2. Gives an exact command sequence to publish a fully deployable version and redeploy on Render.

## A. Clean Checklist

1. Install required tools on your Windows machine:
- PHP 8.3+
- Composer 2+
- Node.js 20+ and npm
- Git

2. Confirm tools are available in PowerShell:
- php -v
- composer --version
- node -v
- npm -v
- git --version

3. Build a fresh Laravel 12 skeleton in a temporary folder.

4. Install Laravel Breeze (Blade) in that skeleton.

5. Copy this project code into that skeleton.

6. Install dependencies and build frontend assets.

7. Generate app key.

8. Commit and push updated full project to GitHub.

9. Redeploy from Render Blueprint.

10. Verify public booking page and admin login page.

## B. Exact Command Sequence (PowerShell)

Run this exactly in PowerShell:

$Repo = "C:\Users\Olavi\Desktop\Juuksur2.0"
$Temp = "C:\Users\Olavi\Desktop\Juuksur2.0_laravel_full"

# 1) Clean temp folder if it already exists
if (Test-Path $Temp) { Remove-Item -Recurse -Force $Temp }

# 2) Create a full Laravel 12 app
composer create-project laravel/laravel $Temp

# 3) Install Breeze auth scaffold
Set-Location $Temp
composer require laravel/breeze --dev
php artisan breeze:install blade

# 4) Install JS deps and build assets once
npm install
npm run build

# 5) Copy your MVP files from current repo into the full Laravel app
Copy-Item "$Repo\app\*" "$Temp\app\" -Recurse -Force
Copy-Item "$Repo\bootstrap\app.php" "$Temp\bootstrap\app.php" -Force
Copy-Item "$Repo\database\migrations\*" "$Temp\database\migrations\" -Recurse -Force
Copy-Item "$Repo\database\seeders\*" "$Temp\database\seeders\" -Recurse -Force
Copy-Item "$Repo\resources\views\*" "$Temp\resources\views\" -Recurse -Force
Copy-Item "$Repo\routes\web.php" "$Temp\routes\web.php" -Force
Copy-Item "$Repo\tests\*" "$Temp\tests\" -Recurse -Force
Copy-Item "$Repo\README.md" "$Temp\README.md" -Force
Copy-Item "$Repo\SCHOOL_SUMMARY.md" "$Temp\SCHOOL_SUMMARY.md" -Force
Copy-Item "$Repo\RENDER_DEPLOY.md" "$Temp\RENDER_DEPLOY.md" -Force
Copy-Item "$Repo\DEPLOY_NEXT_STEPS.md" "$Temp\DEPLOY_NEXT_STEPS.md" -Force
Copy-Item "$Repo\render.yaml" "$Temp\render.yaml" -Force

# 6) Generate key and run local checks
Set-Location $Temp
Copy-Item ".env.example" ".env" -Force
php artisan key:generate
php artisan route:list

# 7) Replace repo contents with complete app
Set-Location "C:\Users\Olavi\Desktop"
Get-ChildItem "$Repo" -Force | Where-Object { $_.Name -ne ".git" } | Remove-Item -Recurse -Force
Copy-Item "$Temp\*" "$Repo\" -Recurse -Force

# 8) Commit and push full deployable app
Set-Location $Repo
git add -A
git commit -m "Convert repo to full Laravel app for Render deployment"
git push

## C. Render Redeploy Steps

1. Open Render Dashboard.
2. If Blueprint resources already exist, click Manual Deploy on the web service.
3. If not created yet, use New + -> Blueprint and connect repository Olavi404/Juuksur2.0.
4. Confirm environment values from render.yaml are present.
5. Set APP_URL to your Render web service URL.
6. Deploy and monitor logs.

## D. Post-Deploy Verification

1. Open home page and submit one booking.
2. Check available times update correctly for same hairdresser/date.
3. Try duplicate booking for same slot and confirm friendly error message appears.
4. Open /login and sign in as admin.
5. Open /admin/bookings and verify list and date filter work.

## E. Optional Quick Test Locally (before push)

After setting PostgreSQL in .env:
- php artisan migrate:fresh --seed
- php artisan test
