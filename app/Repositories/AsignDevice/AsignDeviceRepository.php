<?php

namespace Vanguard\Repositories\AsignDevice;

interface AsignDeviceRepository
{
    public function paginate($perPage, $search = null);
    public function all();
    public function find($id);
    public function create($user_id, array $data);
    public function update($id, array $data);
    public function delete($id);
    public function paginateUser($perPage, $search = null);
}