# ParKoto — Parking Management System

> A PHP-based web application for managing parking records, persons, vehicles, and fines. Built as a beginner learning project using PHP, MySQL (via XAMPP), and Tailwind CSS (CDN).

---

## Table of Contents

- [Overview](#overview)
- [Tech Stack](#tech-stack)
- [Database Setup (XAMPP)](#database-setup-xampp)
- [Database Schema](#database-schema)
- [How to Run](#how-to-run)
- [System Architecture](#system-architecture)
- [User Roles](#user-roles)
- [File Structure & What Each File Does](#file-structure--what-each-file-does)

---

## Overview

**ParKoto** is a parking management system that allows two types of users — **Admins** and **Regular Users** — to interact with parking-related data. The system manages:

- **Users** — People who can log in (system accounts)
- **Persons** — Users registered in the parking database (linked to users via SSN)
- **Cars** — Vehicles registered in the system, each linked to a person (owner)
- **Fines** — Parking fines/penalties issued against specific cars and persons
- **To-Do Tasks** — Admin-only internal task list
- **Appointments** — Admin-only appointment/scheduling tool

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP (procedural + OOP mysqli) |
| Database | MySQL via **XAMPP** (phpMyAdmin) |
| Frontend Styling | Tailwind CSS (loaded via CDN — no build step needed) |
| Charts | Chart.js (CDN) |
| Icons | Font Awesome 6 (CDN) |
| Session Management | PHP native sessions (`$_SESSION`) |
| Web Server | Apache (included in XAMPP) |

---

## Database Setup (XAMPP)

> **Quick Start:** A pre-exported development database `parkoto.sql` is included under the `database/` folder. This file was exported directly from XAMPP phpMyAdmin and contains all tables, sample data, and pre-seeded user accounts.

### Option 1: Import the Pre-Exported Database (Recommended)

1. Start **XAMPP** and ensure **Apache** and **MySQL** are running.
2. Open your browser and go to: `http://localhost/phpmyadmin`
3. Create a new database called: `parkoto`
4. Click the **Import** tab, choose the `database/parkoto.sql` file from this project folder, and click **Go**.
5. Done — all tables, schema, and demo accounts are ready.

### Option 2: Manual Setup

If you prefer to set up the database manually, run the following SQL to create all required tables:

```sql
-- Users table (system login accounts)
CREATE TABLE user (
    user_id     INT AUTO_INCREMENT PRIMARY KEY,
    person_name VARCHAR(100) NOT NULL,
    user_name   VARCHAR(50)  NOT NULL UNIQUE,
    email       VARCHAR(100) NOT NULL UNIQUE,
    user_password VARCHAR(255) NOT NULL,
    person_address VARCHAR(200),
    phone_number VARCHAR(20),
    rol         ENUM('admin', 'user') NOT NULL DEFAULT 'user'
);

-- Persons table (linked to users)
CREATE TABLE person (
    ssn         VARCHAR(20) PRIMARY KEY,
    user_id     INT,
    person_name VARCHAR(100),
    person_address VARCHAR(200),
    phone_number VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);

-- Cars table (vehicles, linked to person by SSN as owner)
CREATE TABLE car (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    register    VARCHAR(20) NOT NULL UNIQUE,
    color       VARCHAR(50),
    model_year  VARCHAR(10),
    owner_id    VARCHAR(20),
    FOREIGN KEY (owner_id) REFERENCES person(ssn)
);

-- Fines table (parking fines linked to car and person)
CREATE TABLE fine (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    car         VARCHAR(20),
    person      VARCHAR(20),
    date        DATE,
    amount      DECIMAL(10,2),
    reason      VARCHAR(255),
    FOREIGN KEY (car) REFERENCES car(register),
    FOREIGN KEY (person) REFERENCES person(ssn)
);

-- Admin to-do tasks
CREATE TABLE todo (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    task        VARCHAR(255) NOT NULL
);

-- Admin appointments
CREATE TABLE appointment (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(100) NOT NULL,
    date        DATETIME,
    description TEXT
);
```

The database connection is configured in `connectdb.php`:
- Server: `localhost`
- User: `root`
- Password: *(empty by default in XAMPP)*
- Database: `parkoto`

---

## How to Run

1. Place the `parkoto` folder inside `C:\xampp\htdocs\`
2. Start XAMPP → Start **Apache** and **MySQL**
3. Set up the database as described above (import `database/parkoto.sql` or run the SQL manually)
4. Open your browser and visit: `http://localhost/parkoto`
5. Log in using one of the demo accounts below, or register a new account (you can select **Admin** or **User** role during signup)
6. You'll be redirected to the appropriate dashboard based on your role

### Demo Credentials

| Role | Username / Email | Password |
|:---|:---|:---|
| **Admin** | `adminone@parkoto.fi` or `admin_one` | `Admin` |
| **User** | `sanna@parkoto.com` or `Sanna` | `Sanna` |

> **Note:** These accounts are pre-seeded in the included `database/parkoto.sql` export. If you set up the database manually, you will need to insert these users yourself or register new accounts via the signup page.

---

## System Architecture

```
Browser
   │
   ▼
index.php  ──► login.php ──► connectdb.php (MySQL)
                  │
         ┌────────┴────────┐
         ▼                 ▼
  /admin/admin_home.php  /user/user_home.php
         │                 │
    Admin Panel         User Panel
    ┌────┬────┬────┐    ┌────┬────┬────┐
    │User│Car │Fine│    │Person Search │
    │    │    │    │    │Car Search    │
    │Person   │    │    │Fine View     │
    └────┴────┴────┘    └─────────────┘
```

### Authentication Flow

```
POST login.php
  → Query user table WHERE user_name OR email = input
  → Verify password with password_verify()
  → Set $_SESSION['user_id'], ['user_role'], ['user_name']
  → Redirect:
      rol = 'admin' → /admin/admin_home.php
      rol = 'user'  → /user/user_home.php
```

### Data Relationships

```
user (user_id)
  └─ person (user_id → FK, SSN = primary identity)
       └─ car (owner_id → person.ssn)
            └─ fine (car → car.register, person → person.ssn)
```

---

## User Roles

### Admin
- Full access to all data: Users, Persons, Cars, Fines
- Can **Add / Edit / Delete** records in all sections
- Has a dashboard with:
  - Bar chart overview of all data counts
  - To-Do List (create, edit, delete tasks)
  - Appointments manager (create, edit, delete)
  - Calendar link (Google Calendar embed)
  - Email shortcuts (Gmail, Outlook)
  - Global search across Cars, Persons, and Fines

### Regular User
- Can **search** Persons by name/SSN
- Can **search** Cars by registration number or owner SSN
- Can **view** fine information for searched cars/persons
- Fine section is restricted: "Access Denied – Only Special Users"
- Has a photo gallery on the home page (placeholder images from Pexels)

---

## File Structure & What Each File Does

```
parkoto/
├── index.php               # Landing/welcome page with Log In and Register buttons
├── login.php               # Login form + authentication logic (checks DB, sets session)
├── signup.php              # Registration form (collects name, username, email, password, address, phone, role)
├── signup_connect.php      # Processes signup form: hashes password, inserts into user table
├── logout.php              # Destroys session and redirects to login.php
├── fine.php                # Fine entry form - standalone form 
├── error_page.php          # Basic error display page
├── connectdb.php           # MySQL database connection using mysqli; connects to 'parkoto' DB
│
├── database/
│   └── parkoto.sql         # Pre-exported development database from XAMPP phpMyAdmin
│                             # Contains all tables, schema, and demo user accounts
│
├── assets/                     # Static image icons
│   ├── avatar_icon.png         # Used in admin sidebar as profile avatar
│   ├── car_icon.png            # Car section nav icon
│   ├── dashboard_icon.png      # Dashboard nav icon
│   ├── fine_icon.png           # Fine section nav icon
│   ├── gmail_logo.png          # Email widget in admin dashboard
│   ├── google_calender_icon.png # Calendar widget icon
│   ├── group_icon.png          # Users section nav icon
│   ├── logout_icon.png         # Logout nav icon
│   ├── outlook_logo.png        # Email widget in admin dashboard
│   ├── to-do-list_icon.png     # To-Do widget icon
│   ├── user_icon.png           # Person nav icon
│   └── circle_user.png         # Unused/alternative user icon
│
├── admin/
│   ├── admin_home.php      # Admin dashboard: stats cards, bar chart, to-do list, appointments, calendar/email widgets
│   ├── admin_nav.php       # Top navigation bar for admin with global search (Cars + Persons + Fines)
│   ├── admin_footer.php    # Footer with copyright year, social media links (Twitter, YouTube, Facebook, Instagram, TikTok, LinkedIn)
│   ├── save_appointment.php # Appointment saving handler
│   │
│   ├── user/
│   │   ├── user_dashboard.php  # Lists all registered users; allows add/edit/delete/role change
│   │   ├── add_user.php        # Inserts a new user into the DB
│   │   ├── update_user.php     # Updates user details (name, email, address, phone)
│   │   ├── update_role.php     # Changes a user's role between 'admin' and 'user'
│   │   └── delete_user.php     # Deletes a user and their associated person record
│   │
│   ├── person/
│   │   ├── person_dashboard.php # Lists all persons; add/edit/delete; links person to user via SSN
│   │   ├── add_person.php       # Inserts a new person (user_id + SSN) into person table
│   │   ├── edit_person.php      # Handles person edit form submission
│   │   ├── update_person.php    # Updates person record in DB
│   │   ├── delete_person.php    # Deletes a person by SSN
│   │   └── search_person.php    # searches person table by name
│   │
│   ├── car/
│   │   ├── car_dashboard.php    # Lists all cars; add/edit/delete; fields: register, color, model_year, owner_id
│   │   ├── add_car.php          # Inserts a new car into the DB
│   │   ├── update_car.php       # Updates car record
│   │   └── delete_car.php       # Deletes a car by registration number
│   │
│   └── fine/
│       ├── fine_dashboard.php   # Lists all fines (JOINed with person name + car register); add/edit/delete
│       ├── add_fine.php         # Validates person exists, then inserts fine record using prepared statements
│       ├── update_fine.php      # Updates an existing fine record
│       ├── delete_fine.php      # Deletes a fine by ID
│       ├── get_owner_name.php   # returns person name by owner SSN (used in fine form)
│       ├── search_cars.php      # returns cars matching a search query
│       └── search_persons.php   # returns persons matching a search query
│
└── user/
    ├── user_home.php        # User dashboard: Person & Car search forms + photo gallery (Pexels images)
    ├── navbar.php           # Top nav for users: logo, global search, welcome message, logout button
    ├── footer.php           # User-facing footer
    │
    ├── person/
    │   ├── person_query.php     # Searches person table by name + SSN; displays result with linked car info
    │   ├── person_insert.php    # Allows user to add a person record
    │   ├── person_update.php    # Updates a person record
    │   └── person_delete.php    # Deletes a person record
    │
    ├── car/
    │   ├── car_query.php        # Searches car table by registration or owner SSN; shows car + owner details
    │   ├── car_insert.php       # Allows user to add a car record
    │   ├── car_update.php       # Updates a car record
    │   └── car_delete.php       # Deletes a car
    │
    └── fine/
        ├── fine_insert.php      # User-side fine page: search cars/persons + apply penalty fine form
        └── fine_query.php       # Displays fine records for a searched car or person
```

---

## Color Palette Used

| Name | Hex | Usage |
|---|---|---|
| Dark Black | `#181C14` | Primary background, buttons |
| Snow White | `#FFFAFA` | Text on dark backgrounds |
| Red (accent) | `#c53030` / `#b91c1c` | Buttons, highlights, "Koto" in logo |
| Cream | `#ECDFCC` | Fine page body text |
| Slate Light | `#F8FAFC` | Card backgrounds, input fields |
| Zinc 950 | `#09090B` | Nav bars, sidebar |

---

*This project was built as a learning exercise to explore PHP fundamentals, MySQL integration, session management, and basic CRUD operations.*
