<?php


namespace App\Creators;

use App\Entity\Credentials;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class referenceCreator implements EventSubscriberInterface
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setUser'],
            BeforeEntityPersistedEvent::class => ['setcred']
        ];
    
    }

    public function setUser(BeforeEntityPersistedEvent  $event)
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof Credentials) {
            $entity->setUserid($this->security->getUser());
        }
    }
    public function setcred(BeforeEntityPersistedEvent  $event)
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof Objectives) {
            $entity->setrefrenceId($this->security->setcred());
        }
        if ($entity instanceof Workstreams) {
            $entity->setrefrenceId($this->security->setcred());
        }
    }
   
}