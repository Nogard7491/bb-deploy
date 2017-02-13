<?php

/**
 * Класс Deploy
 *
 * ...
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
     * Конструктор класса
     *
     * ...
     */
    public function __construct($secret, $payload)
    {

    }

    /**
     * Выполняет обновление файлов на сервере.
     */
    public function execute()
    {

    }

    /**
     * Логирует сообщение в файл.
     *
     * @param string $message сообщение
     */
    private function log($message) {

    }

    /**
     * Проверяет соответствие ключа из GET запроса с ключом константы SECRET.
     * В случае неудачи логирует ошибку и приостанавливает выполнение скрипта.
     *
     * @param string $secret секретный ключ
     */
    private function checkSecretOrDie($secret) {

    }

    /**
     * Проверяет корректность данных с Bitbucket.
     *
     * @param array $payload массив данных с Bitbucket
     */
    private function checkPayloadOrDie($payload) {

    }
}

$secret = $_GET['secret'];
$payload = file_get_contents('php://input');

$deploy = new Deploy($secret, $payload);
$deploy->execute();