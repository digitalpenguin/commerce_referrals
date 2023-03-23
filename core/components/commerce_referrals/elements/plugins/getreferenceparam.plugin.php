<?php
// Checks for GET referral parameter and assigns to user's session.
if (isset($_GET['ref']) && !empty($_GET['ref'])) {
    $_SESSION['commerce_referrals_reference'] = filter_input(INPUT_GET, 'ref',FILTER_SANITIZE_STRING);
}