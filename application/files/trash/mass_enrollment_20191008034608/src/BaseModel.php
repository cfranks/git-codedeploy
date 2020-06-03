<?php
namespace Concrete\Package\MassEnrollment\Src;

use Concrete\Core\Legacy\DatabaseItemList;

abstract class BaseModel extends DatabaseItemList
{

    /**
     * Data Members
     * 
     * @var type 
     */
    protected $app;
    protected $db;
    protected $_table;
    protected $_primary_key;
    protected $fillable;
    protected $date_fields;

    /**
     * Constructor for the Model
     */
    public function __construct()
    {
        $this->app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
        $this->db = $this->app->make('database')->connection();
    }

    /**
     * Function to add data
     * 
     * @param type $data
     */
    public function add($data)
    {
        $data['IpCreated'] = $_SERVER['REMOTE_ADDR'];
        $data['DateCreated'] = date("Y-m-d H:i:s");
        $data['IpModified'] = $_SERVER['REMOTE_ADDR'];
        $data['DateModified'] = date("Y-m-d H:i:s");
        $data['UserCreated'] = '';
        $data['UserModified'] = '';
        $data = $this->filterData($data);
        $this->db->insert($this->_table, $data);
        return $this->db->Insert_ID();
    }

    /**
     * Function to update data
     * 
     * @param type $data
     * @param type $rID
     */
    public function update($data, $id)
    {
        $data['IpModified'] = $_SERVER['REMOTE_ADDR'];
        $data['DateModified'] = date("Y-m-d H:i:s");

        $data = $this->filterData($data);

        $this->db->update($this->_table, $data, [$this->_primary_key => $id]);
        return $id;
    }

    /**
     * Function to delete data
     * 
     * @param type $rID
     */
    public function delete($id)
    {
        $this->db->delete($this->_table, [$this->_primary_key => $id]);
    }

    /**
     * Function to filter the data
     * 
     * @param type $data
     * @return type
     */
    public function filterData($data)
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, $this->fillable)) {
                unset($data[$key]);
            }
            else if (is_array($this->date_fields) && in_array($key, $this->date_fields)) {
                if (strtotime($data[$key]) > 0) {
                    $data[$key] = date("Y-m-d H:i:s", strtotime($data[$key]));
                }
                else {
                    unset($data[$key]);
                }
            }
        }
        return $data;
    }

    /**
     * Function to find the data
     *
     * @param int $id
     * $return array
     */
    public function find($id)
    {
        return $this->db->GetRow("Select * from   $this->_table where  $this->_primary_key=" . $id);
    }

}
