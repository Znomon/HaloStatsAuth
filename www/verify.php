<?php

$pubkey = $_POST["pubKey"];

#"MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAnxyPA5TlZ61CHQgKgKNVqvJFqLoqVWoLb6V+WS1xQKGMr5d6gkk3fpwSOq0+z4Qf8OaYmZ49+CcWY/lMhNV3w0Np5zWvjwsMEYDdHcvM6J+kmy7INmet2vlfLmwO76Ap62+APsMWREnVXf2YWVqTKN5DyJofxBycdE/Te9GSzP3LFiGRzwIdDnZkcB5foyndJh4NWvgGPsKm8GTSCyvZri6FIsNykV6X8icUs5exBPf0Usq6xsek55D/ej6n6MxdsIiWOTqsUiLl40zv7UbpWzcJSeuXcgg52t3DleFGXJWDrfAevo3iqYkeWdIz2AvaQ1G9y9J9PZCxpNjpOD2B71ATcuAYYOXrWQZBaEuq/ZAV5RGapc40CxZBi+rGu8xVHaXAlynza1yomFfnURo9EGdx3E25DKbOy7QHouG0iluc3io+cwc+tprvNg8eMqgAEaCmPdpf5bNpAKkNk38p9v3CtLbNyshTfOT3AGAOAEAYCBzmyYtdgx+x3ieU+Zj2YCHdfHqMYlxAuXqKYPWEntT7CbDD2kyi/ayWJuW1uwedlqwaGqlThwNBg9BdDk53hqv+g/jjjLqdE4cUzONbiUQe8Wgat0R/70iWp3vsPOoMfFzQoXDAq2OuePoIVYaezJ/2C+LMn/MvpPfykV8yPIujLhh2Fba2jioHmhTqit8CAwEAAQ==";

$answer = shell_exec("GenUID.exe $pubkey");
#echo $answer."</br>";

?>

<html>
<body>

Welcome<br>
<br>
Your UID is: <?php echo $answer; ?>

</body>
</html>

<?php #echo $_POST["pubKey"]; ?>


