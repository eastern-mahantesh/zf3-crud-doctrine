<?php

declare(strict_types=1);

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Application\Entity\Post;

class PostForm extends Form
{
    public function __construct()
    {
        parent::__construct('post-form');
        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilter();  
    }
    
    protected function addElements()
    {
        $this->add([
            'type'  => 'text',
            'name' => 'title',
            'attributes' => [
                'id' => 'title'
            ],
            'options' => [
                'label' => 'Title',
            ],
        ]);

        $this->add([
            'type'  => 'textarea',
            'name' => 'content',
            'attributes' => [                
                'id' => 'content'
            ],
            'options' => [
                'label' => 'Content',
            ],
        ]);
        
        $this->add([
            'type'  => 'select',
            'name' => 'status',
            'attributes' => [                
                'id' => 'status'
            ],
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    Post::STATUS_PUBLISHED => 'Published',
                    Post::STATUS_DRAFT => 'Draft',
                ]
            ],
        ]);
        
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Create',
                'id' => 'submitbutton',
            ],
        ]);
    }
    
    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
        
        $inputFilter->add([
                'name'     => 'title',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                    ['name' => 'StripNewlines'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 1024
                        ],
                    ],
                ],
            ]);
        
        $inputFilter->add([
                'name'     => 'content',
                'required' => true,
                'filters'  => [                    
                    ['name' => 'StripTags'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 4096
                        ],
                    ],
                ],
            ]);   
    }
}