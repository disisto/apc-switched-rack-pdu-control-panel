## APC Switched Rack PDU Control Panel
A PHP based Control Panel to control multiple APC Switched Rack PDUs via SNMPv3. A single panel to switch (on, off, restart) the attached devices between different states.

<img src="https://github.com/disisto/apc-switched-rack-pdu-control-panel/raw/main/img/0_apc_pdu_control_panel.gif">

---

## Content


- [Requirements](#requirements)
- [Quick Install](#quick-install)
- [PDU Configuration](#pdu-configuration)
  - [Enable SNMPv3 on PDU](#1-enable-snmpv3-on-pdu)
  - [Choose profile from user profiles list](#2-choose-profile-from-user-profiles-list)
  - [Setup SNMPv3 user profile](#3-setup-snmpv3-user-profile)
  - [Choose profile from access control list](#4-choose-profile-from-access-control-list)
  - [Enable SNMP user](#5-enable-snmp-user)
- [Script adaptation](#script-adaptation)
  - [Edit file](#1-edit-file)
  - [Add access data](#2-add-access-data)
  - [Add additional PDU](#3-add-additional-pdu)
  - [Upload file](#4-upload-file)
- [Server Configuration](#server-configuration)
  - [Check if PHP is installed](#1-check-if-php-is-installed)
  - [Check if PHP module php-snmp is installed](#2-check-if-php-module-php-snmp-is-installed)
  - [Firewall configuration](#3-firewall-configuration)
- [Troubleshooting](#troubleshooting)

---

## Requirements
+ Web server with <a href="https://github.com/php/php-src">PHP</a>
+ PHP module: `php-snmp`
+ APC Switched Rack PDU(s) with enabled SNMPv3 
  * Tested with APC Switched Rack PDU <a href="https://www.apc.com/shop/my/en/products/Rack-PDU-Switched-1U-12A-208V-10A-230V-8-C13/P-AP7920">AP7920</a> and <a href="https://www.apc.com/shop/my/en/products/Rack-PDU-Switched-1U-12A-208V-10A-230V-8-C13/P-AP7921">AP7921</a> on EOL firmware `v3.9.2`

---

## Quick Install
No&nbsp;🚀 &nbsp;science: Upload a single PHP file on a Webserver, enter the PDU IP address and the SNMPv3 access data with an editor and the script is ready for use.

---

## PDU Configuration
### 1. Enable SNMPv3 on PDU
`Administration` -> `Network` -> `SNMPv3: access` -> Tick checkbox -> Confirm by clicking on `Apply`

<img src="https://github.com/disisto/apc-switched-rack-pdu-control-panel/raw/main/img/3.1_enable_snmp_v3.png">


### 2. Choose profile from user profiles list
`Administration` -> `Network` -> `SNMPv3: user profiles` -> choose profile from list

<img src="https://github.com/disisto/apc-switched-rack-pdu-control-panel/raw/main/img/3.2_choose_profile_from_list.png">

### 3. Setup SNMPv3 user profile<br>
`Administration` -> `Network` -> `SNMPv3: user profiles` -> User Profiles

| Fields                      | Description | Exceptions |
| --------------------------- | ----------- | ----------- |
| `User Name`                 | The SNMP user name can contain up to 32<br> characters in length and include any combination<br> of alphanumeric characters (uppercase letters,<br> lowercase letters, and numbers). | ⚠️ &nbsp;Spaces and special characters are not allowed. |
| `Authentication Passphrase` | The password must be 15-32 ASCII characters long. | ⚠️ &nbsp;Special characters are not allowed.|
| `Authentication Protocol`   | Enable `MD5` |
| `Privacy Passphrase`        | The password must be 15-32 ASCII characters long. | ⚠️ &nbsp;Special characters are not allowed.|
| `Privacy Protocol`          | Enable `DES`|

<img src="https://github.com/disisto/apc-switched-rack-pdu-control-panel/raw/main/img/3.3_setup_snmp_v3_user_profile.gif">

ℹ️ &nbsp;The authentication via `Authentication Passphrase` and via `Privacy Passphrase` is optional. Leave both fields empty, if you don't want to use it.

ℹ️ &nbsp;There is an option to authentication via `Authentication Passphrase` only. If you want to go this route, leave the field for `Privacy Passphrase` empty.

ℹ️ &nbsp;If you want to authenticate via `Authentication Passphrase` AND via `Privacy Passphrase`, you have to enter the passphrase at the same time. Otherwise if you do it step by step (enter and save, enter and save) you will delete the previous entry.

### 4. Choose profile from access control list<br>
`Administration` -> `Network` -> `SNMPv3: access control` -> choose profile from list

<img src="https://github.com/disisto/apc-switched-rack-pdu-control-panel/raw/main/img/3.4_choose_profile_from_list.png">

###  5. Enable SNMP user<br>
`Administration` -> `Network` -> `SNMPv3: access control` -> User Profiles

<img src="https://github.com/disisto/apc-switched-rack-pdu-control-panel/raw/main/img/3.5_enable_snmp_user.png">

ℹ️ &nbsp;It is recommended to enable `NMS IP/Host Name` when the script is running as expected without this option. A wrong entry causes a timeout and the script can't feedback why it will fail, which makes troubleshooting difficult.

---

## Script adaptation

### 1. Edit file

Open the PHP file with a text editor of your choice

### 2. Add access data

Between line 40 - 47 you have to enter the access data you have entered in the PDU before

<img src="https://github.com/disisto/apc-switched-rack-pdu-control-panel/raw/main/img/4.2_php_script.png">

| PHP script                                  | APC Control Panel         | Description   |
| -------------                               | -------------             | ------------- |
| $apcPDUs['001']['active']                   | n/a                       | By setting it to `true`, you activate the script to query the PDU with the underlying access data. The query is deactivated with `false`.  |
| $apcPDUs['001']['ipAddress']                | n/a                       | Insert the IPv4 address where the PDU can be reached. |
| $apcPDUs['001']['userProfile']              | User Name                 | Enter the User Name that you previously saved in the PDU.  |
| $apcPDUs['001']['authenticationPassphrase'] | Authentication Passphrase | Enter the Authentication Passphrase that you previously saved in the PDU. |
| $apcPDUs['001']['authenticationProtocol']   | Authentication Protocol   | Leave this option untouched. Make sure that you have activated the option in the PDU. |
| $apcPDUs['001']['privacyPassphrase']        | Privacy Passphrase        | Enter the Privacy Passphrase that you previously saved in the PDU. |
| $apcPDUs['001']['privacyProtocol']          | Privacy Protocol          | Leave this option untouched. Make sure that you have activated the option in the PDU. |
| $apcPDUs['001']['securityLevel']            | Privacy Protocol          | With this option you can specify which authentication should be used:<br><br>`noAuthNoPriv` = No Authentication & Privacy Passphrase are used. Just User Name.<br><br>`authNoPriv` = Combination of User Name & Authentication Passphrase are used.<br><br>`authPriv` = Combination of User Name, Authentication & Privacy Passphrase are used.<br><br>ℹ️ &nbsp;When you try to connect for the first time, start with the first option `noAuthNoPriv`. If the connection is successfully established, switch to the next higher level. This should speed up any troubleshooting.|

### 3. Add additional PDU 
Between line 50 - 57 you can repeat the step above for a second PDU

In case you want to manage more than two PDUs, just copy the block between line 50 and 57, replace the value 002 against 003 in the appropiate variable for the new block and add the access data for the additional PDU.

<img src="https://github.com/disisto/apc-switched-rack-pdu-control-panel/raw/main/img/4.3_php_script.gif">

ℹ️ &nbsp;Depending on how many PDUs you want to control at the end, it can be beneficial to adjust the front end which is based on [Bootstrap 5](https://github.com/twbs/bootstrap). The current layout is designed to manage 2-3 PDUs that can be easily operated from an iPad.

### 4. Upload file

Upload the file to the web directory

ℹ️ &nbsp;Rename the file name if a different index.php is already existing.<br>

---

## Server Configuration

### 1. Check if PHP is installed

```
php -v
```
Otherwise install PHP

> Debian/Ubuntu/Raspian
```
sudo apt update -y && sudo apt upgrade -y && sudo apt install php php-snmp -y
```


> CentOS/RHEL/Fedora
```
sudo yum update -y && sudo yum upgrade -y && sudo yum install php php-snmp -y
```
or
```
sudo dnf update
```
```
sudo dnf upgrade
```
```
sudo dnf install php php-snmp
```

ℹ️ &nbsp;Technically the script should work with `PHP 5` and higher. However it has been never tested on a different version than `PHP 7.4`

### 2. Check if PHP module `php-snmp` is installed

```
php -m | grep -i snmp
```
Otherwise install PHP module `php-snmp`

> Debian/Ubuntu/Raspian
```
sudo apt update -y && sudo apt upgrade -y && sudo apt install php-snmp -y
```


> CentOS/RHEL/Fedora
```
sudo yum update -y && sudo yum upgrade -y && sudo yum install php-snmp -y
```
or
```
sudo dnf update
```
```
sudo dnf upgrade
```
```
sudo dnf install php-snmp
```

### 3. Firewall configuration

If a firewall is set up on the web server, in order to communicate with the PDUs the firewall need to be adjusted accordingly.

SNMP use the following ports:

- 161 (UDP)
- 162 (UDP)

---

## Troubleshooting

The code is kept very simple and offers no handling to deal with possible errors that occur.

If you haven't specify an ErrorLog directive on your Web server configuration, issues should be logged in the following log:

> Apache
```
sudo tail -f /var/log/apache/error.log
```
or
```
sudo tail -f /var/log/httpd/error.log
```

> Nginx
```
sudo tail -f /var/log/nginx/error.log
```

Alternatively show errors via the PHP error reporting:

Add the following code at the real beginning of the script:

```
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```

Depending on the server configuration it may be need to enable error reporting via `php.ini`:

```
display_errors = On

display_startup_errors = On
```

ℹ️ &nbsp;This options are global and affects all PHP projects on the Web server.



---
This project is not affiliated with <a href="https://www.apc.com/">APC by Schneider Electric</a>.<br>
All mentioned trademarks are the property of their respective owners.
