-- Valores iniciales de la tabla pedidos (casos de uso de la práctica 2)
INSERT INTO pedidos (id, cliente_id, vendedor_id, fecha, total, estado, direccion_entrega) VALUES
(1, 1, 1, '2026-10-05 10:15:00',  13.36, 'recibido',      'Calle Triana 15, 2ºB, Las Palmas de Gran Canaria'),
(2, 2, 2, '2026-10-12 11:30:00',  29.71, 'recogido',      NULL),
(3, 3, 3, '2026-10-20 09:05:00',  10.88, 'listo_recoger', NULL),
(4, 4, 1, '2026-10-15 08:40:00', 165.00, 'enviado',       'Paseo Marítimo 8, Las Palmas de Gran Canaria'),
(5, 5, 2, '2026-10-21 16:20:00', 140.00, 'pendiente',     NULL),
(6, 1, 3, '2026-10-22 18:45:00',  11.10, 'pendiente',     NULL),
(7, 4, 3, '2026-10-18 07:50:00', 112.00, 'recogido',      NULL),
(8, 2, 1, '2026-10-22 12:00:00',  14.40, 'enviado',       'Calle León y Castillo 40, Las Palmas de Gran Canaria');
