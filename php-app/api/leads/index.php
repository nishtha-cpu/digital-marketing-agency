<?php
/**
 * api/leads/index.php
 * Replaces: leadController + leadRoutes.js
 *
 * POST   /api/leads/index.php          – Public:  submit contact form (createLead)
 * GET    /api/leads/index.php          – Admin:   get all leads (getLeads)
 * DELETE /api/leads/index.php?id=X    – Admin:   delete lead (deleteLead)
 */

require_once __DIR__ . '/../_helpers.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

$method = $_SERVER['REQUEST_METHOD'];
$pdo    = db();

/* ── POST: Create Lead (public contact form) ───────────── */
if ($method === 'POST') {
    if (defined('APP_ENV') && APP_ENV === 'development') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    $body    = json_decode(file_get_contents('php://input'), true);
    $name    = trim($body['name']            ?? '');
    $email   = strtolower(trim($body['email']   ?? ''));
    $phone   = trim($body['phone']           ?? '');
    $interest = trim($body['service_interest'] ?? $body['serviceInterest'] ?? 'General Inquiry');
    $message = trim($body['message']         ?? '');

    if (!$name || !$email || !$message) {
        jsonResponse(['success' => false, 'message' => 'Please provide name, email, and message'], 400);
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(['success' => false, 'message' => 'Please add a valid email'], 400);
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO leads (name, email, phone, service_interest, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone ?: null, $interest, $message]);
        $id = $pdo->lastInsertId();

        jsonResponse([
            'success' => true,
            'message' => 'Contact form submitted successfully',
            'data'    => ['id' => $id, 'name' => $name, 'email' => $email, 'status' => 'new']
        ], 201);
    } catch (PDOException $e) {
        if (defined('APP_ENV') && APP_ENV === 'development') {
            jsonResponse([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'error'   => $e->getMessage()
            ], 500);
        } else {
            jsonResponse([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    } catch (Exception $e) {
        if (defined('APP_ENV') && APP_ENV === 'development') {
            jsonResponse([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'error'   => $e->getMessage()
            ], 500);
        } else {
            jsonResponse([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}

/* ── GET: List Leads (admin only) ──────────────────────── */
if ($method === 'GET') {
    requireRole('admin');

    $statusFilter = $_GET['status'] ?? '';
    if ($statusFilter && in_array($statusFilter, ['new','contacted','closed'])) {
        $stmt = $pdo->prepare("SELECT * FROM leads WHERE status = ? ORDER BY created_at DESC");
        $stmt->execute([$statusFilter]);
    } else {
        $stmt = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC");
    }
    $leads = $stmt->fetchAll();

    jsonResponse(['success' => true, 'count' => count($leads), 'data' => $leads]);
}

/* ── DELETE: Remove Lead (admin only) ──────────────────── */
if ($method === 'DELETE') {
    requireRole('admin');

    $id = (int) ($_GET['id'] ?? 0);
    if (!$id) jsonResponse(['success' => false, 'message' => 'Lead ID required'], 400);

    $stmt = $pdo->prepare("SELECT id FROM leads WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) jsonResponse(['success' => false, 'message' => 'Lead not found'], 404);

    $pdo->prepare("DELETE FROM leads WHERE id = ?")->execute([$id]);
    jsonResponse(['success' => true, 'message' => 'Lead deleted successfully']);
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
