<?php
require_once(__DIR__ . '/base.php');

class RenderManagerApp extends BaseApp
{
    public function getList()
    {
        $list = $this->_getItems();
        return $this->app->render('rendermanager.php', [
            'items' => $list,
        ]);
    }

    public function saveItem($data)
    {
        $id = $data['id'];
        unset($data['id']);
        unset($data['action']);
        unset($data['hash']);

        return $this->connection->update('rendermanager', [ "id" => $id ], $data);
    }

    public function forceActivate($data)
    {
        $id = $data['id'];
        $this->connection->update('rendermanager', [], [ "force_active" => 0 ]);
        return $this->connection->update('rendermanager', [ "id" => $id ], [ "force_active" => 1, "status" => "normal" ]);
    }

    protected function _getItems()
    {
        $query = "SELECT
            *
        FROM rendermanager as _rm
          ORDER BY _rm.heartbeat_ts DESC";

        return $this->connection->fetchRowMany($query);
    }
}
