# 🇳🇵 NDRIS-Nepal

**Nepal Disaster Response & Inclusive Services - Civic Tech Platform**

A full-stack PHP web application for tracking disasters, citizen grievances, and government policies across Nepal's districts, with an interactive map-based Urban Neglect Index calculator.

---

## 📁 Project Structure

```
NDRIS-Nepal/
├── deployment/               # 📦 Deployment guides and scripts
│   ├── README.md            # Deployment documentation overview
│   ├── DEPLOYMENT.md        # Full platform deployment guide
│   ├── QUICKSTART.md        # 5-minute quick deploy guide
│   ├── WORKFLOW.md          # Visual deployment workflow
│   └── deploy.sh            # Automated GitHub push script
│
├── ndrismap/                # Main application folder
│   ├── .replit              # Replit configuration (required in root)
│   ├── replit.nix           # Replit dependencies
│   ├── railway.json         # Railway configuration
│   ├── render.yaml          # Render configuration
│   ├── Procfile             # Heroku configuration
│   ├── composer.json        # PHP dependencies
│   ├── README.md            # Main project README
│   ├── SETUP.md             # Local setup guide
│   ├── .gitignore           # Git ignore rules
│   ├── sql/                 # Database files
│   ├── php/                 # Backend modules
│   ├── public/              # Frontend pages
│   ├── map/                 # SVG map files
│   └── docs/                # Project documentation
```

---

## ✅ File Organization Complete

Your deployment files are now organized:

### 📁 Deployment Documentation (in `/deployment/`)
- [DEPLOYMENT.md](../deployment/DEPLOYMENT.md)
- [QUICKSTART.md](../deployment/QUICKSTART.md)  
- [WORKFLOW.md](../deployment/WORKFLOW.md)
- [deploy.sh](../deployment/deploy.sh)
- [README.md](../deployment/README.md)

### ⚙️ Config Files (remain in ndrismap root)
These **must stay in** [ndrismap/](ndrismap/) for platforms to auto-detect:
- `.replit`, `replit.nix` - Replit
- `railway.json` - Railway
- `render.yaml` - Render
- `Procfile` - Heroku
- `composer.json` - PHP dependencies

---

## ✅ Updated Files

- Created [deployment/README.md](deployment/README.md) - Deployment folder overview
- Updated [ndrismap/README.md](ndrismap/README.md) - Links to ../deployment/
- Updated [deploy.sh](deployment/deploy.sh) - Works from new location

---

## 📂 Final Structure

```
/NDRIS-Nepal/
├── deployment/                   # 📦 Deployment guides & scripts
│   ├── README.md                 # Deployment folder overview
│   ├── DEPLOYMENT.md             # Complete deployment guide
│   ├── QUICKSTART.md             # 5-minute quick start
│   ├── WORKFLOW.md               # Visual workflow
│   └── deploy.sh                 # Auto-deploy script ✨
│
└── ndrismap/                     # Main application folder
    ├── .replit                   # Replit config (must stay here)
    ├── replit.nix                # Replit dependencies
    ├── railway.json              # Railway config
    ├── render.yaml               # Render config
    ├── Procfile                  # Heroku config
    ├── composer.json             # PHP dependencies
    ├── sql/                      # Database schema
    ├── php/                      # Backend modules
    ├── map/                      # SVG map files
    ├── public/                   # Frontend pages
    └── docs/                     # Project documentation
```

**Organization:**
- ✅ **Deployment docs** → `deployment/` folder (outside ndrismap)
- ✅ **Platform configs** → Stay in `ndrismap/` root (required by platforms)
- ✅ **Application code** → Stays in `ndrismap/`

Updated the deploy.sh script to work from the new location. You can now run it with:

```bash
cd deployment
./deploy.sh
```

All documentation links have been updated to point to `../deployment/` folder!