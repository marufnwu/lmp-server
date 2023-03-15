<?php

$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://lotterymasterpro.com/api/pdf.upload_result.php?slotId=1&key=maruf.is.rex");
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser
echo curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);


?>
