<?php

/**
 * Verify Signature
 * 
 * @author Robin Correa <robin.correa21@gmail.com>
 */

function verifySignature($payload, $signature)
{
    // Concatenate all payload elements
    $data = implode("", $payload);

    $signatureDecoded = base64_decode($signature);

    $pubKey = file_get_contents('pubkey.pem');
    $pubKeyInstance = openssl_pkey_get_public($pubKey);

    return openssl_verify($data, $signatureDecoded, $pubKeyInstance, "sha384");
}

// Trigger encrypt data via curl to tier1 script
$ch = curl_init("http://localhost/openssl-dsa-php/sign.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curlResult = curl_exec($ch);
curl_close($ch);

// Decode data
$decoded = json_decode($curlResult, 1);
$payload = json_decode($decoded['payload'], 1);
$verifyResult = verifySignature($payload, $decoded['signature']);

if ($verifyResult == 1) {
    echo "good signature";
} else if ($verifyResult == 0) {
    echo "bad signature";
} else {
    echo "error checking signature";
}
