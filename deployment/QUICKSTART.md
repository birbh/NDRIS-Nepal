# 🎯 Quick Deploy - NDRIS-Nepal

## ⚡ Fastest Setup (Under 5 Minutes)

### Step 1: Push to GitHub
```bash
cd ndrismap
./deploy.sh
```

### Step 2: Deploy to Replit
1. Go to **https://replit.com**
2. Click **"Import from GitHub"**
3. Paste: `https://github.com/YOUR_USERNAME/ndris-nepal`
4. Click **"Import"**
5. Click **"Run"** button

### Step 3: Setup Database
In Replit Shell:
```bash
mysql -u root -p
# Press Enter (no password)
```

Then:
```sql
CREATE DATABASE ndris_nepal;
USE ndris_nepal;
source sql/schema.sql;
exit;
```

### Step 4: Done! 🎉
Your app is live at: `https://ndris-nepal.YOUR_USERNAME.repl.co`

---

## 🔄 Auto-Deployment
Every time you push to GitHub, your site updates automatically:
```bash
git add .
git commit -m "Update feature"
git push
# Wait 30 seconds - live site updates! ✅
```

---

## 📱 Access Your Live Site
- **Homepage**: `/public/index.php`
- **Submit Grievance**: `/public/report.php`
- **Admin Dashboard**: `/public/dashboard.php`
  - Login: `admin` / `admin123`

---

## 🆘 Quick Fixes

**Database connection error?**
```bash
# Check environment variables in Replit:
# Secrets tab → Add:
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=ndris_nepal
```

**Port already in use?**
- Replit handles this automatically
- Just click "Run" again

**Schema not imported?**
```bash
cd ndrismap
mysql -u root -p ndris_nepal < sql/schema.sql
```

---

## 🔗 Alternative Platforms

**Railway**: https://railway.app → "Deploy from GitHub"
**Render**: https://render.com → "New Web Service"

All use the same GitHub repository - auto-deploy enabled!

---

## ✨ That's It!

Your NDRIS-Nepal app is now:
- ✅ On GitHub
- ✅ Auto-deploying
- ✅ Live on the internet
- ✅ Updating on every push

See **DEPLOYMENT.md** for detailed instructions.
