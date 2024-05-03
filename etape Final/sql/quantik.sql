DROP TABLE IF exists QuantikGame;
DROP TABLE IF exists Player;

CREATE TABLE Player (
                        id serial PRIMARY KEY,
                        name VARCHAR(255) UNIQUE NOT NULL,
						elo int NOT NULL DEFAULT 0
);

CREATE TABLE QuantikGame(
                            gameId serial PRIMARY KEY,
                            playerOne int NOT NULL REFERENCES Player(id),
                            playerTwo int NULL REFERENCES Player(id),
                            gameStatus VARCHAR(100) NOT NULL DEFAULT 'constructed' CHECK ( gameStatus IN ('constructed', 'initialized', 'waitingForPlayer', 'finished')),
                            json text NOT NULL,
                            CONSTRAINT players CHECK ( playerOne<>playerTwo)
);



/* my sql version

CREATE TABLE Player (
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  name VARCHAR(255),
  elo int NOT NULL DEFAULT 0
);

CREATE TABLE QuantikGame(
                            gameId int PRIMARY KEY NOT NULL AUTO_INCREMENT,
                            playerOne int NOT NULL REFERENCES Player(id),
                            playerTwo int NULL REFERENCES Player(id),
                            gameStatus VARCHAR(100) NOT NULL DEFAULT 'constructed' CHECK ( gameStatus IN ('constructed', 'initialized', 'waitingForPlayer', 'finished')),
                            json text NOT NULL,
                            CONSTRAINT players CHECK ( playerOne<>playerTwo)
);

insert into player (name, elo) values ("Quentin", 1512);
insert into player (name, elo) values ("Mermet", 1123);
insert into player (name, elo) values ("Didier", 114);
insert into player (name, elo) values ("Jean", 154);
insert into player (name, elo) values ("Paul", 452);
insert into player (name, elo) values ("jack", 875);
insert into player (name, elo) values ("Michel", 845);
