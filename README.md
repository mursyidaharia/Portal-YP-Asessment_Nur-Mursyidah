# ExPo — Exam Portal

A web-based online examination and student management portal built with Laravel 11 and Laravel Breeze.

---

## Tech Stack

- **Framework:** Laravel 11
- **Authentication:** Laravel Breeze
- **Database:** MySQL
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **Build Tool:** Vite

---

## Features

### Lecturer
- Dashboard with stats — total students, classes, subjects and exams
- Class management — create, edit, delete classes and assign subjects
- Subject management — each subject is owned by the lecturer who created it
- Student overview — view all registered students and their class assignments
- Exam management — create, edit, delete, publish, unpublish exams
- Exam scheduling — set a scheduled publish date/time for auto-publishing
- Due date — exams automatically close after the due date
- Question management — add multiple choice and open text questions per exam
- Grading — MCQ auto-graded, open text manually graded by lecturer
- Bulk release — release all results at once for MCQ-only exams
- Student navigation in grading — navigate between students within the same exam
- Result release — control when students can view their results
- Search and sort — available on all management pages

### Student
- Dashboard — view available exams and recent attempts
- My Exams — view all available exams with status (available, completed, expired)
- Exam attempt — countdown timer with auto-submit when time expires
- Auto-save answers — answers saved automatically via AJAX
- Review page — review all answers before final submission
- Exam history — track all past exam attempts
- Results — view detailed results after lecturer releases
- Search and sort — available on exam and history pages

### Security & Additional Features
- Role-based access control — lecturers and students have separate access
- One attempt per exam — students cannot retake a submitted exam
- Question randomization — questions appear in random order each attempt
- Prevent copy-paste — disabled during exam
- Audit logging — all major actions are tracked with user, action and timestamp

---

## Requirements

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL

---

## Installation

### 1. Clone the repository

    git clone https://github.com/mursyidaharia/Portal-YP-Asessment_Nur-Mursyidah.git
    cd Portal-YP-Asessment_Nur-Mursyidah

### 2. Install dependencies

    composer install
    npm install

### 3. Environment setup

    cp .env.example .env
    php artisan key:generate

### 4. Configure database

Open `.env` and update the following:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=exam_portal
    DB_USERNAME=root
    DB_PASSWORD=
    APP_TIMEZONE=Asia/Kuala_Lumpur

### 5. Run migrations and seed database

    php artisan migrate --seed

### 6. Build assets

    npm run build

### 7. Start the server

    php artisan serve

Visit **http://localhost:8000**

### 8. Optional — Run scheduler for auto-publish feature

Open a separate terminal and run:

    php artisan schedule:work

This enables automatic exam publishing based on scheduled date and time.

---

## Default Credentials

| Role            | Email             | Password |
|-----------------|-------------------|----------|
| Lecturer        | lecturer@expo.com | password |
| Student (CS101) | student1@expo.com | password |
| Student (CS101) | student2@expo.com | password |
| Student (CS102) | student3@expo.com | password |
| Student (CS102) | student4@expo.com | password |

---

## Student Class Assignment

Students are assigned to classes by lecturers via the Classes management page. Upon registration, students should inform their lecturer to be assigned to the appropriate class.

Lecturers can manage class assignments by navigating to **Classes → Edit Class** and selecting the relevant students.

---

## Exam Flow

### Lecturer
1. Create a **Class** and assign subjects
2. Create a **Subject** under your account
3. Assign the subject to a class via **Classes → Edit**
4. Create an **Exam** under a subject — set time limit, scheduled publish and due date
5. Add questions (multiple choice or open text)
6. Publish the exam manually or let it auto-publish
7. After students submit — go to **Grading** to mark open text answers
8. Release results for students to view

### Student
1. Register and inform lecturer to be assigned to a class
2. Go to **My Exams** to view available exams
3. Click **Start Exam** — timer begins immediately
4. Answer all questions — answers are saved automatically
5. Click **Review & Submit** to review before submitting
6. View results under **Exam History** after lecturer releases

---

## Audit Log

All major actions are recorded in the audit log including:

- Class, subject and exam creation, updates and deletion
- Exam attempts and submissions
- Grading and result release
- Scheduled exam auto-publishing