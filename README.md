## APC Switched Rack PDU Control Panel
A PHP based Control Panel to control multiple APC Switched Rack PDUs via SNMPv3. A single panel to switch (on, off, restart) the attached devices between different states.

<img src="https://github.com/disisto/apc-switched-rack-pdu-control-panel/raw/main/img/0_apc_pdu_control_panel.gif">

---

## Content


- [Requirements](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#requirements)
- [Quick Install](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#quick-install)
- [PDU Configuration](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#pdu-configuration)
  - [Enable SNMPv3 on PDU](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#1-enable-snmpv3-on-pdu)
  - [Choose profile from user profiles list](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#2-choose-profile-from-user-profiles-list)
  - [Setup SNMPv3 user profile](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#3-setup-snmpv3-user-profile)
  - [Choose profile from access control list](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#4-choose-profile-from-access-control-list)
  - [Enable SNMP user](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#5-enable-snmp-user)
- [Script adaptation](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#script-adaptation)
  - [Edit file](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#1-edit-file)
  - [Add access data](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#2-add-access-data)
  - [Add additional PDU](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#3-add-additional-pdu)
  - [Upload file](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#4-upload-file)
- [Server Configuration](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#server-configuration)
  - [Check if PHP is installed](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#1-check-if-php-is-installed)
  - [Check if PHP module php-snmp is installed](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#2-check-if-php-module-php-snmp-is-installed)
  - [Firewall configuration](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#3-firewall-configuration)
- [Troubleshooting](https://github.com/disisto/apc-switched-rack-pdu-control-panel/wiki#troubleshooting)

---

## Requirements
+ Web server with <a href="https://github.com/php/php-src">PHP</a>
+ PHP module: `php-snmp`
+ APC Switched Rack PDU(s) with enabled SNMPv3 
  * Tested with APC Switched Rack PDU <a href="https://www.apc.com/us/en/product/AP7901/rack-pdu-switched-1u-20a-120v-8520/">AP7901</a> on EOL firmware `v3.9.3`
  * Tested with APC Switched Rack PDU <a href="https://www.apc.com/shop/my/en/products/Rack-PDU-Switched-1U-12A-208V-10A-230V-8-C13/P-AP7920">AP7920</a> and <a href="https://www.apc.com/shop/my/en/products/Rack-PDU-Switched-1U-12A-208V-10A-230V-8-C13/P-AP7921">AP7921</a> on EOL firmware `v3.9.2`
  * Tested with APC Switched Rack PDU <a href="https://www.apc.com/shop/my/en/products/Rack-PDU-Switched-1U-12A-208V-10A-230V-8-C13/P-AP7920B">AP7920B</a> on latest firmware `v6.5.6`

---

## Run with Docker

Make sure you have [Docker Engine installed](https://docs.docker.com/engine/install/)

```shell
git clone https://github.com/disisto/apc-switched-rack-pdu-control-panel.git
cd apc-switched-rack-pdu-control-panel
cp config.example.php config.php
# Update `config.php` with appropriate values for your PDU(s)
docker compose up -d
```

Browse to http://localhost:8080, and the control panel should be displayed.

## Quick Install
No&nbsp;🚀&nbsp;science: Upload two PHP files on a Webserver. Enter the PDU IP address and the SNMPv3 access data with an editor in `config.php`, and the script is ready for use.

---
This project is not affiliated with <a href="https://www.apc.com/">APC by Schneider Electric</a>.<br>
All mentioned trademarks are the property of their respective owners.
