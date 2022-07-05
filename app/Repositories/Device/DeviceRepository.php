<?php

namespace Vanguard\Repositories\Device;

interface DeviceRepository
{
    public function paginate($perPage, $folder_id, $table_name, $search = null);
    public function all();
    public function find($id);
    public function create(array $data);
    public function create_device($data);
    public function update($id, $table_name, array $data);
    public function delete($project_id, $id);
    
}
