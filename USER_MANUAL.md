# RENTIFY - User Manual & Project Status Report

**Version:** 1.1
**Date:** February 15, 2026
**Stack:** Laravel 11 | PHP 8.2 | Tailwind CSS | Alpine.js | MySQL 8

---

## Table of Contents

1. [Getting Started](#1-getting-started)
2. [Agent Portal (Full Guide)](#2-agent-portal)
3. [Landlord Portal](#3-landlord-portal)
4. [Tenant Portal](#4-tenant-portal)
5. [Email Notifications](#5-email-notifications)
6. [Feature Status Matrix](#6-feature-status-matrix)
7. [What's Left to Build](#7-whats-left-to-build)
8. [Hosting with ngrok (Temporary)](#8-hosting-with-ngrok)

---

## 1. Getting Started

### Demo Credentials

| Role     | Email                  | Password   |
|----------|------------------------|------------|
| Agent    | agent@rentify.co.ke    | password   |
| Landlord | landlord@rentify.co.ke | password   |
| Tenant   | tenant@rentify.co.ke   | password   |

### Accessing the App

- **Local:** `http://rentify-api.test` (via Laragon)
- **Remote/Demo:** Via ngrok URL (see [Section 8](#8-hosting-with-ngrok))
  - If using ngrok, click **"Visit Site"** on the browser warning page

### Registration

Visit `/register` to create a new account. Choose your role:
- **Agent** — Manages everything (properties, tenants, payments)
- **Landlord** — Views their properties, income, and leases
- **Tenant** — Views lease, pays rent, submits maintenance

After registration you are auto-logged in and redirected to your role's dashboard.

### Re-seeding Demo Data

To reset and reload demo data:
```bash
php artisan migrate:fresh --seed
```

This creates 8 users, 3 properties, 10 units, 5 active leases, 1 pending lease (for e-signature demo), invoices, and payments.

---

## 2. Agent Portal

The Agent is the system admin. They manage properties on behalf of landlords and handle all day-to-day operations.

### 2.1 Dashboard (`/agent/dashboard`)

Displays at a glance:
- **Properties** — Total count of properties you manage
- **Occupied Units** — e.g. "8/10" (occupied out of total)
- **Total Collected** — Sum of all completed payments (KSh)
- **Pending Invoices** — Number of unpaid invoices

Below the stats:
- **Recent Payments** — Last 5 payments received with tenant name and amount
- **Overdue Invoices** — Last 5 overdue invoices requiring attention
- **Quick Actions** — Shortcut buttons to Add Property, New Lease, Create Invoice, Record Payment

### 2.2 Property Management

#### Properties (`/agent/properties`)
- View all properties in a table (name, county, type, landlord, unit count)
- **Add Property** — Name, address, county (Kenya counties dropdown), type (apartment/house/commercial/office/warehouse/land), description, photo uploads (multiple)
- **View Property** — Shows stats (total/occupied/vacant units), property details, photo gallery, and units table
- **Edit/Delete Property** — Update details or remove

#### Units (`/agent/properties/{id}/units`)
- Each property has multiple units
- **Add Unit** — Unit number, size (e.g. "2 Bedroom"), rent amount, deposit, status (vacant/occupied)
- **Edit Unit** — Update rent, status, etc.
- Units are nested under properties in the URL

### 2.3 People Management

#### Landlords (`/agent/landlords`)
- **Add Landlord** — Creates a user account + landlord record
- Fields: Name, email, password, national ID, payment details (bank name, account number, M-Pesa number)
- View/edit/delete landlords

#### Tenants (`/agent/tenants`)
- **Add Tenant** — Creates a user account + tenant record
- Fields: Name, email, password, phone, emergency contact, ID document upload (PDF/JPG/PNG, max 5MB)
- **View Tenant** — Shows profile info with downloadable ID document link
- Edit/delete tenants

### 2.4 Lease Management (`/agent/leases`)

- **Create Lease** — Select tenant, select unit, set start/end dates, monthly rent, deposit, terms
- **New:** Leases are created in **Pending** status — the tenant must sign the lease to activate it (see [Tenant E-Signature](#42-my-lease--e-signature))
- When a lease is signed and activated, the unit is automatically marked as **Occupied**
- When a lease is deleted, the unit reverts to **Vacant**
- **View Lease** — Full details with tenant info, unit info, dates, financial terms
- Status flow: `Pending → Active (signed) → Expired/Terminated`
- **Email notification** is sent to the tenant when a new lease is created

### 2.5 Finance

#### Invoices (`/agent/invoices`)
- **Auto-Generated:** System generates monthly invoices on the 1st of every month for all active leases (due on the 5th)
- **Manual Creation:** Agent can also create invoices manually — select lease, enter amount, due date, description
- Invoice statuses: Pending, Paid, Overdue, Partially Paid, Cancelled
- **Auto-Overdue:** System marks past-due invoices as overdue daily at 8:00 AM
- **Email notification** is sent to the tenant when an invoice is generated

#### Payments (`/agent/payments`)
- **Record Payment** — Select an unpaid invoice, enter amount, payment method, M-Pesa receipt number
- Payment methods: M-Pesa, Bank Transfer, Cash, Cheque
- All recorded payments are timestamped with `paid_at`
- **Email notification** is sent to the tenant when a payment is recorded

### 2.6 Maintenance (`/agent/maintenance`)
- View all maintenance requests from tenants
- Each request shows: title, unit, property, tenant, priority, status
- **Update Request** — Change status (pending → in_progress → completed), assign to someone, add resolution notes
- View attached photos from tenant
- **Email notification** is sent to the tenant when a maintenance request is updated

### 2.7 Reports (`/agent/reports`)

All reports are fully functional with live data:

- **Reports Dashboard** — Summary stats (total units, occupancy rate, total collected, total arrears) + quick links to each report + landlord statement links
- **Rent Roll** (`/agent/reports/rent-roll`) — All properties and units showing: unit number, size, rent amount, occupancy status, current tenant. Summary cards for total potential rent, occupied rent, and vacant loss
- **Arrears Report** (`/agent/reports/arrears`) — All overdue/unpaid invoices showing: tenant, property/unit, invoice amount, amount paid, balance due, due date, status badge. Total outstanding arrears summary
- **Occupancy Report** (`/agent/reports/occupancy`) — Per-property breakdown with: total units, occupied, vacant, occupancy rate with color-coded progress bars. Overall occupancy stats
- **Landlord Statement** (`/agent/reports/landlord-statement/{landlord}`) — Per-landlord financial view showing: properties summary (units, occupied, income collected, pending), detailed payment records (date, tenant, property/unit, amount, method)

### 2.8 Notifications (`/agent/notifications`)
- **Send Notification** — Select recipient (landlord or tenant), choose type (general, payment reminder, maintenance update, lease expiry), enter subject and message
- View all sent/received notifications
- Unread indicator (blue dot)
- **Auto Reminders:** System sends rent reminders 3 days before due date

---

## 3. Landlord Portal

Landlords have read-only access to their properties and financial information.

### 3.1 Dashboard (`/landlord/dashboard`)
- **My Properties** — Count of properties assigned to them
- **Occupied Units** — Occupied vs total
- **Total Income** — All completed payments across their properties
- **Pending Amount** — Outstanding invoices
- **Recent Payments** — Last 10 payments with tenant names

### 3.2 My Properties (`/landlord/properties`)
- View list of their properties (name, county, type, units)
- **Property Detail** — Photo gallery, property info, units table with tenant names and statuses
- Read-only (agent manages)

### 3.3 Tenants (`/landlord/tenants`)
- View all tenants occupying their properties
- Read-only tenant details

### 3.4 Leases (`/landlord/leases`)
- View all leases on their properties
- **Approve Lease** — Can approve leases in "Pending" status
- Lease detail page with full terms

### 3.5 Financials (`/landlord/financials`)
- **Overview** — Total income, pending, overdue amounts
- **Statement** — Detailed monthly breakdown with invoices and payments
- Filterable financial data

### 3.6 Notifications (`/landlord/notifications`)
- View notifications sent by the agent
- Payment reminders, maintenance updates, lease alerts

---

## 4. Tenant Portal

Tenants can view their lease, pay rent, and submit maintenance requests.

### 4.1 Dashboard (`/tenant/dashboard`)
- **Monthly Rent** — Current rent amount
- **Next Due** — Amount and due date of next pending invoice
- **Total Paid** — Lifetime payments made
- **Open Requests** — Count of pending/in-progress maintenance requests
- **Current Lease** card showing property, unit, and lease period
- **Recent Payments** — Last 5 transactions

### 4.2 My Lease + E-Signature (`/tenant/lease`)

- **Active Lease** — View active lease details (property, unit, dates, rent, deposit, terms). If signed, shows signature image and signed date
- **Pending Leases (Awaiting Signature)** — When the agent creates a new lease, it appears here in "Pending" status
  - Tenant reviews lease details (property, unit, rent, deposit, dates, terms & conditions)
  - Click **"Sign Now"** to open the signing area
  - **Draw signature** on an HTML5 canvas using mouse or finger (touch-enabled for mobile)
  - Check the "I agree to terms" checkbox
  - Click **"Sign & Accept Lease"** to submit
  - The lease status changes to **Active**, the unit becomes **Occupied**, and the signature is saved
  - The signed signature image is displayed on the active lease card
- **Previous Leases** — View expired or terminated leases

**To test e-signature:** Log in as tenant (`tenant@rentify.co.ke` / `password`). After re-seeding, there will be 1 pending lease awaiting signature.

### 4.3 Invoices (`/tenant/invoices`)
- View all invoices with status badges (Pending, Paid, Overdue)
- Invoice detail with balance calculation

### 4.4 Payments (`/tenant/payments`)
- View payment history with method, reference, and dates
- Payment initiation (M-Pesa integration pending — see below)

### 4.5 Maintenance (`/tenant/maintenance`)
- **Submit Request** — Title, description, priority (Low/Medium/High/Urgent), photo uploads (multiple)
- **View Request** — Status tracking, photo gallery, resolution notes from agent
- Authorization enforced — tenants can only view their own requests

### 4.6 Notifications (`/tenant/notifications`)
- View all notifications (rent reminders, maintenance updates, general)

---

## 5. Email Notifications

The system sends automatic email notifications for key events. Currently configured with **MAIL_MAILER=log** (emails are written to `storage/logs/laravel.log` for testing).

### Emails Sent:

| Event | Recipient | Subject |
|-------|-----------|---------|
| Invoice generated | Tenant | "Rentify - Invoice for [Month Year]" |
| Payment recorded | Tenant | "Rentify - Payment Confirmed" |
| New lease created | Tenant | "Rentify - New Lease Agreement" |
| Maintenance updated | Tenant | "Rentify - Maintenance Request Update" |

### How to View Emails (Testing)

Since emails go to the log file:
```bash
# View the latest email in the log
tail -100 storage/logs/laravel.log
```

### Switching to Real Email (Production)

Update `.env` with SMTP credentials:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@rentify.co.ke
MAIL_FROM_NAME=Rentify
```

---

## 6. Feature Status Matrix

### Mapping to Original Requirements Doc

| # | Feature (from Spec) | Status | Notes |
|---|---------------------|--------|-------|
| **Authentication & Security** ||||
| 1 | Email + password login | DONE | Laravel Breeze |
| 2 | Role-based access control | DONE | RoleMiddleware with agent/landlord/tenant |
| 3 | Password hashing | DONE | Bcrypt via Laravel |
| 4 | Session authentication | DONE | Cookie-based sessions |
| **Property Management** ||||
| 5 | Create properties | DONE | Full CRUD with photos |
| 6 | Assign landlord | DONE | Select landlord on property create |
| 7 | Add units under properties | DONE | Nested CRUD |
| 8 | Track unit status | DONE | Vacant/Occupied auto-updated |
| 9 | Property photos | DONE | Multi-upload, gallery display |
| **Tenant & Lease Management** ||||
| 10 | Tenant onboarding | DONE | Agent creates tenant with profile + ID doc |
| 11 | Lease creation & lifecycle | DONE | Full CRUD, status flow |
| 12 | Lease expiry alerts | DONE | Auto-expire command + notifications |
| 13 | E-signature workflow | DONE | Canvas signature, base64 PNG save, tenant signs to activate |
| **Billing & Payments** ||||
| 14 | Automated invoice generation | DONE | Monthly on 1st, scheduled command |
| 15 | Rent schedules | DONE | Lease-based, auto-invoiced |
| 16 | M-PESA STK Push | NOT STARTED | Requires Safaricom Daraja API credentials |
| 17 | M-PESA Paybill support | NOT STARTED | Callback controller exists (stub) |
| 18 | Payment reconciliation | PARTIAL | Agent manually records; auto-reconciliation needs M-Pesa |
| 19 | Manual payment recording | DONE | Agent can record with method + receipt |
| 20 | Auto-overdue marking | DONE | Daily scheduled command |
| **Maintenance Management** ||||
| 21 | Issue submission with media | DONE | Title, description, priority, photo uploads |
| 22 | Assignment & status updates | DONE | Agent can assign, update status, add notes |
| 23 | Resolution tracking | DONE | Status flow + resolution notes + resolved_at |
| **Notifications** ||||
| 24 | In-app notifications | DONE | Full CRUD, auto-reminders |
| 25 | SMS reminders | NOT STARTED | Needs SMS gateway (Africa's Talking/Twilio) |
| 26 | Email notifications | DONE | 4 Mailable classes, queued delivery, HTML templates |
| **Reporting** ||||
| 27 | Rent roll | DONE | Full data: units, rent amounts, tenants, vacancy status |
| 28 | Arrears report | DONE | Overdue invoices with paid/balance breakdown |
| 29 | Occupancy rate | DONE | Per-property breakdown with progress bars |
| 30 | Landlord statements | DONE | Properties summary + payment details per landlord |
| **Dashboards** ||||
| 31 | Agent dashboard | DONE | Stats, recent payments, overdue, quick actions |
| 32 | Landlord dashboard | DONE | Stats, income, recent payments |
| 33 | Tenant dashboard | DONE | Rent, next due, lease info, payments |
| **Other** ||||
| 34 | Premium responsive UI | DONE | Tailwind CSS, mobile sidebar, Inter font |
| 35 | Database seeder / demo data | DONE | 8 users, 3 properties, 10 units, 6 leases, etc. |

### Summary Count

| Status | Count |
|--------|-------|
| DONE | 31 |
| PARTIAL | 1 |
| NOT STARTED | 3 |
| **Total** | **35** |

**Completion: ~89% fully done, ~91% including partial**

---

## 7. What's Left to Build

### Priority 1: M-Pesa Integration (Estimated: 3-5 days)
This is the biggest remaining feature. Requires:
- **Safaricom Daraja API** developer account + credentials
- **STK Push** — Tenant initiates payment, prompt sent to phone
- **Callback handler** — Process M-Pesa confirmation, auto-update invoice status
- **Paybill/Till Number** configuration
- Testing with sandbox first, then production credentials

What exists:
- `MpesaCallbackController` (stub with routes registered)
- `MpesaService` (empty file, ready to implement)
- Payment method field supports "mpesa" with receipt number

### Priority 2: SMS Notifications (Estimated: 1-2 days)
- Integrate Africa's Talking or Twilio SMS API
- Add phone validation on registration
- SMS triggers: rent reminders, payment confirmations, maintenance updates

### Nice-to-Have (Future)
- PDF export for invoices/receipts/lease agreements
- Dashboard charts & analytics (Chart.js)
- Admin panel (super-admin role)
- Search & filtering across all tables
- Audit logs for payments
- Mobile app (React Native / Flutter)
- AI rent default prediction
- Accounting software integration

---

## 8. Hosting with ngrok (Temporary)

### Prerequisites
1. **Laragon** running with MySQL and Apache/Nginx
2. **ngrok** installed ([download here](https://ngrok.com/download))
3. App accessible locally at `http://rentify-api.test`

### Step-by-Step Setup

#### 1. Start Laragon
Open Laragon and click **"Start All"** to start Apache + MySQL.

#### 2. Re-seed the database (if needed)
```bash
cd C:\laragon\www\Rentify\rentify-api
php artisan migrate:fresh --seed
php artisan storage:link
```

#### 3. Start ngrok
Open a terminal and run:
```bash
ngrok http 80 --host-header=rentify-api.test
```

This gives you a public URL like: `https://abc123.ngrok-free.app`

#### 4. Update Laravel trusted proxies
The app already supports proxied requests. If you encounter HTTPS/redirect issues, add to `.env`:
```env
APP_URL=https://abc123.ngrok-free.app
ASSET_URL=https://abc123.ngrok-free.app
```

Then clear config cache:
```bash
php artisan config:clear
```

#### 5. Share the URL
Send the ngrok URL to testers. They will see a **"Visit Site"** button on the ngrok warning page — click it to proceed.

### Important Notes for ngrok
- The free tier URL changes every time you restart ngrok
- Session cookies may not persist across different browsers — each tester should use their own browser
- File uploads (photos, ID docs) work normally
- ngrok has a request limit on the free tier — for extended testing, use a paid plan or deploy to a proper server

### Alternative: Laravel Herd or Valet (macOS)
If testers are on macOS, they can also use Laravel Herd with `herd share` for similar functionality.

---

## Automated Scheduled Tasks

These run automatically (configure with `cron` or Laravel Scheduler):

| Command | Schedule | What it Does |
|---------|----------|--------------|
| `invoices:generate` | 1st of month, 00:01 | Creates monthly invoices for all active leases |
| `invoices:mark-overdue` | Daily, 08:00 | Marks past-due pending invoices as overdue |
| `reminders:rent` | Daily, 09:00 | Sends in-app reminders 3 days before due date |
| `leases:expire` | Daily | Expires leases past their end date, frees units |

To run the scheduler, add this to your server's crontab:
```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## Tech Stack Summary

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 11 (PHP 8.2) |
| Frontend | Blade + Tailwind CSS 3 + Alpine.js |
| Database | MySQL 8.0 |
| Auth | Laravel Breeze (session-based) |
| Build | Vite |
| IDs | ULIDs (Universally Unique Lexicographically Sortable) |
| File Storage | Local disk (`storage/app/public` with symlink) |
| Email | Laravel Mail (log driver for testing, SMTP for production) |

---

## Testing Checklist for Testers

Use this checklist to verify all features:

### Agent (login: agent@rentify.co.ke / password)
- [ ] Dashboard loads with stats
- [ ] View properties list → click into a property → see photo gallery + units
- [ ] Add a new property with photos
- [ ] Add a unit to a property
- [ ] View landlords list → add a new landlord
- [ ] View tenants list → add a new tenant
- [ ] Create a new lease (select tenant + vacant unit) → verify it shows as "Pending"
- [ ] View invoices → create a manual invoice
- [ ] Record a payment against an invoice
- [ ] View maintenance requests → update status of a request
- [ ] Go to Reports → check Rent Roll, Arrears, Occupancy, Landlord Statement
- [ ] Send a notification to a tenant

### Landlord (login: landlord@rentify.co.ke / password)
- [ ] Dashboard loads with income stats
- [ ] View properties with photo galleries
- [ ] View tenants list
- [ ] View leases
- [ ] View financials / statement
- [ ] View notifications

### Tenant (login: tenant@rentify.co.ke / password)
- [ ] Dashboard loads with rent info + lease card
- [ ] Go to My Lease → see active lease + pending lease awaiting signature
- [ ] **E-Signature test:** Click "Sign Now" on the pending lease → draw signature → check agree → submit → verify lease becomes Active
- [ ] View invoices list
- [ ] View payments history
- [ ] Submit a maintenance request with photos
- [ ] View maintenance request details with photo gallery
- [ ] View notifications

---

*Generated: February 15, 2026*
*Rentify v1.1 — Property Management for Kenya*
