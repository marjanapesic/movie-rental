<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

use Application\Repository\Movie as MoviesRepo;

class AddMovie extends Form {

    /** @var  MoviesRepo */
    protected $rpMovies;

    public function __construct(MoviesRepo $moviesRepo, $options = null){

        $this->rpMovies = $moviesRepo;

        parent::__construct('addMovie');

        $this->setAttribute('method', 'post');
        $this->setAttribute('id', "addMovie");


        $this->add(array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'tabindex'=> '1',
                'maxlength' => '70',
                'value'=> ''
            )
        ));


        $years= [];
        for($i=1900; $i <= \date("Y"); $i++){
            $years[$i] = $i;
        }


        $this->add(array(
                'type' => 'Zend\Form\Element\Date',
                'name' => 'releaseDate',
                'attributes' => array(
                    'tabindex'=> '2'
                ),
                'options' => array(
                    'min'  => '1900-01-01',
                    'max'  => '2050-01-01',
                )
            )
        );


        $this->add(array(
            'name' => 'numberOfCopies',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'tabindex'=> '3',
                'value'=> '0'
            )
        ));

        //submit button
        $this->add(array(
            'name' => 'submitButton',
            'type' => 'submit',
            'attributes'=> array(
                'value' => 'Add',
                'tabindex' => '4'
            )
        ));
    }


    public function getInputFilter()
    {

        if (!$this->filter) {
            $this->filter = new InputFilter();
            $factory = new InputFactory();

            //title filter
            $this->filter->add($factory->createInput(array(
                'name' => 'title',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Movie title cannot be empty.'
                            )
                        )
                    ),
                ),
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags')
                )
            )));


            //release year filter
            $this->filter->add($factory->createInput(array(
                'name' => 'releaseDate',
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags')
                )
            )));

            //number of copies filter
            $this->filter->add($factory->createInput(array(
                'name' => 'numberOfCopies',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Number of copies cannot be empty.'
                            )
                        )
                    ),
                ),
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags')
                )
            )));
        }
        return $this->filter;
    }
}