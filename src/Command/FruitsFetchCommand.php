<?php

namespace App\Command;

use App\Entity\Nutrition;
use App\Entity\Fruit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class FruitsFetchCommand extends Command
{
    private $entityManager;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('fruits:fetch')
            ->setDescription('Fetches data from the FruityVice API');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        try {
            // Create an instance of the HTTP client with SSL verification disabled
            $client = HttpClient::create([
                'verify_host' => false,
            ]);

            // Make a GET request to the FruityVice API
            $response = $client->request('GET', 'https://www.fruityvice.com/api/fruit/all');

            // Get the response content as a string
            $content = $response->getContent();

            // Decode the JSON string into an array of fruit data
            $fruitsData = json_decode($content, true);

            // Persist each fruit into the database
            foreach ($fruitsData as $fruitData) {
                $fruit = new Fruit();
                $fruit->setName($fruitData['name']);
                $fruit->setGenus($fruitData['genus']);
                $fruit->setFamily($fruitData['family']);
                $fruit->setOrder($fruitData['order']);

                // Create an instance of Nutrition for the current fruit
                $nutrition = new Nutrition();
                $nutrition->setCarbohydrates($fruitData['nutritions']['carbohydrates']);
                $nutrition->setProtein($fruitData['nutritions']['protein']);
                $nutrition->setFat($fruitData['nutritions']['fat']);
                $nutrition->setCalories($fruitData['nutritions']['calories']);
                $nutrition->setSugar($fruitData['nutritions']['sugar']);

                // Set the Nutrition for the current fruit
                $fruit->setNutrition($nutrition);

                $this->entityManager->persist($fruit);
            }

            // Flush the changes to the database
            $this->entityManager->flush();

            $io->success(sprintf('%d fruits were fetched and saved into the database.', count($fruitsData)));
        } catch (ClientException $e) {
            $io->error(sprintf('Error fetching fruits from API: %s', $e->getMessage()));
            return Command::FAILURE;
        }

        // Send an email to notify that the fruits have been fetched and saved
        $message = (new Email())
            ->from(new Address('mediathequeBaniMakada@gmail.com', 'Fruits Fetch'))
            ->to(new Address('himmiyounes99@gmail.com', 'Younes Himmi')) //Change this to your gmail and your name
            ->subject('Fruits have been fetched and saved')
            ->html(
                '<html>'.
                '<body style="font-family: Arial, sans-serif; font-size: 16px;">'.
                    '<div style="background-color: #f2f2f2; padding: 20px;">'.
                        '<h1 style="color: #4CAF50;">Fruits have been fetched and saved!</h1>'.
                        sprintf('<p>%d fruits have been fetched and saved into the database.</p>', count($fruitsData)).
                    '</div>'.
                '</body>'.
                '</html>'
            );

        try {
            $this->mailer->send($message);
            $output->writeln(sprintf('Notification email was sent.'));
        } catch (\Exception $e) {
            $output->writeln(sprintf('An error occurred while sending the notification email: %s', $e->getMessage()));
        }
    
        return Command::SUCCESS;
    }
}