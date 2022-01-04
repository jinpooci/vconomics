<?php
require "func.php";

echo color('blue', "[+]")." Vconomics Bot - By: GidhanB.A\n";
echo color('blue', "[+]")." Input Reff: ";
$reff = trim(fgets(STDIN));

Start:
$domain = get_between_array(file_get_contents("https://generator.email/"), 'onclick="change_dropdown_list(this.innerHTML)" id="', '" style="');
$domain = $domain[array_rand($domain)];

$base = gendata($domain);
$email = $base->email;
$pswd = 'qwerty123';
$name = $base->firstname.' '.$base->lastname;

echo color('blue', "\n[+]")." Email: $email\n";

$headers = array();
$headers[] = 'User-Agent: okhttp/3.12.1';
$headers[] = 'Content-Type: application/json';
$headers[] = 'x-culture-code: EN';
$headers[] = 'x-location: ';
$reg = curl('https://id.vscore.vn/api-v1/accounts/register/4', '{"fromReferralId":"'.$reff.'","fullName":"'.$name.'","password":"'.$pswd.'","rePassword":"'.$pswd.'","userName":"'.$email.'"}', $headers);
if (strpos($reg[1], 'REGISTER_SUCCESSFUL_NEED_CONFIRM')) {
    $token = json_decode($reg[1])->data->token;
    echo color('green', "[+]")." Registration successfuly!\n";
    echo color('yellow', "[+]")." Checking email";

    $emails = explode("@", $email);
    $emailx = "surl=".trim($emails[1])."%2F".trim($emails[0]);
    $xyz = array();
    $xyz[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:74.0) Gecko/20100101 Firefox/74.0';
    $xyz[] = 'Cookie: '.$emailx;

    $a = true;
    $b = 0;
    while ($a) {
        if ($b > 5) {
            echo color('red', "\n[+]")." Bad email!\n";
            goto Start;
        }
        $cek = curl('https://generator.email/', null, $xyz, true);
        if (strpos($cek[1], 'Vconomics')) {
            $otp = get_between($cek[1], '"color: #fa7800; font-weight: bold; text-align: center; font-size: 40px">', "</p>");
            echo color('green', " [$otp]\n");
            $a = false;
        } else {
            echo ".";
            $b++;
        }
    }

    $ver = curl('https://id.vscore.vn/api-v1/tokens/verify-otp', '{"otp":"'.$otp.'","otpType":1,"validateToken":"'.$token.'"}', $headers);
    if (strpos($ver[1], 'VERIFY_OTP_SUCCESS')) {
        echo color('green', "[+]")." Verification successfuly!\n";
    } else {
        echo color('red', "[+]")." Error: ".$ver[1]."\n";
    }
    goto Start;
} else {
    echo color('red', "[+]")." Error: ".$reg[1]."\n";
    goto Start;
}
