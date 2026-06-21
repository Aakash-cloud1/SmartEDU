# EduEase

EduEase is a comprehensive educational management system built with PHP and MySQL. It provides tailored dashboards and functionalities for students, teachers, and administrators to streamline academic processes, communication, and record-keeping.

## Features

*   **Role-Based Access Control:** Secure login and registration system with distinct roles: Admin, Teacher, and Student.
*   **Student Dashboard:**
    *   View academic performance (marks, SGPA, CGPA).
    *   Manage and update personal profiles.
    *   Real-time chat functionality with peers and teachers.
*   **Teacher Dashboard:**
    *   Update and manage student marks.
    *   View submitted feedback from students.
    *   Access and review student profiles.
*   **Admin Dashboard:**
    *   Manage user accounts and system settings.
    *   Add new students to the system.
*   **Feedback System:** Built-in mechanism for students to submit feedback to teachers.
*   **AJAX Integration:** Dynamic data loading for a seamless user experience.

## Technologies Used

*   **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
*   **Backend:** PHP (Session Management, Password Hashing)
*   **Database:** MySQL

## Getting Started

### Prerequisites

*   PHP >= 7.4
*   MySQL >= 8.0
*   A local server environment (e.g., XAMPP, WAMP, or PHP Built-in Server)

### Installation & Setup

1.  **Clone or Download the repository** to your local machine.
2.  **Database Setup:**
    *   Ensure your MySQL server is running.
    *   Create a new MySQL database named `user_db`.
    *   *Note: The project requires specific tables to be present in `user_db` (`chat_messages`, `feedback`, `marks`, `student_profiles`, `students`, `subjects`, and `user_db` table).*
3.  **Configuration:**
    *   Open `config.php`.
    *   Update the database credentials if necessary. The default configuration is:
        ```php
        $host = "localhost";
        $user = "root";
        $password = "";
        $database = "user_db";
        $port = 3306;
        ```
4.  **Run the Application:**
    *   **Using XAMPP/WAMP:** Place the project folder in your server's root directory (e.g., `htdocs` for XAMPP) and navigate to `http://localhost/path-to-project` in your browser.
    *   **Using PHP Built-in Server:** Open your terminal, navigate to the project directory, and run:
        ```bash
        php -S localhost:8080
        ```
        Then, open `http://localhost:8080/index.php` in your web browser.

## File Structure Highlights

*   `config.php`: Database connection settings.
*   `index.php` / `login.php`: Application entry point and authentication interface.
*   `login_register.php`: Backend logic for user authentication.
*   `style.css` / `script.js`: Global styles and client-side logic.
*   `user_page.php`, `teacher_page.php`, `admin_page.php`: Role-specific dashboard views.
