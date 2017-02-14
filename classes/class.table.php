<?php

require_once 'class.pager.php';



class table

{

    var $table;

    var $searchable;

    var $columns;

    var $current_id;

    var $_fields;

    var $_tfields;

    var $_filter;

    var $_pager;

    var $_editpage;

    var $_editid;

    var $_db;

	var $howmany_rows;



    function table($table)

    {

        $this->table = $table;

        $this->searchable = array();

        $this->columns = array();

        $this->current_id = null;

        $this->_fields = array();

        $this->_filter = array();

        $this->_pager = null;

        $this->_editpage = '';

        $this->_editid = '';

        $this->_db = new Database();

        if ($this->_db->isError()) die($this->_db->error());



        $res = $this->_db->query("DESCRIBE `$this->table`");

        if ($this->_db->isError()) die($this->_db->error());

        while ($row = $this->_db->fetch($res)) {

            $this->_tfields[$row['Field']] = $row;

        }

    }



    function addField($label, $name, $sort, $id='', $postprocess='')

    {

        $f = array();

        $f['name'] = $label;

        $f['field'] = $name;

        $f['sort'] = $sort;



        if (empty($id)) $id = $name;

        $f['id'] = $id;



        if (is_array($postprocess) || function_exists($postprocess)) {

            $f['postprocess'] = $postprocess;

        }



        $this->_fields[$id] = $f;

        if ($sort) {

            $img = '';



			if (!isset($_GET['sort']))

				$desc = '&desc';

				

            if ($_GET['sort'] == $id) {

	

                if (isset($_GET['desc'])) {

                    $desc = '';

                    $img = 'down';

            	} else {

                   	$desc = '&desc';

                    $img = 'up';

            	}	

			}

			else {

				$desc = '&desc';

			}

			

			if ($_GET['sort'] != "")

               	$img = "<img src=\"images/$img.png\" width=\"13\" alt=\"\" />";



		if ($_GET['sort'] == $id){

			$key = $_GET['key'];

			if($_GET['key'] == 'all' || $_GET['key'] == 'pending' || $_GET['key'] == 'received' || $_GET['key'] == 'adjusted' || $_GET['key'] == 'returned' || $_GET['key'] == 'release' || $_GET['key'] == 'paid' || $_GET['key'] == 'cancelled' || $_GET['key'] == 'expired'){

				if (isset($_GET['desc'])) {

					$this->columns[] = "<a href=\"?sort=$id$desc&key=$key\">".htmlspecialchars($label)."</a>$img";

				}

				else

				{

					$this->columns[] = "<a href=\"?sort=$id$desc&key=$key\">".htmlspecialchars($label)."</a>$img";

				}

			}

			else{

				$this->columns[] = "<a href=\"?sort=$id$desc\">".htmlspecialchars($label)."</a>$img";

			}

		}

		else{

			if($_GET['catid']){

				$this->columns[] = "<a href=\"?sort=$id$desc&catid=".$_GET['catid']."\">".htmlspecialchars($label)."</a>";

				}

				else{

				$this->columns[] = "<a href=\"?sort=$id$desc\">".htmlspecialchars($label)."</a>";

		}}

        } else {

            $this->columns[] = htmlspecialchars($label);

        }

    }





function search_new($noecho=false)

    {



        $out = '

<form action="" method="post" style="float: left;">

<input type="text" class="text-input large-input" id="small_input" name="search" value="'.htmlspecialchars($_POST['search']).'" style="width: 25% !important;"/>



From:<input type="text" id="date_limit" class="text-input large-input" style="width: 25% !important;" name="date_limit"> 



To:<input type="text" id="date_limitto" class="text-input large-input" style="width: 25% !important;" name="date_limitto"> 

<input type="submit" class="button" value="Search" />

</form>

';

        if ($noecho) return $out;

        echo $out;

    }

	

	

    function addJoined($label, $name, $sort, $table, $name_field='', $id_field='', $id='', $postprocess='')

    {

        $f = array();

        $f['name'] = $label;

        $f['field'] = $name;

        $f['sort'] = $sort;

        $f['join'] = $table;



        if (empty($name_field)) $name_field = $name;

        $f['join_name'] = $name_field;



        if (empty($id_field)) $id_field = $name;

        $f['join_id'] = $id_field;



        if (empty($id)) $id = $name;

        $f['id'] = $id;



        if (is_array($postprocess) || function_exists($postprocess)) {

            $f['postprocess'] = $postprocess;

        }



        $this->_fields[$id] = $f;

        if ($sort) {

            $img = '';

            if ($_GET['sort'] == $id) {

                if (isset($_GET['desc'])) {

                    $desc = '';

                    $img = 'up';

                } else {

                    $desc = '&desc';

                    $img = 'down';

                }

                $img = "<img src=\"images/$img.png\" width=\"10\" height=\"9\" align=\"absmiddle\" alt=\"\" />";

            }

            $this->columns[] = "<a href=\"?sort=$id$desc\">".htmlspecialchars($label)."</a>$img";

        } else {

            $this->columns[] = htmlspecialchars($label);

        }

    }

	

	function addJoinednew($label, $name, $sort, $table, $name_field='', $id_field='', $id='', $postprocess='')

    {

        $f = array();

        $f['name'] = $label;

        $f['field'] = $name;

        $f['sort'] = $sort;

        $f['join'] = $table;



        if (empty($name_field)) $name_field = $name;

        $f['join_name'] = $name_field;



        if (empty($id_field)) $id_field = $name;

        $f['join_id'] = $id_field;



        if (empty($id)) $id = $name;

        $f['id'] = $id;



        if (is_array($postprocess) || function_exists($postprocess)) {

            $f['postprocess'] = $postprocess;

        }



        $this->_fields[$id] = $f;

        if ($sort) {

            $img = '';

            if ($_GET['sort'] == $id) {

                if (isset($_GET['desc'])) {

                    $desc = '';

                    $img = 'up';

                } else {

                    $desc = '&desc';

                    $img = 'down';

                }

                $img = "<img src=\"images/$img.png\" width=\"10\" height=\"9\" align=\"absmiddle\" alt=\"\" />";

            }

            $this->columns[] = "<a href=\"?sort=$id$desc\">".htmlspecialchars($label)."</a>$img";

        } else {

            $this->columns[] = htmlspecialchars($label);

        }

    }

	

	

/*

    function setEdit($field, $page='', $id='',$param='') // $param, alti parametrii pt link in afara de id

    {

        if (!is_array($field)) {

            $field = array($field);

        }



        foreach ($field as $fid) {

            $found = null;

            foreach ($this->_fields as $key=>$fld) {

                if ($fld['id'] == $fid) {

                    $found = $key;

                    break;

                }

            }

            if (empty($found)) return;

            $this->_fields[$found]['edit'] = true;

        }



        $this->_editpage = $page;

        $this->_editid = empty($id)?$field[0]:$id;

        if ($param!='')

            $this->_editpage .="?".$param."&";

        else

                    $this->_editpage .= "?";

    }



    // adauga un filtru dupa campul $name

    //      $like=false  pentru "$name = '$value'"

    //          sau "($name > '$value') AND ($name < '$max')" daca $max<>NULL

    //      $like=true   pentru "$name LIKE '%$value%'"

    function addFilter($name, $like, $value, $max=NULL)

    {

        if ($like) {

            $flt = "($name` LIKE '%".$this->_db->escape($value)."%')";

        } else {

            if (is_null($max)) {

                $flt = "(`$name` = '".$this->_db->escape($value)."')";

            } else {

                $flt = "(`$name` > '".$this->_db->escape($value)."') AND "

                        . "(`$name` < '".$this->_db->escape($max)."')";

            }

        }

        $this->_filter[] = $flt;

    }

*/

    function search($noecho=false)

    {



        $out = '

<form action="" method="post" style="float: left;">

<input type="text" class="text-input large-input" id="small_input" name="search" value="'.htmlspecialchars($_POST['search']).'" style="width: 60% !important;"/> <input type="submit" class="button" value="Search" />

</form>

';

        if ($noecho) return $out;

        echo $out;

    }



	function display_records() {

	$keyid = $_GET['key'];

	$catid = $_GET['catid'];

		$out = '<form action="" method="GET" style="float: right;">

						<select name="rows" class="mini-input">

							<option value="all">all records</option>

							<option value="50">50 records</option>

							<option value="100">100 records</option>

							<option value="300">300 records</option>
							

						</select>';

					if(isset($_GET['catid'])){	

				$out = $out.'<input type="hidden" name="catid" value='.$catid.' />';

					}

					

					if(isset($_GET['key'])){	

				$out = $out.'<input type="hidden" name="key" value='.$keyid.' />';

					}

				$out = $out.'<input type="submit" class="button" value="Display">

				</form>';

		

		echo $out;

	}



    function fetch($where = '')

    {

        if (empty($this->_pager)) $this->_select($where);



        $row = $this->_pager->fetch();



        if ($row) {

            $tmp = array();

            foreach ($this->_fields as $field) {

                if (!is_array($field['field'])) {

                    $field['field'] = array($field['field']);

                }



                $val = array();

                foreach ($field['field'] as $fld) {

                    $val[] = $row[$fld];

                }

                if (count($val)==1) $val = implode('', $val);



                if ($field['postprocess']) {

                    if (is_array($field['postprocess'])) {

                        $val = $field['postprocess'][$val];

                    } else {

                        $val = $field['postprocess']($val);

                    }

                }



                $val = htmlspecialchars($val);

                if ($field['edit']) {

                    $val = "<a href=\"".$this->_editpage."id=".$row[$this->_editid]."\">$val</a>";

                }



                $tmp[$field['id']] = $val;

            }

            $this->current_id = $row['id'];

        } else {

            $tmp = $row;

            $this->current_id = null;

        }



        return $tmp;

    }



    function numRows()

    {

        return $this->_pager->numRows();

    }



  function _select($where1 = '')

    {

		$where1;

        $ids = array();

        $joins = array();

        $where = array($where1);

        $order = array();

        $sort = $_GET['sort'];

        $desc = isset($_GET['desc'])?' DESC':'';

        $term = $_POST['search'];

		

		if(isset($_POST['search'])){

			$where = array();

			}

		

		

  $date_limit=$_POST['date_limit'];

  $date_limitto=$_POST['date_limitto'];



if($date_limit != ""){

	$_SESSION['datelimit'] = $date_limit;

	}

if($date_limitto != ""){

	$_SESSION['datelimitto'] = $date_limitto;

	}	



        if ($this->_editid) {

            $ids[] = "`$this->table`.`$this->_editid`";

        }



        foreach ($this->_fields as $field) {

            $search = false;

            if ($term || $date_limit) {

                foreach ($this->searchable as $fid) {

                    if ($fid = $field['id']) $search = true;

                }

            }

            

            if (empty($field['join'])) {

                if (!is_array($field['field'])) {

                    $as = " AS `$field[id]`";

                    $field['field'] = array($field['field']);

                } else {

                    $as = '';

                }

				

                foreach ($field['field'] as $fld) {

                    $ids[] = "`$this->table`.`$fld`$as";

                    if ($field['sort'] && ($sort == $field['id'])) {

                        if ($as) {

                            $order[] = "`$field[id]`$desc";

                        } else {

                            $order[] = "`$this->table`.`$fld`$desc";

                        }

                    }

					 

                    if (($term && $search ) || ($search && $date_limit) || ($date_limitto)) {

						if($term == 'Paypal' || $term == 'paypal'){

			$seraa="`ordertrasactions`.`PaymentMethod` = 1";

			

			}

		else if($term == 'Cheque' || $term == 'cheque'){

			$seraa="`ordertrasactions`.`PaymentMethod` = 2";

			}		

			else{

				

      $seraa="`$this->table`.`$fld` LIKE '%".$this->_db->escape($term)."%'";

			}

				       // $where[] = "`$this->table`.`$fld` LIKE '%".$this->_db->escape($term)."%'";

					

      if($date_limit && !($date_limitto)){

		 $date_limit = date('Y-m-d', strtotime($date_limit));

		

		

       $seraa=$seraa. " and `$this->table`.`OrderDate` LIKE  '%$date_limit%'";

       

       }

	   if($date_limitto && !($date_limit)){

		   

		   

		   $date_limitto = date('Y-m-d', strtotime($date_limitto));

		  

       $seraa=$seraa. " and `$this->table`.`OrderDate` LIKE  '%$date_limitto%'";

       

       }

	   

	   if($date_limit && $date_limitto){

		   

		   

		   $date_limit = date('Y-m-d', strtotime($date_limit));

		   $date_limitto = date('Y-m-d', strtotime($date_limitto));

		   

       $seraa=$seraa. " and `$this->table`.`OrderDate` BETWEEN  str_to_date('$date_limit','%Y-%m-%d') AND str_to_date('$date_limitto','%Y-%m-%d') ";

       

       }

       $seraa;//exit;

      $where[] = $seraa;  

                    }

					

                }

            } else {

                if (!is_array($field['field'])) {

                    $field['field'] = array($field['field']);

                    $field['join'] = array($field['join']);

                    $field['join_name'] = array($field['join_name']);

                    $field['join_id'] = array($field['join_id']);

                }

                foreach ($field['field'] as $key=>$fld) {

                    $ids[] = "`{$field['join'][$key]}`.`{$field['join_name'][$key]}` AS `$fld`";

                    $joins[] = "LEFT JOIN `{$field['join'][$key]}` ON `{$field['join'][$key]}`.`{$field['join_id'][$key]}`=`$this->table`.`$fld`";

                    if ($field['sort'] && ($sort == $field['id'])) {

                        $order[] = "`$fld`$desc";

                    }

                    if ($term && $search) {

                        $where[] = "`{$field['join'][$key]}`.`{$field['join_name'][$key]}` LIKE '%".$this->_db->escape($term)."%'";

                    }

                }

            }

        }



        $ids = join(',', $ids);

        $joins = join(' ', $joins);

        $where = join(' OR ', $where);

        if (!empty($this->_filter)) {

            $filter = join(' AND ', $this->_filter);

            if (empty($where)) {

                $where = $filter;

            } else {

                $where = "($where) AND $filter";

            }

        }

        $order = join(',', $order);

        if (!empty($where)) $where = "WHERE $where";

        if (!empty($order)) $order = "ORDER BY $order";

		

		if($_GET['catid'] && $_GET['catid'] != 'all'){

	if($where){		

$where =$where . " AND `$this->table`.`CategoryId` = ".$_GET['catid'];

	}

	else{

		$where =" where `$this->table`.`CategoryId` = ".$_GET['catid'];

		}

		}

   $sql = "SELECT $ids FROM `$this->table` $joins $where $order";

  

  if ($this->howmany_rows) {

         $this->_pager = new Pager($sql,$this->howmany_rows);

  }

  else {

   $r = $this->_db->select("SELECT value FROM settings WHERE setting='rows'");

   $this->_pager = new Pager($sql,$r['value']);

  }

    }

    function pagination()

    {   

	 	echo $this->_pager->pages();

		return $this->_pager->pages();

    }

    



	// deletes multiple ID's from an array or a single id

	function delete_entries($field,$values) {

	

        $db = new Database();



        if ($db->isError()) die($db->error());



		if (is_array($values)) {

			

			foreach ($values as $value) {

        		$db->query("DELETE FROM `$this->table` WHERE `$field`='$value'");

			}

		}

		else {

			if($this->table == "user"){

				$db->query("DELETE FROM `userroles` WHERE `UserId`='$values'");

			$db->query("DELETE FROM `$this->table` WHERE `$field`='$values'");

		}

		

		else{

			$db->query("DELETE FROM `$this->table` WHERE `$field`='$values'");

			}

		}

        if ($db->isError()) die($db->error());

	}

    

}

?>

