<?php

namespace app\models;

class Partner extends AppModel {

    public $attributes = [
        'name' => '',
        'alias' => '',        
        'type' => '',
        'inn' => '',
        'kpp' => '',
        'bank' => '',
        'bic' => '',
        'account' => '',
        'address' => '',
        'phone' => '',
        'email' => '',
        'delay' => '',
        'vat' => '',
    ];

    /**
     * Проверяет наличие имеющихся КА с такими inn или alias
     * @return bool TRUE если inn и alias свободны, и FALSE если заняты
     */
    public function checkUnique() {
        // попытаемся найти в БД пользователя с таким inn или alias
        $ka = \R::findOne('partner', 'inn = ? OR alias = ?', [$this->attributes['inn'], $this->attributes['alias']]);
        if ($ka) {
            // если нашли такую запись
            if ($ka->inn == $this->attributes['inn']) {
                // совпадает inn
                $this->errors['unique'][] = "C таким ИНН ($ka->inn) в БД существует КА ($ka->name)...";
            }
            if ($ka->alias == $this->attributes['alias']) {
                // совпадает alias
                $this->errors['unique'][] = "C таким номером ($ka->alias) в БД существует КА ($ka->name)...";
            }
            return false;
        }
        return true;
    }

    /**
     * Возвращает массив данных о КА
     * @param $name string наименование КА
     * @return mixed
     */
    public function getPartner($name) {
        $partner = \R::getAssocRow('SELECT * FROM partner WHERE name = ? LIMIT 1', [$name]);
        if (!empty($partner)) {
            return $partner[0];
        }
        return false;
    }

}
