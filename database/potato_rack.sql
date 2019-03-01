CREATE TABLE "devices" (
  "id" int,
  "name" varchar,
  "rack_position" int,
  "rack" varchar,
  "manufacturer" varchar,
  "hardware" varchar,
  "os" varchar,
  "license_key" varchar,
  "role" varchar,
  "is_active" boolean,
  "serial" varchar,
  "asset" varchar,
  "customer" varchar,
  "service" varchar,
  "purchase_date" date,
  "purchase_price" int,
  "cost_object" varchar,
  "notes" varchar
);

CREATE TABLE "racks" (
  "id" int,
  "name" varchar,
  "size" int,
  "room" varchar,
  "is_numberedfrombottom" boolean,
  "notes" varchar
);

CREATE TABLE "software" (
  "id" int,
  "name" varchar,
  "description" varchar,
  "notes" varchar
);

CREATE TABLE "installed_software" (
  "id" int,
  "device" int,
  "software" int
);

CREATE TABLE "buildings" (
  "id" int,
  "name" varchar,
  "short_name" varchar
);

CREATE TABLE "rooms" (
  "id" int,
  "name" varchar,
  "bldg_name" varchar
);

CREATE TABLE "hardware" (
  "id" int,
  "name" varchar,
  "manufacturer" varchar,
  "size" int,
  "support_url" varchar,
  "spec_url" varchar
);

CREATE TABLE "operating_systems" (
  "id" int,
  "name" varchar,
  "developer" varchar,
  "notes" varchar
);

CREATE TABLE "organizations" (
  "id" int,
  "name" varchar,
  "description" varchar,
  "account_number" varchar,
  "is_customer" boolean,
  "is_developer" boolean,
  "is_manufacturer" boolean,
  "url" varchar,
  "notes" varchar
);

CREATE TABLE "roles" (
  "id" int,
  "name" varchar,
  "description" varchar,
  "notes" varchar
);

CREATE TABLE "service_levels" (
  "id" int,
  "name" varchar,
  "description" varchar,
  "notes" varchar
);

CREATE TABLE "users" (
  "id" int,
  "username" varchar,
  "is_super" boolean,
  "is_admin" boolean,
  "is_staff" boolean
);

CREATE TABLE "network_interfaces" (
  "id" int,
  "mac_address" varchar,
  "device" int
);

CREATE TABLE "hostnames" (
  "id" int,
  "name" varchar,
  "is_alias" boolean,
  "network_interface" int
);

CREATE TABLE "firewall_rules" (
  "id" int,
  "interface" int,
  "source" varchar,
  "port" varchar,
  "protocol" varchar,
  "its_registered" boolean,
  "is_deny" boolean,
  "comment" varchar
);

ALTER TABLE "firewall_rules" ADD FOREIGN KEY ("interface") REFERENCES "network_interfaces" ("id");

ALTER TABLE "network_interfaces" ADD FOREIGN KEY ("device") REFERENCES "devices" ("id");

ALTER TABLE "hostnames" ADD FOREIGN KEY ("network_interface") REFERENCES "network_interfaces" ("id");

ALTER TABLE "rooms" ADD FOREIGN KEY ("bldg_name") REFERENCES "buildings" ("name");

ALTER TABLE "hardware" ADD FOREIGN KEY ("manufacturer") REFERENCES "organizations" ("name");

ALTER TABLE "operating_systems" ADD FOREIGN KEY ("name") REFERENCES "organizations" ("name");

ALTER TABLE "racks" ADD FOREIGN KEY ("room") REFERENCES "rooms" ("name");

ALTER TABLE "devices" ADD FOREIGN KEY ("rack") REFERENCES "racks" ("name");

ALTER TABLE "devices" ADD FOREIGN KEY ("manufacturer") REFERENCES "organizations" ("name");

ALTER TABLE "devices" ADD FOREIGN KEY ("hardware") REFERENCES "hardware" ("name");

ALTER TABLE "devices" ADD FOREIGN KEY ("os") REFERENCES "operating_systems" ("name");

ALTER TABLE "devices" ADD FOREIGN KEY ("role") REFERENCES "roles" ("name");

ALTER TABLE "devices" ADD FOREIGN KEY ("customer") REFERENCES "organizations" ("name");

ALTER TABLE "devices" ADD FOREIGN KEY ("service") REFERENCES "service_levels" ("name");

ALTER TABLE "installed_software" ADD FOREIGN KEY ("device") REFERENCES "devices" ("id");

ALTER TABLE "installed_software" ADD FOREIGN KEY ("software") REFERENCES "software" ("id");
