<?php

namespace Project\CLI\Model;

use Phalcon\Mvc\Model;

/**
 * Class SchemaVersion
 * @package Project\CLI\Model
 * @method static SchemaVersion|false findFirstByVersion(string $version)
 */
class SchemaVersion extends Model
{
    /** @var string */
    private $version;

    /**
     * @return array
     */
    public function columnMap(): array
    {
        return [
            'version' => 'version'
        ];
    }

    /**
     * @return string
     */
    public function version(): string
    {
        return $this->version;
    }
}
