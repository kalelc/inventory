<?php
namespace Application\Model;

use Zend\Db\TableGateway\Feature\EventFeature;

interface EventFeatureCacheAwareInterface
{
    public function setEventFeatureCache(EventFeature $eventFeature);
}