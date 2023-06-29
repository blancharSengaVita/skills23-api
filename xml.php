<?php $xml = file_get_contents("http://localhost:5500/file.xml");
$xml = SimpleXMLElement($xml);
print $xml;