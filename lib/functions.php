<?php
// /lib/functions.php
// Common helper functions for Jetz Motors System

/**
 * Sanitize output to prevent XSS.
 */
function sanitize($val)
{
    return htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8');
}

/**
 * Format money values with Peso sign.
 */
function fmt_money($num)
{
    return "₱" . number_format((float) $num, 2);
}

/**
 * Check if a membership is still active.
 */
function is_member_active($is_member, $member_until)
{
    if (!$is_member)
        return false;
    if (!$member_until)
        return false;
    return (strtotime($member_until) >= strtotime(date("Y-m-d")));
}

/**
 * Redirect helper.
 */
function redirect($url)
{
    header("Location: $url");
    exit;
}

/**
 * Generate unique reference code.
 */
function generate_ref($prefix = "REF")
{
    return $prefix . "-" . strtoupper(bin2hex(random_bytes(4)));
}

/**
 * Quick JSON success response (for API endpoints).
 */
function json_ok($data = [])
{
    header("Content-Type: application/json");
    echo json_encode(["ok" => true, "data" => $data]);
    exit;
}

/**
 * Quick JSON error response (for API endpoints).
 */
function json_err($message, $code = 400)
{
    http_response_code($code);
    header("Content-Type: application/json");
    echo json_encode(["ok" => false, "error" => $message]);
    exit;
}
