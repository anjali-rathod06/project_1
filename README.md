# project_1
# Resume Builder

A simple web-based **Resume Builder** developed using **HTML, CSS, PHP, MySQL, and XAMPP**. Users can register, create resumes, preview them, and print or save them as PDF. An admin panel is also provided to manage users and resumes.

## Technologies Used

* HTML
* CSS
* PHP
* MySQL
* XAMPP
* JavaScript

## Project Setup Instructions

### Step 1: Copy the Project Folder

Copy the `resume-builder` project folder and paste it inside the XAMPP `htdocs` folder:

```text
C:\xampp\htdocs\resume-builder
```

### Step 2: Start XAMPP

1. Open the **XAMPP Control Panel**.
2. Start **Apache**.
3. Start **MySQL**.
4. Make sure both services are running.

### Step 3: Create the Database

1. Open your browser.
2. Go to:

```text
http://localhost/phpmyadmin
```

3. Click on the **Import** tab.
4. Click **Choose File**.
5. Select the database file:

```text
database/resume_builder.sql
```

6. Click **Go**.
7. The database and required tables will be created automatically.

### Step 4: Run the Project

Open the following URL in your browser:

```text
http://localhost/resume-builder/
```

Users can register, log in, and create their resumes from the website.

## Admin Panel

The admin panel can be accessed using:

```text
http://localhost/resume-builder/admin/admin_login.php
```

### Default Admin Credentials

```text
Email: admin@resume.com
Password: admin123
```

> **Note:** Change the default admin password before using the project in a production environment.

## How to Use

### User

1. Register a new account.
2. Log in to the system.
3. Open the dashboard.
4. Click **New Resume**.
5. Enter the required personal, educational, and professional details.
6. Save the resume.
7. Preview the generated resume.
8. Use the **Print/PDF** option to print or save the resume as a PDF.

### Admin

1. Log in through the Admin Panel.
2. Open the **Users** section to view registered users.
3. Open the **Resumes** section to view saved resumes.
4. Verify that the user and resume information has been stored correctly.

## Security

* User passwords are stored using **password hashing** instead of plain text.
* Admin and normal user login sessions are maintained separately.
* Database operations are handled through PHP and MySQL.

## Requirements

Before running the project, make sure you have:

* XAMPP installed
* Apache enabled
* MySQL enabled
* A web browser
* The `resume-builder` project folder

## Project Structure

```text
resume-builder/
│
├── admin/
├── database/
│   └── resume_builder.sql
├── css/
├── images/
├── *.php
└── README.md
```

## Author

**Resume Builder Project**

Developed as an academic web development project using PHP and MySQL.
