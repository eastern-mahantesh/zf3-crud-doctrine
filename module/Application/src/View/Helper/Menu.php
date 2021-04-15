<?php

declare(strict_types=1);

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Menu extends AbstractHelper 
{
    /**
     * @var array
     */
    protected $items = [];
    
    /**
     * @var string
     */
    protected $activeItemId = '';
    
    /**
     * @param array $items Menu items.
     */
    public function __construct($items=[]) 
    {
        $this->items = $items;
    }
    
    /**
     * @param array $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }
    
    /**
     * @param string $activeItemId
     */
    public function setActiveItemId(string $activeItemId)
    {
        $this->activeItemId = $activeItemId;
    }
    
    /**
     * @return string
     */
    public function render() 
    {
        if (count($this->items)==0)
            return ''; // Do nothing if there are no items.
        
        $result = '<nav class="navbar navbar-default" role="navigation">';
        $result .= '<div class="navbar-header">';
        $result .= '<button type="button" class="navbar-toggle" data-toggle="collapse"';
        $result .= 'data-target=".navbar-ex1-collapse">';
        $result .= '<span class="sr-only">Toggle navigation</span>';
        $result .= '<span class="icon-bar"></span>';
        $result .= '<span class="icon-bar"></span>';
        $result .= '<span class="icon-bar"></span>';
        $result .= '</button>';
        $result .= '</div>';
        $result .= '<div class="collapse navbar-collapse navbar-ex1-collapse">';
        $result .= '<ul class="nav navbar-nav">';
        foreach ($this->items as $item) {
            $result .= $this->renderItem($item);
        }
        $result .= '</ul>';
        $result .= '</div>';
        $result .= '</nav>';
        
        return $result;
    }
    
    /**
     * @param array $item
     * @return string
     */
    protected function renderItem(array $item)
    {
        $id = isset($item['id']) ? $item['id'] : '';
        $isActive = ($id==$this->activeItemId);
        $label = isset($item['label']) ? $item['label'] : '';
        $result = '';
        $escapeHtml = $this->getView()->plugin('escapeHtml');
        if (isset($item['dropdown'])) {
            $dropdownItems = $item['dropdown'];
            $result .= '<li class="dropdown ' . ($isActive?'active':'') . '">';
            $result .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
            $result .= $escapeHtml($label) . ' <b class="caret"></b>';
            $result .= '</a>';
           
            $result .= '<ul class="dropdown-menu">';
            foreach ($dropdownItems as $item) {
                $link = isset($item['link']) ? $item['link'] : '#';
                $label = isset($item['label']) ? $item['label'] : '';
                $result .= '<li>';
                $result .= '<a href="'.$escapeHtml($link).'">'.$escapeHtml($label).'</a>';
                $result .= '</li>';
            }
            $result .= '</ul>';
            $result .= '</li>';
            
        } else {        
            $link = isset($item['link']) ? $item['link'] : '#';
            $result .= $isActive?'<li class="active">':'<li>';
            $result .= '<a href="'.$escapeHtml($link).'">'.$escapeHtml($label).'</a>';
            $result .= '</li>';
        }
        return $result;
    }
}