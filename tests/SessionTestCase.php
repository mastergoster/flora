<?php

namespace Tests;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;

class SessionTestCase implements SessionInterface
{
    private $session = [];
    /**
     * Starts the session storage.
     *
     * @return bool
     *
     * @throws \RuntimeException if session fails to start
     */
    public function start()
    {
        return;
    }

    /**
     * Returns the session ID.
     *
     * @return string
     */
    public function getId()
    {
        return;
    }

    /**
     * Sets the session ID.
     */
    public function setId(string $id)
    {
        return;
    }

    /**
     * Returns the session name.
     *
     * @return string
     */
    public function getName()
    {
        return;
    }

    /**
     * Sets the session name.
     */
    public function setName(string $name)
    {
        return;
    }

    /**
     * Invalidates the current session.
     *
     * Clears all session attributes and flashes and regenerates the
     * session and deletes the old session from persistence.
     *
     * @param int $lifetime Sets the cookie lifetime for the session cookie. A null value
     *                      will leave the system settings unchanged, 0 sets the cookie
     *                      to expire with browser session. Time is in seconds, and is
     *                      not a Unix timestamp.
     *
     * @return bool
     */
    public function invalidate(int $lifetime = null)
    {
        return;
    }
    /**
     * Migrates the current session to a new session id while maintaining all
     * session attributes.
     *
     * @param bool $destroy  Whether to delete the old session or leave it to garbage collection
     * @param int  $lifetime Sets the cookie lifetime for the session cookie. A null value
     *                       will leave the system settings unchanged, 0 sets the cookie
     *                       to expire with browser session. Time is in seconds, and is
     *                       not a Unix timestamp.
     *
     * @return bool
     */
    public function migrate(bool $destroy = false, int $lifetime = null)
    {
        return;
    }
    /**
     * Force the session to be saved and closed.
     *
     * This method is generally not required for real sessions as
     * the session will be automatically saved at the end of
     * code execution.
     */
    public function save()
    {
        return;
    }
    /**
     * Checks if an attribute is defined.
     *
     * @return bool
     */
    public function has(string $name)
    {
        return \array_key_exists($name, $this->session);
    }
    /**
     * Returns an attribute.
     *
     * @param mixed $default The default value if not found
     *
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return $this->session[$name];
    }

    /**
     * Sets an attribute.
     *
     * @param mixed $value
     */
    public function set(string $name, $value)
    {
        $this->session[$name] = $value;
    }

    /**
     * Returns attributes.
     *
     * @return array
     */
    public function all()
    {
        return $this->session;
    }
    /**
     * Sets attributes.
     */
    public function replace(array $attributes)
    {
        return;
    }

    /**
     * Removes an attribute.
     *
     * @return mixed The removed value or null when it does not exist
     */
    public function remove(string $name)
    {
        unset($this->session[$name]);
    }

    /**
     * Clears all attributes.
     */
    public function clear()
    {
        $this->session  = [];
    }

    /**
     * Checks if the session was started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * Registers a SessionBagInterface with the session.
     */
    public function registerBag(SessionBagInterface $bag)
    {
        return;
    }

    /**
     * Gets a bag instance by name.
     *
     * @return SessionBagInterface
     */
    public function getBag(string $name)
    {
    }

    /**
     * Gets session meta.
     *
     * @return MetadataBag
     */
    public function getMetadataBag()
    {
    }
}
