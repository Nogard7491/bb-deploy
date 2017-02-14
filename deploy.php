<?php

/**
 * Класс Deploy
 *
 * Класс реализует функционал автоматического файлов при изменении ветки репозитория с Bitbucket.
 *
 * Для обновления файлов используются команды:
 *     1. git reset --hard HEAD
 *     2. git pull
 */
class Deploy
{
    /**
     * @const string секретный ключ
     */
    const SECRET = '';

    /**
     * @const string имя репозитория
     */
    const REPOSITORY = '';

    /**
     * @const string имя ветки
     */
    const BRANCH = 'master';

    /**
     * @const string имя удалённого репозитория (по умолчанию)
     */
    const REMOTE = 'origin';

    /**
     * @const путь к файлу с репозиторием
     */
    const REPOSITORY_ROOT_PATH = '';

    /**
     * @const string путь к файлу с логами
     */
    const LOG_ROOT_PATH = '';

    /**
     * @const string имя файла с логами
     */
    const LOG_FILENAME = '';

    /**
     * @const string формат записи даты
     */
    const LOG_DATE_FORMAT = 'd.m.Y H:i:s';

    /**
     * @var array массив данных с Bitbucket
     */
    public $payload;

    /**
     * Создаёт объект класса
     *
     * @param string $secret секретный ключ из GET запроса
     * @param array $payload массив данных с Bitbucket
     */
    public function __construct($secret, $payload)
    {
        $this->payload = json_decode($payload, true);
        $this->checkSecretOrDie($secret);
        $this->checkDataOrDie($this->payload);
    }

    /**
     * Выполняет обновление файлов на сервере.
     */
    public function execute()
    {
        $this->log('Началось обновление файлов на сервере...');
        try {
            $command = 'cd ' . self::REPOSITORY_ROOT_PATH;
            $command .= ' && git reset --hard HEAD';
            $command .= ' && git pull ' . self::REMOTE . ' ' . self::BRANCH;
            $result = shell_exec($command);
        } catch (Exception $ex) {
            $this->log('Ошибка: "Выполнение команды обновления файлов произошло с ошибкой".');
        }
        $this->log('Обновление файлов завершено с результатом: ' . $result);
    }

    /**
     * Логирует сообщение в файл.
     *
     * @param string $message сообщение
     */
    private function log($message)
    {
        $filePath = realpath(self::LOG_ROOT_PATH) . DIRECTORY_SEPARATOR . self::LOG_FILENAME;
        if (!file_exists($filePath)) {
            file_put_contents($filePath, '');
            chmod($filePath, 0666);
        }
        file_put_contents($filePath, date(self::LOG_DATE_FORMAT) . ' ' . $message . PHP_EOL, FILE_APPEND);
    }

    /**
     * Проверяет соответствие ключа из GET запроса с ключом константы SECRET.
     * В случае неудачи логирует ошибку и приостанавливает выполнение скрипта.
     *
     * @param string $secret секретный ключ
     */
    private function checkSecretOrDie($secret)
    {
        if (self::SECRET !== $secret) {
            $this->log('Ошибка: "Не совпадает секретный ключ".');
            die();
        }
    }

    /**
     * Проверяет корректность данных с Bitbucket.
     *
     * @param array $payload массив данных с Bitbucket
     */
    private function checkPayloadOrDie($payload)
    {
        $dieFlag = false;

        if (empty($payload)) {
            $dieFlag = true;
            $this->log('Ошибка: "Массив данных с Bitbucket пуст".');
        }

        if (self::REPOSITORY !== $payload['repository']['name']) {
            $dieFlag = true;
            $this->log('Ошибка: "Не совпадает название репозитория".');
        }

        if (self::BRANCH !== $payload['push']['changes']['new']['name']) {
            $dieFlag = true;
            $this->log('Ошибка: "Не совпадает название ветки".');
        }

        if ($dieFlag == true) {
            die();
        }
    }
}

date_default_timezone_set('Europe/Moscow');

$secret = $_GET['secret'];
$payload = file_get_contents('php://input');

$deploy = new Deploy($secret, $payload);
$deploy->execute();