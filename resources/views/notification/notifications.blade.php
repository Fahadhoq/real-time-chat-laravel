<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        .notification-card {
            margin-top: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .notification-card h4 {
            margin-bottom: 20px;
        }
        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        .notification-item:last-child {
            border-bottom: none;
        }
        .badge-custom {
            padding: 5px 10px;
            font-size: 0.85rem;
            border-radius: 12px;
        }
        .badge-unread {
            background-color: #dc3545;
            color: #ffffff;
        }
        .badge-read {
            background-color: #28a745;
            color: #ffffff;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Notifications</h1>

        @if ($notifications->isEmpty())
            <div class="alert alert-info text-center" role="alert">
                No notifications found.
            </div>
        @else
            <div class="notification-card">
                <h4>Your Notifications</h4>
                <a href="{{ route('order.notification') }}" class="btn btn-sm btn-link text-decoration-none">Send Notification</a>
                        
                @foreach ($notifications as $notification)
                    <div class="notification-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 text-primary">{{ $notification->data['title'] ?? 'Notification' }}</h5>
                            <p class="mb-2 text-muted">{{ $notification->data['message'] ?? '' }}</p>
                        </div>
                        <div>
                            @if (is_null($notification->read_at))
                                <span class="badge badge-custom badge-unread">Unread</span>
                            @else
                                <span class="badge badge-custom badge-read">Read</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
