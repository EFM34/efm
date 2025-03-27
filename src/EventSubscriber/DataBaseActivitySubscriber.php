<?php

namespace App\EventSubscriber;

use App\Entity\Category;
use Doctrine\ORM\Events;
use App\Entity\Product;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;


#[AsDoctrineListener(event: Events::postUpdate)]
#[AsDoctrineListener(event: Events::postRemove)]
class DataBaseActivitySubscriber
{

    /* kernelInterface $appKernel */
    private $appKernel;
    private $rootDir;

    /**
     * Construct
     *
     * @param kernelInterface $appKernel
     */
    public function __construct(kernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
        $this->rootDir = $appKernel->getProjectDir();
    }


    /**
     * Les venements de suppression et de mis a jour
     */
    public function getSubscribedEvents(): array
    {
        return [
            // on intercepte la de mis a jour
            Events::postUpdate,
            // on intercepte les evenement de supression
            Events::postRemove,
        ];
    }


    /**
     * Remove
     *
     * @param PostRemoveEventArgs $args
     * @return void
     */
    public function postRemove(PostRemoveEventArgs $args): void
    {

        $this->logActivity('remove', $args->getObject());
    }


    /**
     * Update
     *
     * @param PostUpdateEventArgs $args
     * @return void
     */
    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->logActivity('update', $args->getObject());
    }


    /**
     * Action Log
     *
     * @param string $action
     * @param mixed $entity
     * @return void
     */
    public function logActivity(string $action, mixed $entity): void
    {


        if (($entity instanceof Product) && $action === "remove") {
            // remove image Product
            $imageUrls = $entity->getImageUrls();
            foreach ($imageUrls as $imageUrl) {
                $filelink = $this->rootDir . "/public/assets/images/products/" . $imageUrl;

                // Permet de suprimer les images lie a cette table Product
                $this->deleteImage($filelink);
            }
        }

        if (($entity instanceof Category) && $action === "remove") {
            // remove image Category
            $filename = $entity->getImageUrl();
            $filelink = $this->rootDir . "/public/assets/images/categories/" . $filename;

            // Permet de suprimer les images lie a cette table Category
            $this->deleteImage($filelink);
        }
    }


    /**
     * Remove
     *
     * @param string $filelink
     * @return void
     */
    public function deleteImage(string $filelink): void
    {
        try {
            $result = unlink($filelink);
        } catch (\Throwable $th) {
        }
    }
}
