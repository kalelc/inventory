<?php
namespace Application\Model;

use Zend\Db\TableGateway\Feature\EventFeature;

interface EventFeatureCacheAwareInterface
{
    /**
     *
     * @param EventFeature $eventFeature
     */
    public function setEventFeatureCache(EventFeature $eventFeature);
}