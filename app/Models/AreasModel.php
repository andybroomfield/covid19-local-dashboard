<?php namespace App\Models;

use CodeIgniter\Model;

class AreasModel extends Model
{
  protected $table = 'areas';

  protected $allowedFields = ['name', 'slug', 'type', 'code'];

  public function updateOrInsert($values)
  {
    // Get name and type.
    $name = $values['name'];
    $type = $values['type'];

    // Create a slug if not degined.
    if (empty($values['slug']))
    {
      $values['slug'] = url_title($name, '-', TRUE);
    }

    // Check if exists.
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

  public function search($type = NULL, $search = NULL)
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
    $this->orderBy('name', 'asc');
    $result = $this->findAll();
    return $result;
  }

  public function getBySlug(string $type, string $slug)
  {
    $this->where('type', $type);
    $this->where('slug', $slug);
    $result = $this->first();
    return $result;
  }
}
