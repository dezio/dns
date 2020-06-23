<?php
/**
 * File: DnsRecord.php
 * Created: Jun 2020
 * Project: trashmail-api
 */

namespace Spatie\Dns;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DnsRecord implements Arrayable {
    private $record;

    /**
     * @param $result
     * @return Collection|self[]
     */
    public static function fromDig($result) {
        return collect(explode("\n", $result))->filter(function ($r) {
            return strlen(trim($r));
        })->mapInto(self::class);
    }

    public function __construct($record) {
        $this->record = trim($record);
    }

    private function getPart($index) {
        if ($splitted = preg_split("/\s+/", $this->record)) {
            return @$splitted[$index];
        } // if end
        return null;
    }

    public function getName() {
        $name = $this->getPart(0);
        if (Str::endsWith($name, ".")) {
            $name = Str::substr($name, 0, -1);
        } // if end
        return $name;
    }

    public function getTtl() {
        return $this->getPart(1);
    }

    public function getClass() {
        return $this->getPart(2);
    }

    public function getType() {
        return $this->getPart(3);
    }

    public function getContent() {
        return $this->getPart(4);
    }

    public function toArray() {
        return [
            "name"    => $this->getName(),
            "ttl"     => $this->getTtl(),
            "class"   => $this->getClass(),
            "type"    => $this->getType(),
            "content" => $this->getContent()
        ];
    }
}
