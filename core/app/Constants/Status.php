<?php

namespace App\Constants;

class Status
{

    const ENABLE  = 1;
    const DISABLE = 0;

    const YES = 1;
    const NO  = 0;

    const VERIFIED   = 1;
    const UNVERIFIED = 0;

    const PAYMENT_INITIATE = 0;
    const PAYMENT_SUCCESS  = 1;
    const PAYMENT_PENDING  = 2;
    const PAYMENT_REJECT   = 3;

    const TICKET_OPEN   = 0;
    const TICKET_ANSWER = 1;
    const TICKET_REPLY  = 2;
    const TICKET_CLOSE  = 3;

    const PRIORITY_LOW    = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH   = 3;

    const USER_ACTIVE = 1;
    const USER_BAN    = 0;

    const KYC_UNVERIFIED = 0;
    const KYC_PENDING    = 2;
    const KYC_VERIFIED   = 1;

    const REVIEWER_ACTIVE = 1;
    const REVIEWER_BAN    = 0;

    const PRODUCT_PENDING     = 0;
    const PRODUCT_APPROVE     = 1;
    const PRODUCT_SOFT_REJECT = 2;
    const PRODUCT_HARD_REJECT = 3;
    const PRODUCT_DELETE      = 4;
    const PRODUCT_RESUBMIT    = 5;

    const PRODUCT_UPDATE_PENDING  = 1;
    const PRODUCT_UPDATE_APPROVED = 2;
    const PRODUCT_UPDATE_REJECTED = 3;

    const TEMP_PRODUCT_RESUBMIT = 1;
    const TEMP_PRODUCT_UPDATE   = 2;

    const SELL_PENDING  = 0;
    const SELL_APPROVED = 1;
    const SELL_REJECTED = 2;
    const SELL_INITIATE = 3;

    const CATEGORY_ACTIVE   = 1;
    const CATEGORY_INACTIVE = 0;

    const SUBCATEGORY_ACTIVE   = 1;
    const SUBCATEGORY_INACTIVE = 0;

    const REVIEW_REPORTED = 2;


    const REGULAR_LICENSE  = 1;
    const EXTENDED_LICENSE = 2;
}
