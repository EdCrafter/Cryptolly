<?php
include_once("dataProcessor.php");
class QueryBuilder {

    private $db;
    protected $from = '';
    protected $select = [];
    protected $update =[];
    protected $insert = [];
    protected $where = [];
    protected $order = [];
    protected $orderDesk = 'DESC';
    protected $group = [];
    protected $join = [];
    protected $offset = null;
    protected $limit = null;
    protected $sql = '';
    protected $delete = false;
    protected $concWhere = "AND";

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
    public function join($t, $c,$q=null) {
        $this->join[] = [
            'type' => 'JOIN ',
            'joinQuery' => $q,
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

    public function delete() {
        $this->delete = true;
        return $this;
    }

    public function update($fields,$values) {
        if (is_array($fields) && is_array($values) && count($fields) === count($values)) {
            $updateData = array_combine($fields, $values);
            $this->update = $updateData;
        }
        else if(!is_array($fields) && !is_array($values)){
            $this->update[$fields] = $values;
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

    public function orderBy($fields, $orderDesk = 'DESC') {
        if (is_array($fields)) {
            foreach ($fields as $v) {
                array_push($this->order, $v);
            }
        } else {
            array_push($this->order, $fields);
        }
        $this->orderDesk = $orderDesk;
        return $this;
    }

    public function count() {
        $c = $this->db->queryOne(str_replace( ' * ',' COUNT(*) as count ', $this->sql()));
        if (!$c) return false;
        return $c['count'];
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

    public function setConcWhere($concWhere) {
        $this->concWhere = $concWhere;
        return $this;
    }

    public function where($field, $condition, $value) {
        if ($condition == 'eq') $condition = '=';
        else if ($condition == 'ne') $condition = '<>';
        else if ($condition == 'le') $condition = '<=';
        else if ($condition == 'lt') $condition = '<';
        else if ($condition == 'gt') $condition = '>';
        else if ($condition == 'ge') $condition = '>=';
        else if ($condition == 'bw') {
            $condition = 'LIKE';
            $value.='%';
        }
        else if ($condition == 'ew') {
            $condition = 'LIKE';
            $value='%'.$value;
        }
        else if ($condition == 'cn') {
            $condition = 'LIKE';
            $value='%'.$value.'%';
        }
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
            if ($wh){
                $wh .=" $this->concWhere";
            }
            if ($item['condition'] == 'nc') {
                $wh .= ' ' . $item['field'] . " NOT LIKE '%" . $item['value'] . "%'";
            } else if ($item['condition'] == 'bn') {
                    $wh .=  ' ' . $item['field'] . " NOT LIKE '". $item['value']. "%'";
            } else if ($item['condition'] == 'en') {
                $wh .=  ' ' . $item['field'] . " NOT LIKE '%". $item['value']. "'";
            } else if ($item['condition'] == 'IN') {
                $wh .=  ' ' . $item['field'] . ' '. $item['condition'].  ' ('. implode(',', $item['value']) . ')';
            }
            else{
                $wh .=  ' ' . $item['field'] . ' '. $item['condition']. ((!$isJoin)?" '":" ") . $item['value'] . ((!$isJoin)?"'":"");
            }
        }

        return $wh;
    }

    public function sql()
    {   
        $this->sql = '';
        
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
                $this->sql .= ' '. $item['type'] . (($item['joinQuery'])?'(':'').$item['joinQuery'].(($item['joinQuery'])?')':'') . $item['table'] . ' ON ' . $this->buildWhere($item['condition'], true);
            }
            if ($this->where) {
                $this->sql .= ' WHERE '. $this->buildWhere($this->where );
            }
            if ($this->group) $this->sql .= ' GROUP BY ' . implode(',', $this->group);
            if ($this->order) $this->sql .= ' ORDER BY ' . implode(',', $this->order) . ' '.$this->orderDesk;
            if ($this->offset && $this->limit) $this->sql .= ' LIMIT '. $this->offset . ',' . $this->limit;
            else if ($this->limit) $this->sql .= ' LIMIT '. $this->limit;
        }
        if ($this->update)
        {
            $this->sql .= 'UPDATE ';
            $this->sql .= $this->from ." SET ";
            $i = 0;
            foreach($this->update as $key => $value) {
                if ($i > 0) $this->sql .= ', ';
                $this->sql .= $key . ' = \'' . $value. '\'';
                $i++;
            }
            if ($this->where) {
                $this->sql .= ' WHERE '. $this->buildWhere($this->where );
            }
        }
        if ($this->delete)
        {
            $this->sql .= 'DELETE FROM ';
            $this->sql .= $this->from;
            if ($this->where) {
                $this->sql .= ' WHERE '. $this->buildWhere($this->where );
            }
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
        if (!$r) {
            echo "s= ";
            var_dump($sql);

            return false;
        }
        $rows = [];
        while ($row = mysqli_fetch_assoc($r)) {
            $rows[] = $row;
        }
        return $rows;
    }
    public function queryOne($sql) {
        if (!$this->isConnect()) return false;
        $r = mysqli_query($this->con, $sql);
        if (!$r) { 
            return false;
        }
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
?>