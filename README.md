<div align="center">
  <img src="https://readme-typing-svg.herokuapp.com?font=Fira+Code&size=32&pause=1000&color=F7F7F7&center=true&vCenter=true&width=900&lines=🤝+PeerLink+-+Peer+Support+Platform" alt="Title" />
</div>

A robust web-based peer support and anonymous guidance platform built with PHP, MySQL, and jQuery. It allows students to safely book counseling sessions with verified Peer Listeners (Navigators) from the ZNNHS Turno Peer Health Navigators Network (PHNN), while administrators oversee registration logs and platform-wide approvals with ease.

---

### 📦 Stack
- PHP 8.x
- MySQL / MariaDB
- PHPMailer
- jQuery & AJAX
- Vanilla CSS

---

### ✨ Quick start
```bash
# Clone the repository
git clone https://github.com/wayne2604/peerlink.git

# Navigate to the directory
cd peerlink

# Copy the example config and add your DB credentials
cp includes/db_connect.php.example includes/db_connect.php
```
Ensure you have a local server (like XAMPP) running, the database schema (`if0_41094727_peerlink.sql`) imported, and your mail settings configured in `send_otp.php` to get started.

---

### ⚙️ Features
- **Multi-Role Authentication** — Secure landing gates and custom dashboards tailored for Students (Clients), Peer Listeners (Navigators), and Administrators.
- **Anonymous Session Booking** — Simple peer selection workspace allowing students to review listener specialties, beliefs, personalities, and hobbies.
- **Real-Time Interactive Chat** — Robust chat rooms running asynchronous background queries to facilitate continuous, smooth communication between students and active listeners.
- **AI-Powered Automated Support** — Dedicated automated FAQ bot integrated directly into the client and listener portals for immediate advice.
- **Listener Verification Gate** — Secure backend verification queues where admins review, approve, or reject prospective peer listener accounts.
- **Safety Flags & Referral Redirects** — Immediate crisis flags redirecting users directly to Guidance Counselor offices and Department of Health support resources when a session is reported.
- **Dark Mode Compatibility** — Full responsive design featuring light and dark theme toggles synced flawlessly via browser LocalStorage.

---

### 🛠️ How it works
The system follows a modular PHP architecture designed for reliability, responsiveness, and ease of use:
- **Database Connectivity**: Uses `mysqli` with secure parameterized database preparation to ensure clean queries and robust authentication checking.
- **Decoupled Logic**: System components are highly modularized, isolating UI presentation layers (e.g., `student_home.php`, `listener_dashboard.php`) from logic engines (e.g., `login_process.php`, `post_data.php`).
- **Secure Recovery System**: Connects PHPMailer dynamically to SMTP servers to generate and send secure 6-digit One-Time Passwords (OTPs) for swift password recovery.
- **Asynchronous Chat Polling**: Incorporates jQuery AJAX streams to continuously poll for message updates, allowing peer-to-peer chats to behave in real-time.

---

### 📁 Project structure
```text
/
├── PHPMailer/              # Core PHPMailer library for SMTP email dispatch
├── css/                    # Stylesheets, styling variables, and layout styles
├── images/                 # App assets, system logo, and avatars
├── includes/               # Shared backend dependencies and config files
│   ├── db_connect.php      # Main database connection file (gitignored)
│   └── db_connect.php.example # Database connection configuration template
├── admin_dashboard.php     # Admin hub for pending approvals and user registries
├── booking_form.php        # Booking interface for students to describe and reserve sessions
├── communication_room.php  # Secure live-chat workspace for ongoing support sessions
├── forgot_password.php     # Initialization form to send recovery codes via email
├── index.php               # Main login page and entry point for all roles
├── listener_dashboard.php  # Navigator console for profile editing and incoming request management
├── load_data.php           # AJAX helper to fetch new chat messages securely
├── login_process.php       # Authentication logic and secure session starter
├── post_data.php           # AJAX helper saving newly sent message strings
├── register.php            # Unified client registration and validation form
├── send_otp.php            # Core PHP code sending one-time recovery passwords via SMTP
├── student_home.php        # Client console featuring listener selection and FAQ bot
├── terminate_session.php   # Logic ending or escalating peer support chat sessions
└── verify_otp.php          # Code validation page to finalize account password resets
```

---

### 👤 Author
**Wayne** — [github.com/wayne2604](https://github.com/wayne2604)
