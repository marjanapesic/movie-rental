<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

class SignUp extends Form {

    /** @var  \Application\Repository\User */
    protected $rpUser;

    public function __construct($options = null){

        parent::__construct('formSignUp');

        $this->setAttribute('method', 'post');
        $this->setAttribute('id', "formSignUp");

        //email text input
        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'attributes' => array(
                'tabindex'=> '1',
                'maxlength' => '70',
                'value'=> ''
            ),
            'options' => array(
                'label' => 'Email'
            )
        ));

        //password text input
        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'attributes' => array(
                'tabindex'=> '2',
                'value'=> ''
            ),
            'options' => array(
                'label' => 'Password'
            )
        ));

        //submit button
        $this->add(array(
            'name' => 'submitButton',
            'type' => 'submit',
            'attributes'=> array(
                'value' => 'Sign up',
                'tabindex' => '3'
            )
        ));
    }


    public function getInputFilter()
    {

        if (!$this->filter) {
            $this->filter = new InputFilter();
            $factory = new InputFactory();

            //email filter
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
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags')
                )
            )));

            //password filter
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
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags')
                )
            )));
        }
        return $this->filter;
    }


    public function isValid()
    {
        $isValid = parent::isValid();

        if($isValid){
            if($this->rpUser->findBy(['email' => $this->get("email")->getValue()])){
                $isValid = false;

                $messages = $this->getMessages();
                $messages['email'][] = "Email already exists";
                $this->setMessages($messages);
            }
        }

        return $isValid;
    }

    public function setRpUser(\Application\Repository\User $rpUser){
        $this->rpUser = $rpUser;
    }
}