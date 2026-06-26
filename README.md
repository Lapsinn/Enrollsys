# EnrollSys — CCIS Online Enrollment Portal

EnrollSys is a responsive web portal built with Laravel, designed for the College of Computer and Information Sciences (CCIS). It streamlines the academic enrollment process for students and administrators, managing forms, records, block assignments, and subject enrollments.

---

## 🚀 Key Features

### 👤 Student Features
* **Enrollment Form**: Submit and save drafts of the standard CCIS enrollment form. Once approved by the registrar, the form is locked to prevent unauthorized changes.
* **Academic Record Upload**: Overhauled to be document-centric. Students can upload their transcript/records file (PDF, Word, or image) directly to secure local storage.
* **Enrollment Status**: Track the approval status of the enrollment form, with real-time feedback and office notes left by the registrar.
* **Subject Enrollment**: An interactive subjects selection panel featuring a dynamic, real-time search bar (powered by Alpine.js). Once approved by an admin, the selection is locked.
* **Class Schedule Preview**: View a dynamic schedule preview based on selected subjects.

### 🔑 Admin Features
* **Approval Queue**: View pending student applications, approve/reject forms, and add custom office notes directly to student profiles (stored in the database).
* **Block Assignment**: Manage blocks (Years 1–4, Sections 1–5, and 1N/1-1N). The block options dynamically filter based on the student's year level. Added controller-level validation to prevent invalid block assignments.
* **Subject Approval**: Review enrolled subjects for each student, and lock or unlock their selections.

---

## 🛠️ Tech Stack
* **Framework**: Laravel 11
* **Frontend**: HTML5, Vanilla CSS, Bootstrap 5 (Styling), Alpine.js (Interactivity)
* **Database**: SQLite / MySQL (Eloquent ORM)

---

## ⚙️ Installation & Setup

1. **Clone & Install Dependencies**:
   ```bash
   composer install
   npm install
   ```

2. **Environment Configuration**:
   Create a `.env` file from the template:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Migration & Seeding**:
   Initialize database tables, populate blocks, parse/seed all 132 subjects from `courses.md`, and create the test admin and student accounts:
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Run the Development Server**:
   Run both the Laravel server and the Vite development asset server:
   ```bash
   # Run the backend
   php artisan serve

   # Compile and run the frontend assets
   npm run dev
   ```

---

## 👥 Seeded Test Accounts

The database is pre-seeded with a registrar administrator and five student profiles with modern Filipino names. These accounts represent different stages of the enrollment workflow, making it easy to test various features:

| Name | Email | Program/Year | Enrollment Stage / Scenario |
| :--- | :--- | :--- | :--- |
| **Maria Teresa Reyes** | `registrar@school.edu` | Admin (Registrar) | **Registrar Admin**: Can view all applications, approve forms and subjects, assign blocks, download record files, and manage office notes. |
| **Althea Santos** | `althea@school.edu` | BSCS - Year 1 | **Fully Completed & Approved**: Form approved, record file uploaded, block assigned (`1-1`), subjects enrolled & approved/locked. |
| **Joshua Dela Cruz** | `joshua@school.edu` | BSIT - Year 2 | **Subjects Pending Approval**: Form approved, record file uploaded, block assigned (`2-2`), subjects enrolled but pending admin approval. |
| **Carlo Aquino** | `carlo@school.edu` | BSCS - Year 3 | **Form Approved, No Subjects Enrolled**: Form approved, record file uploaded, block unassigned, no subjects selected yet. |
| **Dianne Rivera** | `dianne@school.edu` | BSIT - Year 1 | **Form Pending Approval**: Form submitted and pending registrar approval, no record file uploaded, block unassigned. |
| **Ethan Roxas** | `ethan@school.edu` | BSCS - Year 4 | **Draft/Initial Stage**: Enrollment form saved as draft (incomplete), no record file uploaded, block unassigned. |

*Note: All seeded accounts use the password `password123`.*
