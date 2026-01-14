# NDRIS-Nepal - Setup Guide

## 🚀 Installation Instructions

### Prerequisites
- XAMPP (Apache + PHP 8+ + MySQL)
- Web browser
- Text editor (optional, for modifications)

### Step 1: Setup Database

1. Start XAMPP and ensure Apache and MySQL are running
2. Open phpMyAdmin: `http://localhost/phpmyadmin`
3. Import the database schema:
   - Click "SQL" tab
   - Copy and paste the contents of `sql/schema.sql`
   - Click "Go" to execute
   - This will create the `ndris_nepal` database with all tables and sample data

### Step 2: Configure Database Connection

The database connection is already configured in `php/db.php` with default XAMPP settings:
- Host: `localhost`
- User: `root`
- Password: `` (empty)
- Database: `ndris_nepal`

If your MySQL settings are different, edit these constants in `php/db.php`.

### Step 3: Place Files

Copy the entire `ndrismap` folder to your XAMPP `htdocs` directory:
```
C:\xampp\htdocs\ndrismap\     (Windows)
/Applications/XAMPP/htdocs/ndrismap/   (Mac)
/opt/lampp/htdocs/ndrismap/    (Linux)
```

### Step 4: Access the Application

Open your web browser and navigate to:
- **Public Homepage**: `http://localhost/ndrismap/public/index.php`
- **Submit Grievance**: `http://localhost/ndrismap/public/report.php`
- **Admin Dashboard**: `http://localhost/ndrismap/public/dashboard.php`

### Step 5: Admin Login

Default admin credentials:
- **Username**: `admin`
- **Password**: `admin123`

**⚠️ IMPORTANT**: Change this password in production! Use the password change function in the admin dashboard.

---

## 📁 Project Structure

```
ndrismap/
├── sql/
│   └── schema.sql              # Database schema with sample data
├── php/
│   ├── db.php                  # Database connection
│   ├── auth.php                # Authentication system
│   ├── disaster_crud.php       # Disaster records CRUD
│   ├── grievance_crud.php      # Grievance management
│   ├── policy_crud.php         # Policy tracking
│   └── neglect_index.php       # Neglect score calculator
├── map/
│   ├── nepal.svg               # Interactive Nepal map
│   ├── map.js                  # Map interaction logic
│   └── map.css                 # Map styling
├── public/
│   ├── index.php               # Public homepage with map
│   ├── report.php              # Grievance submission form
│   ├── dashboard.php           # Admin dashboard
│   └── dashboard.js            # Dashboard JavaScript
└── docs/
    └── ...                     # Project documentation
```

---

## 🎯 Features

### Public Features
- **Interactive Nepal Map**: Click districts to view neglect data
- **Grievance Submission**: Citizens can report issues
- **Real-time Statistics**: View disaster, grievance, and policy counts
- **District Information**: Detailed metrics per district

### Admin Features
- **Secure Login**: Session-based authentication
- **Disaster Management**: Add/view/delete disaster records
- **Grievance Management**: Review and update grievance status
- **Policy Tracking**: Manage policy effectiveness scores
- **Neglect Index**: View and recalculate district neglect scores
- **Dashboard Statistics**: Real-time data overview

---

## 🔧 Usage Guide

### For Citizens (Public Access)

1. **View Map Data**:
   - Visit the homepage
   - Click on any district on the map
   - View detailed neglect index and metrics

2. **Submit a Grievance**:
   - Navigate to "Submit Grievance"
   - Select category and district
   - Describe the issue (minimum 10 characters)
   - Submit the form

### For Administrators

1. **Login**:
   - Go to Admin Dashboard
   - Enter credentials
   - Access full CRUD functionality

2. **Manage Disasters**:
   - Switch to "Disasters" tab
   - Click "+ Add New Disaster"
   - Fill in details and submit
   - View, edit, or delete existing records

3. **Review Grievances**:
   - Switch to "Grievances" tab
   - Change status using dropdown (Pending → Reviewed → Resolved)
   - Delete spam or test entries

4. **Track Policies**:
   - Switch to "Policies" tab
   - Add new policy records
   - Update effectiveness scores

5. **Monitor Neglect Index**:
   - Switch to "Neglect Index" tab
   - View computed scores for all districts
   - Click "Recalculate All" to update indices after data changes

---

## 📊 Urban Neglect Index Formula

The system uses a heuristic formula to compute district neglect:

```
Neglect Score = (Grievances × 0.4) + (Disasters × 0.3) - (Policy Score × 0.3)
```

**Interpretation**:
- **0-5**: Low neglect (Green)
- **5-10**: Medium neglect (Yellow)
- **10+**: High neglect (Red)

**Note**: This is a simplified educational model. Real-world applications would require more complex algorithms, historical data normalization, and expert validation.

---

## 🔒 Security Features

- MySQLi prepared statements (SQL injection prevention)
- Input sanitization (XSS prevention)
- Password hashing (bcrypt via `password_hash()`)
- Session-based authentication
- Admin-only access controls

---

## 🐛 Troubleshooting

### Database Connection Error
- Verify MySQL is running in XAMPP
- Check `php/db.php` credentials
- Ensure `ndris_nepal` database exists

### Blank Page / White Screen
- Check Apache error logs: `xampp/apache/logs/error.log`
- Enable PHP error reporting in `php.ini`
- Verify file permissions

### Map Not Loading
- Ensure SVG path is correct in `index.php`
- Check browser console for JavaScript errors
- Clear browser cache

### Admin Login Not Working
- Verify admin user exists in database
- Check `admin_users` table
- Default password: `admin123`

---

## 🎓 Academic Context

This project demonstrates:
- **Full-stack PHP development** (procedural, no frameworks)
- **Database design** (normalized schema with relationships)
- **CRUD operations** (Create, Read, Update, Delete)
- **Authentication systems** (session management)
- **Data visualization** (SVG maps, interactive UI)
- **Security best practices** (prepared statements, input validation)

**Ideal for**:
- +2/Bachelor level PHP projects
- Civic tech portfolio pieces
- Web development coursework
- Viva/defense presentations

---

## 📝 License

See LICENSE file in parent directory.

---

## 🤝 Contributing

This is an educational project. Improvements welcome via:
- Code optimization
- Additional districts on map
- Enhanced visualizations
- Mobile responsiveness
- Accessibility features

---

## 📧 Support

For questions or issues:
1. Check documentation in `docs/` folder
2. Review inline code comments
3. Test with sample data provided

---

**Built with ❤️ for transparent governance and civic engagement**
