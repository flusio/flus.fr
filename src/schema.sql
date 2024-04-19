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
    last_sync_at TEXT,

    preferred_service TEXT NOT NULL DEFAULT 'flus',
    preferred_tariff TEXT NOT NULL DEFAULT 'stability',
    reminder BOOLEAN NOT NULL DEFAULT false,

    entity_type TEXT NOT NULL DEFAULT 'natural',
    address_first_name TEXT,
    address_last_name TEXT,
    address_legal_name TEXT,
    address_address1 TEXT,
    address_postcode TEXT,
    address_city TEXT,
    address_country TEXT,
    company_vat_number TEXT,

    managed_by_id TEXT,

    FOREIGN KEY (access_token) REFERENCES tokens(token) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (managed_by_id) REFERENCES accounts(id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE payments (
    id TEXT PRIMARY KEY NOT NULL,
    created_at TEXT NOT NULL,
    completed_at TEXT,
    is_paid BOOLEAN NOT NULL DEFAULT false,
    type TEXT NOT NULL,

    invoice_number TEXT,
    amount INTEGER NOT NULL,
    quantity INTEGER NOT NULL DEFAULT 1,
    frequency TEXT,
    credited_payment_id TEXT,

    payment_intent_id TEXT,
    session_id TEXT,

    account_id TEXT NOT NULL,

    FOREIGN KEY (credited_payment_id) REFERENCES payments(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (account_id) REFERENCES accounts(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE free_renewals (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    created_at TEXT NOT NULL,
    quantity INTEGER NOT NULL DEFAULT 1
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

CREATE TABLE jobs (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    created_at TEXT NOT NULL,
    updated_at TEXT NOT NULL,
    perform_at TEXT NOT NULL,
    name TEXT NOT NULL DEFAULT '',
    args TEXT NOT NULL DEFAULT '{}',
    frequency TEXT NOT NULL DEFAULT '',
    queue TEXT NOT NULL DEFAULT 'default',
    locked_at TEXT,
    number_attempts BIGINT NOT NULL DEFAULT 0,
    last_error TEXT NOT NULL DEFAULT '',
    failed_at TEXT
);
