<?php

namespace App\Contracts\Auth;

interface MustVerifyNumber
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedNumber();

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markNumberAsVerified();

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendNumberVerificationNotification();

    /**
     * Get the whtsapp number that should be used for verification.
     *
     * @return string
     */
    public function getNumberForVerification();
}
