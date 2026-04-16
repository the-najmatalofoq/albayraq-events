<?php
// resources/lang/en/messages.php
return [
    'success' => 'Success',
    'created' => 'Created successfully',
    'updated' => 'Updated successfully',
    'not_found' => 'Not found',
    'forbidden' => 'Forbidden',
    'unauthorized' => 'Unauthorized',
    'validation_failed' => 'Validation failed',

    'error' => 'An error occurred',
    'auth' => [
        'registered' => 'Registration successful',
        'logged_in' => 'Logged in successfully',
        'logged_out' => 'Logged out successfully',
        'token_refreshed' => 'Token refreshed successfully',
        'invalid_credentials' => 'Invalid credentials',
        'token_mismatch' => 'Session expired. Please login again.',
        'session_invalidated' => 'Your session has been terminated or invalidated. Please login again.',
        'unauthorized' => 'You are not authorized to perform this action.',
        'forbidden' => 'You do not have permission to access this resource.',
        'email_not_verified' => 'Your email address is not verified.',
        'session_invalidated' => 'Your session has been terminated or invalidated. Please login again.',

    ],
    'user' => [
        'account_not_active' => 'Your account is not active.',
        'user_already_exists' => 'A user with this information already exists.',
        'join_request' => [
            'approved' => 'Join request approved. User has been notified.',

        ],
    ],
    'errors' => [
        'validation_failed' => 'The given data was invalid.',
        'unauthenticated' => 'You are not authenticated.',
        'forbidden' => 'You do not have permission to perform this action.',
        'not_found' => 'The requested resource was not found.',
        'token_mismatch' => 'Your session has expired. Please login again.',
        'too_many_requests' => 'Too many requests. Please try again later.',
        'server_error' => 'An internal server error occurred.',
        'service_unavailable' => 'The service is temporarily unavailable.',
        'user_already_exists' => 'A user with this email already exists.',
        'invalid_credentials' => 'Invalid email or password.',
        'email_not_verified' => 'Your email address is not verified Please check your email for the verification code.',
        'user_not_approved' => 'Your join request has not been approved yet.',
        'user_pending' => 'Your join request is still pending approval.',
        'bank_already_exists' => 'A bank account with this IBAN or Owner Account already exists.',
        'user_not_found' => 'The requested user was not found.',
        'user_not_verified' => 'Your email address is not verified.',
        'pending_update_request' => 'You already have a pending update request for :target.',
    ],
    'targets' => [
        'user_info' => 'personal information',
        'employee_profile' => 'employee profile',
        'medical_record' => 'medical record',
        'bank_account' => 'bank account',
    ],
];
