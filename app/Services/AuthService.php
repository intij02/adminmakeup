<?php

namespace App\Services;

use App\Models\UserModel;
use RuntimeException;

class AuthService
{
    private const LOGIN_MAX_ATTEMPTS = 5;
    private const LOGIN_WINDOW_SECONDS = 60;

    public function attemptLogin(string $username, string $password): array
    {
        $username = trim($username);
        $password = (string) $password;

        if ($username === '' || $password === '') {
            return ['ok' => false, 'message' => 'Usuario y contraseña son obligatorios.'];
        }

        if ($this->isLoginRateLimited($username)) {
            return ['ok' => false, 'message' => 'Demasiados intentos. Espera un minuto.'];
        }

        $users = new UserModel();
        $user = $users->where('user', $username)->first();

        if (! $user || ! isset($user['pass'])) {
            $this->registerFailedAttempt($username);
            return ['ok' => false, 'message' => 'Credenciales inválidas.'];
        }

        if (! $this->verifyPassword((string) $user['pass'], $password)) {
            $this->registerFailedAttempt($username);
            return ['ok' => false, 'message' => 'Credenciales inválidas.'];
        }

        $this->clearFailedAttempts($username);
        $this->upgradeLegacyPasswordHashIfNeeded($users, $user, $password);

        $permissionService = new PermissionService();
        $permissions = $permissionService->getPermissionsForUserId((int) $user['id']);

        session()->set('user', [
            'id'     => (int) $user['id'],
            'nombre' => $user['nombre'],
            'user'   => $user['user'],
        ]);
        session()->set('permissions', $permissions);
        session()->regenerate(true);

        $this->logAuthEvent('login_success', (int) $user['id'], $username);

        return ['ok' => true, 'message' => 'Login correcto.'];
    }

    public function logout(): void
    {
        $user = session()->get('user');
        if (is_array($user) && isset($user['id'])) {
            $this->logAuthEvent('logout', (int) $user['id'], (string) $user['user']);
        }

        session()->destroy();
    }

    public function requireAuthenticatedUser(): array
    {
        $user = session()->get('user');

        if (! is_array($user) || ! isset($user['id'])) {
            throw new RuntimeException('Unauthenticated');
        }

        return $user;
    }

    private function verifyPassword(string $storedHash, string $password): bool
    {
        if ($storedHash === '') {
            return false;
        }

        // Legacy compatibility: historical system used md5.
        if (preg_match('/^[a-f0-9]{32}$/i', $storedHash) === 1) {
            return hash_equals(strtolower($storedHash), md5($password));
        }

        return password_verify($password, $storedHash);
    }

    private function isLoginRateLimited(string $username): bool
    {
        $key = $this->getAttemptsSessionKey($username);
        $bucket = session()->get($key);

        if (! is_array($bucket) || ! isset($bucket['count'], $bucket['started_at'])) {
            return false;
        }

        $startedAt = (int) $bucket['started_at'];
        $now = time();

        if (($now - $startedAt) > self::LOGIN_WINDOW_SECONDS) {
            session()->remove($key);
            return false;
        }

        return (int) $bucket['count'] >= self::LOGIN_MAX_ATTEMPTS;
    }

    private function registerFailedAttempt(string $username): void
    {
        $key = $this->getAttemptsSessionKey($username);
        $bucket = session()->get($key);
        $now = time();

        if (! is_array($bucket) || ! isset($bucket['count'], $bucket['started_at'])) {
            session()->set($key, [
                'count'      => 1,
                'started_at' => $now,
            ]);
            return;
        }

        $startedAt = (int) $bucket['started_at'];

        if (($now - $startedAt) > self::LOGIN_WINDOW_SECONDS) {
            session()->set($key, [
                'count'      => 1,
                'started_at' => $now,
            ]);
            return;
        }

        session()->set($key, [
            'count'      => ((int) $bucket['count']) + 1,
            'started_at' => $startedAt,
        ]);
    }

    private function clearFailedAttempts(string $username): void
    {
        session()->remove($this->getAttemptsSessionKey($username));
    }

    private function getAttemptsSessionKey(string $username): string
    {
        return 'login_attempts_' . sha1(mb_strtolower(trim($username)));
    }

    private function upgradeLegacyPasswordHashIfNeeded(UserModel $users, array $user, string $password): void
    {
        $storedHash = (string) $user['pass'];

        if (preg_match('/^[a-f0-9]{32}$/i', $storedHash) !== 1) {
            return;
        }

        $users->update((int) $user['id'], [
            'pass' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    private function logAuthEvent(string $event, int $userId, string $username): void
    {
        log_message('info', 'auth_event={event} user_id={userId} username={username} ip={ip}', [
            'event'    => $event,
            'userId'   => $userId,
            'username' => $username,
            'ip'       => (string) service('request')->getIPAddress(),
        ]);
    }
}
