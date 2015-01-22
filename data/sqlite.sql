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
