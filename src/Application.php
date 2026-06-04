<?php

namespace src;

use src\Entity;

class Application extends Entity{
    protected string $tableName = 'application';
    public int $id;
    public int $user_id;
    public string $reason;
    public string $text;
    public string $date;
    public string $time;
    public string $status;

    public function validate(array $data){
        if(!isset($data['reason']) || mb_strlen($data['reason']) < 5){
            throw new \InvalidArgumentException('Поле должно содержать минимум 5 символов.');
        }
        if(empty($data['reason'])){
            throw new \InvalidArgumentException('Поле не должно быть пустым');
        }
        if(empty($data['text'])){
            throw new \InvalidArgumentException('Поле не должно быть пустым');
        }
        if(empty($data['date'])){
            throw new \InvalidArgumentException('Дата не должна быть пустой');
        }
        if(empty($data['time'])){
            throw new \InvalidArgumentException('Время не должно быть пустым');
        }
    }

    public function saveApplication(int $userId, array $data){
        $this->user_id = $userId;
        $this->reason = $data['reason'] ?? '';
        $this->text = $data['text']   ?? '';
        $this->date = $data['date']   ?? '';
        $this->time = $data['time']   ?? '';
        $this->status = 'new';

        $this->validate($data);

        $fields = [
            'user_id' => $this->user_id,
            'reason' => $this->reason,
            'text' => $this->text,
            'date' => $this->date,
            'time' => $this->time,
            'status' => $this->status,
        ];

        return $this->insert($fields);
    }
}