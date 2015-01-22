# Open Badges Backend

Backend code and schema for Open Badges.

Note this only includes the bare minimum to set up a database to hold issuer,
earner and badge data. There is no admin or user frontend.

In the database schema, we have been stricter than the standard requires in some
areas, making optional fields NOT NULL and therefore required. In addition,
some optional fields have been omitted.
