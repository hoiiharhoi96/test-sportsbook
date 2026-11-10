CREATE TABLE IF NOT EXISTS sports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS countries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS tournaments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sport_id INT NOT NULL,
    country_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    CONSTRAINT country_id_sport_id_uq UNIQUE (country_id, sport_id),
    FOREIGN KEY (sport_id) REFERENCES sports(id) ON DELETE CASCADE,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    external_id INT NOT NULL UNIQUE,
    tournament_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    start_time DATETIME NOT NULL,
    FOREIGN KEY (tournament_id) REFERENCES tournaments(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS scopes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    number INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    CONSTRAINT event_id_type_number_uq UNIQUE (event_id, type, number),
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS markets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    scope_id INT NOT NULL,
    type_parameter DECIMAL(10,3) DEFAULT NULL,
    type VARCHAR(50) NOT NULL,
    FOREIGN KEY (scope_id) REFERENCES scopes(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS outcomes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    market_id INT NOT NULL,
    external_id BIGINT NOT NULL UNIQUE,
    odds DECIMAL(10,3) NOT NULL,
    FOREIGN KEY (market_id) REFERENCES markets(id) ON DELETE CASCADE
);
