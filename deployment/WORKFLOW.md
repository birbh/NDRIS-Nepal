# 🔄 Auto-Deployment Workflow

## Visual Flow

```
┌─────────────────────────────────────────────────────────────┐
│  STEP 1: Development (Your Computer)                        │
│  ──────────────────────────────────────────────────────    │
│                                                              │
│  1. Edit code in ndrismap/                                  │
│  2. Test locally: http://localhost/ndrismap                 │
│  3. Run: ./deploy.sh                                        │
│                                                              │
│     ↓ git add . && git commit && git push                   │
│                                                              │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│  STEP 2: GitHub (Code Storage)                              │
│  ──────────────────────────────────────────────────────    │
│                                                              │
│  Repository: github.com/YOUR_USERNAME/ndris-nepal           │
│  ✅ Version controlled                                      │
│  ✅ Backup in cloud                                         │
│  ✅ Collaboration ready                                     │
│                                                              │
│     ↓ Webhook triggers deployment                           │
│                                                              │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│  STEP 3: Deployment Platform (Choose One)                   │
│  ──────────────────────────────────────────────────────    │
│                                                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │   Replit     │  │   Railway    │  │    Render    │     │
│  │              │  │              │  │              │     │
│  │  • Free ✅   │  │  • $5 free   │  │  • Free ✅   │     │
│  │  • Instant   │  │  • Fast      │  │  • Auto-SSL  │     │
│  │  • Built-in  │  │  • DB add-on │  │  • Custom    │     │
│  │    MySQL     │  │              │  │    domains   │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
│                                                              │
│  Platform reads:                                            │
│  • .replit / railway.json / render.yaml                    │
│  • composer.json (PHP dependencies)                        │
│  • Procfile (start command)                                │
│                                                              │
│     ↓ Auto-deploy in 30-60 seconds                         │
│                                                              │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│  STEP 4: Live Application (Internet)                        │
│  ──────────────────────────────────────────────────────    │
│                                                              │
│  🌐 https://ndris-nepal.your-platform.com                   │
│                                                              │
│  ✅ Publicly accessible                                     │
│  ✅ Auto-updates on git push                                │
│  ✅ SSL/HTTPS enabled                                       │
│  ✅ Database connected                                      │
│                                                              │
│  Pages live:                                                │
│  • /public/index.php       (Homepage + Map)                │
│  • /public/report.php      (Submit Grievance)              │
│  • /public/dashboard.php   (Admin Panel)                   │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

## Timeline Example

```
09:00 AM → You edit disaster_crud.php
09:05 AM → git push to GitHub
09:05 AM → GitHub receives push
09:06 AM → Platform detects change (webhook)
09:06 AM → Platform pulls latest code
09:07 AM → Platform restarts with new code
09:07 AM → ✅ Live site updated!
```

**Total time: ~2 minutes from push to live!**

## What Each File Does

```
┌──────────────────────┬────────────────────────────────────────┐
│ File                 │ Purpose                                │
├──────────────────────┼────────────────────────────────────────┤
│ .replit              │ Tells Replit how to run PHP            │
│ replit.nix           │ Installs PHP on Replit                 │
│ railway.json         │ Configures Railway deployment          │
│ render.yaml          │ Configures Render deployment           │
│ Procfile             │ Heroku start command                   │
│ composer.json        │ PHP dependencies (auto-detected)       │
│ deploy.sh            │ Your quick-push script                 │
└──────────────────────┴────────────────────────────────────────┘
```

## Update Cycle

```
Local Changes
     ↓
Test on XAMPP (localhost)
     ↓
git push to GitHub
     ↓
Platform auto-deploys
     ↓
Test on live URL
     ↓
Share with users! 🎉
```

## Environment Variables

Platforms can set these without changing code:

```bash
DB_HOST=mysql.railway.internal
DB_USER=railway
DB_PASS=secret123
DB_NAME=railway

# Your php/db.php reads these automatically!
# Fallback to localhost if not set
```

## Benefits

✅ **Version Control**: All changes tracked in GitHub
✅ **Auto-Deploy**: No manual uploads via FTP
✅ **Rollback**: Revert to any previous version
✅ **Collaboration**: Multiple developers can contribute
✅ **Always Online**: Professional hosting
✅ **Free SSL**: HTTPS automatically enabled
✅ **No Server Management**: Platform handles everything

## Comparison: Old vs New Way

### ❌ Old Way (Manual)
1. Edit code locally
2. Open FileZilla/FTP
3. Upload changed files one by one
4. Hope you didn't miss any files
5. Test live site
6. Fix issues manually
7. Re-upload
**Time: ~20-30 minutes per update**

### ✅ New Way (Auto-Deploy)
1. Edit code locally
2. `git push`
3. Wait 60 seconds
4. Site is updated!
**Time: ~2 minutes per update**

## Security Note

⚠️ **Never commit these to GitHub:**
- Real database passwords
- API keys
- Admin credentials (beyond defaults)

✅ **Use environment variables instead!**

Set them in platform dashboard:
- Replit: Secrets tab
- Railway: Variables tab
- Render: Environment tab

---

**You're all set! Push to GitHub, deploy once, and all future updates are automatic!** 🚀
