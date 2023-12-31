<?php

function validateGoogleRecaptchaV3(array $formData): bool
{
    $serverSecretKey = 'secret key';

    $check = array(
        'secret'   => $serverSecretKey,
        'response' => $formData['g-recaptcha-response']
    );

    $startProcess = curl_init();

    curl_setopt($startProcess, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($startProcess, CURLOPT_POST, true);
    curl_setopt($startProcess, CURLOPT_POSTFIELDS, http_build_query($check));
    curl_setopt($startProcess, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($startProcess, CURLOPT_RETURNTRANSFER, true);

    $receiveData = curl_exec($startProcess);

    curl_close($startProcess);

    $finalResponse = json_decode($receiveData, true);

    if ($finalResponse['score'] < 0.7) {
        throw new Exception("invalid recaptcha", 400);
    }

    return true;
}
