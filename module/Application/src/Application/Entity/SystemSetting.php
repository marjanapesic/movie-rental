<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SystemSetting
 *
 * @ORM\Table(name="system_settings")
 * @ORM\Entity(repositoryClass="Application\Repository\SystemSetting")
 */
class SystemSetting
{
    /**
     * @var string
     *
     * @ORM\Column(name="setting_name", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $settingName;

    /**
     * @var string
     *
     * @ORM\Column(name="setting_value", type="string", length=255, nullable=true)
     */
    private $settingValue;



    public function __construct($settingName, $settingValue)
    {
        $this->settingName = $settingName;
        $this->settingValue = $settingValue;
    }


    /**
     * Get settingName
     *
     * @return string
     */
    public function getSettingName()
    {
        return $this->settingName;
    }

    /**
     * Set settingValue
     *
     * @param string $settingValue
     *
     * @return SystemSetting
     */
    public function setSettingValue($settingValue)
    {
        $this->settingValue = $settingValue;

        return $this;
    }

    /**
     * Get settingValue
     *
     * @return string
     */
    public function getSettingValue()
    {
        return $this->settingValue;
    }
}
