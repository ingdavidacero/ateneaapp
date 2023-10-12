##Inserción categorías 

INSERT INTO categories (`id`, `name`, `active`) VALUES ('1', 'Historia', '1');
INSERT INTO categories (`id`, `name`, `active`) VALUES ('2', 'Geografía', '1');
INSERT INTO categories (`id`, `name`, `active`) VALUES ('3', 'Deportes', '1');
INSERT INTO categories (`id`, `name`, `active`) VALUES ('4', 'Arte', '1');
INSERT INTO categories (`id`, `name`, `active`) VALUES ('5', 'Ciencia', '1');
INSERT INTO categories (`id`, `name`, `active`) VALUES ('6', 'Entretenimiento', '1');

##Inserción niveles

INSERT INTO levels (`id`, `name`) VALUES ('1', 'Fácil');
INSERT INTO levels (`id`, `name`) VALUES ('2', 'Intermedio');
INSERT INTO levels (`id`, `name`) VALUES ('3', 'Difícil');

##Insersión estados de las partidas

INSERT INTO match_states (`id`, `name`) VALUES ('1', 'Iniciado');
INSERT INTO match_states (`id`, `name`) VALUES ('2', 'Ganado');
INSERT INTO match_states (`id`, `name`) VALUES ('3', 'Perdido');
INSERT INTO match_states (`id`, `name`) VALUES ('4', 'Desistido');

##Inserción de comodines 

INSERT INTO wildcards (`id`, `name`, `description`, `active`) VALUES ('1', '50/50', 'El juego te descartará dos opciones erróneas, dejando una opción errónea y la opción correcta. ', '1');
INSERT INTO wildcards (`id`, `name`, `description`, `active`) VALUES ('2', 'Cambio de pregunta', 'El juego te cambia la pregunta por una del mismo nivel.', '1');
INSERT INTO wildcards (`id`, `name`, `description`, `active`) VALUES ('3', 'Llamada a un amigo', 'El juego te dará la opción de la consulta a un amigo.', '1');

##Inserción de estados de tokens

INSERT INTO token_states (`id`, `name`) VALUES ('1', 'Activo');
INSERT INTO token_states (`id`, `name`) VALUES ('2', 'Inactivo');
