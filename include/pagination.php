<?php

class Pagination
{
    public $limits = [10, 50, 100];
    private $limit = 10;
    private $page = 1;
    private $rowsCount = 0;
    private $pageCount = 0;
    private $limitPages = 5;
    private $params = [];


    public function getRowsCount() {
        return $this->rowsCount;
    }
    public function getPageCount() {
        return $this->pageCount;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function getFirst() {
        return ($this->page - 1) * $this->limit;
    }

    public function getPage() {
        return $this->page;
    }

    public function setParams($params) {
        $this->params = $params;
    }
    public function setLimit($limit) {
        if ($limit && is_numeric($limit) && $limit > 2 && in_array($limit, $this->limits)) {
            $this->limit = $limit;
            $this->calcPages();
        }
    }

    public function setPage($page) {
        if ($page && is_numeric($page) && $page > 0) {
            $this->page = $page;
            $this->calcPages();
        }
    }

    public function setRowsCount($rows) {
        if ($rows && is_numeric($rows) && $rows>0){
            $this->rowsCount = $rows;
            $this->calcPages();
        }
    }

    private function calcPages() {
        $this->pageCount = 0;
        if ($this->rowsCount > 0) {
            $this->pageCount = (int)(($this->rowsCount - 1) / $this->limit) + 1;
        }
        if ($this->pageCount == 0) {
            $this->page = 1;
        }
        else if ($this->page > $this->pageCount) {
            $this->page = $this->pageCount;
        }
    }

    public function getParams($page) {
        return "?page=".$page."&limit=".$this->limit.'&'.http_build_query($this->params);
    }
    public function getArrParams() {
        return $this->params;
    }

    public function show() {
        if ($this->pageCount < 2) return '';
        $first = $this->page - (int)($this->limitPages / 2);
        if ($first < 1) $first = 1;
        $last = $first + $this->limitPages - 1;
        if ($last > $this->pageCount) {
            $last = $this->pageCount;
            $first = $last - $this->limitPages + 1;
            if ($first < 1) $first = 1;
        }
        $s = '';
        if ($first > 1) {
            $s .= "<a href='".$this->getParams(1)."'>1</a>";
        }
        if ($first > 2) {
            $s .= "<a href='".$this->getParams($first - 1)."'>...</a>";
        }
        for ($i=$first;$i<=$last;$i++) {
            $s .= "<a " .(($i == $this->page)?'class="active"':''). " href='".$this->getParams($i)."'>{$i}</a>";
        }
        if ($last < $this->pageCount) {
            $s .= "<a href='".$this->getParams($this->pageCount)."'>{$this->pageCount}</a>";
        }
        return $s;
    }
}
