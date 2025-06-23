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
    public $confirm;


    const FORM_CLASS = "d-flex justify-content-center align-items-center flex-column x_panel";

    const TITLE_CONTAINER_CLASS = "col-12 text-center";
    const TITLE_CLASS = "h1 text-black";

    // Inputs
    const FIELD_CONTAINER_CLASS = " row col-12 col-md-8 my-2 justify-content-start ";
    const LABEL_CONTAINER_CLASS = " row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4 ";
    const LABEL_CLASS = " h6 m-0 fw-bold px-2 ";
    const INPUT_CONTAINER_CLASS = " row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start ";
    const INPUT_CLASS = " form-control ";
    const MULTI_INPUT_CONTAINER_CLASS = "row col-12 m-0 p-0 mx-2";
    const MULTI_LABEL_CLASS = " text-left mx-2 h6 ";
    const SUBMIT_CONTAINER_CLASS = "";
    const SUBMIT_CLASS = "";

    const INPUTS_TYPE_CLASSES = [
        'textarea' => '',
        'text' => '',
        'integer' => '',
        'decimal' => '',
        'select' => ' select2 ',
        'checkbox' => ' flat ',
        'radio' => ' flat  ',
        'date' => '',
    ];

    public function __construct(
        string $action, 
        string $method, 
        string $title, 
        string $submitText,
        string $id,
        array $fields,
        bool $confirm = true
        )
    {
        $this->action = $action;
        $this->method = $method;
        $this->title = $title;
        $this->submitText = $submitText;
        $this->id = $id;
        $this->fields = $fields;
        $this->confirm = $confirm;
    }

    /**
     * Dibuja en pantalla el formulario HTML
     */
    public function DrawForm()
    {
        echo '<form action="' . $this->action . '" method="' . $this->method . '" id="' . $this->id . '" class="' . self::FORM_CLASS . ($this->confirm ? ' confirm-form ' : '') . '">';

            echo '<div class="' . self::TITLE_CONTAINER_CLASS . '">';
                echo '<h1 class="' . self::TITLE_CLASS . '">';
                    echo $this->title;
                echo '</h1>';
            echo '</div>';

            foreach($this->fields as $field){
                $hidden = false;
                if(isset($field['hidden'])){
                    $hidden = $field['hidden'];
                }

                if($field['name'] === 'id' || $hidden){
                    echo '<input type="hidden" value="' . $field['value'] . '" name="' . $field['name'] . '">';
                    continue;
                }                

                echo '<div class="' . self::FIELD_CONTAINER_CLASS . '">';
                    echo '<div class="' . self::LABEL_CONTAINER_CLASS . '">';
                        echo '<label class="' . self::LABEL_CLASS . '" for="' . $field['id'] . '">' . $field['display'] . '</label>';
                    echo '</div>';
    
                    echo '<div class="' . self::INPUT_CONTAINER_CLASS . '">';
                        $id = 'id="' . $field['id'] . '" ';
                        $class = 'class="' . self::INPUT_CLASS . ' col-10 col-md-' . $field['size'] . self::INPUTS_TYPE_CLASSES[$field['type']] . '"' ;
                        $placeholder = 'placeholder="' . $field['placeholder'] . '"';
                        $required = $field['required'] === true ? 'required ' : '';

                        if($field['type'] === 'checkbox')
                            $name = 'name="' . $field['name'] . '[]" ';
                        else
                            $name = 'name="' . $field['name'] . '" ';

                        if(in_array($field['type'], ['integer', 'decimal', 'text', 'date'])){
                            $input = '<input ' . $id . $name . $class . $placeholder . $required . ' value="' . $field['value'] . '" ';
                            if($field['type'] === 'integer')
                                $input .= ' type="number" ';
                            else if($field['type'] === 'decimal')
                                $input .= ' type="number" step="0.00001" ';
                            else
                                $input .= ' type= "' . $field['type'] . '" ';
                            
                            if(in_array($field['type'], ['integer', 'decimal']))
                                $input .= ' onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46)" ';

                            $input .= $this->GetHTMLProperties($field);

                            $input .= '>';
                            echo $input;
                        }
                        else if($field['type'] === 'textarea'){
                            $textarea = '<textarea ' . $id . $name . $class . $required . ' rows="3" columns="50" value="' . $field['value'] . '"';
                            $textarea .= $this->GetHTMLProperties($field);                           

                            echo $textarea . '>' . $field['value'] . '</textarea> ';
                        }
                        else if($field['type'] === 'select'){
                            echo '<div class="row m-0 p-0 col-12 col-md-' . $field['size'] . '">';
                                echo '<select ' . $id . $name . $class . $required . ' >';
                                    echo '<option value="">&nbsp;</option>';
                                    foreach($field['elements'] as $option){
                                        echo '<option value="' . $option['value'] . '"';
                                        if($field['value'] === $option['value'])
                                            echo ' selected ';
                                        echo '>';
                                        echo $option['display'] . '</option>';
                                    }
                                echo '</select>';
                            echo '</div>';
                        }
                        else if(in_array($field['type'], ['radio', 'checkbox'])){
                            foreach($field['elements'] as $element){
                                echo '<div class="' . self::MULTI_INPUT_CONTAINER_CLASS . '">';
                                    echo '<input id="' . $field['name'] . '-' . $element['value'] . '" type="' . $field['type'] . '" ' . $name . ' value="' . $element['value'] . '" ' . $class;
                                    if(in_array($element['value'], $field['value']))
                                        echo ' checked ';
                                    echo '>';
                                    echo '<label for="' . $field['name'] . '-' . $element['value'] . '" class="' . self::MULTI_LABEL_CLASS . '">' . $element['display'] . '</label>';
                                echo '</div>';
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

    private function GetHTMLProperties($field){
        $htmlProperties = '';
        if(isset($field['max'])){
            if($field['type'] === 'date')
                $htmlProperties .= ' max="' . $field['max'] . '" ';
            else
                $htmlProperties .= ' maxlength="' . $field['max'] . '" ';
        }

        if(isset($field['min'])){
            if($field['type'] === 'date')
                $htmlProperties .= ' min="' . $field['min'] . '" ';
            else
                $htmlProperties .= ' minlength="' . $field['min'] . '" ';
        }
        
        if(isset($field['disabled'])){
            if($field['disabled'] === true)
                $htmlProperties .= ' disabled ';
        }

        return $htmlProperties;
    }
}

/*
Here is an example for que fields

$fields = [
    [
        'name' => 'name',
        'display' => 'Nombre',
        'placeholder' => 'Nombre del producto',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 255,
        'min' => 1,
        'required' => true,
        'value' => $edit ? $target_product['name'] : ''
    ],
    [
        'name' => 'price',
        'display' => 'Precio',
        'placeholder' => 'Precio ($)',
        'id' => 'price',
        'type' => 'decimal',
        'size' => 4,
        'required' => true,
        'value' => $edit ? $target_product['price'] : ''
    ],
    [
        'name' => 'category',
        'display' => 'Categorías',
        'placeholder' => '',
        'id' => 'category',
        'type' => 'checkbox',
        'size' => 6,
        'required' => true,
        'value' => ['ids', 'of', 'product', 'categories'],
        'elements' => [
            [
                'display' => 'Belleza',
                'value' => '1'
            ],
            [
                'display' => 'Papelería',
                'value' => '2'
            ],
            [
                'display' => 'Comida',
                'value' => '3'
            ],
        ]
    ],
    [
        'name' => 'type',
        'display' => 'Tipo',
        'placeholder' => '',
        'id' => 'type',
        'type' => 'select',
        'size' => 6,
        'required' => true,
        'value' => '',
        'elements' => [
            [
                'display' => 'Físico',
                'value' => '1'
            ],
            [
                'display' => 'Líquido',
                'value' => '2'
            ],
            [
                'display' => 'Gaseoso',
                'value' => '3'
            ],
        ]
    ],
];
*/