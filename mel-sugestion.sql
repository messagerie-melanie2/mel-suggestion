-- Table: Sugestion.suggestions

-- DROP TABLE IF EXISTS "Sugestion".suggestions;

CREATE TABLE IF NOT EXISTS "Sugestion".suggestions
(
    id integer NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 10000 CACHE 1 ),
    title text COLLATE pg_catalog."default",
    description text COLLATE pg_catalog."default",
    user_email text COLLATE pg_catalog."default",
    created_date date,
    state integer NOT NULL,
    instance text COLLATE pg_catalog."default",
    "update-date" date,
    CONSTRAINT suggestions_pkey PRIMARY KEY (id)
)
-- Table: Sugestion.votes

-- DROP TABLE IF EXISTS "Sugestion".votes;

CREATE TABLE IF NOT EXISTS "Sugestion".votes
(
    id integer NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 10000 CACHE 1 ),
    user_email text COLLATE pg_catalog."default",
    voting_day date,
    sugestion_id integer,
    CONSTRAINT votes_pkey PRIMARY KEY (id),
    CONSTRAINT "vote-suggestion" FOREIGN KEY (sugestion_id)
        REFERENCES "Sugestion".suggestions (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)