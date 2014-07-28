<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\ConfigAwareInterface;

class ConfigController extends AbstractActionController implements ConfigAwareInterface
{
    protected $config;

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function folderPermissionsAction()
    {   
        $jsonModel = new JsonModel();

        foreach($this->config['component'] as $component)
        {
            if(@$component['image_path'] || @$component['video_path'] || @$component['file_path']) {
                if(isset($component['image_path']) && !empty($component['image_path'])) {
                    if(!file_exists($component['image_path'])) {
                        $oldmask = umask(0);
                        mkdir($component['image_path'], 0777);
                        umask($oldmask);
                        $jsonModel->setVariable($component['image_path'],$component['image_path']);
                    }
                }
                if(isset($component['video_path']) && !empty($component['video_path'])) {
                    if(!file_exists($component['video_path'])) {
                        $oldmask = umask(0);
                        mkdir($component['video_path'], 0777);
                        umask($oldmask);
                        $jsonModel->setVariable($component['video_path'],$component['video_path']);
                    }
                }
                if(isset($component['file_path']) && !empty($component['file_path'])) {
                    if(!file_exists($component['file_path'])) {
                        $oldmask = umask(0);
                        mkdir($component['file_path'], 0777);
                        umask($oldmask);
                        $jsonModel->setVariable($component['file_path'],$component['file_path']);
                    }
                }
            }
        }

        $response = $this->getResponse();
        $response->setContent(json_encode($jsonModel->getVariables()));
        return $response;
    }
}
?>