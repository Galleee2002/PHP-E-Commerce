<?php

require_once __DIR__ . '/DBConexion.php';
require_once __DIR__ . '/Producto.php';
require_once __DIR__ . '/Carrito.php';

class Compra
{
    /**
     * Persiste la compra desde el carrito de sesión.
     * Re-lee el precio desde MySQL (fuente de verdad) y usa transacción PDO.
     *
     * @throws RuntimeException si el carrito está vacío o un producto ya no existe
     */
    public function crearDesdeCarrito(int $usuarioId, Carrito $carrito): int
    {
        if ($usuarioId <= 0) {
            throw new RuntimeException('Usuario inválido para completar la compra.');
        }

        $items = $carrito->obtenerItems();

        if ($items === []) {
            throw new RuntimeException('El carrito está vacío.');
        }

        $lineas = [];
        $total = 0.0;
        $productoModelo = new Producto();

        foreach ($items as $productoId => $item) {
            $productoId = (int) $productoId;
            $cantidad = (int) ($item['cantidad'] ?? 0);

            if ($productoId <= 0 || $cantidad < 1) {
                throw new RuntimeException('Hay un ítem inválido en el carrito.');
            }

            $producto = $productoModelo->porId($productoId);

            if ($producto === null) {
                throw new RuntimeException('Un producto del carrito ya no está disponible.');
            }

            $precioUnitario = (float) $producto->getPrecio();
            $total += $precioUnitario * $cantidad;

            $lineas[] = [
                'producto_fk' => $productoId,
                'cantidad' => $cantidad,
                'precio_unitario' => $precioUnitario,
            ];
        }

        $db = (new DBConexion)->getConexion();
        $db->beginTransaction();

        try {
            $stmtCompra = $db->prepare(
                'INSERT INTO compras (usuario_fk, total)
                 VALUES (:usuario_fk, :total)'
            );
            $stmtCompra->execute([
                'usuario_fk' => $usuarioId,
                'total' => $total,
            ]);

            $compraId = (int) $db->lastInsertId();

            if ($compraId <= 0) {
                throw new RuntimeException('No se pudo crear la compra.');
            }

            $stmtDetalle = $db->prepare(
                'INSERT INTO compras_tienen_productos
                    (compra_fk, producto_fk, cantidad, precio_unitario)
                 VALUES
                    (:compra_fk, :producto_fk, :cantidad, :precio_unitario)'
            );

            foreach ($lineas as $linea) {
                $stmtDetalle->execute([
                    'compra_fk' => $compraId,
                    'producto_fk' => $linea['producto_fk'],
                    'cantidad' => $linea['cantidad'],
                    'precio_unitario' => $linea['precio_unitario'],
                ]);
            }

            $db->commit();
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            throw $e;
        }

        $carrito->vaciar();

        return $compraId;
    }

    /**
     * Cabecera de compra + líneas de detalle.
     *
     * @return array{compra: array, productos: array}|null
     */
    public function porId(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $db = (new DBConexion)->getConexion();

        $stmt = $db->prepare(
            'SELECT compra_id, usuario_fk, fecha, total
             FROM compras
             WHERE compra_id = :id'
        );
        $stmt->execute(['id' => $id]);
        $compra = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($compra === false) {
            return null;
        }

        $stmtDetalle = $db->prepare(
            'SELECT ctp.producto_fk, ctp.cantidad, ctp.precio_unitario,
                    p.nombre
             FROM compras_tienen_productos ctp
             INNER JOIN productos p ON p.producto_id = ctp.producto_fk
             WHERE ctp.compra_fk = :compra_fk
             ORDER BY p.nombre'
        );
        $stmtDetalle->execute(['compra_fk' => $id]);

        return [
            'compra' => $compra,
            'productos' => $stmtDetalle->fetchAll(PDO::FETCH_ASSOC),
        ];
    }

    /**
     * Listado de compras de un usuario (sin detalle de productos).
     *
     * @return list<array>
     */
    public function porUsuario(int $usuarioId): array
    {
        if ($usuarioId <= 0) {
            return [];
        }

        $db = (new DBConexion)->getConexion();

        $stmt = $db->prepare(
            'SELECT compra_id, usuario_fk, fecha, total
             FROM compras
             WHERE usuario_fk = :usuario_fk
             ORDER BY fecha DESC, compra_id DESC'
        );
        $stmt->execute(['usuario_fk' => $usuarioId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
