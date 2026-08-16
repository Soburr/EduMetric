# EduMetric

A comprehensive school management platform built with **Laravel**, **Blade**, and **JavaScript**, designed to streamline academic administration, student assessment, and performance reporting.

EduMetric features a scalable **Computer-Based Testing (CBT) Engine** for real-time examinations, automated grading, result analytics, report card generation, and broadsheet management, making it suitable for primary, secondary, and tertiary institutions.

---

## 🚀 Features

### 📚 Academic Management

* Student registration and management
* Class and department management
* Subject allocation
* Academic session and term management
* Teacher management

### 📝 CBT Examination Engine

* Real-time computer-based examinations
* Multiple question types
* Timed assessments
* Automatic grading
* Exam scheduling
* Question bank management
* Randomized questions
* Instant result generation

### 📊 Result Analytics

* Automated score computation
* Student performance analysis
* Subject performance statistics
* Class ranking and positioning
* Grade calculation system
* Performance trend tracking

### 📄 Report Card System

* Automated report card generation
* Continuous assessment computation
* Termly result processing
* Printable student report sheets
* Cumulative performance records

### 📑 Broadsheet Management

* Class broadsheet generation
* Subject-wise performance summaries
* Student ranking reports
* Downloadable and printable broadsheets

### 🔒 Security & Access Control

* Role-based authentication
* Administrator dashboard
* Teacher dashboard
* Student portal
* Secure access management

### 📱 User Experience

* Responsive interface
* Modern dashboard design
* Real-time interactions
* Fast and intuitive navigation

---

## 🛠️ Technology Stack

### Backend

* Laravel

### Frontend

* Blade Templates
* JavaScript
* HTML5
* CSS3 & Tailwind

### Database

* MySQL

### Authentication

* Laravel Authentication

---

## 📋 Requirements

Before installation, ensure you have:

* PHP 8.1+
* Composer
* MySQL
* Node.js & NPM

---

## ⚙️ Installation

### Clone the Repository

```bash
git clone https://github.com/yourusername/edumetric.git

cd edumetric
```

### Install Dependencies

```bash
composer install

npm install
```

### Configure Environment

```bash
cp .env.example .env
```

Update the database credentials inside the `.env` file.

### Generate Application Key

```bash
php artisan key:generate
```

### Run Migrations

```bash
php artisan migrate
```

### Build Frontend Assets

```bash
npm run build
```

For development:

```bash
npm run dev
```

### Start the Application

```bash
php artisan serve
```

Visit:

```text
http://127.0.0.1:8000
```

---

## 🎯 Core Modules

### Administration Module

* User management
* Academic session setup
* School configuration
* Notice Upload
* Manage Broadsheet and Report Card
* Add/Remove Teachers
* Add/Remove Students

### Teacher Module

* Result management
* Assessment uploads
* Question creation
* Student performance monitoring
* Add Student to their class
* Update Student's details

### Student Module

* CBT participation
* Result viewing
* Academic records access
* Report card access
* Notice viewing

### Examination Module

* CBT administration
* Question bank
* Exam scheduling
* Result processing
* Exam shuffling

### Reporting Module

* Report cards
* Broadsheets
* Academic statistics
* Performance analytics

---

---

## 🧪 Testing

Run application tests:

```bash
php artisan test
```

---

## 📈 Future Improvements

* Parent Portal
* SMS Notifications
* Email Notifications
* Assignment Management
* Attendance Tracking
* School Fees Management
* Mobile Application
* AI-Based Performance Insights
* Attendance Marker

---

## 🤝 Contributing

Contributions are welcome.

1. Fork the repository
2. Create a feature branch

```bash
git checkout -b feature/new-feature
```

3. Commit your changes

```bash
git commit -m "Add new feature"
```

4. Push to GitHub

```bash
git push origin feature/new-feature
```

5. Create a Pull Request

---

## 📄 License

This project is licensed under the MIT License.

---

## 👨‍💻 Author

Developed by **Soburr**

GitHub: https://github.com/Soburr

---

⭐ If you find EduMetric useful, consider giving the project a star on GitHub.
