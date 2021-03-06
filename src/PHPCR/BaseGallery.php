<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\MediaBundle\PHPCR;

use Doctrine\Common\Collections\ArrayCollection;
use Sonata\MediaBundle\Model\Gallery;
use Sonata\MediaBundle\Model\GalleryHasMediaInterface;

/**
 * Bundle\MediaBundle\Document\BaseGallery.
 */
abstract class BaseGallery extends Gallery
{
    /**
     * @var string
     */
    private $uuid;

    public function __construct()
    {
        $this->galleryHasMedias = new ArrayCollection();
    }

    /**
     * Get universal unique id.
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    public function addGalleryHasMedia(GalleryHasMediaInterface $galleryHasMedia)
    {
        $galleryHasMedia->setGallery($this);

        // set nodename of GalleryHasMedia
        if (!$galleryHasMedia->getNodename()) {
            $galleryHasMedia->setNodename(
                'media'.($this->galleryHasMedias->count() + 1)
            );
        }

        $this->galleryHasMedias->set($galleryHasMedia->getNodename(), $galleryHasMedia);
    }

    /**
     * Pre persist method.
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        $this->reorderGalleryHasMedia();
    }

    /**
     * Pre Update method.
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();

        $this->reorderGalleryHasMedia();
    }
}
