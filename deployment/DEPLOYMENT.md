# 🚀 Auto-Deployment Guide - NDRIS-Nepal

This guide shows how to deploy NDRIS-Nepal with **automatic updates from GitHub**.

---

## 📋 Prerequisites

1. ✅ GitHub account
2. ✅ Push your code to a GitHub repository
3. ✅ Choose a deployment platform (see below)

---

## 🎯 Step 1: Push to GitHub

```bash
cd /Users/bir_65/NDRIS-Nepal/NDRIS-Nepal/ndrismap

# Initialize git (if not already done)
git init

# Add all files
git add .

# Commit
git commit -m "Initial commit - NDRIS Nepal"

# Create repo on GitHub, then:
git remote add origin https://github.com/YOUR_USERNAME/ndris-nepal.git
git branch -M main
git push -u origin main
```

---

## 🌟 Option 1: Deploy to Replit (EASIEST)

### Setup
1. Go to [replit.com](https://replit.com)
2. Sign in with GitHub
3. Click **"+ Create Repl"**
4. Select **"Import from GitHub"**
5. Enter your repo URL: `https://github.com/YOUR_USERNAME/ndris-nepal`
6. Click **"Import from GitHub"**

### Auto-Deployment
- ✅ Already configured via `.replit` file
- Every GitHub push auto-updates the Repl
- Click **"Run"** to start
- Get instant live URL: `https://ndris-nepal.YOUR_USERNAME.repl.co`

### Database Setup
```bash
# In Replit Shell:
mysql -u root -p
# (Press Enter for no password)

# Then paste and run:
CREATE DATABASE ndris_nepal;
USE ndris_nepal;
source sql/schema.sql;
exit;
```

**Live URL**: Auto-generated, always-on free tier ✅

---

## 🚂 Option 2: Deploy to Railway

### Setup
1. Go to [railway.app](https://railway.app)
2. Sign in with GitHub
3. Click **"New Project"**
4. Select **"Deploy from GitHub repo"**
5. Choose `ndris-nepal` repository
6. Railway auto-detects PHP via `railway.json`

### Add Database
1. Click **"+ New"** → **"Database"** → **"Add MySQL"**
2. Copy connection details
3. Update `php/db.php` with Railway's database credentials (or use environment variables)

### Auto-Deployment
- ✅ Automatic on every `git push`
- Get custom URL: `https://ndris-nepal.up.railway.app`

### Environment Variables
```
DB_HOST=mysql.railway.internal
DB_USER=root
DB_PASS=<from Railway>
DB_NAME=railway
```

**Free Tier**: $5 credit/month, then pay-as-you-go

---

## 🎨 Option 3: Deploy to Render

### Setup
1. Go to [render.com](https://render.com)
2. Sign in with GitHub
3. Click **"New +"** → **"Web Service"**
4. Connect your `ndris-nepal` repository
5. Render auto-detects settings via `render.yaml`

### Add Database
1. Create **"New +"** → **"PostgreSQL"** (or use external MySQL)
2. For MySQL: Use external service like [PlanetScale](https://planetscale.com) (free)
3. Add connection string to Environment Variables

### Auto-Deployment
- ✅ Automatic on every `git push` to main branch
- Get URL: `https://ndris-nepal.onrender.com`

**Free Tier**: Yes, with auto-sleep after inactivity

---

## 🎈 Option 4: Deploy to Heroku

### Setup
```bash
# Install Heroku CLI
brew install heroku/brew/heroku  # macOS
# or download from heroku.com

# Login
heroku login

# Create app
heroku create ndris-nepal

# Add MySQL addon (free tier)
heroku addons:create jawsdb:kitefin

# Push to Heroku
git push heroku main
```

### Auto-Deployment via GitHub
1. Go to Heroku Dashboard
2. Select your app → **"Deploy"** tab
3. Connect to GitHub repository
4. Enable **"Automatic Deploys"** from `main` branch

### Database Setup
```bash
# Get database credentials
heroku config:get JAWSDB_URL

# Import schema
heroku run bash
mysql -h <host> -u <user> -p<pass> <db_name> < sql/schema.sql
```

**Pricing**: Eco plan ($5/month) or hobby tier

---

## 🔧 Environment Variables Setup

Update `php/db.php` to read from environment variables:

```php
// Use environment variables if available (for cloud deployment)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'ndris_nepal');
```

Then set variables in your platform's dashboard.

---

## 🔄 How Auto-Deployment Works

```
You push to GitHub
       ↓
Platform detects push (webhook)
       ↓
Platform pulls latest code
       ↓
Platform runs build/start commands
       ↓
Your app updates automatically ✅
```

**Every time you `git push`, your live site updates in 30-60 seconds!**

---

## 📊 Comparison Table

| Platform | Free Tier | Auto-Deploy | Database | Difficulty |
|----------|-----------|-------------|----------|-----------|
| **Replit** | ✅ Yes | ✅ Yes | ✅ Built-in | ⭐ Easy |
| **Railway** | $5 credit | ✅ Yes | ✅ Add-on | ⭐⭐ Medium |
| **Render** | ✅ Yes | ✅ Yes | ⚠️ External | ⭐⭐ Medium |
| **Heroku** | ⚠️ Paid | ✅ Yes | ✅ Add-on | ⭐⭐⭐ Hard |

---

## 🎯 Recommended for Students: **Replit**

**Why?**
- ✅ Completely free
- ✅ One-click GitHub import
- ✅ Built-in MySQL
- ✅ Always-on free tier
- ✅ Instant deployment
- ✅ No credit card required

---

## 🐛 Troubleshooting

### Database Connection Failed
```bash
# Check environment variables are set
# Verify database credentials
# Ensure MySQL service is running
```

### Port Issues
```bash
# Most platforms set $PORT automatically
# Make sure your start command uses: -S 0.0.0.0:$PORT
```

### File Permissions
```bash
# Ensure proper permissions on deploy
chmod -R 755 public/
chmod -R 755 php/
```

---

## 📝 Quick Deploy Checklist

- [ ] Code pushed to GitHub
- [ ] `.replit` or `railway.json` configured
- [ ] Database credentials set
- [ ] Schema imported to cloud database
- [ ] Auto-deploy enabled
- [ ] Test live URL
- [ ] Change default admin password

---

## 🎉 Success!

Once deployed, share your link:
- **Live Demo**: `https://your-app.platform.com`
- **GitHub Repo**: `https://github.com/YOUR_USERNAME/ndris-nepal`

Every `git push` now updates your live site automatically! 🚀

---

## 📧 Need Help?

1. Check platform-specific docs
2. Review deployment logs
3. Test locally first: `php -S localhost:8000 -t public`

**Pro Tip**: Start with Replit for simplest setup, then scale to Railway/Render if needed.
