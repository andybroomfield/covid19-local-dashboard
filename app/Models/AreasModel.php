<?php namespace App\Models;

use CodeIgniter\Model;

class AreasModel extends Model
{
  protected $table = 'areas';

  protected $allowedFields = ['name', 'type', 'code'];

  public function updateOrInsert($values)
  {
    $name = $values['name'];
    $type = $values['type'];
    $existing_area = $this->asArray()
                          ->where('name', $name)
                          ->where('type', $type)
                          ->first();
    if (empty($existing_area))
    {
      // Insert
      $this->insert($values);
      $id = $this->db->insertId();
    } else
    {
      // Update
      $id = $existing_area['id'];
      $this->update($id, $values);
    }
    return $id;
  }

  public function search($type, $search)
  {
    $this->asArray();
    if (!empty($type))
    {
      $this->where('type', $type);
    }
    if (!empty($search))
    {
      $this->like('name', $search, 'both', NULL, true);
    }
    $result = $this->findAll();
    return $result;
  }
}
