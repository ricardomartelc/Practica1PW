-- Valores iniciales de la tabla sensores (casos de uso de la práctica 2)
INSERT INTO sensores (id, vendedor_id, codigo_dispositivo, tipo, valor_actual, umbral_min, umbral_max) VALUES
(1, 1, 'SN-HV-001', 'temperatura',  6.50,  2.00,  8.00),
(2, 1, 'SN-HV-002', 'humedad',     85.00, 80.00, 95.00),
(3, 2, 'SN-LQ-001', 'temperatura',  9.20,  2.00,  8.00),   -- ALERTA: supera el máximo
(4, 2, 'SN-LQ-002', 'humedad',       NULL, 70.00, 90.00),  -- sin lectura todavía
(5, 3, 'SN-PN-001', 'temperatura', 22.00, 15.00, 28.00);
