# RENTIFY — Comprehensive QA Testing Guide

**Version:** 3.0
**Date:** February 16, 2026
**Application:** Rentify — Property Management System for Kenya
**Stack:** Laravel 11 | PHP 8.2 | Tailwind CSS | Alpine.js | MySQL 8
**Total Test Cases:** 338

---

This guide is structured as **one continuous business journey** — from system access through asset setup, tenancy lifecycle, money flow, support, oversight, and security. Each flow leads naturally into the next, the way real property management works.

---

## Table of Contents

**SETUP**
- [1. Environment Setup & Prerequisites](#1-environment-setup--prerequisites)
- [2. Test Data Reference](#2-test-data-reference)

**FLOW 1 — System Access & Identity**
- [3. Authentication & Registration](#3-flow-1--authentication--registration)

**FLOW 2 — System Foundation Setup (Agent-Only)**
- [4. Landlord Management](#4-flow-2a--landlord-management)
- [5. Property Management](#5-flow-2b--property-management)
- [6. Unit Management](#6-flow-2c--unit-management)

**FLOW 3 — People Onboarding**
- [7. Tenant Management](#7-flow-3--tenant-management)

**FLOW 4 — Lease Lifecycle (Core Business Logic)**
- [8. Lease Creation (Agent)](#8-flow-4a--lease-creation-agent)
- [9. E-Signature & Lease Activation (Tenant)](#9-flow-4b--e-signature--lease-activation-tenant)
- [10. Lease Visibility Across Roles](#10-flow-4c--lease-visibility-across-roles)

**FLOW 5 — Billing & Money Flow**
- [11. Invoice Management (Agent)](#11-flow-5a--invoice-management-agent)
- [12. Payment Recording (Agent)](#12-flow-5b--payment-recording-agent)
- [13. Financial Visibility Across Roles](#13-flow-5c--financial-visibility-across-roles)

**FLOW 6 — Maintenance & Support**
- [14. Maintenance Requests (Tenant)](#14-flow-6a--maintenance-requests-tenant)
- [15. Maintenance Handling (Agent)](#15-flow-6b--maintenance-handling-agent)

**FLOW 7 — Communication & Notifications**
- [16. In-App Notifications](#16-flow-7a--in-app-notifications)
- [17. Email Notifications (Logged)](#17-flow-7b--email-notifications-logged)

**FLOW 8 — Dashboards & Oversight**
- [18. Agent Dashboard](#18-flow-8a--agent-dashboard)
- [19. Landlord Dashboard & Portal](#19-flow-8b--landlord-dashboard--portal)
- [20. Tenant Dashboard & Portal](#20-flow-8c--tenant-dashboard--portal)

**FLOW 9 — Reporting & Statements**
- [21. Agent Reports](#21-flow-9a--agent-reports)
- [22. Landlord Statements](#22-flow-9b--landlord-statements)

**FLOW 10 — Security, Isolation & Trust**
- [23. Role-Based Access Control](#23-flow-10a--role-based-access-control)
- [24. Cross-Role Data Isolation](#24-flow-10b--cross-role-data-isolation)

**FLOW 11 — Stability & Real-World Use**
- [25. File Uploads & Storage](#25-flow-11a--file-uploads--storage)
- [26. Responsive Design & Mobile](#26-flow-11b--responsive-design--mobile)
- [27. Edge Cases & Error Handling](#27-flow-11c--edge-cases--error-handling)

**FLOW 12 — Full Business Journey**
- [28. Full End-to-End Workflow](#28-flow-12--full-end-to-end-workflow)

**WRAP-UP**
- [29. Known Limitations](#29-known-limitations)
- [30. Test Execution Summary](#30-test-execution-summary)

---

## 1. Environment Setup & Prerequisites

### 1.1 System Requirements

| Component | Required Version | Purpose |
|-----------|-----------------|---------|
| PHP | 8.2 or higher | Backend runtime |
| MySQL | 8.0 or higher | Database |
| Node.js | 18 or higher | Asset compilation (Vite) |
| Composer | 2.x | PHP package manager |
| Laragon | Latest | Local development server (Apache + MySQL) |
| ngrok | 3.x (optional) | Temporary public URL for remote testing |
| Browser | Chrome / Firefox / Safari / Edge (latest) | Testing |

### 1.2 Initial Setup (First Time Only)

Open a terminal in `C:\laragon\www\Rentify\rentify-api` and run each command:

```bash
# Step 1: Install PHP dependencies
composer install

# Step 2: Install JavaScript dependencies
npm install

# Step 3: Build frontend assets (Tailwind CSS + Alpine.js via Vite)
npm run build

# Step 4: Create environment file (if not already present)
cp .env.example .env

# Step 5: Generate application key
php artisan key:generate

# Step 6: Create the storage symlink (enables file uploads to be served in the browser)
php artisan storage:link
```

### 1.3 Database Setup (Run Before EVERY Test Session)

This command drops all tables, recreates them, and populates with demo data:

```bash
php artisan migrate:fresh --seed
```

**What this creates (exact counts):**

| Data Type | Count | Details |
|-----------|-------|---------|
| Users | 8 | 1 agent, 2 landlords, 5 tenants |
| Landlord Records | 2 | Linked to landlord users |
| Tenant Records | 5 | Linked to tenant users |
| Properties | 3 | Sunrise Apartments, Garden Villas, Business Hub Tower |
| Units | 10 | 5 occupied, 5 vacant |
| Leases | 6 | 5 active (signed) + 1 pending (for e-signature demo) |
| Invoices | ~12 | Mix of Paid (past months) and Pending (current month) |
| Payments | ~7 | All completed, M-Pesa method |
| Maintenance Requests | 3 | 1 pending, 1 in-progress, 1 pending-urgent |
| Notifications | 19 | Spread across all users |

**IMPORTANT:** Always re-seed before starting a new test session to ensure a clean, consistent starting state.

### 1.4 Starting the Application

1. Open the **Laragon** application
2. Click **"Start All"** — this starts both Apache and MySQL
3. Wait for both indicators to turn green
4. Open your browser and navigate to: `http://Rentify.test`
5. You should see the Rentify login page

**Troubleshooting — If you see the Laragon default page:**
The Apache virtual host needs to be configured. The document root must point to:
`C:/laragon/www/Rentify/rentify-api/public`

### 1.5 Remote Testing with ngrok (Optional)

For sharing the app with remote testers who are not on your local machine:

```bash
# Open a SEPARATE terminal (keep Laragon running), then run:
ngrok http 80 --host-header=Rentify.test
```

This gives you a URL like `https://xxxx-xxxx.ngrok-free.app`. Share this with testers.

**If styles/assets don't load via ngrok**, update `.env`:
```
APP_URL=https://xxxx-xxxx.ngrok-free.app
ASSET_URL=https://xxxx-xxxx.ngrok-free.app
```
Then clear the config cache: `php artisan config:clear`

**Note:** Remote testers will see an ngrok interstitial page — click **"Visit Site"** to proceed.

---

## 2. Test Data Reference

### 2.1 User Accounts (All passwords: `password`)

| # | Role | Name | Email | Phone |
|---|------|------|-------|-------|
| 1 | **Agent** | John Kamau | `agent@rentify.co.ke` | 0712345678 |
| 2 | **Landlord** | Mary Wanjiku | `landlord@rentify.co.ke` | 0723456789 |
| 3 | **Landlord** | Peter Ochieng | `landlord2@rentify.co.ke` | 0734567890 |
| 4 | **Tenant** | Grace Muthoni | `tenant@rentify.co.ke` | 0745678901 |
| 5 | **Tenant** | David Kiprop | `tenant2@rentify.co.ke` | 0756789012 |
| 6 | **Tenant** | Sarah Akinyi | `tenant3@rentify.co.ke` | 0767890123 |
| 7 | **Tenant** | James Mwangi | `tenant4@rentify.co.ke` | 0778901234 |
| 8 | **Tenant** | Lucy Njeri | `tenant5@rentify.co.ke` | 0789012345 |

### 2.2 Properties

| Property Name | Type | Address | County | Landlord | Total Units |
|---------------|------|---------|--------|----------|-------------|
| Sunrise Apartments | Apartment | 123 Kenyatta Avenue | Nairobi | Mary Wanjiku | 4 (A1, A2, B1, B2) |
| Garden Villas | House | 45 Ngong Road | Nairobi | Mary Wanjiku | 3 (V1, V2, V3) |
| Business Hub Tower | Commercial | 78 Moi Avenue | Mombasa | Peter Ochieng | 3 (S1, S2, S3) |

### 2.3 Units

| Unit | Property | Size | Rent (KSh) | Deposit (KSh) | Status | Tenant |
|------|----------|------|------------|----------------|--------|--------|
| A1 | Sunrise Apartments | 1 Bedroom | 15,000 | 15,000 | **Occupied** | Grace Muthoni |
| A2 | Sunrise Apartments | 2 Bedroom | 18,000 | 18,000 | **Occupied** | David Kiprop |
| B1 | Sunrise Apartments | 2 Bedroom | 22,000 | 22,000 | **Occupied** | Sarah Akinyi |
| B2 | Sunrise Apartments | 2 Bedroom | 22,000 | 22,000 | **Vacant** | — |
| V1 | Garden Villas | 3 Bedroom | 35,000 | 35,000 | **Occupied** | James Mwangi |
| V2 | Garden Villas | 3 Bedroom | 35,000 | 35,000 | **Vacant** | — |
| V3 | Garden Villas | 4 Bedroom | 40,000 | 40,000 | **Vacant** | — |
| S1 | Business Hub Tower | Office Suite | 45,000 | 45,000 | **Occupied** | Lucy Njeri |
| S2 | Business Hub Tower | Office Suite | 50,000 | 50,000 | **Vacant** | — |
| S3 | Business Hub Tower | Large Office Suite | 55,000 | 55,000 | **Vacant** | — |

### 2.4 Leases

| Tenant | Unit | Rent (KSh) | Status | Started | Notes |
|--------|------|------------|--------|---------|-------|
| Grace Muthoni | A1 | 15,000 | **Active** | 6 months ago | Demo tenant — has invoices + payments |
| Grace Muthoni | V2 | 35,000 | **Pending** | Current month | **For e-signature demo — NOT yet signed** |
| David Kiprop | A2 | 18,000 | **Active** | 3 months ago | |
| Sarah Akinyi | B1 | 22,000 | **Active** | 2 months ago | |
| James Mwangi | V1 | 35,000 | **Active** | 4 months ago | |
| Lucy Njeri | S1 | 45,000 | **Active** | 5 months ago | |

### 2.5 Invoices & Payments Pattern

For each active lease, the seeder creates:
- **Past month(s) invoices** → Status: **Paid** (each has a corresponding M-Pesa payment)
- **Current month invoice** → Status: **Pending** (no payment yet — use this for testing payment recording)

### 2.6 Maintenance Requests

| # | Title | Unit | Tenant | Priority | Status | Assigned To |
|---|-------|------|--------|----------|--------|-------------|
| 1 | Leaking Kitchen Faucet | A1 | Grace Muthoni | Medium | Pending | — |
| 2 | Broken Window Lock | A2 | David Kiprop | High | In Progress | Kamau Repairs Ltd |
| 3 | Air Conditioning Not Working | B1 | Sarah Akinyi | Urgent | Pending | — |

### 2.7 Landlord → Property Ownership (Critical for Data Isolation)

| Landlord | Properties They Own |
|----------|-------------------|
| Mary Wanjiku | Sunrise Apartments + Garden Villas (7 units total) |
| Peter Ochieng | Business Hub Tower (3 units total) |

---

# FLOW 1 — System Access & Identity

> *"Who are you, and can you enter the system?"*

Nothing else matters if users cannot enter the system.

## 3. FLOW 1 — Authentication & Registration

### 3.1 Login

**URL:** `http://Rentify.test/login`

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AUTH-01 | Login page renders | 1. Open `http://Rentify.test/login` in your browser | Login form appears with: Email field, Password field, "Remember me" checkbox, "Log in" button, "Forgot your password?" link, link to registration page. Rentify branding is visible. | |
| AUTH-02 | Agent login success | 1. Enter email: `agent@rentify.co.ke` 2. Enter password: `password` 3. Click "Log in" | Page redirects to `/agent/dashboard`. The agent dashboard loads with stats cards and "Welcome back, John Kamau" greeting text. Sidebar shows: Dashboard, Properties, Landlords, Tenants, Leases, Invoices, Payments, Maintenance, Reports, Notifications. | |
| AUTH-03 | Landlord login success | 1. Enter email: `landlord@rentify.co.ke` 2. Enter password: `password` 3. Click "Log in" | Page redirects to `/landlord/dashboard`. The landlord dashboard loads with "Landlord Dashboard" heading and "Overview of your properties and income" subtitle. Sidebar shows landlord menu items. | |
| AUTH-04 | Tenant login success | 1. Enter email: `tenant@rentify.co.ke` 2. Enter password: `password` 3. Click "Log in" | Page redirects to `/tenant/dashboard`. The tenant dashboard loads with "Tenant Dashboard" heading and "Welcome back, Grace Muthoni" greeting. Stats cards show Monthly Rent, Next Due, Total Paid, Open Requests. | |
| AUTH-05 | Wrong password rejected | 1. Enter email: `agent@rentify.co.ke` 2. Enter password: `wrongpassword` 3. Click "Log in" | Error message: "These credentials do not match our records." Stays on login page. Email field retains entered value. | |
| AUTH-06 | Non-existent email rejected | 1. Enter email: `nobody@test.com` 2. Enter password: `password` 3. Click "Log in" | Same error. No information leakage about whether the email exists. | |
| AUTH-07 | Empty fields prevented | 1. Leave both fields blank 2. Click "Log in" | Browser HTML5 validation prevents submission. | |
| AUTH-08 | Empty password only | 1. Enter a valid email 2. Leave password blank 3. Click "Log in" | Browser prevents submission due to required field. | |
| AUTH-09 | Logout works | 1. Log in as any user 2. Click user dropdown (top-right) 3. Click "Log Out" | Session ends. Redirected to `/login`. Protected URLs redirect to login. | |
| AUTH-10 | Remember me | 1. Check "Remember me" 2. Log in 3. Close browser completely 4. Reopen and visit dashboard | You remain logged in (session persists via remember token cookie). | |

### 3.2 Registration

**URL:** `http://Rentify.test/register`

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AUTH-11 | Registration page renders | 1. Open `http://Rentify.test/register` | Form: Name, Email, Phone Number (placeholder "+254712345678"), Register As (dropdown: Agent/Landlord/Tenant), Password, Confirm Password. "Already registered?" link at bottom. | |
| AUTH-12 | Register as Agent | 1. Name: "Test Agent" 2. Email: `test.agent@test.com` 3. Phone: `0712000000` 4. Role: "Agent" 5. Password: `password` 6. Confirm: `password` 7. Click "Register" | Account created. Auto-logged in. Redirected to `/agent/dashboard`. Dashboard shows 0 properties (empty state). | |
| AUTH-13 | Register as Landlord | 1. Name: "Test Landlord" 2. Email: `test.landlord@test.com` 3. Phone: `0712000001` 4. Role: "Landlord" 5. Password: `password` 6. Confirm: `password` 7. Click "Register" | Account + landlord record created. Redirected to `/landlord/dashboard`. Shows 0 properties. | |
| AUTH-14 | Register as Tenant | 1. Name: "Test Tenant" 2. Email: `test.tenant@test.com` 3. Phone: `0712000002` 4. Role: "Tenant" 5. Password: `password` 6. Confirm: `password` 7. Click "Register" | Account + tenant record created. Redirected to `/tenant/dashboard`. Shows "No active lease found" empty state. | |
| AUTH-15 | Duplicate email rejected | 1. Try to register with `agent@rentify.co.ke` (exists) | Validation error: "The email has already been taken." Form retains other values. | |
| AUTH-16 | Password mismatch | 1. Password: `password` 2. Confirm: `different` 3. Submit | Validation error: "The password confirmation does not match." | |
| AUTH-17 | Short password | 1. Password: `123` 2. Confirm: `123` 3. Submit | Validation error: minimum 8 characters. | |
| AUTH-18 | Missing role | 1. Leave "Register As" as "Select role..." 2. Submit | Validation error or browser prevents submission. | |
| AUTH-19 | Newly created user can login | 1. Complete AUTH-12 2. Log out 3. Log in with `test.agent@test.com` / `password` | Login succeeds. Redirected to agent dashboard. | |

---

# FLOW 2 — System Foundation Setup (Agent-Only)

> *"Set up the physical world before people live in it."*

Landlord → Property → Unit mirrors real estate reality. You cannot create a property without a landlord, and you cannot create a unit without a property.

**Login as:** `agent@rentify.co.ke` / `password` for all Flow 2 tests.

## 4. FLOW 2a — Landlord Management

**URL:** `/agent/landlords`

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AL-01 | Landlords list | 1. Click "Landlords" in sidebar | Table shows 2 landlords: Mary Wanjiku (`landlord@rentify.co.ke`), Peter Ochieng (`landlord2@rentify.co.ke`). Each row: name, email, property count. | |
| AL-02 | View landlord profile | 1. Click "Mary Wanjiku" | Shows: Name, Email, Phone, National ID (12345678), Payment Details (KCB Bank, Account: 1234567890). Lists properties she owns: Sunrise Apartments, Garden Villas. | |
| AL-03 | Add landlord form | 1. Click "Add Landlord" | Form: Name, Email, Password, Phone, National ID, Payment Details (bank info / M-Pesa number). | |
| AL-04 | Create a new landlord | 1. Name: "Jane Wambui" 2. Email: `jane@test.com` 3. Password: `password` 4. Phone: `0712999999` 5. National ID: "99887766" 6. Payment Details: Bank = "KCB", Account = "5555555555" 7. Submit | Landlord + user account created. Appears in list. Can be selected when creating properties. | |
| AL-05 | New landlord can login | 1. Log out 2. Log in with `jane@test.com` / `password` | Login succeeds. Redirected to `/landlord/dashboard`. Shows 0 properties. | |
| AL-06 | Available in property form | 1. Log back in as agent 2. Go to Add Property → check Landlord dropdown | "Jane Wambui" appears as an option alongside Mary and Peter. | |
| AL-07 | Edit landlord | 1. Click Edit on Peter Ochieng 2. Change National ID to "11112222" 3. Save | National ID updates. Visible on profile page. | |
| AL-08 | Delete landlord | 1. Delete "Jane Wambui" (created in AL-04) 2. Confirm | Removed from list. User account deleted. Cannot log in anymore. | |

---

## 5. FLOW 2b — Property Management

**URL:** `/agent/properties`

### 5.1 Properties List

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AP-01 | Properties list loads | 1. Click "Properties" in sidebar | Table: 3 properties. Columns: Name, County/Address, Type badge, Landlord, Units count. "Add Property" button top-right. | |
| AP-02 | Sunrise Apartments data | 1. Find row | Name: Sunrise Apartments, County: Nairobi, Type: "Apartment" badge, Landlord: Mary Wanjiku, Units: 4. | |
| AP-03 | Garden Villas data | 1. Find row | Name: Garden Villas, County: Nairobi, Type: "House" badge, Landlord: Mary Wanjiku, Units: 3. | |
| AP-04 | Business Hub Tower data | 1. Find row | Name: Business Hub Tower, County: Mombasa, Type: "Commercial" badge, Landlord: Peter Ochieng, Units: 3. | |
| AP-05 | Type badges distinct | 1. Check Type column | "Apartment", "House", "Commercial" — each has a distinct colored badge. | |

### 5.2 Create Property

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AP-06 | Create form loads | 1. Click "Add Property" | Form: Name, Address, County (dropdown), Type (dropdown), Landlord (dropdown), Description (textarea), Photos (file upload). Submit + Cancel buttons. | |
| AP-07 | County dropdown | 1. Click County dropdown | All 47 Kenya counties in a scrollable list: Mombasa, Kwale, Kilifi, ... Nairobi. | |
| AP-08 | Type dropdown | 1. Click Type dropdown | Options: Apartment, House, Commercial, Land. | |
| AP-09 | Landlord dropdown | 1. Click Landlord dropdown | Shows: Mary Wanjiku, Peter Ochieng (+ any created during testing). | |
| AP-10 | Submit valid property | 1. Name: "Riverside Heights" 2. Address: "56 Riverside Drive" 3. County: Nairobi 4. Type: Apartment 5. Landlord: Mary Wanjiku 6. Description: "Modern apartments with pool" 7. Submit | Success flash. Redirected to list. "Riverside Heights" appears. Count = 4. | |
| AP-11 | Missing required fields | 1. Leave Name blank 2. Submit | Validation error: "The name field is required." Old input preserved. | |
| AP-12 | Missing landlord | 1. Fill all except Landlord 2. Submit | Validation error for landlord_id. | |
| AP-13 | Submit with photos | 1. Fill required fields 2. Upload 2-3 JPG/PNG (each <2MB) 3. Submit | Created with photos. Gallery visible on detail page. | |

### 5.3 View Property Detail

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AP-14 | View detail page | 1. Click "Sunrise Apartments" | Shows: Name, Address ("123 Kenyatta Avenue"), County ("Nairobi"), Type badge, Landlord ("Mary Wanjiku"), Description. | |
| AP-15 | Property stats | 1. Check stats area | Mini stats: Total Units (4), Occupied (3), Vacant (1). | |
| AP-16 | Units table | 1. Scroll to units section | A1 (1BR, KSh 15,000, Occupied, Grace Muthoni), A2 (2BR, KSh 18,000, Occupied, David Kiprop), B1 (2BR, KSh 22,000, Occupied, Sarah Akinyi), B2 (2BR, KSh 22,000, Vacant). | |
| AP-17 | Photo gallery | 1. View a property with uploaded photos | Photos in a responsive grid. Images load from `/storage/...`. | |

### 5.4 Edit & Delete Property

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AP-18 | Edit form pre-populated | 1. Click "Edit" on Sunrise | Fields pre-filled: Name, Address, County (selected), Type (selected), Landlord (selected), Description. | |
| AP-19 | Update property name | 1. Change Name to "Sunrise Apartments Premium" 2. Save | Success message. New name shows in list and detail page. | |
| AP-20 | Update county | 1. Change county from Nairobi to Mombasa 2. Save | County updates. Displays correctly. | |
| AP-21 | Delete empty property | 1. Create a test property with NO units 2. Delete it 3. Confirm | Removed from list. Old URL gives 404. | |
| AP-22 | Delete property with units | 1. Attempt to delete Sunrise Apartments | Either cascades or shows error (foreign key constraint). No server crash. Document observed behavior. | |

---

## 6. FLOW 2c — Unit Management

**URL:** `/agent/properties/{property}/units`

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AU-01 | Units list for Sunrise | 1. Go to Sunrise Apartments → manage units | Table: A1, A2, B1, B2. Columns: Unit Number, Size, Rent (KSh), Deposit, Status badge. | |
| AU-02 | Status badges | 1. Check status column | A1, A2, B1 = green "Occupied". B2 = "Vacant" (gray/yellow). | |
| AU-03 | Rent formatting | 1. Check amounts | "KSh 15,000.00", "KSh 18,000.00", "KSh 22,000.00" — commas, decimals. | |
| AU-04 | Add unit form | 1. Click "Add Unit" | Form: Unit Number, Size, Rent Amount, Deposit Amount, Status dropdown. | |
| AU-05 | Create a unit | 1. Unit: "C1" 2. Size: "Studio" 3. Rent: 12000 4. Deposit: 12000 5. Status: Vacant 6. Submit | Created. Appears in list. Property shows Total Units: 5, Vacant: 2. | |
| AU-06 | Edit unit | 1. Edit B2 → change rent to 25000 2. Save | Shows "KSh 25,000.00" in table. | |
| AU-07 | Delete unit | 1. Delete "C1" 2. Confirm | Removed. Count back to 4. | |
| AU-08 | Garden Villas units | 1. Navigate to Garden Villas → units | V1 (Occupied, 3BR, KSh 35,000), V2 (Vacant, 3BR, KSh 35,000), V3 (Vacant, 4BR, KSh 40,000). | |
| AU-09 | Business Hub units | 1. Navigate to Business Hub → units | S1 (Occupied, Office Suite, KSh 45,000), S2 (Vacant, KSh 50,000), S3 (Vacant, KSh 55,000). | |

---

# FLOW 3 — People Onboarding

> *"Now bring people into the system."*

Tenants cannot exist meaningfully without properties and units already in place.

## 7. FLOW 3 — Tenant Management

**URL:** `/agent/tenants`

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AT-01 | Tenants list | 1. Click "Tenants" in sidebar | Table: Grace Muthoni, David Kiprop, Sarah Akinyi, James Mwangi, Lucy Njeri. Each: name, email, phone. | |
| AT-02 | View tenant profile | 1. Click "Grace Muthoni" | Name, Email (`tenant@rentify.co.ke`), Phone (0745678901), Emergency Contact (0700111222). ID document link if uploaded. | |
| AT-03 | Add tenant form | 1. Click "Add Tenant" | Form: Name, Email, Password, Phone, Emergency Contact, ID Document (file upload — PDF/JPG/PNG, max 5MB). | |
| AT-04 | Create a tenant | 1. Name: "Alice Njoki" 2. Email: `alice@test.com` 3. Password: `password` 4. Phone: `0700111333` 5. Emergency Contact: "John Doe, 0700444555" 6. Upload JPG as ID 7. Submit | Created. User account with Tenant role. Appears in list. Available for lease creation. | |
| AT-05 | ID document (JPG) | 1. Upload JPG <5MB during creation | Accepted. Download/view link on profile. | |
| AT-06 | ID document (PDF) | 1. Upload PDF as ID | Accepted. Opens/downloads correctly. | |
| AT-07 | Invalid file type | 1. Upload `.exe` or `.txt` as ID | Validation error: only PDF/JPG/JPEG/PNG allowed. | |
| AT-08 | Large file rejected | 1. Upload file >5MB | Validation error: max size exceeded. | |
| AT-09 | Edit tenant | 1. Edit Grace → change phone to "0700999888" 2. Save | Phone updates on profile. | |
| AT-10 | New tenant can login | 1. Log out 2. Login as `alice@test.com` / `password` | Redirected to `/tenant/dashboard`. Shows "No active lease found". | |

---

# FLOW 4 — Lease Lifecycle (Core Business Logic)

> *"Connect people to space, legally."*

This is the **heart of Rentify**. Lease creation → Tenant review → E-signature → Activation → Visibility across all roles.

## 8. FLOW 4a — Lease Creation (Agent)

**Login as:** `agent@rentify.co.ke` / `password`
**URL:** `/agent/leases`

### 8.1 Existing Leases

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| ALS-01 | Leases list loads | 1. Click "Leases" in sidebar | 6 leases. Columns: Tenant, Property/Unit, Start, End, Rent (KSh), Status. 5 "Active" (green), 1 "Pending" (yellow — Grace's V2). | |
| ALS-02 | Active lease row | 1. Check Grace's A1 row | Grace Muthoni, Sunrise Apartments / A1, KSh 15,000.00, Active (green). ~6 months ago. | |
| ALS-03 | Pending lease row | 1. Check Grace's V2 row | Grace Muthoni, Garden Villas / V2, KSh 35,000.00, Pending (yellow). Current month. | |

### 8.2 Create New Lease

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| ALS-04 | Create form loads | 1. Click "New Lease" | Form: Tenant dropdown (all tenants), Unit dropdown (**vacant units only**), Start Date, End Date, Rent, Deposit, Terms (textarea). | |
| ALS-05 | Only vacant units shown | 1. Open Unit dropdown | Shows: B2 (Sunrise), V2/V3 (Garden Villas), S2/S3 (Business Hub). Occupied units (A1, A2, B1, V1, S1) must NOT appear. | |
| ALS-06 | Create a lease | 1. Tenant: David Kiprop 2. Unit: S2 (Business Hub) 3. Start: today 4. End: +1 year 5. Rent: 50000 6. Deposit: 50000 7. Terms: "Standard 12-month lease." 8. Submit | Created with **Pending** status (NOT Active). Success message. | |
| ALS-07 | New lease is Pending | 1. Find it in the list | Status: "Pending" (yellow). NOT Active. | |
| ALS-08 | Unit remains Vacant | 1. Check Properties → Business Hub → S2 | Still **Vacant**. Only changes to Occupied after tenant signs. | |
| ALS-09 | Email notification | 1. Check `storage/logs/laravel.log` | Email: subject "Rentify - New Lease Agreement". Body: David Kiprop, Business Hub Tower, S2, KSh 50,000. | |
| ALS-10 | Missing tenant validation | 1. Submit without selecting tenant | Validation error. | |
| ALS-11 | End before start validation | 1. Start = tomorrow, End = yesterday 2. Submit | Validation error: end date must be after start date. | |

### 8.3 View, Edit, Delete

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| ALS-12 | View active lease | 1. Click Grace's A1 lease | Detail: tenant, property, unit, dates, KSh 15,000, deposit, terms, Active badge. Signature section if signed. | |
| ALS-13 | Signature on signed lease | 1. Check Grace's A1 | "Signed on DD/MM/YYYY" badge (seeder sets `signed_at`). Signature image may not display (no `signature_url` in seeded data). | |
| ALS-14 | View pending lease | 1. Click Grace's V2 lease | Status: Pending. No signature section. Terms visible. | |
| ALS-15 | Edit lease | 1. Edit any lease → change Terms 2. Save | Terms update. Visible on detail page. | |
| ALS-16 | Delete lease | 1. Delete the S2 lease from ALS-06 2. Check S2 | Removed. S2 remains/reverts to Vacant. | |

---

## 9. FLOW 4b — E-Signature & Lease Activation (Tenant)

**This is the most complex feature. Perform with a freshly seeded database.**

### 9.1 Pre-Signing Verification

**Login as:** `tenant@rentify.co.ke` / `password` (Grace Muthoni)

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| ES-01 | Pending lease exists | 1. Login as Grace 2. Go to "My Lease" | Two cards: (1) Active lease A1 Sunrise (green), (2) Pending lease V2 Garden Villas (amber: "Lease Awaiting Your Signature"). | |
| ES-02 | Pending lease details | 1. Review amber card | Property: Garden Villas, Unit: V2, Rent: KSh 35,000.00, Deposit: KSh 35,000.00, Start: 1st current month, End: +1 year. Status: "Pending". | |
| ES-03 | Terms scroll | 1. Check "Terms & Conditions" box | Scrollable box (gray bg, max ~192px). Text: "Standard 12-month lease..." Scrollbar if text exceeds. | |
| ES-04 | Sign Now button | 1. Look below terms | Indigo "Sign Now" button with pen icon. No signing area yet. | |
| ES-05 | Agent confirms V2 Vacant | 1. In separate browser, login as agent 2. Properties → Garden Villas → Units | V2 = **Vacant**. | |

### 9.2 Signing Process — Step by Step

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| ES-06 | Click Sign Now | 1. Click "Sign Now" on V2 lease | Button disappears. Signing area expands: (1) "Draw Your Signature" label, (2) Canvas 400x200 with dashed border, (3) "Clear" button, (4) "Sign above using your mouse or finger" text, (5) Agreement checkbox (unchecked), (6) "Sign & Accept Lease" button (disabled/gray), (7) "Cancel" button. | |
| ES-07 | Draw with mouse | 1. Click-and-drag on canvas | Dark ink (#1e293b) follows cursor. Border turns indigo. Smooth lines. | |
| ES-08 | Signature captured text | 1. After drawing | "Sign above..." changes to green **"Signature captured"**. | |
| ES-09 | Draw with touch | 1. On mobile, touch and drag | Ink follows finger. Page does NOT scroll during drawing (touch-none). | |
| ES-10 | Clear resets canvas | 1. Click "Clear" | Canvas blank. "Signature captured" disappears. Reverts to "Sign above..." text. | |
| ES-11 | Redraw after clear | 1. Clear → draw again | Works. "Signature captured" reappears. | |
| ES-12 | Disabled — no signature, no checkbox | 1. Don't draw, don't check | Button disabled (50% opacity, cursor not-allowed). | |
| ES-13 | Disabled — signature only | 1. Draw but don't check box | Button stays disabled. | |
| ES-14 | Disabled — checkbox only | 1. Check box but don't draw | Button stays disabled. | |
| ES-15 | Enabled — both met | 1. Draw + check "I agree" | Button turns **green and clickable**. | |
| ES-16 | Agreement text | 1. Read checkbox label | "I have read and agree to the terms and conditions of this lease agreement. I understand that by signing, I am entering into a binding rental agreement for the property and unit described above." | |
| ES-17 | Submit signature | 1. Click "Sign & Accept Lease" | Text changes to **"Signing..."** with spinner. Form submits. | |

### 9.3 Post-Signing Verification

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| ES-18 | Success redirect | 1. After submission | Redirected to "My Lease". Green banner: **"Lease signed successfully! Your tenancy is now active."** | |
| ES-19 | Lease now Active | 1. Check V2 card | **Green header** (was amber). "Active Lease" title. Status: **Active** (green). No "Sign Now" button. | |
| ES-20 | Signed date badge | 1. Check green header | Badge: **"Signed on DD/MM/YYYY"** with checkmark. | |
| ES-21 | Signature image | 1. Scroll past details | "Your Signature" label. PNG image of drawn signature in bordered container. Loads from `/storage/signatures/...`. | |
| ES-22 | Amber card gone | 1. Check entire page | No "Lease Awaiting Your Signature" card. V2 is in Active section. | |
| ES-23 | Two active leases | 1. Count active cards | Two: A1 Sunrise + V2 Garden Villas. | |
| ES-24 | Agent sees Occupied | 1. Login as agent → Garden Villas → Units → V2 | V2 changed from Vacant to **Occupied** (green). Automatic on signing. | |
| ES-25 | Agent sees signature | 1. Agent → Leases → Grace's V2 | Status: Active. "E-Signature" section with "Signed on DD/MM/YYYY at HH:MM" + signature image. | |
| ES-26 | File on disk | 1. Check `storage/app/public/signatures/` | PNG file: `{lease_id}_{timestamp}.png`. Valid image. | |

### 9.4 Cancel & Edge Cases

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| ES-27 | Cancel signing | 1. Click "Sign Now" 2. Draw 3. Click "Cancel" | Area collapses. Canvas cleared. "Sign Now" reappears. No data submitted. | |
| ES-28 | Reopen after cancel | 1. Click "Sign Now" again | Fresh blank canvas. No residual drawing. All state reset. | |
| ES-29 | Cannot sign active lease | 1. POST to sign endpoint for A1 (already active) | Error: "This lease cannot be signed." Status check blocks. | |
| ES-30 | Cannot sign other's lease | 1. Login as David → try sign URL for Grace's lease | **403 Forbidden**. tenant_id check blocks. | |

---

## 10. FLOW 4c — Lease Visibility Across Roles

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| LV-01 | Agent sees all leases | 1. Agent → Leases | All 6 leases visible (5 active + 1 pending/now active after ES). | |
| LV-02 | Landlord sees own leases | 1. Login as Mary → Leases | Only leases for Sunrise + Garden Villas. No Business Hub leases. | |
| LV-03 | Landlord lease detail | 1. Click any lease | Full details, signature info if signed. No Edit/Delete buttons. Read-only. | |
| LV-04 | Tenant sees own leases | 1. Login as Grace → My Lease | Only Grace's leases (A1 + V2). No other tenant's leases. | |
| LV-05 | Other tenant's view | 1. Login as David → My Lease | Only David's A2 lease. No Grace/Sarah/James/Lucy data. | |

---

# FLOW 5 — Billing & Money Flow

> *"Once a lease exists, money must move."*

Invoices and payments are meaningless apart. This flow tests them as a connected cycle.

## 11. FLOW 5a — Invoice Management (Agent)

**Login as:** `agent@rentify.co.ke` / `password`
**URL:** `/agent/invoices`

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AI-01 | Invoices list loads | 1. Click "Invoices" in sidebar | Table of all invoices. Columns: Description/Tenant, Lease, Amount (KSh), Due Date, Status badge. Mix: "Paid" (green), "Pending" (yellow), possibly "Overdue" (red). | |
| AI-02 | Status badge colors | 1. Review badges | Paid = green, Pending = yellow, Overdue = red, Partially Paid = orange, Cancelled = gray. | |
| AI-03 | Create form | 1. Click "Create Invoice" | Form: Lease dropdown (active leases with tenant + unit info), Amount, Due Date, Description. | |
| AI-04 | Lease dropdown correct | 1. Open dropdown | Each option: tenant name, property, unit. Only active leases. | |
| AI-05 | Create invoice | 1. Lease: Grace - A1 2. Amount: 15000 3. Due Date: future 4. Description: "Rent for March 2026" 5. Submit | Created. **Pending** status. Success message. In list. | |
| AI-06 | Email notification | 1. Check `storage/logs/laravel.log` | Subject: "Rentify - Invoice for [Month Year]". Body: tenant, property, unit, amount, due date. | |
| AI-07 | View invoice detail | 1. Click any invoice | Lease info, Amount, Due Date, Description, Status. Payment history section (empty if unpaid). | |
| AI-08 | Paid invoice shows payment | 1. Click a "Paid" invoice | Payment section: amount, method (M-Pesa), reference, date. Balance: KSh 0.00. | |

---

## 12. FLOW 5b — Payment Recording (Agent)

**URL:** `/agent/payments`

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| APY-01 | Payments list loads | 1. Click "Payments" in sidebar | Table: Tenant, Invoice, Amount (KSh), Method badge, Reference/Receipt, Date. | |
| APY-02 | Method badges | 1. Check method column | All seeded = green "M-Pesa" badge. | |
| APY-03 | Record form | 1. Click "Record Payment" | Form: Invoice dropdown (**unpaid only** — Pending/Overdue), Amount, Method (M-Pesa/Bank Transfer/Cash/Cheque), Reference, M-Pesa Receipt. | |
| APY-04 | Only unpaid invoices | 1. Open Invoice dropdown | Only Pending/Overdue. No Paid invoices. | |
| APY-05 | Record full payment | 1. Select Grace's pending invoice (KSh 15,000) 2. Amount: 15000 3. Method: M-Pesa 4. Reference: "PAY-TEST001" 5. Receipt: "SG4K7R2X1Y" 6. Submit | Recorded. Invoice → **Paid**. Payment in list. | |
| APY-06 | Invoice status updated | 1. Go to Invoices → find that invoice | Badge: **"Paid"** (green). Was "Pending". | |
| APY-07 | Partial payment | 1. Record KSh 10,000 against KSh 18,000 invoice | Recorded. Invoice → **"Partially Paid"** (orange). Balance: KSh 8,000. | |
| APY-08 | Email notification | 1. Check log | Subject: "Rentify - Payment Confirmed". Body: tenant, amount, method, reference, property, unit. | |
| APY-09 | View payment detail | 1. Click a payment | Amount, Method badge, Reference, Receipt, Date/Time, linked Invoice details. | |
| APY-10 | Method dropdown | 1. Open dropdown | Exactly 4: M-Pesa, Bank Transfer, Cash, Cheque. | |

---

## 13. FLOW 5c — Financial Visibility Across Roles

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| FV-01 | Tenant sees own invoices | 1. Login as Grace → Invoices | Only Grace's. Mix of Paid + Pending. No other tenant's invoices. | |
| FV-02 | Tenant invoice detail (unpaid) | 1. Click Pending invoice | Amount, Due Date, Description. Empty payments. Balance = full amount. | |
| FV-03 | Tenant invoice detail (paid) | 1. Click Paid invoice | Payment record shown. Balance = KSh 0. | |
| FV-04 | Tenant sees own payments | 1. Grace → Payments | Only Grace's payment history. | |
| FV-05 | Landlord financials | 1. Login as Mary → Financials | Total Income + Pending from Mary's properties only. No Business Hub data. | |
| FV-06 | Landlord statement | 1. Click "View Statement" | Breakdown by property (Sunrise + Garden Villas only). | |
| FV-07 | Agent sees all invoices | 1. Agent → Invoices | All invoices across all properties. | |
| FV-08 | Agent sees all payments | 1. Agent → Payments | All payments across all properties. | |

---

# FLOW 6 — Maintenance & Support

> *"Things break after people move in."*

## 14. FLOW 6a — Maintenance Requests (Tenant)

**Login as:** `tenant@rentify.co.ke` / `password` (Grace Muthoni)

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| MR-01 | Maintenance list | 1. Click "Maintenance" in sidebar | Grace's requests. 1 seeded: "Leaking Kitchen Faucet" (Medium, Pending). | |
| MR-02 | New Request form | 1. Click "New Request" | Form: Title (required), Description (required), Priority (Low/Medium/High/Urgent), Photos (multiple file upload, `enctype="multipart/form-data"`). | |
| MR-03 | Submit request | 1. Title: "Bathroom drain blocked" 2. Description: "Completely blocked, water not draining, floor wet." 3. Priority: "High" 4. Upload 1-2 photos 5. Submit | Created. Success message. Appears: "Bathroom drain blocked", High (orange), Pending (yellow). | |
| MR-04 | View detail | 1. Click "Bathroom drain blocked" | Title, full description, Priority (High/orange), Status (Pending/yellow), Property (Sunrise), Unit (A1). Photo gallery. No resolution notes. | |
| MR-05 | Photos display | 1. Check gallery | Uploaded images load from `/storage/...`. No broken icons. | |
| MR-06 | No lease → blocked | 1. Login as tenant with NO lease → try to create request | Error: "No active lease found". Request NOT created. | |

---

## 15. FLOW 6b — Maintenance Handling (Agent)

**Login as:** `agent@rentify.co.ke` / `password`
**URL:** `/agent/maintenance`

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AM-01 | List loads | 1. Click "Maintenance" in sidebar | 3 seeded requests + any created during testing. Columns: Title, Property/Unit, Tenant, Priority badge, Status badge. | |
| AM-02 | Seeded #1 | 1. Find "Leaking Kitchen Faucet" | A1 (Sunrise), Grace Muthoni, **Medium** (yellow), **Pending** (yellow). | |
| AM-03 | Seeded #2 | 1. Find "Broken Window Lock" | A2 (Sunrise), David Kiprop, **High** (orange), **In Progress** (blue). Assigned: Kamau Repairs Ltd. | |
| AM-04 | Seeded #3 | 1. Find "Air Conditioning Not Working" | B1 (Sunrise), Sarah Akinyi, **Urgent** (red), **Pending** (yellow). | |
| AM-05 | Priority colors | 1. Compare | Low = gray/blue, Medium = yellow, High = orange, Urgent = red. | |
| AM-06 | Status colors | 1. Compare | Pending = yellow, In Progress = blue, Completed = green, Cancelled = gray. | |
| AM-07 | View detail | 1. Click "Leaking Kitchen Faucet" | Full description, Priority, Status, Tenant, Property, Unit, Created date. Photo gallery (empty for seeded). Resolution notes (empty for Pending). | |
| AM-08 | Update to In Progress | 1. Edit "Leaking Kitchen Faucet" 2. Status → "In Progress" 3. Assigned To: "ABC Plumbing" 4. Save | Status: **In Progress** (blue). Assigned shows "ABC Plumbing". | |
| AM-09 | Complete with notes | 1. Edit again 2. Status → "Completed" 3. Resolution Notes: "Fixed leaking pipe. Replaced washer." 4. Save | Status: **Completed** (green). Notes visible on detail. | |
| AM-10 | Email on update | 1. Check `storage/logs/laravel.log` | Subject: "Rentify - Maintenance Request Update". Body: Grace, title, new status, property, unit. If completed: resolution notes. | |

---

# FLOW 7 — Communication & Notifications

> *"Keep everyone informed."*

## 16. FLOW 7a — In-App Notifications

**Login as:** `agent@rentify.co.ke` / `password`
**URL:** `/agent/notifications`

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AN-01 | Agent notifications list | 1. Click "Notifications" in sidebar | 3 seeded notifications. Each: Subject, Type, Date. Unread have visual indicator (blue dot/bold). | |
| AN-02 | Read vs unread | 1. Check list | "Monthly Collection Summary" = read. "New Maintenance Request" + "Urgent Maintenance Request" = unread. | |
| AN-03 | View detail | 1. Click a notification | Subject heading, full message, Type, Date. Marked as read after viewing. | |
| AN-04 | Send form | 1. Click "Send Notification" | **Recipient** dropdown (grouped: Landlords / Tenants with names + emails), **Type** (General / Payment Reminder / Maintenance Update / Lease Expiry Notice), **Subject**, **Message** textarea. | |
| AN-05 | Send to tenant | 1. Recipient: Grace Muthoni 2. Type: Payment Reminder 3. Subject: "February Rent Due" 4. Message: "Dear Grace, rent of KSh 15,000 is due on the 5th." 5. Submit | Created. In agent's list. | |
| AN-06 | Send to landlord | 1. Recipient: Mary Wanjiku 2. Type: General 3. Subject: "Monthly Report" 4. Message: "Your February report is ready." 5. Submit | Created. Visible when Mary logs in. | |
| AN-07 | Tenant receives it | 1. Logout 2. Login as Grace → Notifications | "February Rent Due" from AN-05 is visible. | |
| AN-08 | Landlord receives it | 1. Login as Mary → Notifications | "Monthly Report" from AN-06 is visible. | |

---

## 17. FLOW 7b — Email Notifications (Logged)

**Setup:** Emails logged to `storage/logs/laravel.log` (`MAIL_MAILER=log`). View with:
```bash
tail -200 storage/logs/laravel.log
# or open the file in any text editor
```

### 17.1 Lease Created

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| EM-01 | Trigger | 1. Agent creates a lease (ALS-06) | Email in log. | |
| EM-02 | Subject | 1. Search log | **"Rentify - New Lease Agreement"** | |
| EM-03 | Content | 1. Read body | Tenant name, Property, Unit number (`unit_number` not `name`), Rent (KSh), Deposit, Start/End dates. "Review Lease" link. Branded layout. | |

### 17.2 Invoice Created

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| EM-04 | Trigger | 1. Agent creates invoice (AI-05) | Email in log. | |
| EM-05 | Subject | 1. Search log | **"Rentify - Invoice for [Month] [Year]"** | |
| EM-06 | Content | 1. Read body | Tenant, Property, Unit, Amount (KSh), Due date. "View Invoice" link. | |

### 17.3 Payment Received

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| EM-07 | Trigger | 1. Agent records payment (APY-05) | Email in log. | |
| EM-08 | Subject | 1. Search log | **"Rentify - Payment Confirmed"** | |
| EM-09 | Content | 1. Read body | Tenant, Amount, Method (M-Pesa), Reference, Property, Unit, Date. Green styling. "View Payment History" link. | |

### 17.4 Maintenance Updated

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| EM-10 | Trigger | 1. Agent updates maintenance (AM-08) | Email in log. | |
| EM-11 | Subject | 1. Search log | **"Rentify - Maintenance Request Update"** | |
| EM-12 | Content — status change | 1. Read body (In Progress) | Tenant, Title, Status, Property, Unit. "View Request" link. | |
| EM-13 | Content — completed | 1. Read body (Completed) | All above + amber box with resolution notes. | |

---

# FLOW 8 — Dashboards & Oversight

> *"Understand what's happening at a glance."*

## 18. FLOW 8a — Agent Dashboard

**Login as:** `agent@rentify.co.ke` / `password`
**URL:** `/agent/dashboard`

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AD-01 | Page loads | 1. Login as agent | "Dashboard" + "Welcome back, John Kamau". "Add Property" button top-right. Premium styling. | |
| AD-02 | Total Properties card | 1. First card | "Total Properties": **3**. Indigo. Clickable → `/agent/properties`. | |
| AD-03 | Occupied Units card | 1. Second card | "Occupied Units": **5/10**. Green. | |
| AD-04 | Total Collected card | 1. Third card | "Total Collected": KSh amount (sum of payments). Blue. | |
| AD-05 | Pending Invoices card | 1. Fourth card | "Pending Invoices": count (~5). Yellow. Clickable → `/agent/invoices`. | |
| AD-06 | Recent Payments | 1. Scroll to left column | "Recent Payments" with green icon. List: initial avatar, name, date, "+KSh XX,XXX.XX" in green. "View all" → `/agent/payments`. | |
| AD-07 | Overdue Invoices | 1. Right column | "Overdue Invoices" with red icon. If overdue: avatar, name, due date (red), amount (red). If none: "No overdue invoices - all clear!" | |
| AD-08 | Quick Actions | 1. Scroll to bottom | 4 cards: **Add Property** (indigo), **New Lease** (emerald), **Create Invoice** (blue), **Record Payment** (amber). Hover effects. | |
| AD-09 | QA — Add Property | 1. Click card | → `/agent/properties/create`. | |
| AD-10 | QA — New Lease | 1. Click card | → `/agent/leases/create`. | |
| AD-11 | QA — Create Invoice | 1. Click card | → `/agent/invoices/create`. | |
| AD-12 | QA — Record Payment | 1. Click card | → `/agent/payments/create`. | |
| AD-13 | Sidebar navigation | 1. Check sidebar | 10 items: Dashboard (active), Properties, Landlords, Tenants, Leases, Invoices, Payments, Maintenance, Reports, Notifications. | |
| AD-14 | Sidebar links work | 1. Click each sidebar item | Each navigates correctly. Current page highlighted. | |

---

## 19. FLOW 8b — Landlord Dashboard & Portal

**Login as:** `landlord@rentify.co.ke` / `password` (Mary Wanjiku)

### 19.1 Dashboard

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| LD-01 | Dashboard loads | 1. Login as Mary | "Landlord Dashboard" + "Overview of your properties and income". 4 stats + Recent Payments. | |
| LD-02 | Properties card | 1. First card | "Properties": **2**. Indigo. | |
| LD-03 | Occupied Units card | 1. Second card | **4/7** (4 occupied of 7 total across Mary's properties). Green. | |
| LD-04 | Total Income card | 1. Third card | KSh sum of completed payments for Mary's properties only. Blue. | |
| LD-05 | Pending Amount card | 1. Fourth card | KSh sum of unpaid invoices for Mary's properties. Yellow. | |
| LD-06 | Recent Payments | 1. Scroll down | Payments from: Grace, David, Sarah, James. NOT Lucy (Peter's tenant). | |

### 19.2 Properties

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| LD-07 | Properties list | 1. Click "Properties" | Shows: Sunrise Apartments + Garden Villas. NOT Business Hub. | |
| LD-08 | Property detail | 1. Click "Sunrise Apartments" | Property info + units table (4 units, status, tenants). | |
| LD-09 | Read-only | 1. Check for management buttons | NO Add/Edit/Delete buttons. Landlords = read-only. | |

### 19.3 Other Sections

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| LD-10 | Tenants list | 1. Click "Tenants" | Grace, David, Sarah, James. NOT Lucy. | |
| LD-11 | Leases list | 1. Click "Leases" | Leases for Mary's properties. No S1 (Lucy). No Edit/Delete. | |
| LD-12 | Lease detail | 1. Click any lease | Full details + signature if signed. Read-only. | |
| LD-13 | Financials | 1. Click "Financials" | Income + Pending for Mary's properties only. | |
| LD-14 | Statement | 1. Click "View Statement" | Per-property breakdown: Sunrise + Garden Villas. | |
| LD-15 | Notifications | 1. Click "Notifications" | Mary's seeded notifications + any sent during testing. | |

### 19.4 Peter Ochieng (Second Landlord)

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| LD-16 | Peter's dashboard | 1. Login as `landlord2@rentify.co.ke` | Properties = 1, Occupied = 1/3, Income from Lucy only. | |
| LD-17 | Peter's properties | 1. Go to Properties | ONLY Business Hub Tower. | |
| LD-18 | Peter's tenants | 1. Go to Tenants | ONLY Lucy Njeri. | |

---

## 20. FLOW 8c — Tenant Dashboard & Portal

**Login as:** `tenant@rentify.co.ke` / `password` (Grace Muthoni)

### 20.1 Dashboard

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| TD-01 | Dashboard loads | 1. Login as Grace | "Tenant Dashboard" + "Welcome back, Grace Muthoni". 4 stats + lease card + recent payments. | |
| TD-02 | Monthly Rent card | 1. First card | "Monthly Rent": **KSh 15,000.00**. Indigo. | |
| TD-03 | Next Due card | 1. Second card | "Next Due": KSh 15,000.00 (or "None"). Subtitle: due date. Yellow. | |
| TD-04 | Total Paid card | 1. Third card | Sum of Grace's completed payments. Green. | |
| TD-05 | Open Requests card | 1. Fourth card | **1** (Leaking Kitchen Faucet). Red. | |
| TD-06 | Current Lease card | 1. Below stats | Property: Sunrise Apartments, Unit: A1, Lease Period dates. Indigo icon. | |
| TD-07 | Recent Payments | 1. Below lease | Grace's payments. Description, date, amount (green). "View all" → `/tenant/payments`. | |
| TD-08 | Empty dashboard | 1. New tenant (no lease) → Dashboard | "No active lease found" + "Contact your agent for lease assignment." No stats. | |

### 20.2 My Lease

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| TD-09 | Page loads | 1. Click "My Lease" | Active card (A1 green) + Pending card (V2 amber). Title: "My Lease". | |
| TD-10 | Active card | 1. Check green card | "Active Lease". "Signed on DD/MM/YYYY" badge. Property, Unit, Status, Dates, Rent. | |
| TD-11 | Signature display | 1. Check below details | "Your Signature" label + signature image (if `signature_url` set). Fully testable after Flow 4b. | |
| TD-12 | Pending card | 1. Check amber card | "Lease Awaiting Your Signature". V2 Garden Villas details. "Sign Now" button. Terms scroll. | |
| TD-13 | No leases | 1. New tenant → My Lease | Document icon, "No leases", "You do not have any lease agreements yet." | |

### 20.3 Invoices, Payments, Notifications

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| TD-14 | Invoices list | 1. Click "Invoices" | Grace's only. Paid + Pending mix. | |
| TD-15 | Unpaid invoice detail | 1. Click Pending invoice | Amount, Due Date, Description. Empty payments. Balance = full. | |
| TD-16 | Paid invoice detail | 1. Click Paid invoice | M-Pesa payment record. Balance = 0. | |
| TD-17 | Payments list | 1. Click "Payments" | Grace's only. Amount, Method, Reference, Date. | |
| TD-18 | Notifications | 1. Click "Notifications" | Grace's notifications. Seeded + any from testing. | |

---

# FLOW 9 — Reporting & Statements

> *"Summarise performance and financials."*

## 21. FLOW 9a — Agent Reports

**Login as:** `agent@rentify.co.ke` / `password`
**URL:** `/agent/reports`

### 21.1 Reports Dashboard

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AR-01 | Reports index | 1. Click "Reports" in sidebar | 4 stat cards + 3 report links + Landlord Statements list. | |
| AR-02 | Total Units | 1. First card | **10** (4+3+3). | |
| AR-03 | Occupancy Rate | 1. Second card | **50%** — "5 of 10 occupied". | |
| AR-04 | Total Collected | 1. Third card | KSh sum of all completed payments. | |
| AR-05 | Total Arrears | 1. Fourth card | KSh sum of unpaid invoice balances. | |
| AR-06 | Report cards | 1. Check 3 cards below | "Rent Roll" (blue), "Arrears Report" (red), "Occupancy Report" (green). Clickable. | |
| AR-07 | Landlord list | 1. Bottom section | Mary Wanjiku ("M" avatar) + Peter Ochieng ("P" avatar). Clickable. | |

### 21.2 Rent Roll

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AR-08 | Page loads | 1. Click "Rent Roll" | Heading, "Back to Reports" button, 3 stats, property tables. | |
| AR-09 | Summary stats | 1. Check 3 cards | **Total Potential**: KSh 337,000 (sum all unit rents). **Occupied**: KSh 135,000. **Vacant Loss**: KSh 202,000. | |
| AR-10 | Sunrise table | 1. Find section | A1 (15K, Occupied, Grace), A2 (18K, Occupied, David), B1 (22K, Occupied, Sarah), B2 (22K, Vacant, "—"). | |
| AR-11 | Garden Villas table | 1. Find section | V1 (35K, Occupied, James), V2 (35K, Vacant), V3 (40K, Vacant). | |
| AR-12 | Business Hub table | 1. Find section | S1 (45K, Occupied, Lucy), S2 (50K, Vacant), S3 (55K, Vacant). | |
| AR-13 | Back button | 1. Click "Back to Reports" | Returns to reports index. | |

### 21.3 Arrears Report

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AR-14 | Page loads | 1. Click "Arrears Report" | Total arrears card + table of unpaid invoices. | |
| AR-15 | Table columns | 1. Check | Tenant, Property/Unit, Amount (KSh), Paid (green), Balance (red bold), Due Date, Status badge. | |
| AR-16 | Balance math | 1. Verify: Balance = Amount - Paid | Correct for each row. | |
| AR-17 | Only unpaid | 1. Check rows | No Paid invoices. Only Pending/Overdue/Partially Paid. | |
| AR-18 | Empty state | 1. (If all paid) | Green checkmark: "No outstanding arrears — All invoices are paid up." | |

### 21.4 Occupancy Report

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AR-19 | Page loads | 1. Click "Occupancy Report" | 3 stats + property table with progress bars. | |
| AR-20 | Overall stats | 1. Check cards | Occupancy: ~50%, Occupied: 5/10, Vacant: 5. | |
| AR-21 | Sunrise row | 1. Find | 4 units, 3 occupied, 1 vacant, **75%**. Amber bar. | |
| AR-22 | Garden Villas row | 1. Find | 3 units, 1 occupied, 2 vacant, **33%**. Red bar. | |
| AR-23 | Business Hub row | 1. Find | 3 units, 1 occupied, 2 vacant, **33%**. Red bar. | |
| AR-24 | Bar colors | 1. Compare | Green >=80%, Amber 50-79%, Red <50%. | |

---

## 22. FLOW 9b — Landlord Statements

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| AR-25 | Mary's statement | 1. Reports index → click "Mary Wanjiku" | "Landlord Statement" + "Mary Wanjiku (landlord@rentify.co.ke)". | |
| AR-26 | Mary's stats | 1. Check 3 cards | **Total Income** (green), **Pending Amount** (yellow), **Properties**: 2 (indigo). | |
| AR-27 | Properties summary | 1. Check table | Sunrise (4 units, 3 occupied) + Garden Villas (3 units, 1 occupied). Footer totals. NO Business Hub. | |
| AR-28 | Recent payments | 1. Check section | Payments from Grace, David, Sarah, James. NOT Lucy. | |
| AR-29 | Peter's statement | 1. Back → click "Peter Ochieng" | ONLY Business Hub data. Lucy's payments. No Sunrise/Garden Villas. | |
| AR-30 | Isolation confirmed | 1. Compare Mary's and Peter's | Zero overlap. Each sees only their properties. | |

---

# FLOW 10 — Security, Isolation & Trust

> *"Make sure no one sees what they shouldn't."*

## 23. FLOW 10a — Role-Based Access Control

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| SEC-01 | Unauthenticated → agent | 1. Logged out → `/agent/dashboard` | Redirected to `/login`. | |
| SEC-02 | Unauthenticated → tenant | 1. Logged out → `/tenant/dashboard` | Redirected to `/login`. | |
| SEC-03 | Unauthenticated → landlord | 1. Logged out → `/landlord/dashboard` | Redirected to `/login`. | |
| SEC-04 | Tenant → agent route | 1. Grace → `/agent/dashboard` | **403 Forbidden**. | |
| SEC-05 | Tenant → landlord route | 1. Grace → `/landlord/dashboard` | **403 Forbidden**. | |
| SEC-06 | Agent → landlord route | 1. Agent → `/landlord/dashboard` | **403 Forbidden**. | |
| SEC-07 | Agent → tenant route | 1. Agent → `/tenant/dashboard` | **403 Forbidden**. | |
| SEC-08 | Landlord → agent route | 1. Mary → `/agent/dashboard` | **403 Forbidden**. | |
| SEC-09 | Landlord → tenant route | 1. Mary → `/tenant/dashboard` | **403 Forbidden**. | |
| SEC-10 | Maintenance ownership | 1. Grace → note maintenance URL 2. Login as David → that URL | **403 Forbidden**. | |
| SEC-11 | Invoice ownership | 1. Grace → note invoice URL 2. David → that URL | **403 Forbidden** or not found. | |
| SEC-12 | E-signature ownership | 1. David → POST to Grace's sign URL | **403 Forbidden**. | |
| SEC-13 | CSRF protection | 1. POST without `_token` (via Postman/curl) | **419 Page Expired**. | |
| SEC-14 | 404 page | 1. Navigate to `/agent/nonexistent` | Styled 404 page. Not raw error dump. | |

---

## 24. FLOW 10b — Cross-Role Data Isolation

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| ISO-01 | Mary's scope | 1. Mary → all pages | Sees: Sunrise + Garden Villas. NEVER Business Hub. | |
| ISO-02 | Peter's scope | 1. Peter → all pages | Sees: Business Hub ONLY. Never Sunrise/Garden Villas. | |
| ISO-03 | Mary's tenants | 1. Mary → Tenants | Grace, David, Sarah, James. NOT Lucy. | |
| ISO-04 | Peter's tenants | 1. Peter → Tenants | Lucy ONLY. | |
| ISO-05 | Grace's scope | 1. Grace → invoices, payments, maintenance | All data = Grace's. No David/Sarah/James/Lucy. | |
| ISO-06 | David's scope | 1. David → all pages | David's A2 lease, his invoices, his maintenance. Nothing else. | |
| ISO-07 | Lucy's scope | 1. Lucy → all pages | Lucy's S1 lease, her invoices. No other tenants/properties. | |
| ISO-08 | Agent sees all | 1. Agent → all pages | ALL 3 properties, 5 tenants, 6 leases, all invoices, all payments. Full visibility. | |

---

# FLOW 11 — Stability & Real-World Use

> *"Does it survive edge cases and devices?"*

## 25. FLOW 11a — File Uploads & Storage

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| FU-01 | Property photos (JPG) | 1. Agent → Create Property + 2-3 JPGs 2. View detail | Gallery grid. Images load. No broken icons. | |
| FU-02 | Property photos (PNG) | 1. Create with PNGs | Same — displays correctly. | |
| FU-03 | Tenant ID (PDF) | 1. Agent → Create Tenant + PDF 2. View profile | Download/view link works. PDF opens. | |
| FU-04 | Tenant ID (JPG) | 1. Upload JPG as ID | Displays/downloads correctly. | |
| FU-05 | Maintenance photos | 1. Tenant → New Request + 2 photos 2. View detail | Gallery visible to tenant AND agent. | |
| FU-06 | Signature file | 1. Complete e-signature 2. Check `storage/app/public/signatures/` | PNG file `{lease_id}_{timestamp}.png`. Valid image. | |
| FU-07 | Large file rejected | 1. Upload >2MB photo or >5MB ID | Validation error. Not uploaded. | |
| FU-08 | Invalid type rejected | 1. Upload `.exe`/`.txt`/`.zip` | Validation error. Only allowed types accepted. | |
| FU-09 | Storage link | 1. If broken images → run `php artisan storage:link` | Symlink created. Images load after. | |
| FU-10 | Multiple photos | 1. Select 3+ photos at once 2. Submit | All saved and displayed. | |

---

## 26. FLOW 11b — Responsive Design & Mobile

Test with Chrome DevTools (F12 → device toolbar) or real devices.

| ID | Test Case | Viewport | Steps | Expected Result | Pass/Fail |
|----|-----------|----------|-------|-----------------|-----------|
| RD-01 | Desktop sidebar | 1280px+ | Any page | Sidebar fixed left. Content fills right. | |
| RD-02 | Tablet sidebar | 768-1279px | Any page | Sidebar visible (fixed from md:768px). | |
| RD-03 | Mobile hidden | <768px | Any page | Sidebar hidden. Hamburger icon appears. | |
| RD-04 | Mobile toggle | <768px | Click hamburger | Sidebar slides in as overlay. Dismiss by clicking outside/X. | |
| RD-05 | Mobile stats | <768px | Dashboard | Cards stack vertically (1 col). | |
| RD-06 | Tablet stats | 640-1023px | Dashboard | 2 columns (sm:grid-cols-2). | |
| RD-07 | Desktop stats | 1024px+ | Agent dashboard | 4 columns (lg:grid-cols-4). | |
| RD-08 | Mobile tables | <768px | Table pages | Horizontal scroll. Swipe left/right. | |
| RD-09 | Mobile e-signature | <768px | Tenant → My Lease → Sign Now | Canvas usable with finger. No page scroll during draw (touch-none). | |
| RD-10 | Font consistency | All | Multiple pages | Inter font everywhere. | |
| RD-11 | Card styling | All | All cards | `rounded-xl`, `ring-1 ring-gray-900/5`, `shadow-sm`. Consistent. | |

---

## 27. FLOW 11c — Edge Cases & Error Handling

| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| EC-01 | Empty agent dashboard | 1. New agent → dashboard | All 0s. "No recent payments". "No overdue invoices - all clear!". Quick Actions work. No errors. | |
| EC-02 | Empty tenant dashboard | 1. New tenant → dashboard | "No active lease found" + "Contact your agent." No stats. | |
| EC-03 | No lease — My Lease | 1. New tenant → My Lease | Icon + "No leases" + "You do not have any lease agreements yet." | |
| EC-04 | No invoices | 1. New tenant → Invoices | Empty list. No errors. | |
| EC-05 | No payments | 1. New tenant → Payments | Empty list. No errors. | |
| EC-06 | Maintenance without lease | 1. New tenant → submit request | Error: "No active lease found". NOT created. | |
| EC-07 | Empty landlord dashboard | 1. New landlord → dashboard | 0s everywhere. "No recent payments." No errors. | |
| EC-08 | Reports with no data | 1. New agent → Reports | 0 values. Empty states. No crashes. | |
| EC-09 | No vacant units for lease | 1. All units occupied → create lease | Dropdown empty/no options. Form prevents submission. | |
| EC-10 | Long text | 1. Enter 500+ chars in description 2. Submit | Saved fully. Displayed on detail page. | |
| EC-11 | Special characters | 1. Name: `O'Brien's & "Apts" <Test>` 2. View | Displays correctly. No HTML injection. Escaped. | |
| EC-12 | Back button after submit | 1. Submit form 2. Browser back 3. Resubmit | No duplicate. CSRF handles gracefully. | |
| EC-13 | 404 page | 1. `/this-does-not-exist` | Styled 404. Not raw error dump. | |
| EC-14 | Invalid ULID in URL | 1. `/agent/properties/nonexistent-id` | 404. Route model binding fails gracefully. | |

---

# FLOW 12 — Full Business Journey

> *"Prove the system works as a whole."*

## 28. FLOW 12 — Full End-to-End Workflow

**Purpose:** Walk through the complete lifecycle — property creation to payment — crossing all 3 portals. If all 27 steps pass, the entire system is verified.

**Pre-requisite:** `php artisan migrate:fresh --seed`

### Step 1: Agent Creates Property

| # | Action | Expected |
|---|--------|----------|
| 1 | Login as agent (`agent@rentify.co.ke` / `password`) | Dashboard loads. |
| 2 | Add Property: "Ocean View Residences", "12 Bamburi Road", Mombasa, Apartment, Landlord = Peter Ochieng | Created. In list. |
| 3 | Add Unit: "OV1", "2 Bedroom", Rent 28000, Deposit 28000, Vacant | Created. |
| 4 | Add Unit: "OV2", "1 Bedroom", Rent 20000, Deposit 20000, Vacant | Created. Property has 2 units. |

### Step 2: Agent Creates Tenant

| # | Action | Expected |
|---|--------|----------|
| 5 | Add Tenant: "Brian Omondi", `brian@test.com`, `password`, Phone 0711222333 | Created. |

### Step 3: Agent Creates Lease

| # | Action | Expected |
|---|--------|----------|
| 6 | New Lease: Brian → OV1, today → +1 year, 28000, 28000, "Standard lease" | Created. **Pending**. Email logged. |
| 7 | Check OV1 | Still **Vacant**. |

### Step 4: Tenant Signs Lease

| # | Action | Expected |
|---|--------|----------|
| 8 | Logout → Login as Brian (`brian@test.com`) | "No active lease found" on dashboard. |
| 9 | My Lease | Amber card: "Lease Awaiting Your Signature" for OV1. |
| 10 | Sign Now → Draw → Check "I agree" → Submit | "Signing..." → Success: "Lease signed successfully!" |
| 11 | My Lease | Green "Active Lease" for OV1. Signed badge. Signature image. |

### Step 5: Agent Creates Invoice

| # | Action | Expected |
|---|--------|----------|
| 12 | Login as agent → Check OV1 | **Occupied**. |
| 13 | Create Invoice: Brian - OV1, 28000, due 5th next month | Created. Email logged. |

### Step 6: Tenant Views Invoice

| # | Action | Expected |
|---|--------|----------|
| 14 | Login as Brian → Invoices | KSh 28,000 Pending. |
| 15 | Click invoice | Balance = KSh 28,000. No payments yet. |

### Step 7: Agent Records Payment

| # | Action | Expected |
|---|--------|----------|
| 16 | Agent → Record Payment: Brian's invoice, 28000, M-Pesa, "PAY-E2E001" | Recorded. Email logged. |
| 17 | Check invoice | **Paid**. Balance = 0. |

### Step 8: Tenant Confirms

| # | Action | Expected |
|---|--------|----------|
| 18 | Brian → Payments | KSh 28,000, M-Pesa. |
| 19 | Dashboard | "Total Paid" = KSh 28,000. |

### Step 9: Tenant Submits Maintenance

| # | Action | Expected |
|---|--------|----------|
| 20 | Brian → New Request: "Hot water not working", High | Created. Pending + High. |

### Step 10: Agent Handles Maintenance

| # | Action | Expected |
|---|--------|----------|
| 21 | Agent → Edit → "In Progress" | Updated. Email logged. |
| 22 | Edit → "Completed" + "Replaced water heater" | Completed. Email with notes. |

### Step 11: Landlord Verifies

| # | Action | Expected |
|---|--------|----------|
| 23 | Login as Peter (`landlord2@rentify.co.ke`) | Sees "Ocean View Residences". |
| 24 | Properties | Business Hub + Ocean View (both Peter's). |
| 25 | Financials | KSh 28,000 from Brian. |

### Step 12: Agent Sends Notification

| # | Action | Expected |
|---|--------|----------|
| 26 | Agent → Send Notification → Brian, "Welcome!" | Sent. |
| 27 | Brian → Notifications | "Welcome!" visible. |

**ALL 27 STEPS PASS = FULL SYSTEM VERIFIED.**

---

## 29. Known Limitations

**Do NOT report these as bugs:**

| # | Limitation | Status | Notes |
|---|-----------|--------|-------|
| 1 | M-Pesa STK Push | Not Implemented | Requires Safaricom Daraja API |
| 2 | M-Pesa Paybill | Not Implemented | API stub exists, no live connection |
| 3 | SMS notifications | Not Implemented | Needs Africa's Talking / Twilio |
| 4 | Emails go to log only | By Design | `MAIL_MAILER=log` — change to smtp for real delivery |
| 5 | Tenant self-pay online | Not Implemented | Depends on payment gateway |
| 6 | PDF export | Future Feature | |
| 7 | Dashboard charts | Future Feature | Will use Chart.js |
| 8 | Search/filter on tables | Future Feature | |
| 9 | Super-admin panel | Future Feature | Admin enum exists, no portal built |
| 10 | Audit trail | Future Feature | |
| 11 | Auto-invoice scheduler | Requires Setup | Cron: `php artisan schedule:run` every minute |
| 12 | ngrok limits | Infrastructure | Free tier has request caps |

---

## 30. Test Execution Summary

### Results Table

| # | Flow | Section | Test IDs | Total | Pass | Fail | Blocked |
|---|------|---------|----------|-------|------|------|---------|
| 1 | Access | Authentication & Registration | AUTH-01 – AUTH-19 | 19 | | | |
| 2 | Foundation | Landlord Management | AL-01 – AL-08 | 8 | | | |
| 3 | Foundation | Property Management | AP-01 – AP-22 | 22 | | | |
| 4 | Foundation | Unit Management | AU-01 – AU-09 | 9 | | | |
| 5 | Onboarding | Tenant Management | AT-01 – AT-10 | 10 | | | |
| 6 | Lease | Lease Creation (Agent) | ALS-01 – ALS-16 | 16 | | | |
| 7 | Lease | E-Signature (Tenant) | ES-01 – ES-30 | 30 | | | |
| 8 | Lease | Lease Visibility | LV-01 – LV-05 | 5 | | | |
| 9 | Billing | Invoice Management | AI-01 – AI-08 | 8 | | | |
| 10 | Billing | Payment Recording | APY-01 – APY-10 | 10 | | | |
| 11 | Billing | Financial Visibility | FV-01 – FV-08 | 8 | | | |
| 12 | Support | Maintenance (Tenant) | MR-01 – MR-06 | 6 | | | |
| 13 | Support | Maintenance (Agent) | AM-01 – AM-10 | 10 | | | |
| 14 | Comms | In-App Notifications | AN-01 – AN-08 | 8 | | | |
| 15 | Comms | Email Notifications | EM-01 – EM-13 | 13 | | | |
| 16 | Oversight | Agent Dashboard | AD-01 – AD-14 | 14 | | | |
| 17 | Oversight | Landlord Portal | LD-01 – LD-18 | 18 | | | |
| 18 | Oversight | Tenant Portal | TD-01 – TD-18 | 18 | | | |
| 19 | Reporting | Agent Reports | AR-01 – AR-24 | 24 | | | |
| 20 | Reporting | Landlord Statements | AR-25 – AR-30 | 6 | | | |
| 21 | Security | Access Control | SEC-01 – SEC-14 | 14 | | | |
| 22 | Security | Data Isolation | ISO-01 – ISO-08 | 8 | | | |
| 23 | Stability | File Uploads | FU-01 – FU-10 | 10 | | | |
| 24 | Stability | Responsive Design | RD-01 – RD-11 | 11 | | | |
| 25 | Stability | Edge Cases | EC-01 – EC-14 | 14 | | | |
| 26 | E2E | Full Workflow | Steps 1–27 | 27 | | | |
| | | **TOTAL** | | **338** | | | |

### Severity Classification

| Severity | Definition | Example |
|----------|-----------|---------|
| **Critical** | System unusable, data loss, security breach | Cannot login, data visible to wrong user, payments wrong |
| **High** | Major feature broken, no workaround | Cannot create leases, e-signature fails, emails not logged |
| **Medium** | Feature partially broken, workaround exists | Wrong badge color, formatting issue on one page |
| **Low** | Cosmetic, minor UI | Misaligned text, wrong font, extra whitespace |

### Bug Report Template

```
Bug ID:         [e.g., BUG-001]
Test Case ID:   [e.g., ALS-06]
Severity:       [Critical / High / Medium / Low]
Summary:        [One-line description]
Steps to Reproduce:
  1. [Step 1]
  2. [Step 2]
  3. [Step 3]
Expected Result: [What should happen]
Actual Result:   [What actually happened]
Screenshots:     [Attach if applicable]
Browser:         [Chrome / Firefox / Safari / Edge + version]
Environment:     [Local Laragon / ngrok Remote]
```

### Sign-Off

| Field | Value |
|-------|-------|
| Tester Name | |
| Test Date | |
| Build Version | 3.0 |
| Environment | Laragon Local / ngrok Remote |
| Browser(s) Tested | |
| OS | |
| Total Tests Executed | /338 |
| Total Passed | |
| Total Failed | |
| Total Blocked | |
| Critical Bugs Found | |
| Overall Status | **Pass / Fail / Conditional Pass** |
| Notes | |

---

*Rentify QA Testing Guide v3.0 — Flow-Based Structure*
*Total Test Cases: 338*
*Generated: February 16, 2026*
*Rentify — Property Management System for Kenya*
