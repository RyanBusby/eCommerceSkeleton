sql = """CREATE TABLE orders (id SERIAL PRIMARY KEY,
    hash VARCHAR(255) NOT NULL,
    total FLOAT NOT NULL,
    paid SMALLINT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    user_id INTEGER NOT NULL)
;
CREATE TABLE orders_products (id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL)
;
CREATE TABLE orders_products (id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL)
;
CREATE TABLE payments (id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL,
    failed SMALLINT NOT NULL,
    transaction_id VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP)
;
CREATE TABLE users (id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP)
;"""

orders_cols = [
    'id SERIAL PRIMARY KEY',
    'hash VARCHAR(255) NOT NULL',
    'total FLOAT NOT NULL',
    'paid SMALLINT NOT NULL',
    'created_at TIMESTAMP',
    'updated_at TIMESTAMP',
    'user_id INTEGER NOT NULL'
]

orders_products_cols = [
    'id SERIAL PRIMARY KEY',
    'order_id INTEGER NOT NULL',
    'product_id INTEGER NOT NULL'
]

payments_cols = [
    'id SERIAL PRIMARY KEY',
    'order_id INTEGER NOT NULL',
    'failed SMALLINT NOT NULL',
    'transaction_id VARCHAR(255)',
    'created_at TIMESTAMP',
    'updated_at TIMESTAMP'
]

products_cols = [
    'id SERIAL PRIMARY KEY',
    'title VARCHAR(255) NOT NULL',
    'slug VARCHAR(255) NOT NULL',
    'description VARCHAR(255)',
    'price FLOAT NOT NULL',
    'image VARCHAR(255)',
    'created_at TIMESTAMP',
    'updated_at TIMESTAMP',
    'samplefile VARCHAR(255)',
    'file VARCHAR(255)'
]

users_cols = [
    'id SERIAL PRIMARY KEY',
    'name VARCHAR(255)',
    'email VARCHAR(255) NOT NULL',
    'password VARCHAR(255) NOT NULL',
    'created_at TIMESTAMP',
    'updated_at TIMESTAMP'
]

base = '''CREATE TABLE %s (%s)'''

tables = [
    ('orders', orders_cols),
    ('orders_products', orders_products_cols),
    ('payments', payments_cols),
    ('products', products_cols),
    ('users', users_cols)
]

commands = []
for table_cols in tables:
    command = base % (table_cols[0], ', '.join(table_cols[1]))
    commands.append(command)
