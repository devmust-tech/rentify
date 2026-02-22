<?php

namespace App\Enums;

enum NotificationType: string
{
    case GENERAL = 'general';
    case INFO = 'info';
    case WARNING = 'warning';
    case SUCCESS = 'success';
    case ERROR = 'error';
    case PAYMENT_REMINDER = 'payment_reminder';
    case MAINTENANCE_UPDATE = 'maintenance_update';
    case LEASE_EXPIRY = 'lease_expiry';
}
