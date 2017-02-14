<?php
include('config.pager.php');
require_once('class.database.php');

class Pager
{
    // public members
    var $extraQuery;

    // private members
    var $_numRows;
    var $_numPages;
    var $_totalRows;
    var $_totalPages;
    var $_curPage;
    var $_res;
    var $_db;

    function Pager($sql, $numRows=5, $numPages=2)
    {
     	if ($numRows == "all")
			$numRows = 9999999999;
		
   		$this->_numRows  = $numRows;
        $this->_numPages = $numPages;

        $this->_db = new Database();
        if ($this->_db->isError()) die($this->_db->error());

        $res = $this->_db->query($sql);
        if ($this->_db->isError()) die($this->_db->error());

        $this->_totalRows = $this->_db->num_rows($res);
        $this->_totalPages = ceil($this->_totalRows/$this->_numRows);

        $this->_curPage = $_REQUEST['p'];
        if (empty($this->_curPage)) $this->_curPage = 1;
        if ($this->_curPage < 1) $this->_curPage = 1;
        if ($this->_totalPages && ($this->_curPage > $this->_totalPages))
            $this->_curPage = $this->_totalPages;

        preg_match("/^SELECT.*(LIMIT ((\\d+)(, (\\d+))?))[^(]*$/s",
                    $sql, $matches);
        $lim1 = empty($matches[3])?0:$matches[3];
        $lim2 = empty($matches[5])?0:$matches[5];

        $offset = $this->_curPage?(($this->_curPage-1)*$this->_numRows):0;
        if ($lim1) {
            $sql = preg_replace("/^(SELECT.*)(LIMIT ((\\d+)(, (\\d+))?))([^(]*)$/s",
                                '\\1 LIMIT '.($lim2?(($lim1+$offset).', '):'')
                                . ($lim2?min($lim2, $this->_numRows)
                                    : min($lim1, $this->_numRows)).'\\7', $sql);
        } else {
            $sql .= ' LIMIT '.$offset.', '.$this->_numRows;
        }
        $this->_res = $this->_db->query($sql);
        if ($this->_db->isError()) die($this->_db->error());
    }

    function fetch()
    {
        return $this->_db->fetch($this->_res);
    }

    function numRows()
    {
        return $this->_db->num_rows($this->_res);
    }

    function pages()
    {
        if ($this->_totalPages < 2) return '';

        $num = ($this->_numPages-1)/2;

        $first = $this->_curPage-floor($num);
        $last = $this->_curPage+ceil($num);

        if ($first < 1) {
            $last += 1-$first;
            $first = 1;
            if ($last > $this->_totalPages) $last = $this->_totalPages;
        }
        if ($last > $this->_totalPages) {
            $first -= $last-$this->_totalPages;
            $last = $this->_totalPages;
            if ($first < 1) $first = 1;
        }
        if ($first == 2) $first = 1;
        if ($last == $this->_totalPages-1) $last = $this->_totalPages;

        $lFirst = str_replace('%%%', '1', PAGER_FIRST);
        $lPrev  = str_replace('%%%', $this->_curPage-1, PAGER_PREV);
        $lNext  = str_replace('%%%', $this->_curPage+1, PAGER_NEXT);
        $lLast  = str_replace('%%%', $this->_totalPages,
                                                    PAGER_LAST);
        $lSkipL = str_replace('%%%1%%%', max(1, $first-$this->_numPages),
                    str_replace('%%%2%%%', $first-1, PAGER_PAGE_INTERVAL));
        $lSkipR = str_replace('%%%2%%%', min($last+$this->_numPages,
                                                        $this->_totalPages),
                    str_replace('%%%1%%%', $last+1, PAGER_PAGE_INTERVAL));

        $thispage = basename($_SERVER['PHP_SELF']);
        if (!empty($_SERVER['QUERY_STRING'])) {
            $q = $_SERVER['QUERY_STRING'];
            if (preg_match('/(^|&)p=[^&]*(&|$)/', $q)) {
                $q = preg_replace('/(^|&)p=[^&]*(&|$)/', '\\1p=%%%\\2', $q);
            } else {
                $q .= '&p=%%%';
            }
        } else {
            $q = 'p=%%%';
        }
        $tmp = '';
        if (!empty($this->extraQuery)) {
            foreach ($this->extraQuery as $k=>$v) {
                $tmp .= '&'.urlencode($k).'='.urlencode($v);
            }
        }
// The council id
        if(isset($_GET[council_id]))
            $thispage .= '?councild_id='.$_GET[councild_id].'&'.$q.$tmp;
        else
            $thispage .= '?'.$q.$tmp;

        $str = '';
   //     if ($first > 1) {
            $str .= '<a href="'.str_replace('%%%', '1', $thispage).'">'
                    . $lFirst.'</a>';
         //   $str .=  PAGER_SEPARATOR;
     //   }

        if ($this->_curPage > 1) {
            $str .= '<a href="'
                    . str_replace('%%%', $this->_curPage-1, $thispage)
                    . '">'.$lPrev.'</a>';
            $str .= PAGER_SEPARATOR;
        }

        if ($first-$this->_numPages > 1) {
            if (strlen(PAGER_ELIPSIS)) {
                $str .= PAGER_ELIPSIS.PAGER_SEPARATOR;
            }
        }

        if ($first > 2) {
            $str .= '<a href="'
                    . str_replace('%%%', max($first-$this->_numPages, 1)
                    + floor($num), $thispage).'">'.$lSkipL.'</a>';
            $str .= PAGER_SEPARATOR;
        }

        for ($i=$first; $i<=$last; $i++) {
            if ($i == $this->_curPage) {
                $str .= str_replace('%%%', $i, PAGER_THISPAGE_NUMBER);
            } else {
                $str .= '<a class="number" href="'.str_replace('%%%', $i, $thispage).'">';
                $str .= str_replace('%%%', $i, PAGER_PAGE_NUMBER);
                $str .= '</a>';
            }
            $str .= PAGER_SEPARATOR;
        }

        if ($last < $this->_totalPages-1) {
            $str .= '<a href="'
                    . str_replace('%%%', $last+1+floor($num), $thispage)
                    . '">'.$lSkipR.'</a>';
        }

        if ($last+$this->_numPages < $this->_totalPages) {
            if (strlen(PAGER_ELIPSIS)) {
                $str .= PAGER_SEPARATOR.PAGER_ELIPSIS;
            }
        }

        if ($this->_curPage < $this->_totalPages) {
            $str .= PAGER_SEPARATOR;
            $str .= '<a href="'
                    . str_replace('%%%', $this->_curPage+1, $thispage)
                    . '">'.$lNext.'</a>';
        }

      //  if ($last < $this->_totalPages-1) {
     //       $str .= PAGER_SEPARATOR;
            $str .= '<a href="'
                    . str_replace('%%%', $this->_totalPages, $thispage).'">'
                    . $lLast.'</a>';
        //}

        return $str;
    }
}
?>
