<?php

require_once __DIR__ . '/Producto.php';

class Carrito
{
    public const SESSION_KEY = 'carrito';

    private function asegurarSesion(): void
    {
        if (!isset($_SESSION[self::SESSION_KEY]) || !is_array($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }
    }

    public function agregar(int $productoId, int $cantidad = 1): bool
    {
        if ($productoId <= 0) {
            return false;
        }

        if ($cantidad < 1) {
            $cantidad = 1;
        }

        $producto = (new Producto)->porId($productoId);

        if ($producto === null) {
            return false;
        }

        $this->asegurarSesion();

        if (isset($_SESSION[self::SESSION_KEY][$productoId])) {
            $_SESSION[self::SESSION_KEY][$productoId]['cantidad'] += $cantidad;
            $_SESSION[self::SESSION_KEY][$productoId]['imagen'] = $producto->getImagen();
        } else {
            $_SESSION[self::SESSION_KEY][$productoId] = [
                'cantidad' => $cantidad,
                'nombre' => $producto->getNombre(),
                'precio' => $producto->getPrecio(),
                'imagen' => $producto->getImagen(),
            ];
        }

        return true;
    }

    public function actualizarCantidad(int $productoId, int $cantidad): void
    {
        $this->asegurarSesion();

        if (!isset($_SESSION[self::SESSION_KEY][$productoId])) {
            return;
        }

        if ($cantidad < 1) {
            $this->quitar($productoId);
            return;
        }

        if ($cantidad > 99) {
            $cantidad = 99;
        }

        $_SESSION[self::SESSION_KEY][$productoId]['cantidad'] = $cantidad;
    }

    public function quitar(int $productoId): void
    {
        $this->asegurarSesion();
        unset($_SESSION[self::SESSION_KEY][$productoId]);
    }

    public function vaciar(): void
    {
        $_SESSION[self::SESSION_KEY] = [];
    }

    public function obtenerItems(): array
    {
        $this->asegurarSesion();

        return $_SESSION[self::SESSION_KEY];
    }

    public function calcularTotal(): float
    {
        $total = 0.0;

        foreach ($this->obtenerItems() as $item) {
            $total += (float) $item['precio'] * (int) $item['cantidad'];
        }

        return $total;
    }

    public function cantidadItems(): int
    {
        $cantidad = 0;

        foreach ($this->obtenerItems() as $item) {
            $cantidad += (int) $item['cantidad'];
        }

        return $cantidad;
    }
}
