<?php

declare(strict_types=1);

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class CommentForm extends Form
{
    public function __construct()
    {
        parent::__construct('comment-form');
        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilter();  
    }
    
    protected function addElements()
    {
        $this->add([
            'type'  => 'text',
            'name' => 'author',
            'attributes' => [
                'id' => 'author'
            ],
            'options' => [
                'label' => 'Author',
            ],
        ]);
        
        $this->add([
            'type'  => 'textarea',
            'name' => 'comment',
            'attributes' => [
                'id' => 'comment'
            ],
            'options' => [
                'label' => 'Comment',
            ],
        ]);
                
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Save',
                'id' => 'submitbutton',
            ],
        ]);
    }
    
    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
        
        $inputFilter->add([
                'name'     => 'author',
                'required' => true,
                'filters'  => [                    
                    ['name' => 'StringTrim'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 128
                        ],
                    ],
                ],
            ]);
        
        $inputFilter->add([
                'name'     => 'comment',
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