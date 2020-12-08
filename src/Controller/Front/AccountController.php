<?php

namespace App\Controller\Front;

use App\Controller\BaseController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends BaseController
{
    /** @var UserPasswordEncoderInterface */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * AccountController constructor.
     *
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($em);
        $this->passwordEncoder = $passwordEncoder;
    }
}
