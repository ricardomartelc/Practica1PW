-- Valores iniciales de la tabla tienda_vendedores (casos de uso de la práctica 2)
INSERT INTO tienda_vendedores (vendedor_id, producto_id, stock, precio_b2c, precio_b2b, minimo_b2b, descuento_pct) VALUES
-- Puesto 1: La Huerta Verde
(1, 1,  120, 2.20, 1.80, 20,  0),
(1, 2,  200, 1.90, 1.50, 25, 10),
(1, 3,   80, 1.20, 0.90, 30,  0),
(1, 4,  150, 2.50, 2.00, 20,  0),
(1, 5,   60, 4.50, 3.80, 10, 15),
(1, 6,   40, 6.90, 5.50, 10,  0),
-- Puesto 2: Lácteos y Quesos Artesanos
(2, 7,   50, 9.50, 8.00,  5,  0),
(2, 8,   40, 11.90, 10.00, 5, 10),
(2, 9,  100, 1.60, 1.25, 24,  0),
(2, 12,  30, 7.50, 6.20,  6,  0),
-- Puesto 3: Panadería El Horno de Rosa
(3, 10, 200, 0.80, 0.60, 50,  0),
(3, 11,  80, 3.20, 2.60, 10,  5),
(3, 12,  25, 7.90, 6.50,  6,  0);
