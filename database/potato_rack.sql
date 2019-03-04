CREATE TABLE "devices" (
  "id" serial primary key,
  "name" varchar,
  "rack_position" int,
  "rack" int,
  "manufacturer" int,
  "hardware" int,
  "os" int,
  "license_key" varchar,
  "role" int,
  "is_active" boolean,
  "serial" varchar,
  "asset" varchar,
  "customer" int,
  "service" int,
  "purchase_date" date,
  "purchase_price" int,
  "cost_object" varchar,
  "notes" varchar
);

CREATE TABLE "racks" (
  "id" serial primary key,
  "name" varchar,
  "size" int,
  "room" int,
  "is_numberedfrombottom" boolean,
  "notes" varchar
);

CREATE TABLE "software" (
  "id" serial primary key,
  "name" varchar,
  "description" varchar,
  "notes" varchar
);

CREATE TABLE "installed_software" (
  "id" serial primary key,
  "device" int,
  "software" int
);

CREATE TABLE "buildings" (
  "id" serial primary key,
  "name" varchar,
  "short_name" varchar
);

CREATE TABLE "rooms" (
  "id" serial primary key,
  "name" varchar,
  "bldg" int
);

CREATE TABLE "hardware" (
  "id" serial primary key,
  "name" varchar,
  "manufacturer" int,
  "size" int,
  "support_url" varchar,
  "spec_url" varchar
);

CREATE TABLE "operating_systems" (
  "id" serial primary key,
  "name" varchar,
  "developer" int,
  "notes" varchar
);

CREATE TABLE "organizations" (
  "id" serial primary key,
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
  "id" serial primary key,
  "name" varchar,
  "description" varchar,
  "notes" varchar
);

CREATE TABLE "service_levels" (
  "id" serial primary key,
  "name" varchar,
  "description" varchar,
  "notes" varchar
);

CREATE TABLE "users" (
  "id" serial primary key,
  "username" varchar,
  "is_super" boolean,
  "is_admin" boolean,
  "is_staff" boolean
);

CREATE TABLE "network_interfaces" (
  "id" serial primary key,
  "mac_address" varchar,
  "device" int
);

CREATE TABLE "hostnames" (
  "id" serial primary key,
  "name" varchar,
  "is_alias" boolean,
  "network_interface" int
);

CREATE TABLE "firewall_rules" (
  "id" serial primary key,
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

ALTER TABLE "rooms" ADD FOREIGN KEY ("bldg") REFERENCES "buildings" ("id");

ALTER TABLE "hardware" ADD FOREIGN KEY ("manufacturer") REFERENCES "organizations" ("id");

ALTER TABLE "operating_systems" ADD FOREIGN KEY ("developer") REFERENCES "organizations" ("id");

ALTER TABLE "racks" ADD FOREIGN KEY ("room") REFERENCES "rooms" ("id");

ALTER TABLE "devices" ADD FOREIGN KEY ("rack") REFERENCES "racks" ("id");

ALTER TABLE "devices" ADD FOREIGN KEY ("manufacturer") REFERENCES "organizations" ("id");

ALTER TABLE "devices" ADD FOREIGN KEY ("hardware") REFERENCES "hardware" ("id");

ALTER TABLE "devices" ADD FOREIGN KEY ("os") REFERENCES "operating_systems" ("id");

ALTER TABLE "devices" ADD FOREIGN KEY ("role") REFERENCES "roles" ("id");

ALTER TABLE "devices" ADD FOREIGN KEY ("customer") REFERENCES "organizations" ("id");

ALTER TABLE "devices" ADD FOREIGN KEY ("service") REFERENCES "service_levels" ("id");

ALTER TABLE "installed_software" ADD FOREIGN KEY ("device") REFERENCES "devices" ("id");

ALTER TABLE "installed_software" ADD FOREIGN KEY ("software") REFERENCES "software" ("id");
