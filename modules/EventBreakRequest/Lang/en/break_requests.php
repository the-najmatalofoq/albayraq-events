<?php
// modules/EventBreakRequest/Lang/en/break_requests.php
return [
    'messages' => [
        'request_created' => 'Break request created successfully',
        'request_approved' => 'Break request approved successfully',
        'request_rejected' => 'Break request rejected successfully',
        'request_cancelled' => 'Break request cancelled successfully',
        'cannot_break_first_hour' => 'Cannot request a break during the first hour of the event',
        'cannot_break_last_hour' => 'Cannot request a break during the last hour of the event',
        'exceeds_daily_limit' => 'Total break duration exceeds the daily limit (60 minutes)',
        'overlap_with_existing' => 'This period overlaps with an existing approved break',
        'attendance_required' => 'You must check in before requesting a break',
        'unauthorized_approve' => 'You do not have permission to approve break requests for this event',
        'cover_employee_not_available' => 'The cover employee is not available or has a break at the same time',
        'break_not_found' => 'Break request not found',
        'already_approved' => 'This request has already been approved',
        'already_rejected' => 'This request has already been rejected',
        'cannot_cancel_approved' => 'Cannot cancel an approved request',
    ],
    'status' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'cancelled' => 'Cancelled',
    ],
];
