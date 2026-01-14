# 📦 NDRIS-Nepal Deployment Files

This folder contains all deployment guides and scripts for the NDRIS-Nepal project.

## 📄 Files in This Folder

- **[DEPLOYMENT.md](DEPLOYMENT.md)** - Complete deployment guide for all platforms
- **[QUICKSTART.md](QUICKSTART.md)** - 5-minute quick deploy guide
- **[WORKFLOW.md](WORKFLOW.md)** - Visual deployment workflow diagram
- **[deploy.sh](deploy.sh)** - Automated push-to-GitHub script

## ⚙️ Configuration Files (in ndrismap root)

These files **MUST stay in the `ndrismap/` root folder** for platforms to detect them:

- **`.replit`** - Replit configuration
- **`replit.nix`** - Replit dependencies
- **`railway.json`** - Railway configuration
- **`render.yaml`** - Render configuration
- **`Procfile`** - Heroku configuration
- **`composer.json`** - PHP dependencies

---

## 🚀 Quick Deploy

```bash
cd ../deployment
./deploy.sh
```

Then follow the instructions to deploy on your chosen platform.

---

## 📚 Documentation Links

- [Full Deployment Guide](DEPLOYMENT.md)
- [Quick Start (5 min)](QUICKSTART.md)
- [Workflow Diagram](WORKFLOW.md)

---

**Choose your platform**: Replit • Railway • Render • Heroku
