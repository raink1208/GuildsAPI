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

INSERT INTO guilds (id, name, level, exp) VALUES (:id, :name, :level, :exp);

        -- #}

        -- #{ save

UPDATE guilds SET level=:level, exp=:exp WHERE id=:guild_id;

        -- #}

    -- #}

-- #}