<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Setup\Patch\Data;

use AdeoWeb\WeatherApplication\Model\Attribute\Source\WeatherTypeAttribute;
use Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\Product;

class ProductWeatherTypeAttribute implements DataPatchInterface, PatchRevertableInterface
{
    private const ATTRIBUTE_WEATHERTYPE = 'product_weathertype';
    private const PARAM_SETUP           = 'setup';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create([self::PARAM_SETUP => $this->moduleDataSetup]);

        $eavSetup->addAttribute(Product::ENTITY, self::ATTRIBUTE_WEATHERTYPE, [
            'type' => 'text',
            'label' => 'WeatherType',
            'input' => 'select',
            'source' => WeatherTypeAttribute::class,
            'frontend' => '',
            'required' => false,
            'backend' => ArrayBackend::class,
            'sort_order' => '30',
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            'default' => null,
            'visible' => true,
            'user_defined' => true,
            'searchable' => true,
            'filterable' => true,
            'comparable' => true,
            'visible_on_front' => true,
            'unique' => false,
            'apply_to' => 'simple,grouped,bundle,configurable,virtual',
            'group' => 'General',
            'used_in_product_listing' => true,
            'is_used_in_grid' => true,
            'is_visible_in_grid' => true,
            'is_filterable_in_grid' => true,
            'option' => ''
        ]);
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create([self::PARAM_SETUP => $this->moduleDataSetup]);
        $eavSetup->removeAttribute(Product::ENTITY, self::ATTRIBUTE_WEATHERTYPE);
        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
