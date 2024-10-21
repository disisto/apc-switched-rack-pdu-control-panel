<?php

/**
*    APC Switched Rack PDU Control Panel
*    Version 1.4
*
*    A PHP based Control panel to control multiple APC Switched Rack PDUs via SNMPv3. A single panel to switch (on, off, restart) the attached devices between different states.
*
*    Documentation: https://github.com/disisto/apc-switched-rack-pdu-control-panel
*
*
*    Licensed under MIT (https://github.com/disisto/apc-switched-rack-pdu-control-panel/blob/main/LICENSE)
*
*    Copyright (c) 2021-2024 Roberto Di Sisto
*
*    Permission is hereby granted, free of charge, to any person obtaining a copy
*    of this software and associated documentation files (the "Software"), to deal
*    in the Software without restriction, including without limitation the rights
*    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
*    copies of the Software, and to permit persons to whom the Software is
*    furnished to do so, subject to the following conditions:
*
*    The above copyright notice and this permission notice shall be included in all
*    copies or substantial portions of the Software.
*
*    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
*    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
*    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
*    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
*    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
*    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
*    SOFTWARE.
**/




####  Set your personal APC PDU settings

$apcPDUs = [];

$apcPDUs['001'] = createPDUCfg(
  false,                           // Toggle Switch             :: Switch on (true) or switch off (false) to stop monitoring the PDU.
  '',                             // IP address                :: Enter the IP address of the PDU
  '',                             // User Name                 :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> User Name | NOTE: The SNMP user name can contain up to 32 characters in length and include any combination of alphanumeric characters (uppercase letters, lowercase letters, and numbers). ⚠️  Spaces not allowed.
  '',                             // Authentication Passphrase :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Authentication Passphrase | NOTE: The password must be 15-32 ASCII characters long. ⚠️ Special characters not allowed.
  '',                             // Authentication Protocol   :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Authentication Protocol
  '',                             // Privacy Passphrase        :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Privacy Passphrase | NOTE: The password must be 15-32 ASCII characters long. ⚠️ Special characters not allowed.
  '',                             // Privacy Protocol          :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Privacy Protocol
  ''                              // Security Level            :: Set desired security level: noAuthNoPriv | authNoPriv | authPriv | More detail: https://www.php.net/manual/en/function.snmp3-get.php
);

$apcPDUs['002'] = createPDUCfg(
  false,                           // Toggle Switch             :: Switch on (true) or switch off (false) to stop monitoring the PDU.
  '',                             // IP address                :: Enter the IP address of the PDU
  '',                             // User Name                 :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> User Name | NOTE: The SNMP user name can contain up to 32 characters in length and include any combination of alphanumeric characters (uppercase letters, lowercase letters, and numbers). ⚠️  Spaces not allowed.
  '',                             // Authentication Passphrase :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Authentication Passphrase | NOTE: The password must be 15-32 ASCII characters long. ⚠️ Special characters not allowed.
  '',                             // Authentication Protocol   :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Authentication Protocol
  '',                             // Privacy Passphrase        :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Privacy Passphrase | NOTE: The password must be 15-32 ASCII characters long. ⚠️ Special characters not allowed.
  '',                             // Privacy Protocol          :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Privacy Protocol
  ''                              // Security Level            :: Set desired security level: noAuthNoPriv | authNoPriv | authPriv | More detail: https://www.php.net/manual/en/function.snmp3-get.php
);

function createPDUCfg($active, $ipAddress, $userProfile, $authPassphrase, $authProtocol, $privacyPassphrase, $privacyProtocol, $securityLevel) {
    return [
        'active'                    => $active,
        'ipAddress'                 => $ipAddress,
        'userProfile'               => $userProfile,
        'authenticationPassphrase'  => $authPassphrase,
        'authenticationProtocol'    => $authProtocol,
        'privacyPassphrase'         => $privacyPassphrase,
        'privacyProtocol'           => $privacyProtocol,
        'securityLevel'             => $securityLevel,
    ];
}

####################################################
### No further editing is needed below this line ###
####################################################

#### OID records
## http://oidref.com/1.3.6.1.4.1.318.1.1.12

// rPDUIdentName
$rPDUIdentName                  = '1.3.6.1.4.1.318.1.1.12.1.1';

// rPDUOutletDevCommand
$rPDUOutletDevCommand           = '1.3.6.1.4.1.318.1.1.12.3.1.1.0';

// rPDUOutletControlOutletCommand
$rPDUOutletControlOutletCommand = '1.3.6.1.4.1.318.1.1.12.3.3.1.1.4.';

// rPDUOutletStatusIndex
$rPDUOutletStatusIndex          = '1.3.6.1.4.1.318.1.1.12.3.5.1.1.1';

// rPDUOutletStatusOutletName
$rPDUOutletStatusOutletName     = '1.3.6.1.4.1.318.1.1.12.3.5.1.1.2';

// rPDUOutletStatusOutletState
$rPDUOutletStatusOutletState    = '1.3.6.1.4.1.318.1.1.12.3.5.1.1.4';


#################################################
##### FORM SUBMITTED -> FIRE COMMAND TO PDU #####
#################################################

### Toggle status (ON, OFF, REBOOT)

if (isset($_REQUEST["IP"]) && isset($_REQUEST["OUTLET"])) {
  foreach ($apcPDUs as $key => $apcPDU) {

    if ($apcPDU['ipAddress'] == $_REQUEST["IP"]) {

        // Command to all outlets has been send
        if (!is_numeric($_REQUEST["OUTLET"])) {
            switch ($_REQUEST["STATE"]) {
              case 'ON':
                  snmp3_set($apcPDU['ipAddress'], $apcPDU['userProfile'], $apcPDU['securityLevel'], $apcPDU['authenticationProtocol'], $apcPDU['authenticationPassphrase'], $apcPDU['privacyProtocol'], $apcPDU['privacyPassphrase'], $rPDUOutletDevCommand, 'i', 2);
                  header('Location: .');
                  exit;
              case 'OFF':
                  snmp3_set($apcPDU['ipAddress'], $apcPDU['userProfile'], $apcPDU['securityLevel'], $apcPDU['authenticationProtocol'], $apcPDU['authenticationPassphrase'], $apcPDU['privacyProtocol'], $apcPDU['privacyPassphrase'], $rPDUOutletDevCommand, 'i', 3);
                  header('Location: .');
                  exit;
              case 'REBOOT':
                  snmp3_set($apcPDU['ipAddress'], $apcPDU['userProfile'], $apcPDU['securityLevel'], $apcPDU['authenticationProtocol'], $apcPDU['authenticationPassphrase'], $apcPDU['privacyProtocol'], $apcPDU['privacyPassphrase'], $rPDUOutletDevCommand, 'i', 4);
                  header('Location: .');
                  exit;
            }
        }
        // Command to a single outlets has been send
        else {
          // Get state of the affected APC PDU power outlet
          $queryOutletState = snmp3_walk($apcPDU['ipAddress'], $apcPDU['userProfile'], $apcPDU['securityLevel'], $apcPDU['authenticationProtocol'], $apcPDU['authenticationPassphrase'], $apcPDU['privacyProtocol'], $apcPDU['privacyPassphrase'], $rPDUOutletStatusOutletState . '.' . $_REQUEST["OUTLET"]);

          if (isset($_REQUEST["STATE"]) != "REBOOT") {
            if (implode('', $queryOutletState) == 'INTEGER: 1') {
              // If submitted state is ON (1), change to OFF (2)
              $state = 2;
            }

            if (implode('', $queryOutletState) == 'INTEGER: 2') {
              // If submitted state is OFF (2), change to ON (1)
              $state = 1;
            }
            
          }
          else {
            // REBOOT has been requested
            $state = 3;
          }

          snmp3_set($apcPDU['ipAddress'], $apcPDU['userProfile'], $apcPDU['securityLevel'], $apcPDU['authenticationProtocol'], $apcPDU['authenticationPassphrase'], $apcPDU['privacyProtocol'], $apcPDU['privacyPassphrase'], $rPDUOutletControlOutletCommand . $_REQUEST["OUTLET"], 'i', $state);
          header('Location: .');
          exit;
        }
    }
  }
}

### Rename PDU name

#### Error handling: If all new PDU name contain only supported characters
$noASCII = false;

if (isset($_POST["IP"]) && isset($_POST["pduInputName"])) {

  // Check if input contain any non-ASCII characters
  if(!preg_match('/[^\x20-\x7e]/', $_POST["pduInputName"])) {
    // 100% ASCII -> start renaming
    $pduNewName = $_POST["pduInputName"];
    foreach ($apcPDUs as $key => $apcPDU) {
      if ($apcPDU['ipAddress'] == $_POST["IP"]) {
        snmp3_set($apcPDU['ipAddress'], $apcPDU['userProfile'], $apcPDU['securityLevel'], $apcPDU['authenticationProtocol'], $apcPDU['authenticationPassphrase'], $apcPDU['privacyProtocol'], $apcPDU['privacyPassphrase'], $rPDUIdentName . '.0', 's', $pduNewName);
        header('Location: .');
        exit;
      }
    }
  }
  else {
   // Show error message as input contain non-ASCII
    $noASCII      = true;
    $affectedPDU  = $_POST["IP"];
  }
}

################################################
############# Error Msg Templates ##############
################################################

#### Error handling: If all PDUs are deactivated (all are set to false)
// Initialization
$anytrue = false;
$alltrue = true;

function errorMsg($errorType, $erroMsgString, $affectedPDU) {

  switch ($erroMsgString) {
    case 'Timeout':
        $errorMsg['en']['connection']['head']       = $erroMsgString;
        $errorMsg['en']['connection']['body']       = '<p>A <b>'.strtolower($erroMsgString).'</b> has occurred on the PDU with the IP address <b>'.$affectedPDU.'</b>. Possible reasons:</p>
                                                      <ul>
                                                        <li>Device is unreachable</li>
                                                          <ul>
                                                            <li>Device is busy</li>
                                                            <li>Device is offline</li>
                                                            <li>Device is in an different (V)LAN</li>
                                                            <li>IP address has been changed</li>
                                                            <li>Security settings prevent connection</li>
                                                            <li>and more...</li>
                                                          </ul>
                                                      </ul>';
        $errorMsg['en']['connection']['footer']     = 'Please consult <a href="https://github.com/disisto/apc-switched-rack-pdu-control-panel" title="GitHub" target="_blank" rel="noopener noreferrer nofollow" class="text-decoration-none">README.md</a> for more informations.';
        break;

    case 'Unknown user name':
        $errorMsg['en']['connection']['head']       = $erroMsgString;
        $errorMsg['en']['connection']['body']       = '<p>An <b>'.strtolower($erroMsgString).'</b> was used to log into the PDU with the IP address <b>'.$affectedPDU.'</b>. Possible reasons:</p>
                                                        <ul>
                                                          <li>User Name do not match</li>
                                                          <li>User Name contains illegal characters</li>
                                                          <li>SNMP User on PDU has not been enabled</li>
                                                          <li>Wrong IP address for "NMS IP / Host Name" setting stored on PDU</li>
                                                          <ul><li>Try '.$_SERVER['SERVER_ADDR'].' if you have not tried yet</i></li></ul>
                                                        </ul>';
        $errorMsg['en']['connection']['footer']     = 'Please consult <a href="https://github.com/disisto/apc-switched-rack-pdu-control-panel#3-setup-snmpv3-user-profile" title="GitHub" target="_blank" rel="noopener noreferrer nofollow" class="text-decoration-none">README.md</a> for more informations.';
        break;

    case 'No securityName specified':
        $errorMsg['en']['connection']['head']       = 'No user name specified';
        $errorMsg['en']['connection']['body']       = '<p><b>No user name</b> has been <b>specified</b> for the PDU with the IP address <b>'.$affectedPDU.'</b>.';
        $errorMsg['en']['connection']['footer']     = 'Please consult <a href="https://github.com/disisto/apc-switched-rack-pdu-control-panel#2-add-access-data" title="GitHub" target="_blank" rel="noopener noreferrer nofollow" class="text-decoration-none">README.md</a> for more informations.';
        break;

    case 'Decryption error':
        $errorMsg['en']['connection']['head']       = $erroMsgString;
        $errorMsg['en']['connection']['body']       = '<p>A <b>'.strtolower($erroMsgString).'</b> has occurred on the PDU with the IP address <b>'.$affectedPDU.'</b>. Possible reasons:</p>
                                                      <ul>
                                                        <li>Authentication Passphrase do not match or is missing</li>
                                                        <li>Authentication Passphrase contains illegal characters</li>
                                                        <li>Authentication Protocol settings do not match or is missing</li>
                                                        <li>Privacy Passphrase do not match or is missing</li>
                                                        <li>Privacy Passphrase contains illegal characters</li>
                                                        <li>Privacy Protocol settings do not match or is missing</li>
                                                        <li>Device is busy</li>
                                                      </ul>';
        $errorMsg['en']['connection']['footer']     = 'Please consult <a href="https://github.com/disisto/apc-switched-rack-pdu-control-panel" title="GitHub" target="_blank" rel="noopener noreferrer nofollow" class="text-decoration-none">README.md</a> for more informations.';
        break;

    default:
        $errorMsg['en']['connection']['head']       = 'Connection error';
        $errorMsg['en']['connection']['body']       = '<p>A connection error with the error message "<b>'.$erroMsgString.'</b>" has occurred on the PDU with the IP address <b>'.$affectedPDU.'</b>.</p>';
        $errorMsg['en']['connection']['footer']     = 'Please consult <a href="https://github.com/disisto/apc-switched-rack-pdu-control-panel#troubleshooting" title="GitHub" target="_blank" rel="noopener noreferrer nofollow" class="text-decoration-none">'.explode('/', $_SERVER['SERVER_SOFTWARE'])[0].' error log</a> for more informations.';
  }

  $errorMsg['en']['invalidIP']['head']        = 'Invalid IP address';
  $errorMsg['en']['invalidIP']['body']        = '<p>Invalid IP address (<b>"'.$affectedPDU.'"</b>) detected. Check entries on line 44, 55, etc.</p>';
  $errorMsg['en']['invalidIP']['footer']      = 'Set a valid IP address to establish a connection.';
  
  $errorMsg['en']['configuration']['head']    = 'Configuration error';
  $errorMsg['en']['configuration']['body']    = '<p>All PDUs has been set to \'false\' in the PHP file, which prevent to show any results. Check entries on line 43, 54, etc.</p>';
  $errorMsg['en']['configuration']['footer']  = 'Set at least one entry to \'true\' in order to get any results.';

  $errorMsg['en']['noASCII']['head']          = 'Unsupported characters';
  $errorMsg['en']['noASCII']['body']          = '<p>Unsupported characters were used to rename the PDU. Only <a href="https://en.wikipedia.org/wiki/ASCII" title="Wikipedia" target="_blank" rel="noopener noreferrer nofollow" class="text-decoration-none">ASCII characters</a> are allowed.</p>';
  $errorMsg['en']['noASCII']['footer']        = 'Enter a new name that contains only ASCII characters.';

  echo '
  <div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">
      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-exclamation-triangle-fill pt-1" viewBox="0 0 16 16">
        <use xlink:href="#exclamation-triangle-fill"/>
      </svg>
      '.$errorMsg['en'][$errorType]['head'].'
    </h4>
      '.$errorMsg['en'][$errorType]['body'].'
    <hr>
    <p class="mb-0">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-right-circle-fill pt-1" viewBox="0 0 16 16">
        <use xlink:href="#arrow-right-circle-fill"/>
      </svg>
      '.$errorMsg['en'][$errorType]['footer'].'
    </p>
  </div>
  ';

}


################################################
############### Stylesheet mgmt ################
################################################

function stylesheetSource($stylesheetFiles) {
  foreach ($stylesheetFiles as $fileInfo) {
    $file = $fileInfo['file'];
    $local = '  <link rel="stylesheet" href="'.$file.'">';

    if (file_exists($file)) {
        echo $local . "\n";
    } else {
        $cloud = '  <link rel="stylesheet" href="'.$fileInfo['cloud']['url'].'"';
        if (isset($fileInfo['cloud']['integrity'])) {
            $cloud .= ' integrity="'.$fileInfo['cloud']['integrity'].'"';
        }
        if (isset($fileInfo['cloud']['crossorigin'])) {
            $cloud .= ' crossorigin="'.$fileInfo['cloud']['crossorigin'].'"';
        }
        $cloud .= '>';
        echo $cloud . "\n";
    }
}
}

$stylesheetFiles = array(
  array(
      'file'  => 'assets/vendor/bootstrap/5.3.3/css/bootstrap.min.css',
      'cloud' => array(
          'url'         => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
          'integrity'   => 'sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH',
          'crossorigin' => 'anonymous'
      )
  )
);


################################################
################# Script mgmt ##################
################################################

function scriptSource($scriptFiles) {
  foreach ($scriptFiles as $fileInfo) {
      $file = $fileInfo['file'];
      $local = '  <script src="'.$file.'"></script>';

      if (file_exists($file)) {
          echo $local . "\n";
      } else {
          $cloud = '  <script src="'.$fileInfo['cloud']['url'].'"';
          if (isset($fileInfo['cloud']['integrity'])) {
              $cloud .= ' integrity="'.$fileInfo['cloud']['integrity'].'"';
          }
          if (isset($fileInfo['cloud']['crossorigin'])) {
              $cloud .= ' crossorigin="'.$fileInfo['cloud']['crossorigin'].'"';
          }
          $cloud .= '></script>';
          echo $cloud . "\n";
      }
  }
}

$scriptFiles = array(
  array(
      'file'  => 'assets/vendor/bootstrap/5.3.3/js/bootstrap.bundle.min.js',
      'cloud' => array(
          'url'         => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
          'integrity'   => 'sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz',
          'crossorigin' => 'anonymous'
      )
  ),
  array(
      'file'  => 'assets/vendor/clipboardjs/2.0.11/js/clipboard.min.js',
      'cloud' => array(
          'url'         => 'https://cdn.jsdelivr.net/npm/clipboard@2.0.11/dist/clipboard.min.js'
      )
  )
);
?>


<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head>
    <script src="https://getbootstrap.com/docs/5.3/assets/js/color-modes.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A PHP based Control panel to control multiple APC Switched Rack PDUs via SNMPv3. A single panel to switch (on, off, restart) the attached devices between different states.">
    <meta name="author" content="https://github.com/disisto">
    <meta name="theme-color" content="#712cf9">
    <title>APC | Switched Rack PDU</title>

<?= stylesheetSource($stylesheetFiles) ?>

   <style>
      .outlet-labels {
        font-size: 23px;
      }
      .form-check-input {
        clear: left;
      }
      .form-switch.form-switch-xl {
        margin-bottom: 2rem;
      }
      .form-switch.form-switch-xl .form-check-input {
        height: 2.4rem;
        width: calc(4rem + 0.75rem);
        border-radius: 5rem;
      }
      .round-btn {
        height: 2.4rem;
        width: 2.4rem;
        border-radius: 50%;
        border: 1px solid;
      }
      .reboot { 
        margin-top: -2px; 
        margin-left: -16px; 
      }
      .pencil {
        width: 200px;
        border: 1px dotted black;
      }
      h1 {
        margin: 0;
          display: inline-block;
      }
      button {
        float: right;
      }
      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }
      .switch-container {
        width: 160px;
        padding-left: 10px;
      }
   </style>

  </head>
  <body>

  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="75" height="36" viewBox="0 0 588 280.351" version="1.1">

  <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
    <symbol id="apc-logo" viewBox="0 0 588 280.351">
      <g id="surface1">
        <path style=" stroke:none;fill-rule:evenodd;fill:rgb(0%,65.098572%,31.37207%);fill-opacity:1;" d="M 6.589844 259.257813 C 6.589844 262.96875 9.492188 265.554688 13.152344 265.554688 C 17.242188 265.554688 19.933594 262.433594 19.933594 257.859375 C 19.933594 253.171875 17.242188 250.113281 13.207031 250.113281 C 9.976563 250.113281 7.714844 251.773438 6.589844 254.417969 Z M 6.589844 267.488281 C 6.589844 270.234375 5.1875 271.09375 3.253906 271.09375 L 2.441406 271.09375 C 0.347656 271.09375 -1 270.289063 -1 267.488281 L -1 234.339844 C -1 234.074219 0.347656 233.480469 1.855469 233.480469 C 4.117188 233.480469 6.589844 234.671875 6.589844 239.558594 L 6.589844 248.332031 C 8.472656 245.699219 11.378906 244.027344 15.09375 244.027344 C 22.140625 244.027344 27.25 249.355469 27.25 257.75 C 27.25 265.925781 22.296875 271.582031 14.984375 271.582031 C 10.945313 271.582031 8.042969 269.585938 6.589844 267.058594 "/>
        <path style=" stroke:none;fill-rule:evenodd;fill:rgb(0%,65.098572%,31.37207%);fill-opacity:1;" d="M 29.238281 271.847656 C 30.472656 272.386719 31.664063 272.761719 32.84375 272.972656 C 34.832031 273.347656 37.039063 273.347656 38.492188 271.847656 C 39.300781 270.925781 39.675781 270.226563 39.839844 269.804688 C 39.949219 269.53125 40.003906 269.320313 39.949219 269.15625 C 38.710938 269 37.953125 268.566406 37.46875 267.480469 L 28.433594 246.5 C 28.269531 246.070313 30.261719 244.292969 32.570313 244.292969 C 34.347656 244.292969 35.800781 245.046875 36.824219 248.0625 L 42.53125 263.078125 L 47.582031 248.117188 C 48.5 245.105469 50.117188 244.292969 51.785156 244.292969 C 53.9375 244.292969 55.980469 246.019531 55.875 246.398438 L 46.292969 271.464844 C 45.164063 274.273438 43.125 277.980469 38.0625 279.269531 C 33.917969 280.351563 23.800781 277.980469 29.238281 271.847656 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 100.144531 260.296875 C 100.144531 262.570313 99.5625 264.53125 98.402344 266.199219 C 97.242188 267.851563 95.613281 269.121094 93.539063 270.007813 C 91.453125 270.890625 89.070313 271.328125 86.386719 271.328125 C 85.628906 271.328125 84.707031 271.28125 83.613281 271.183594 C 82.519531 271.082031 81.074219 270.820313 79.261719 270.386719 C 77.453125 269.949219 75.574219 269.304688 73.621094 268.453125 L 73.621094 259.53125 C 75.453125 260.671875 77.246094 261.613281 79.015625 262.375 C 80.785156 263.125 82.679688 263.5 84.707031 263.5 C 86.570313 263.5 87.773438 263.191406 88.328125 262.570313 C 88.875 261.941406 89.148438 261.363281 89.148438 260.824219 C 89.148438 259.839844 88.675781 259.011719 87.738281 258.335938 C 86.796875 257.660156 85.429688 256.964844 83.636719 256.246094 C 81.648438 255.40625 79.90625 254.492188 78.402344 253.5 C 76.90625 252.503906 75.675781 251.265625 74.726563 249.777344 C 73.777344 248.289063 73.308594 246.527344 73.308594 244.492188 C 73.308594 242.523438 73.785156 240.75 74.75 239.171875 C 75.703125 237.578125 77.171875 236.316406 79.148438 235.375 C 81.121094 234.417969 83.53125 233.945313 86.378906 233.945313 C 88.402344 233.945313 90.28125 234.144531 92.003906 234.53125 C 93.714844 234.929688 95.136719 235.375 96.265625 235.871094 C 97.378906 236.355469 98.148438 236.746094 98.566406 237.027344 L 98.566406 245.570313 C 97.078125 244.503906 95.425781 243.550781 93.59375 242.710938 C 91.761719 241.867188 89.808594 241.445313 87.734375 241.445313 C 86.367188 241.445313 85.378906 241.710938 84.761719 242.238281 C 84.152344 242.761719 83.847656 243.414063 83.847656 244.175781 C 83.847656 244.902344 84.148438 245.523438 84.753906 246.050781 C 85.371094 246.574219 86.457031 247.210938 88.027344 247.96875 C 90.828125 249.277344 93.074219 250.46875 94.753906 251.519531 C 96.433594 252.570313 97.753906 253.78125 98.707031 255.160156 C 99.664063 256.53125 100.144531 258.25 100.144531 260.296875 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 128.980469 269.628906 C 128.1875 269.929688 127.355469 270.199219 126.480469 270.453125 C 125.605469 270.714844 124.621094 270.917969 123.53125 271.082031 C 122.429688 271.253906 121.234375 271.335938 119.9375 271.335938 C 117.015625 271.335938 114.542969 270.84375 112.511719 269.851563 C 110.496094 268.855469 108.933594 267.621094 107.839844 266.117188 C 106.75 264.621094 105.992188 263.097656 105.566406 261.53125 C 105.148438 259.976563 104.9375 258.601563 104.9375 257.414063 C 104.9375 256.226563 105.148438 254.851563 105.582031 253.289063 C 106.011719 251.730469 106.757813 250.222656 107.828125 248.773438 C 108.894531 247.320313 110.445313 246.097656 112.464844 245.121094 C 114.488281 244.144531 116.980469 243.652344 119.9375 243.652344 C 121.917969 243.652344 123.53125 243.796875 124.773438 244.074219 C 126.015625 244.355469 127.28125 244.722656 128.558594 245.203125 L 128.558594 252.625 C 125.476563 251.644531 123.066406 251.152344 121.332031 251.152344 C 119.652344 251.152344 118.15625 251.664063 116.851563 252.679688 C 115.554688 253.691406 114.898438 255.277344 114.898438 257.414063 C 114.898438 258.835938 115.226563 260.03125 115.867188 261.007813 C 116.515625 261.976563 117.335938 262.6875 118.339844 263.152344 C 119.335938 263.601563 120.332031 263.835938 121.332031 263.835938 C 122.28125 263.835938 123.351563 263.691406 124.570313 263.410156 C 125.769531 263.132813 127.238281 262.726563 128.980469 262.203125 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 162.128906 270.878906 L 152.6875 270.878906 L 152.6875 257 C 152.6875 256.894531 152.6875 256.792969 152.703125 256.695313 C 152.710938 256.601563 152.71875 256.398438 152.71875 256.082031 C 152.71875 254.804688 152.4375 253.632813 151.898438 252.558594 C 151.355469 251.484375 150.320313 250.953125 148.804688 250.953125 C 147.609375 250.953125 146.554688 251.355469 145.648438 252.15625 C 144.738281 252.945313 143.871094 253.953125 143.050781 255.160156 L 143.050781 270.878906 L 133.605469 270.878906 L 133.605469 234.464844 L 143.050781 234.464844 L 143.050781 247.960938 C 144.609375 246.296875 146.144531 245.160156 147.667969 244.554688 C 149.191406 243.953125 150.8125 243.652344 152.527344 243.652344 C 158.933594 243.652344 162.128906 247.25 162.128906 254.449219 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 196.367188 270.878906 L 186.929688 270.878906 L 186.929688 256.449219 C 186.929688 254.976563 186.683594 253.699219 186.207031 252.597656 C 185.722656 251.507813 184.667969 250.953125 183.035156 250.953125 C 182.265625 250.953125 181.589844 251.101563 181.007813 251.390625 C 180.414063 251.675781 179.886719 252.054688 179.429688 252.511719 C 178.964844 252.960938 178.574219 253.398438 178.261719 253.820313 C 177.949219 254.238281 177.621094 254.6875 177.285156 255.160156 L 177.285156 270.878906 L 167.839844 270.878906 L 167.839844 244.101563 L 177.285156 244.101563 L 177.285156 247.960938 C 178.773438 246.363281 180.265625 245.25 181.757813 244.605469 C 183.253906 243.964844 184.921875 243.652344 186.757813 243.652344 C 193.164063 243.652344 196.367188 247.484375 196.367188 255.160156 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 220.636719 255.097656 C 220.636719 253.390625 220.207031 252.023438 219.355469 251.007813 C 218.5 249.984375 217.269531 249.472656 215.671875 249.472656 C 214.078125 249.472656 212.796875 249.988281 211.820313 251.027344 C 210.84375 252.066406 210.359375 253.425781 210.359375 255.097656 Z M 229.496094 259.757813 L 210.617188 259.757813 C 210.984375 261.390625 211.886719 262.726563 213.328125 263.761719 C 214.769531 264.796875 216.402344 265.316406 218.226563 265.316406 C 221.421875 265.316406 224.953125 264.421875 228.8125 262.625 L 228.8125 268.492188 C 227.433594 269.210938 225.746094 269.867188 223.78125 270.453125 C 221.804688 271.042969 219.300781 271.335938 216.238281 271.335938 C 212.953125 271.335938 210.140625 270.714844 207.824219 269.496094 C 205.515625 268.269531 203.78125 266.609375 202.632813 264.503906 C 201.484375 262.40625 200.910156 260.023438 200.910156 257.359375 C 200.910156 254.738281 201.484375 252.378906 202.632813 250.3125 C 203.78125 248.234375 205.515625 246.601563 207.824219 245.421875 C 210.140625 244.238281 212.945313 243.652344 216.238281 243.652344 C 218.417969 243.652344 220.515625 244.125 222.542969 245.050781 C 224.566406 245.996094 226.230469 247.515625 227.535156 249.648438 C 228.839844 251.765625 229.496094 254.503906 229.496094 257.863281 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 243.292969 237.933594 C 243.292969 239.042969 242.898438 239.976563 242.117188 240.742188 C 241.335938 241.511719 240.398438 241.902344 239.320313 241.902344 C 238.582031 241.902344 237.917969 241.726563 237.324219 241.378906 C 236.714844 241.027344 236.238281 240.550781 235.890625 239.941406 C 235.546875 239.339844 235.371094 238.671875 235.371094 237.933594 C 235.371094 236.847656 235.753906 235.914063 236.523438 235.128906 C 237.289063 234.339844 238.21875 233.945313 239.320313 233.945313 C 240.398438 233.945313 241.335938 234.339844 242.117188 235.128906 C 242.898438 235.914063 243.292969 236.847656 243.292969 237.933594 M 244.050781 270.878906 L 234.613281 270.878906 L 234.613281 244.101563 L 244.050781 244.101563 Z M 244.050781 270.878906 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 269.453125 262.386719 L 269.453125 252.148438 C 268.347656 251.539063 267.472656 251.097656 266.851563 250.835938 C 266.238281 250.578125 265.5 250.441406 264.652344 250.441406 C 263.15625 250.441406 261.914063 251.074219 260.914063 252.339844 C 259.925781 253.601563 259.425781 255.316406 259.425781 257.488281 C 259.425781 259.859375 259.953125 261.589844 260.996094 262.667969 C 262.042969 263.746094 263.265625 264.285156 264.652344 264.285156 C 265.792969 264.285156 266.714844 264.109375 267.417969 263.738281 C 268.121094 263.382813 268.804688 262.933594 269.453125 262.386719 M 278.894531 270.882813 L 269.453125 270.882813 L 269.453125 268.464844 C 268.457031 269.292969 267.398438 269.980469 266.277344 270.523438 C 265.164063 271.0625 263.628906 271.335938 261.660156 271.335938 C 259.707031 271.335938 257.796875 270.851563 255.914063 269.894531 C 254.039063 268.921875 252.492188 267.425781 251.285156 265.386719 C 250.074219 263.34375 249.46875 260.773438 249.46875 257.675781 C 249.46875 255.242188 249.925781 252.96875 250.839844 250.835938 C 251.757813 248.707031 253.183594 246.976563 255.132813 245.644531 C 257.085938 244.320313 259.46875 243.652344 262.296875 243.652344 C 264.042969 243.652344 265.464844 243.847656 266.558594 244.226563 C 267.644531 244.613281 268.605469 245.105469 269.453125 245.707031 L 269.453125 234.472656 L 278.894531 234.472656 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 303.484375 255.097656 C 303.484375 253.390625 303.050781 252.023438 302.199219 251.007813 C 301.34375 249.984375 300.113281 249.472656 298.515625 249.472656 C 296.925781 249.472656 295.640625 249.988281 294.664063 251.027344 C 293.6875 252.066406 293.203125 253.425781 293.203125 255.097656 Z M 312.34375 259.757813 L 293.464844 259.757813 C 293.832031 261.390625 294.738281 262.726563 296.175781 263.761719 C 297.617188 264.796875 299.246094 265.316406 301.070313 265.316406 C 304.269531 265.316406 307.800781 264.421875 311.660156 262.625 L 311.660156 268.492188 C 310.277344 269.210938 308.59375 269.867188 306.625 270.453125 C 304.652344 271.042969 302.144531 271.335938 299.085938 271.335938 C 295.800781 271.335938 292.984375 270.714844 290.667969 269.496094 C 288.359375 268.269531 286.625 266.609375 285.476563 264.503906 C 284.328125 262.40625 283.757813 260.023438 283.757813 257.359375 C 283.757813 254.738281 284.328125 252.378906 285.476563 250.3125 C 286.625 248.234375 288.359375 246.601563 290.667969 245.421875 C 292.984375 244.238281 295.792969 243.652344 299.085938 243.652344 C 301.261719 243.652344 303.359375 244.125 305.386719 245.050781 C 307.410156 245.996094 309.078125 247.515625 310.382813 249.648438 C 311.6875 251.765625 312.34375 254.503906 312.34375 257.863281 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 340.488281 245.558594 L 337 255.011719 C 335.558594 253.644531 334.109375 252.960938 332.636719 252.960938 C 331.304688 252.960938 330.15625 253.414063 329.179688 254.320313 C 328.210938 255.226563 327.28125 256.894531 326.386719 259.335938 L 326.386719 270.878906 L 316.9375 270.878906 L 316.9375 244.101563 L 326.386719 244.101563 L 326.386719 250.550781 C 326.972656 248.964844 328.023438 247.421875 329.535156 245.914063 C 331.035156 244.410156 332.8125 243.652344 334.839844 243.652344 C 335.914063 243.652344 336.84375 243.808594 337.640625 244.117188 C 338.433594 244.4375 339.390625 244.914063 340.488281 245.558594 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 366.097656 239.832031 L 383.691406 239.832031 L 383.691406 243.78125 L 370.558594 243.78125 L 370.558594 253.28125 L 383.300781 253.28125 L 383.300781 257.296875 L 370.558594 257.296875 L 370.558594 266.9375 L 384.140625 266.9375 L 384.140625 270.878906 L 366.097656 270.878906 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 397.058594 270.878906 L 401.128906 270.878906 L 401.128906 239.832031 L 397.058594 239.832031 Z M 397.058594 270.878906 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 418.730469 258.589844 L 429.011719 258.589844 C 428.90625 256.957031 428.429688 255.691406 427.570313 254.804688 C 426.710938 253.925781 425.554688 253.480469 424.105469 253.480469 C 422.660156 253.480469 421.46875 253.925781 420.539063 254.804688 C 419.617188 255.691406 419.011719 256.957031 418.730469 258.589844 M 433.027344 260.980469 L 418.664063 260.980469 C 418.765625 262.960938 419.421875 264.53125 420.628906 265.699219 C 421.824219 266.867188 423.382813 267.449219 425.289063 267.449219 C 427.945313 267.449219 430.390625 266.628906 432.636719 264.988281 L 432.636719 268.910156 C 431.394531 269.730469 430.164063 270.316406 428.941406 270.671875 C 427.71875 271.019531 426.285156 271.207031 424.644531 271.207031 C 422.398438 271.207031 420.574219 270.734375 419.1875 269.796875 C 417.796875 268.863281 416.683594 267.605469 415.847656 266.019531 C 415.007813 264.4375 414.59375 262.605469 414.59375 260.527344 C 414.59375 257.40625 415.472656 254.875 417.242188 252.921875 C 418.996094 250.96875 421.292969 249.988281 424.105469 249.988281 C 426.816406 249.988281 428.988281 250.9375 430.59375 252.839844 C 432.214844 254.738281 433.027344 257.277344 433.027344 260.46875 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 461.414063 266.15625 L 461.414063 270.140625 C 459.371094 270.890625 457.378906 271.265625 455.425781 271.265625 C 452.207031 271.265625 449.644531 270.316406 447.726563 268.410156 C 445.8125 266.511719 444.855469 263.957031 444.855469 260.761719 C 444.855469 257.539063 445.792969 254.933594 447.664063 252.960938 C 449.527344 250.980469 451.988281 249.988281 455.039063 249.988281 C 456.101563 249.988281 457.050781 250.09375 457.894531 250.289063 C 458.742188 250.488281 459.78125 250.863281 461.023438 251.410156 L 461.023438 255.742188 C 458.949219 254.410156 457.027344 253.738281 455.261719 253.738281 C 453.414063 253.738281 451.898438 254.386719 450.710938 255.679688 C 449.523438 256.96875 448.929688 258.625 448.929688 260.625 C 448.929688 262.742188 449.570313 264.40625 450.847656 265.652344 C 452.125 266.894531 453.847656 267.515625 456.011719 267.515625 C 457.582031 267.515625 459.378906 267.066406 461.414063 266.15625 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 471.769531 253.664063 L 479.398438 246.171875 L 479.398438 250.449219 L 485.804688 250.449219 L 485.804688 254.066406 L 479.398438 254.066406 L 479.398438 264.121094 C 479.398438 266.476563 480.359375 267.644531 482.28125 267.644531 C 483.722656 267.644531 485.238281 267.175781 486.835938 266.226563 L 486.835938 269.960938 C 485.285156 270.835938 483.59375 271.265625 481.769531 271.265625 C 479.925781 271.265625 478.394531 270.726563 477.164063 269.640625 C 476.78125 269.3125 476.460938 268.945313 476.207031 268.542969 C 475.960938 268.132813 475.75 267.589844 475.578125 266.9375 C 475.410156 266.273438 475.328125 265.015625 475.328125 263.160156 L 475.328125 254.066406 L 471.769531 254.066406 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 502.765625 250.441406 L 502.765625 255.09375 L 502.988281 254.730469 C 504.957031 251.566406 506.925781 249.988281 508.882813 249.988281 C 510.429688 249.988281 512.027344 250.761719 513.691406 252.304688 L 511.546875 255.878906 C 510.136719 254.539063 508.824219 253.871094 507.613281 253.871094 C 506.304688 253.871094 505.160156 254.492188 504.207031 255.742188 C 503.242188 256.984375 502.765625 258.460938 502.765625 260.175781 L 502.765625 270.878906 L 498.695313 270.878906 L 498.695313 250.441406 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 525.242188 250.441406 L 529.3125 250.441406 L 529.3125 270.878906 L 525.242188 270.878906 Z M 527.277344 241.839844 C 527.949219 241.839844 528.515625 242.074219 528.992188 242.53125 C 529.464844 242.988281 529.703125 243.550781 529.703125 244.21875 C 529.703125 244.875 529.464844 245.433594 528.992188 245.914063 C 528.515625 246.390625 527.949219 246.625 527.277344 246.625 C 526.65625 246.625 526.105469 246.382813 525.632813 245.898438 C 525.152344 245.421875 524.914063 244.851563 524.914063 244.21875 C 524.914063 243.597656 525.152344 243.042969 525.632813 242.566406 C 526.105469 242.082031 526.65625 241.839844 527.277344 241.839844 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 559.140625 266.15625 L 559.140625 270.140625 C 557.101563 270.890625 555.105469 271.265625 553.152344 271.265625 C 549.933594 271.265625 547.371094 270.316406 545.453125 268.410156 C 543.542969 266.511719 542.585938 263.957031 542.585938 260.761719 C 542.585938 257.539063 543.519531 254.933594 545.394531 252.960938 C 547.257813 250.980469 549.714844 249.988281 552.769531 249.988281 C 553.828125 249.988281 554.777344 250.09375 555.625 250.289063 C 556.472656 250.488281 557.511719 250.863281 558.753906 251.410156 L 558.753906 255.742188 C 556.675781 254.410156 554.757813 253.738281 552.988281 253.738281 C 551.144531 253.738281 549.628906 254.386719 548.4375 255.679688 C 547.25 256.96875 546.65625 258.625 546.65625 260.625 C 546.65625 262.742188 547.296875 264.40625 548.574219 265.652344 C 549.851563 266.894531 551.574219 267.515625 553.738281 267.515625 C 555.308594 267.515625 557.105469 267.066406 559.140625 266.15625 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(21.568298%,20.783997%,20.783997%);fill-opacity:1;" d="M 1.191406 195.3125 L 559.121094 195.3125 L 559.121094 192.21875 L 1.191406 192.21875 Z M 1.191406 195.3125 "/>
        <path style=" stroke:none;fill-rule:evenodd;fill:rgb(92.941284%,10.980225%,14.117432%);fill-opacity:1;" d="M 99.800781 152.925781 L 79.953125 117.523438 L 151.683594 117.523438 L 115.660156 41.214844 L 62.84375 152.925781 L -0.335938 152.925781 L 82.386719 2.132813 L 148.933594 2.132813 L 231.046875 152.925781 "/>
        <path style=" stroke:none;fill-rule:evenodd;fill:rgb(92.941284%,10.980225%,14.117432%);fill-opacity:1;" d="M 231.355469 65.324219 L 310.101563 65.324219 C 329.957031 65.324219 337.878906 64.097656 337.878906 50.058594 C 337.878906 36.617188 331.472656 35.09375 311.011719 35.09375 L 231.046875 35.09375 L 213.328125 2.132813 L 324.445313 2.132813 C 376.964844 2.132813 396.484375 25.039063 396.484375 49.445313 C 396.484375 72.957031 378.488281 98.894531 320.796875 98.894531 L 294.527344 98.894531 L 294.527344 152.925781 L 231.046875 152.925781 L 231.046875 65.324219 "/>
        <path style=" stroke:none;fill-rule:evenodd;fill:rgb(92.941284%,10.980225%,14.117432%);fill-opacity:1;" d="M 561.035156 141.632813 C 542.105469 150.484375 518.605469 154.769531 493.570313 154.769531 C 417.863281 154.769531 391.921875 112.632813 391.921875 77.839844 C 391.921875 33.890625 430.382813 0 496.632813 0 C 520.742188 0 541.800781 3.652344 559.496094 10.375 L 559.496094 54.027344 C 541.191406 44.265625 525.3125 39.984375 506.691406 39.984375 C 474.945313 39.984375 448.996094 54.320313 448.996094 77.21875 C 448.996094 99.8125 475.554688 114.46875 507.300781 114.46875 C 525.625 114.46875 540.578125 110.816406 561.035156 101.34375 "/>
        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(13.725281%,12.156677%,12.548828%);fill-opacity:1;" d="M 578.359375 22.722656 C 579.765625 22.722656 581.023438 22.621094 581.023438 20.925781 C 581.023438 19.566406 579.785156 19.3125 578.628906 19.3125 L 576.359375 19.3125 L 576.359375 22.722656 Z M 576.359375 28.894531 L 574.699219 28.894531 L 574.699219 17.90625 L 578.890625 17.90625 C 581.480469 17.90625 582.757813 18.863281 582.757813 21.035156 C 582.757813 22.996094 581.527344 23.851563 579.90625 24.054688 L 583.03125 28.894531 L 581.179688 28.894531 L 578.28125 24.125 L 576.359375 24.125 Z M 578.386719 31.335938 C 582.679688 31.335938 586.09375 27.953125 586.09375 23.371094 C 586.09375 18.863281 582.679688 15.464844 578.386719 15.464844 C 574.007813 15.464844 570.621094 18.863281 570.621094 23.371094 C 570.621094 27.953125 574.007813 31.335938 578.386719 31.335938 Z M 568.699219 23.371094 C 568.699219 17.90625 573.144531 13.878906 578.386719 13.878906 C 583.5625 13.878906 588 17.90625 588 23.371094 C 588 28.894531 583.5625 32.921875 578.386719 32.921875 C 573.144531 32.921875 568.699219 28.894531 568.699219 23.371094 "/>
      </g>
    </symbol>
    <symbol id="check2" viewBox="0 0 16 16">
      <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
    </symbol>
    <symbol id="circle-half" viewBox="0 0 16 16">
      <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
    </symbol>
    <symbol id="moon-stars-fill" viewBox="0 0 16 16">
      <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
      <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
    </symbol>
    <symbol id="sun-fill" viewBox="0 0 16 16">
      <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
    </symbol>
    <symbol id="pencil-fill" viewBox="0 0 16 16">
      <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
    </symbol>
    <symbol id="toggle-on" viewBox="0 0 16 16">
      <path d="M5 3a5 5 0 0 0 0 10h6a5 5 0 0 0 0-10H5zm6 9a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/>
    </symbol>
    <symbol id="toggle-off" viewBox="0 0 16 16">
      <path d="M11 4a4 4 0 0 1 0 8H8a4.992 4.992 0 0 0 2-4 4.992 4.992 0 0 0-2-4h3zm-6 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8zM0 8a5 5 0 0 0 5 5h6a5 5 0 0 0 0-10H5a5 5 0 0 0-5 5z"/>
    </symbol>
    <symbol id="bootstrap-reboot" viewBox="0 0 16 16">
      <path d="M1.161 8a6.84 6.84 0 1 0 6.842-6.84.58.58 0 1 1 0-1.16 8 8 0 1 1-6.556 3.412l-.663-.577a.58.58 0 0 1 .227-.997l2.52-.69a.58.58 0 0 1 .728.633l-.332 2.592a.58.58 0 0 1-.956.364l-.643-.56A6.812 6.812 0 0 0 1.16 8z"/>
      <path d="M6.641 11.671V8.843h1.57l1.498 2.828h1.314L9.377 8.665c.897-.3 1.427-1.106 1.427-2.1 0-1.37-.943-2.246-2.456-2.246H5.5v7.352h1.141zm0-3.75V5.277h1.57c.881 0 1.416.499 1.416 1.32 0 .84-.504 1.324-1.386 1.324h-1.6z"/>
    </symbol>
    <symbol id="outlet" viewBox="0 0 16 16">
      <path d="M3.34 2.994c.275-.338.68-.494 1.074-.494h7.172c.393 0 .798.156 1.074.494.578.708 1.84 2.534 1.84 5.006 0 2.472-1.262 4.297-1.84 5.006-.276.338-.68.494-1.074.494H4.414c-.394 0-.799-.156-1.074-.494C2.762 12.297 1.5 10.472 1.5 8c0-2.472 1.262-4.297 1.84-5.006zm1.074.506a.376.376 0 0 0-.299.126C3.599 4.259 2.5 5.863 2.5 8c0 2.137 1.099 3.74 1.615 4.374.06.073.163.126.3.126h7.17c.137 0 .24-.053.3-.126.516-.633 1.615-2.237 1.615-4.374 0-2.137-1.099-3.74-1.615-4.374a.376.376 0 0 0-.3-.126h-7.17z"/>
      <path d="M6 5.5a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm4 0a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zM7 10v1h2v-1a1 1 0 0 0-2 0z"/>
    </symbol>
    <symbol id="arrow-clockwise" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
      <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
    </symbol>
    <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
      <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
    </symbol>
    <symbol id="arrow-right-circle-fill" viewBox="0 0 16 16">
      <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
    </symbol>
    <symbol id="github" viewBox="0 0 16 16">
      <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
    </symbol>
  </svg>

<div class="col-lg-8 mx-auto p-2 py-md-0">
  <header class="d-flex align-items-center pb-2 pb-sm-0 mb-5 border-bottom">
    <a href="" class="d-flex align-items-center text-body-emphasis text-decoration-none px-2">
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="75" height="36" viewBox="0 0 588 280.351" version="1.1">
        <use xlink:href="#apc-logo"/>
      </svg>
        <span class="fs-1 fw-bold ps-2 d-none d-sm-inline text-dark-emphasis">Control Panel</span>
    </a>

    <div class="ms-auto d-flex align-items-center">
    </div>

    <div class="btn-group mx-2">
      <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center"
        id="bd-theme"
        type="button"
        aria-expanded="false"
        data-bs-toggle="dropdown"
        aria-label="Toggle theme (auto)">
        <svg class="bi my-1 theme-icon-active" width="1em" height="1em"><use href="#circle-half"></use></svg>
        <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
      </button>

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-theme shadow" aria-labelledby="bd-theme-text">
        <li>
        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
          <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#sun-fill"></use></svg>
            Light
          <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
        </button>
        </li>
        <li>
        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
          <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#moon-stars-fill"></use></svg>
            Dark
          <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
        </button>
        </li>
        <li>
        <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
          <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#circle-half"></use></svg>
            Auto
          <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
        </button>
        </li>
      </ul>
    </div>
  </header>

  <main>


<?php

foreach ($apcPDUs as $key => $apcPDU) {

  // Error handling, if all PDUs are deactivated (all are set to false)
  $anytrue |= $apcPDU['active'];
  $alltrue &= $apcPDU['active'];

  // Generate list based on amout of configured/activated APC PDUs
  if ($apcPDU['active'] == true) {

    // Get name of APC PDU
    $queryApcPduName = snmp3_walk($apcPDU['ipAddress'], $apcPDU['userProfile'], $apcPDU['securityLevel'], $apcPDU['authenticationProtocol'], $apcPDU['authenticationPassphrase'], $apcPDU['privacyProtocol'], $apcPDU['privacyPassphrase'], $rPDUIdentName);

    // Check if we are getting an error while trying to rename the PDU
    if ($noASCII == true) {
      if ($affectedPDU == $apcPDU['ipAddress']) {
        $erroMsgString = null;
        errorMsg('noASCII', $erroMsgString, $affectedPDU);
      }
    }

    // Check if we are getting an error
    if (is_null($queryApcPduName) || $queryApcPduName == false) {

      // Fetch to which PDU IP the error belong to
      $affectedPDU = $apcPDU['ipAddress'];

      // Check if we are trying to communicate to valid IP
      if (filter_var($apcPDU['ipAddress'], FILTER_VALIDATE_IP)) {

        // Fetch last SNMP error message in regards of connection issues
        $session = new SNMP(SNMP::VERSION_3, $apcPDU['ipAddress'], $apcPDU['userProfile']);
        $errorFromLastSnmpRequest = $session->get($rPDUIdentName);
        $erroMsgString = substr($session->getError(), strpos($session->getError(), ":") + 2);
        $session->close();

        errorMsg('connection', $erroMsgString, $affectedPDU);
      } 
      else {
        errorMsg('invalidIP', $erroMsgString, $affectedPDU);
      }
    }
    // Continue if we are able to get the PDU Name
    else {

      // Clean up output for APC PDU name string
      if (preg_match('/"([^"]+)"/', $queryApcPduName[0], $n)) {
        $apcPduName = $n[1];   
      }

      if (implode('',$queryApcPduName));

          $session = new SNMP(SNMP::VERSION_3, $apcPDU['ipAddress'], $apcPDU['userProfile']);
          $session->setSecurity($apcPDU['securityLevel'], $apcPDU['authenticationProtocol'], $apcPDU['authenticationPassphrase'], $apcPDU['privacyProtocol'], $apcPDU['privacyPassphrase']);
          // Get number of every single APC PDU power outlet
          $queryIndex = $session->walk($rPDUOutletStatusIndex);
          // Get name of every single APC PDU power outlet
          $queryName  = $session->walk($rPDUOutletStatusOutletName);
          // Get state of every single APC PDU power outlet
          $queryState = $session->walk($rPDUOutletStatusOutletState);
          #print_r($session->getError());
          $session->close();

          $combinedArray = array_map(function($item) {
            return array_combine(['outlet', 'name', 'state'], $item);
          }, array_map(null, $queryIndex, $queryName, $queryState));

          foreach ($combinedArray as $key => $value) {
            if (preg_match('/"([^"]+)"/', $value['name'], $n)) {
              $name = $n[1];   
            }
            $status = ((substr($value['state'], -1, 1) == 1) ? 'ON' : ((substr($value['state'], -1, 1) == 2) ? 'OFF' : 'UNKNOWN' ));
            $index = trim(substr($value['outlet'], -2));
              $results[] = [
                "PDU Name"    => $apcPduName,
                "IP Address"  => $apcPDU['ipAddress'],
                "Outlet"      => $index,
                "Name"        => $name,
                "Status"      => $status,
              ];
          }

      echo'
            <div class="container px-4 pt-3 border text-light-emphasis bg-light-subtle border rounded" id="icon-grid">
              <div class="container border-bottom border-bottom-dark">
                <div class="get-quote">
                  <div class="row">

                    <div class="col-6 d-flex">
                      <a href="http://'.$apcPDU['ipAddress'].'" title="'.$apcPduName.'" target="_blank" rel="noopener noreferrer nofollow" class="text-dark-emphasis text-decoration-none">
                        <h2>'.$apcPduName.'</h2>
                      </a>
                      <span class="mx-2" data-bs-toggle="modal" data-bs-target="#pduName'.preg_replace('/\\./i', '', $apcPDU['ipAddress']).'" >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="top" title="Rename PDU">
                        <use xlink:href="#pencil-fill"/>
                        </svg>
                      </span>
                    </div>

                    <div class="col-6 d-flex flex-row-reverse">
                      <div class="btn-toolbar pb-2 ps-5" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group me-2" role="group" aria-label="First group">
                          <form action="." method="post">
                            <button type="submit" class="btn btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Turn ALL outlets ON" name="STATE" value="ON">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-toggle-on" viewBox="0 0 16 16">
                                <use xlink:href="#toggle-on"/>
                              </svg>
                            </button>
                            <input type="hidden" name="OUTLET" value="ALL">
                            <input type="hidden" name="IP" value="'.$apcPDU['ipAddress'].'">
                          </form>
                          <form action="." method="post">
                            <button type="submit" class="btn btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Turn ALL outlets OFF" name="STATE" value="OFF">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-toggle-off" viewBox="0 0 16 16">
                                <use xlink:href="#toggle-off"/>
                              </svg>
                            </button>
                            <input type="hidden" name="OUTLET" value="ALL">
                            <input type="hidden" name="IP" value="'.$apcPDU['ipAddress'].'">
                          </form>
                          <form action="." method="post">
                            <button type="submit" class="btn btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="REBOOT ALL outlets" name="STATE" value="REBOOT">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bootstrap-reboot" viewBox="0 0 16 16">
                                <use xlink:href="#bootstrap-reboot"/>
                              </svg>
                            </button>
                            <input type="hidden" name="OUTLET" value="ALL">
                            <input type="hidden" name="IP" value="'.$apcPDU['ipAddress'].'">
                          </form>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
          
          

              <!-- Modal Rename PDU -->
              <div class="modal fade" id="pduName'.preg_replace('/\\./i', '', $apcPDU['ipAddress']).'" tabindex="-1" aria-labelledby="pduName'.preg_replace('/\\./i', '', $apcPDU['ipAddress']).'Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="pduName'.preg_replace('/\\./i', '', $apcPDU['ipAddress']).'Label">Rename PDU</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="." method="post">
                      <div class="modal-body">
                        <div class="mb-3">
                          <label for="pduInputName" class="form-label">PDU Name</label>
                          <input type="text" class="form-control" id="pduInputName" name="pduInputName" placeholder="'.$apcPduName.'" aria-describedby="pduName" onkeypress="return /^[\x00-\x7F]*$/i.test(event.key)">
                          <input type="hidden" name="IP" value="'.$apcPDU['ipAddress'].'">
                          <div id="pduName'.preg_replace('/\\./i', '', $apcPDU['ipAddress']).'" class="form-text">Only ASCII character set allowed</div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- Modal Rename PDU -->

              <div class="row g-4 py-5 row-cols-1 row-cols-md-2 row-cols-lg-4 pb-3">
      ';

      foreach ($results as $result) {
        if ($result['IP Address'] == $apcPDU['ipAddress']) {
          echo'
                  <div class="col d-flex align-items-start">        

                    <svg class="bi flex-shrink-0" width="1.75em" height="1.75em" fill="currentColor" data-bs-toggle="tooltip" data-bs-placement="top" title="https://'.$_SERVER['SERVER_NAME'].'?IP='.$result['IP Address'].'&OUTLET='.$result['Outlet'].'" data-clipboard-text="https://'.$_SERVER['SERVER_NAME'].'?IP='.$result['IP Address'].'&OUTLET='.$result['Outlet'].'">
                        <use xlink:href="#outlet"/>
                    </svg>

                  <div>
                    <h4 class="fw-bold mb-0 ms-2 outlet-labels">'.$result['Name'].'</h4>
                    

                  <div class="switch-container">
                    <div class="row">
                      <div class="col">
                        <form action="." method="post">
                          <div class="form-check form-switch form-switch-xl">
                            <input class="form-check-input" type="checkbox" id="STATE" name="STATE" value="TOGGLE" onChange="this.form.submit()"  '.(($result['Status'] == "ON") ? ' checked' : '').'>
                            <input type="hidden" name="OUTLET" value="'.$result['Outlet'].'">
                            <input type="hidden" name="IP" value="'.$result['IP Address'].'">
                          </div>
                        </form>
                      </div>
                      <div class="col">
                        '.(($result['Status'] == "ON") ? '
                        <form action="." method="post">
                          <div class="form-check pt-1 ps-1">
                            <button type="submit" class="btn btn-outline-secondary round-btn" name="STATE" value="REBOOT">
                              <svg xmlns="http://www.w3.org/2000/svg" width="2.75em" height="1.75em" fill="currentColor" class="reboot">
                                <use xlink:href="#arrow-clockwise"/>
                              </svg>
                            </button>
                            <input type="hidden" name="OUTLET" value="'.$result['Outlet'].'">
                            <input type="hidden" name="IP" value="'.$result['IP Address'].'">
                          </div>
                        </form>
                        ' : '').'
                      </div>
                    </div>
                  </div>

                  </div>
                </div>
          ';
        }
      }

      echo'
              </div>
            </div>

            <br>
      ';
    }
  }
}

##### Error handling, if all PDUs are deactivated (all are set to false)
if ($alltrue) {
  // All elements are true
  # echo 'All elements are true';
}
elseif (!$anytrue) {
  // All elements are false
  # echo 'All elements are false';
  errorMsg('configuration', $erroMsgString, $affectedPDU);
}
else {
  // All elements are true
  # echo 'Mixed values'
}

?>

  </main>
  <footer class="pt-2 my-1 text-muted border-top">
    &copy; <?= ((date("Y") == 2021) ? date("Y") . ' ' : '2021-' . date("Y") . ' ') ?>
    <a href="https://github.com/disisto/apc-switched-rack-pdu-control-panel" title="GitHub" target="_blank" rel="noopener noreferrer nofollow" class="text-muted text-decoration-none"> 
      <svg class="bi" width="16" height="16"><use xlink:href="#github"/></svg>
    </a>
    | APC Switched Rack PDU Control Panel
  </footer>
</div>

<?php scriptSource($scriptFiles); ?>

  <!-- Bootstrap Tooltips -->
  <script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  </script>

  <!-- https://clipboardjs.com/ -->
  <script>
    new ClipboardJS('.bi');
  </script>

  </body>
</html>
