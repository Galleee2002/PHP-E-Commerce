<?php

require_once __DIR__ . '/DBConexion.php';

class Usuario
{
    public const ROL_COMUN = 'comun';
    public const ROL_ADMIN = 'admin';

    public const SESSION_KEY_ID = 'usuario_id';
    public const SESSION_KEY_EMAIL = 'usuario_email';
    public const SESSION_KEY_ROL = 'usuario_rol';

    private int $usuario_id = 0;
    private string $email = '';
    private string $password = '';
    private ?string $nombre = null;
    private ?string $apellido = null;
    private string $rol = self::ROL_COMUN;

    public function porEmail(string $email): ?self
    {
        $db = (new DBConexion)->getConexion();

        $consulta = "SELECT usuario_id, email, password, nombre, apellido, rol
                     FROM usuarios
                     WHERE email = :email";
        $stmt = $db->prepare($consulta);
        $stmt->execute(['email' => $email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

        $usuario = $stmt->fetch();

        return $usuario === false ? null : $usuario;
    }

    public function porId(int $id): ?self
    {
        $db = (new DBConexion)->getConexion();

        $consulta = "SELECT usuario_id, email, password, nombre, apellido, rol
                     FROM usuarios
                     WHERE usuario_id = :id";
        $stmt = $db->prepare($consulta);
        $stmt->execute(['id' => $id]);
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

        if (!password_verify($password, $usuario->getPassword())) {
            return null;
        }

        return $usuario;
    }

    public function registrar(string $email, string $password, string $nombre, string $apellido): self
    {
        $db = (new DBConexion)->getConexion();

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $consulta = "INSERT INTO usuarios (email, password, nombre, apellido, rol)
                     VALUES (:email, :password, :nombre, :apellido, :rol)";
        $stmt = $db->prepare($consulta);
        $stmt->execute([
            'email' => $email,
            'password' => $passwordHash,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'rol' => self::ROL_COMUN,
        ]);

        $usuario = $this->porEmail($email);

        if ($usuario === null) {
            throw new RuntimeException('No se pudo recuperar el usuario recién registrado.');
        }

        return $usuario;
    }

    public static function iniciarSesion(self $usuario): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        $_SESSION[self::SESSION_KEY_ID] = $usuario->getId();
        $_SESSION[self::SESSION_KEY_EMAIL] = $usuario->getEmail();
        $_SESSION[self::SESSION_KEY_ROL] = $usuario->getRol();
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

    public static function esAdmin(): bool
    {
        return isset($_SESSION[self::SESSION_KEY_ROL])
            && $_SESSION[self::SESSION_KEY_ROL] === self::ROL_ADMIN;
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

    public function getNombre(): string
    {
        return $this->nombre ?? '';
    }

    public function getApellido(): string
    {
        return $this->apellido ?? '';
    }

    public function getRol(): string
    {
        return $this->rol;
    }
}
