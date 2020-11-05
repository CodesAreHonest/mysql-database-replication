<?php

return [
    'validation' => [
        'invalid_input'   => 'Input provided is not valid.',
        'INVALID_PAYLOAD' => 'Payload provided does not satisfy our request body requirements.'
    ],
    'server'     => [
        'MAINTENANCE'         => 'server module under maintenance',
        'FORBIDDEN'           => 'user does not have the permission to access or create resource',
        'QUERY_EXCEPTION'     => 'malformed query founds in database operations.',
        'UNEXPECTED_ERROR'    => 'an unexpected events occurs on database transactions or server operations. ',
        'UNHANDLED_EXCEPTION' => 'errors generate from operations without exception handling.',
        'CLIENT_EXCEPTION'    => 'client made an invalid request.', // 4xx families
        'SERVER_EXCEPTION'    => 'server failed to fulfill a request. ', // 500 families
        'UNAUTHORIZED'        => 'you have no permission access to the resources', // 403
        'UNAUTHENTICATED'     => 'unable to authenticated with the provided credentials', // 401
        'API_KEY_INVALID'     => 'API Key invalid', // 401
        'NOT_FOUND'           => 'Record not found', // 404
        'BAD_GATEWAY'         => 'unknown error occurs' // 502
    ],
];
