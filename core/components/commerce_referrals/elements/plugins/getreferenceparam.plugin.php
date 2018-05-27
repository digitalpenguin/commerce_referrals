<?php
// Checks for GET referral parameter and assigns to user's session.
if($_GET['ref']) {
    $_SESSION['ref'] = filter_input(INPUT_GET,"ref",FILTER_SANITIZE_STRING);
}