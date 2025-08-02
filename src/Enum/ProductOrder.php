<?php

namespace App\Enum;

enum ProductOrder: string
{
    case ALL = 'all';
    case ALL_REVERSE = 'all-reverse';
    case ALL_WITH_EXPIRATION_DATE = 'all-with-expiration-date';
    case NAME = 'name';
    case NAME_REVERSE = 'name-reverse';
}
