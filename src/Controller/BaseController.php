<?php

namespace App\Controller;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class BaseController.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa070819999@gmail.com>
 */
class BaseController extends AbstractController
{
    /** @var EntityManagerInterface */
    protected EntityManagerInterface $em;

    /**
     * BaseController constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param object $object
     * @return boolean
     */
    public function save(object $object): bool
    {
        try {
            if (!$object->getId()) {
                $this->em->persist($object);
            }
            $this->em->flush();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param object $object
     * @return boolean
     */
    public function remove(object $object): bool
    {
        try {
            if ($object) {
                $this->em->remove($object);
            }
            $this->em->flush();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Upload files.
     *
     * @param array $files
     * @param object $object
     * @return object
     */
    public function uploadFiles(array $files, object $object): object
    {
        foreach ($files as $file) {
            $filename = bin2hex(random_bytes(6)) . '.' . $file->guessExtension();
            $file->move($this->getParameter('image_directory'), $filename);

            $image = new Image();
            $image->setName($filename);
            $object->addImage($image);
        }
        return $object;
    }
}
