<?php


namespace rain1208\guildsAPI\models;


class GuildLevel
{
    private int $level;
    private int $exp;

    public function __construct(int $level, int $exp)
    {
        $this->level = $level;
        $this->exp = $exp;
    }

    public function levelUp()
    {
        $this->level++;
    }

    public function setExp(int $exp)
    {
        $this->exp = $exp;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getExp(): int
    {
        return $this->exp;
    }
}