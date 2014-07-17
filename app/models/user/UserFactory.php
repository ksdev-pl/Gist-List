<?php

class UserFactory
{
    /**
     * Get new User
     *
     * @return User
     */
    public function getInstance()
    {
        return new User();
    }
}