<?php
class Usuario
{
    protected int $usuario_id = 0;
    protected string $email = '';
    protected string $password = '';
    protected ?string $nombre = null;
    protected ?string $apellido = null;
}