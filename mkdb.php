<?php

$pdo->exec("CREATE TABLE IF NOT EXISTS editorials (
                id INTEGER PRIMARY KEY,
                name TEXT,
                transcript TEXT,
                moment TEXT,
                duration TEXT,
                filesize INTEGER
                url text,
                mimetype TEXT
              )");

$pdo->exec("CREATE TABLE IF NOT EXISTS posts (
                id INTEGER PRIMARY KEY,
                name TEXT,
                author INTEGER,
                topic INTEGER,
                moment TEXT,
                duration TEXT,
                filesize INTEGER,
                url text,
                mimetype TEXT
              )");

  $pdo->exec("CREATE TABLE IF NOT EXISTS topics (
      id INTEGER PRIMARY KEY,
      name TEXT,
      since_when DATETIME
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
                    id INTEGER PRIMARY KEY,
                    name TEXT,
                    mail TEXT,                    
                    password TEXT
                  )");
//insert into users('name','mail','image','password') values ('Václav Černý','vasek@vasekcerny.cz','media/user/vasek.jpg','$2y$10$DWhCHXfnC2cah9ozX.ssGuMVxDdTp12jy0DTEi0Zs9.50rzjiCteir');
