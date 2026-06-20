<?php

require_once __DIR__ . '/DBConexion.php';

class Usuario
{
    public const SESSION_KEY_ID = 'usuario_id';
    public const SESSION_KEY_EMAIL = 'usuario_email';

    private int $usuario_id = 0;
    private string $email = '';
    private string $password = '';

    public function porEmail(string $email): ?self
    {
        $db = (new DBConexion)->getConexion();

        $consulta = "SELECT usuario_id, email, password FROM usuarios WHERE email = :email";
        $stmt = $db->prepare($consulta);
        $stmt->execute(['email' => $email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

        $usuario = $stmt->fetch();

        return $usuario === false ? null : $usuario;
    }

    public function verificarCredenciales(string $email, string $password): ?self
    {
        $usuario = $this->porEmail($email);

        if ($usuario === null) {
            return null;
        }

        if ($usuario->getPassword() !== $password) {
            return null;
        }

        return $usuario;
    }

    public static function iniciarSesion(self $usuario): void
    {
        $_SESSION[self::SESSION_KEY_ID] = $usuario->getId();
        $_SESSION[self::SESSION_KEY_EMAIL] = $usuario->getEmail();
    }

    public static function cerrarSesion(): void
    {
        $_SESSION = [];

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    public static function estaLogueado(): bool
    {
        return isset($_SESSION[self::SESSION_KEY_ID]);
    }

    public static function idEnSesion(): ?int
    {
        if (!self::estaLogueado()) {
            return null;
        }

        return (int) $_SESSION[self::SESSION_KEY_ID];
    }

    public function getId(): int
    {
        return $this->usuario_id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
