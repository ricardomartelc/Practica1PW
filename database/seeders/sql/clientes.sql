-- Valores iniciales de la tabla clientes (casos de uso de la práctica 2)
INSERT INTO clientes (id, nif, email, password, nombre, apellidos, tipo) VALUES
(1, '12345678A', 'ana.garcia@example.com',          '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ana',   'García López',  'particular'),
(2, '23456789B', 'luis.martin@example.com',         '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Luis',  'Martín Pérez',  'particular'),
(3, '34567890C', 'marta.suarez@example.com',        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Marta', 'Suárez Díaz',   'particular'),
(4, 'B35123456', 'compras@elmirador.example.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Restaurante El Mirador S.L.', NULL, 'empresa'),
(5, 'A38654321', 'compras@hotelatlantico.example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Hotel Atlántico S.A.',        NULL, 'empresa');
