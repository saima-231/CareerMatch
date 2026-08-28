# CareerMatch 🎓

**CareerMatch** is a full-stack internship management platform built with **PHP, MySQL, JavaScript, HTML, and Tailwind CSS**.

It provides a single platform where students can find and apply for internships, companies can post and manage internship opportunities, and administrators can manage the overall system.

## 🌐 Live Website

**CareerMatch:** https://careermatch.gamer.gd/

## ✨ Features

### 🎓 Student

* Register and log in
* Manage profile and skills
* Search internships
* View internship details
* Apply for internships
* Track application status

### 🏢 Company

* Register and log in
* Manage company profile
* Post internships
* Manage internships
* View applicants
* Update application status

### 🔐 Admin

* Secure admin login
* Dashboard with system statistics
* Manage students
* Manage companies
* Approve or reject companies
* Manage internships
* Manage applications
* View reports
* Remove users

## 🛠️ Technologies

* **PHP** — Backend development
* **MySQL** — Database
* **HTML5** — Page structure
* **JavaScript** — Client-side functionality
* **Tailwind CSS** — UI design and responsive layout
* **PDO** — Database connection and prepared statements
* **Git & GitHub** — Version control

## 🗄️ Database

The project uses MySQL with the following main tables:

* `students`
* `companies`
* `internships`
* `applications`
* `admins`

The `applications` table connects students with internships, while internships are associated with companies.

## 👥 User Roles

| Role       | Responsibilities                         |
| ---------- | ---------------------------------------- |
| 🎓 Student | Find internships and submit applications |
| 🏢 Company | Post internships and manage applicants   |
| 🔐 Admin   | Manage the entire platform               |

## 🎨 UI & Design

CareerMatch uses **Tailwind CSS** to create a responsive and modern interface.

The design includes:

* Responsive dashboards
* Fixed navigation sidebars
* Dark-themed interface
* Cards and tables
* Gradient effects
* Search functionality
* Responsive layouts

## 🔐 Security

The application uses:

* PHP sessions for authentication
* Role-based access control
* PDO prepared statements
* Input validation
* `htmlspecialchars()` for safe output

## 📁 Project Structure

```text
CareerMatch/
├── admin/
├── company/
├── config/
├── dashboard/
├── includes/
├── student/
├── admin_login.php
├── admin_register.php
├── companies.php
├── index.php
├── internships.php
├── login.php
├── logout.php
└── register.php
```

## 🎯 Project Goal

The goal of CareerMatch is to simplify the internship process by connecting **students, companies, and administrators** through one web-based platform.

## 👩‍💻 Developer

**Saima Sultana**

Developed as a full-stack web development academic project using PHP and MySQL.

## 📄 License

This project is created for **educational and academic purposes**.
