<?php
namespace src;

class Feedback extends Entity
{
    protected string $tableName = 'feedback';
    protected string $fio = '';
    protected string $phone = '';
    protected string $text = '';
    protected array|string $image_file = '';
    protected string $create_at = '';
    protected string $agree = '';
    protected string $status = 'new';

    public function getFio(): string
    {
        return $this->fio;
    }

    public function loadFromForm(array $post, ?array $file = null): void
    {
        $data = [];
        $data['fio'] = $post['fio'] ?? '';
        $data['phone'] = $post['phone'] ?? '';
        $data['text'] = $post['text']  ?? '';
        $data['agree'] = !empty($post['agree']) ? '1' : '0';

        if ($file && isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
            $data['image_file'] = $file;
        }

        $this->load($data);
    }

    public function validate(): void
    {
        $this->fio = preg_replace('/\s+/u', ' ', trim($this->fio));

        if (empty($this->fio)) {
            throw new \InvalidArgumentException('Не передано ФИО');
        }
        if (empty($this->phone)) {
            throw new \InvalidArgumentException('Не передан телефон');
        }
        if (empty($this->text)) {
            throw new \InvalidArgumentException('Не передан текст отзыва');
        }
        if (!preg_match('/^[А-Яа-яЁё\s-]+$/u', $this->fio)) {
            throw new \InvalidArgumentException('ФИО только русские символы');
        }

        $parts = explode(' ', $this->fio);

        if (count($parts) < 2 || count($parts) > 3) {
            throw new \InvalidArgumentException('ФИО: Фамилия Имя (Отчество)');
        }
        if (!preg_match('/^\+7-\(\d{3}\)-\d{3}-\d{2}-\d{2}$/', $this->phone)) {
            throw new \InvalidArgumentException('Телефон: +7-(XXX)-XXX-XX-XX');
        }
        if (!empty($this->image_file) && is_array($this->image_file)) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $extension = strtolower(pathinfo($this->image_file['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedExtensions, true)) {
                throw new \InvalidArgumentException('Загружайте файлы в формате jpg, png либо gif');
            }
            if ($this->image_file['size'] > 5 * 1024 * 1024) {
                throw new \InvalidArgumentException('Слишком большой файл. Файл не должен превышать 5Мб.');
            }
        }
        if (empty($this->agree) || $this->agree !== '1') {
            throw new \InvalidArgumentException('Необходимо согласиться с ОПД');
        }
    }

    public function save(): bool
    {
        $uploadDir = dirname(__DIR__) . '/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!empty($this->image_file) && is_array($this->image_file)) {
            $fileName = $this->image_file['name'];
            $pathAbs = $uploadDir . $fileName;
            $pathRel = 'uploads/' . $fileName;

            if (!move_uploaded_file($this->image_file['tmp_name'], $pathAbs)) {
                throw new \InvalidArgumentException('Ошибка при загрузке файла');
            }
        } else {
            $pathRel = '';
        }

        $this->create_at = date('Y-m-d H:i:s');
        $fields = [
            'fio' => $this->fio,
            'phone' => $this->phone,
            'text' => $this->text,
            'image_file' => $pathRel,
            'create_at' => $this->create_at,
            'agree' => $this->agree,
            'status' => 'new',
        ];
        return $this->insert($fields);
    }

    public function findApproved(): array
    {
        $sql = 'SELECT * FROM ' . $this->tableName . " WHERE status = 'approved'";
        $result = $this->DB->querySQL($sql);
        if ($result === false) {
            return [];
        }
        return $result ?? [];
    }
}