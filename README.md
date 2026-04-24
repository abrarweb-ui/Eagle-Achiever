# ============================================================
# Eagle Achievers — Complete Setup Guide
# PHP + MySQL + Firebase Affiliate + EdTech Platform
# ============================================================

## CREDENTIALS (Default)

| Role        | Email                         | Password   |
|-------------|-------------------------------|------------|
| Admin       | admin@eagleachievers.in        | password   |
| Test User   | user@test.com                 | password   |
| Affiliate   | aadil@test.com                | password   |

> ⚠️ Change ALL default passwords immediately after setup!

---

## LOCALHOST SETUP (XAMPP / WAMP / Laragon)

### Step 1 — Copy Files
```
Copy the `eagle_achievers/` folder to:
  XAMPP:    C:/xampp/htdocs/eagle_achievers/
  WAMP:     C:/wamp64/www/eagle_achievers/
  Laragon:  C:/laragon/www/eagle_achievers/
```

### Step 2 — Create Database
1. Open phpMyAdmin → http://localhost/phpmyadmin
2. Click **New** → Database name: `eagle_achievers` → Create
3. Click Import → Choose `database/eagle_achievers.sql` → Go

### Step 3 — Configure
Edit `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Your DB username
define('DB_PASS', '');           // Your DB password (blank for XAMPP default)
define('DB_NAME', 'eagle_achievers');
define('SITE_URL', 'http://localhost/eagle_achievers');
```

### Step 4 — Visit
- **Site:**       http://localhost/eagle_achievers/
- **Admin:**      http://localhost/eagle_achievers/admin/login.php
- **Register:**   http://localhost/eagle_achievers/register.php

---

## CPANEL / PRODUCTION HOSTING SETUP

### Step 1 — Upload Files
Using File Manager or FTP:
- Upload ALL files to `public_html/` (root) or a subdirectory
- Maintain the folder structure exactly

### Step 2 — Create MySQL Database (cPanel)
1. cPanel → **MySQL Databases**
2. Create database: `yourusername_eagle`
3. Create user + assign ALL PRIVILEGES
4. Go to **phpMyAdmin** → select your database → Import → `database/eagle_achievers.sql`

### Step 3 — Configure
Edit `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'yourusername_dbuser');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'yourusername_eagle');
define('SITE_URL', 'https://eagleachievers.in');  // Your domain
```

### Step 4 — Set Permissions
```
uploads/          → 755
uploads/courses/  → 755
uploads/avatars/  → 755
config/config.php → 644
```

### Step 5 — SSL
Enable Free SSL in cPanel → AutoSSL → Install

---

## RAZORPAY PAYMENT SETUP

1. Sign up at https://razorpay.com
2. Dashboard → Settings → API Keys → Generate Test Key
3. Copy Key ID and Key Secret
4. Edit `config/config.php`:
   ```php
   define('RAZORPAY_KEY_ID', 'rzp_test_XXXXXXXXXX');
   define('RAZORPAY_KEY_SECRET', 'XXXXXXXXXXXXXXXXXX');
   define('RAZORPAY_MODE', 'test');  // Change to 'live' for production
   ```
5. OR set via Admin Panel → Settings → Payment tab

### Test Cards (Razorpay Test Mode)
| Card Number         | CVV | Expiry  |
|---------------------|-----|---------|
| 4111 1111 1111 1111 | Any | Any future date |

---

## FIREBASE SETUP (Optional — for realtime notifications & chat)

1. Go to https://console.firebase.google.com
2. Create new project: `eagle-achievers`
3. Enable **Realtime Database**
   - Rules for development:
   ```json
   {
     "rules": {
       ".read": "auth != null",
       ".write": "auth != null"
     }
   }
   ```
4. Project Settings → Your Apps → Add Web App
5. Copy the config and update `config/config.php`:
   ```php
   define('FIREBASE_API_KEY', 'AIzaSy...');
   define('FIREBASE_AUTH_DOMAIN', 'eagle-achievers.firebaseapp.com');
   define('FIREBASE_PROJECT_ID', 'eagle-achievers');
   define('FIREBASE_STORAGE_BUCKET', 'eagle-achievers.appspot.com');
   define('FIREBASE_MESSAGING_SENDER_ID', '123456789');
   define('FIREBASE_APP_ID', '1:123456789:web:abc...');
   define('FIREBASE_DATABASE_URL', 'https://eagle-achievers-default-rtdb.firebaseio.com');
   ```

> ℹ️ Firebase is OPTIONAL. The site works 100% without it. Firebase only powers: realtime notifications, live chat widget, and activity logs.

---

## FOLDER STRUCTURE

```
eagle_achievers/
├── index.php                 ← Homepage
├── login.php                 ← User login
├── register.php              ← User registration
├── logout.php                ← Logout handler
├── .htaccess                 ← Apache rules + security
│
├── config/
│   └── config.php            ← DB, Razorpay, Firebase config
│
├── includes/
│   ├── functions.php         ← All helper functions
│   ├── header.php            ← Public site header/nav
│   └── footer.php            ← Public site footer + WhatsApp
│
├── pages/
│   ├── courses.php           ← Courses listing
│   ├── course.php            ← Course detail + payment
│   ├── affiliate.php         ← Affiliate program page
│   ├── about.php             ← About us
│   ├── contact.php           ← Contact form
│   ├── privacy.php           ← Privacy policy
│   ├── refund.php            ← Refund policy
│   ├── terms.php             ← Terms & conditions
│   └── shipping.php          ← Shipping policy
│
├── user/
│   └── dashboard.php         ← User dashboard (courses, affiliate, payouts)
│
├── admin/
│   ├── login.php             ← Admin login
│   ├── logout.php            ← Admin logout
│   ├── index.php             ← Admin dashboard (stats + charts)
│   ├── admin.css             ← Admin panel styles
│   ├── includes/
│   │   ├── header.php        ← Admin header + sidebar
│   │   └── footer.php        ← Admin footer + JS
│   └── pages/
│       ├── users.php         ← Manage users
│       ├── affiliates.php    ← Manage affiliates
│       ├── courses.php       ← Manage courses (add/edit/delete)
│       ├── orders.php        ← View orders + revenue
│       ├── enrollments.php   ← Manage enrollments + manual enroll
│       ├── commissions.php   ← Affiliate commissions + bulk approve
│       ├── payouts.php       ← Payout requests (approve/reject/paid)
│       ├── testimonials.php  ← Manage testimonials
│       ├── messages.php      ← Contact messages inbox
│       └── settings.php      ← Site settings panel
│
├── ajax/
│   ├── create_order.php      ← Create Razorpay order
│   ├── verify_payment.php    ← Verify payment + enroll + commission
│   ├── payout_request.php    ← Submit payout request
│   ├── become_affiliate.php  ← Apply for affiliate
│   ├── update_profile.php    ← Update user profile
│   ├── notifications.php     ← Notification AJAX API
│   └── support_chat.php      ← Save chat to MySQL
│
├── assets/
│   ├── css/style.css         ← Global premium styles
│   ├── js/app.js             ← Global JS (toast, Razorpay, etc.)
│   └── js/firebase-init.js   ← Firebase realtime features
│
├── uploads/
│   ├── .htaccess             ← Block PHP in uploads
│   ├── courses/              ← Course thumbnails
│   └── avatars/              ← User avatars
│
└── database/
    └── eagle_achievers.sql   ← Complete database schema + sample data
```

---

## KEY FEATURES SUMMARY

### ✅ PHP + MySQL (Core System)
- Secure user registration + login (bcrypt passwords)
- CSRF protection on all forms
- Admin panel with full sidebar navigation
- Course management (add/edit/delete + thumbnail upload)
- Order creation and payment verification
- Affiliate tracking (clicks → signups → purchases → commissions)
- Payout request system with UPI/bank transfer
- Manual enrollment by admin
- Contact form inbox in admin
- Site settings panel
- Pagination + search on all admin tables

### ✅ Firebase (Smart Realtime Features Only)
- Realtime notification push to users
- Online status tracking
- Live support chat widget
- Activity log (lightweight)

### ✅ Razorpay Payments
- Order creation via API
- Payment signature verification (HMAC-SHA256)
- Test mode + live mode support
- Auto enrollment after payment
- Auto commission calculation after payment

### ✅ Affiliate System
- Unique referral code per user
- 30-day tracking cookie
- Click tracking (deduped by IP per day)
- Signup attribution
- Purchase commission (per course rate)
- Payout requests with UPI / bank transfer
- Admin approve → mark paid workflow

---

## CUSTOMIZATION GUIDE

### Change Admin Password
```sql
UPDATE admins SET password='$2y$12$NEW_BCRYPT_HASH' WHERE email='admin@eagleachievers.in';
```
Generate hash: `password_hash('YourNewPassword', PASSWORD_BCRYPT, ['cost'=>12])`

### Add New Category
```sql
INSERT INTO categories (name, slug, icon) VALUES ('YouTube', 'youtube', '▶️');
```

### Change Commission Rate for a Course
Admin Panel → Courses → Edit → Change Commission % field

### Change Minimum Payout
Admin Panel → Settings → Affiliate Settings → Min Payout Amount

---

## SECURITY CHECKLIST (Before Going Live)

- [ ] Change admin password from default
- [ ] Set `SITE_URL` to your actual domain with `https://`
- [ ] Enable SSL in cPanel
- [ ] Set Razorpay to `live` mode with live keys
- [ ] Set strong `DB_PASS`
- [ ] Set file permissions (uploads → 755)
- [ ] Remove `database/` folder or block via .htaccess after import
- [ ] Enable Firebase security rules

---

## SUPPORT

📞 WhatsApp: +91 7006895694
✉️ Email: Support@eagleachievers.in
🌐 eagleachievers.in
