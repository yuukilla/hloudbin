<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class BakeCommand extends Command
{
    protected function configure(): void
    {
        parent::configure();

        $this->setName('bake');
        $this->setDescription('Bake new application components');
        $this->addArgument('name', InputArgument::REQUIRED, "Name of action");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bakeAction($input->getArgument('name'));

        return 1;
    }

    private function bakeAction(string $name)
    {
        $name = ucfirst($name);
        $code = [
            '<?php',
            '',
            'namespace App\Action\\' . $name . ';',
            '',
            'use Psr\Http\Message\ResponseInterface;',
            'use Psr\Http\Message\ServerRequestInterface;',
            '',
            'final class ' . $name . 'Action',
            '{',
            '    public function __invoke(ServerRequestInterface $reuqest, ResponseInterface $response): ResponseInterface',
            '    {',
            '        $response->getBody()->write(\''. $name .'Action\');',
            '    }',
            '}',
            ''
        ];

        if (!$this->pathExists(__DIR__ . '/../Action/' . $name)) {
            mkdir(__DIR__ . '/../Action/' . $name);
        }

        file_put_contents(
            __DIR__ . '/../Action/'.$name.'/'.$name.'Action.php',
            implode("\n", $code)
        );

        return 1;


    }

    private function pathExists(string $path): bool
    {
        return is_dir($path);
    }
}