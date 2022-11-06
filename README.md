# OpenSSL Digital Signature

DSA simply has 2 operations:
- Signature Generation (Sign)
- Signature Verification

## 1. Sign
```php
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
```
## 2. Verify
```php
function verifySignature($payload, $signature)
{
    // Concatenate all payload elements
    $data = implode("", $payload);

    $signatureDecoded = base64_decode($signature);

    $pubKey = file_get_contents('pubkey.pem');
    $pubKeyInstance = openssl_pkey_get_public($pubKey);

    return openssl_verify($data, $signatureDecoded, $pubKeyInstance, "sha384");
}
```

## 3. Usage (Demo)
```php
# Change the data (any) in payload from sign.php
$payload = [
    'firstName' => 'Robin',
    'lastName' => 'Correa',
    'role' => 'Full-stack Software Developer'
];
```

### Run the decryption script
php verify.php

## 4. Generated payload with signature from sign.php
```json
{
	"payload": "JSON payload",
	"signature": "Base64 encoded signature"
}
```

## 5. Verification Result
```php
$verifyResult = verifySignature($payload, $decoded['signature']);

if ($verifyResult == 1) {
    echo "good signature";
} else if ($verifyResult == 0) {
    echo "bad signature";
} else {
    echo "error checking signature";
}
```