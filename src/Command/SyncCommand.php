<?php

namespace App\Command;

use App\Dto\ProductDto;
use App\Service\Product;
use GuzzleHttp\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:sync',
    description: 'Sync products'
)]
class SyncCommand extends Command
{
    protected $productService;
    const API_LINK = 'https://fakestoreapi.com/products';

    public function __construct(Product $productService, string $name = null)
    {
        parent::__construct($name);
        $this->productService = $productService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(['Started...']);
        $client = new Client();
        $response = $client->get(self::API_LINK);
        $content = $response->getBody()->getContents();
        $data = json_decode($content, true);

        $products = [];
        foreach ($data as $item) {
            $productDpo = new ProductDto();
            $productDpo->setName($item['title'] ?? '');
            $productDpo->setPrice((float)($item['price'] ?? 0));
            $productDpo->setDescription($item['description'] ?? '');
            $productDpo->setImageLink($item['image'] ?? '');
            $productDpo->setCategory($item['category'] ?? '');
            $products[] = $productDpo;
        }

        $this->productService->saveOrUpdate($products);

        $output->writeln(['End.']);
        return Command::SUCCESS;
    }
}
