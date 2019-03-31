<?php

namespace Project\Core\Security;

use Project\Core\Models\User;
use Phalcon\Mvc\User\Component;
use Phalcon\Session\AdapterInterface;
use Project\Core\Exception\AuthException;

/**
 * Class Auth
 * @package Project\Core\Security
 * @property AdapterInterface $session
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class Auth extends Component
{
    /**
     * Key under which identity will be stored in session
     */
    private const IDENTITY_KEY = 'auth:identity';

    /**
     * @param string $username
     * @param string $plainPassword
     * @throws AuthException
     * @throws \Exception
     */
    public function check(string $username, string $plainPassword): void
    {
        $user = User::findFirstByUsername($username);
        if (!$user instanceof User || !$user->checkPassword($plainPassword)) {
            throw new AuthException(translate('ERR_LOGIN_FAILED'));
        }

        $identity = Identity::fromUser($user);

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
     * @return Identity|null
     */
    public function getIdentity(): ?Identity
    {
        return $this->session->get(self::IDENTITY_KEY);
    }

    /**
     * @return User|null
     * @throws AuthException
     */
    public function getUser(): ?User
    {
        $identity = $this->getIdentity();
        if (!$identity instanceof Identity) {
            return null;
        }

        $user = User::findFirstById($identity->getUserId());

        if (!$user instanceof User) {
            $this->remove();
            throw new AuthException(
                translate('MSG_USER_DELETED')
            );
        }
        return $user;
    }
}
