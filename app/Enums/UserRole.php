<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case LANDLORD = 'landlord';
    case TENANT = 'tenant';
    case AGENT = 'agent';
}
