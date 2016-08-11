<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

class LoginForm extends Form {

    public function __construct($options = null){

        parent::__construct('formLoginUser');

        $this->setAttribute('method', 'post');
        $this->setAttribute('id', "formLoginUser");

        //email text area
        $this->add(array(
            'name' => 'email',
            'attributes' => [
                'type'=> 'Zend\Form\Element\Textarea',
                'tabindex'=> '1',
                'maxlength' => '70',
                'value'=> ''
            ],
            'options' => $options
        ));


        //password text area
        $this->add(array(
            'name' => "password",
            'attributes' => ['type'=> 'password',
                'tabindex'=> '2',
                'value'=> ''
            ]
        ));

        //submit button
        $this->add(array(
            'name' => 'buttonLogin',
            'type' => 'submit',
            'attributes'=> array(
                'value' => 'Log in',
                'tabindex' => '3'
            )
        ));
    }


    public function createInputField( $name,$attributes, $options){



    }

    public function getInputFilter()
    {
        if (!$this->filter) {

            $this->filter = new InputFilter();
            $factory = new InputFactory();

            $this->filter->add($factory->createInput(array(
                'name' => 'email',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Email is empty.'
                            )
                        )
                    ),
                    array(
                        'name' => 'EmailAddress',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Invalid email format.'
                            )
                        )
                    )
                ),
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                )
            )));

            $this->filter->add($factory->createInput(array(
                'name' => 'password',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Password is empty.'
                            )
                        )
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 6,
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'Password should have at least 6 characters.'
                            )

                        )
                    )
                ),
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                )
            )));
        }
        return $this->filter;
    }
}