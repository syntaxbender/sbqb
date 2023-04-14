<?php
class qb{
    const ASC = "asc";
    const DESC = "desc";
    const INNER = "inner";
    const LEFT = "left";
    const RIGHT = "right";
    const SELECT = "select";
    const DELETE = "delete";
    const INSERT = "insert";
    const UPDATE = "update";
    const AND = "and";
    const OR = "or";
    private $table = null;
    private $joins = [];
    private $fields = [];
    private $conditions = [];
    private $values = [];
    private $set = [];
    private $group = null;
    private $order = null;
    private $limit = null;
    private $mode = null;
    function __construct(){
        
    }

    public function table($tableName,$as = null){
        $this->table = ($as != null) ? self::AS($tableName,$as) : $tableName;
        return $this;
    }

    public function set($field,$value){
        $this->mode = self::INSERT;
        $this->set[] = $field." = ?";
        $this->values[] = $value;
        return $this;
    }
    public function update($field,$value){
        $this->mode = self::UPDATE;
        $this->set[] = $field." = ?";
        $this->values[] = $value;
        return $this;
    }
    public function select($field,$as = null){
        $this->mode = self::SELECT;
        $this->fields[] = ($as != null)? self::AS($field,$as) : $field;
        return $this;
    }
    public function delete(){
        $this->mode = self::DELETE;
        return $this;
    }

    public function foreignTable($foreignTableName, $foreignField, $selfField, $type, $as = null){
        $this->joins[] = ($as != null) ? $type." join ".$foreignTableName." as ".$as. " on ".$foreignField." = ".$selfField : $type." join ".$foreignTableName." on ".$foreignField." = ".$selfField; // i dont use as method bcs of i will operate validation process by name conversion in next commit.
        // ....::AS("inner join xxxx") cant pass validation for name conversion... 
        return $this;
    }

    public function search($field,$search,$return=false){
        $query = $field." like ?";
        $this->values[] = "%".$search."%";
        if($return)
            return $query;
        $this->conditions[] = $query;   
        return $this;
    }
    public function equal($field,$value,$return=false){
        $query = $field." = ?";
        $this->values[] = $value;
        if($return)
            return $query;
        $this->conditions[] = $query;
        return $this;
    }
    public function notEqual($field,$value,$return=false){
        $query = $field." != ?";
        $this->values[] = $value;
        if($return)
            return $query;
        $this->conditions[] = $query;
        return $this;
    }
    public function between($field,$min,$max,$return=false){
        $query = $field." between ? and ?";
        $this->values[] = $min;
        $this->values[] = $max;
        if($return)
            return $query;
        $this->conditions[] = $query;
        return $this;
    }
    public function biggerThan($field,$value,$return=false){
        $query = $field." > ?";
        $this->values[] = $value;
        if($return)
            return $query;
        $this->conditions[] = $query;
        return $this;
    }
    public function lowerThan($field,$value,$return=false){
        $query = $field." < ?";
        $this->values[] = $value;
        if($return)
            return $query;
        $this->conditions[] = $query;
        return $this;
    }
    public function bigOrEq($field,$value,$return=false){
        $query = $field." >= ?";
        $this->values[] = $value;
        if($return)
            return $query;
        $this->conditions[] = $query;
        return $this;
    }
    public function lowOrEq($field,$value,$return=false){
        $query = $field." <= ?";
        $this->values[] = $value;
        if($return)
            return $query;
        $this->conditions[] = $query;
        return $this;
    }
    public function group($field){
        $this->group = "group by ".$field;
        return $this;
    }
    public function order($field,$order){
        $this->order = "order by ".$field;
        return $this;
    }
    public function limit($length, $start = null){
        $this->limit = ($start != null) ? "limit ".$start.", ".$length :"limit ".$length;;
        return $this;
    }
    public static function COUNT($field){
        return "COUNT(".$field.")";
    }
    public static function MAX($field){
        return "MAX(".$field.")";
    }
    public static function MIN($field){
        return "MIN(".$field.")";
    }
    public static function AVG($field){
        return "AVG(".$field.")";
    }
    public static function SUM($field){
        return "SUM(".$field.")";
    }
    public static function CONDITION($condition,$trueField,$falseField){
        return "IF(".$condition.",".$trueField.",".$falseField.")";
    }
    public static function UNIX_TIMESTAMP($field){
        return "UNIX_TIMESTAMP(".$field.")";
    }
    public static function AS($field,$replaceText){
        return $field." as ".$replaceText;
    }
    public function OR(...$logics){
        $this->conditions[] = "(".implode(" OR ",$logics).")";
        return $this;
    }
    public function AND(...$logics){
        $this->conditions[] = "(".implode(" AND ",$logics).")";
        return $this;
    }
    public function exec(){
        $query = "";
        switch ($this->mode) {
            case "insert":
                $query = "insert into ".$this->table." set ".implode(", ",$this->set);
                break;
            case "select":
                $query =
                "select "
                .implode(", ",$this->fields)
                ." from "
                .$this->table
                .((count($this->joins)>0)? " " : "").implode(" ",$this->joins)
                .((count($this->conditions)>0)? " where " : "")
                .implode(" AND ",$this->conditions)
                .(($this->group!=null)? " ".$this->group : "")
                .(($this->order!=null)? " ".$this->order : "")
                .(($this->limit!=null)? " ".$this->limit : "");
                break;
            case "update":
                $query =
                "update "
                .$this->table." SET "
                .implode(", ",$this->set)
                .((count($this->conditions)>0)? " where " : "")
                .implode(" AND ",$this->conditions);
                break;
            case "delete":
                $query =
                "delete from "
                .$this->table
                .((count($this->conditions)>0)? " where " : "")
                .implode(" AND ",$this->conditions);
                break;
        }
        //$query = $this->mode." ".implode(", ",$this->fields)
        return $query;
    }
}
$asd = new qb();
$za = $asd->table("haso","taso")->foreignTable("rel_table", "rel_table.id", "haso.id", qb::INNER)
    ->select("asd","qwe")
    ->select("ok")
    ->OR($asd->between("num","10","20",true),$asd->between("money","1000","20000",true),)->search("dominator","aranan")->equal("id","1")->exec();
echo $za;
