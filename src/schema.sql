CREATE TABLE payments (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    created_at datetime NOT NULL,
    type TEXT NOT NULL,
    completed BOOLEAN NOT NULL DEFAULT FALSE,
    email TEXT NOT NULL,
    amount INTEGER NOT NULL,
    address_first_name TEXT NOT NULL,
    address_last_name TEXT NOT NULL,
    address_address1 TEXT NOT NULL,
    address_postcode TEXT NOT NULL,
    address_city TEXT NOT NULL,
    payment_intent_id TEXT,
    username TEXT,
    frequency TEXT
);
