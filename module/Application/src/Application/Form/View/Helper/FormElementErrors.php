<?php
namespace Application\Form\View\Helper;

use Zend\Form\View\Helper\FormElementErrors as OriginalFormElementErrors;

class FormElementErrors extends OriginalFormElementErrors  
{
    protected $messageCloseString     = '</span>';
    protected $messageOpenFormat      = '<span class="error">';
    protected $messageSeparatorString = '</span><br><span class="error">';
}
?>