# NDRIS-Nepal
**National Disaster, Responsibility & Impact System for Nepal**

A full-stack civic-tech web application that integrates disaster records, citizen grievances, policy tracking, and urban neglect indices into an interactive map-based system.

## 🎯 Project Overview

NDRIS-Nepal is an educational civic technology prototype built with:
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Backend**: Procedural PHP 8
- **Database**: MySQL/MariaDB with MySQLi
- **Visualization**: Interactive SVG-based Nepal map

## ✨ Key Features

### Public Features
- 🗺️ **Interactive Nepal Map** - Click districts to view detailed metrics
- 📝 **Citizen Grievance System** - Submit and track community issues
- 📊 **Real-time Statistics** - View disaster, grievance, and policy data
- 🎨 **Color-coded Neglect Index** - Visual representation of district-level governance gaps

### Admin Features
- 🔐 **Secure Authentication** - Session-based admin login
- 📋 **Disaster Management** - CRUD operations for historical disaster records
- 👥 **Grievance Review** - Status updates and moderation
- 📈 **Policy Tracking** - Monitor policy effectiveness by district
- 🔄 **Neglect Index Calculator** - Automated computation and recalculation

## 🚀 Quick Start

See **[SETUP.md](SETUP.md)** for detailed local installation.

### Quick Install (XAMPP - Local)
1. Import `sql/schema.sql` into phpMyAdmin
2. Copy `ndrismap/` to `htdocs/`
3. Access: `http://localhost/ndrismap/public/index.php`
4. Admin login: `admin` / `admin123`

### 🚀 Deploy Live (Auto-updates from GitHub)
**Recommended**: Use **[Replit](https://replit.com)** for instant deployment

```bash
# 1. Push to GitHub
cd ../deployment
./deploy.sh

# 2. Import from GitHub on Replit
# 3. Your app is live! ✅
```

📖 **Full deployment guide**: [../deployment/DEPLOYMENT.md](../deployment/DEPLOYMENT.md) | ⚡ [Quick start](../deployment/QUICKSTART.md)

**Supports auto-deployment on**: Replit • Railway • Render • Heroku

## 📁 Project Structure

```
ndrismap/
├── sql/          # Database schema and sample data
├── php/          # Backend CRUD modules (disaster, grievance, policy, auth)
├── map/          # SVG map, JavaScript, and CSS
├── public/       # Frontend pages (index, report, dashboard)
└── docs/         # Project documentation
```

## 📊 Urban Neglect Index

The system computes a heuristic neglect score for each district:

**Formula**: `(Grievances × 0.4) + (Disasters × 0.3) - (Policy Score × 0.3)`

**Levels**:
- 🟢 **0-5**: Low neglect
- 🟡 **5-10**: Medium neglect
- 🔴 **10+**: High neglect

## 🔒 Security Features

- ✅ MySQLi prepared statements (SQL injection prevention)
- ✅ Input sanitization (XSS prevention)
- ✅ Password hashing (bcrypt)
- ✅ Session-based authentication
- ✅ Admin access controls

## 🎓 Educational Value

This project demonstrates:
- Full-stack PHP development (procedural, no frameworks)
- Database design and normalization
- Complete CRUD operations
- Authentication and session management
- Data visualization and interactive UIs
- Security best practices

**Perfect for**: +2/Bachelor CS projects, web development portfolios, civic tech demonstrations

## 📖 Documentation

- [SETUP.md](SETUP.md) - Installation and configuration guide
- [docs/COPILOT_CONTEXT.txt](docs/COPILOT_CONTEXT.txt) - AI assistant guidelines
- [docs/DATABASE_SCHEMA.md](docs/DATABASE_SCHEMA.md) - Database structure
- [docs/FILE_STRUCTURE.md](docs/FILE_STRUCTURE.md) - Project organization
- [docs/SYSTEM_ARCHITECTURE.md](docs/SYSTEM_ARCHITECTURE.md) - Technical architecture

## 🤝 Contributing

This is an educational project. Contributions welcome for:
- Additional districts on the map
- Mobile responsiveness
- Enhanced visualizations
- Accessibility improvements
- Code optimization

## ⚠️ Disclaimer

This system uses heuristic calculations for educational purposes. The Urban Neglect Index is a simplified model and should not be used for actual policy decisions without proper validation, historical data analysis, and expert consultation.

## 📝 License

See LICENSE file in parent directory.

---

**Built for transparent governance, civic engagement, and data-driven accountability** 🇳🇵
