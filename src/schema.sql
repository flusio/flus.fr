CREATE TABLE tokens (
    token TEXT PRIMARY KEY,
    created_at TEXT NOT NULL,
    expired_at TEXT NOT NULL,
    invalidated_at TEXT
);

CREATE TABLE accounts (
    id TEXT PRIMARY KEY,
    created_at TEXT NOT NULL,
    expired_at TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    access_token TEXT,

    preferred_frequency TEXT NOT NULL DEFAULT 'month',
    preferred_payment_type TEXT NOT NULL DEFAULT 'card',
    preferred_service TEXT NOT NULL DEFAULT 'flusio',
    reminder BOOLEAN NOT NULL DEFAULT false,

    address_first_name TEXT,
    address_last_name TEXT,
    address_address1 TEXT,
    address_postcode TEXT,
    address_city TEXT,
    address_country TEXT,

    FOREIGN KEY (access_token) REFERENCES tokens(token) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE payments (
    id TEXT PRIMARY KEY NOT NULL,
    created_at TEXT NOT NULL,
    completed_at TEXT,
    is_paid BOOLEAN NOT NULL DEFAULT false,
    type TEXT NOT NULL,

    invoice_number TEXT,
    email TEXT NOT NULL,
    amount INTEGER NOT NULL,
    frequency TEXT,
    company_vat_number TEXT,
    credited_payment_id TEXT,

    address_first_name TEXT NOT NULL,
    address_last_name TEXT NOT NULL,
    address_address1 TEXT NOT NULL,
    address_postcode TEXT NOT NULL,
    address_city TEXT NOT NULL,
    address_country TEXT NOT NULL DEFAULT "FR",

    payment_intent_id TEXT,
    session_id TEXT,

    account_id TEXT NOT NULL,

    FOREIGN KEY (credited_payment_id) REFERENCES payments(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (account_id) REFERENCES accounts(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE pot_usages (
    id TEXT PRIMARY KEY NOT NULL,
    created_at TEXT NOT NULL,
    completed_at TEXT,
    is_paid BOOLEAN NOT NULL DEFAULT true,

    amount INTEGER NOT NULL,
    frequency TEXT,

    account_id TEXT,

    FOREIGN KEY (account_id) REFERENCES accounts(id) ON UPDATE CASCADE ON DELETE SET NULL
);
