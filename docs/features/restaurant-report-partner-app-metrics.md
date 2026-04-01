# Feature: Partner App Metrics in Restaurant Report

## Task Title

**Movo Dashboard – Restaurant Report Update**  
_(ClickUp Task ID: 86ex3h690)_

## Description

Improve the restaurant report in the Movo Dashboard by adding new metrics to measure Partner App usage and restaurant performance in handling orders received via the partner app.

The report is located at:  
`baseUrl/admin/reports/restaurant-report`

## Objectives

- Add a boolean indicator showing whether a restaurant uses the partner app.
- Display the usage rate (percentage of orders processed via the partner app relative to total orders).
- Show detailed counts of orders accepted and rejected through the partner app.

All values must be calculated only from orders placed during the selected date range (`orders_from` / `orders_to`) and only from orders that are handled via the partner app.

## Required Fields

| Field Name                        | Type       | Description                                                                       | Calculation                                                                  |
| --------------------------------- | ---------- | --------------------------------------------------------------------------------- | ---------------------------------------------------------------------------- |
| **Partner App Usage**             | Yes/No     | Indicates whether the restaurant uses the Partner App to manage orders.           | `Yes` if `accepted_orders_count + rejected_orders_count > 0`, else `No`.     |
| **Usage Rate**                    | Percentage | Reflects how much the restaurant interacts with orders coming through the app.    | `(accepted_orders_count + rejected_orders_count) ÷ total_orders_count × 100` |
| **Accepted Orders (Partner App)** | Integer    | Number of orders the restaurant accepted via the Partner App.                     | `partner_accept_date IS NOT NULL`                                            |
| **Rejected Orders (Partner App)** | Integer    | Number of orders the restaurant rejected via the Partner App (without accepting). | `partner_accept_date IS NULL` AND cancellation reason flag = `'p'` (partner) |

**Note:** The originally planned “Cancelled Orders (Partner App)” field was removed from the scope.

## Business Logic & Database Relations

- **Orders table:** `tbl_ordering_order`
    - `partner_accept_date` – set when the restaurant accepts an order via the partner app.
    - `cancel_id` – foreign key to `tbl_order_cancel_resons.reson_id` for cancelled orders.
- **Cancel reasons table:** `tbl_order_cancel_resons`
    - `flag` – indicates who initiated the cancellation: `'p'` for partner, `'a'` for admin, `'u'` for user.
- **Canceled orders table:** `tbl_canceled_orders`
    - Links `order_id` to `cancel_id`.

The relationship chain:  
`Order` → `cancel()` → `CanceledOrder` → `cancelReason()` → `CancelReason` with `flag = 'p'`.

All counts respect the date filters `orders_from` and `orders_to` applied to the `date_created` column.

## Needed Changes

### 1. Repository Method

**File:** `app/Repository/Dashboard/ReportRepository.php`

- Add a private helper `applyOrderDateFilter()` to reuse the date filter logic.
- In `restaurantReport()`, add three new `withCount` subqueries:
    - `total_orders_count` – all orders (any status) in the period.
    - `accepted_orders_count` – orders with `partner_accept_date` not null.
    - `rejected_orders_count` – orders never accepted (`partner_accept_date` null) and linked to a partner cancel reason.
- Remove the previous `cancelled_by_partner_count`.

### 2. Blade Views

**Files:**

- `resources/views/reports/restaurant-report.blade.php`
- `resources/views/reports/restaurant-report-excel.blade.php`

- Add new table headers for the four columns.
- Inside the `@foreach` loop, compute the values:
    ```php
    $accepted = $branch->accepted_orders_count ?? 0;
    $rejected = $branch->rejected_orders_count ?? 0;
    $totalOrders = $branch->total_orders_count ?? 0;
    $usageRate = $totalOrders > 0 ? round(($accepted + $rejected) / $totalOrders * 100, 2) : 0;
    ```
- Display:
    - Partner App Usage: `{{ ($accepted + $rejected) > 0 ? 'Yes' : 'No' }}`
    - Usage Rate: `{{ $usageRate }}%`
    - Accepted Orders: `{{ $accepted }}`
    - Rejected Orders: `{{ $rejected }}`

### 3. Export Class

**File:** `app/Exports/RestaurantReportExport.php`  
No changes needed because it uses the same repository and view.

## Inputs

- **Date filters:** `orders_from` and `orders_to` (datetime-local inputs) – used to filter all order counts.
- Other existing filters (province, store type, commission ranges, etc.) remain unchanged.

## Outputs

The restaurant report table (HTML and Excel export) will contain the following additional columns:

| Column Name                   | Example Value |
| ----------------------------- | ------------- |
| Partner App Usage             | Yes           |
| Usage Rate                    | 68.50%        |
| Accepted Orders (Partner App) | 237           |
| Rejected Orders (Partner App) | 12            |

All values are consistent with the selected date range.

## Implementation Summary

| File                                | Change                                                                                                                               |
| ----------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------ |
| `ReportRepository.php`              | Added `total_orders_count`, `accepted_orders_count`, `rejected_orders_count` with date filter; removed `cancelled_by_partner_count`. |
| `restaurant-report.blade.php`       | Added four new table columns; removed cancelled column; adjusted usage rate formula.                                                 |
| `restaurant-report-excel.blade.php` | Same changes as HTML view.                                                                                                           |

## Testing Checklist

- [ ] The new columns appear in both the HTML report and the Excel export.
- [ ] **Partner App Usage** = Yes when at least one accepted or rejected order exists in the period.
- [ ] **Usage Rate** = (Accepted + Rejected) ÷ Total Orders × 100.
- [ ] Changing the date filters updates all four new columns correctly.
- [ ] A restaurant with no partner app activity shows No, 0%, 0, 0.
- [ ] A restaurant with only accepted orders shows Yes, (Accepted ÷ Total Orders)%, correct accepted count, 0 rejected.
- [ ] A restaurant with only rejected orders shows Yes, (Rejected ÷ Total Orders)%, 0 accepted, correct rejected count.
- [ ] Cancelled orders are **not** counted in any of these metrics.

---

**Date:** 2026-04-01  
**Author:** Development Team
