<?php
namespace Vendor\ColorChange\Plugin;

use Magento\Cms\Block\Block;
use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;

class BlockPlugin
{
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function beforeToHtml(Block $subject)
    {
        $buttonColor = $this->getButtonColor($subject);

        if ($buttonColor) {
            $subject->addData(['button_color' => $buttonColor]);
        }

        return null;
    }

    private function getButtonColor(Block $block)
    {
        $storeId = $block->getStoreId();
        $configPath = "button_block_config/store_$storeId/color"; // Adjust based on your configuration

        return $this->scopeConfig->getValue($configPath);
    }
}
