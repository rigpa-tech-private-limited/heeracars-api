<?php
// Provide the Host Information.
$tHost = "gateway.sandbox.push.apple.com";
//$tHost = "gateway.push.apple.com";
$tPort = 2195;
// Provide the Certificate and Key Data.
$tCert = "certificate.pem";
$tPassphrase = "heeracars2020";
$tToken = "token";
// Audible Notification Option.
$tSound = "default";
// The content that is returned by the LiveCode "pushNotificationReceived" message.
$tPayload = "APNS payload";
// Create the message content that is to be sent to the device.
// Create the payload body
//Below code for non silent notification
$tBody["aps"] = array(
"badge" => +1,
"alert" => "Test notification",
"sound" => "default"
);
$tBody ["payload"] = $tPayload;
// Encode the body to JSON.
$tBody = json_encode ($tBody);
// Create the Socket Stream.
$tContext = stream_context_create ();
stream_context_set_option ($tContext, "ssl", "local_cert", $tCert);
// Remove this line if you would like to enter the Private Key Passphrase manually.
stream_context_set_option ($tContext, "ssl", "passphrase", $tPassphrase);
// Open the Connection to the APNS Server.
$tSocket = stream_socket_client ("ssl://".$tHost.":".$tPort, $error, $errstr, 30, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $tContext);
// Check if we were able to open a socket.
if (!$tSocket)
exit ("APNS Connection Failed: $error $errstr" . PHP_EOL);
// Build the Binary Notification.
$tMsg = chr (0) . chr (0) . chr (32) . pack ("H*", $tToken) . pack ("n", strlen ($tBody)) . $tBody;
// Send the Notification to the Server.
$tResult = fwrite ($tSocket, $tMsg, strlen ($tMsg));
if ($tResult)
echo "Delivered Message to APNS" . PHP_EOL;
else
echo "Could not Deliver Message to APNS" . PHP_EOL;
// Close the Connection to the Server.
fclose ($tSocket);
?>