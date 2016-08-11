<?php
namespace Application\Form;

use Application\Repository\MovieRental as RpMovieRental;
use Application\Repository\Movie as RpMovie;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;


class EditMovie extends Form
{
    const FIELD_MOVIE_ID = 'movieId';
    const FIELD_TITLE = 'title';
    const FIELD_RELEASE_DATE = 'releaseDate';
    const FIELD_NUMBER_OF_COPIES = 'numberOfCopies';
    const SUBMIT_BUTTON = 'submitButton';

    /** @var RpMovieRental $rpMovieRental */
    private $rpMovieRental;

    public function __construct(RpMovie $rpMovie, RpMovieRental $rpMovieRental, $options = null)
    {
        parent::__construct('editMovie');

        $this->rpMovieRental = $rpMovieRental;
        $this->rpMovie = $rpMovie;

        $this->setAttribute('method', 'post');
        $this->setAttribute('id', "addMovie");


        $this->add(array(
            'name' => self::FIELD_MOVIE_ID,
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => [
                'required' => true
            ]
        ));

        $this->add(array(
            'name' => self::FIELD_TITLE,
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'tabindex' => '1',
                'maxlength' => '70',
                'value' => ''
            )
        ));


        $years = [];
        for ($i = 1900; $i <= \date("Y"); $i++) {
            $years[$i] = $i;
        }


        $this->add(array(
                'type' => 'Zend\Form\Element\Date',
                'name' => self::FIELD_RELEASE_DATE,
                'attributes' => array(
                    'tabindex' => '2'
                ),
                'options' => array(
                    'min' => '1900-01-01',
                    'max' => '2050-01-01',
                )
            )
        );


        $this->add(array(
            'name' => self::FIELD_NUMBER_OF_COPIES,
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'tabindex' => '3',
                'value' => '0'
            )
        ));

        //submit button
        $this->add(array(
            'name' => 'submitButton',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Save',
                'tabindex' => '4'
            )
        ));
    }



    public function getInputFilter()
    {

        if (!$this->filter) {
            $this->filter = new InputFilter();
            $factory = new InputFactory();


            $this->filter->add($factory->createInput(array(
                'name' => self::FIELD_MOVIE_ID,
            )));

            //title filter
            $this->filter->add($factory->createInput(array(
                'name' => self::FIELD_TITLE,
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
                'name' => self::FIELD_RELEASE_DATE,
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags')
                )
            )));

            //number of copies filter
            $this->filter->add($factory->createInput(array(
                'name' => self::FIELD_NUMBER_OF_COPIES,
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



    public function isValid(){

        $isValid = parent::isValid();

        if($isValid){

            /** @var \Application\Entity\Movie $movie */
            $movie = $this->rpMovie->find($this->get(self::FIELD_MOVIE_ID)->getValue());

            $cntOpenMovieRentals = count($this->rpMovieRental->getOpenMovieRentals($movie));

            if ($cntOpenMovieRentals > $this->get(self::FIELD_NUMBER_OF_COPIES)->getValue()) {

                $isValid = false;

                $messages = $this->get(self::FIELD_NUMBER_OF_COPIES)->getMessages();
                $messages[] = "Number of copies cannot be lower than number of movies rented at the moment (" . $cntOpenMovieRentals . ").";
                $this->setMessages($messages);
                $this->get(self::FIELD_NUMBER_OF_COPIES)->setMessages($messages);
            }
        }

        return $isValid;
    }
}