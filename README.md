# Customer Support Ticketing System

**Student Name:** Prajwal Bhattrai  
**Project:** PHP + MySQL Web Application

## 🔗 Live Site Link
You can access the hosted project here:  
http://103.41.173.36/~np03cs4a240181/ticket_system/public/index.php



##  Login Credentials
Use these details to log in as the Administrator:
* **Username:** `admin`
* **Password:** `password`

*(You can also register new users or use the seeded test accounts)*



##  Features Implemented

### 1. Core Functionality (CRUD)
* **Create:** Users can submit new support tickets with Priority and Issue Type.
* **Read:** Admin Dashboard displays all tickets in a structured table.
* **Update:** Admins can change ticket status (Open/Resolved) and add responses.
* **Delete:** Admins can permanently delete tickets (protected action).

### 2. Advanced Features (Bonus)
* **Live Ajax Search:** Users can search for tickets instantly without reloading the page.
* **Advanced Filtering:** Search supports filtering by Ticket Status and Priority.
* **Admin Dashboard:** Dedicated view for administrators to manage the system.

### 3. Security Measures
* **SQL Injection Protection:** All database queries use PDO Prepared Statements.
* **XSS Protection:** All output is escaped using `htmlspecialchars()` to prevent script injection.
* **CSRF Protection:** Forms are protected with unique cryptographic tokens to prevent cross-site attacks.
* **Session Management:** Secure login/logout system with `session_destroy()` and access control checks.
* **Password Hashing:** User passwords are securely hashed using `password_hash()` (Bcrypt).



##  Known Issues / Limitations
* **Mobile Responsiveness:** The table layout is optimized for desktop views and may require scrolling on very small mobile screens.
* **Browser Caching:** If the Live Search feature does not appear immediately, a hard refresh (Ctrl+F5) may be required to clear old JavaScript cache.



## 📂 Project Structure
* `config/` - Database connection settings.
* `public/` - Main accessible pages (index, search, add, edit).
* `includes/` - Reusable components (header, footer, functions).
* `assets/` - CSS styles and JavaScript files.

