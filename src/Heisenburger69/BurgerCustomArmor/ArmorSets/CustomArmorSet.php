<?php

namespace Heisenburger69\BurgerCustomArmor\ArmorSets;

use Heisenburger69\BurgerCustomArmor\Abilities\ArmorAbility;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\item\LeatherBoots;
use pocketmine\item\LeatherCap;
use pocketmine\item\LeatherPants;
use pocketmine\item\LeatherTunic;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\utils\Color;
use pocketmine\utils\TextFormat as C;

class CustomArmorSet
{
    public const TIER_DIAMOND = 5;
    public const TIER_IRON = 4;
    public const TIER_GOLD = 3;
    public const TIER_CHAIN = 2;
    public const TIER_LEATHER = 1;

    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $tier;
    /**
     * @var bool
     */
    private $glint;
    /**
     * @var ArmorAbility[]
     */
    private $abilities;
    /**
     * @var Color
     */
    private $color;
    /**
     * @var array
     */
    private $strength;
    /**
     * @var array
     */
    private $names;
    /**
     * @var array
     */
    private $lores;
    /**
     * @var array
     */
    private $setBonusLore;

    /**
     * CustomArmorSet constructor.
     * @param string $name
     * @param int $tier
     * @param bool $glint
     * @param array $abilities
     * @param Color $color
     * @param array $strength
     * @param array $names
     * @param array $lores
     * @param array $setBonusLore
     */
    public function __construct(string $name, int $tier, bool $glint, array $abilities, Color $color, array $strength, array $names, array $lores, array $setBonusLore)
    {
        $this->name = $name;
        $this->tier = $tier;
        $this->glint = $glint;
        $this->abilities = $abilities;
        $this->color = $color;
        $this->strength = $strength;
        $this->names = $names;
        $this->lores = $lores;
        $this->setBonusLore = $setBonusLore;
    }

    /**
     * @return Item[]
     */
    public function getSetPieces(): array
    {
        $pieces = [
            $this->getHelmet(),
            $this->getChestplate(),
            $this->getLeggings(),
            $this->getBoots()
        ];
        return $pieces;
    }

    public function getHelmet(): Item
    {
        $item = ArmorSetUtils::getHelmetFromTier($this->tier);
        $item->setCustomName(C::RESET . C::colorize($this->names["helmet"]));

        if ($this->glint) $item->setNamedTagEntry(new ListTag("ench"));
        $item->setNamedTagEntry(new StringTag("burgercustomarmor", $this->name));

        $lore = ArmorSetUtils::getHelmetLore($this->lores, $this->setBonusLore);
        $item->setLore($lore);

        if($item instanceof LeatherCap) {
            $item->setCustomColor($this->color);
        }

        return $item;
    }

    public function getChestplate(): Item
    {
        $item = ArmorSetUtils::getChestplateFromTier($this->tier);
        $item->setCustomName(C::RESET . C::colorize($this->names["chestplate"]));

        if ($this->glint) $item->setNamedTagEntry(new ListTag("ench"));
        $item->setNamedTagEntry(new StringTag("burgercustomarmor", $this->name));

        $lore = ArmorSetUtils::getChestplateLore($this->lores, $this->setBonusLore);
        $item->setLore($lore);

        if($item instanceof LeatherTunic) {
            $item->setCustomColor($this->color);
        }

        return $item;
    }

    public function getLeggings(): Item
    {
        $item = ArmorSetUtils::getLeggingsFromTier($this->tier);
        $item->setCustomName(C::RESET . C::colorize($this->names["leggings"]));

        if ($this->glint) $item->setNamedTagEntry(new ListTag("ench"));
        $item->setNamedTagEntry(new StringTag("burgercustomarmor", $this->name));

        $lore = ArmorSetUtils::getLeggingsLore($this->lores, $this->setBonusLore);
        $item->setLore($lore);

        if($item instanceof LeatherPants) {
            $item->setCustomColor($this->color);
        }

        return $item;
    }

    public function getBoots(): Item
    {
        $item = ArmorSetUtils::getBootsFromTier($this->tier);
        $item->setCustomName(C::RESET . C::colorize($this->names["boots"]));

        if ($this->glint) $item->setNamedTagEntry(new ListTag("ench"));
        $item->setNamedTagEntry(new StringTag("burgercustomarmor", $this->name));

        $lore = ArmorSetUtils::getBootsLore($this->lores, $this->setBonusLore);
        $item->setLore($lore);

        if($item instanceof LeatherBoots) {
            $item->setCustomColor($this->color);
        }

        return $item;
    }

    /**
     * @param EntityDamageEvent $event
     * @return float
     */
    public function getArmorModifierPoints(EntityDamageEvent $event): float
    {
        return
            $this->getHelmetModifier($event) +
            $this->getChestplateModifier($event) +
            $this->getLeggingsModifier($event) +
            $this->getBootsModifier($event);
    }

    /**
     * @param EntityDamageEvent $event
     * @return float
     */
    public function getHelmetModifier(EntityDamageEvent $event): float
    {
        $itemPoints = ArmorSetUtils::getHelmetFromTier($this->tier)->getDefensePoints();
        if(isset($this->strength["helmet"])) {
            $itemPoints = $this->strength["helmet"];
        }
        $totalPoints = ArmorSetUtils::getTotalStrengthPoints($this->tier, $this->strength);

        return -$event->getFinalDamage() * $itemPoints * 1 / $totalPoints;
    }

    /**
     * @param EntityDamageEvent $event
     * @return float
     */
    public function getChestplateModifier(EntityDamageEvent $event): float
    {
        $itemPoints = ArmorSetUtils::getChestplateFromTier($this->tier)->getDefensePoints();
        if(isset($this->strength["chestplate"])) {
            $itemPoints = $this->strength["chestplate"];
        }
        $totalPoints = ArmorSetUtils::getTotalStrengthPoints($this->tier, $this->strength);

        return -$event->getFinalDamage() * $itemPoints * 1 / $totalPoints;
    }

    /**
     * @param EntityDamageEvent $event
     * @return float
     */
    public function getLeggingsModifier(EntityDamageEvent $event): float
    {
        $itemPoints = ArmorSetUtils::getLeggingsFromTier($this->tier)->getDefensePoints();
        if(isset($this->strength["leggings"])) {
            $itemPoints = $this->strength["leggings"];
        }
        $totalPoints = ArmorSetUtils::getTotalStrengthPoints($this->tier, $this->strength);

        return -$event->getFinalDamage() * $itemPoints * 1 / $totalPoints;
    }

    /**
     * @param EntityDamageEvent $event
     * @return float
     */
    public function getBootsModifier(EntityDamageEvent $event): float
    {
        $itemPoints = ArmorSetUtils::getBootsFromTier($this->tier)->getDefensePoints();
        if(isset($this->strength["boots"])) {
            $itemPoints = $this->strength["boots"];
        }
        $totalPoints = ArmorSetUtils::getTotalStrengthPoints($this->tier, $this->strength);

        return -$event->getFinalDamage() * $itemPoints * 1 / $totalPoints;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getTier(): int
    {
        return $this->tier;
    }

    /**
     * @return bool
     */
    public function isGlint(): bool
    {
        return $this->glint;
    }

    /**
     * @return ArmorAbility[]
     */
    public function getAbilities(): array
    {
        return $this->abilities;
    }

    /**
     * @return Color
     */
    public function getColor(): Color
    {
        return $this->color;
    }

    /**
     * @return array
     */
    public function getStrength(): array
    {
        return $this->strength;
    }

    /**
     * @return array
     */
    public function getNames(): array
    {
        return $this->names;
    }

    /**
     * @return array
     */
    public function getLores(): array
    {
        return $this->lores;
    }

    /**
     * @return array
     */
    public function getSetBonusLore(): array
    {
        return $this->setBonusLore;
    }


}