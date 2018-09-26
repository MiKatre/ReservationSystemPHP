<?php

namespace App\Command;


use App\Entity\Order;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Doctrine\ORM\EntityManager;

class CleanDatabaseCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('app:clean-database')
      ->setDescription('Remove stalled orders from database')
      ->setHelp('This command allows you to remove all the unfinished orders that have been made, but not paid. Only for pasts days.')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
      $em = $this->getContainer()->get('doctrine.orm.entity_manager');
      $repository = $em->getRepository(Order::class);

      $stalledOrders = $repository->findStalledOrders(false, new \DateTime());

      if (count($stalledOrders) <= 0) {
        $output->writeln([
            '',
            'Everything clear!',
            '<info>No stalled order </>',
            '',
        ]);
        return;
      }

      foreach ($stalledOrders as $stalledOrder) {
        $output->write($stalledOrder->getFirstName());
        $output->writeln(' has not paid');
        $em->remove($stalledOrder);
      }

      $output->writeln(' ');

      $helper = $this->getHelper('question');
      $question = new ConfirmationQuestion('<info>Erase ' . count($stalledOrders) . ' orders ? (y/n)</> ', false);

      if (!$helper->ask($input, $output, $question)) {
        return;
      }

      $em->flush();

      $output->writeln([
          '',
          'Whoa!',
          '<info>' . count($stalledOrders) . ' items cleared </>',
          '',
      ]);
  }
}