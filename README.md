# 🏠 College Hostel Management System

A web-based College Hostel Management System developed using **PHP, MySQL, HTML, CSS, and JavaScript**. The system helps students manage hostel-related activities such as registration, login, room viewing, room booking, and complaints.

## 📌 Project Overview

The College Hostel Management System is designed to simplify hostel management by providing a centralized platform for students and administrators.

Students can create an account, log in securely, view available hostel rooms, apply for a room, check their booking status, and submit complaints.

The project uses **MySQL** as the database and **PHP** for server-side processing.

## ✨ Features

### 👨‍🎓 Student Module

- Student Registration
- Student Login
- Secure Password Hashing
- Student Dashboard
- View Student Profile
- View Department and Academic Year
- View Available Hostel Rooms
- Apply/Book a Hostel Room
- View Booking Status
- Submit Hostel Complaints
- View Complaint Status
- Logout

### 👨‍💼 Admin Module

- Admin Login
- Manage Students
- Manage Hostel Rooms
- Manage Bookings
- Manage Complaints
- Update Booking Status
- Update Complaint Status

## 🛠️ Technologies Used

| Technology | Purpose |
|------------|---------|
| PHP | Backend / Server-side Development |
| MySQL | Database Management |
| HTML5 | Web Page Structure |
| CSS3 | Styling |
| JavaScript | Client-side functionality |
| XAMPP | Local Development Server |
| phpMyAdmin | Database Management |


## 🗄️ Database

The project uses a MySQL database named:

`college_hostel`

### Main Tables

- `students`
- `rooms`
- `bookings`
- `complaints`
- `admins`

## 📂 Project Structure

```text
college_hostel/
│
├── index.php
├── login.php
├── register.php
├── dashboard.php
├── rooms.php
├── booking.php
├── complaints.php
├── logout.php
│
├── admin/
│   ├── login.php
│   ├── dashboard.php
│   ├── students.php
│   ├── rooms.php
│   ├── bookings.php
│   └── complaints.php
│
├── css/
│   └── style.css
│
├── js/
│   └── script.js
│
└── includes/
    └── db.php
