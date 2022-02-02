-- Table: Sugestion.suggestions

-- DROP TABLE IF EXISTS "Sugestion".suggestions;

CREATE TABLE IF NOT EXISTS "Sugestion".suggestions
(
    id integer NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 10000 CACHE 1 ),
    title text COLLATE pg_catalog."default",
    description text COLLATE pg_catalog."default",
    user_email text COLLATE pg_catalog."default",
    "created-date" date,
    state integer NOT NULL,
    instance text COLLATE pg_catalog."default",
    "update-date" date,
    CONSTRAINT suggestions_pkey PRIMARY KEY (id)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS "Sugestion".suggestions
    OWNER to root;
-- Table: Sugestion.suggestions

-- DROP TABLE IF EXISTS "Sugestion".suggestions;

CREATE TABLE IF NOT EXISTS "Sugestion".suggestions
(
    id integer NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 10000 CACHE 1 ),
    title text COLLATE pg_catalog."default",
    description text COLLATE pg_catalog."default",
    user_email text COLLATE pg_catalog."default",
    "created-date" date,
    state integer NOT NULL,
    instance text COLLATE pg_catalog."default",
    "update-date" date,
    CONSTRAINT suggestions_pkey PRIMARY KEY (id)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS "Sugestion".suggestions
    OWNER to root;