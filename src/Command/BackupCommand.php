<?php

namespace Zz\BackupOMatic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
// use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Zz\BackupOMatic\Config\YamlConfig;
use Zz\BackupOMatic\BackupOMatic;
use Zz\BackupOMatic\Event\BackupOMaticProgressEvent;
use Symfony\Component\Console\Helper\ProgressBar;

class BackupCommand extends Command
{
    protected $backupOMatic;
    protected $progressBar;
    
    public function __construct ( $name = null ) {
        $this->backupOMatic = new BackupOMatic;
        
        parent::__construct($name);
    }
    
    protected function configure ()
    {
        $this
            ->setName('backup')
            ->setDescription('Back up your files')
            ->addArgument(
                'configPath',
                InputArgument::OPTIONAL,
                'Path of your backup\'s Yaml config'
            )
        ;
    }

    protected function execute ( InputInterface $input, OutputInterface $output )
    {
        $configPath = $input->getArgument('configPath');
        
        if ( $configPath === null ) {
            $configPath = 'backup.yml';
        }
        
        if ( !file_exists($configPath) ) {
            throw new \InvalidArgumentException('The config ' . $configPath . ' doesn\'t exist !');
        }
        
        $config = new YamlConfig (file_get_contents($configPath));
        
        $this->progressBar = new ProgressBar($output, count($config->getFiles()));
        $this->backupOMatic->getDispatcher()->addListener(BackupOMatic::PROGRESS_EVENT, [$this, 'onProgress']);
        
        $this->backupOMatic->backup($config);
        
        $this->progressBar->finish();
        $output->writeln('<info>Your backup has been done !</info>');
    }
    
    public function onProgress ( BackupOMaticProgressEvent $event ) {
        $this->progressBar->advance();
    }
}
