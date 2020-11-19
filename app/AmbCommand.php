<?php
namespace App;

use App\Traits\Helpers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class AmbCommand extends Command
{

    use Helpers;
    protected static $defaultName = 'config:run';
    protected $outputMsg = '';

    public function __construct()
    {
        parent::__construct();
    }
    // ...
    protected function configure()
    {
        $this->setDescription('Esse codigo cria um arquivo .conf na pasta /etc/apache2/sites-available/')
            ->setHelp('Cara ta na duvida ainda chama o Claudio que ele te ajuda');
    }

// ...
    public function execute(InputInterface $input, OutputInterface $output)
    {
        
        $this->outputMsg = $output;
        echo $this->exec_shell('clear');

        $output->writeln([
            '<fg=blue>INICIANDO SCRIPT</>',
            '============',
            '',
        ]);
            
        $helper = $this->getHelper('question');
        $question = new Question('Por favor digite o host do seu site ex.: (claudiodev.com.br) = ' , 'nao_informado');

        $hostName = $helper->ask($input, $output, $question);

        if($hostName == 'nao_informado'){
            $this->msg('- É Obrigatorio informar o host', 'red', true);
            return Command::FAILURE;
        }


        $question = new Question('Informe o PATH para o seu projeto ex.: (/var/www/meu_site) = ', 'nao_informado');

        $pathToProject = $helper->ask($input, $output, $question);

        if($pathToProject == 'nao_informado'){
            $this->msg('- É Obrigatorio informar o path do seu projeto', 'red', true);
            return Command::FAILURE;
        }

        $question = new Question('Para finalizar informe o nome para o arquivo .conf ex.: (meu_site) = ', 'nao_informado');

        $nameForFileConf = $helper->ask($input, $output, $question);

        if($nameForFileConf == 'nao_informado'){
            $this->msg('- É Obrigatorio informar o nome para o seu arquivo .conf', 'red', true);
            return Command::FAILURE;
        }

        $this->createFile($hostName, $pathToProject, $nameForFileConf);


        return Command::SUCCESS;
    }

    public function createFile($hostName, $pathToProject, $nameForFileConf)
    {
        $this->msg('- Carregando o arquivo de exemplo');

        $arquivoExemplo =  PATH_STOPRAGE.'/exemple.conf';

       

        $fn = fopen( $arquivoExemplo  ,"r");
        $text = '';
        dd($fn, $arquivoExemplo);

        while(!feof($fn))  {
            $linha = fgets($fn);
       
            $text .= \str_replace([':hostname:', ':path_to_project:', ':name:'], [$hostName, $pathToProject, '\${APACHE_LOG_DIR}/'.$nameForFileConf], $linha);
        }
        fclose($fn);
        $this->msg('- Gravando o arquivo');

        $this->exec_shell('echo "'.$text.'" > '.PATH_SITES_AVAILABLE.'/'.$nameForFileConf.'.conf' );
        $this->msg('- Dando permicao do Apache');
        $this->exec_shell('chown -R www-data:www-data '. $pathToProject);
        $this->msg('- Regristando o site');
        $this->exec_shell('cd '.PATH_SITES_AVAILABLE.' && a2ensite '.$nameForFileConf );
        $this->msg('- Reiniciando o apache');
        $this->exec_shell('sudo service apache2 restart');
        $this->msg('- Agora pode acessar:');
        $this->msg('- http://'.$hostName);
        $this->msg('Obs: Caso seja um subdominio lembre de configurar ele, no meu caso eu uso a ');
       
       
    }



}
