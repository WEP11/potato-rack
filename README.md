# potato-rack
Potato Rack is an attempt at a lightweight, PostgreSQL based IT inventory manager that is compatible with SSO solutions (mainly phpCAS). The main components will be:

- Server inventory management
- Server rack space management
- Network interface and hostname management
- Network interface firewall rules
- Multiple Authentication Methods (None, htaccess, or phpCAS)

The firewall rules table will track by interface. You might be wondering why there would be a separate table of rules from the one provided by the server firewall software? Well at our institution we submit firewall requests processed by our central IT staff. We have no access to rules that they have in their system, so my recommendation was logging the our requests in our records to make sure we are aware of ports opened by our IT staff.
