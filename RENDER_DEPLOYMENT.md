# Deploy Hotel Reservation System to Render

This guide will help you deploy your Laravel application to Render for **FREE**.

## Prerequisites

1. A GitHub account
2. A Render account (sign up at https://render.com)
3. Your project pushed to a GitHub repository

## Step 1: Push Your Code to GitHub

```bash
git init
git add .
git commit -m "Initial commit - ready for Render deployment"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git
git push -u origin main
```

## Step 2: Create a Free PostgreSQL Database on Render

1. Go to https://dashboard.render.com
2. Click **"New +"** → **"PostgreSQL"**
3. Configure:
   - **Name**: `hotel-reservation-db`
   - **Database**: `hotel_reservation`
   - **User**: (auto-generated)
   - **Region**: Choose closest to you
   - **PostgreSQL Version**: 16 (or latest)
   - **Plan**: **Free** (0.25GB storage, expires after 90 days)
4. Click **"Create Database"**
5. Wait for database to be created
6. **Copy the "Internal Database URL"** (starts with `postgresql://`)

## Step 3: Create a Web Service on Render

1. Click **"New +"** → **"Web Service"**
2. Connect your GitHub repository
3. Configure the web service:

### Basic Settings:
- **Name**: `hotel-reservation-system` (or your choice)
- **Region**: Same as your database
- **Branch**: `main`
- **Root Directory**: Leave blank
- **Runtime**: `PHP`
- **Build Command**: `bash build.sh`
- **Start Command**: `bash start.sh`

### Instance Type:
- **Plan**: **Free** (512MB RAM, sleeps after 15 min inactivity)

### Environment Variables:
Click **"Add Environment Variable"** and add these:

```
APP_NAME="Hotel Reservation System"
APP_ENV=production
APP_KEY=base64:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
APP_DEBUG=false
APP_URL=https://hotel-reservation-system.onrender.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=pgsql
DATABASE_URL=[PASTE YOUR INTERNAL DATABASE URL HERE]

SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=log
```

**Important Notes:**
- For `APP_KEY`: Run `php artisan key:generate --show` locally and copy the output
- For `DATABASE_URL`: Paste the Internal Database URL from Step 2
- Replace `APP_URL` with your actual Render URL

### Advanced Settings (Optional):
- **Auto-Deploy**: Yes (deploys automatically on git push)

4. Click **"Create Web Service"**

## Step 4: Wait for Deployment

1. Render will start building your application (takes 5-10 minutes first time)
2. Watch the logs for any errors
3. Once complete, you'll see "Your service is live 🎉"

## Step 5: Access Your Application

Your app will be available at:
```
https://hotel-reservation-system.onrender.com
```
(Replace with your actual service name)

## Important Notes

### Free Tier Limitations:

**Web Service:**
- Sleeps after 15 minutes of inactivity
- First request after sleep takes ~30-60 seconds
- 750 hours/month free

**PostgreSQL:**
- 1GB storage
- Expires after 90 days
- Need to migrate to paid plan ($7/month) or use external free DB

### Alternative Free Database Options:

If Render's DB expires, use:

1. **Neon** (https://neon.tech) - Free forever, 0.5GB
2. **Supabase** (https://supabase.com) - Free tier available
3. **ElephantSQL** (https://www.elephantsql.com) - 20MB free

Just update the `DATABASE_URL` environment variable.

## Troubleshooting

### Build fails:
- Check that `build.sh` has Unix line endings (LF not CRLF)
- Verify PHP version in composer.json matches Render's

### App won't start:
- Check logs in Render dashboard
- Verify `APP_KEY` is set
- Ensure database connection is correct

### Database connection errors:
- Use `DATABASE_URL` instead of separate DB variables
- Verify database is in same region as web service

### 500 errors:
- Set `APP_DEBUG=true` temporarily to see errors
- Check storage permissions

## Updating Your App

After making changes:
```bash
git add .
git commit -m "Your update message"
git push origin main
```

Render will automatically rebuild and redeploy.

## Custom Domain (Optional)

1. Go to your web service settings
2. Click "Custom Domains"
3. Add your domain
4. Update DNS records as instructed

## Need Help?

- Render Docs: https://render.com/docs
- Laravel Docs: https://laravel.com/docs
- Check logs in Render dashboard
