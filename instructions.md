# Project Instructions: Cosplay Rental Management System

## 1. Project Overview
You are tasked with building a modern, flat-design web application for a Cosplay Rental Business. The system must handle complex inventory tracking (down to individual costume components), hardware integrations for physical store operations, automated email notifications, and robust business logic (security deposits, penalty calculations, and role-based access).

## 2. Tech Stack Requirements
Strictly use the following technologies. Do not deviate unless explicitly instructed.
- **Backend Framework:** Laravel 13 (PHP 8.3+)
- **Database:** MariaDB (Ensure all migrations and Eloquent models are optimized for MariaDB engines)
- **Frontend:** Blade Templates, Tailwind CSS
- **Asynchronous Tasks:** Native Laravel 13 Queues and Task Scheduling (Cron)

## 3. Design System & UI/UX Rules
The application must strictly follow these design constraints:
- **Typography:** Strictly use the `Inter` font for all text.
- **Color Palette:**
  - `Background / Light Elements`: `#FCF0F2`
  - `Primary Accent / Dark Elements`: `#F2B3BD`
  - `Text`: Use dark slate/gray (`text-gray-800` or `text-gray-900` in Tailwind) for optimal readability against the light background.
- **Styling Direction:** Flat, minimalist, and utilitarian. 
- **Mobile-First:** Ensure all public-facing pages (Landing Page, Catalog, Checkout) are fully responsive and optimized for mobile screens using Tailwind's responsive utilities.
- **STRICT PROHIBITIONS:** Zero "AI-style" designs. DO NOT use glow effects, glassmorphism, neon shadows, or heavy gradients. Keep the UI extremely clean, professional, and high-contrast.

## 4. Database Schema & Integrity Guidelines (MariaDB)
Generate migrations for at least the following core entities. **Crucial:** Implement `SoftDeletes` on `users`, `costumes`, and `costume_components` to preserve booking history integrity.
- `users`: Standard user data + `phone_number` (string), `rfid_uid` (nullable string, unique), `role` (enum: 'admin', 'customer').
- `costumes`: `name`, `series`, `size`, `base_price`, `deposit_price`, `image_path` (nullable string).
- `costume_components`: `costume_id`, `name` (e.g., "Red Wig"), `barcode_string` (unique), `status`.
- `bookings`: `user_id`, `costume_id`, `start_date`, `end_date`, `status`, `total_price` (includes deposit), `penalty_fee` (default 0), `payment_proof` (nullable string).
- `user_addresses`: `user_id`, `address_line`, `is_primary` (boolean).

## 5. Security & Access Control
- **Role-Based Access Control (RBAC):** Implement a middleware that strictly blocks `customer` role from accessing any `/admin/*` routes (must return a 403 Forbidden response).

## 6. Core Features & Hardware Logic

### A. Barcode Integration (Component Tracking)
- **Hardware Logic:** Physical barcode scanners act as rapid keyboard inputs followed by an `Enter` keystroke. Create an input field in Blade that is automatically focused and captures this rapid input, triggering a Javascript form submission or Fetch API call.
- **Backend Logic:** Scanning a component's barcode toggles its status (e.g., from `Rented` to `Returned`).

### B. RFID Integration (Quick Access & Premium Items)
- **Hardware Logic:** Similar to the barcode, RFID readers input a string and hit `Enter`. Create a global listener or a dedicated hidden input field.
- **Backend Logic:** Tapping an RFID card instantly fetches the associated User profile and their active `Bookings`.

### C. Mailtrap Integration (Automated Notifications)
- Configure `.env` to use the SMTP Mailtrap driver.
- **Mailables:** 
  1. `BookingConfirmed`: Triggered after an admin confirms a payment.
  2. `ReturnReminder`: A scheduled command running daily, finding `Bookings` due tomorrow and dispatching an email reminder.

## 7. Admin Dashboard Features (Manajemen Admin)
All administrative interfaces must use **Bahasa Indonesia** and adhere to the flat design system.

### A. Status Tracker & Overview (`/admin/dashboard`)
Display categorized lists/tables for real-time tracking: `Menunggu Konfirmasi`, `Sedang Dikirim`, `Sedang Dirental`, `Proses Kembali`, and `Tersedia`.

### B. Katalog CRUD (`/admin/katalog`)
- Form to Create, Read, Update, and Delete costumes.
- **Upload Gambar Kostum:** Securely store display images (`image_path`) in `storage/app/public` and use `php artisan storage:link`.
- **Nested Component Form:** Dynamically add multiple accessories and assign a unique barcode string to each in the same form.

### C. Manajemen Pesanan Masuk (`/admin/pesanan-rental`)
- Display orders with status `menunggu_konfirmasi`.
- Admins can view a modal containing the Customer Name, Costume Details, Shipping Address, and the uploaded **Gambar Bukti Pembayaran**.
- A "Konfirmasi Pesanan" button updates status to `diproses` and triggers the `BookingConfirmed` email.

### D. Quality Control Return Flow & Denda (`/admin/qc-barcode`)
- View triggered when a package returns. Displays a checklist of expected components.
- **Barcode Scanning:** Scanning a component automatically checks it off.
- **Late Fee Logic:** The system must automatically calculate days past `end_date` and suggest a late fee.
- **Missing Items & Deposit:** If items are missing/damaged, or if there's a late fee, the admin inputs the `penalty_fee`. The system calculates how much of the `deposit_price` is refunded or if the user owes more.

### E. Laporan Keuangan (Reporting)
- A simple page displaying total rental revenue for the current month.
- Include a button to export this data (e.g., using a simple CSV export or Laravel PDF library) so the business owner can download the report.

## 8. User Features & Member Area (Frontend)
All user-facing text must strictly use **Bahasa Indonesia**.

### A. Landing Page & Katalog (`/` and `/katalog`)
- **Landing Page:** Hero section, "Cara Sewa" 3-step guide, and Featured Catalog.
- **Katalog:** Grid layout with search/filters. Display costume images using `asset('storage/...')` with a flat placeholder fallback if no image exists. No shadows/glows on cards.
- **Detail Kostum:** Display Size, Description, and a list of all included accessories/components.

### B. Profil & Manajemen Alamat (`/profil`)
- Users can update their basic info, notably their **Nomor Telepon/WhatsApp**.
- Users can manage multiple addresses and set one as "Alamat Utama" (Primary).

### C. Checkout Flow (`/checkout`)
- **Alamat:** Pre-select "Alamat Utama" with an option to temporarily select another saved address for this specific booking.
- **Perhitungan Harga:** Clearly breakdown the `base_price` * days + `deposit_price` (Uang Jaminan).
- **Validasi Tanggal:** Date picker strictly disables past and already-booked dates.
- **Upload Bukti:** Required file upload for "Bukti Pembayaran" (`mimes:jpg,jpeg,png`, max 2MB).
- **Persetujuan (T&C):** A mandatory checkbox: "Saya setuju dengan Syarat & Ketentuan (termasuk denda kerusakan/keterlambatan)." Must be checked to submit.

### D. Halaman Pesanan User (`/pesanan`)
- Display order history with a status tracking pipeline (*Menunggu Konfirmasi* -> *Diproses* -> *Sedang Dikirim* -> *Sedang Dirental* -> *Proses Kembali* -> *Selesai*).
- Details view shows the rental period, components, total paid, and any final penalty deductions from their deposit.

## 9. Global Form Validation & Toast Notifications
- **Frontend & Backend Validation:** Implement strict client-side validations mirrored by Laravel Form Requests.
- **Sistem Toast:** Build a flat-design reusable Toast component (utilizing `#F2B3BD` and `#FCF0F2`).
- Trigger Toasts in **Bahasa Indonesia** for Success (e.g., form submissions, successful barcode scans) and Error (e.g., invalid file types, unauthorized access, missing components).

## 10. Execution Steps for Agents
1. Initialize Laravel 13, configure MariaDB and Mailtrap.
2. Scaffold Tailwind CSS, configure `Inter` font, and custom color palette.
3. Generate migrations (with SoftDeletes) and models. Run `php artisan storage:link`.
4. Build the middleware for RBAC.
5. Develop Admin and User views following the strict flat Design System and Mobile-First approach.
6. Implement Barcode/RFID JavaScript listeners.
7. Implement Mailables, Scheduled Tasks, and Export logic.
8. Output a summary of routes and instructions for testing the hardware inputs and running the cron scheduler.
