

CREATE TABLE Peliculas (
    id_pelicula INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(50) NOT NULL,
    genero VARCHAR(30) NOT NULL,
    anio YEAR NOT NULL,
    director VARCHAR(50) NOT NULL
);

CREATE TABLE Actores (
    id_actor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    nacionalidad VARCHAR(50) NOT NULL
);

CREATE TABLE Peliculas_Actores (
    id_pelicula_actor INT AUTO_INCREMENT PRIMARY KEY,
    fk_id_pelicula INT,
    fk_id_actor INT,
    FOREIGN KEY (fk_id_pelicula) REFERENCES Peliculas(id_pelicula),
    FOREIGN KEY (fk_id_actor) REFERENCES Actores(id_actor)
);
