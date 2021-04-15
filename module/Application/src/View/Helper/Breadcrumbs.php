<?php

declare(strict_types=1);

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\View\Helper\boolean as helperBoolean;

class Breadcrumbs extends AbstractHelper
{
    /**
     * @var array $items
     */
    private $items = [];
    
    /**
     * @param array $items Array of items (optional).
     */
    public function __construct(array $items=[])
    {                
        $this->items = $items;
    }
    
    /**
     * @param array $items Items.
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }
    
    /**
     * @return string
     */
    public function render() 
    {
        if (count($this->items)==0)
            return ''; // Do nothing if there are no items.
        
        $result = '<ol class="breadcrumb">';
        $itemCount = count($this->items);
        $itemNum = 1; // item counter
        foreach ($this->items as $label=>$link) {
            $isActive = ($itemNum==$itemCount?true:false);
            $result .= $this->renderItem($label, $link, $isActive);
            $itemNum++;
        }
        
        $result .= '</ol>';
        return $result;
    }

    /**
     * @param string $label
     * @param string $link
     * @param boolean $isActive
     * @return string
     */
    protected function renderItem(string $label, string $link, $isActive)
    {
        $escapeHtml = $this->getView()->plugin('escapeHtml');
        $result = $isActive?'<li class="active">':'<li>';
        if (!$isActive)
            $result .= '<a href="'.$escapeHtml($link).'">'.$escapeHtml($label).'</a>';
        else
            $result .= $escapeHtml($label);
        $result .= '</li>';
        return $result;
    }
}