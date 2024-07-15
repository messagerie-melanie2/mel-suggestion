create table "suggestions" ("id" bigserial primary key not null, "title" varchar(255) not null, "description" text not null, "user_email" varchar(255), "user_lastname" varchar(255) null, "user_firstname" varchar(255) null,"comment" text, "state" varchar(255) not null, "instance" varchar(255) not null, "created_at" timestamp(0) without time zone null, "updated_at" timestamp(0) without time zone null);

create index "suggestions_instance_index" on "suggestions" ("instance");

create table "votes" ("id" bigserial primary key not null, "user_email" varchar(255) not null, "suggestion_id" bigint not null, "created_at" timestamp(0) without time zone null, "updated_at" timestamp(0) without time zone null, "type" TEXT DEFAULT '"up"');
