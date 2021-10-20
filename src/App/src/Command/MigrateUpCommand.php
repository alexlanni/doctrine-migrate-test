<?php

namespace App\Command;

use Doctrine\ORM\EntityManager;
use Laminas\Cli\Command\AbstractParamAwareCommand;
use Laminas\Cli\Input\StringParam;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class MigrateUpCommand extends AbstractParamAwareCommand
{

    /** @var string */
    public static $defaultName = 'migrate:up';

    /** @var array  */
    private $migrateConfig = [];

    private $currentSchema = null;

    private EntityManager $entityManager;

    private $migrateFilesPath = __DIR__ . '/migrations/';


    public function __construct(
        array $migrateConfig,
        EntityManager $entityManager,
        string $migrateFilesPath = null
    ) {
        $this->migrateConfig = $migrateConfig;
        $this->entityManager = $entityManager;

        if($migrateFilesPath != null) {
            $this->migrateFilesPath = $migrateFilesPath;
        }

        parent::__construct(self::$defaultName);
    }


    protected function configure() : void
    {
        $this->setName(self::$defaultName);
        $this->addParam(
            (new StringParam('schema'))
                ->setDescription('Database Schema to Migrate UP')
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->currentSchema = $input->getParam('schema');

        if(!isset($this->migrateConfig[$this->currentSchema])){
            $output->writeln("Nessuna configurazione per lo schema $this->currentSchema");
            return 1;
        }

        $dbconnection = $this->entityManager->getConnection();

        // Caricamento Current Version
        $currentVersionStmt = $dbconnection->prepare(
            "SELECT to_version FROM db_migration ORDER BY last_update DESC LIMIT 1"
        );

        $fromVersion = 'v0';

        try{
            $result = $currentVersionStmt->executeQuery();
            $rows = $result->fetchAllAssociative();

            if(count($rows) != 1){
                $output->writeln("Errore nell'esecuzione della migrazione:");
                $output->writeln("Impossibile ottenere la versione corrente.");
                return 1;
            }

            $fromVersion = $rows[0]['to_version'];
        }catch (\Exception $e) {
            if(strstr($e->getMessage(),'Base table or view not found') === false){
                $output->writeln("Errore nell'esecuzione della migrazione:");
                $output->writeln($e->getMessage());
                return 1;
            }
        }

        $migrateCnf = $this->migrateConfig[$this->currentSchema];

        $fromIdx = array_keys($migrateCnf, $fromVersion);
        if(count($fromIdx) != 1){
            $output->writeln("Errore nel riconoscimento della versione iniziale");
            return 1;
        }
        $fromId = $fromIdx[0];

        if(!isset($migrateCnf[$fromId+1])){
            $output->writeln("Il DB e' aggiornato");
            return 1;
        }

        $toVersion = $migrateCnf[$fromId+1];

        $fileMigrate = $this->currentSchema .
                                '_' .
                                $this->sanitizeVersion($fromVersion) .
                                '_' .
                                $this->sanitizeVersion($toVersion) .
                                '.sql';


        if(!is_file($this->migrateFilesPath . $fileMigrate)){
            $output->writeln("Script di migrazione $fileMigrate non trovato!");
            return 1;
        }

        $output->writeln("Script di migrazione $fileMigrate individuato");

        $dbconnection->beginTransaction();
        try{
            $sql = trim(file_get_contents($this->migrateFilesPath . $fileMigrate));
            $stmt = $dbconnection->prepare($sql);
            $stmt->executeStatement();

            $output->writeln("Migrazione eseguita con successo. Da $fromVersion a $toVersion");
            return 0;
        } catch (\Exception $e) {
            $dbconnection->rollBack();
            $output->writeln("ERRORE ESECUZIONE SCRIPT DI MIGRAZIONE");
            $output->writeln($e->getMessage());
            return 1;
        }

    }

    private function sanitizeVersion($version) {
        return str_replace('.','-',$version);
    }

}