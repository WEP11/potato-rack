# potato-rack

<img src="https://github.com/WEP11/potato-rack/blob/master/public/images/potato-big.png" width="50%">

Potato Rack is an attempt at a lightweight, PostgreSQL based IT inventory manager that is compatible with SSO solutions (mainly phpCAS). The main components will be:

- Server inventory management
- Server rack space management
- Network interface and hostname management
- Network interface firewall rules
- Multiple Authentication Methods (None, htaccess, or phpCAS)

This was designed around a protected local network, thus there may be some security vulnerabilities for public deployments. PR's welcome!

## Requirements
* PostGreSQL
* PHP
* Apache

## Installation
1. Download the latest release
2. Extract the archive
3. Copy config/settings.ini.tmp to config/settings.ini
4. Copy config/server-directives.php.tmp to public/server-directives.php
5. Customize config/settings.ini
6. Customize public/server-directives.php
7. Deploy the database schema to your database
8. Link public to an appropriate location for your Apache instance

### For CAS Installations
* Manually add a superuser to the database
