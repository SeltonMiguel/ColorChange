<?php
namespace Vendor\ColorChange\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class ColorChangeCommand extends Command
{
    const COMMAND_NAME = 'color:change';

    private $state;
    private $storeManager;
    private $blockFactory;
    private $filesystem;

    public function __construct(
        State $state,
        StoreManagerInterface $storeManager,
        BlockFactory $blockFactory,
        DirectoryList $directoryList,
        string $name = null
    ) {
        parent::__construct($name);
        $this->state = $state;
        $this->storeManager = $storeManager;
        $this->blockFactory = $blockFactory;
        $this->filesystem = $directoryList;
    }

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Change button color for a specific store view.')
            ->setDefinition([
                new InputOption('hex_color', null, InputOption::VALUE_REQUIRED, 'Hex color code'),
                new InputOption('store_id', null, InputOption::VALUE_REQUIRED, 'Store view ID'),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $hexColor = $input->getOption('hex_color');
        $storeId = $input->getOption('store_id');

        try {
            $this->state->setAreaCode('adminhtml');
        } catch (\Exception $e) {
        }

        $this->storeManager->setCurrentStore($storeId);

        $blockIdentifier = 'button_block';
        $block = $this->blockFactory->create();
        $block->load($blockIdentifier);

        if (!$block->getId()) {
            $output->writeln("Error: Block with identifier $blockIdentifier not found.");
            return;
        }

        $configPath = $this->filesystem->getPath('var') . '/color_change_config.xml';
        $this->saveColorConfig($configPath, $storeId, $hexColor);

        $output->writeln("Button color changed successfully for store view $storeId to #$hexColor.");
    }

    private function saveColorConfig($configPath, $storeId, $hexColor)
    {
        $config = simplexml_load_file($configPath) ?: new \SimpleXMLElement('<config/>');
        $storeConfig = $config->xpath("store[@id='$storeId']");

        if (!$storeConfig) {
            $storeConfig = $config->addChild('store');
            $storeConfig->addAttribute('id', $storeId);
        } else {
            $storeConfig = $storeConfig[0];
        }

        $storeConfig->color = $hexColor;

        $config->asXML($configPath);
    }
}
