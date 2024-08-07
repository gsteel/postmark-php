<?php

declare(strict_types=1);

namespace Postmark\Models;

use JsonSerializable;

final class Header implements JsonSerializable
{
    /**
     * @param non-empty-string $name
     * @param scalar|null      $value
     */
    public function __construct(private readonly string $name, private $value) // phpcs:ignore
    {
    }

    /** @return array{Name: non-empty-string, Value: scalar|null} */
    public function jsonSerialize(): array
    {
        return [
            'Name' => $this->name,
            'Value' => $this->value,
        ];
    }

    /**
     * @param array<string, scalar|null>|array<array-key, Header> $values
     *
     * @return list<self>|null
     */
    public static function listFromArray(array|null $values): array|null
    {
        if ($values === [] || $values === null) {
            return null;
        }

        $list = [];
        foreach ($values as $name => $value) {
            if ($value instanceof self) {
                $list[] = $value;
                continue;
            }

            $name = (string) $name;

            if ($name === '') {
                continue;
            }

            $list[] = new self($name, $value);
        }

        return $list;
    }
}
