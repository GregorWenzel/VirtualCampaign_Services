<?php
require_once(__DIR__ . '/base.php');

class SettingsApp extends BaseApp
{
    protected function _getSettingsData()
    {
        $query = "SELECT * FROM settings WHERE name IN ('manager_lastUpdate', 'manager_status', 'manager_message')";
        return $this->connection->fetchRowMany($query);
    }

    protected function _getOpenedProductions()
    {
        $query = "SELECT
            production.*,
            film.name,
            film.account_id,
            film.audio_id,
            film.url_hash,
            film.custom3,
            film.formats,
            production.update_ts as production_update_ts,
            account_group.indicative,
            account_group.abdicative,
            account.login_username AS username
        FROM production, film, account, account_group
        WHERE production.status < 8
          AND film.film_id = production.film_film_id
          AND account.account_id = film.account_id
          AND account_group.account_group_id = account.account_group_id
          AND production.delete_ts IS NULL
          ORDER BY production.update_ts DESC";

        return $this->connection->fetchRowMany($query);
    }

    protected function _getFinishedProductions()
    {
        $query = "SELECT
            production.*
            ,film.name
            ,film.account_id
            ,film.audio_id
            ,film.custom3
            ,film.formats
            ,film.url_hash
            ,production.update_ts as production_update_ts
            ,account_group.indicative
            ,account_group.abdicative
            ,account.login_username AS username
             FROM production, film, account, account_group
             WHERE production.status >= 7 AND error_code = 0
             AND film.film_id = production.film_film_id
             AND account.account_id = film.account_id
             AND account_group.account_group_id = account.account_group_id
             ORDER BY production.update_ts DESC";
        return $this->connection->fetchRowMany($query);
    }

    public function getSettings()
    {
        $query = "SELECT * FROM settings";
        $settings = $this->connection->fetchRowMany($query);

        $this->app->render('settings.php', [
            'settings' => $settings,
            'hash' => HASH,
        ]);
    }

    public function updateSettings($data)
    {
        $conds = ['settings_id' => $data['id']];
        $update = ['value' => $data['value']];
        if (isset($data['null']) && $data['null']) {
            $update = ['value' => null];
        }

        $this->connection->update('settings', $conds, $update);
        $this->app->redirect("/settings?hash=" . HASH);
    }

    public function getStat()
    {
        $settings = $this->_getSettingsData();
        $openedProductions = $this->_getOpenedProductions();
        $finishedProductions = $this->_getFinishedProductions();

        $status = false;
        $message = '';
        $update = 0;
        foreach ($settings as $item) {
            if ($item['name'] == 'manager_message') {
                $message = $item['value'];
            } else if ($item['name'] == 'manager_status') {
                $status = (bool)$item['value'];
            } else if ($item['name'] == 'manager_lastUpdate') {
                $update = strtotime($item['value']);
            }
        }

        $this->app->render('stat.php', [
            'status' => $status,
            'message' => $message,
            'update' => $update,
            'openedProductions' => $openedProductions,
            'finishedProductions' => $finishedProductions
        ]);
    }
}