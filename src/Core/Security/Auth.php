<?php

namespace Project\Core\Security;

use Project\Core\Models\User;
use Phalcon\Mvc\User\Component;
use Project\Core\Exception\AuthException;

/**
 * Class Auth
 * @package Project\Core\Security
 */
class Auth extends Component
{
    /**
     * Key under which identity will be stored in session
     */
    private const IDENTITY_KEY = 'auth-identity';

    /**
     * @param string $username
     * @param string $plainPassword
     * @throws AuthException
     */
    public function check(string $username, string $plainPassword): void
    {
        /** @var $user User */
        $user = User::findFirstByUsername($username);
        if ($user === false) {
            $this->authThrottling();
            throw new AuthException('Wrong email/password combination');
        }

        if (!$user->checkPassword($plainPassword)) {
            $this->authThrottling();
            throw new AuthException('Wrong email/password combination');
        }

        $identity = new Identity(
            $user->getId(),
            $user->getUsername(),
            $user->getRoles()
        );

        $this->session->set(self::IDENTITY_KEY, $identity);
    }

    /**
     * Log out
     */
    public function remove(): void
    {
        $this->session->remove(self::IDENTITY_KEY);
    }

    /**
     * Throttle failed login attempts
     */
    private function authThrottling(): void
    {
        // TODO implement throttling
        sleep(2);
    }

    /**
     * @return Identity|null
     */
    public function getIdentity(): ?Identity
    {
        return $this->session->get(self::IDENTITY_KEY);
    }

    /**
     * @return User
     * @throws AuthException
     */
    public function getUser(): User
    {
        $identity = $this->session->get(self::IDENTITY_KEY);
        if (is_null($identity)) {
            return null;
        }

        $user = User::findFirstById($identity->getUserId());

        if ($user == false) {
            $this->remove();
            throw new AuthException('User account deleted');
        }
        return $user;
    }
}