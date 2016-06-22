<?php

/**
 * Custom Form Helper
 *
 * This helper extends Form Helper and uses Html helper. You can apply default site wide
 * settings or options to form elements with the help of this.
 *
 * @author: Aniket
 */
App::uses('FormHelper', 'View/Helper');

class CustomFormHelper extends FormHelper
{
    public $helpers = array('Html');

    /**
     * Generates an HTML FORM element.
     *
     * @param mixed $model The model name for which the form is being defined. Should
     * @param array $options An array of html attributes and options.
     * @return string A formatted opening FORM tag.
     */
    public function create($model = null, $options = array())
    {
        $default = array('class' => 'form-horizontal');
        if (isset($options['class']) && $options['class'] == "") {
            $default['class'] = "";
        }
        $options = array_merge_recursive($default, $options);

        return FormHelper::create($model, $options);
    }

    /**
     * Generates a form label element
     *
     * @param string $fieldName includes "Modelname.fieldname"
     * @param string $text includes text of label
     * @param array $options includes attributes
     * @return string Completed form input
     */
    public function label($fieldName = null, $text = null, $options = array())
    {
        $default = array('class' => 'control-label');
        if (isset($options['class']) && $options['class'] == "") {
            $default['class'] = "";
        }
        $options = array_merge_recursive($default, $options);

        return FormHelper::label($fieldName, $text, $options);
    }

    /**
     * Generates a form input element complete with label and wrapper div
     *
     * @param array $options includes attributes
     * @return string Completed form input
     */
    public function input($fieldName, $options = array())
    {
        $default = array('class' => 'form-control', 'label' => false);
        if (isset($options['class']) && $options['class'] == "") {
            $default['class'] = "";
        }
        $options = array_merge_recursive($default, $options);

        return FormHelper::input($fieldName, $options);
    }

    /**
     * Generate a set of inputs for `$fields`. If $fields is null the fields of current model
     * will be used.
     *
     * @param array $fields An array of fields to generate inputs for, or null.
     * @param array $blacklist A simple array of fields to not create inputs for.
     * @return string Completed form inputs.
     */
    public function inputs($fields = null, $blacklist = null, $options = array())
    {
        $default = array('class' => 'form-control', 'label' => false);
        if (isset($options['class']) && $options['class'] == "") {
            $default['class'] = "";
        }
        $options = array_merge_recursive($default, $options);

        return FormHelper::inputs($fields, $blacklist, $options);
    }

    /**
     * Creates a textarea widget.
     *
     * @param string $fieldName Name of a field, in the form "Modelname.fieldname"
     * @param array $options Array of HTML attributes, and special options above.
     * @return string A generated HTML text input element
     */
    public function textarea($fieldName, $options = array())
    {
        $default = array('class' => 'form-control');
        if (isset($options['class']) && $options['class'] == "") {
            $default['class'] = "";
        }
        $options = array_merge_recursive($default, $options);

        return FormHelper::textarea($fieldName, $options);
    }

    /**
     * Creates a `<button>` tag. The type attribute defaults to `type="submit"`
     * You can change it to a different value by using `$options['type']`.
     *
     * ### Options:
     *
     * - `escape` - HTML entity encode the $title of the button. Defaults to false.
     *
     * @param string $title The button's caption. Not automatically HTML encoded
     * @param array $options Array of options and HTML attributes.
     * @return string A HTML button tag.
     * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#FormHelper::button
     */
    public function button($title, $options = array())
    {
        $default = array('class' => 'btn btn-flat');
        if (isset($options['class']) && $options['class'] == "") {
            $default['class'] = "";
        }
        $options = array_merge_recursive($default, $options);

        return FormHelper::button($title, $options);
    }

    /**
     * Generates a table
     *
     * @param array $options includes options to set attributes of table example: id, class
     * @param array $thead includes <th> data
     * @param array $tbody includes <td> data
     * @return string Complete Table
     */
    public function table($options = array(), $thead = array(), $tbody = array())
    {
        $attribs = "";
        foreach ($options as $key => $value) {
            $attribs .= $key . '="' . $value . '" ';
        }
        $table_start = sprintf('<table %s>', $attribs);

        $table_head = '<thead>';
        if (is_array($thead)) {
            foreach ($thead as $key => $value) {
                $table_head .= '<tr>';
                for ($i = 0; $i < count($value); $i++) {
                    $th_attributes = "";
                    if (!empty($value[$i]['attributes'])) {
                        foreach ($value[$i]['attributes'] as $k => $v) {
                            $th_attributes .= $k . '="' . $v . '" ';
                        }
                    }
                    $table_head .= sprintf('<th %s>', $th_attributes) . $value[$i]['data'] . '</th>';
                }
                $table_head .= '</tr>';
            }
        }
        $table_head .= '</thead>';


        $table_body = '<tbody>';
        foreach ($tbody as $bRow) {
            $table_body .= '<tr>';
            $td_attributes = "";
            foreach ($bRow as $key => $value) {
                if ($key == "attributes") {
                    foreach ($value as $k => $v) {
                        $td_attributes .= $k . '="' . $v . '" ';
                    }
                }
                if ($key == "data") {
                    foreach ($value as $bItem) {
                        $table_body .= sprintf('<td %s>', $td_attributes) . $bItem . '</td>';
                    }
                }
            }
            $table_body .= '</tr>';
        }
        $table_body .= '</tbody>';

        $table_end = '</table>';

        return $table_start . $table_head . $table_body . $table_end;
    }

    /**
     * Generates a anchor tag
     *
     * @param string $title includes title of link
     * @param string $url includes URL of the link
     * @param array $options includes other options
     * @param string $confirmMessage
     * @return string anchor tag
     */
    public function link($title, $url = null, $options = array(), $confirmMessage = false)
    {
        return $this->Html->link(
                        $title, $url, $options, $confirmMessage
        );
    }

    /**
     * Creates a submit button element. This method will generate `<input />` elements that
     * can be used to submit, and reset forms by using $options. image submits can be created by supplying an
     * image path for $caption.
     *
     * ### Options
     *
     * - `div` - Include a wrapping div?  Defaults to true. Accepts sub options similar to
     *   FormHelper::input().
     * - `before` - Content to include before the input.
     * - `after` - Content to include after the input.
     * - `type` - Set to 'reset' for reset inputs. Defaults to 'submit'
     * - Other attributes will be assigned to the input element.
     *
     * ### Options
     *
     * - `div` - Include a wrapping div?  Defaults to true. Accepts sub options similar to
     *   FormHelper::input().
     * - Other attributes will be assigned to the input element.
     *
     * @param string $caption The label appearing on the button OR if string contains :// or the
     *  extension .jpg, .jpe, .jpeg, .gif, .png use an image if the extension
     *  exists, AND the first character is /, image is relative to webroot,
     *  OR if the first character is not /, image is relative to webroot/img.
     * @param array $options Array of options. See above.
     * @return string A HTML submit button
     * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#FormHelper::submit
     */
    public function submit($caption = null, $options = array())
    {
        $default = array('class' => 'btn btn-flat');
        if (isset($options['class']) && $options['class'] == "") {
            $default['class'] = "";
        }
        $options = array_merge_recursive($default, $options);

        return FormHelper::submit($caption, $options);
    }

}