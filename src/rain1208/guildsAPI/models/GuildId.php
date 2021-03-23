<?php


namespace rain1208\guildsAPI\models;


class GuildId
{
    const NO_GUILD = -1;

    private int $value;

    public function __construct(int $id)
    {
        $this->value = $id;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param GuildId $other
     * @return bool
     */
    public function equals(GuildId $other): bool
    {
        if ($this->value == self::NO_GUILD || $other->value == self::NO_GUILD)
            return false;

        return $this->value == $other->value;
    }
}