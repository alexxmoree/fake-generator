<?php

namespace App\Enum;

enum LoyaltyTriggerType: string
{
    case CreditForOrder = 'credit_for_order';
    case Bonus = 'bonus';
    case Referral = 'referral';
}