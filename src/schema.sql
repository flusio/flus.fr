CREATE TABLE payments (
    id TEXT PRIMARY KEY NOT NULL,
    created_at TEXT NOT NULL,
    type TEXT NOT NULL,
    invoice_number TEXT,
    completed_at TEXT,
    email TEXT NOT NULL,
    amount INTEGER NOT NULL,
    address_first_name TEXT NOT NULL,
    address_last_name TEXT NOT NULL,
    address_address1 TEXT NOT NULL,
    address_postcode TEXT NOT NULL,
    address_city TEXT NOT NULL,
    address_country TEXT NOT NULL DEFAULT "FR",
    payment_intent_id TEXT,
    session_id TEXT,
    username TEXT,
    frequency TEXT,
    company_vat_number TEXT
);

CREATE TABLE tokens (
    token TEXT PRIMARY KEY,
    created_at TEXT NOT NULL,
    expired_at TEXT NOT NULL,
    invalidated_at TEXT
);
