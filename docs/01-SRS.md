# System Requirements Specification (SRS)

## Event-Centric Workforce Management Platform

**Version:** 1.0  
**Date:** 2026-03-25  
**Based on:** وثيقة متطلبات تطوير النظام (System Requirements Document)

---

## 1. Executive Summary

This platform manages temporary and event-based workforces where the **Event (الفعالية)** is the central organizational unit. Every business operation — hiring, contracts, attendance, evaluations, tasks, reports, and financials — exists exclusively within the scope of an event. An employee has no persistent operational identity beyond the events they participate in.

---

## 2. Core Design Principles

- The **Event** is the atomic unit. No orphan operations exist outside an event context.
- An employee participating in 3 events has 3 separate contracts, evaluations, badges, and certificates.
- Permissions, roles, and access are scoped **per-event**, not globally.
- When an event closes, all associated access and permissions expire.
- Cross-event employee history (ratings, violations) is only visible during acceptance review.

---

## 3. Actors & Roles

| Actor | Arabic | Scope | Key Capabilities |
|-------|--------|-------|------------------|
| General Manager | المدير العام | Global | View all events, unified reports, override attendance |
| Operations Manager | مدير العمليات | Global | Same as GM |
| Project Manager | مدير المشروع | Per-event | Final violation approval, financial approval, event closure |
| Area Manager | مدير منطقة | Per-event | Mid-tier violation approval, evaluations |
| Site Manager | مدير موقع | Per-group | Group-level management |
| Supervisor | مشرف | Per-group | Attendance barcode, first-tier violations, daily evaluations |
| Individual (Worker) | فرد | Per-event | View tasks, check-in/out, accept contracts |
| Applicant | متقدم | Temporary | Create account, apply to event+position |
| System Controller | متحكم بالنظام | Global | Barcode settings, system configuration |
| Admissions Admin | إدارة القبول | Per-event | Accept/reject applicants, assign job titles |

---

## 4. Feature Inventory

### F01 — Event Lifecycle Management
The Event is the root aggregate. Lifecycle: Draft → Published → Active → Pending Closure → Closed. Contains: name, geolocation with geofence, duration, working hours, positions, wages, employment terms, guide file (PDF), and logo.

### F02 — Announcements & Applications
Job postings are event-bound. No generic job board exists. Applicants apply to Event + Position. System auto-announces to alumni. Auto-ranking by: past evaluations, participation count, violation count.

### F03 — Acceptance & Assignment
Admissions Admin accepts applicants from within the event. Each participation gets independent contract, evaluation, guide file. Past history displayed on acceptance screen. Rejected/cancelled appear in separate list.

### F04 — Contracts
Event-bound, position-bound contracts (daily or monthly). 5-step mandatory acceptance gate: read contract → read regulations → read guide → watch video → pass quiz. Admin rejection requires selecting a predefined reason.

### F05 — Attendance & Departure
Geofenced to event location. Primary method: scanning supervisor's barcode. Screenshot prevention on barcode screen. Admin override available. No attendance outside event scope.

### F06 — Evaluations
Daily evaluations per employee per event. Evaluators: Manager, Supervisor, Individual (per permissions). Auto-lock after configurable window. All evaluations must complete before event closure.

### F07 — Violations & Deductions
Event-scoped violations with tiered escalation: Supervisor → Area Manager → Project Manager. Deductions apply only to event contract. Violations affect future ranking.

### F08 — Tasks
Per-event tasks assigned to specific individuals. Private visibility (only assignee sees). Optional links to time, location, or group.

### F09 — Groups
Color-coded groups within events. Visible on map. Composition: Site Manager + Site Supervisor + Individuals. Membership locks after operations start. Group-level file management.

### F10 — Operational Reports
Six types: Security, Medical, Patrols, Lost Items, Daily Observations, Readiness. Uniform template, editable, multi-format export. All must be approved before event closure.

### F11 — Employee Badge
Per-event badge: name, employee number, job title, event name, company logo, event logo. Same employee gets different badges per event.

### F12 — Messaging & Notifications
Admin-to-employee broadcast. Targeting: all participants, specific group, or job category. In-app + push notifications.

### F13 — Financials & Odoo Integration
Odoo ERP sync for: contracts, wages, deductions, expenses, financial approvals. Financial data must be approved before event closure.

### F14 — Custodies & Expenses
In-app custody handover. Dedicated file sections for custodies and expenses. Feeds into financial module.

### F15 — Dashboard & Permissions
Event-level RBAC. Configurable: who manages announcements, approves contracts, uploads reports, creates tasks. Full audit log (who, when, what). GM/Ops see all events.

### F16 — Experience Certificates
Auto-generated on event closure. Contains: employee name, event name, total hours, evaluation level. Unique verification code.

---

## 5. Event Closure Gates

| Gate | Condition | Source |
|------|-----------|--------|
| Gate 1 | All employees evaluated | F06 |
| Gate 2 | All reports approved | F10 |
| Gate 3 | Financial data approved | F13 |

---

## 6. Non-Functional Requirements

| Category | Requirement |
|----------|-------------|
| Security | Screenshot prevention on barcode screens |
| Security | Event-scoped RBAC with full audit logging |
| Performance | Real-time geofence validation |
| Performance | Concurrent attendance for large events |
| Availability | Offline capability for remote sites |
| Integration | Odoo ERP bidirectional sync |
| Integration | Google Maps + Geofencing API |
| Localization | Arabic RTL + English (spatie/laravel-translatable) |
| Data | Complete isolation between events |
| Compliance | Contract acceptance evidence trail (5-step gate) |
