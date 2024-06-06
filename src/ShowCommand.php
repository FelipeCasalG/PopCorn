<?php

namespace Acme;

use GuzzleHttp\ClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ShowCommand extends Command
{

    private $client;

    public function __construct(ClientInterface $client) {
        $this->client = $client;
        parent::__construct();
    }

    public function configure()
    {
        $this->setName('show')
            ->setDescription('Show movie information')
            ->addArgument('movieTitle', InputArgument::REQUIRED, 'Movie title')
            ->addOption('fullPlot', null, InputOption::VALUE_NONE, 'Show full plot');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $movieTitle = $input->getArgument('movieTitle');
        $isFullPlot = $input->getOption('fullPlot');
        $response = $this->client->request('GET', 'http://www.omdbapi.com/', [
            'query' => [
                'apikey' => 'e416bca5',
                't' => $movieTitle,
                'plot' => $isFullPlot ? 'full' : 'short'
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $table = new Table($output);
        $table->setHeaders([
            'Title',
            'Year',
            'Plot'
        ]);
        $table->addRow([
            $data['Title'],
            $data['Year'],
            $data['Plot']
        ]);
        $table->render();
    }
    
}
