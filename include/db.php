<?php
include_once("dataProcessor.php");
class QueryBuilder {

    private $db;
    protected $from = '';
    protected $select = [];
    protected $insert = [];
    protected $where = [];
    protected $order = [];
    protected $group = [];
    protected $join = [];
    protected $offset = null;
    protected $limit = null;
    protected $sql = '';

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
    public function join($t, $c) {
        $this->join[] = [
            'type' => 'JOIN ',
            'table' => $t,
            'condition' => $c
        ];
        return $this;
    }

    public function select($fields) {
        if (empty($fields)){
            $this->select[0] = '*';
        }
        else{
            if (is_array($fields)) {
                foreach ($fields as $v) {
                    array_push($this->select, $v);
                }
            } else {
                array_push($this->select, $fields);
            }
        }
        return $this;
    }
    public function insert($fields,$values) {
        if (is_array($fields) && is_array($values) && count($fields) === count($values)) {
            $insertData = array_combine($fields, $values);
            $this->insert = $insertData;
        }
        else if(!is_array($fields) && !is_array($values)){
            $this->insert[$fields] = $values;
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

    public function groupBy($fields) {
        if (is_array($fields)) {
            foreach ($fields as $v) {
                array_push($this->group, $v);
            }
        } else {
            array_push($this->group, $fields);
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

        
        if ($this->insert)
        {
            $this->sql .= 'INSERT INTO ';
            $this->sql .= $this->from ." (";
            $this->sql .= implode(',', array_keys($this->insert)); 
            $this->sql .= ") VALUES ('";
            $this->sql .= implode("','", array_values($this->insert)); 
            $this->sql .= "')";
        }
        if ($this->select)
        {
            $this->sql .= 'SELECT ';
            $this->sql .= implode(',', $this->select);
            $this->sql .= ' FROM ' . $this->from;
            foreach($this->join as $item) {
                $this->sql .= ' '. $item['type'] . ' ' . $item['table'] . ' ON ' . $this->buildWhere($item['condition'], true);
            }
            if ($this->where) {
                $this->sql .= ' WHERE '. $this->buildWhere($this->where );
            }
            if ($this->group) $this->sql .= ' GROUP BY ' . implode(',', $this->group);
            if ($this->order) $this->sql .= ' ORDER BY ' . implode(',', $this->order);
            if ($this->offset && $this->limit) $this->sql .= ' LIMIT '. $this->offset . ',' . $this->limit;
            else if ($this->limit) $this->sql .= ' LIMIT '. $this->limit;
        }
        
        return $this->sql;
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
    public function executeQuery($sql) {
        if (!$this->isConnect()) return false;
        $result = mysqli_query($this->con, $sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    
    public function update($url,$urlParams){
        if (!$this->isConnect()) return false;
        if($urlParams){
            $url = $url.'?'.http_build_query($urlParams);
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            return $data;
        }
        return false;
    }
    public function updateV1($dataUp,$errorTable,$errorFields,$errorFieldsValue,$errorMessageFieldName,$tableUpdate,$tableFields,$tableFieldsValue,$tableFieldName){
        if (!$this->isConnect()) return false;
        $current_price = htmlentities($dataUp);
        if (!is_numeric($current_price)){
            $error_message = "Invalid numeric value for $tableFieldName";
            $this->writeError($errorTable,$errorFields,$errorFieldsValue,$errorMessageFieldName,$error_message);
            return false;
        }
        else{
            $insertFieldArr[0] = $tableFieldName;
            $insertValueArr[0] = $current_price;
            foreach ($tableFields as $f) {
                array_push($insertFieldArr, $f);
            }
            foreach ($tableFieldsValue as $v) {
                array_push($insertValueArr, $v);
            }
            $sql  = $this ->find($tableUpdate)
            ->insert($insertFieldArr,$insertValueArr)
            ->sql();
            $this->executeQuery($sql);
            return true;
        
        }
    }
    public function writeError($errorTable,$errorFields,$errorFieldsValue,$errorMessageFieldName,$error_message){
        if (!$this->isConnect()) return false;
        $insertFieldArr[0] = $errorMessageFieldName;
        $insertValueArr[0] = $error_message;
        foreach ($errorFields as $f) {
            array_push($insertFieldArr, $f);
        }
        foreach ($errorFieldsValue as $v) {
            array_push($insertValueArr, $v);
        }
        $error_sql  = $this ->find($errorTable)
        ->insert($insertFieldArr,$insertValueArr)
        ->sql();
        $this->executeQuery($error_sql);
    }
}
