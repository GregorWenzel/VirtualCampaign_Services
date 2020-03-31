<?php

class baseClass
{
    protected $_dbconn;
    public $_error;
    public $_errorArr;


    public function __construct($dbconn)
    {
        $this->_dbconn = $dbconn;
    }

    protected function escapeData($data) {
        return $this->_dbconn->real_escape_string($data);
    }

    protected function dbSelect($sql, $error, $fetch = false)
    {
        if ($res = $this->_dbconn->query($sql)) {
            if (!$fetch) {
                return $res;
            }

            $result = [];
            while($row_data = mysqli_fetch_array($res)) {
                array_push($result, $row_data);
            }
            return $result;
        }

        $this->_error = error::buildErrorString($error, $this->_dbconn->error);
        return null;
    }

    protected function dbQuery($sql, $error)
    {
        if ($res = $this->_dbconn->query($sql)) {
            return true;
        }

        $this->_error = error::buildErrorString($error, $this->_dbconn->error);
        return null;
    }
}