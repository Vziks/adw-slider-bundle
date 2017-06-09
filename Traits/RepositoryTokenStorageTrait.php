<?php

namespace ADW\SliderBundle\Traits;


use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Trait RepositoryTokenStorageTrait
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Trait
 * @author Anton Prokhorov
 */
trait RepositoryTokenStorageTrait
{

    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

}