<?php
/**
 * pdf.php — Génération du CV en PDF avec TCPDF
 * Dépendance : tcpdf/tcpdf.php
 */

declare(strict_types=1);

// ================================
// CONFIGURATION & SÉCURITÉ
// ================================

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *'); // ⚠️ À restreindre en production
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Autoriser uniquement POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

// ================================
// CHARGEMENT TCPDF
// ================================

$tcpdfPath = __DIR__ . '/tcpdf/tcpdf.php';
if (!file_exists($tcpdfPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'TCPDF introuvable']);
    exit;
}

require_once $tcpdfPath;

// ================================
// RÉCUPÉRATION & VALIDATION JSON
// ================================

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'JSON invalide']);
    exit;
}

// Champs obligatoires
$required = ['name', 'email', 'phone', 'address', 'experience', 'education', 'skills'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Champ manquant : $field"]);
        exit;
    }
}

// ================================
// NETTOYAGE DES DONNÉES
// ================================

function clean(string $value): string {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

$name       = clean($data['name']);
$email      = clean($data['email']);
$phone      = clean($data['phone']);
$address    = clean($data['address']);
$experience = clean($data['experience']);
$education  = clean($data['education']);
$skills     = clean($data['skills']);
$photo      = $data['photo'] ?? null;

// ================================
// CRÉATION DU PDF
// ================================

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Métadonnées
$pdf->SetCreator('CV Generator');
$pdf->SetAuthor($name);
$pdf->SetTitle("CV - $name");
$pdf->SetSubject('Curriculum Vitae');

// Configuration page
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 15);
$pdf->AddPage();

// ================================
// EN-TÊTE
// ================================

// Photo (si URL valide)
if ($photo && filter_var($photo, FILTER_VALIDATE_URL)) {
    try {
        $pdf->Image($photo, 15, 15, 30, 30);
        $pdf->SetXY(50, 15);
    } catch (Exception $e) {
        $pdf->SetXY(15, 15);
    }
}

$pdf->SetFont('helvetica', 'B', 18);
$pdf->Cell(0, 10, $name, 0, 1);

$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 7, "Email : $email", 0, 1);
$pdf->Cell(0, 7, "Téléphone : $phone", 0, 1);
$pdf->Cell(0, 7, "Adresse : $address", 0, 1);
$pdf->Ln(8);

// ================================
// SECTION EXPÉRIENCE
// ================================

$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 9, 'Expérience professionnelle', 0, 1);

$pdf->SetFont('helvetica', '', 11);
$pdf->MultiCell(0, 7, $experience);
$pdf->Ln(4);

// ================================
// SECTION ÉDUCATION
// ================================

$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 9, 'Éducation', 0, 1);

$pdf->SetFont('helvetica', '', 11);
$pdf->MultiCell(0, 7, $education);
$pdf->Ln(4);

// ================================
// SECTION COMPÉTENCES
// ================================

$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 9, 'Compétences', 0, 1);

$pdf->SetFont('helvetica', '', 11);
$skillsArray = array_filter(array_map('trim', explode(',', $skills)));

foreach ($skillsArray as $skill) {
    $pdf->Cell(0, 6, "• $skill", 0, 1);
}

// ================================
// SORTIE PDF (AJAX)
// ================================

$pdfContent = $pdf->Output('', 'S');

echo json_encode([
    'success'  => true,
    'filename' => 'cv-' . strtolower(str_replace(' ', '-', $name)) . '.pdf',
    'pdf'      => base64_encode($pdfContent)
]);
