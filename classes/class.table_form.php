<?php



define('VALIDATE_CUSTOM',  0);

define('VALIDATE_EMPTY',   1);

define('VALIDATE_NUMERIC', 2);

define('VALIDATE_EMAIL',   3);



class table_form

{

    var $table;

    var $extraValidation;

    var $posted;

    var $_id;

    var $_action;

    var $_method;

    var $_tfields;

    var $_fields;

    var $_validate;

    var $_hasfiles;

    var $_java_end;

    var $_db;



    function table_form($table, $form_id, $method='post', $action='')

    {

        $this->table     = $table;

        $this->posted    = false;

        $this->_id       = $form_id;

        $this->_method   = $method;

        $this->_action   = $action;

        $this->_tfields  = array();

        $this->_fields   = array();

        $this->_validate = array();

        $this->_hasfiles = false;

        $this->_java_end = '';

        $this->extraValidation = '';

        $this->_db = new Database();

        if ($this->_db->isError()) die($this->_db->error());



        $res = $this->_db->query("DESCRIBE `$this->table`");

        if ($this->_db->isError()) die($this->_db->error());

        while ($row = $this->_db->fetch($res)) {

            $this->_tfields[$row['Field']] = $row;

        }

    }



	// read only input

    function add_readonly($label, $name)

    {

        $f = array();

        $f['name'] = $label;

        $f['id']   = $name;

        $f['type'] = 'readonly';



        $this->_fields[$name] = $f;

    }



	// input hidden

    function add_hidden($name)

    {

        $f = array();

        $f['id']   = $name;

        $f['type'] = 'hidden';



        $this->_fields[$name] = $f;

    }

	

	 function add_hidden_value($name,$value)

    {

        $f = array();

        $f['id']   = $name;

        $f['type'] = 'hidden';

     $f['value'] = $value;

        $this->_fields[$name] = $f;

    }





	// file upload input

    function add_file($label,$name,$size)

    {

        $f['name'] = $label;

        $f['id']   = $name;

        $f['type'] = 'file';

        $f['size'] = $size;

        $this->_fields[$name] = $f;

        $this->_hasfiles = true ;

    }





	// text area input

    function add_textarea($label,$name,$cont='')

    {

        $f['name'] = $label;

        $f['id']   = $name;

        $f['type'] = 'textarea';

        $f['content']= $cont;



        $this->_fields[$name] = $f;

    }



	// checkbox

    function add_checkbox($label,$name)

    {

        $f['name'] = $label;

        $f['id']   = $name;

        $f['type'] = 'checkbox';

        $this->_fields[$name] = $f;

    }



    function add_text($label, $name, $size = 20,$type='text', $validate = VALIDATE_CUSTOM, $maxlength = 0) //$type paswprd, sau text

    {

        $f = array();

        $f['name'] = $label;

        $f['id']   = $name;

        $f['type'] = $type;

        $f['size'] = $size;

        if ($maxlength) $f['maxlength'] = $maxlength;

        

        if (is_array($this->_tfields[$name])) {

            if (!$maxlength) {

                if ($max = $this->_getMaxLength($name)) {

                    $f['maxlength'] = $max;

                }

            }

            if (!empty($this->_tfields[$name]['Default'])) {

                $f['value'] = $this->_tfields[$name]['Default'];

            }

            if ($this->_tfields[$name]['Null'] != 'YES') {

                $this->_validate[] = array($name, 'TEXT', VALIDATE_EMPTY);

            }

        }

        if ($validate != VALIDATE_CUSTOM) {

            $this->_validate[] = array($name, 'TEXT', $validate);

        }



        $this->_fields[$name] = $f;

    }



	// select dropdown

    function add_select($label, $name, $table, $table_id='', $show='', $sort='', $order='',$where='') 

    {

        $f = array();

        $f['name'] = $label;

        $f['id']   = $name;

        $f['type'] = 'select';

        $f['options'] = array();

        if (is_array($this->_tfields[$name])) {

            $val = $this->_tfields[$name]['Default'];



            if ($this->_tfields[$name]['Null'] != 'YES') {

                $this->_validate[] = array($name, 'SELECT', VALIDATE_EMPTY);

            }

        }



        if (empty($table_id)) $table_id = $name;

        if (empty($show)) $show = $table_id;

        $sql = "SELECT `$table_id` AS `value`, `$show` AS `name` FROM `$table`";

        

        if (!empty($where)) {

            $sql .= " WHERE $where ";

        }

		if (!empty($sort)) {

            $sql .= "ORDER BY `$sort` $order";

        }

		//echo $sql;exit;

        $res = $this->_db->query($sql);

        if ($this->_db->isError()) die($this->_db->error());

        while ($row = $this->_db->fetch($res)) {

			$row['name'] = stripslashes($row['name']);

            if ($row['value'] == $val) $row['selected'] = true;

            $f['options'][] = $row;

        }



        $this->_fields[$name] = $f;

    }


	function add_edittext($label, $name, $table, $table_id='', $show='', $sort='', $order='',$where='') 
    {

        $f = array();

        $f['name'] = $label;

        $f['id']   = $name;

        $f['type'] = 'select';

        $f['options'] = array();

        if (is_array($this->_tfields[$name])) {

            $val = $this->_tfields[$name]['Default'];



            if ($this->_tfields[$name]['Null'] != 'YES') {

                $this->_validate[] = array($name, 'SELECT', VALIDATE_EMPTY);

            }

        }



        if (empty($table_id)) $table_id = $name;

        if (empty($show)) $show = $table_id;

        $sql = "SELECT `$table_id` AS `value`, `$show` AS `name` FROM `$table`";

        

        if (!empty($where)) {

            $sql .= " WHERE $where ";

        }

		if (!empty($sort)) {

            $sql .= "ORDER BY `$sort` $order";

        }

		//echo $sql;exit;

        $res = $this->_db->query($sql);

        if ($this->_db->isError()) die($this->_db->error());

        while ($row = $this->_db->fetch($res)) {

			$row['name'] = stripslashes($row['name']);

            if ($row['value'] == $val) $row['selected'] = true;

            $f['options'][] = $row;

        }



        $this->_fields[$name] = $f;

    }

	

	// select dropdown

    function add_dropdown($label, $name, $array) 

    {

        $f = array();

        $f['name'] = $label;

        $f['id']   = $name;

        $f['type'] = 'select';

        $f['options'] = $array;



        $this->_fields[$name] = $f;

    }



    function add_image($label,$name) {

	

		$f = array();

        $f['name'] = $label;

        $f['id']   = $name;

        $f['type'] = 'image';



        $this->_fields[$name] = $f;	

	}



    function link_selects($child, $parent, $linktable, $parent_id='', $child_id='')

    {

        $c = $this->_fields[$child];

        $p = $this->_fields[$parent];



        if (!is_array($c)) return;

        if ($c['type'] != 'select') return;

        if (!is_array($p)) return;

        if ($p['type'] != 'select') return;



        if (empty($parent_id)) $parent_id = $parent;

        if (empty($child_id))  $child_id  = $child;



        $sql = "SELECT `$parent_id` AS `pid`, `$child_id` AS `cid` FROM `$linktable`";

        $res = $this->_db->query($sql);

        if ($this->_db->isError()) die($this->_db->error());

        $arr = array();

        while ($row = $this->_db->fetch($res)) {

            if ($arr[$row['cid']]) {

                $arr[$row['cid']] .= ' '.$row['pid'];

            } else {

                $arr[$row['cid']] = $row['pid'];

            }

        }

        foreach ($c['options'] as $key=>$val) {

            if (!function_exists('a__addlink_')) { function a__addlink_($str){return "link_$str";} }

            $classes = implode(' ', array_map('a__addlink_', explode(' ', $arr[$val['value']])));

            if ($c['options'][$key]['class']) {

                $c['options'][$key]['class'] .= " $classes";

            } else {

                $c['options'][$key]['class'] = $classes;

            }

        }

        $p['onchange'] = "linkedSelect(this, '$child')";

        $c['onchange'] = "if (this.options[this.selectedIndex].style.display!='') this.selectedIndex=0;";

        $this->_java_end .= "linkedSelect(document.getElementById('$parent'), '$child');";



        $this->_fields[$child]  = $c;

        $this->_fields[$parent] = $p;

    }



	// radio button

    function add_radio($label, $name, $options)

    {

        $f = array();

        $f['name'] = $label;

        $f['id']   = $name;

        $f['type'] = 'radio';

        $f['options'] = $options;

        $f['class'] = 'checkbox';

        if (is_array($this->_tfields[$name])) {

            if (!empty($this->_tfields[$name]['Default'])) {

                $f['value'] = $this->_tfields[$name]['Default'];

            }

            if ($this->_tfields[$name]['Null'] != 'YES') {

                $this->_validate[] = array($name, 'RADIO', VALIDATE_EMPTY);

            }

        }



        $this->_fields[$name] = $f;

    }



    function addEvent($field, $event, $action)

    {

        if (is_array($this->_fields[$field])) {

            $this->_fields[$field][$event] = $action;

        }

    }



    function setValues($values)

    {

        foreach ($values as $field=>$value) {

            if (is_array($this->_fields[$field])) {

              if ($this->_fields[$field]['type'] !='textarea')  // nu afiseaza value in textarea

                $this->_fields[$field]['value'] = stripslashes($value);



            }

        }

    }



    function show_java($noecho = false)

    {

        $out = '

<script type="text/javascript" src="js/forms.js"></script>

<script type="text/javascript"><!--//--><![CDATA[

function TF_checkForm(form)

{

    if (!form) return false;

';

        foreach ($this->_validate as $val) {

            $name = $this->_fields[$val[0]]['name'];

            switch ($val[2]) {

                case VALIDATE_EMPTY:

                    $func = 'hasValue';

                    $msg  = 'Please enter required field';

                    break;

                case VALIDATE_NUMERIC:

                    $func = 'isInteger';

                    $msg  = 'Invalid number';

                    break;

                case VALIDATE_EMAIL:

                    $func = 'isEmail';

                    $msg  = 'Invalid email';

                    break;

                default:

                    $func = '';

                    $msg  = '';

            }

            if ($func && $msg) {

                $out .= "

    if (form.$val[0] && !$func(form.$val[0], '$val[1]' )) {

    	if (!doError(form, form.$val[0], '$val[1]', '$msg - $name'))

    		return false;

    }

";

            }

        }

        if ($this->extraValidation) {

            $out .= '    return '.$this->extraValidation.'(form);';

        } else {

            $out .= '    return true;';

        }

        $out .= '

}

//]]></script>';



        if ($noecho) return $out;

        echo $out;

    }



    function begin_form($noecho = false)

    {

        $out .= "<form action=\"$this->_action\" method=\"$this->_method\"".($this->_hasfiles?' enctype="multipart/form-data"':'')." id=\"$this->_id\" onsubmit=\"return TF_checkForm(this)\">";



        if ($noecho) return $out;

        echo $out;

    }



    function get_field()

    {

        if (!isset($this->__key)) {

            $this->__keys = array();

            foreach ($this->_fields as $key=>$val) {

                $this->__keys[] = $key;

            }

            $this->__key = 0;

        }



        $fld = $this->_fields[$this->__keys[$this->__key]];

        if ($fld) {

            $this->__key++;

        } else {

            unset($this->__key);

        }



        return $fld;

    }



    function show_label($field, $noecho = false)

    {

        $out = $field['name'];



        if ($noecho) return $out;

        echo $out;

    }



    function show_input($field, $noecho = false)

    {

        $out = '';

        unset($field['name']);

        $type = $field['type'];

        unset($field['type']);

        switch ($type) {

            case 'readonly':

                $out .= "<span id=\"$field[id]\">".htmlspecialchars($field['value']).'</span>';

                break;

            case 'hidden':

                $out .= '<input class="hidden" type="hidden"'.$this->_attr('name', $field, 'id');

                foreach ($field as $key=>$val) $out .= $this->_attr($key, $field);

                $out .= ' />';

                break;

            case 'textarea':  //afiseaza textarea

                $out .= '<textarea class="text" '.$this->_attr('name', $field, 'id');

                foreach ($field as $key=>$val) if($key!='content')$out .= $this->_attr($key, $field);

                $out .= '>'.stripslashes($field[content]).'</textarea>';

                break;

            case 'checkbox':  //afiseaza checkbox

                $out .= '<input type="checkbox"'.$this->_attr('name', $field, 'id');

                foreach ($field as $key=>$val) $out .= $this->_attr($key, $field);

                if ($field[value]=="on")

                    $out .= ' checked />';

                else

                  $out .= ' />';

                break;

            case 'text':
			
                $out .= '<input class="text-input small-input" type="text"'.$this->_attr('name', $field, 'id');

                foreach ($field as $key=>$val) $out .= $this->_attr($key, $field);
				
                $out .= ' />';

                break;

            case 'file':

                $out .= '<input class="text-input small-input" type="file"'.$this->_attr('name', $field, 'id');

                foreach ($field as $key=>$val) $out .= $this->_attr($key, $field);

                $out .= ' />';

                break;

            case 'password':  //text field pentru password

                $out .= '<input class="text-input small-input" type="password"'.$this->_attr('name', $field, 'id');

                foreach ($field as $key=>$val) $out .= $this->_attr($key, $field);

                $out .= ' />';

                break;

            case 'select':

                $out .= '<select class="small-input" '.$this->_attr('name', $field, 'id');

                $opts = $field['options'];

                foreach ($opts as $key=>$option) {

                    if ($field['value'] == $option['value']) $opts[$key]['selected'] = true;

                }

                unset($field['options']);

                unset($field['value']);

                foreach ($field as $key=>$val) $out .= $this->_attr($key, $field);

                $out .= '>';

                $out .= '<option value="">-- Please Select --</option>';

                foreach ($opts as $option) {

                    $sel = $option['selected']?' selected="selected"':'';

                    $class = $this->_attr('class', $option);

                    $out .= '<option value="'.htmlspecialchars($option['value'])."\"$sel$class>".htmlspecialchars($option['name']).'</option>';

                }

                $out .= '</select>';

                break;

            case 'radio':

                $name = $field['id'];

                $radio = "<input type=\"radio\" name=\"$name\" id=\"{$name}_";

                foreach ($field['options'] as $value=>$label) {

                    $sel = ($field['value'] == $value)?' checked="checked"':'';

                    $out .= $radio.htmlspecialchars($value)."\" value=\"$value\"$sel /><label for=\"".htmlspecialchars($name.'_'.$value).'" style="display:inline">'.htmlspecialchars($label).'</label>';

                }

                break;



			case 'image':

				$out .= '

				<input ';

				foreach ($field as $key=>$val) $out .= $this->_attr($key, $field);

				$out .=' type="file" />';

				

				break;

        }



        if ($noecho) return $out;

        echo $out;

    }



    function end_form($noecho = false)

    {

        $out .= '</form>';

        if ($this->_java_end) {

            $out .= '<script type="text/javascript"><!--//--><![CDATA['."\r\n".$this->_java_end."\r\n".'//]]></script>';

        }



        if ($noecho) return $out;

        echo $out;

    }



    function setPostFields($fields, $id_field, $id_value, $insert=false)

    {

        foreach ($fields as $field) {

            if (is_array($this->_fields[$field])) {

                if (!empty($_POST[$field])) {

                    $this->_fields[$field]['value'] = sanitize($_POST[$field]);

                }

            }

        }



        if ($insert) {

            if (!function_exists('a__getfield_')) { function a__getfield_($str) { return addslashes($_POST[$str]); } }

            $names = '`'.implode('`, `', $fields).'`';

            $values = "'".implode("', '", array_map('a__getfield_', $fields))."'";

            $sql = "INSERT INTO `$this->table` ($names) VALUES ($values)";

            $this->posted = true;

        } else {

            if (!function_exists('a__getpair_')) { function a__getpair_($str) { return "`$str`='".addslashes($_POST[$str])."'"; } }

            $pairs = implode(', ', array_map('a__getpair_', $fields));

            $sql = "UPDATE `$this->table` SET $pairs WHERE `$id_field`='".addslashes($id_value)."'";

            $this->posted = true;

        }



        $this->_db->query($sql);

        if ($this->_db->isError()) die($this->_db->error());

    }



    function _getMaxLength($name)

    {

        if (!is_array($this->_tfields[$name])) return 0;



        $r = $this->_tfields[$name]['type'];

        if (empty($r)) return null;

        if (($p = strpos($r, '(')) === false) return 0;

        if (($q = strpos($r, ')', $p)) === false) return 0;

        $num = substr($r, $p+1, $q-$p-1);



        return $num*1;

    }



    function _attr($name, $field, $key='')

    {

        if (!$key) $key = $name;



        if ($field[$key]) {
		
            return " $name=\"".htmlspecialchars($field[$key]).'"';

        }

    }





	// extracts a row with a certain id and field

    function get_row($id_field,$id_val)

    {

        $db = new Database();

        if ($db->isError()) die($db->error());

        $sql = "SELECT * FROM `$this->table` WHERE `$id_field`='$id_val'";

        $result=$db->select($sql);

        if ($db->isError()) die($db->error());

        return $result;

    }

}

?>

