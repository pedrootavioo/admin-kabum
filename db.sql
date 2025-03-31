SET FOREIGN_KEY_CHECKS = 0;
drop table if exists users;
drop table if exists persons;
drop table if exists addresses;
drop table if exists clients;
SET FOREIGN_KEY_CHECKS = 1;

create table if not exists persons
(
    id         bigint auto_increment
        primary key,
    name       varchar(255)                          null,
    email      varchar(255)                          null,
    birthdate  date                                  null,
    document   varchar(11)                           null,
    identity   varchar(255)                          null,
    phone      varchar(14)                           null,
    created_at timestamp default current_timestamp() null,
    updated_at timestamp                             null on update current_timestamp()
);

create table if not exists addresses
(
    id           bigint auto_increment
        primary key,
    person_id    bigint                                 not null,
    zipcode      varchar(9)                             null,
    street       varchar(255)                           null,
    number       varchar(255)                           null,
    city         varchar(255)                           null,
    ibge_code    int                                    null,
    state        varchar(255)                           null,
    state_code   int                                    null,
    complement   varchar(255)                           null,
    neighborhood varchar(255)                           null,
    main         tinyint(1) default 1                   not null,
    created_at   timestamp  default current_timestamp() null,
    updated_at   timestamp                              null on update current_timestamp(),
    constraint addresses_persons_id_fk
        foreign key (person_id) references persons (id)
            on delete cascade
);

create table if not exists clients
(
    id         bigint auto_increment
        primary key,
    person_id  bigint                                null,
    created_at timestamp default current_timestamp() null,
    updated_at timestamp                             null on update current_timestamp(),
    constraint clients_persons_id_fk
        foreign key (person_id) references persons (id)
            on update cascade on delete cascade
);

create table if not exists users
(
    id         bigint auto_increment
        primary key,
    person_id  bigint                                null,
    password   varchar(255)                          null,
    created_at timestamp default current_timestamp() null,
    updated_at timestamp                             null on update current_timestamp(),
    constraint users_persons_id_fk
        foreign key (person_id) references persons (id)
);

INSERT INTO `persons` (`id`, `name`, `email`, `birthdate`, `document`, `identity`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'GUARDIÃO KABUM', 'email@kabum.com', NULL, '', NULL, NULL, '2025-03-31 04:57:56', NULL);

INSERT INTO `users` (`id`, `person_id`, `password`, `created_at`, `updated_at`) VALUES
(1, 1, '$2y$12$whpRQ0jsD1oQgOYAFAV1MOP6K/FzyDYbpZ3nTZTKBfCc/3zh6QT0K', '2025-03-31 04:57:56', NULL);