<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Base model for all custom model classes
 * Extends CI_Model class
 *
 * @property CI_DB_query_builder $db
 * @property CI_DB_forge $dbforge
 * @property CI_Config $config
 * @property CI_Loader $load
 * @property CI_Session $session
 */
class MY_Model extends CI_Model {

    protected $condition;
    public $rr;

    public function __construct() {
        $this->condition = null;
        $this->rr = null;
        parent::__construct();
    }

    /**
     * Flush model data and conditions after each method call
     *
     * @param string $method Class method name
     * @param mix $param Called method arguments
     * @return mix Executed result of called method
     */
    public function __call($method, $param) {
        $result = $this->$method($param);
        return $result;
    }

    /**
     * Get table row based on query condition
     *
     * @param string $table Database table name
     * @param mixed $condition Query conditions
     * @param string $select Table columns [comma separated] to return specific values from array or object
     * @param bool $arr Check if return result should be in array or object
     *
     * @return mixed Query Result
     */
    public function getTableRow($table, $condition, $select = '*', $arr = false) {
        if (!empty($select) && is_string($select)) {
            $this->db->select($select);
        }
        if (!empty($condition)) {
            $this->_prepareConditions($condition);
        }
        $query = $this->db->get($table);
        return $arr ? $query->row_array() : $query->row();
    }

    public function getUserIdByUsername($username) {
        $this->db->select('user_id');
        $this->db->from(T_USER);
        $this->db->where('username', $username);

        $result = $this->db->get()->row()->user_id;
        return $result;
    }

    /**
     * Insert new data into table
     *
     * @param string $table Database table name
     * @param array $data Data to store
     * 
     * @return mixed if success returns insert ID, else false
     */
    public function insertIntoTable($table, $data) {
        $result = $this->db->insert($table, $data);
        return $result ? $this->db->insert_id() : false;
    }

    /**
     * Update table based on conditions provided
     *
     * @param string $table Database table name
     * @param array $data Data to update
     * @param mixed $condition Query conditions
     *
     * @return bool if success returns true, else false
     */
    public function updateTable($table, $data, $condition) {
        if (!empty($condition)) {
            $this->_prepareConditions($condition);
        }
        $this->db->update($table, $data);
        return $this->db->affected_rows() > 0 ? true : false;
    }

    /**
     * Delete all rows inside a table
     *
     * @param string $table Database table name
     *
     * @return bool if success returns true, else false
     */
    public function truncateTable($table) {
        $this->db->truncate($table);

        return true;
    }

    /**
     * Get table data based on conditions provided
     *
     * @param string $table Database table name
     * @param mixed $condition Table filter conditions
     * @param string $select Table columns to be selected
     * @param int $limit Row limit to be fetched
     * @param int $offset Row offset after which to be fetched
     * @param array $sortBy Table data sort parameter [ key as column_name, value as sort direction ]
     * @param bool $arr Check if return result should be in array or object
     *
     * @return mixed Query Result
     */
    public function getTableData($table, $condition = null, $select = '*', $limit = 0, $offset = 0, $sortBy = [], $arr = false, $distinct = false) {
        if (!empty($condition)) {
            $this->_prepareConditions($condition);
        }
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        if (!empty($sortBy) && is_array($sortBy)) {
            foreach ($sortBy as $k => $v) {
                if (is_string($k)) {
                    $this->db->order_by($k, strtoupper($v) == 'DESC' ? 'DESC' : 'ASC');
                }
            }
        }
        if (!empty($select) && is_string($select)) {
            $this->db->select($select);
        }
        if ($distinct) {
            $this->db->distinct();
        }
        $query = $this->db->get($table);

        return $arr ? $query->result_array() : $query->result();
    }

    /**
     * Count table data based on conditions
     *
     * @param string $table Database table name
     * @param mixed $condition Table filter conditions
     *
     * @return int Number of results found
     */
    public function countTableRow($table, $condition) {
        if (!empty($condition)) {
            $this->rr = 1;
            $this->_prepareConditions($condition);
        }

        return $this->db->count_all_results($table);
    }

    /**
     * Delete table rows based on conditions
     *
     * @param string $table Database table name
     * @param mixed $condition Table filter conditions
     *
     * @return int Number of deleted rows
     */
    public function deleteTableRow($table, $condition) {
        if (!empty($condition)) {
            $this->_prepareConditions($condition);
        }

        $this->db->delete($table, $condition);
        return $this->db->affected_rows();
    }

    /**
     * Execute a query SQL
     *
     * @param string $sql SQL query
     * @param array $escapes Array parameters of needs to check for mysql-escape
     * @param bool $arr Type of result array / object
     *
     * @return int Number of deleted rows
     */
    public function query($sql, $escapes = [], $arr = false) {
        $query = $this->db->query($sql, $escapes);
        return $arr ? $query->result_array() : $query->result();
    }

    /**
     * Get table columns
     *
     * @param string $table Database table name
     * @param bool $arr Check if return result should be in array or object
     *
     * @return mixed list of table columns
     */
    public function getTableColumns($table, $arr = false) {
        $query = $this->db->query('SHOW COLUMNS FROM ' . $table);
        return $arr ? $query->result_array() : $query->result();
    }

    /**
     * Check if data is unique based on conditions provided
     *
     * @return bool True if unique, False otherwise
     */
    public function checkUnique($table, $condition) {
        return $this->countTableRow($table, $condition) > 0 ? false : true;
    }

    /**
     * To check whether there requested column both in database and model class definition.
     *
     * @param $name Name of the column to be verified
     *
     * @return bool Returns true if column is properly defined, false otherwise
     */
    public function isValidColumn($name) {
        return in_array($name, $this->getColumns()) &&
                in_array($name, $this->getTableColumns(true));
    }

    /**
     * Generate random token string in base64 format
     *
     * @return string Random token
     */
    public function generateToken() {
        $issueTime = time() . '_' . rand(1000, 9999);
        return base64_encode($issueTime);
    }

    /**
     * Parse condition keys and prepare necessary CI active records for DB Query
     *
     * @param mix $conditions DB query conditions
     */
    private function _prepareConditions($conditions) {
        $and = $or = $in = $like = [];
        if (is_array($conditions)) {
            foreach ($conditions as $c => $v) {
                $v = trim($v);
                $k = explode(' ', $c);
                if (preg_match('~\sor|\s\|$~', $c)) {
                    $or[$k[0]] = $v;
                } elseif (stristr($c, ' like')) {
                    $like[$k[0]] = $v;
                } elseif (is_array($v)) {
                    $in[$k[0]] = $v;
                } else {
                    if (isset($k[1])) {
                        $and[$k[0] . trim($k[1])] = $v;
                    } else {
                        $and[$k[0]] = $v;
                    }
                }
            }

            if (!empty($and)) {
                $this->db->where($and, null, true);
            }
            if (!empty($or)) {
                $this->db->or_where($or, null, true);
            }
            if (!empty($like)) {
                $this->db->like($like, '', 'both', true);
            }
            if (!empty($in)) {
                $this->db->where_in($in, null, true);
            }
        } else {
            $this->db->where($conditions);
        }

        return true;
    }

    /**
     * Prepare comma separated
     *
     * @param array $cond
     * @return string
     */
//    public function conditionCommaSepareted(array $condition, string $logic = 'OR'): string {
//        if(empty($this->condition)) {
//            $this->condition = $cond;
//        } else {
//            $cond .= ',' . $cond;
//        }
//    }
}
