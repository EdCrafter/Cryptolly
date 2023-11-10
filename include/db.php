<?php

class QueryBuilder {

    private $db;
    protected $from = '';
    protected $select = [];
    protected $where = [];
    protected $order = [];
    protected $join = [];
    protected $offset = null;
    protected $limit = null;

    public function __construct($db, $from) {
        $this->from = $from;
        $this->db = $db;
    }

    public function offset($o) { $this->offset = $o; return $this; }
    public function limit($l) { $this->limit = $l; return $this; }

    public function leftJoin($t, $c) {
        $this->join[] = [
            'type' => ' LEFT JOIN ',
            'table' => $t,
            'condition' => $c
        ];
        return $this;
    }

    public function select($fields) {
        if (is_array($fields)) {
            foreach ($fields as $v) {
                array_push($this->select, $v);
            }
        } else {
            array_push($this->select, $fields);
        }
        return $this;
    }

    public function orderBy($fields) {
        if (is_array($fields)) {
            foreach ($fields as $v) {
                array_push($this->order, $v);
            }
        } else {
            array_push($this->order, $fields);
        }
        return $this;
    }

    public function where($field, $condition, $value) {
        array_push($this->where,[
            'field' => $field,
            'condition' => $condition,
            'value' => $value
        ]);
        return $this;
    }

    private function buildWhere($where, $isJoin = false) {
        $wh = '';
        foreach($where as $item) {
            if ($wh) $wh .= ' AND';
            $wh .=  ' ' . $item['field'] . ' '. $item['condition']. ((!$isJoin)?" '":" ") . $item['value'] . ((!$isJoin)?"'":"");
        }

        return $wh;
    }

    public function sql()
    {
        $sql = 'SELECT ';
        if (!$this->select)
        {
            $sql .= ' * ';
        }else {
            $sql .= implode(',', $this->select);
        }
        $sql .= ' FROM ' . $this->from;
        foreach($this->join as $item) {
            $sql .= ' '. $item['type'] . ' ' . $item['table'] . ' ON ' . $this->buildWhere($item['condition'], true);
        }
        if ($this->where) {
            $sql .= ' WHERE '. $this->buildWhere($this->where );
        }
        if ($this->order) $sql .= ' ORDER BY ' . implode(',', $this->order);
        if ($this->offset && $this->limit) $sql .= ' LIMIT '. $this->offset . ',' . $this->limit;
        else if ($this->limit) $sql .= ' LIMIT '. $this->limit;
        return $sql;
    }


    public function rows() {
        return $this->db->query($this->sql());
    }

}


class DB {
    private $options = [];
    private $con = null;
    public function __construct($options) {
        $this->options = $options;
        $this->connect();
    }

    public function find($from)
    {
        return new QueryBuilder($this, $from);
    }

    public function __destruct(){
        $this->close();
    }

    public function isConnect() {
        return ($this->con != null);
    }

    public function close() {
        if($this->con) mysqli_close($this->con);
    }

    public function connect() {
        $this->con = mysqli_connect($this->options['host'],$this->options['user'],$this->options['password'], $this->options['db']);
        return $this->isConnect();
    }
    public function query($sql) {
        if (!$this->isConnect()) return false;
        $r = mysqli_query($this->con, $sql);
        if (!$r) return false;
        $rows = [];
        while ($row = mysqli_fetch_assoc($r)) {
            $rows[] = $row;
        }
        return $rows;
    }
    public function queryOne($sql) {
        if (!$this->isConnect()) return false;
        $r = mysqli_query($this->con, $sql);
        if (!$r) return false;
        return mysqli_fetch_assoc($r);
    }
}
