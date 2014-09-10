<?php
namespace Application\Db;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\TableGateway\TableGateway as ZendTableGateway;
use Zend\Db\TableGateway\Feature\MasterSlaveFeature;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\TableGateway\Feature\AbstractFeature;
use Application\Model\EventFeatureCacheAwareInterface;
use Zend\Db\TableGateway\Feature\EventFeature;

class TableGateway extends ZendTableGateway implements EventFeatureCacheAwareInterface
{

    protected $eventFeature;
    protected $tableName;

    public function __construct($table, AdapterInterface $adapter, $features = null, ResultSetInterface $resultSetPrototype = null)
    {
        if (! (is_string($table) || $table instanceof TableIdentifier)) {
            throw new \InvalidArgumentException('Table name must be a string or an instance of Zend\Db\Sql\TableIdentifier');
        }
        $this->table = $table;

        $this->adapter = $adapter;

        if ($features !== null) {
            if ($features instanceof AbstractFeature) {
                $features = array(
                    $features
                    );
            }
            if (is_array($features)) {
                $this->featureSet = new FeatureSet($features);
            } elseif ($features instanceof FeatureSet) {
                $this->featureSet = $features;
            } else {
                throw new \InvalidArgumentException('TableGateway expects $feature to be an instance of an AbstractFeature or a FeatureSet, or an array of AbstractFeatures');
            }
        } else {
            $this->featureSet = new FeatureSet();
        }
        $resources = preg_replace('/(?<!^)([A-Z])/', '-\\1',explode("\\",get_class($resultSetPrototype->getArrayObjectPrototype()))[2]);
        $this->tableName = strtolower(str_replace("-","_", $resources));

        $this->resultSetPrototype = ($resultSetPrototype) ?  : new ResultSet();
        $this->initialize();

    }

    public function setEventFeatureCache(EventFeature $eventFeature)
    {

        if ($eventFeature !== null) {
            if ($eventFeature instanceof AbstractFeature) {
                $eventFeature = array(
                    $eventFeature
                    );
            }
            if (is_array($eventFeature)) {
                $this->featureSet = new FeatureSet($eventFeature);
            } elseif ($eventFeature instanceof FeatureSet) {
                $this->featureSet = $eventFeature;
            } else {
                throw new \InvalidArgumentException('TableGateway expects $eventFeature to be an instance of an AbstractFeature or a FeatureSet, or an array of AbstractFeatures');
            }
        }
        $this->eventFeature = $eventFeature;
    }
    
    public function getTableName(){
        return $this->tableName;
    }

}