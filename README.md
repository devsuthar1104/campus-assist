# Campus Assist - Campus Management System

A comprehensive campus management system built with PHP and MySQL for managing student complaints, lost & found items, and administrative tasks.

## 🚀 Features
- 🔐 **User Authentication** - Separate login for Students and Admin
- 📋 **Complaint Management** - Students can submit and track complaints
- 🔍 **Lost & Found System** - Report and search for lost items
- 👥 **Student Dashboard** - Personal dashboard for students
- 🛠️ **Admin Panel** - Complete administrative control
- 📊 **Real-time Status Updates** - Track complaint progress

## 🛠️ Tech Stack
- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 7.0+
- **Database**: MySQL 5.7+
- **Server**: Apache (XAMPP/WAMP/LAMP)

## 📋 Prerequisites
Before installation, make sure you have:
- XAMPP/WAMP/LAMP installed on your system
- PHP 7.0 or higher
- MySQL 5.7 or higher
- Web browser (Chrome, Firefox, Safari, Edge)

## 🚀 Installation Guide

### Step 1: Download XAMPP (If not installed)
1. Go to https://www.apachefriends.org/
2. Download XAMPP for your operating system
3. Install XAMPP with default settings
4. Start **Apache** and **MySQL** services from XAMPP Control Panel

### Step 2: Clone the Repository
```bash
git clone https://github.com/devsuthar1104/campus-assist.git
```

**OR Download ZIP:**
1. Click on **"Code"** button (green button on GitHub)
2. Select **"Download ZIP"**
3. Extract the ZIP file

### Step 3: Move Project to Server Directory
1. Copy the `campus-assist` folder
2. Paste it in your server directory:
   - **Windows (XAMPP)**: `C:\xampp\htdocs\`
   - **Mac (XAMPP)**: `/Applications/XAMPP/htdocs/`
   - **Linux (LAMP)**: `/var/www/html/`

### Step 4: Create Database
1. Open your web browser
2. Go to: http://localhost/phpmyadmin
3. Click **"New"** on the left sidebar
4. Enter database name: `campus_assist`
5. Click **"Create"**

### Step 5: Import Database Structure
1. In phpMyAdmin, select `campus_assist` database
2. Click **"Import"** tab
3. Click **"Choose File"** button
4. Select `campus_assist.sql` file from project folder
5. Click **"Go"** button to import

**If SQL file is not available, create tables manually:**
```sql
-- Run this SQL in phpMyAdmin
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('pending', 'in_progress', 'resolved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE lost_found (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    item_name VARCHAR(100) NOT NULL,
    description TEXT,
    type ENUM('lost', 'found') NOT NULL,
    location VARCHAR(200),
    contact_info VARCHAR(100),
    status ENUM('active', 'resolved') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### Step 6: Configure Database Connection
1. Open `config/database.php` file in text editor
2. Update database credentials:
```php
<?php
$servername = "localhost";
$username = "root";          // Default XAMPP username
$password = "";              // Default XAMPP password (empty)
$dbname = "campus_assist";   // Your database name
?>
```

### Step 7: Run the Application
1. Make sure XAMPP Apache and MySQL are running
2. Open web browser
3. Go to: http://localhost/campus-assist
4. You should see the Campus Assist homepage

### Step 8: Create Admin Account (First Time Setup)
1. Go to: http://localhost/campus-assist/auth/register.php
2. Register with these details:
   - Username: `admin`
   - Email: `admin@campus.com`
   - Password: `admin123`
3. Manually change role to admin in database:
   - Go to phpMyAdmin → campus_assist → users table
   - Edit the admin user record
   - Change `role` from `student` to `admin`

## 🎯 How to Use

### For Students:
1. **Register**: Create account at `/auth/register.php`
2. **Login**: Login at `/auth/login.php`
3. **Dashboard**: Access student dashboard
4. **Submit Complaints**: Report issues and track status
5. **Lost & Found**: Report lost items or browse found items

### For Admin:
1. **Login**: Use admin credentials
2. **Dashboard**: View system overview
3. **Manage Complaints**: Review and update complaint status
4. **Manage Items**: Handle lost & found items
5. **User Management**: View registered users

## 📁 Project Structure
```
campus-assist/
├── admin/                  # Admin panel files
│   ├── dashboard.php
│   ├── manage_complaints.php
│   └── manage_items.php
├── assets/                 # CSS, JS, Images
│   ├── css/style.css
│   └── js/script.js
├── auth/                   # Authentication files
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── config/                 # Configuration files
│   └── database.php
├── includes/               # Common includes
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── student/                # Student panel files
│   ├── dashboard.php
│   ├── submit_complaint.php
│   ├── my_complaints.php
│   ├── lost_found.php
│   └── report_item.php
├── complaints/             # Complaint management
│   └── submit.php
├── index.php              # Homepage
└── README.md              # This file
```

## 🔧 Troubleshooting

### Common Issues:

**1. "Cannot connect to database"**
- Check if MySQL is running in XAMPP
- Verify database credentials in `config/database.php`
- Ensure database `campus_assist` exists

**2. "404 Not Found"**
- Check if Apache is running
- Verify project is in correct htdocs folder
- Check URL: http://localhost/campus-assist

**3. "Permission denied"**
- On Linux/Mac: `chmod -R 755 /path/to/campus-assist`
- On Windows: Run XAMPP as Administrator

**4. "PHP errors showing"**
- Add this to top of `index.php` for debugging:
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
```

### Default Login Credentials:
After setup, you can use:
- **Admin**: admin@campus.com / admin123
- **Student**: Create new account via registration

## 🌐 Demo Accounts (After Setup)
```
Admin Login:
Email: admin@campus.com
Password: admin123

Student Login:
Create your own account via registration
```

## 🔄 Future Updates
To get latest updates:
```bash
git pull origin main
```

## 🤝 Contributing
1. Fork the repository
2. Create feature branch: `git checkout -b feature-name`
3. Commit changes: `git commit -m 'Add feature'`
4. Push to branch: `git push origin feature-name`
5. Submit pull request

## 📄 License
This project is open source and available under the [MIT License](LICENSE).

## 📞 Support & Contact
- **Developer**: Dev Suthar
- **GitHub**: [@devsuthar1104](https://github.com/devsuthar1104)
- **Email**: devsuthar1104@gmail.com

## ⭐ Show Your Support
If this project helped you, please give it a ⭐ on GitHub!

---
