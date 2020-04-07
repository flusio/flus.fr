CREATE TABLE payments (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    created_at datetime NOT NULL,
    completed BOOLEAN NOT NULL DEFAULT FALSE,
    email TEXT NOT NULL,
    amount INTEGER NOT NULL,
    payment_intent_id TEXT
);
