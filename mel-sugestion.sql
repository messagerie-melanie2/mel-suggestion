CREATE TABLE IF NOT EXISTS "Sugestion".suggestions
(
    id integer NOT NULL ,
    title text COLLATE pg_catalog."default",
    description text COLLATE pg_catalog."default",
    user_email text COLLATE pg_catalog."default",
    created_date date,
    state integer NOT NULL,
    instance text COLLATE pg_catalog."default",
    "update-date" date,
    CONSTRAINT suggestions_pkey PRIMARY KEY (id)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS "Sugestion".suggestions
    OWNER to root;
-- Index: information_suggestion

-- DROP INDEX IF EXISTS "Sugestion".information_suggestion;

CREATE UNIQUE INDEX IF NOT EXISTS information_suggestion
    ON "Sugestion".suggestions USING btree
    (title COLLATE pg_catalog."default" ASC NULLS LAST, description COLLATE pg_catalog."default" ASC NULLS LAST, instance COLLATE pg_catalog."default" ASC NULLS LAST)
    TABLESPACE pg_default;
-- Index: suivi_suggestion

-- DROP INDEX IF EXISTS "Sugestion".suivi_suggestion;

CREATE INDEX IF NOT EXISTS suivi_suggestion
    ON "Sugestion".suggestions USING btree
    (state ASC NULLS LAST, user_email COLLATE pg_catalog."default" ASC NULLS LAST, instance COLLATE pg_catalog."default" ASC NULLS LAST)
    TABLESPACE pg_default;
  
CREATE SEQUENCE IF NOT EXISTS suggestions.incremente
    CYCLE
    INCREMENT 1
    START 1
    MINVALUE 1
    MAXVALUE 999999
    CACHE 1
    OWNED BY suggestions.id;

ALTER SEQUENCE suggestions.incremente
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

TABLESPACE pg_default;

ALTER TABLE IF EXISTS "Sugestion".votes
    OWNER to root;
-- Index: vote_information

-- DROP INDEX IF EXISTS "Sugestion".vote_information;

CREATE INDEX IF NOT EXISTS vote_information
    ON "Sugestion".votes USING btree
    (user_email COLLATE pg_catalog."default" ASC NULLS LAST, sugestion_id ASC NULLS LAST)
    TABLESPACE pg_default;
    CREATE SEQUENCE IF NOT EXISTS suggestions.incrementeid-vote
    CYCLE
    INCREMENT 1
    START 1
    MINVALUE 1
    MAXVALUE 999999
    CACHE 1
    OWNED BY vote.id;

ALTER SEQUENCE suggestions.incrementeid-vote