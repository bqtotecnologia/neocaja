<?php

/**
 * Clase que se encarga de construir formularios HTML.
 * Antes de mostrar el formulario debe estar encerrado en un div con la siguiente clase "row col-12 justify-content-center". 
 * Este ocupará siempre todo el ancho de su contenedor.
 */
class FormBuilder
{
    public $action;
    public $fields;
    public $method;
    public $title;
    public $submitText;
    public $id;


    const FORM_CLASS = "d-flex justify-content-center align-items-center flex-column x_panel confirm-form";

    const TITLE_CONTAINER_CLASS = "col-12 text-center";
    const TITLE_CLASS = "h1 text-black";

    // Inputs
    const FIELD_CONTAINER_CLASS = " row col-12 col-md-8 my-2 justify-content-start ";
    const LABEL_CONTAINER_CLASS = " row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4 ";
    const LABEL_CLASS = " h6 m-0 fw-bold px-2 ";
    const INPUT_CONTAINER_CLASS = " row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start ";
    const INPUT_CLASS = " form-control ";
    const MULTI_LABEL_CLASS = " text-left ";
    const SUBMIT_CONTAINER_CLASS = "";
    const SUBMIT_CLASS = "";

    const INPUTS_TYPE_CLASSES = [
        'textarea' => '',
        'text' => '',
        'integer' => '',
        'decimal' => '',
        'select' => ' select2 ',
        'checkbox' => ' flat ',
        'radio' => ' flat  '
    ];

    public function __construct(
        string $action, 
        string $method, 
        string $title, 
        string $submitText,
        string $id,
        array $fields
        )
    {
        $this->action = $action;
        $this->fields = $fields;
        $this->method = $method;
        $this->title = $title;
        $this->submitText = $submitText;
        $this->id = $id;
    }

    /**
     * Dibuja en pantalla el formulario HTML
     */
    public function DrawForm()
    {
        echo '<form action="' . $this->action . '" method="' . $this->method . '" id="' . $this->id . '" class="' . self::FORM_CLASS . '">';

            echo '<div class="' . self::TITLE_CONTAINER_CLASS . '">';
                echo '<h1 class="' . self::TITLE_CLASS . '">';
                    echo $this->title;
                echo '</h1>';
            echo '</div>';

            foreach($this->fields as $field){
                if($field['name'] === 'id'){
                    echo '<input type="hidden" value="' . $field['value'] . '" name="id">';
                    continue;
                }

                echo '<div class="' . self::FIELD_CONTAINER_CLASS . '">';
                    echo '<div class="' . self::LABEL_CONTAINER_CLASS . '">';
                        echo '<label class="' . self::LABEL_CLASS . '" for="' . $field['id'] . '">' . $field['display'] . '</label>';
                    echo '</div>';
    
                    echo '<div class="' . self::INPUT_CONTAINER_CLASS . '">';
                        $id = 'id="' . $field['id'] . '" ';
                        $name = 'name="' . $field['name'] . '" ';
                        $class = 'class="' . self::INPUT_CLASS . ' col-10 col-md-' . $field['size'] . self::INPUTS_TYPE_CLASSES[$field['type']] . '"' ;
                        $placeholder = 'placeholder="' . $field['placeholder'] . '"';
                        $required = $field['required'] === true ? 'required ' : '';

                        if(in_array($field['type'], ['integer', 'decimal', 'text'])){
                            $input = '<input ' . $id . $name . $class . $placeholder . $required . ' value="' . $field['value'] . '" ';
                            if($field['type'] === 'integer')
                                $input .= ' type="number" ';
                            else if($field['type'] === 'decimal')
                                $input .= ' type="number" step="0.1" ';
                            else
                                $input .= ' type= "' . $field['type'] . '" ';
                            
                            if(in_array($field['type'], ['integer', 'decimal']))
                                $input .= ' onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46)" ';

                            if(isset($field['max']))
                                $input .= ' maxlength="' . $field['max'] . '" ';

                            if(isset($field['min']))
                                $input .= ' minlength="' . $field['min'] . '" ';

                            $input .= '>';
                            echo $input;
                        }
                        else if($field['type'] === 'textarea'){
                            echo '<textarea ' . $id . $name . $class . $required . ' rows="50 columns="4" "value="' . $field['value'] . '"';
                            echo '></textarea> ';
                        }
                        else if($field['type'] === 'select'){
                            echo '<select ' . $id . $name . $class . $required . ' >';
                                echo '<option value="">&nbsp;</option>';
                                foreach($field['elements'] as $option){
                                    echo '<option value="' . $option['value'] . '">' . $option['display'] . '</option>';
                                }
                            echo '</select>';
                        }
                        else if($field['type'] === 'radio'){
                            foreach($field['elements'] as $element){
                                echo '<div class="row col-12 m-0 p-0">';
                                    echo '<input type="radio" ' . $name . '" ' . ' value="' . $element['value'] . '" ' . $class . '>';
                                    echo '<label class="' . self::MULTI_LABEL_CLASS . '">' . $element['display'] . '</label>';
                                echo '</div>';
                            }
                        }
                        else if($field['type'] === 'checkbox'){
                            foreach($field['elements'] as $element){
                                echo '<input type="radio" ' . $name . '[]" ' . ' value="' . $element['value'] . '" ' . $class . '>';
                                echo '<label class="' . self::MULTI_LABEL_CLASS . '">' . $element['display'] . '</label>';
                            }
                        }
                        

                    echo '</div>';
                echo '</div>';
            }

            echo '<div>';
                echo '<h3 class="text-danger" id="error-displayer"></h3>';
            echo '</div>';

            echo '<div class="row col-12 m-0 p-0 justify-content-center">';
                echo '<button type="submit" class="btn btn-success">';
                    echo $this->submitText;
                echo '</button>';
            echo '</div>';


        echo '</form>';
    }
}