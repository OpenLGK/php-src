--TEST--
Bug #68996 (Invalid free of CG(interned_empty_string))
--EXTENSIONS--
soap
--SKIPIF--
<?php
if (getenv("USE_ZEND_ALLOC") !== "0")
    print "skip Need Zend MM disabled";
?>
--FILE--
<?php
$s = new SoapServer(NULL, [
    'uri' => 'http://foo',
]);

function foo() {
  return new SoapFault("\xfc\x63", "some msg");
}
$s->addFunction("foo");

function handleFormatted($s, $input) {
  ob_start();
  $s->handle($input);
  $response = ob_get_clean();

  // libxml2 2.13 has different formatting behaviour: it outputs <faultcode/> instead of <faultcode></faultcode>
  // this normalizes the output to <faultcode/>
  $response = str_replace('<faultcode></faultcode>', '<faultcode/>', $response);
  $response = str_replace('<env:Value></env:Value>', '<env:Value/>', $response);
  echo $response;
}

// soap 1.1
$HTTP_RAW_POST_DATA = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
  <SOAP-ENV:Body>
    <SOAP-ENV:foo />
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOF;
handleFormatted($s, $HTTP_RAW_POST_DATA);

// soap 1.2
$HTTP_RAW_POST_DATA = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope">
  <env:Body>
    <env:foo />
  </env:Body>
</env:Envelope>
EOF;
handleFormatted($s, $HTTP_RAW_POST_DATA);
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"><SOAP-ENV:Body><SOAP-ENV:Fault><faultcode/><faultstring>some msg</faultstring></SOAP-ENV:Fault></SOAP-ENV:Body></SOAP-ENV:Envelope>
<?xml version="1.0" encoding="UTF-8"?>
<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope"><env:Body><env:Fault><env:Code><env:Value/></env:Code><env:Reason><env:Text xml:lang="">some msg</env:Text></env:Reason></env:Fault></env:Body></env:Envelope>
