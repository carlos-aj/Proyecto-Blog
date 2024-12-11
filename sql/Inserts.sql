USE blog;

INSERT INTO usuarios (nombre, apellidos, email, username, contrasena, rol) VALUES
                                                                               ('admin', 'admin', 'admin@admin.com', 'admin', '$2y$10$QPoHIZ2PQtPEPWJTlEns1OSXbpiOzuLPZIeUvo.LbD3QxEV9Kl2pW', 'admin');

INSERT INTO categorias (nombre) VALUES
                                    ('Rutinas de fuerza'),
                                    ('Cardio'),
                                    ('Hipertrofia'),
                                    ('Movilidad y estiramientos'),
                                    ('CrossFit'),
                                    ('Nutrición deportiva'),
                                    ('Equipo y accesorios'),
                                    ('Suplementos');

INSERT INTO entradas (usuario_id, categoria_id, titulo, descripcion, fecha) VALUES
                                                                                (1, 1, "Entrenamiento de fuerza para principiantes", "Si estás comenzando con el entrenamiento de fuerza, es importante enfocarte en ejercicios básicos como sentadillas, peso muerto y press de banca. Estos movimientos trabajan múltiples grupos musculares y ayudan a construir una base sólida. Asegúrate de mantener una buena técnica para evitar lesiones y considera empezar con un entrenador personal si tienes dudas.", CURDATE()),
                                                                                (1, 7, "Mejores zapatillas para levantamiento", "Elegir el calzado adecuado para levantamiento de pesas puede marcar una gran diferencia. Las zapatillas con suela plana y rígida, como las de levantamiento olímpico, ofrecen estabilidad y soporte durante ejercicios como sentadillas y peso muerto. Algunas marcas recomendadas incluyen Nike Romaleos, Adidas Adipower y Reebok Legacy Lifters.", CURDATE()),
                                                                                (2, 3, "Cómo ganar masa muscular", "Para ganar masa muscular necesitas un balance positivo de calorías, lo que significa consumir más de las que quemas. Además, enfócate en ejercicios de resistencia con un peso adecuado para realizar entre 8 y 12 repeticiones. No olvides incluir proteínas en cada comida y descansar adecuadamente para optimizar la recuperación muscular.", CURDATE()),
                                                                                (2, 6, "Batidos de proteínas caseros", "Preparar tus propios batidos de proteínas puede ser una opción económica y saludable. Prueba mezclar leche o bebida vegetal, un plátano, avena, mantequilla de maní y una porción de proteína en polvo. Este batido es ideal para después del entrenamiento.", CURDATE()),
                                                                                (2, 4, "Estiramientos para después del entrenamiento", "Realizar estiramientos después de entrenar ayuda a mejorar la flexibilidad y reducir la rigidez muscular. Dedica al menos 10 minutos a estirar los principales grupos musculares que trabajaste durante la sesión. Algunos ejercicios recomendados incluyen el estiramiento del gato-vaca para la espalda y el estiramiento del corredor para las piernas.", CURDATE()),
                                                                                (3, 2, "Entrenamiento HIIT: ¿Es para ti?", "El HIIT (entrenamiento interválico de alta intensidad) es una excelente opción para mejorar la resistencia cardiovascular y quemar calorías en poco tiempo. Consiste en alternar periodos de ejercicio intenso con breves descansos. Es ideal para personas con poco tiempo, pero no se recomienda para principiantes sin supervisión profesional.", CURDATE());
