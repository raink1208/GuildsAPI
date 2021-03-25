-- #! sqlite

-- #{ guildsql

    -- #{ init
        -- #{ guilds
CREATE TABLE IF NOT EXISTS guilds(id INTEGER NOT NULL PRIMARY KEY, name TEXT NOT NULL UNIQUE, level INTEGER NOT NULL, exp INTEGER NOT NULL);
        -- #}

        -- #{ players
CREATE TABLE IF NOT EXISTS players(id TEXT PRIMARY KEY, guild_id INTEGER NOT NULL, permission INTEGER NOT NULL);
        -- #}
    -- #}

    -- #{ guild

        -- #{ create
        -- #:id int
        -- #:name string
        -- #:level int
        -- #:exp int

INSERT INTO guilds (id, name, level, exp) VALUES (:id, :name, :level, :exp);

        -- #}

        -- #{ save
        -- #:guild_id int
        -- #:level int
        -- #:exp int

UPDATE guilds SET level=:level, exp=:exp WHERE id=:guild_id;

        -- #}

    -- #}

    -- #{ player

        -- #{ create
        -- #:id string
        -- #:guild_id int
        -- #:permission int

INSERT INTO players (id, guild_id, permission) VALUES (:id, :guild_id, :permission);

        -- #}

        -- #{ save
        -- #:name string
        -- #:guild_id int
        -- #:permission int

UPDATE players SET guild_id=:guild_id, permission=:permission WHERE id=:name;

        -- #}

    -- #}

-- #}