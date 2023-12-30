<?php
return [
    [
        'active' => false,              // Toggle Switch             :: Switch on (true) or switch off (false) to stop monitoring the PDU.
        'ipAddress' => '',              // IP address                :: Enter the IP address of the PDU
        'userProfile' => '',            // User Name                 :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> User Name | NOTE: The SNMP user name can contain up to 32 characters in length and include any combination of alphanumeric characters (uppercase letters, lowercase letters, and numbers). ⚠️  Spaces not allowed.
        'authenticationPassphrase' => '',         // Authentication Passphrase :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Authentication Passphrase | NOTE: The password must be 15-32 ASCII characters long. ⚠️ Special characters not allowed.
        'authenticationProtocol' => '',           // Authentication Protocol   :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Authentication Protocol
        'privacyPassphrase' => '',      // Privacy Passphrase        :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Privacy Passphrase | NOTE: The password must be 15-32 ASCII characters long. ⚠️ Special characters not allowed.
        'privacyProtocol' => '',        // Privacy Protocol          :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Privacy Protocol
        'securityLevel' => ''           // Security Level            :: Set desired security level: noAuthNoPriv | authNoPriv | authPriv | More detail: https://www.php.net/manual/en/function.snmp3-get.php
    ],
    [
        'active' => false,              // Toggle Switch             :: Switch on (true) or switch off (false) to stop monitoring the PDU.
        'ipAddress' => '',              // IP address                :: Enter the IP address of the PDU
        'userProfile' => '',            // User Name                 :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> User Name | NOTE: The SNMP user name can contain up to 32 characters in length and include any combination of alphanumeric characters (uppercase letters, lowercase letters, and numbers). ⚠️  Spaces not allowed.
        'authenticationPassphrase' => '',         // Authentication Passphrase :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Authentication Passphrase | NOTE: The password must be 15-32 ASCII characters long. ⚠️ Special characters not allowed.
        'authenticationProtocol' => '',           // Authentication Protocol   :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Authentication Protocol
        'privacyPassphrase' => '',      // Privacy Passphrase        :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Privacy Passphrase | NOTE: The password must be 15-32 ASCII characters long. ⚠️ Special characters not allowed.
        'privacyProtocol' => '',        // Privacy Protocol          :: Administration -> Network -> SNMPv3: user profiles -> choose profile from list -> Privacy Protocol
        'securityLevel' => ''           // Security Level            :: Set desired security level: noAuthNoPriv | authNoPriv | authPriv | More detail: https://www.php.net/manual/en/function.snmp3-get.php
    ]
];