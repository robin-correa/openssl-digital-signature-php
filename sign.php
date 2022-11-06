<?php

/**
 * Sign / Generate Signature
 * 
 * @author Robin Correa <robin.correa21@gmail.com>
 */

function generateSignature($payload)
{
    // Use private key for signature
    $privKey = file_get_contents('privkey.pem');
    $privKeyInstance = openssl_pkey_get_private($privKey);
    $data = implode("", $payload);

    // sign signature
    openssl_sign($data, $signature, $privKeyInstance, "sha384");

    return base64_encode($signature);
}

$payload = [
    'firstName' => 'Robin',
    'lastName' => 'Correa',
    'role' => 'Full-stack Software Developer'
];

$presentInArray = [
    'payload' => json_encode($payload),
    'signature' => generateSignature($payload),
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($presentInArray);