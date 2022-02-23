CREATE TABLE IF NOT EXISTS "Sugestion".suggestions (
    id integer NOT NULL,
    title text COLLATE pg_catalog."default",
    description text COLLATE pg_catalog."default",
    user_email text COLLATE pg_catalog."default",
    created_date date,
    state integer NOT NULL,
    instance text COLLATE pg_catalog."default",
    "update-date" date,
    CONSTRAINT suggestions_pkey PRIMARY KEY (id)
) TABLESPACE pg_default;



-- Index: information_suggestion
-- DROP INDEX IF EXISTS "Sugestion".information_suggestion;
CREATE UNIQUE INDEX IF NOT EXISTS  identities_suggestion_id_idx ON "Sugestion".suggestions USING btree (
    instance COLLATE pg_catalog."default" ASC NULLS LAST
) TABLESPACE pg_default;

CREATE SEQUENCE IF NOT EXISTS suggestions_seq NO MAXVALUE NO MINVALUE INCREMENT 1 CACHE 1 OWNED BY suggestions.id;

-- DROP TABLE IF EXISTS "Sugestion".votes;
CREATE TABLE IF NOT EXISTS "Sugestion".votes (
    id integer NOT NULL,
    user_email text COLLATE pg_catalog."default",
    voting_day date,
    sugestion_id integer,
    CONSTRAINT votes_pkey PRIMARY KEY (id),
    CONSTRAINT "vote-suggestion" FOREIGN KEY (sugestion_id) REFERENCES "Sugestion".suggestions (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION
) TABLESPACE pg_default;

ALTER TABLE
    IF EXISTS "Sugestion".votes OWNER to root;

-- Index: vote_information
-- DROP INDEX IF EXISTS "Sugestion".vote_information;
CREATE INDEX IF NOT EXISTS identities_suggestion_id_idx ON "Sugestion".votes USING btree (
    sugestion_id ASC NULLS LAST
) TABLESPACE pg_default;

CREATE SEQUENCE IF NOT EXISTS vote-seq - vote NO MAXVALUE NO MINVALUE INCREMENT 1 CACHE 1 OWNED BY vote.id;
