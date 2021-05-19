<?php


namespace App\EventListener;


use App\Entity\Post;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class PostChangeListener
{
    public function postUpdate(Post $post, LifecycleEventArgs $eventArgs)
    {
        $post->setCreatedAt(new \DateTime());
    }

}