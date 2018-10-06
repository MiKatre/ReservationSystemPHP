<?php

namespace App\Responder;

use App\Entity\Order;
use App\Utils\PriceControl;
use Symfony\Component\HttpFoundation\JsonResponse;

class TicketResponder
{
  private $price;

  public function __construct(PriceControl $price){
    $this->price = $price;
  }

  /**
   * @param Order $order
   * @return JsonResponse
   */
  public function addTicketRes(Order $order)
  {
    $tickets = [];

    foreach ($order->getTickets() as $singleTicket){
      $dateOfBirth = $singleTicket->getDateOfBirth();
      $object = (object) [
        'id' => $singleTicket->getId(),
        'firstName' => $singleTicket->getFirstName(),
        'lastName' => $singleTicket->getLastName(),
        'dateOfBirth' => $dateOfBirth,
        'price' => $this->price->getPriceHT($dateOfBirth, $singleTicket->getDiscount(), $singleTicket->getIsFullDay())->price,
        'priceName' => $this->price->getPriceHT($dateOfBirth, $singleTicket->getDiscount(), $singleTicket->getIsFullDay())->name,
        'isFullDay' => $singleTicket->getIsFullDay(),
      ];
      $tickets[] = $object;
    }

    $response = new JsonResponse(array(
      'success' => true,
      'tickets' => $tickets,
      'totalHT' => $this->price->getTotalHT($order->getTickets()),
      'totalTTC' => $this->price->getTotalTTC($order->getTickets()),
      'TVA' => PriceControl::TVA,
      'message' => 'Billet ajouté',
    ));

    return $response;
  }

  /**
   * @param Order $order
   * @return JsonResponse
   */
  public function getTicketsRes(Order $order){
      $tickets = [];

      foreach ($order->getTickets() as $singleTicket){
          $dateOfBirth = $singleTicket->getDateOfBirth();
          $object = (object) [
              'id' => $singleTicket->getId(),
              'firstName' => $singleTicket->getFirstName(),
              'lastName' => $singleTicket->getLastName(),
              'dateOfBirth' => $dateOfBirth,
              'price' => $this->price->getPriceHT($dateOfBirth, $singleTicket->getDiscount(), $singleTicket->getIsFullDay())->price,
              'priceName' => $this->price->getPriceHT($dateOfBirth, $singleTicket->getDiscount(), $singleTicket->getIsFullDay())->name,
              'isFullDay' => $singleTicket->getIsFullDay(),
          ];
          $tickets[] = $object;
      }

      $response = new JsonResponse(array(
          'success' => true,
          'tickets' => $tickets,
          'totalHT' => $this->price->getTotalHT($order->getTickets()),
          'totalTTC' => $this->price->getTotalTTC($order->getTickets()),
          'TVA' => PriceControl::TVA,
      ));

      return $response;
  }

  /**
   * @param $remaining
   * @return JsonResponse
   */
  public function remainingTicketsRes($remaining){
      $response = new JsonResponse(array(
          'success' => true,
          'message' => 'X Billets restants le ' . $remaining->date,
          'remaining' => $remaining->remaining,
      ));
      return $response;
  }

  /**
   * @return JsonResponse
   */
  public function removeTicketRes(){
      $response = new JsonResponse(array(
          'success' => true,
          'message' => 'Billet supprimé',
      ));
      return $response;
  }
}

