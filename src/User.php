<?php
namespace src;

use InvalidArgumentException;

class User extends Entity{
    protected string $tableName = 'user';
    public int $id;
    public string $role;
    public string $username;
    public string $email;
    public string $password;
    public ?string $fio = null;
    public ?string $phone = null;
    public bool $isGuest;
    public bool $isAdmin;
    public ?string $authToken = null;

    public function isAdmin(): bool{
        return ($this->role === 'admin');
    }

    public function validate($data){
        if (empty($data['login'])) {
            throw new InvalidArgumentException('Не передано имя пользователя');
        }
        if (empty($data['email'])) {
            throw new InvalidArgumentException('Не передан email');
        }
        if (empty($data['password'])) {
            throw new InvalidArgumentException('Не передан пароль');
        }
        if (empty($data['fio'])) {
            throw new InvalidArgumentException('Не передано ФИО');
        }
        if (empty($data['phone'])) {
            throw new InvalidArgumentException('Не передан телефон');
        }
        if (strlen($data['login']) < 3) {
            throw new InvalidArgumentException('Имя пользователя слишком короткое');
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Неправильный формат email');
        }
        if (strlen($data['password']) < 6) {
            throw new InvalidArgumentException('Пароль должен иметь хотя бы 6 символов');
        }

        if ($this->findOneByColumn('login', $data['login'])) {
            throw new InvalidArgumentException('Пользователь с таким логином уже существует');
        }
        if ($this->findOneByColumn('email', $data['email'])) {
            throw new InvalidArgumentException('Email уже занят');
        }

        return true;
    }

    public function identity(): ?array{
        if(!empty($_SESSION['user_id'])){
            $user = $this->getById((int)$_SESSION['user_id']);
            if($user !== null){
                return $user;
            }
        }

        $token = $_COOKIE['token'] ?? '';
        if(empty($token)){
            return null;
        }

        $parts = explode(':', $token, 2);
        if(count($parts) !== 2){
            return null;
        }

        [$userId, $authToken] = $parts;
        $user = $this->getById((int)$userId);
        if($user === null){
            return null;
        }

        if(($user['authToken'] ?? $user['auth_token'] ?? null) !== $authToken){
            return null;
        }

        return $user;
    }

    public function refreshAuthToken(): string{
        $bytes = random_bytes(16);
        $this->authToken = sha1($bytes);
        return $this->authToken;
    }

    public function createTokenCookie(){
        if(empty($this->authToken)){
            $this->refreshAuthToken();
        }
        setcookie('token', $this->authToken, time() + 3600 * 24 * 30, '/');
    }

   public function login(array $data){
        $this->validateLogin($data);
        $userData = $this->findOneByColumn('login', $data['login']);
        if(!$userData){
            throw new InvalidArgumentException('Пользователь не найден');
        }
        if($data['password'] !== $userData['password']){
            throw new InvalidArgumentException('Неверный пароль');
        }
        $this->load($userData);
        $this->refreshAuthToken();
        $this->createTokenCookie();

        $_SESSION['user_id'] = $this->id;
        $_SESSION['role'] = $this->role ?? 'user';

        return true;
    }

    public function logout(){
        if (isset($_COOKIE['token'])) {
            setcookie('token', '', time() - 3600, '/');
        }
    }

    public function validateLogin(array $data){
        if(empty($data['login'])){
            throw new InvalidArgumentException('Не передан логин');
        }
        if(empty($data['password'])){
            throw new InvalidArgumentException('Не передан пароль');
        }

        return true;
    }

    public function save(): bool{
        if(empty($this->username) || empty($this->email) || empty($this->password)){
            return false;
        }
        $fields = [
            'login' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'fio' => $this->fio ?? '',
            'phone' => $this->phone ?? '',
            'role' => $this->role ?? 'user',
        ];

        return (bool)$this->insert($fields);
    }
}