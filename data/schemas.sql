-- Drop tables in reverse order of creation, otherwise
-- we may run into foreign key constraints
DROP TABLE IF EXISTS earned_badges;
DROP TABLE IF EXISTS earners;
DROP TABLE IF EXISTS available_badges;
DROP TABLE IF EXISTS issuers;

CREATE TABLE issuers
(
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL,
  url TEXT NOT NULL,
  description TEXT NOT NULL,
  image TEXT,
  email TEXT
);

CREATE TABLE available_badges
(
  id INTEGER PRIMARY KEY,
  issuer_id INTEGER NOT NULL,
  name TEXT NOT NULL,
  description TEXT NOT NULL,
  image TEXT NOT NULL,
  criteria TEXT NOT NULL,
  FOREIGN KEY (issuer_id) REFERENCES issuers(id)
);

CREATE TABLE earners
(
  id INTEGER PRIMARY KEY,
  identity TEXT NOT NULL UNIQUE,
  hashed INTEGER NOT NULL DEFAULT 1,
  type TEXT NOT NULL
);

CREATE TABLE earned_badges
(
  uid TEXT PRIMARY KEY,
  earner_id INTEGER NOT NULL,
  badge_id INTEGER NOT NULL,
  verification_type TEXT NOT NULL,
  verification_url TEXT NOT NULL,
  issued TEXT NOT NULL,
  image TEXT,
  evidence TEXT,
  expires TEXT,
  revoked TEXT,
  revoked_reason TEXT,
  FOREIGN KEY (earner_id) REFERENCES earners(id),
  FOREIGN KEY (badge_id) REFERENCES available_badges(id)
);
